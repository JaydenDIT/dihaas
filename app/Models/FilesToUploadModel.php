<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilesToUploadModel extends Model
{
    use HasFactory;
    
    public $table = "doc_table";
    protected $fillable = [
        'doc_id',
        'doc_name',
        'is_mandatory',
        'remarks',
        'status',
        'doc_type',
        'esign_yn',
        'description',
        'id'
    ];
}
