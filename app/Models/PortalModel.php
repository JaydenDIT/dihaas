<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortalModel extends Model
{
    use HasFactory;
    protected $table = 'portal_name';
    public $fillable = [
        'software_name', 
        'department_name', 
        'govt_name',
        'developed_by',
        'copyright',
        'short_form_name'
    ];
}