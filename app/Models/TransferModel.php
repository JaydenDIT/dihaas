<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferModel extends Model
{
    use HasFactory;
    
    public $table = "transfers";
    protected $fillable = [
        'id',
        'ein',
        'transfer_dept_id',
        'transfer_post_id',
        'status',
        'remark',
        'remark_details',
        'updated_at',
        'created_at'
    ];
}