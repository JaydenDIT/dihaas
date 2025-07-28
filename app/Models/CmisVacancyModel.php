<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmisVacancyModel extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = "cmis_vacancy_yearly";
    protected $fillable = [
        'id',
        'field_dept_code',
        'department',
        'dsg_srno',
        'designation',
        'post_count',
        'emp_cnt',
        'vacancy',
        'pull_year',
        'status',
        'current_employee_under_dih', 
        'total_post_vacant_dept', 
        'employee_under_dih', 
        'post_under_direct',
        'created_at',
        'updated_at'
       
        
    ];
}
