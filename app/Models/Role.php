<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $primaryKey = 'role_id';
    public $timestamps = false;
    protected $guarded = ['role_id'];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_role_mapping', 'role_id', 'menu_id');
    }

    public function duties()
    {
        return $this->belongsToMany(Task::class, 'tasks_role_mapping', 'role_id', 'tasks_id');
    }
}
