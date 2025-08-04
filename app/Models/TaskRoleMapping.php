<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskRoleMapping extends Model
{
    protected $table = 'tasks_role_mapping';
    protected $primaryKey = 'tasks_role_mapping_id';
    public $timestamps = false;
    protected $guarded = ['tasks_role_mapping_id'];

    public function task()
    {
        return $this->belongsTo(Task::class, 'tasks_id', 'tasks_id');
    }
}
