<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sign2Model extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = "department_signing_authority";
    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at',
    ];
}