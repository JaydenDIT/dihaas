<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Storage;

class CmisApiService
{
    private static string $cmis_token = '';
    private static string $cmis_api = '';

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

        // Always add token
        $payload['token'] = self::$cmis_token;

        try {
            return Http::timeout(30)
                ->post(self::$cmis_api . $endpoint, $payload);
        } catch (ConnectionException $e) {
            throw new Exception("CMIS API connection failed: " . $e->getMessage(), 0, $e);
        } catch (Exception $e) {
            throw new Exception("CMIS API request error: " . $e->getMessage(), 0, $e);
        }
    }

    public static function apiEmployeeDetailByEIN(string $ein)
    {

        return Storage::disk('private')->exists('empDetail.json')
            ? json_decode(Storage::disk('private')->get('empDetail.json'), true)
            : [];


        return self::safePost('/get-employee-profile', [
            'ein' => $ein,
        ]);
    }


    public static function apiAdminDepartments(int $adm_dept_cd = 0)
    {

        //test
        if ($adm_dept_cd !== 0) {
            return Storage::disk('private')->exists('departMentList.json')
                ? json_decode(Storage::disk('private')->get('departMentList.json'), true)
                : [];
        }
        return Storage::disk('private')->exists('allAdmList.json')
            ? json_decode(Storage::disk('private')->get('allAdmList.json'), true)
            : [];




        $payload = [];
        if ($adm_dept_cd !== 0) {
            $payload['adm_dept_cd'] = $adm_dept_cd;
        }

        return self::safePost('/get-adm-department-list', $payload);
    }

    public static function apiFieldDepartments(int $field_dept_cd = 0)
    {
        //test
        return Storage::disk('private')->exists('departMentList.json')
            ? json_decode(Storage::disk('private')->get('departMentList.json'), true)
            : [];

        $payload = [];
        if ($field_dept_cd !== 0) {
            $payload['field_dept_cd'] = $field_dept_cd;
        }

        return self::safePost('/get-department-list', $payload);
    }

    public static function apiAllPostUnderDepartment($dept_code)
    {
        //test
        return Storage::disk('private')->exists('allPost.json')
            ? json_decode(Storage::disk('private')->get('allPost.json'), true)
            : [];

        return self::safePost('/get-all-dept-details-by-dept-cd', [
            'dept_code' => $dept_code,
        ]);
    }
}
