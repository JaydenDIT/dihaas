<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Process extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = "process_id";
    protected $table = "process";
    protected $guarded = ['process_id'];
    public $timestamps = false;

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'process_tasks_mappings', 'process_id', 'tasks_id')
            ->withPivot('sequence', 'allow_drop', 'allow_reject', 'allow_esign')
            ->withTimestamps()
            ->orderBy('sequence');
    }
}
