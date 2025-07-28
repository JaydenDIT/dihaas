<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileMandatoryModel extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = "file_upload_mandatory";
    protected $fillable = [
        'is_mandatory'
        
    ];
}
