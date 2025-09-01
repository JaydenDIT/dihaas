<?php

namespace App\Http\Controllers\Duties;

use App\Http\Controllers\Controller;
use App\Models\PostVaccancy;
use App\Models\Proforma;
use App\Models\Task;
use App\Models\UoGeneration;
use App\Models\User;
use App\Services\CmisApiService;
use App\Services\LogService;
use App\Services\WorkflowHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class UoGenerationController extends Controller
{
    public function index($tasks_id)
    {
        $task = Task::findOrFail($tasks_id);
        $departments = CmisApiService::apiFieldDepartments();
        return view('duties.uoFormGenerationList', compact('departments', 'task'));
    }

    public function ajaxlist(Request $request, $tasks_id)
    {
        $task = Task::findOrFail($tasks_id);

        $application_status = $request->input('application_status');

        switch ($application_status) {
            //proforma_status tells the current state of the application

            case 'pending': // currently pending on me
                $data = WorkflowHandler::proformaTaskCurrentData($task);        // collection where
                break;
            case 'forwarded': //forwarded from me but entire process not completed
                $data = WorkflowHandler::proformaTaskForwardedData($task);
                break;
            case 'completed': //forwarded from me but entire process not completed
                $data = WorkflowHandler::proformaTaskCompletedData($task);
                break;
            case 'rejected': //forwarded from me or rejected during me but entire process is rejected later
                $data = WorkflowHandler::proformaTaskRejectedData($task);
                break;
            default:
                return DataTables::of([])->make(true); // No data for other statuses
                break;
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('deceased_doe', function ($row) {
                return date('d M, Y', strtotime($row->deceased_doe));
            })
            ->editColumn('created_at', function ($row) {
                return date('d M, Y', strtotime($row->created_at));
            })
            ->editColumn('applicant_dob', function ($row) {
                return date('d M, Y', strtotime($row->applicant_dob));
            })
            ->addColumn('remarks', function ($row) {
                return $row->proformaLogs()
                    ->whereIn('action_name', ['forwarded', 'rejected', 'reverted', 'completed'])
                    ->latest()
                    ->value('action_remark') ?? 'N/A';
            })
            ->addColumn('action', function ($row) use ($application_status) {
                // $data = urlencode(json_encode($row));
                $resp = "<div class='d-flex gap-2'>";
                if ($application_status === 'pending') {
                    $resp .= "<a href='" . route('duties.uo.formgeneration.view', $row->proforma_id) . "' class='btn btn-sm btn-primary view-btn'>View</a>";
                    //$resp .= '#';
                } else {
                    $resp .= "<a href='" . route('duties.proforma.view', $row->proforma_id) . "' class='btn btn-sm btn-primary view-btn'>View</a>";
                }
                $resp .= "</div>";
                return $resp;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }



    public function view($id)
    {
        $proforma = Proforma::findOrFail($id);
        $this->authorize('canPerformOnProforma',  [$proforma, 'uo_form_generation']);

        $total_step = 4;
        $tasks = getPrevNextTasks($id);
        return view('duties.uoFormGeneration', compact('proforma', 'total_step', 'tasks'));
    }

    public function submit(Request  $request, $id)
    {
        //signed_proforma_doc
        try {
            DB::beginTransaction();

            $proforma = Proforma::findOrFail($id);

            //WorkflowHandler comes after LogService
            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => Auth::user()->user_id,
                'action_name' => 'completed',
                'action_remark' => $request->remarks ?? '',
                'process_sequence' => $proforma->process_sequence
            ]);

            /**
             * 1.   Get the UO Generation
             */
            $uoGeneration = UoGeneration::where('proforma_id', $proforma->proforma_id)->firstOrFail();

            /**
             * Here we will reduce the no of posts in Die-in-harness from post_vaccancies table             * 
             * */
            PostVaccancy::deductDiaPost($uoGeneration->alloted_field_dept_cd, $uoGeneration->alloted_dsg_srno);

            WorkflowHandler::forwardApplication($proforma);

            DB::commit();

            return response()->json(['message' => 'You have successfully submitted signed document.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error submitting signed UO document: ' . $e->getMessage()], 422);
        }
    }

    //method for loading proforma document
    public function loadProformaDocument($id)
    {
        $proforma = Proforma::with('UoGeneration', 'uoFileSubmission')->findOrFail($id);

        if (is_null($proforma->UoGeneration->signed_proforma_doc) || trim($proforma->UoGeneration->signed_proforma_doc == "")) {
            $pdf = Pdf::loadView('duties.pdfs.proforma-doc', compact('proforma'));

            //to display in browser:
            return $pdf->stream('proforma-doc.pdf');
        }

        // Stream the file instead of forcing download
        $filePath = storage_path('app/' . $proforma->UoGeneration->signed_proforma_doc);

        if (!file_exists($filePath)) {
            abort(404, 'Signed document not found.');
        }

        // Detect mime type
        $mimeType = mime_content_type($filePath);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $proforma->UoGeneration->uo_number . '"'
        ]);
    }

    //method to save esigned UO document
    public function storeEsignUODocument(Request $request)
    {
        $request->validate([
            'signed_UO_file' => 'required',
            'proforma_id' => ['required', 'exists:uo_generations,proforma_id'],
        ]);
        //getting esigned document in base64 format
        $signedBased64Doc = $request->post('signed_UO_file');
        $proforma_id = $request->post('proforma_id');

        //Here, convert the based64 encoded data to file and store.

        // get the storage path in the variable $signed_doc_path

        // decode base64 string (remove header if present)
        if (str_contains($signedBased64Doc, ',')) {
            $signedBased64Doc = explode(',', $signedBased64Doc)[1];
        }

        $decodedFile = base64_decode($signedBased64Doc);

        // create a unique filename
        $fileName = 'signed_uo_' . $proforma_id . '_' . time() . '.pdf';

        // define storage path
        $filePath = 'signed_uo_docs/' . $fileName;

        // store file inside storage/app/signed_uo_docs
        Storage::disk('local')->put($filePath, $decodedFile);

        // get the storage path for DB
        $signed_doc_path = $filePath;

        // update record

        UoGeneration::where('proforma_id', $proforma_id)->update([
            'signed_proforma_doc' => $signed_doc_path
        ]);

        return response()->json([
            'message' => 'You have successfully uploaded the signed document'
        ]);
    }
}
