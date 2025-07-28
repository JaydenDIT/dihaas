<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class applicants_statusModel extends Model
{
    use HasFactory;
    public $table = "applicants_status";

    protected $fillable = [
        'id',
        'ein',
        'appl_number',
        'remark',        
        'remark_details',
        'remark_date',
        'entered_by',
        'efile_dp',
        'dp_file_link',
        'efile_ad',
        'ad_file_link',
        'ad_efile_increment',
        'dp_efile_increment',
        'role_id'
       
    ];
}
