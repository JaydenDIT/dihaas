<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ProcessTasksMapping;
use App\Models\ProformaModel;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class TaskApplicationController extends Controller
{
    public function index($task_id)
    {
        $task = Task::findOrFail($task_id);

        return view('applications.index', compact('task'));
    }

    public function ajaxlist(Request $request, $task_id)
    {


        $allApplications = $this->dataList($request, $task_id);
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



    public function dataList(Request $request, $task_id)
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
