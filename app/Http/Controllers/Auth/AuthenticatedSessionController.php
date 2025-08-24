<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Library\SmsSender;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Step 1: Validate email, password & login type
        $data      = $request->validated();
        $loginType = $data['loginType'];

        //getting user
        $user = User::where([
            'email' => $data['email'],
        ])->first();

        if (empty($user)) {
            return back()->withInput()->withErrors([
                'email' => 'Sorry, your email is not registered in our app.',
            ]);
        }

        if (
            ($loginType === "citizenLogin" && $user->role_id != 77) ||
            ($loginType !== "citizenLogin" && $user->role_id == 77)
        ) {
            $message = $loginType === "citizenLogin"
                ? 'Sorry, you are not a citizen user.'
                : 'Sorry, you are not a department user.';

            return back()->withInput()->withErrors(['loginType' => $message]);
        }

        $request->authenticate();
        // Prevent full login until OTP verified
        Auth::logout();

        // Step 2: Generate OTP        
        $otp = SmsSender::generateOTP('login_otp');
        $otpExpires = env('OTP_EXPIRES_IN', 5); //By default 5 minutes
        // Save OTP in session ()
        session([
            '2fa_user_id' => $user->user_id, //Auth::id(),
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


        $request->session()->regenerate();

        // Step 4: Redirect to OTP verification page
        return redirect()->route('otp.show')->with(
            'success',
            'An OTP has ben sent to both your registered email ID as well as your mobile number.'
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
