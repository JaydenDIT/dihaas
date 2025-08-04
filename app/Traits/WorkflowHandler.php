<?php

namespace App\Traits;


use App\Models\ProcessTasksMapping;
use App\Models\ProformaModel;
use Illuminate\Support\Facades\Auth;

trait WorkflowHandler
{


    public static function forwardApplication(ProformaModel $app)
    {
        $current = ProcessTasksMapping::where('process_id', $app->process_id)
            ->where('sequence', $app->process_sequence)
            ->first();

        $next = ProcessTasksMapping::where('process_id', $app->process_id)
            ->where('sequence', '>', $app->process_sequence)
            ->orderBy('sequence')
            ->first();

        if ($next) {
            $app->process_sequence = $next->sequence;
            $app->proforma_status = 'pending';
        } else {
            $app->process_sequence = 9999; // mark as done
            $app->proforma_status = 'completed';
        }

        $app->save();
        return $app;
    }

    public static function dropApplication(ProformaModel $app)
    {
        $prev = ProcessTasksMapping::where('process_id', $app->process_id)
            ->where('sequence', '<', $app->process_sequence)
            ->orderByDesc('sequence')
            ->first();

        if ($prev) {
            $app->process_sequence = $prev->sequence;
            $app->proforma_status = 'pending';
            $app->save();
        }

        return $app;
    }

    public static function rejectApplication(ProformaModel $app)
    {
        $app->proforma_status = 'rejected';
        $app->process_sequence = -99;
        $app->save();
        return $app;
    }


    public static function proformaTaskData($task)
    {
        $user = Auth::user();

        // Check permission
        if (!$user->role->duties->contains('tasks_id', $task->tasks_id)) {
            abort(403, 'Unauthorized');
        }

        $mappings = ProcessTasksMapping::where('tasks_id', $task->tasks_id)->get();

        $allApplications = collect();

        foreach ($mappings as $mapping) {
            $apps = ProformaModel::where('process_id', $mapping->process_id)
                ->where('process_sequence', '>=', $mapping->sequence)
                ->orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")
                ->get();

            $allApplications = $allApplications->merge($apps);
        }

        return $allApplications;
    }
}
