<?php

namespace App\Services;

use App\Models\Proforma;
use App\Models\DocumentList;
use App\Models\UploadedDocument;

class DocumentRequirementService
{
    /**
     * Get all active documents and mark which ones are required.
     *
     * @param Proforma $application
     * @return \Illuminate\Support\Collection  Each row will have ->required = true/false
     */
    public static function getDocumentsWithRequirement(Proforma $application)
    {
        $criteriaConfig = config('documentCriteria');

        // Step 1: Determine required criteria keys
        $requiredKeys = [];

        foreach ($criteriaConfig as $docKey => $rules) {
            $isRequired = false;

            // Always required
            if (!empty($rules['required']) && $rules['required'] === true) {
                $isRequired = true;
            }

            // Required based on conditions
            if (!$isRequired && !empty($rules['required_if']) && is_array($rules['required_if'])) {
                foreach ($rules['required_if'] as $condition) {
                    [$table, $field, $operator, $value] = $condition;

                    if ($table === 'proforma') {
                        $sourceData = $application;
                    } elseif (isset($application->$table)) {
                        $sourceData = $application->$table;
                    } elseif (is_array($application) && isset($application[$table])) {
                        $sourceData = $application[$table];
                    } else {
                        $sourceData = null;
                    }


                    if (is_array($sourceData) || is_object($sourceData)) {
                        $fieldValue = is_object($sourceData)
                            ? ($sourceData->$field ?? null)
                            : ($sourceData[$field] ?? null);

                        if (self::matchesCondition($fieldValue, $operator, $value)) {
                            $isRequired = true;
                            break;
                        }
                    }
                }
            }

            if ($isRequired) {
                $requiredKeys[] = $docKey;
            }
        }

        // Step 2: Load uploaded documents for this Proforma
        $uploaded = UploadedDocument::where('proforma_id', $application->proforma_id)
            ->get()
            ->keyBy('document_list_id'); // <-- Faster lookup

        // Step 3: Load all active documents, tag required flag, and attach uploaded file if exists
        $documents = DocumentList::whereNull('deleted_at')
            ->get()
            ->map(function ($doc) use ($requiredKeys, $uploaded) {
                $doc->required = in_array($doc->document_criteria, $requiredKeys);
                $doc->uploaded_file = $uploaded->get($doc->document_list_id); // null if not uploaded
                return $doc;
            });

        return $documents;
    }

    /**
     * Compare value with condition
     */
    private static function matchesCondition($fieldValue, $operator, $value): bool
    {
        switch (strtolower($operator)) {
            case '=':
                return $fieldValue == $value;
            case '!=':
                return $fieldValue != $value;
            case 'in':
                return in_array($fieldValue, (array)$value);
            case 'not_in':
                return !in_array($fieldValue, (array)$value);
            default:
                return false;
        }
    }
}
