<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class CmisApiService
{
    private static string $cmis_token;
    private static string $cmis_api;

    /**
     * Initialize static config from env
     */
    private static function init(): void
    {
        if (!isset(self::$cmis_token) || !isset(self::$cmis_api)) {
            self::$cmis_token = env('CMIS_TOKEN', '');
            self::$cmis_api   = env('CMIS_API', '');

            if (self::$cmis_token === '' || self::$cmis_api === '') {
                throw new Exception("API cannot be fetched — missing CMIS_TOKEN or CMIS_API");
            }
        }
    }

    public static function apiEmployeeDetailByEIN(string $ein)
    {
        self::init();

        return Http::post(self::$cmis_api . '/get-employee-profile', [
            'ein'   => $ein,
            'token' => self::$cmis_token,
        ]);
    }

    public static function apiPostByDeptCd(string $dept_cd)
    {
        self::init();

        return Http::post(self::$cmis_api . '/get-all-dept-details-by-dept-cd', [
            'dept_code' => $dept_cd,
            'token'     => self::$cmis_token,
        ]);
    }

    public static function apiAdminDepartments(int $adm_dept_cd = 0)
    {
        self::init();

        $payload = ['token' => self::$cmis_token];
        if ($adm_dept_cd !== 0) {
            $payload['adm_dept_cd'] = $adm_dept_cd;
        }

        return Http::post(self::$cmis_api . '/get-adm-department-list', $payload);
    }

    public static function apiFieldDepartments(int $field_dept_cd = 0)
    {
        self::init();

        $payload = ['token' => self::$cmis_token];
        if ($field_dept_cd !== 0) {
            $payload['field_dept_cd'] = $field_dept_cd;
        }

        return Http::post(self::$cmis_api . '/get-department-list', $payload);
    }
    public static function apiAllPostUnderDepartment($dept_code)
    {
        self::init();

        $payload = ['token' => self::$cmis_token];
        $payload['dept_code'] = $dept_code;

        return Http::post(self::$cmis_api . '/get-all-dept-details-by-dept-cd', $payload);
    }
}
