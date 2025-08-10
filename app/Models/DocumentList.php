<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentList extends Model
{
    use HasFactory;
    protected $table = 'document_list';
    protected $primaryKey = 'document_list_id';

    protected $fillable = [
        'document_name',
        'document_criteria'
    ];
}
