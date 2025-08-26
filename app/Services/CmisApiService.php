<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Storage;

class CmisApiService
{
    private static string $cmis_token = '';
    private static string $cmis_api   = '';
    public static int $status_code = 0;
    public static $error_response = null;

    private static function init(): void
    {
        if (self::$cmis_token === '' || self::$cmis_api === '') {
            self::$cmis_token = env('CMIS_TOKEN', '');
            self::$cmis_api   = env('CMIS_API', '');

            if (self::$cmis_token === '' || self::$cmis_api === '') {
                throw new Exception("API cannot be fetched — missing CMIS_TOKEN or CMIS_API");
            }
        }
    }

    private static function safePost(string $endpoint, array $payload = [])
    {
        self::init();

        $payload['token'] = self::$cmis_token;

        try {
            $result = Http::timeout(30)
                ->post(self::$cmis_api . $endpoint, $payload);

            if ($result->failed()) {
                self::$status_code =  $result->status();
                self::$error_response = json_decode($result->getBody(), true);
                throw new Exception("CMIS API request failed.");
            }

            return $result->json();
        } catch (ConnectionException $e) {
            throw new Exception("CMIS API connection failed: " . $e->getMessage(), 0, $e);
        } catch (Exception $e) {
            throw new Exception("CMIS API request error: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Fetch employee details by EIN
     */
    public static function apiEmployeeDetailByEIN(string $ein): array
    {
        if (env('CMIS_MODE', 'offline') === 'offline') {
            return Storage::disk('private')->exists('empDetail.json')
                ? json_decode(Storage::disk('private')->get('empDetail.json'), true)
                : [];
        } else {
            return self::safePost('/get-employee-profile', [
                'ein' => $ein,
            ]);
        }
    }

    /**
     * Fetch admin/ministry list
     * if $adm_dept_cd is provided, fetch specific ministry with its department_list under it
     */
    public static function apiAdminDepartments(int $adm_dept_cd = 0): array
    {
        if (env('CMIS_MODE', 'offline') === 'offline') {
            if ($adm_dept_cd !== 0) {
                return Storage::disk('private')->exists('departMentList.json')
                    ? json_decode(Storage::disk('private')->get('departMentList.json'), true)
                    : [];
            }
            return Storage::disk('private')->exists('allAdmList.json')
                ? json_decode(Storage::disk('private')->get('allAdmList.json'), true)
                : [];
        } else {
            $payload = [];
            if ($adm_dept_cd !== 0) {
                $payload['adm_dept_cd'] = $adm_dept_cd;
            }

            return self::safePost('/get-adm-department-list', $payload);
        }
    }

    /**
     * Fetch field departments
     * if $field_dept_cd is provided, fetch only the data for that department
     */
    public static function apiFieldDepartments(int $field_dept_cd = 0): array
    {
        if (env('CMIS_MODE', 'offline') === 'offline') {
            if ($field_dept_cd !== 0) {
                return Storage::disk('private')->exists('departmentDetail.json')
                    ? json_decode(Storage::disk('private')->get('departmentDetail.json'), true)
                    : [];
            }
            $departMentList = Storage::disk('private')->exists('departMentList.json')
                ? json_decode(Storage::disk('private')->get('departMentList.json'), true)
                : [];
            return $departMentList['field_dept'] ?? [];
        } else {
            $payload = [];
            if ($field_dept_cd !== 0) {
                $payload['field_dept_cd'] = $field_dept_cd;
            }

            return self::safePost('/get-department-list', $payload);
        }
    }

    /**
     * Fetch all posts under department
     */
    public static function apiAllPostUnderDepartment(string $dept_code): array
    {
        if (env('CMIS_MODE', 'offline') === 'offline') {
            return Storage::disk('private')->exists('allPost.json')
                ? json_decode(Storage::disk('private')->get('allPost.json'), true)
                : [];
        } else {
            return self::safePost('/get-all-dept-details-by-dept-cd', [
                'dept_code' => $dept_code,
            ]);
        }
    }
}
