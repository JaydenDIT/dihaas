<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Process;
use App\Models\Task;
use App\Models\ProcessTasksMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcessTaskMappingController extends Controller
{
    public function index()
    {
        $processes = Process::orderBy('process_name')->get();
        return view('Process_task_mapping.index', compact('processes'));
    }


    public function fetchData($id)
    {

        $task_duties = [
            "view" => "View",
            "apply" => "Apply",
            "upload_casebody" => "Case Body Upload and Forward",
            "prepare_cert" => "Preparation of Certicate",
            "forward" => "Check and Forward",
            "approve" => "Final Approver",
            "esign" => "eSign the Certificate",
        ];
        $included = ProcessTasksMapping::where('process_id', $id)
            ->with('task')
            ->orderBy('sequence')
            ->get()
            ->map(function ($map) use ($task_duties) {
                return [
                    'tasks_id' => $map->tasks_id,
                    'tasks_name' => $map->task->tasks_name,
                    'allow_drop' => $map->allow_drop,
                    'allow_reject' => $map->allow_reject,
                    'allow_esign' => $map->allow_esign,
                    'tasks_duty' => $task_duties[$map->task->tasks_duty] ?? "Nothing",
                    'tasks_description' => $map->task->tasks_description

                ];
            });

        $excluded = Task::whereNotIn('tasks_id', $included->pluck('tasks_id'))
            ->get()
            ->map(function ($task)  use ($task_duties) {
                return [
                    'tasks_id' => $task->tasks_id,
                    'tasks_name' => $task->tasks_name,
                    'tasks_duty' => $task_duties[$task->tasks_duty] ?? "Nothing",
                    'tasks_description' => $task->tasks_description
                ];
            });




        return response()->json([
            'included' => $included,
            'excluded' => $excluded
        ]);
    }

    public function saveMapping(Request $request)
    {
        $request->validate([
            'pid' => 'required|exists:process,process_id',
            'included' => 'required|array',
            'included.*.tasks_id' => 'required|exists:tasks,tasks_id',
            'included.*.sequence' => 'required|integer',
            'included.*.allow_drop' => 'required|boolean',
            'included.*.allow_reject' => 'required|boolean',
            'included.*.allow_esign' => 'required|boolean',
        ]);

        DB::beginTransaction();
        try {
            // Remove existing included
            ProcessTasksMapping::where('process_id', $request->pid)->delete();

            // Insert new included
            foreach ($request->included as $map) {
                ProcessTasksMapping::create([
                    'process_id' => $request->pid,
                    'tasks_id' => $map['tasks_id'],
                    'sequence' => $map['sequence'],
                    'allow_drop' => $map['allow_drop'],
                    'allow_reject' => $map['allow_reject'],
                    'allow_esign' => $map['allow_esign'],
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Process duties mapping saved successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to save mapping.', 'error' => $e->getMessage()], 500);
        }
    }
}
