<?php

namespace App\Http\Controllers\Auth;

//use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\Controller;
use App\Models\PortalModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Library\SmsSender;
use App\Models\CurrentSessionModel;
use App\Models\PasswordHistoryModel;
use App\Models\User;
use App\Models\UserLogModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Symfony\Component\Console\Input\Input;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
 {
    /**
    * Write code on Method
    *
    * @return response()
    */

    public function index()
 {

        $attempts = session( 'attempts' );
        $lastAttemptDate = session( 'lastAttemptDate' );

        return view( 'auth.login_applicant', [
            'attempts' => $attempts,
            'lastAttemptDate' => $lastAttemptDate,

        ] );

        // return view( 'auth.login_applicant' );
    }

    /**
    * Write code on Method
    *
    * @return response()
    */

    public function registration()
 {
        return view( 'auth.registration' );
    }

    /**
    * Write code on Method
    *
    * @return response()
    */

    // public function postlogincitizen( Request $request )
    // {
    //     $request->validate( [
    //         'email' => 'required',
    //         'password' => 'required',
    // ] );
    //     if ( !SmsSender::check_otp( $request->post( 'otp', '' ), 'login_otp' ) ) {

    //        return redirect( 'loginApplicant' )->withErrors( 'Sorry! Wrong OTP' );
    //     }

    //     $credentials = $request->only( 'email', 'password' );
    //     if ( Auth::attempt( $credentials ) ) {
    //         dd( $credentials );
    //         //session()->put( 'role_id', 1 );
    //         return redirect()->intended( 'home' )
    //             ->withSuccess( 'You have Successfully loggedin' );
    //     }

    //     return redirect( 'loginApplicant' )->withSuccess( 'Sorry! You have entered invalid credentials' );
    // }

    public function authenticateCitizen( Request $request )
 {

        $validator =  Validator::make(
            $request->all(),
            [ 'email' => [ 'required', 'exists:users,email' ], 'password' => 'required' ],
            [],
            [ 'email' => 'Given Email Id',  'password' => 'Given Credential' ]
        );

        $credentials = $validator->validate();

        //user email exist
        $user = User::where( 'email', $request->post( 'email' ) )->first();

        if ( 3 <=  UserLogModel::getAttemptNumber( $user->id ) ) {
            // return back()->withErrors( [
            //     'email' => 'Please try after 1 hours.',
            // ] )->onlyInput( 'email' );
            return response()->json( [
                'status' => 0,
                'msg' => 'Please try again after 1 hour.',
            ] );
        }

        if ( !SmsSender::check_otp( $request->post( 'login_otp' ), 'login_otp' ) ) {
            // return back()->withErrors( [
            //     'Mismatch-OTP' => 'OTP does not match.',
            // ] )->onlyInput( 'email' );
            return response()->json( [
                'status' => 0,
                'msg' => 'OTP does not match.',
            ] );
        }

        if ( Auth::attempt( $credentials ) ) {
            $request->session()->regenerate();
            $this->insert_login_log( $user,  true );

            $user_id = $user->id;
            $username = $user->name;
    
            // Check if entry exists with status 'true'
            $existingEntry = CurrentSessionModel::where('user_id', $user_id)
                                                ->orWhere('username', $username)
                                                ->exists();
            if ($existingEntry) {
                // Entry already exists, show alert
                return response()->json([
                    'status' => 0,
                    'msg' => 'Your account is logged in on another device.',
                ]);
            } else {
                // Entry doesn't exist, proceed with insertion
                CurrentSessionModel::create([
                    'user_id' => $user_id,
                    'username' => $username,
                    'email' => $user->email,
                    'status' => 'true',  
                ]);
                
            }

            // return redirect()->route( 'dashboard' );
            return response()->json( [
                'status' => 1,
                'msg' => 'Login Success',
            ] );
        }
        $this->insert_login_log( $user, false );
        return response()->json( [
            'status' => 0,
            'msg' => 'Your provided credentials do not match in our records.',
        ] );
        // return back()->withErrors( [
        //     'email' => 'Your provided credentials do not match in our records.',
        // ] )->onlyInput( 'email' );

    }

    public function insert_login_log( $user, $flag )
 {
        //$clientIP = $request->ip();
        $attempt_number =  UserLogModel::getAttemptNumber( $user->id );
        if ( $flag ) {
            $attempt_number = 0;
        } else {
            $attempt_number += 1;
        }
        UserLogModel::create( [
            'user_id' => $user->id,
            'username' => $user->name,
            'mobile' => $user->mobile,
            'email' => $user->email,
            'attempts' => $attempt_number,
            'attempt_status' => $flag,
            'ip_address' => get_client_ip()
        ] );
    }

    /**
    * Write code on Method
    *
    * @return response()
    */

    public function postRegistration( Request $request )
 {
        $request->validate( [
            'name' => [ 'required', 'string', 'max:255' ],
            'mobile' => [ 'required', 'string', 'max:10' ],
            'email' => [ 'required', 'string', 'email', 'max:255', 'unique:users' ],
            'password' => [ 'required', 'string', 'min:8', 'confirmed' ],
        ] );

        $data = $request->all();
        $check = $this->create( $data );

        if ( !$check ) {
            return redirect()->back()->withErrors( [ 'Failed to Registered' ] );
        }

        return redirect( 'loginApplicant' )->withSuccess( 'You have Successfully Registered' );
    }

    /**
    * Write code on Method
    *
    * @return response()
    */

    public function dashboard()
 {
        if ( Auth::check() ) {
            return view( 'home' );
        }

        return redirect( 'loginApplicant' )->withSuccess( 'Opps! You do not have access' );
    }

    /**
    * Write code on Method
    *
    * @return response()
    */

    public function create( array $data )
 {
        return User::create( [
            'name' => $data[ 'name' ],
            // 'username' => $data[ 'username' ],
            'mobile' => $data[ 'mobile' ],
            'email' => $data[ 'email' ],
            'role_id' => 77, //applicant role id is fixed as 77, applicant menu after login is code in app.blade.php
            'password' => Hash::make( $data[ 'password' ] )
        ] );
    }

    //     public function insert_login_log( $user, $flag ) {
    //         //$clientIP = $request->ip();
    //         $attempt_number =  UserLogModel::getAttemptNumber( $user->user_id );
    //         if ( $flag ) {
    //             $attempt_number = 0;
    //         }
    //         else {
    //             $attempt_number += 1;
    //         }
    //         UserLogModel::create( [
    //             'user_id' => $user->id,
    //             'username' => $user->name,
    //             'mobile' => $user->mobile,
    //             'email' => $user->email,
    //             'attempts' => $attempt_number,
    //             'attempt_status' => $flag,
    //             //'ip_address' => get_client_ip()
    // ] );

    // }
    /**
    * Write code on Method
    *
    * @return response()
    */

    public function logout( Request $request )
 {
        if ( Auth::check() ) {
            $this->insert_login_log( Auth::user(), true );
        }
        $user_id = Auth::user()->id;
        CurrentSessionModel::where('user_id', $user_id)->delete();
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route( 'welcome' );
    }

    public function smsLoginCitizenOTP( Request $request )
 {
        $validator =  Validator::make(
            $request->all(),
            [
                'email' => [ 'required', 'exists:users,email' ],
                'password' => [ 'required' ],
            ],
            [],
            [ 'email' => 'Given Email Id',  'password' => 'Given Credential' ]
        );

        if ( $validator->fails() ) {
            return response()->json( [
                'status' => 0,
                'msg' => 'Error! Failed. Wrong Credential',
                'errors' => [ 'email' => 'Your provided credentials do not match in our records.', ]
            ] );
        }

        //captcha validation
        //  $validator =  Validator::make( $request->all(),  [ 'captcha' => [ 'required', 'captcha' ] ] );

        //  if ( $validator->fails() )
        // {
        //      return response()->json( [
        //          'status' => 0,
        //          'msg' => 'Error! Failed.',
        //          'errors' => [ 'captcha'=>'Captcha Mismatch.', ]
        // ] );
        //  }
        $user = User::where( 'email', $request->post( 'email' ) )->first();

        //    // $user->attempts += 1;
        //     $user->last_attempt_date = now()->toDateString();
        //     $user->save();

        if ( abs( $user->role_id != 77 ) ) {
            return response()->json( [
                'status' => 0,
                'msg' => 'Failed',
                'errors' => [ 'email' => 'You are not authorized.', ]
            ] );
        }
        if ( 3 <=  UserLogModel::getAttemptNumber( $user->id ) ) {
            return response()->json( [
                'status' => 0,
                'msg' => 'Failed',
                'errors' => [ 'email' => 'Please try after 1 hour.', ]
            ] );
        }

        if ( Hash::check( $request->post( 'password' ), $user->password ) ) {

            SmsSender::send_otp( $user->mobile, 'login_otp' );

            $mobile = substr_replace( $user->mobile, '*******', 1, 7 );

            return response()->json( [
                'status' => 1,
                'msg' => 'OTP has been send to your mobile number: ' . $mobile,
            ] );
        }
        $this->insert_login_log( $user, false );
        //     UserLogModel::create( [
        //         'user_id' => $user->id,
        //         'username' => $user->username,
        //         'mobile' => $user->mobile,
        //         'email' => $user->email,

        //        'attempts' => $user->attempts,
        //        'last_attempt_date' => $user->last_attempt_date,
        // ] );
        return response()->json( [
            'status' => 0,
            'msg' => 'Failed',
            'errors' => [ 'email' => 'Your provided credentials do not match in our records.', ]
        ] );
    }

    public function smsLoginCitizenOTPResend( Request $request )
 {
        $user = User::where( 'email', $request->post( 'email' ) )->first();
        SmsSender::resend_otp( $user->mobile, 'login_otp' );
        //$mobile_number = substr_replace( $user->mobile_number, '****', 2, 4 );
        $mobile_number = substr_replace( $user->mobile, '*******', 1, 7 );
        return response()->json( [
            'status' => 1,
            'msg' => 'OTP has been send to your mobile number: ' . $mobile_number,
        ] );
    }

    public function forgotPassword( Request $request )
 {
        return view( 'auth.forgotPassword' );
    }

    public function passwordResetEmail( Request $request )
 {

        $rules = [
            'email' => [ 'required', 'exists:users,email' ]
        ];
        $niceNames = [
            'email' => 'Email'
        ];
        Validator::make( $request->all(), $rules, [],  $niceNames )->validate();

        try {
            DB::beginTransaction();
            $str_result = '23456789ABCDEFGHJKMNPQRSTUVWXYZabcdefghkpqrtyz';
            $password = substr(
                str_shuffle( $str_result ),
                0,
                8
            );

            $mailData = [
                'view' => 'email.passwordReset',
                'subject' => 'Reset Password',
                'title' => 'DIHAS',
                'body' => $password
            ];

            if ( !SmsSender::sendEmail( $request->post( 'email' ), $mailData ) ) {
                throw new Exception( 'Failed to Reset' );
            }

            $user = User::where( 'email', $request->post( 'email' ) )->first();
            $this->passwordHistorySave( $user );
            $user->password = Hash::make( $password );
            $user->save();
        } catch ( Exception $e ) {
            DB::rollBack();
            return redirect()->back()->withErrors( [ 'msg' => 'Fail to Reset Password.' ] );
        }
        $email = explode( '@', $request->post( 'email' ) );
        $email = substr_replace( $email[ 0 ], '****', 2, -3 ) . '@' . $email[ 1 ];
        //return success
        DB::commit();
        return redirect()->back()->with( [ 'success' => 'Your password has been send to ' . $email ] );
    }

    public function passwordHistorySave( $user )
 {
        try {
            PasswordHistoryModel::create( [
                'user_id' => $user->user_id,
                'old_password' => $user->password,
                'ip_address' => get_client_ip(),

            ] );
        } catch ( Exception $e ) {
            return false;
        }

        return true;
    }
}
