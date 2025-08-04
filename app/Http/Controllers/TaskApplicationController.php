<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\DepartmentModel;
use App\Models\ProcessTasksMapping;
use App\Models\ProformaModel;
use App\Models\RemarksApproveModel;
use App\Models\RemarksModel;
use App\Models\Task;
use App\Services\VerifyNewApplication;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class TaskApplicationController extends Controller
{


    public function index($task_id)
    {
        $task = Task::findOrFail($task_id);

        switch ($task->tasks_duty) {
            case  'verify_new_application':
                return   VerifyNewApplication::index($task);
                break;
        }



        return view('malem.index', compact('task'));
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
        switch ($task->tasks_duty) {
            case  'verify_new_application':
                return   VerifyNewApplication::ajaxlist($task);
                break;
        }







        $allApplications = $this->dataList($task_id);
        return DataTables::of($allApplications)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                if ($row['status_label'] === 'Pending') {
                    return '<span class="badge bg-warning">Pending</span>';
                } elseif ($row['status_label'] === 'Completed') {
                    return '<span class="badge bg-success">Completed</span>';
                } else {
                    return '<span class="badge bg-secondary">Not Reached</span>';
                }
            })
            ->rawColumns(['status'])
            ->make(true);
    }



    public function dataList($task_id)
    {
        $user = Auth::user();
        $task = Task::findOrFail($task_id);

        // Check permission
        if (!$user->role->duties->contains('tasks_id', $task->tasks_id)) {
            abort(403, 'Unauthorized');
        }

        $mappings = ProcessTasksMapping::where('tasks_id', $task->tasks_id)->get();

        $allApplications = collect();

        foreach ($mappings as $mapping) {
            $apps = ProformaModel::where('process_id', $mapping->process_id)
                ->where('process_sequence', '>=', $mapping->sequence)
                ->get()
                ->map(function ($app) use ($mapping) {

                    /* return [
                        'application_id' => $app->application_id,
                        'status' => $app->application_status,
                        'process_id' => $app->process_id,
                        'current_sequence' => $app->process_sequence,
                        'task_sequence' => $mapping->sequence,
                        'status_label' => $app->process_sequence == $mapping->sequence ? 'Pending' : ($app->process_sequence > $mapping->sequence ? 'Completed' : 'Not Reached'),
                    ]; */

                    $app->task_sequence = $mapping->sequence;
                    $app->status_label = $app->process_sequence == $mapping->sequence ? 'Pending' : ($app->process_sequence > $mapping->sequence ? 'Completed' : 'Not Reached');

                    return $app;
                });

            $allApplications = $allApplications->merge($apps);
        }

        return $allApplications;
    }
}
