<?php

namespace App\Http\Controllers\CMIS;

use App\Http\Controllers\Controller;
use App\Services\CmisApiService;
use Exception;

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
            throw new Exception("API cannot be fetched — missing CMIS_TOKEN or CMIS_API");
        }
    }


    public function getEmployeeDetailByEIN(String $id)
    {
        try {
            $result = CmisApiService::apiEmployeeDetailByEIN($id);
            return response()->json( //test
                $result,
                200
            );

            return response()->json(
                $result->json(),
                $result->status()
            );
        } catch (Exception $e) {
            $response = is_null(CmisApiService::$error_response) ? [
                'message' => 'Internal Server Error',
                'error'   => $e->getMessage()
            ] : CmisApiService::$error_response;

            return response()->json($response, CmisApiService::$status_code);
        }
    }
    public function getPostByDeptCd(String $id)
    {
        try {
            $result = CmisApiService::apiAllPostUnderDepartment($id);
            return response()->json( //test
                $result,
                200
            );
            return response()->json(
                $result->json(),
                $result->status()
            );
        } catch (Exception $e) {
            /* return response()->json([
                'message' => 'Internal Server Error',
                'error'   => $e->getMessage()
            ], 500); */
            return $this->sendResponse($e);
        }
    }
    public function getDepartmentByAdmCd(int $id)
    {
        try {
            $result = CmisApiService::apiAdminDepartments($id);
            return response()->json( //test
                $result,
                200
            );
            return response()->json(
                $result->json(),
                $result->status()
            );
        } catch (Exception $e) {
            return $this->sendResponse($e);
        }
    }

    private function sendResponse(Exception $e)
    {
        $response = is_null(CmisApiService::$error_response) ? [
            'message' => 'Internal Server Error',
            'error'   => $e->getMessage()
        ] : CmisApiService::$error_response;

        return response()->json($response, CmisApiService::$status_code);
    }
}
