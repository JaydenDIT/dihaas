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
            $query = Proforma::where('process_id', $mapping->process_id);
            /**
             * Only the concern department user will be able to see the proforma list.
             * However if the user is of the department 'Department of Personal' or if the user is 
             * super-admin then he/she will be able to see all the proforma list irrespective of 
             * departments of Manipur.
             */

            $user = Auth::user(); //Retrieve the currently authenticated user
            if ($user->role->role_group == "citizen") {
                $query->where('create_by', $user->user_id);
            } else if (
                $user->role->role_group == "superadmin"/* Check if the suser is super-admin, role id for super admin is 999 */
                || $user->field_dept_cd == 201 /* Check if the user belongs to Department of Personal. field_dept_cd is 201*/
            ) {
                //user belongs to Department of Personel(DP)
            } else {
                /* *
                Here, it is found that user is a departmental user but doesn't belong to DP, so he/she must be able to see only the proforma list
                that belong to the department which he/she belongs
                */
                $query->where('deceased_field_dept_cd', $user->field_dept_cd);
            }

            $query->orderByRaw("expire_on_duty = 0, deceased_doe, proforma_submission_date, applicant_dob");

            // Apply filter logic from the specific case
            $query = $filterCallback($query, $mapping);

            $allApplications = $allApplications->merge($query->get());
        }

        return $allApplications;
    }

    /* -------------------------------
     * SPECIFIC STATUS HANDLERS
     * ----------------------------- */
    public static function proformaTaskCurrentData($task, $create_by = null)
    {
        return self::getApplicationsByMapping($task->tasks_id, function ($query, $mapping) use ($create_by) {

            if (!is_null($create_by)) {
                $query->where('create_by', $create_by);
            }
            return $query
                ->where('process_sequence', '=', $mapping->sequence)
                ->whereIn('proforma_status', ['forwarded', 'reverted']);
        });
    }

    public static function proformaTaskForwardedData($task, $create_by = null)
    {
        return self::getApplicationsByMapping($task->tasks_id, function ($query, $mapping) use ($create_by) {
            if (!is_null($create_by)) {
                $query->where('create_by', $create_by);
            }
            return $query
                ->where('process_sequence', '>', $mapping->sequence)
                ->whereIn('proforma_status', ['forwarded', 'reverted']);
        });
    }

    public static function proformaTaskCompletedData($task, $create_by = null)
    {
        return self::getApplicationsByMapping($task->tasks_id, function ($query, $mapping) use ($create_by) {
            if (!is_null($create_by)) {
                $query->where('create_by', $create_by);
            }
            return $query
                ->where('process_sequence', '>', $mapping->sequence)
                ->where('proforma_status', 'completed');
        });
    }

    public static function proformaTaskRejectedData($task, $create_by = null)
    {
        return self::getApplicationsByMapping($task->tasks_id, function ($query, $mapping) use ($create_by) {
            if (!is_null($create_by)) {
                $query->where('create_by', $create_by);
            }
            return $query
                ->where('process_sequence', '>=', $mapping->sequence)
                ->where('proforma_status', 'rejected');
        });
    }

    public static function proformaTaskNotReachData($task, $create_by = null)
    {
        return self::getApplicationsByMapping($task->tasks_id, function ($query, $mapping) use ($create_by) {
            if (!is_null($create_by)) {
                $query->where('create_by', $create_by);
            }
            return $query->where('process_sequence', '<', $mapping->sequence);
        });
    }
}
