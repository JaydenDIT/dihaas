<?php

namespace App\View\Components;

use App\Models\ProcessTasksMapping;
use App\Models\Proforma;
use App\Models\ProformaLog;
use Illuminate\View\Component;

class ProformaActivityLogs extends Component
{
    public $proformaId;
    public $logs;
    public $remainingTasks;
    /**
     * Create a new component instance.
     *
     * @param int $proformaId
     */
    public function __construct(int $proformaId)
    {
        $this->proformaId = $proformaId;
        $proforma = Proforma::find($proformaId);

        $this->logs = ProformaLog::where('proforma_id', $proformaId)
            ->join('process_tasks_mappings as ptm', function ($join) use ($proforma) {
                $join->on('proforma_log.process_sequence', '=', 'ptm.sequence')
                    ->where('ptm.process_id', $proforma->process_id);
            })
            ->join('tasks as t', 't.tasks_id', '=', 'ptm.tasks_id')
            ->where('process_sequence', '!=', 1)
            ->orderBy('created_at')
            ->get(['proforma_log.*', 't.tasks_name', 't.tasks_id']);

        //Getting the remaining tasks
        $this->remainingTasks = $this->getRemainingTasks($proforma->process_id);
    }

    private function getRemainingTasks($process_id)
    {
        if (sizeof($this->logs) == 0) {
            return [];
        }
        $lastSequence = $this->logs[sizeof($this->logs) - 1]->process_sequence;
        return ProcessTasksMapping::where('process_id', $process_id)
            ->where('sequence', '>', $lastSequence)
            ->orderBy('sequence')->get()->map(function ($item) {
                return $item->task;
            });
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.proforma-activity-logs');
    }
}
