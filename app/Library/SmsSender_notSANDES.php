<?php

/**
 * Description of SmsSender: This class is for sending sms and email it also include the otp
 * @author:  Konsam Malemngalba Singh
 * 
 **/

namespace App\Library;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyMail;
use Exception;

class SmsSender_notSANDES
{

    //public static $otp_text = " is the OTP for registration on Hon'ble Justice Gita Mittal Committee -JGMC";
    public static $otp_text = " is the OTP to login to https://relief4manipur.nic.in";


    public static function sendsms($mobileno, $OTP)
    {
        //return true; //check
        try {

            $message = urlencode($OTP . self::$otp_text);
            //$message = urlencode("123434 is the OTP to login to https://relief4manipur.nic.in");

            $mobileno = '91' . $mobileno;

            $username = 'rlfmnpr.otp';
            $pin = '295b3QsD';
            $sender = 'NICSMS';
            $entityId = '110100001364';
            $templateId = '1107169875332640178';

            $baseurl = 'https://hydgw.sms.gov.in/failsafe/MLink';

            $url = $baseurl;
            $postfields = 'username=' . $username . '&pin=' . $pin . '&mnumber=' . $mobileno . '&message=' . $message . '&signature=' . $sender . '&dlt_entity_id=' . $entityId . '&dlt_template_id=' . $templateId;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            //echo '=========='.curl_error($ch);
            curl_close($ch);

            if ($response) {
                $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            } else {
                throw new Exception("Cannot Connect.");
            }
        } catch (Exception $e) {
            throw $e;
        }

        return true;
    }

    public static function sendsmsSandes($mobileno, $OTP)
    {
        // return true; //check
        try {

            // $message = urlencode($OTP.self::$otp_text);

            // //$message = urlencode($message);
            // $sender = 'JGMC'; 
            // $apikey = '1274429253g162qmr86l39u2668ipr9vp8g2';
            // $baseurl = 'https://instantalerts.co/api/web/send?apikey='.$apikey;

            // $url = $baseurl.'&sender='.$sender.'&to='.$mobileno.'&message='.$message;    


            // sandes
            $msg = urlencode('Your OTP to login to Justice Gita Mittal Commission is ' . $OTP . '. Validity of this OTP is 5 minutes. Do not share with anyone.');

            //--- curl stated ---
            $url = 'http://localhost:8021/send?receiverid=' . $mobileno . '&msg=' . $msg . '&priority=high-volatile';




            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, false);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            if ($response) {
                $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            } else {
                throw new Exception("Cannot Connect.");
            }
        } catch (Exception $e) {
            throw $e;
        }

        return true;
    }




    public static function sendCurl($url, $params)
    {
        try {
            //url-ify the data for the POST
            $fields_string = http_build_query($params);

            //open connection
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_USERAGENT, 'JusticeGitaMittalCommsission');
            curl_setopt($ch, CURLOPT_REFERER, env('RTI_APP_URL'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            //execute post
            $response = curl_exec($ch);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            //close connection
            curl_close($ch);

            if (!$response) {
                throw new Exception("Cannot Connect.");
            }
        } catch (Exception $e) {
            throw $e;
        }

        return  $response;
    }




    public static function sendEmail($email, array $mailData)
    {
        //return true;
        try {
            Mail::to($email)->send(new NotifyMail($mailData));
        } catch (Exception $e) {
            throw $e;
        }
        return true;
    }

    public static function generateOTP($key)
    {
        $number = rand(111111, 999999);
        // $number = 123456;
        Session::put([
            $key => $number,
            'expiry_time' => 60 * 10
        ]);
        return $number;
    }

    public static function sendEmailOtp($email, $key)
    {

        $otp = self::generateOTP($key);

        $mailData = [
            "view" => "email.otpMail",
            "subject" => "OTP verification",
            "title" => "Justice Gita Mittal Commission",
            "body" => $otp
        ];

        return self::sendEmail($email, $mailData);
    }


    public static function resend_EmailOtp($email, $key)
    {
        if (Session::has($key)) {
            $otp = Session::get($key);
        } else {
            $otp = self::generateOTP($key);
        }
        $mailData = [
            "view" => "email.otpMail",
            "subject" => "OTP verification",
            "title" => "Justice Gita Mittal Commission",
            "body" => $otp
        ];


        return self::sendEmail($email, $mailData);
    }




    public static function check_mobile(int $mobile)
    {
        $mobileregex = "/^[6-9][0-9]{9}$/";
        if (!preg_match($mobileregex, $mobile)) {
            return false;
        }
        return true;
    }


    public static function send_otp($mobile, $key)
    {
        if (!self::check_mobile((int) $mobile)) {
            return false;
        }
        $otp = self::generateOTP($key);

        return  self::sendsms($mobile, $otp);
    }


    public static function resend_otp($mobile, $key)
    {
        if (!self::check_mobile((int) $mobile)) {
            return false;
        }
        if (Session::has($key)) {
            $otp = Session::get($key);
        } else {
            $otp = self::generateOTP($key);
        }
        return   self::sendsms($mobile, $otp);
    }

    public static function check_otp($otp, $key)
    {

        if (!Session::has($key)) {
            return false;
        }

        if (Session::get($key) !=  $otp) {
            return false;
        }

        return true;
    }
}
