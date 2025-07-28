<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRefinfoModel extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = "user_ref_info";
    protected $fillable = [
        'user_id',
        'reference_type',
        "ref_no",
        'issued_by',
        'valid_from',
        'valid_to',
        'status'
    ];
}