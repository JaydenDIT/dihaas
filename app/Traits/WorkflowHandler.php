<?php

namespace App\Traits;


use App\Models\ProcessTasksMapping;
use App\Models\ProformaModel;

trait WorkflowHandler
{
    public function forwardApplication(ProformaModel $app)
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

    public function dropApplication(ProformaModel $app)
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

    public function rejectApplication(ProformaModel $app)
    {
        $app->proforma_status = 'rejected';
        $app->process_sequence = -99;
        $app->save();
        return $app;
    }
}
