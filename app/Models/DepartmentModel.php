<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentModel extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = "field_dept";
    protected $fillable = [
        'id',
        'ministry_id',
        'dept_id',
        'dept_name',
        'field_secrt',
        'dept_scope',
        'dept_short'
        
    ];
}
