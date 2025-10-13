<?php

namespace App\Http\Controllers\Duties;

use App\Http\Controllers\Controller;
use App\Models\Proforma;
use App\Models\Remark;
use App\Models\Task;
use App\Models\UoFileSubmission;
use App\Services\CmisApiService;
use App\Services\LogService;
use App\Services\UOFileStorageService;
use App\Services\WorkflowHandler;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class UoFileSubmissionController extends Controller
{
    public function index(Request $request, $tasks_id)
    {

        $task = Task::findOrFail($tasks_id);
        $departments = CmisApiService::apiFieldDepartments();
        $view = $request->input('view', '');
        $remarks = Remark::orderBy('id')->get();
        return view('duties.uoFileSubmissionList', compact('departments', 'task', 'view', 'remarks'));
    }

    public function ajaxlist(Request $request, $tasks_id)
    {
        $task = Task::findOrFail($tasks_id);

        $application_status = $request->input('application_status');
        $overallSeniorityIndex = Proforma::getOverallSeniorityList();

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
            ->editColumn('proforma_submission_date', function ($row) {
                return date('d M, Y', strtotime($row->proforma_submission_date));
            })
            ->editColumn('applicant_dob', function ($row) {
                return date('d M, Y', strtotime($row->applicant_dob));
            })
            ->addColumn('pending_at', function ($row) {
                $tasks = getPrevNextTasks($row->proforma_id);
                if (isset($tasks['current']['tasks_id'])) {
                    $currentTask = Task::find($tasks['current']['tasks_id']);

                    //Here if currentTask is not null then we will return the user roles who can access this task
                    if ($currentTask) {
                        $roles = $currentTask->roles->pluck('role_name')->toArray();
                        return implode(" / ", $roles);
                    } else {
                        return 'N/A';
                    }
                }
                return 'N/A';
            })
            ->addColumn('remarks', function ($row) {

                $log = $row->proformaLogs()
                    ->whereIn('action_name', ['forwarded', 'rejected', 'reverted', 'completed'])
                    ->latest()
                    ->first();
                if ($log) {
                    return (object)[
                        'remark' => $log->action_remark,
                        'date' => $log->created_at->format('d M, Y'),
                        'time' => $log->created_at->format('h:i A'),
                        'by' => $log->actionBy->fullname ?? 'N/A'
                    ];
                } else {
                    return null;
                }
            })
            ->addColumn('action', function ($row) use ($application_status) {
                // $data = urlencode(json_encode($row));
                $resp = "<div class='d-flex gap-2'>";
                if ($application_status === 'pending') {
                    $resp .= "<a href='" . route('duties.uo.filesubmission.view', $row->proforma_id) . "' class='btn btn-sm btn-primary view-btn'>View</a>";
                } else {
                    $resp .= "<a href='" . route('duties.proforma.view', $row->proforma_id) . "' class='btn btn-sm btn-primary view-btn'>View</a>";
                }
                $resp .= "</div>";
                return $resp;
            })
            ->addColumn('overall_seniority_idx', function ($row) use ($overallSeniorityIndex) {
                return $overallSeniorityIndex[$row->proforma_id] ?? 'N/A';
            })
            ->addColumn('dept_seniority_idx', function ($row) {
                return Proforma::getDepartmentalSeniorityIndex($row->proforma_id);
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }


    public function view($id)
    {
        $proforma = Proforma::findOrFail($id);
        $total_step = 4;
        $tasks = getPrevNextTasks($id);
        $remarks = Remark::where('is_active', 1)->get();
        $this->authorize('canPerformOnProforma',  [$proforma, 'uo_file_submission']);

        $doc = UoFileSubmission::where('proforma_id', $id)
            ->first();

        // Whether UO Document file is submitted and exists in storage
        $doc_submitted = ($doc && Storage::disk('public')->exists($doc->file_path));

        return view('duties.uoFileSubmission', compact('proforma', 'total_step', 'tasks', 'remarks', 'doc_submitted'));
    }


    // This method is for submiting UO file of a single proforma
    public function submit(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $proforma = Proforma::findOrFail($id);
            $this->authorize('canPerformOnProforma',  [$proforma, 'uo_file_submission']);
            $file   = $request->file('document_file');

            $doc = UoFileSubmission::where('proforma_id', $id)
                ->first();

            if ($doc && Storage::disk('public')->exists($doc->file_path)) {
                Storage::disk('public')->delete($doc->file_path);
            }

            if (!$doc) {
                $doc = new UoFileSubmission();
                $doc->proforma_id = $id;
            }

            $path = $file->store('uploads/documents', 'public');
            $doc->file_name = $file->getClientOriginalName();
            $doc->file_path = $path;
            $doc->uploaded_by = Auth::user()->user_id;
            $doc->save();

            $proforma->mini_sequence = "file_uploaded";
            $proforma->save();
            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => Auth::user()->user_id,
                'action_name' => 'forwarded', //'file_uploaded',
                'action_remark' => $request->remarks ?? 'UO file submitted.',
                'process_sequence' => $proforma->process_sequence
            ]);

            //WorkflowHandler comes after LogService            
            WorkflowHandler::forwardApplication($proforma);

            DB::commit();
            return response()->json(['message' => 'UO file submitted and proforma has been forwarded successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error verifying proforma: ' . $e->getMessage()], 422);
        }
    }


    /**
     * Purpose: To put forward for a single proforma
     * Business Logic:
     * After UO file is submitted, the proforma can be moved/forwarded to the next step.
     * Find if the field 'mini_sequence' is equal to 'file_uploaded' which means file has been uploaded
     * 
     * @param $request is the HTTP request of type Illuminate\Http\Request
     * @param int $id  The proforma id which is the primary key of the proforma table.
     */
    public function forward(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'remarks' => 'nullable|string|max:600',
            ]);
            $proforma = Proforma::findOrFail($id);
            $this->authorize('canForward',  [$proforma, 'uo_file_submission']);
            if ($proforma->mini_sequence != "file_uploaded") {
                return response()->json(['message' => 'Upload file before forwarding.'], 422);
            }

            //WorkflowHandler comes after LogService
            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => Auth::user()->user_id,
                'action_name' => 'forwarded', //compulsory always set 'forwarded' if the proforma application is  forwarded
                'action_remark' => $request->remarks,
                'process_sequence' => $proforma->process_sequence
            ]);
            WorkflowHandler::forwardApplication($proforma);
            DB::commit();
            return response()->json(['message' => 'Proforma forwarded successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error forwarding proforma: ' . $e->getMessage()], 422);
        }
    }

    /**
     * Purpose: To upload a common UO file for a group of proforma, and then forward those proformas to the 
     * next step in bulk at one go.
     * 
     * Business Logic:
     * 1. Get the proforma Ids for the desired proforma
     * 2. Get a remark if present
     * 3. Grab the uploaded file and save in storage and the get the physical path 
     * 4. For every proforma id (perform a loop), set the same path in uo_file_submissions table using UoFileSubmission Model.
     * 5. Add a log service to tell that the proforma has been forwarded
     * 6. Forward the proforma using WorkFlowHandler
     * 
     * @param $request is the HTTP request of type Illuminate\Http\Request
     */

    public function bulkSubmitUOFileAndForward(Request $request)
    {
        $request->validate([
            'selected_proforma' => 'required|array|min:1',
            'selected_proforma.*' => 'exists:proforma,proforma_id',
            'remarks' => 'nullable|string|max:600',
            'document_file' => 'required|file|mimes:pdf|max:5120',
        ]);

        DB::beginTransaction();
        try {
            // Save the file in storage and save the path in uo_file_submissions table
            UOFileStorageService::saveUOFile($request->file('document_file'), $request->selected_proforma);

            $proformas = Proforma::whereIn('proforma_id', $request->selected_proforma)->get();
            foreach ($proformas as $proforma) {
                //WorkflowHandler comes after LogService
                LogService::addProformaLog([
                    'proforma_id' => $proforma->proforma_id,
                    'action_by' => Auth::user()->user_id,
                    'action_name' => 'forwarded', //compulsory always set 'forwarded' if the proforma application is  forwarded
                    'action_remark' => $request->remarks,
                    'process_sequence' => $proforma->process_sequence
                ]);
                //Forward the application
                WorkflowHandler::forwardApplication($proforma);
            }
            DB::commit();
            return response()->json(['message' => 'Your document has been uploaded successfully and forwarded.'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurs while uploading document and forwarding proforma: ' . $e->getMessage()], 422);
        }
    }
}
