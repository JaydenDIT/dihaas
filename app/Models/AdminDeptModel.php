<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminDeptModel extends Model
{
    use HasFactory;
    public $table = "adm_dept";
    protected $fillable = [
        'ministry_id',
        'adm_bdgt_cd',
        'ministry',
        'adm_dept_cd_char'
    ];
}
