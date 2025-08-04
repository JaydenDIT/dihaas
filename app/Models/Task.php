<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'tasks_id';
    protected $guarded = ['tasks_id'];

    // Creator relationship
    public function creator()
    {
        return $this->belongsTo(User::class, 'create_by', 'user_id');
    }

    // Roles assigned to this task
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'tasks_role_mapping', 'tasks_id', 'role_id');
    }

    public function processes()
    {
        return $this->belongsToMany(Process::class, 'process_tasks_mappings', 'tasks_id', 'process_id')
            ->withPivot('sequence', 'allow_drop', 'allow_reject', 'allow_esign');
    }
}
