<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Process;
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

    public function allProcess(Request $request)
    {
        $process_name = $request->input('process_name', null);
        //By default we are directly getting a process for process_name
        if (!is_null($process_name)) {
            $process = Process::where('process_name', $process_name)->first();
            //We are giving error response if there are no processes in the system.
            if (is_null($process)) {
                return response()->view('errors.custom', ['title' => 'Process Error', 'message' => 'No such processe called \'' . $process_name . '\' found in the system. Please contact system administrator.'], 500);
            }
        } else {
            //We are giving error response if there are no processes in the system.
            $process = Process::first();
            if (is_null($process)) {
                return response()->view('errors.custom', ['title' => 'Process Error', 'message' => 'No processes found in the system. Please contact system administrator.'], 500);
            }
        }

        // Getting all tasks under this process order by sequence from pivot table
        $processTasks = $process ? $process->tasks() : collect();

        //If there is no tasks in the precess then also, we will return error response
        if ($processTasks->count() == 0) {
            return response()->view('errors.custom', ['title' => 'Process Error', 'message' => 'No tasks found under the selected process. Please contact system administrator.'], 500);
        }

        // Get currently authenticated user
        $user = Auth::user();

        // Eager load role's duties (tasks) and their related processes
        $tasks = $user->role->duties()->with('processes')->get();

        $taskSummaries = [];
        foreach ($tasks as $task) {
            $pending = 0;
            $forwarded = 0;
            $completed = 0;
            $total = 0;

            foreach ($task->processes as $process) {
                $sequence = $process->pivot->sequence;

                // Proforma query initialized based on process id
                $apps = Proforma::where('process_id', $process->process_id);

                // If the user is just a citizen
                if ($user->role->role_group == "citizen") {
                    $apps->where('create_by', $user->user_id);
                }
                // Here, we need to check if the authenticated user is super admin or if the user belongs to Department of Personel,
                else if (in_array($user->role->role_name, ['Superadmin', 'DP Nodal', 'DP Assistant'])) {
                    //do nothing
                } else if (!is_null($user->field_dept_cd)) {
                    //Otherwise, we should filter only the proformas that belong to department of the currently authenticated user.
                    $apps->where('deceased_field_dept_cd', $user->field_dept_cd);
                }

                $result = $apps->get();

                // Counts are calculated from the collection of proformas retrieved by the query '$apps'
                $pending += $result->where('process_sequence', $sequence)->count();
                $forwarded += $result->where('process_sequence', '>', $sequence)
                    ->where('proforma_status', '!=', 'completed')
                    ->count(); //tasks already forwarded by him, but the whole process may not be completed
                $completed += $result->where('proforma_status', 'completed')->count();
            }
            $total = $pending + $forwarded + $completed;
            // Use tasks_id as key to avoid duplicates
            $taskSummaries[$task->tasks_id] = [
                'task' => $task->tasks_name,
                'tasks_id' => $task->tasks_id,
                'pending' => $pending,
                'forwarded' => $forwarded,
                'completed' => $completed,
                'total' => $total,
            ];
        }

        //$cards = array_values($taskSummaries); // Reset keys for blade loop

        //Now reordering the task based on processTasks sequence
        $orderedCards = [];

        $availableTaskIds = $processTasks->orderBy('sequence')->pluck('tasks.tasks_id')->toArray();
        // Filter cards to include only those tasks that are part of the current process
        foreach ($availableTaskIds as $taskId) {
            if (isset($taskSummaries[$taskId])) {
                $orderedCards[] = $taskSummaries[$taskId];
            }
        }

        return view('duties.allprocess', [
            'cards' => $orderedCards,
            'process_name' => ucwords(str_replace('_', ' ', $process->process_name)),
            'processes' => Process::all()
        ]);
    }



    public function ajaxlist(Request $request, $tasks_id)
    {
        $task = Task::findOrFail($tasks_id);

        $application_status = $request->input('application_status');
        $overallSeniorityIndex = Proforma::getOverallSeniorityList();

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
            ->addColumn('remarks', function ($row) {
                return $row->proformaLogs()
                    ->whereIn('action_name', ['forwarded', 'rejected', 'reverted', 'completed'])
                    ->latest()
                    ->value('action_remark') ?? 'N/A';
            })
            ->addColumn('action', function ($row) {
                $data = urlencode(json_encode($row));
                return "<div class='d-flex gap-2'>
                            <a href='" . route('duties.proforma.view', $row->proforma_id) . "' class='btn btn-sm btn-primary view-btn'>view</a>
                        </div>";
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
