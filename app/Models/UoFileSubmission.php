<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UoFileSubmission extends Model
{
    use HasFactory;
    protected $table = "uo_file_submissions";
    protected $primaryKey = 'uo_file_submission_id';
    protected $guarded = ['uo_file_submission_id'];

    public function proforma()
    {
        return $this->belongsTo(Proforma::class, 'proforma_id', 'proforma_id');
    }
}
