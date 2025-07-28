<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmisVacancyHistoryModel extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = "cmis_vacancy_history";
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
        'created_at',
        'updated_at'
       
        
    ];
}
