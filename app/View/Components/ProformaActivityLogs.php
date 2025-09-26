<?php

namespace App\View\Components;

use App\Models\Proforma;
use App\Models\ProformaLog;
use Illuminate\View\Component;

class ProformaActivityLogs extends Component
{
    public $proformaId;
    public $logs;
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
