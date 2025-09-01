<?php

namespace App\Services;

use App\Models\ProcessTasksMapping;
use App\Models\Proforma;
use Illuminate\Support\Facades\Auth;

class WorkflowHandler
{
    /* -------------------------------
     * APPLICATION STATE TRANSITIONS
     * ----------------------------- */
    public static function forwardApplication(Proforma $app)
    {
        $next = ProcessTasksMapping::where('process_id', $app->process_id)
            ->where('sequence', '>', $app->process_sequence)
            ->orderBy('sequence')
            ->first();

        if ($next) {
            $app->process_sequence = $next->sequence;
            $app->proforma_status = 'forwarded';
        } else {
            $app->process_sequence = 9999;
            $app->proforma_status = 'completed';
        }

        $app->mini_sequence = NULL;
        $app->save();
        return $app;
    }

    public static function dropApplication(Proforma $app)
    {
        $prev = ProcessTasksMapping::where('process_id', $app->process_id)
            ->where('sequence', '<', $app->process_sequence)
            ->orderByDesc('sequence')
            ->first();

        if ($prev) {
            $app->process_sequence = $prev->sequence;
            $app->proforma_status = 'reverted';
            $app->mini_sequence = NULL;
            $app->save();
        }

        return $app;
    }

    public static function rejectApplication(Proforma $app)
    {
        $app->proforma_status = 'rejected';
        $app->process_sequence = -99;
        $app->save();
        return $app;
    }

    /* -------------------------------
     * PERMISSION CHECK
     * ----------------------------- */
    protected static function checkPermission($taskId)
    {
        $user = Auth::user();

        if (!$user->role->duties->contains('tasks_id', $taskId)) {
            abort(403, 'Unauthorized');
        }
    }

    /* -------------------------------
     * CORE DATA FETCHER
     * ----------------------------- */
    protected static function getApplicationsByMapping($taskId, callable $filterCallback)
    {
        self::checkPermission($taskId);

        $mappings = ProcessTasksMapping::where('tasks_id', $taskId)->get();
        $allApplications = collect();

        foreach ($mappings as $mapping) {
            $query = Proforma::where('process_id', $mapping->process_id)
                ->orderByRaw("expire_on_duty = 0, deceased_doe, created_at, applicant_dob");

            // Apply filter logic from the specific case
            $query = $filterCallback($query, $mapping);

            $allApplications = $allApplications->merge($query->get());
        }

        return $allApplications;
    }

    /* -------------------------------
     * SPECIFIC STATUS HANDLERS
     * ----------------------------- */
    public static function proformaTaskCurrentData($task)
    {
        return self::getApplicationsByMapping($task->tasks_id, function ($query, $mapping) {
            return $query
                ->where('process_sequence', '=', $mapping->sequence)
                ->whereIn('proforma_status', ['forwarded', 'reverted']);
        });
    }

    public static function proformaTaskForwardedData($task)
    {
        return self::getApplicationsByMapping($task->tasks_id, function ($query, $mapping) {
            return $query
                ->where('process_sequence', '>', $mapping->sequence)
                ->whereIn('proforma_status', ['forwarded', 'reverted']);
        });
    }

    public static function proformaTaskCompletedData($task)
    {
        return self::getApplicationsByMapping($task->tasks_id, function ($query, $mapping) {
            return $query
                ->where('process_sequence', '>', $mapping->sequence)
                ->where('proforma_status', 'completed');
        });
    }

    public static function proformaTaskRejectedData($task)
    {
        return self::getApplicationsByMapping($task->tasks_id, function ($query, $mapping) {
            return $query
                ->where('process_sequence', '>=', $mapping->sequence)
                ->where('proforma_status', 'rejected');
        });
    }

    public static function proformaTaskNotReachData($task)
    {
        return self::getApplicationsByMapping($task->tasks_id, function ($query, $mapping) {
            return $query->where('process_sequence', '<', $mapping->sequence);
        });
    }
}
