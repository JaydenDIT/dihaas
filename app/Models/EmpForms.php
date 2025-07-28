<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpForms extends Model
{
    use HasFactory;
    public $table = "emp_forms";
    protected $fillable = [
        'form_id',
        'form_desc',
        'status'
    ];
}
