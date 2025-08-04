<?php

namespace App\Policies;


use App\Models\ProcessTasksMapping;
use App\Models\ProformaModel;
use App\Models\User;

class ProformaPolicy
{
    public function canPerform(User $user, ProformaModel $app, $task_id)
    {
        $mapping = ProcessTasksMapping::where('process_id', $app->process_id)
            ->where('sequence', $app->process_sequence)
            ->where('tasks_id', $task_id)
            ->first();

        return $mapping && $user->role->duties->contains('tasks_id', $task_id);
    }

    public function canReject(User $user, ProformaModel $app)
    {
        return ProcessTasksMapping::where('process_id', $app->process_id)
            ->where('sequence', $app->process_sequence)
            ->where('allow_reject', true)
            ->exists();
    }

    public function canDrop(User $user, ProformaModel $app)
    {
        return ProcessTasksMapping::where('process_id', $app->process_id)
            ->where('sequence', $app->process_sequence)
            ->where('allow_drop', true)
            ->exists();
    }
}
