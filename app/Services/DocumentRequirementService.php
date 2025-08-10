<?php

namespace App\Services;

/**
 * 
 *  Uses the document criteria configuration to determine which documents are required for a given application.
 *  $missingDocs = DocumentRequirementService::check($apps);
 *  dd($missingDocs);
 */



class DocumentRequirementService
{
    /**
     * Check which documents are required for the given application.
     *
     * @param object|array $apps  The application data (can be Eloquent model or array)
     * @return array
     */
    public static function check($apps)
    {
        $criteria = config('document_criteria');
        $missingDocs = [];

        foreach ($criteria as $docKey => $rules) {
            $isRequired = false;

            // 1. If always required
            if (!empty($rules['required']) && $rules['required'] === true) {
                $isRequired = true;
            }

            // 2. If required based on conditions
            if (!empty($rules['required_if']) && is_array($rules['required_if'])) {
                foreach ($rules['required_if'] as $condition) {
                    // $condition = ['table', 'field', 'operator', 'value']
                    [$table, $field, $operator, $value] = $condition;

                    $sourceData = isset($apps->$table) ? $apps->$table : (is_array($apps) && isset($apps[$table]) ? $apps[$table] : null);

                    if (is_array($sourceData) || is_object($sourceData)) {
                        $fieldValue = is_object($sourceData) ? ($sourceData->$field ?? null) : ($sourceData[$field] ?? null);

                        if (self::matchesCondition($fieldValue, $operator, $value)) {
                            $isRequired = true;
                            break;
                        }
                    }
                }
            }

            if ($isRequired) {
                $missingDocs[] = $docKey;
            }
        }

        return $missingDocs;
    }

    /**
     * Compare field value with condition
     */
    private static function matchesCondition($fieldValue, $operator, $value)
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
