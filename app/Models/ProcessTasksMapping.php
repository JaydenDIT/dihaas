<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessTasksMapping extends Model
{
    protected $primaryKey = 'process_tasks_mapping_id';
    protected $table = "process_tasks_mappings";
    protected $guarded = ['process_tasks_mapping_id'];

    public $timestamps = false;

    public function task()
    {
        return $this->belongsTo(Task::class, 'tasks_id', 'tasks_id');
    }

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id', 'process_id');
    }
}
