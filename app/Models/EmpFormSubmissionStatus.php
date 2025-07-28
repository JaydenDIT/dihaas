<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpFormSubmissionStatus extends Model
{
    use HasFactory;
    public $table = "emp_form_submission_status";
    
    protected $fillable = [
        'ein',
        'form_id',
        'form_desc',
        'submit_status',
        'client_ip'
    ];
}
