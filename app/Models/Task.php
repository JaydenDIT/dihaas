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
            ->withPivot('sequence', 'allow_drop', 'allow_reject', 'allow_esign')
            ->orderBy('sequence');;
    }

    /**
     * Determine whether the current task (job) is accessible by a given role.
     *
     * ---
     * Purpose:
     * Each task may be mapped to one or more roles in the `task_role_mapping` table.
     * This method checks if the specified role has access to the task by verifying
     * the existence of a mapping record.
     *
     * ---
     * @param  int  $role_id  The ID of the role to check accessibility for
     *
     * @return bool  True if the task is accessible by the given role, false otherwise
     */
    public function isAccessibleByRole($role_id): bool
    {
        //Getting count from the mapping table
        $count = TaskRoleMapping::where([
            'tasks_id' => $this->tasks_id,
            'role_id' => $role_id
        ])->count();

        return $count > 0;
    }
}
