<?php

namespace App\Services;

use App\Models\Proforma;
use App\Models\ProformaLog;
use Exception;
use Illuminate\Support\Facades\Http;

class ProformaService
{
    public static function addLog($param)
    {
        try {
            // Validate the parameters
            if (!isset($param['proforma_id'], $param['action_by'], $param['action_name'])) {
                throw new Exception("Missing required parameters for logging.");
            }
            // Create a new log entry
            ProformaLog::create([
                'proforma_id' => $param['proforma_id'],
                'action_by' => $param['action_by'],
                'action_name' => $param['action_name'],
                'action_remark' => $param['action_remark'] ?? null,
            ]);
        } catch (Exception $e) {
            // Handle exceptions, possibly log them or rethrow
            throw new Exception("Error adding log: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Change the status of a Proforma.
     * This might not be used in the current context, but it's a common operation.
     */
    public static function changeStatus(Proforma $proforma, string $status): void
    {
        try {
            // Update the proforma status
            $proforma->update(['status' => $status]);
        } catch (Exception $e) {
            // Handle exceptions, possibly log them or rethrow
            throw new Exception("Error changing status: " . $e->getMessage(), 0, $e);
        }
    }
}
