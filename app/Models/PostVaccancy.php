<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PostVaccancy extends Model
{
    use HasFactory;
    protected $table = "post_vaccancies";
    protected $primaryKey = "vaccancy_id";

    protected $fillable = [
        'adm_dept_cd',
        'adm_dept_name',
        'field_dept_cd',
        'field_dept_name',
        'dsg_srno',
        'dsg_name',
        'no_of_posts_dia',
        'no_of_posts_dr',
        'created_by',
    ];

    public function scopeGetVaccancy($query)
    {
        //How do I get this from here?

        return $query->select(
            'adm_dept_cd',
            'adm_dept_name',
            'field_dept_cd',
            'field_dept_name',
            'dsg_srno',
            'dsg_name',
            DB::raw('sum(no_of_posts_dia) as dia_vacc_posts'),
            DB::raw('sum(no_of_posts_dr) as dr_vacc_posts'),
        )->groupBy(
            'adm_dept_cd',
            'adm_dept_name',
            'field_dept_cd',
            'field_dept_name',
            'dsg_srno',
            'dsg_name',
        )->orderBy('adm_dept_name')->get();
    }
}
