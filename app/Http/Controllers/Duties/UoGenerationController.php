<?php

namespace App\Http\Controllers\Duties;

use App\Http\Controllers\Controller;
use App\Models\Proforma;
use App\Models\Task;
use App\Models\User;
use App\Services\CmisApiService;
use App\Services\LogService;
use App\Services\WorkflowHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $adminDepartments = CmisApiService::apiAdminDepartments();

        $adminDepts = [];
        foreach ($adminDepartments as $dept) {
            $adminDepts[$dept['adm_dept_cd']] = $dept['adm_dept_desc'];
        }

        $departments = CmisApiService::apiFieldDepartments();

        //Retrieving Nodal Officers for Department of personels for signing authority
        $dpNodals = $users = User::with('role')
            ->whereHas('role', function ($query) {
                $query->where('role_name', '=', 'DP Nodal');
            })->get();
        return view('duties.uoFormGeneration', compact('proforma', 'total_step', 'tasks', 'departments', 'dpNodals', 'adminDepts'));
    }

    public function submit(Request  $request, $id)
    {
        /*
        try {
            DB::beginTransaction();

            $proforma = Proforma::findOrFail($id);
           

            //WorkflowHandler comes after LogService
            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => Auth::user()->user_id,
                'action_name' => 'forwarded',
                'action_remark' => $request->remarks ?? '',
                'process_sequence' => $proforma->process_sequence
            ]);
            WorkflowHandler::forwardApplication($proforma);
            DB::commit();
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Proforma forwarded successfully.'], 200);
            }
            return redirect()->back()->with('success', 'Proforma forwarded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Error forwarding proforma: ' . $e->getMessage()], 422);
            }
            return redirect()->back()->with('error', 'Error forwarding proforma: ' . $e->getMessage());
        }
        */
        return response()->json([
            'message' => 'You have successfully submitted signed document.'
        ]);
    }

    //method for loading proforma document
    public function loadProformaDocument($id)
    {
        $proforma = Proforma::with('UoGeneration', 'uoFileSubmission')->findOrFail($id);

        /* return response()->json([
            'proforma' => $proforma
        ]); */
        $pdf = Pdf::loadView('duties.pdfs.proforma-doc', compact('proforma'));

        //to display in browser:
        return $pdf->stream('proforma-doc.pdf');

        // To directly download:
        //return $pdf->download('proforma_doc.pdf');

        /*
        $pdfContent = $pdf->output();
        // Return base64 encoded version
        $base64 = base64_encode($pdfContent);

        return response()->json([
            'base64' => $base64
        ]);*/
    }
}
