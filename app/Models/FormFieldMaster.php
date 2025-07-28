<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormFieldMaster extends Model
{
    use HasFactory;
    public $table = "form_field_mast";
    protected $fillable = [
        'form_id',
        'controll_name',
        'iseditable',
        'description',
        'valid_to',
        'pan',
        'certificate',
        'active_status'
    ];
}
