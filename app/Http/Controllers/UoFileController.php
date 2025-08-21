<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UoFileSubmission;

//Controller for getting the UO file

class UoFileController extends Controller
{
    // end point for getting the uo file from proforma id
    public function getFile(Request $request, $proforma_id)
    {
        $uoFile = UoFileSubmission::where('proforma_id', $proforma_id)->first();
        // Check if file exists
        $path = storage_path("app/public/{$uoFile->file_path}");
        if (!file_exists($path)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        // Detect mime type
        $mimeType = mime_content_type($path);

        // Stream the file instead of forcing download
        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $uoFile->file_name . '"'
        ]);
    }
}
