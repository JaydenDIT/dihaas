<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileHistoryModel extends Model
{
    use HasFactory;
    protected $table = 'files_uploaded_history';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'ein',
        'appl_number',
        'uploaded_by',
        'file_name',
        'file_path',
        'doc_id',
       
    ];
}