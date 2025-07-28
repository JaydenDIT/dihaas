<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssemblyConstituency extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = "assembly_constituency";
    protected $fillable = [
        'state_code',
        'district_cd',
        'assmb_contcy_cd',
        'assembly_constcy_desc',
    ];
}
