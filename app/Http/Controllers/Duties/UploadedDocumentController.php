<?php

namespace App\Http\Controllers\Duties;

use App\Http\Controllers\Controller;
use App\Models\UploadedDocument;
use App\Models\DocumentList;
use App\Models\Proforma;
use App\Services\DocumentRequirementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UploadedDocumentController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'proforma_id'       => 'required|integer',
                'document_list_id'  => 'required|integer',
                'document_file'     => 'required|file', // 20 MB
            ]);
            $proformaId     = $request->input('proforma_id');
            $documentListId = $request->input('document_list_id');
            $file           = $request->file('document_file');

            $documentList = DocumentList::findOrFail($documentListId);
            $maxSizeKB = $documentList->max_size_kb ?? 2048; // fallback to 2MB
            $maxSizeBytes = $maxSizeKB * 1024;

            // Validate file size dynamically
            if ($file->getSize() > $maxSizeBytes) {
                return response()->json([
                    'message' => "File exceeds maximum size of {$maxSizeKB} KB"
                ], 422);
            }

            // Check if existing document for same proforma & doc type
            $doc = UploadedDocument::where('proforma_id', $proformaId)
                ->where('document_list_id', $documentListId)
                ->first();

            if ($doc && Storage::disk('public')->exists($doc->file_path)) {
                Storage::disk('public')->delete($doc->file_path);
            }

            if (!$doc) {
                $doc = new UploadedDocument();
                $doc->proforma_id = $proformaId;
                $doc->document_list_id = $documentListId;
            }

            // Store new file
            $path = $file->store('uploads/documents', 'public');

            // Update DB record
            $doc->file_name = $documentList->document_name . '.' . $file->getClientOriginalExtension();
            $doc->file_type = $file->getClientMimeType();
            $doc->file_size = $file->getSize();
            $doc->file_path = $path;
            $doc->save();

            DB::commit();
            $proforma = Proforma::findOrFail($proformaId);
            $documents = DocumentRequirementService::getDocumentsWithRequirement($proforma);
            $requiredDocumentsLeft = $documents
                ->where('required', true)
                ->filter(function ($doc) {
                    return empty($doc->uploaded_file);
                })
                ->count();
            return response()->json([
                'message' => 'Document uploaded successfully',
                'data' => [
                    'id'        => $doc->uploaded_document_id,
                    'file_name' =>  $documentList->document_name . '.' . $file->getClientOriginalExtension(),
                    'file_type' => $doc->file_type,
                    'file_size' => $doc->file_size,
                    'url'       => route('duties.upload.document.load', $doc->uploaded_document_id),
                    'requiredDocumentsLeft' => $requiredDocumentsLeft
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Document upload failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }



    public function loadFile($id)
    {
        $doc = UploadedDocument::findOrFail($id);

        // Check if file exists
        $path = storage_path("app/public/{$doc->file_path}");
        if (!file_exists($path)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        // Detect mime type
        $mimeType = mime_content_type($path);

        // Stream the file instead of forcing download
        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $doc->file_name . '"'
        ]);
    }





    public function destroy($id)
    {
        $doc = UploadedDocument::findOrFail($id);

        // Delete file from storage
        if (Storage::disk('public')->exists($doc->file_path)) {
            Storage::disk('public')->delete($doc->file_path);
        }

        $doc->delete();

        return response()->json([
            'message' => 'Document deleted successfully'
        ]);
    }
}
