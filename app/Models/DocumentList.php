<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentList extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'document_list';
    protected $primaryKey = 'document_list_id';

    protected $fillable = [
        'document_name',
        'document_criteria',
        'max_size_kb',
    ];
}
