<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OriginalDataUpdateLog extends Model
{
    use HasFactory;
    public $table = "original_data_update_log";
    protected $fillable = [
        'ein',
        'form_id',
        'control_name',
        'remark',
        'ip_address',
        'updated_by'
    ];
}
