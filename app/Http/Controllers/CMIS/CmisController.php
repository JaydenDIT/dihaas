<?php

namespace App\Http\Controllers\CMIS;

use App\Http\Controllers\Controller;
use App\Services\CmisApiService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CmisController extends Controller
{
    //
    private $cmis_token;
    private  $cmis_api;
    public function __construct()
    {
        $this->cmis_token = env('CMIS_TOKEN', '');
        $this->cmis_api = env('CMIS_API', '');

        if ($this->cmis_token == '' || $this->cmis_api  == '') {
            response()->json(['message' => 'API cannot be fetch'], 503);
        }
    }


    public function getEmployeeDetailByEIN(String $id)
    {
        try {
            $response = CmisApiService::apiEmployeeDetailByEIN($id);

            return response()->json(
                $response->json(),
                $response->status()
            );
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    public function getPostByDeptCd(String $id)
    {
        try {
            $response = CmisApiService::apiAllPostUnderDepartment($id);

            return response()->json(
                $response->json(),
                $response->status()
            );
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    public function getDepartmentByAdmCd(int $id)
    {
        try {
            $response = CmisApiService::apiAdminDepartments($id);

            return response()->json(
                $response->json(),
                $response->status()
            );
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
