<?php

namespace App\Services;

use App\Models\UoFileSubmission;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Service class responsible for handling storage and persistence of UO files.
 *
 * This class provides functionality to:
 * - Store uploaded files in a defined storage disk.
 * - Associate stored files with one or more proformas in the database.
 *
 */
class UOFileStorageService
{
    /**
     * Stores the uploaded file into the configured storage disk.
     *
     * @param UploadedFile $file The uploaded file instance (usually from $request->file('document_file')).
     *
     * @return string The relative storage path of the file (e.g. "uploads/documents/xyz.pdf").
     *
     * @throws Exception If file storage fails.
     */
    public static function storeFile($file): string
    {
        $path = $file->store('uploads/documents', 'public');
        return $path;
    }

    /**
     * Method to set the physical storage path to uo_file_submissions table for one or more
     * proformas
     * Saves the uploaded UO file and associates it with one or more proformas.
     *
     * Workflow:
     * 1. Stores the file in the configured storage disk.
     * 2. Iterates through provided proforma IDs.
     * 3. For each proforma:
     *    - Deletes any previously stored file.
     *    - Creates or updates the UoFileSubmission record.
     * 
     * @param UploadedFile $file The uploaded file instance
     *                           (from $request->file('document_file')).
     * @param array<int> $proforma_ids Array of proforma IDs to associate with the file.
     *
     * @return void
     *
     * @throws Exception If no proforma IDs are provided.
     */
    public static function saveUOFile($file, array $proforma_ids)
    {
        if (sizeof($proforma_ids) == 0) {
            throw new Exception("At least one proforma id is required");
        }

        /**
         * Store the file and get the path
         */
        $path = self::storeFile($file);

        // Search for all proforma ids
        foreach ($proforma_ids as $id) {
            $doc = UoFileSubmission::where('proforma_id', $id)
                ->first();

            // Delete old file if it exists
            if ($doc && Storage::disk('public')->exists($doc->file_path)) {
                Storage::disk('public')->delete($doc->file_path);
            }

            // Create new record if none exists
            if (!$doc) {
                $doc = new UoFileSubmission();
                $doc->proforma_id = $id;
            }

            // Update with new file details
            $doc->file_name = $file->getClientOriginalName();
            $doc->file_path = $path;
            $doc->uploaded_by = Auth::user()->user_id;
            $doc->save();
        }
    }
}
