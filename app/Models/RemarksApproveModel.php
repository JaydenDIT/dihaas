<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemarksApproveModel extends Model
{
    use HasFactory;
    
    public $table = "remarks_approve";
    protected $fillable = [
        'id',
        'probable_remarks',
        'description',
        'created_at',
        'updated_at'
    ];
}
