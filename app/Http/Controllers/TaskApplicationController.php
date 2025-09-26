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


    /**
     * Retrieve and summarize the number of Proforma requests (pending, forwarded, completed, total)
     * for each task in the Die-in-Harness process that the currently authenticated user can access.
     *
     * ---
     * Purpose:
     * This method determines which process to evaluate (based on `process_name` in the request,
     * or the first available process if none is given). For that process, it finds all tasks
     * and filters them by the user's role to determine accessibility. For each accessible task,
     * it calculates:
     *  - Pending requests
     *  - Forwarded requests
     *  - Completed requests
     *  - Total requests
     *
     * ---
     * Business Logic:
     * 1. Select process:
     *    - If `process_name` is given, find that process by name.
     *    - If not provided, pick the first process in the system.
     *    - Return error if no process is found.
     * 
     * 2. Retrieve tasks of the process (ordered by sequence).
     *    - Return error if no tasks exist under the process.
     *
     * 3. Filter tasks by accessibility based on the authenticated user's role.
     *
     * 4. For each accessible task:
     *    - Initialize counts (pending, forwarded, completed).
     *    - For each process associated with the task:
     *        i. Build a Proforma query filtered by the process and user context.
     *       ii. If task duty is `uo_form_generation`, load UO Generation records and restrict
     *           to those where the authenticated user is the signing authority.
     *      iii. Apply role-based filters:
     *           - Citizens: only their own Proformas.
     *           - Superadmin/DP roles: unrestricted.
     *           - Departmental roles: only within the same department.
     *       iv. Calculate:
     *           - `pending`: Proformas at the same sequence.
     *           - `forwarded`: Proformas forwarded to higher sequences but not completed.
     *           - `completed`: Proformas marked as completed.
     *    - Compute total as the sum of the three counts.
     *
     * 5. Reorder tasks by the process sequence to maintain UI consistency.
     *
     * ---
     * @param  \Illuminate\Http\Request  $request  Request containing optional `process_name`
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     *         Returns the `duties.allprocess` view with:
     *           - `cards`: Task summary counts (pending, forwarded, completed, total)
     *           - `process_name`: Current process (formatted)
     *           - `processes`: All processes in the system
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException (500)
     *         If no process or no tasks exist for the given process.
     */
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
            //We are the first process in the system.
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

        //Getting accesible tasks(jobs)

        // Get currently authenticated user
        $user = Auth::user();

        // Eager load role's duties (tasks) and their related processes
        // $tasks = $user->role->duties()->with('processes')->get();

        $tasks = $processTasks->get()->filter(function ($task) use ($user) {
            return $task->isAccessibleByRole($user->role_id);
        });

        $taskSummaries = [];
        foreach ($tasks as $task) {
            $pending = 0;
            $forwarded = 0;
            $completed = 0;
            $total = 0;

            foreach ($task->processes as $process) {
                //Getting the sequence for the particular process task mapping
                $sequence = $process->pivot->sequence;

                // Proforma query initialized based on process id
                $apps = Proforma::where('process_id', $process->process_id);

                //Load UO form generation records in case if the task is about UO generation
                if ($task->task_duty == 'uo_form_generation') {
                    $apps->with('uoGeneration');
                }
                // If the user is just a citizen or if the task is about form submission, then only the proformas 
                //submitted by the authenticated user will be counted
                if ($user->role->role_group == "citizen" || $task->tasks_duty == "client_form_submission") {
                    $apps->where('create_by', $user->user_id);
                }
                // Here, we need to check if the authenticated user is departmental user except Department of Personel,                
                else if ($user->role->role_group == "single_department") {
                    //Otherwise, we should filter only the proformas that belong to department of the currently authenticated user.
                    $apps->where('deceased_field_dept_cd', $user->field_dept_cd);
                }

                $result = ($task->tasks_duty == 'uo_form_generation') ? $apps->get()->filter(function ($item) {
                    //Only the right signing authority will see proforma counts in case if the task is about UO generation
                    if (!is_null($item->uoGeneration)) {
                        return $item->uoGeneration->signing_authority == Auth::id();
                    }
                    return false;
                }) : $apps->get();

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
                'task_description' => $task->tasks_description,
                'tasks_id' => $task->tasks_id,
                'pending' => $pending,
                'forwarded' => $forwarded,
                'completed' => $completed,
                'total' => $total,
            ];
        }

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

    // Method to revert a single proforma to the previous step
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

    // Method to revert multipe proformas to the previous step at one time
    public function bulkRevert(Request $request, $tasks_id)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'selected_proforma' => 'required|array',
                'remarks' => 'required|string|max:600',
            ]);

            // Since all the proformas are at the same task i.e. at the same step, so we just fetch the task once
            $tasks = Task::findOrFail($tasks_id);

            // Fetching all the proformas to be reverted
            $proformas = Proforma::whereIn('proforma_id', $request->selected_proforma)->get();
            foreach ($proformas as $proforma) {
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
            }
            DB::commit();
            return response()->json(['message' => 'Selected proformas reverted successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error has occured while reverting proformas. Cause: ' . $e->getMessage()], 422);
        }
    }


    // Method to reject a single proforma to the initial step
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

    // Now is the method for multiple proformas to be rejected at one time
    public function bulkReject(Request $request, $tasks_id)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'selected_proforma' => 'required|array',
                'remarks' => 'required|string|max:600',
            ]);

            // Since all the proformas are at the same task i.e. at the same step, so we just fetch the task once
            $tasks = Task::findOrFail($tasks_id);

            // Fetching all the proformas to be rejected
            $proformas = Proforma::whereIn('proforma_id', $request->selected_proforma)->get();
            foreach ($proformas as $proforma) {
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
            }
            DB::commit();
            return response()->json(['message' => 'Selected proformas rejected successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error has occured while rejecting proformas. Cause: ' . $e->getMessage()], 422);
        }
    }
}
