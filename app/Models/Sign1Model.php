<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sign1Model extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = "dp_signing_authority";
    protected $fillable = [
        'id',
        'authority_name',
        'created_at',
        'updated_at',
    ];
}