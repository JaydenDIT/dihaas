<?php

namespace App\Services;

use App\Models\Process;
use App\Models\ProformaModel;

class ProcessMatcher
{
    /**
     * Match process based on application data and assign process_id.
     */
    public static function matchAndAssignProcess(ProformaModel $application): ?int
    {
        $allProcesses = Process::all();

        foreach ($allProcesses as $process) {
            $criteriaList = json_decode($process->process_criteria, true);

            if (self::matchesCriteria($application, $criteriaList)) {
                // Assign and save
                $application->process_id = $process->process_id;
                $application->process_sequence = 1;
                $application->proforma_status = 'new';
                // $application->save();

                return $process->process_id;
            }
        }

        return null;
    }

    /**
     * Checks if the application matches all criteria.
     */
    protected static function matchesCriteria(ProformaModel $application, array $criteriaList): bool
    {
        foreach ($criteriaList as $criterion) {
            $field = $criterion['field'] ?? null;
            $operator = $criterion['operator'] ?? '==';
            $value = $criterion['value'] ?? null;

            if (!isset($application->$field)) {
                return false;
            }

            $appValue = $application->$field;

            switch ($operator) {
                case '==':
                    if ($appValue != $value) return false;
                    break;
                case '!=':
                    if ($appValue == $value) return false;
                    break;
                case '>':
                    if ($appValue <= $value) return false;
                    break;
                case '<':
                    if ($appValue >= $value) return false;
                    break;
                case '>=':
                    if ($appValue < $value) return false;
                    break;
                case '<=':
                    if ($appValue > $value) return false;
                    break;
                case 'in':
                    if (!in_array($appValue, (array) $value)) return false;
                    break;
                case 'not_in':
                    if (in_array($appValue, (array) $value)) return false;
                    break;
                default:
                    return false;
            }
        }

        return true;
    }
}
