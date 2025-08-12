<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadedDocument extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'uploaded_documents';
    protected $primaryKey = 'uploaded_document_id';
    protected $guarded = ['uploaded_document_id'];
    public function proforma()
    {
        return $this->belongsTo(Proforma::class);
    }

    public function document()
    {
        return $this->belongsTo(DocumentList::class, 'document_list_id');
    }
}
