<?php

namespace App\Policies;

use App\Models\ProcessTasksMapping;
use App\Models\Proforma;
use App\Models\User;

class ProformaPolicy
{
    /**
     * Global check — does the user have this duty anywhere?
     * Usage: $this->authorize('canPerform', ['App\Models\Proforma', 'form_submit']);
     */
    public function canPerform(User $user, string $tasks_duty)
    {
        return $this->checkTaskAccess($user, null, [], $tasks_duty);
    }

    /**
     * Stage-specific check — is the user at the correct process step for this duty?
     * Usage: $this->authorize('canPerformOnProforma', [$proforma, 'form_submit']);
     */
    public function canPerformOnProforma(User $user, Proforma $app, string $tasks_duty)
    {
        return $this->checkTaskAccess($user, $app, [], $tasks_duty);
    }

    public function canView(User $user, Proforma $app, string $tasks_duty = "")
    {
        return $this->checkTaskAccess($user, $app, [], $tasks_duty);
    }

    public function canForward(User $user, Proforma $app, string $tasks_duty = "")
    {
        return $this->checkTaskAccess($user, $app, [], $tasks_duty);
    }

    public function canReject(User $user, Proforma $app, string $tasks_duty = "")
    {
        return $this->checkTaskAccess($user, $app, ['allow_reject' => true], $tasks_duty);
    }

    public function canDrop(User $user, Proforma $app, string $tasks_duty = "")
    {
        return $this->checkTaskAccess($user, $app, ['allow_drop' => true], $tasks_duty);
    }

    /**
     * Base check for task access.
     * If $app is null → global check (any process/sequence)
     * If $app is set → stage-specific check for current process step.
     */
    protected function checkTaskAccess(User $user, ?Proforma $app, array $extraConditions = [], string $tasks_duty = "")
    {
        $task_ids = $user->role->duties->pluck('tasks_id')->toArray();

        $query = ProcessTasksMapping::whereIn('tasks_id', $task_ids);

        // Stage-specific filter
        if ($app !== null) {
            $query->where('process_id', $app->process_id)
                ->where('sequence', $app->process_sequence);
        }

        // Apply extra conditions (like allow_reject, allow_drop)
        foreach ($extraConditions as $column => $value) {
            $query->where($column, $value);
        }

        // Optional tasks_duty check via relationship
        if (!empty($tasks_duty)) {
            $query->whereHas('task', function ($q) use ($tasks_duty) {
                $q->where('tasks_duty', $tasks_duty);
            });
        }

        return $query->exists();
    }
}
