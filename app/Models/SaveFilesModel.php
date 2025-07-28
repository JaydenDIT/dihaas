<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaveFilesModel extends Model
{
    use HasFactory;

    protected $table = 'save_files';

    protected $fillable = [
        'id',
        'ein',
        'appl_number',
        'applicant_application_file',
        'death_certificate_file',
        'termination_order_file',
        'matriculation_file',
        'educational_qualification_file',
        'additional_qualification_file',
        'affidavit_from_family_file',
        'affidavit_from_applicant_file',
        'physically_handicapped_file',
        'others',
        'uploaded_by'
    ];
}
