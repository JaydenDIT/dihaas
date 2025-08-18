<?php

namespace App\Services;

use App\Models\Proforma;
use App\Models\ProformaLog;
use Exception;
use Illuminate\Support\Facades\Http;

class LogService
{
    public static function addProformaLog($param)
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
                'process_sequence' => $param['process_sequence'] ?? null
            ]);
        } catch (Exception $e) {
            // Handle exceptions, possibly log them or rethrow
            throw new Exception("Error adding log: " . $e->getMessage(), 0, $e);
        }
    }
}
