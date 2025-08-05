<?php

use App\Models\ProcessTasksMapping;
use App\Models\ProformaModel;
use App\Models\Task;
use App\Models\TaskRoleMapping;
use App\Models\WebSettingModel;
use Illuminate\Support\Facades\Auth;

function generateRandomString($length = 32)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/*php function to generate random unique id*/
function randId($length = 32)
{
    $id = (uniqid() . rand() . time() . generateRandomString($length));
    $char = str_shuffle($id);
    for ($i = 0, $rand = '', $l = strlen($char) - 1; $i < $length; $i++) {
        $rand .= $char[mt_rand(0, $l)];
    }
    return $rand;
}
/*function to get client ip*/
function get_client_ip()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP')) {
        $ipaddress = getenv('HTTP_CLIENT_IP');
    } else if (getenv('HTTP_X_FORWARDED_FOR')) {
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    } else if (getenv('HTTP_X_FORWARDED')) {
        $ipaddress = getenv('HTTP_X_FORWARDED');
    } else if (getenv('HTTP_FORWARDED_FOR')) {
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    } else if (getenv('HTTP_FORWARDED')) {
        $ipaddress = getenv('HTTP_FORWARDED');
    } else if (getenv('REMOTE_ADDR')) {
        $ipaddress = getenv('REMOTE_ADDR');
    } else {
        $ipaddress = 'UNKNOWN';
    }
    return $ipaddress;
}

//check for empty object
function isEmptyObject($object): bool
{
    if (is_null($object) || trim(json_encode($object)) == "{}") {
        return true;
    }
    return false;
}


/*--------------------- FILE RELATED FUNCTIONS --------------------------*/
//file reading and writing functions
function downloadFile($file_path, $flag = false)
{
    if (file_exists($file_path)) {
        header('Content-Description: File Transfer');
        //header('Content-Type: application/octet-stream');
        if ($flag == true) {
            //download popup will display
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        } else {
            header('Content-Type: ' . mime_content_type($file_path));
        }
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        flush(); // Flush system output buffer
        readfile($file_path);
        die();
    } else {
        http_response_code(404);
        die("File not found.");
    }
}



function getDiff($date1, $date2)
{
    $diff = abs(strtotime($date2) - strtotime($date1));

    $aDay = 60 * 60 * 24;
    $aYear = 365 * $aDay;
    $aMonth = 30 * $aDay;

    $result = "";

    //getting year quotient of all sec by secInYear
    $year = floor($diff /  $aYear);
    $leftSec = $diff % $aYear;

    $result .= ($year == 0) ? "" : $year . " years ";

    //getting remaining months
    $month = floor($leftSec / $aMonth);
    $leftSec = $leftSec % $aMonth;
    $result .= ($month == 0) ? "" : $month . " months ";

    //getting remaining days
    $day = floor($leftSec / $aDay);
    $leftSec = $leftSec % $aDay;
    $result .= ($day == 0) ? "" : $day . " days ";

    //getting times
    $hour = floor($leftSec / 3600); //hours
    $leftSec = $leftSec % 3600;
    $result .= ($hour == 0) ? "" : $hour . " hours ";



    return ($result == "") ? "Generating link for First Appeal..." : $result . " left";


    $min = floor($leftSec / 60); //minutes
    $result .= ($min == 0) ? "" : $min . " min ";

    $sec = $leftSec % 60; //seconds
    $result .= ($sec == 0) ? "" : $sec . " sec ";

    return ($result == "") ? "N/A" : $result . " left";
}



function leftTime($date)
{
    return getDiff(date("Y-m-d H:i:s"), $date);
}


//to get the finalcial year of a date
function getFinancialYear($date)
{
    $date = str_replace("/", "-", $date); // if there is slash '/' instead of hyphen '-'
    $datetime = strtotime($date);

    $year = date("Y", $datetime);
    $year_mark = strtotime("31-03-" . $year); //year mark is nothing but the end of a financial year

    if ($datetime > $year_mark) {
        $from_year = $year;
        $to_year = $year + 1;
    } else {
        $from_year = $year - 1;
        $to_year = $year;
    }
    return $from_year . "-" . $to_year;
}

//-------- header function ----------/
if (!function_exists('getallheaders')) {
    //this function is usefull when nginx is used instead of apache
    function getallheaders()
    {

        $headers = [];

        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(
                    ' ',
                    '-',
                    ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))
                )] = $value;
            }
        }
        return $headers;
    }
}

// function get_setting($option_key){
//     $data = WebSettingModel::where('option_key', $option_key)->first();
//     if(is_null($data)){
//         return "";
//     }
//     return $data->option_value;
// }

//function to get the current, previous and next task
function sixDigitsEin($value)
{
    return str_pad($value, 6, '0', STR_PAD_LEFT);
}

function getPrevNextTasks($ein)
{
    $application = ProformaModel::findOrFail(sixDigitsEin($ein));

    $current_sequence = $application->process_sequence;
    $process_id = $application->process_id;

    // Get all mappings for the process ordered by sequence
    $mappings = ProcessTasksMapping::where('process_id', $process_id)
        ->orderBy('sequence')
        ->get();

    $user_role_id = Auth::user()->role_id;

    $tasks = [
        'previous' => null,
        'current' => null,
        'next' => null,
        'can_perform' => false,
    ];

    foreach ($mappings as $map) {
        $task_data = [
            'tasks_id' => $map->tasks_id,
            'sequence' => $map->sequence,
            'tasks_name' => $map->task->tasks_name,
            'tasks_duty' => $map->task->tasks_duty,
            'allow_reject' => $map->allow_reject,
            'allow_drop' => $map->allow_drop,
            'allow_esign' => $map->allow_esign,
        ];

        if ($map->sequence == $current_sequence - 1) {
            $tasks['previous'] = $task_data;
        } elseif ($map->sequence == $current_sequence) {
            $tasks['current'] = $task_data;

            // Check if current user role is allowed to perform this task
            $is_allowed = TaskRoleMapping::where('tasks_id', $map->tasks_id)
                ->where('role_id', $user_role_id)
                ->exists();

            $tasks['can_perform'] = $is_allowed;
        } elseif ($map->sequence == $current_sequence + 1) {
            $tasks['next'] = $task_data;
        }
    }

    return $tasks;
}
