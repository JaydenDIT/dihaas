<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayScale extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = "pay_scale";
    protected $fillable = [
        'psc_paycomm_cd',
        'psc_scale_cd',
        'pfm_for_cd',
        'psc_group_cd',
        'psc_lo_limit',
        'psc_inc1',
        'psc_stage1',
        'psc_inc2',
        'psc_stage2',
        'psc_inc3',
        'psc_stage3',
        'psc_inc4',
        'psc_up_limit',
        'psc_dscr',
        'psc_wef',
        'psc_to_date',
        'psc_payband',
        'psc_grade',
        'psc_gradepay',
        'psc_gis_group_cd',
        'psc_entry_dtts',
        'psc_upd_dtts',
        'psc_verif_flg',
        'scale_id',
        'group_cd_old',
    ];
}
