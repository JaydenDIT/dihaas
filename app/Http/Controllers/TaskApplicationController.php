<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Proforma;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Services\CmisApiService;
use App\Services\LogService;
use App\Services\WorkflowHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TaskApplicationController extends Controller
{


    public function index(Request $request, $tasks_id)
    {

        $task = Task::findOrFail($tasks_id);
        switch ($task->tasks_duty) {
            case  'client_form_submission':
                return redirect()->route('duties.form.index', ['tasks_id' => $tasks_id, 'view' => $request->input('view', 'pending')]);
                break;
            case  'verify_and_forward':
                return redirect()->route('duties.verify.form.index', ['tasks_id' => $tasks_id, 'view' => $request->input('view', 'un-verified')]);
                break;
            case  'verify_physical_copy':
                return redirect()->route('duties.verify.document.index', ['tasks_id' => $tasks_id, 'view' => $request->input('view', 'un-verified')]);
                break;
            case  'uo_file_submission':
                return redirect()->route('duties.uo.filesubmission.index', ['tasks_id' => $tasks_id, 'view' => $request->input('view', 'pending')]);
                break;
            case  'uo_file_approval':
                return redirect()->route('duties.uo.file-approval.index', ['tasks_id' => $tasks_id, 'view' => $request->input('view', 'pending')]);
                break;
            case  'uo_formfillup':
                return redirect()->route('duties.uo.formfillup.index', ['tasks_id' => $tasks_id, 'view' => $request->input('view', 'pending')]);
                break;
            case 'uo_form_generation':
                return redirect()->route('duties.uo.formgeneration.index', ['tasks_id' => $tasks_id, 'view' => $request->input('view', 'pending')]);
                break;
        }
        $departments = CmisApiService::apiFieldDepartments();
        return view('duties.list_of_applications', compact('departments', 'task'));
    }

    public function allProcess()
    {
        $user = Auth::user();
        // Eager load role's duties (tasks) and their related processes
        $tasks = $user->role->duties()->with('processes')->get();

        $taskSummaries = [];

        foreach ($tasks as $task) {
            $pending = 0;
            $completed = 0;
            $total = 0;

            foreach ($task->processes as $process) {
                $sequence = $process->pivot->sequence;
                $apps = Proforma::where('process_id', $process->process_id)->get();
                $pending += $apps->where('process_sequence', $sequence)->count();
                $completed += $apps->where('process_sequence', '>', $sequence)->count();
                $total += $pending + $completed;
            }

            // Use tasks_id as key to avoid duplicates
            $taskSummaries[$task->tasks_id] = [
                'task' => $task->tasks_name,
                'tasks_id' => $task->tasks_id,
                'pending' => $pending,
                'completed' => $completed,
                'total' => $pending + $completed,
            ];
        }

        $cards = array_values($taskSummaries); // Reset keys for blade loop
        return view('duties.allprocess', compact('cards'));
    }



    public function ajaxlist(Request $request, $tasks_id)
    {
        $task = Task::findOrFail($tasks_id);

        $application_status = $request->input('application_status');


        switch ($application_status) {
            case 'completed':
                $data = WorkflowHandler::proformaTaskCompletedData($task);
                break;
            case 'notreach':
                $data = WorkflowHandler::proformaTaskNotReachData($task);
                break;
            default:
                $data = WorkflowHandler::proformaTaskCurrentData($task);
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
            ->addColumn('action', function ($row) {
                $data = urlencode(json_encode($row));
                return "<div class='d-flex gap-2'>
                            <a href='" . route('duties.proforma.view', $row->proforma_id) . "' class='btn btn-sm btn-primary view-btn'>view</a>
                        </div>";
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function revert(Request $request, $proforma_id, $tasks_id)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'remarks' => 'required|string|max:600',
            ]);
            $proforma = Proforma::findOrFail($proforma_id);
            $tasks = Task::findOrFail($tasks_id);
            $this->authorize('canDrop',  [$proforma, $tasks->tasks_duty]);
            //WorkflowHandler comes after LogService
            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => Auth::user()->user_id,
                'action_name' => 'reverted',
                'action_remark' => $request->remarks,
                'process_sequence' => $proforma->process_sequence
            ]);
            WorkflowHandler::dropApplication($proforma);
            DB::commit();
            return response()->json(['message' => 'Proforma reverted successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error reverting proforma: ' . $e->getMessage()], 422);
        }
    }

    public function reject(Request $request, $proforma_id, $tasks_id)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'remarks' => 'required|string|max:600',
            ]);
            $proforma = Proforma::findOrFail($proforma_id);
            $tasks = Task::findOrFail($tasks_id);
            $this->authorize('canReject',  [$proforma, $tasks->tasks_duty]);
            //WorkflowHandler comes after LogService
            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => Auth::user()->user_id,
                'action_name' => 'rejected',
                'action_remark' => $request->remarks,
                'process_sequence' => $proforma->process_sequence
            ]);
            WorkflowHandler::rejectApplication($proforma);
            DB::commit();
            return response()->json(['message' => 'Proforma rejected successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error rejecting proforma: ' . $e->getMessage()], 422);
        }
    }
}
