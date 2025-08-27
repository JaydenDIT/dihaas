<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UoGeneration extends Model
{
    use HasFactory;

    protected $fillable = [
        'proforma_id',
        'approve_for_uo_id',
        'uo_number',
        'generated_by',
        'generated_on',
        'is_applicant_choice_post',
        'alloted_adm_dept_cd',
        'alloted_adm_dept_desc',
        'alloted_field_dept_cd',
        'alloted_field_dept_desc',
        'alloted_dsg_srno',
        'alloted_dsg_desc',
        'alloted_group_code',
        'signed_proforma_doc',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uo_number)) {
                $model->uo_number = Str::uuid();
            }
        });
    }

    public function proforma()
    {
        return $this->belongsTo(Proforma::class, 'proforma_id', 'proforma_id');
    }
}
