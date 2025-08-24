<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Library\SmsSender;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class OtpVerificationController extends Controller
{
    public function show()
    {
        if (empty(session('2fa_user_id'))) {
            //then redirect route to the welcome screen
            return redirect()->route('welcome');
        }
        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        if (session('otp') == $request->otp && now()->lt(session('otp_expires_at'))) {
            // OTP valid
            Auth::loginUsingId(session('2fa_user_id'));

            // Clear OTP session
            session()->forget(['otp', 'otp_expires_at', '2fa_user_id']);

            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP']);
    }

    public function resendOTP(Request $request)
    {
        $user_id_2fa = $request->user_id;
        $user = User::findOrFail($user_id_2fa);
        // Step 2: Generate OTP        
        $otp = SmsSender::generateOTP('login_otp');
        $otpExpires = env('OTP_EXPIRES_IN', 5); //By default 5 minutes
        // Save OTP in session ()
        session([
            '2fa_user_id' => $user_id_2fa,
            'otp' => $otp,
            'otp_expires_at' =>  now()->addMinutes($otpExpires),
        ]);

        //Now sending OTP in email
        $mailData = [
            "view" => "email.otpMail",
            "subject" => "OTP verification",
            "title" => "No-Reply",
            "body" => $otp
        ];

        SmsSender::sendEmail($user->email, $mailData);

        //Now sending OTP as sms in mobile phone
        SmsSender::sendsmsOTP($user->mobile, $otp);

        return redirect()->back()->with(
            'success',
            'OTP has been resent to your email and mobile number.'
        );
    }
}
