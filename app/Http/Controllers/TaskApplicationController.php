<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Proforma;
use App\Models\Task;
use App\Services\CmisApiService;
use App\Services\WorkflowHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\DataTables;

class TaskApplicationController extends Controller
{


    public function index($tasks_id)
    {

        $task = Task::findOrFail($tasks_id);
        switch ($task->tasks_duty) {
            case  'client_form_submission':
                return redirect()->route('duties.form.index', [$tasks_id]);
                break;
            case  'verify_and_forward':
                return redirect()->route('duties.verify.form.index', [$tasks_id]);
                break;
            case  'verify_physical_copy':
                return redirect()->route('duties.verify.document.index', [$tasks_id]);
                break;
            case  'uo_file_submission':
                return redirect()->route('duties.uo.filesubmission.index', [$tasks_id]);
                break;
            case  'uo_formfillup':
                return redirect()->route('duties.uo.formfillup.index', [$tasks_id]);
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
}
