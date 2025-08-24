<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Library\SmsSender;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SmsController extends Controller
{
    public function smsLoginCitizenOTP(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email'    => ['required', 'exists:users,email'],
                'password' => ['required'],
            ],
            [],
            ['email' => 'Given Email Id', 'password' => 'Given Credential']
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'msg'    => 'Error! Failed. Wrong Credential',
                'errors' => ['email' => 'Your provided credentials do not match in our records.'],
            ]);
        }

        $user = User::where('email', $request->post('email'))->first();

        // if (abs($user->role_id != 77)) { //$user->role->role_group
        if ($user->role->role_group != "citizen") { //$user->role->role_group
            return response()->json([
                'status' => 0,
                'msg'    => 'Failed',
                'errors' => ['email' => 'You are not authorized.'],
            ]);
        }

        if (Hash::check($request->post('password'), $user->password)) {

            SmsSender::send_SmsOtp($user->mobile, 'login_otp');

            $mobile = substr_replace($user->mobile, '*******', 1, 7);

            return response()->json([
                'status' => 1,
                'msg'    => 'OTP has been send to your mobile number: ' . $mobile,
            ]);
        }

        return response()->json([
            'status' => 0,
            'msg'    => 'Failed',
            'errors' => ['email' => 'Your provided credentials do not match in our records.'],
        ]);
    }

    public function smsLoginCitizenOTPResend(Request $request)
    {
        $user = User::where('email', $request->post('email'))->first();
        SmsSender::resend_SmsOtp($user->mobile, 'login_otp');
        //$mobile_number = substr_replace( $user->mobile_number, '****', 2, 4 );
        $mobile_number = substr_replace($user->mobile, '*******', 1, 7);
        return response()->json([
            'status' => 1,
            'msg'    => 'OTP has been send to your mobile number: ' . $mobile_number,
        ]);
    }

    public function smsLoginOTP(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'emailDept'    => ['required', 'exists:users,email'],
                'passwordDept' => ['required'],
            ],
            [],
            ['email' => "Given Email Id", 'password' => "Given Credential"]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'msg'    => 'Error! Failed. Wrong Credential',
                'errors' => ['emailDept' => 'Your provided credentials do not match in our records.'],
            ]);
        }

        $user = User::where('email', $request->post('emailDept'))->first();

        if (abs($user->role_id) > 10) {
            return response()->json([
                'status' => 0,
                'msg'    => 'Failed',
                'errors' => ['emailDept' => 'You are not authorized.'],
            ]);
        }
        /*
        if (3 <= UserLogModel::getAttemptNumber($user->id)) {
            return response()->json([
                'status' => 0,
                'msg'    => 'Failed',
                'errors' => ['emailDept' => 'Please try after 1 hour.'],
            ]);
        } */

        if (Hash::check($request->post('passwordDept'), $user->password)) {

            SmsSender::send_SmsOtp($user->mobile, 'login_otp_Dept');

            $mobile = substr_replace($user->mobile, "*******", 1, 7);

            return response()->json([
                'status' => 1,
                'msg'    => "OTP has been send to your mobile number: " . $mobile,
            ]);
        }
        /*
        UserLogModel::create([
            'user_id'           => $user->id,
            'username'          => $user->username,
            'mobile'            => $user->mobile,
            'email'             => $user->email,

            'attempts'          => $user->attempts,
            'last_attempt_date' => $user->last_attempt_date,
        ]);
        $this->insert_login_log($user, "smsLoginOTP", false); */

        return response()->json([
            'status' => 0,
            'msg'    => 'Failed',
            'errors' => ['emailDept' => 'Your provided credentials do not match in our records.'],
        ]);
    }

    public function smsLoginOTPResend(Request $request)
    {
        $user = User::where("email", $request->post('email'))->first();
        SmsSender::resend_otp($user->mobile, 'login_otp_Dept');
        //$mobile_number = substr_replace($user->mobile_number,"****",2,4);
        $mobile_number = substr_replace($user->mobile, "*******", 1, 7);
        return response()->json([
            'status' => 1,
            'msg'    => "OTP has been send to your mobile number: " . $mobile_number,
        ]);
    }
}
