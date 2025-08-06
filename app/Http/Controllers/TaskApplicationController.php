<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DepartmentModel;
use App\Models\ProformaModel;
use App\Models\Task;
use App\Services\WorkflowHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\DataTables;

class TaskApplicationController extends Controller
{


    public function index($task_id)
    {
        $departments = DepartmentModel::orderBy('dept_id')->get()->unique('dept_id');
        $task = Task::findOrFail($task_id);
        /*switch ($task->tasks_duty) {
            case  'verify_and_forward':
                break;
        }*/
        return view('duties.list_of_applications', compact('departments', 'task'));
    }

    public function allProcess()
    {
        $user = Auth::user();

        dd($user);

        // Eager load role's duties (tasks) and their related processes
        $tasks = $user->role->duties()->with('processes')->get();

        $taskSummaries = [];

        foreach ($tasks as $task) {
            $pending = 0;
            $completed = 0;
            $total = 0;

            foreach ($task->processes as $process) {
                $sequence = $process->pivot->sequence;

                $apps = ProformaModel::where('process_id', $process->process_id)->get();

                $pending += $apps->where('process_sequence', $sequence)->count();
                $completed += $apps->where('process_sequence', '>', $sequence)->count();
                $total += $pending + $completed;
            }

            // Use task_id as key to avoid duplicates
            $taskSummaries[$task->tasks_id] = [
                'task' => $task->tasks_name,
                'task_id' => $task->tasks_id,
                'pending' => $pending,
                'completed' => $completed,
                'total' => $pending + $completed,
            ];
        }

        $cards = array_values($taskSummaries); // Reset keys for blade loop
        return view('malem.allprocess', compact('cards'));
    }



    public function ajaxlist(Request $request, $task_id)
    {
        $task = Task::findOrFail($task_id);

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


        switch ($task->tasks_duty) {

            case  'verify_and_forward':
                $dept_id = $request->input('dept_id');
                if (!empty($dept_id)) {
                    $data = $data->filter(function ($item) use ($dept_id) {
                        return $item->dept_id == $dept_id;
                    })->values(); // reset keys after filtering
                }
                break;
            case  'client_form_submission':
                if (strtolower(Auth::user()->role->role_name) != 'superadmin') {
                    $data = $data->filter(function ($item) {
                        return Auth::user()->id == $item->uploaded_id;
                    })->values(); // reset keys after filtering
                }
                break;
        }


        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('deceased_doe', function ($row) {
                return date('d M, Y', strtotime($row->deceased_doe));
            })
            ->editColumn('appl_date', function ($row) {
                return date('d M, Y', strtotime($row->appl_date));
            })
            ->editColumn('applicant_dob', function ($row) {
                return date('d M, Y', strtotime($row->applicant_dob));
            })
            ->addColumn('action', function ($row) {
                $data = urlencode(json_encode($row));
                return "<div class='d-flex gap-2'>
                            <a href='" . route('viewPersonalDetailsFrom', Crypt::encryptString($row->ein)) . "' class='btn btn-sm btn-primary view-btn' data-row='{$data}'>View</a>
                        
                        </div>";
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
}
