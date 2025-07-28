<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayCommissionModel extends Model
{
    use HasFactory;
    public $table = "pay_commission_tbl";
    protected $fillable = [
        'code_val',
        'code_text',
    ];
}
