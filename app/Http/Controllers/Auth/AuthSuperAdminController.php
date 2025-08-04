<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Library\SmsSender;
use App\Models\AdminDeptModel;
use App\Models\CurrentSessionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\UserLogModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Models\UserRoleModel as Roles;
use App\Models\DepartmentModel as Department;
use App\Models\PasswordHistoryModel;
use App\Models\Sign2Model;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthSuperAdminController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */

    public function index()
    {
        // return view( 'auth.login_superadmin' );
        //get the seesion
        $attempts = session('attempts');
        $lastAttemptDate = session('lastAttemptDate');

        return view('auth.login_superadmin', [
            'attempts' => $attempts,
            'lastAttemptDate' => $lastAttemptDate,

        ]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */

    public function postloginsuperadmin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (!SmsSender::check_otp($request->post('otp', ''), 'login_otp')) {
            return redirect('superadmin')->withErrors('Sorry! Wrong OTP');
        }

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            //set role id in session
            //session()->put( 'role_id', 1 );
            return redirect()->intended('home')
                ->withSuccess('You have Successfully loggedin');
        }

        return redirect('superadmin')->withSuccess('Sorry! You have entered invalid credentials');
    }

    // public function postRegistration( Request $request )
    // {

    //     $request->validate( [
    //         'name' => 'required',
    //         'username' => 'required',
    //         'mobile' => 'required',
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required|min:6',
    // ] );

    //     $data = $request->all();
    //     $check = $this->create( $data );

    //     return redirect( '/loginApplicant' )->withSuccess( 'You have Successfully Registered' );
    // }

    /**
     * Write code on Method
     *
     * @return response()
     */

    public function dashboard()
    {
        if (Auth::check()) {
            return view('home');
        }

        return redirect('/superadmin')->withSuccess('Opps! You do not have access');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    // public function create( array $data )
    // {
    //   return User::create( [
    //     'name' => $data[ 'name' ],
    //     'username' => $data[ 'username' ],
    //     'mobile' => $data[ 'mobile' ],
    //     'email' => $data[ 'email' ],
    //     'role_id' => 77, //applicant role id is fixed as 77, applicant menu after login is code in app.blade.php
    //     'password' => Hash::make( $data[ 'password' ] )
    // ] );
    // }
    /**
     * Authenticate the user.
     */

    public function authenticateSuper(Request $request)
    {

        $validator =  Validator::make(
            $request->all(),
            ['email' => ['required', 'exists:users,email'], 'password' => 'required'],
            [],
            ['email' => 'Given Email Id',  'password' => 'Given Credential']
        );

        $credentials = $validator->validate();

        //user email exist
        $user = User::where('email', $request->post('email'))->first();
        if (3 <=  UserLogModel::getAttemptNumber($user->id)) {
            // return back()->withErrors( [
            //     'email' => 'Please try after 1 hours.',
            // ] )->onlyInput( 'email' );
            return response()->json([
                'status' => 0,
                'msg' => 'Please try again after 1 hour.',
            ]);
        }

        if (!SmsSender::check_otp($request->post('login_otp'), 'login_otp')) {
            // return back()->withErrors( [
            //     'Mismatch-OTP' => 'OTP does not match.',
            // ] )->onlyInput( 'email' );
            return response()->json([
                'status' => 0,
                'msg' => 'OTP does not match.',
            ]);
        }

        //return $this->insert_login_log( $user, false );
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $this->insert_login_log($user, 'authenticateSuper', true);

            $user_id = $user->id;
            $username = $user->name;

            // Check if entry exists with status 'true'
            $existingEntry = CurrentSessionModel::where('user_id', $user_id)
                ->orWhere('username', $username)
                ->exists();
            // if ($existingEntry) {
            //     // Entry already exists, show alert
            //     return response()->json([
            //         'status' => 0,
            //         'msg' => 'Your account is logged in on another device.',
            //     ]);
            // } else {
            //     // Entry doesn't exist, proceed with insertion
            //     CurrentSessionModel::create([
            //         'user_id' => $user_id,
            //         'username' => $username,
            //         'email' => $user->email,
            //         'status' => 'true',  
            //     ]);

            // }
            // return redirect()->route( 'dashboard' );
            return response()->json([
                'status' => 1,
                'msg' => 'Login Success',
            ]);
        }
        $this->insert_login_log($user, 'authenticateSuper', false);

        // return back()->withErrors( [
        //     'email' => 'Your provided credentials do not match in our records.',
        // ] )->onlyInput( 'email' );
        return response()->json([
            'status' => 0,
            'msg' => 'Your provided credentials do not match in our records.',
        ]);
    }

    public function insert_login_log($user, $flag)
    {

        $attempt_number =  UserLogModel::getAttemptNumber($user->id);
        if ($flag) {
            $attempt_number = 0;
        } else {
            $attempt_number += 1;
        }
        UserLogModel::create([
            'user_id' => $user->id,
            'username' => $user->name,
            'mobile' => $user->mobile,
            'email' => $user->email,
            'attempts' => $attempt_number,
            'attempt_status' => $flag,
            'ip_address' => get_client_ip()
        ]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */

    public function logout(Request $request)
    {
        // Session::flush();
        // Auth::logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        // return view( 'welcome' );
        if (Auth::check()) {
            $this->insert_login_log(Auth::user(), true);
        }
        $user_id = Auth::user()->id;
        CurrentSessionModel::where('user_id', $user_id)->delete();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome');
    }

    public function smsLoginSuperadminOTP(Request $request)
    {
        $validator =  Validator::make(
            $request->all(),
            [
                'email' => ['required', 'exists:users,email'],
                'password' => ['required'],
            ],
            [],
            ['email' => 'Given Email Id',  'password' => 'Given Credential']
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'msg' => 'Error! Failed. Wrong Credential',
                'errors' => ['email' => 'Your provided credentials do not match in our records.',]
            ]);
        }
        //captcha validation
        //   $validator =  Validator::make( $request->all(),  [ 'captcha' => [ 'required', 'captcha' ] ] );

        //   if ( $validator->fails() )
        // {
        //       return response()->json( [
        //           'status' => 0,
        //           'msg' => 'Error! Failed.',
        //           'errors' => [ 'captcha'=>'Captcha Missmatch.', ]
        // ] );
        //   }
        $user = User::where('email', $request->post('email'))->first();

        // $user->attempts += 1;
        // $user->last_attempt_date = now()->toDateString();
        // $user->save();
        if (abs($user->role_id) != 999) {
            return response()->json([
                'status' => 0,
                'msg' => 'Failed',
                'errors' => ['email' => 'You are not authorized.',]
            ]);
        }

        if (3 <=  UserLogModel::getAttemptNumber($user->id)) {
            return response()->json([
                'status' => 0,
                'msg' => 'Failed',
                'errors' => ['email' => 'Please try after 1 hour.',]
            ]);
        }

        if (Hash::check($request->post('password'), $user->password)) {

            SmsSender::send_otp($user->mobile, 'login_otp');

            $mobile = substr_replace($user->mobile, '*******', 1, 7);

            return response()->json([
                'status' => 1,
                'msg' => 'OTP has been send to your mobile number: ' . $mobile,
            ]);
        }

        //     UserLogModel::create( [
        //         'user_id' => $user->id,
        //         'username' => $user->username,
        //         'mobile' => $user->mobile,
        //         'email' => $user->email,

        //        'attempts' => $user->attempts,
        //        'last_attempt_date' => $user->last_attempt_date,
        // ] );
        $this->insert_login_log($user, false);
        return response()->json([
            'status' => 0,
            'msg' => 'Failed',
            'errors' => ['email' => 'Your provided credentials do not match in our records.',]
        ]);
    }

    public function smsLoginSuperadminOTPResend(Request $request)
    {
        $user = User::where('email', $request->post('email'))->first();
        SmsSender::resend_otp($user->mobile, 'login_otp');
        //$mobile_number = substr_replace( $user->mobile_number, '****', 2, 4 );
        $mobile_number = substr_replace($user->mobile, '*******', 1, 7);
        return response()->json([
            'status' => 1,
            'msg' => 'OTP has been send to your mobile number: ' . $mobile_number,
        ]);
    }

    public function forgotPassword(Request $request)
    {
        return view('auth.forgotPassword');
    }

    public function passwordResetEmail(Request $request)
    {

        $rules = [
            'email' => ['required', 'exists:users,email']
        ];
        $niceNames = [
            'email' => 'Email'
        ];
        Validator::make($request->all(), $rules, [],  $niceNames)->validate();

        try {
            DB::beginTransaction();
            $str_result = '23456789ABCDEFGHJKMNPQRSTUVWXYZabcdefghkpqrtyz';
            $password = substr(
                str_shuffle($str_result),
                0,
                8
            );

            $mailData = [
                'view' => 'email.passwordReset',
                'subject' => 'Reset Password',
                'title' => 'Die-in-Harness Appointment System',
                'body' => $password
            ];

            if (!SmsSender::sendEmail($request->post('email'), $mailData)) {
                throw new Exception('Failed to Reset');
            }

            $user = User::where('email', $request->post('email'))->first();
            $this->passwordHistorySave($user);
            $user->password = Hash::make($password);
            $user->save();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['msg' => 'Fail to Reset Password.']);
        }
        $email = explode('@', $request->post('email'));
        $email = substr_replace($email[0], '****', 2, -3) . '@' . $email[1];
        //return success
        DB::commit();
        return redirect()->back()->with(['success' => 'Your password has been send to ' . $email]);
    }

    public function passwordHistorySave($user)
    {
        try {
            PasswordHistoryModel::create([
                'user_id' => $user->user_id,
                'old_password' => $user->password,
                'ip_address' => get_client_ip(),

            ]);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    // public function verifysuperadmin( Request $request )
    // {
    //     $email = $request->input( 'email' );
    //     $password = $request->input( 'password' );

    //     // Hash the provided password
    //     $hashedPassword = Hash::make( $password );

    //     // Find the user by email in the users table
    //     $user = User::where( 'email', $email )->first();

    //     if ( $user ) {
    //         // Check attempts
    //         if ( $user->attempts >= 3 ) {
    //             // Check if last_attempt_date is today's date
    //             $currentDate = now()->toDateString();
    //             $lastAttemptDate = $user->last_attempt_date;

    //             if ($lastAttemptDate == $currentDate) {
    //                 $status = 0;
    //                 return response()->json([
    //                     'status' => $status,
    //                     'message' => 'Please try again tomorrow.'
    //                 ]);
    //             }

    //             // Reset attempts to 0 when last_attempt_date is not today
    //             else {
    //                 $user->attempts = 0;
    //             }
    //         }

    //         $superadminusermobile = $user->mobile;
    //         $lastFourDigits = substr($superadminusermobile, -4);

    //         // $randomNumber = random_int(100000, 999999);
    //         // $otp = "123456"; 

    //         // session(['otp' => $otp]); 
    //         //  $this->callSandesAPI($otp, $superadminusermobile);
    //         SmsSender::send_otp($superadminusermobile, 'login_otp');

    //         // Update attempts and last_attempt_date in the database

    //         $user->attempts += 1;
    //         $user->last_attempt_date = now()->toDateString();
    //         $user->save();
    //         UserLogModel::create([
    //             'user_id' => $user->id,
    //             'username' => $user->username,
    //             'mobile' => $user->mobile,
    //             'email' => $user->email,

    //             'attempts' => $user->attempts,
    //             'last_attempt_date' => $user->last_attempt_date,
    //         ]);

    //         $status = 1;
    //         return response()->json([
    //             'status' => $status,
    //             'message' => "OTP is sent to your mobile number ******$lastFourDigits"
    //         ]);
    //     } else if (!$user) {
    //         $status = 0;
    //         return response()->json([
    //             'status' => $status,
    //             'message' => 'Invalid username or password'
    //         ]);
    //     } else {
    //         $status = 2;
    //         return response()->json([
    //             'status' => $status,
    //             'message' => 'No response from Server..Please see your internet connection'
    //         ]);
    //     }
    // }



    // public function verifysuperadminresendotp(Request $request)
    // {
    //     $email = $request->input('email');
    //     //  $password = $request->input('password');

    //     // Hash the provided password
    //     // $hashedPassword = Hash::make($password);

    //     // Find the user by email in the users table
    //     $user = User::where('email', $email)->first();

    //     if ($user) {
    //         // Check attempts
    //         if ($user->attempts >= 3) {
    //             // Check if last_attempt_date is today's date
    //             $currentDate = now()->toDateString();
    //             $lastAttemptDate = $user->last_attempt_date;

    //             if ( $lastAttemptDate == $currentDate ) {
    //                 $status = 0;
    //                 return response()->json( [
    //                     'status' => $status,
    //                     'message' => 'Please try again tomorrow.'
    // ] );
    //             }

    //             // Reset attempts to 0 when last_attempt_date is not today
    //             else {
    //                 $user->attempts = 0;
    //             }
    //         }

    //         $superadminusermobile = $user->mobile;
    //         $lastFourDigits = substr( $superadminusermobile, -4 );

    //         // $randomNumber = random_int( 100000, 999999 );
    //         // $otp = '123456';

    //         // session( [ 'otp' => $otp ] );

    //         // $this->callSandesAPI( $otp, $superadminusermobile );
    //         SmsSender::send_otp( $superadminusermobile, 'login_otp' );
    //         // Update attempts and last_attempt_date in the database
    //         $user->attempts += 1;
    //         $user->last_attempt_date = now()->toDateString();
    //         $user->save();
    //         UserLogModel::create( [
    //             'user_id' => $user->id,
    //             'username' => $user->username,
    //             'mobile' => $user->mobile,
    //             'email' => $user->email,

    //             'attempts' => $user->attempts,
    //             'last_attempt_date' => $user->last_attempt_date,
    // ] );

    //         $status = 1;
    //         return response()->json( [
    //             'status' => $status,
    //             'message' => "OTP is sent to your mobile number **$lastFourDigits"
    // ] );
    //     } else if ( !$user ) {
    //         $status = 0;
    //         return response()->json( [
    //             'status' => $status,
    //             'message' => 'Invalid email or password'
    // ] );
    //     } else {
    //         $status = 2;
    //         return response()->json( [
    //             'status' => $status,
    //             'message' => 'No response from Server..Please see your internet connection'
    // ] );
    //     }
    // }

    ///////////////////////////////Official Registration////////////////////////////
    /**
     * Write code on Method
     *
     * @return response()
     */

    // public function register()
    // {
    //     $role = [ 1, 2, 3, 4, 5, 6, 8, 9 ];
    //     $roles = Roles::get()->whereIn( 'role_id', $role )->toArray();
    //     $ministry = AdminDeptModel::orderBy( 'ministry' )->get()->unique( 'ministry' );
    //     $departments = Department::orderBy( 'dept_name' )->get()->unique( 'dept_name' );
    //     return view( 'auth.register', [
    //         'roles' => $roles,
    //         'departments' => $departments,
    //         'ministry' => $ministry,
    // ] );
    //     //  return view( 'auth.register' );
    // }

    // protected function validator( array $data )
    // {
    //     return Validator::make( $data, [
    //         'name' => [ 'required', 'string', 'max:255' ],
    //         'email' => [ 'required', 'string', 'email', 'max:255', 'unique:users' ],
    //         'mobile' => [ 'required', 'string', 'max:10' ],
    //         'password' => [ 'required', 'string', 'min:8', 'confirmed' ],

    //         'ministry_id' => isset( $data[ 'ministry_id' ] ) ? 'required' : '',
    //         'dept_id' => [ 'required', 'integer', '' ],
    //         'role_id' => [ 'required', 'integer', '' ],
    //         'post_id' => [ 'required', 'integer', '' ],
    // ] );
    // }

    public function register()
    {
        $role = [1, 2, 3, 4, 5, 6, 8, 9];
        $roles = Roles::get()->whereIn('role_id', $role)->toArray();
        $ministry = AdminDeptModel::orderBy('ministry')->get()->unique('ministry');
        $departments = Department::orderBy('dept_name')->get()->unique('dept_name');
        $departmentSigningAuthority = Sign2Model::orderBy('id')->get();

        return view('auth.register', [
            'roles' => $roles,
            'departments' => $departments,
            'ministry' => $ministry,
            'departmentSigningAuthority' => $departmentSigningAuthority
        ]);
        //  return view( 'auth.register'  );
    }

    protected function validator(array $data)
    {

        // dd( $data[ 'password' ]);
        if ($data['role_id'] == 1 ||  $data['role_id'] == 2 || $data['role_id'] == 3 ||  $data['role_id'] == 4 ||  $data['role_id'] == 5 ||  $data['role_id'] == 6 ||  $data['role_id'] == 8) {

            return Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'mobile' => ['required', 'string', 'digits:10'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'ministry_id' => isset($data['ministry_id']) ? 'required' : '',
                'dept_id' => ['required', 'integer'],
                'role_id' => ['required', 'integer'],
                'post' => ['required', 'integer'],
            ], [
                'name.required' => 'The name field is required.',
                'email.required' => 'The email field is required.',
                'email.unique' => 'This email is already taken. Please choose a different one.',
                'email.email' => 'Please enter a valid email address.',
                'mobile.required' => 'The mobile field is required.',
                'mobile.digits' => 'The mobile field must not exceed 10 characters.',
                'password.required' => 'The password field is required.',
                'password.min' => 'The password must be at least 8 characters long.',
                'password.confirmed' => 'The password confirmation does not match.',
                'dept_id.required' => 'The department field is required.',
                'dept_id.integer' => 'Please select a valid department.',
                'role_id.required' => 'The role field is required.',
                'role_id.integer' => 'Please select a valid role.',
                'post.required' => 'The post field is required.',
                'post.integer' => 'Please select a valid post.',
            ]);
        }

        if ($data['role_id'] == 9) {

            return Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'mobile' => ['required', 'string', 'digits:10'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'ministry_id' => isset($data['ministry_id']) ? 'required' : '',
                'dept_id' => ['required', 'integer'],
                'role_id' => ['required', 'integer'],
                'post' => ['required', 'integer'],
            ], [
                'name.required' => 'The name field is required.',
                'email.required' => 'The email field is required.',
                'email.unique' => 'This email is already taken. Please choose a different one.',
                'email.email' => 'Please enter a valid email address.',
                'mobile.required' => 'The mobile field is required.',
                'mobile.digits' => 'The mobile field must not exceed 10 characters.',
                'password.required' => 'The password field is required.',
                'password.min' => 'The password must be at least 8 characters long.',
                'password.confirmed' => 'The password confirmation does not match.',
                'dept_id.required' => 'The department field is required.',
                'dept_id.integer' => 'Please select a valid department.',
                'role_id.required' => 'The role field is required.',
                'role_id.integer' => 'Please select a valid role.',
                'post.required' => 'The post field is required.',
                'post.integer' => 'Please select a valid post.',
            ]);
        }
    }

    // public function createofficial( array $data )
    // {

    //     return User::create( [
    //         'name' => $data[ 'name' ],
    //         // 'username' => $data[ 'username' ],
    //         'email' => $data[ 'email' ],
    //         'mobile' => $data[ 'mobile' ],

    //         'password' => Hash::make( $data[ 'password' ] ),
    //         //'ministry_id' => $data[ 'ministry_id' ],
    //         'ministry_id' => isset( $data[ 'ministry_id' ] ) ? $data[ 'ministry_id' ] : null,
    //         'dept_id' => $data[ 'dept_id' ],
    //         'role_id' => $data[ 'role_id' ],
    //         'post_id' =>$data[ 'post' ]
    // ] );
    // }

    public function createofficial(array $data)
    {


        if ($data['role_id'] == 1 ||  $data['role_id'] == 2 || $data['role_id'] == 3 ||  $data['role_id'] == 4 ||  $data['role_id'] == 5 ||  $data['role_id'] == 6 || $data['role_id'] == 8) {
            return User::create([
                'name' => $data['name'],
                // 'username' => $data[ 'username' ],
                'email' => $data['email'],
                'mobile' => $data['mobile'],

                'password' => Hash::make($data['password']),
                //'ministry_id' => $data[ 'ministry_id' ],
                'ministry_id' => isset($data['ministry_id']) ? $data['ministry_id'] : null,
                'dept_id' => $data['dept_id'],
                'role_id' => $data['role_id'],

                'post_id' => $data['post']

            ]);
        }
        if ($data['role_id'] == 9) {
            return User::create([
                'name' => $data['name'],
                // 'username' => $data[ 'username' ],
                'email' => $data['email'],
                'mobile' => $data['mobile'],

                'password' => Hash::make($data['password']),
                //'ministry_id' => $data[ 'ministry_id' ],
                'ministry_id' => isset($data['ministry_id']) ? $data['ministry_id'] : null,
                'dept_id' => $data['dept_id'],
                'role_id' => $data['role_id'],

                'post_id' => $data['department_signing_authority']

            ]);
        }
    }

    public function postRegister(Request $request)
    {
        if ($request->name != null && $request->email != null && $request->mobile != null && $request->ministry_id != null && $request->dept_id != null && $request->role_id != null && $request->password != null) {
            $this->validator($request->all())->validate();

            $check = $this->createofficial($request->all());
            // dd( $check );
            if (!$check) {
                return redirect()->back()->withErrors(['Failed to Registered']);
            }

            return redirect()->back()->withSuccess('Successfully Registered');
        }

        if ($request->name == null || $request->email == null || $request->mobile == null || $request->ministry_id == null || $request->dept_id == null || $request->role_id == null || $request->password == null) {
            return redirect()->back()->withErrors(['All fields are require to filled.....']);
        }
    }
    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }
    ///////////////////////////////////////////////////////////////////////////////

    //     public function updateOfficialUser( Request $request )
    // {

    //         // Validate the form data
    //         $request->validate( [
    //             'id' => 'required|exists:users,id',
    //             'name' => 'required|string|max:255',
    //             'email' => 'required|email|max:255',
    //             'mobile' => 'required',
    //             'dept_id' => 'required',
    //             'ministry_id' => 'required',
    //             'role_id' => 'required',
    //             'password' => 'required|confirmed',
    //             'post'=>'required',
    // ] );

    //          // Find the user by ID
    //          $user = User::findOrFail( $request->id );

    //         // Fetch dept_id based on dept_name
    //         $dept = Department::where( 'dept_id', $request->dept_id )->first();

    //         // Fetch ministry_id based on ministry
    //         $ministry = AdminDeptModel::where( 'ministry_id', $request->ministry_id )->first();

    //           // Fetch role_id based on role_name
    //           $role = Roles::where( 'role_id', $request->role_id )->first();

    //         if ( $user ) {
    //             $res = $user->update( [
    //                 'name' => $request->name,
    //                 'email' => $request->email,
    //                 'mobile' => $request->mobile,
    //                 'dept_id' => $dept->dept_id,
    //                 'ministry_id' => $ministry->ministry_id, // Update with the fetched dept_id
    //                // 'ministry_id' => isset( $ministry[ 'ministry_id' ] ) ? $ministry[ 'ministry_id' ] : null,
    //                 'role_id' => $role->role_id,
    //                 'password' => Hash::make( $request->password ),
    //                 'post_id'=>$request->post,
    // ] );

    //             if ( $res ) {
    //                 // Return a response indicating success
    //                 //return response()->json( [ 'message' => 'Data updated successfully ' ] );
    //                 return redirect()->route( 'register_user_edit' )->with( 'message', 'Data updated successfully!!!' );
    //         }
    //             else {
    //                 return back()->with( 'error', 'Failed to update...' );
    //             }

    //         }

    //  }

    public function updateOfficialUser(Request $request)
    {
        //  dd($request->all());



        // Validate the form data
        if ($request->role_id == 1 ||  $request->role_id == 2 || $request->role_id == 3 ||  $request->role_id == 4 || $request->role_id == 5 ||  $request->role_id == 6  ||  $request->role_id == 8) {

            if ($request->active_status == 'false') {

                $request->validate([
                    'id' => 'required|exists:users,id',
                    'name' => 'string|max:255', // Add the 'alpha' rule for alphabets only
                    'email' => [
                        '',
                        'email',
                        'max:255',
                        Rule::unique('users', 'email')->ignore($request->id), // Add this line for checking uniqueness
                    ],
                    'mobile' => '',
                    'active_status' => 'required',
                    'dept_id' => '',
                    'ministry_id' => '',
                    'role_id' => '',
                    'password' => '',
                    'post' => '',
                ],);

                // Find the user by ID
                $user = User::findOrFail($request->id);

                //$userData = User::where( 'id', $request->id )->first();
                // Fetch dept_id based on dept_name
                $dept = Department::where('dept_id', $request->dept_id)->first();

                // Fetch ministry_id based on ministry
                $ministry = AdminDeptModel::where('ministry_id', $request->ministry_id)->first();
                //  dd( $request->role_id );
                // Fetch role_id based on role_name
                $role = Roles::where('role_id', $request->role_id)->first();
                // Check if dept_id is found
                // if ( $dept && $ministry && $role ) {
                // Update the user data
                //  dd( $role->role_id );
                if ($user) {
                    //   dd( $request->active_status );
                    $res = $user->update([
                        'name' => $request->name,
                        'email' => $request->email,
                        'mobile' => $request->mobile,
                        'active_status' => $request->active_status,
                        'dept_id' => $dept->dept_id,
                        'ministry_id' => $ministry->ministry_id, // Update with the fetched dept_id
                        // 'ministry_id' => isset( $ministry[ 'ministry_id' ] ) ? $ministry[ 'ministry_id' ] : null,
                        'role_id' => $role->role_id,
                        'password' => $user->password,
                        'post_id' => $user->post_id,
                    ]);

                    if ($res) {
                        // Return a response indicating success
                        //return response()->json( [ 'message' => 'Data updated successfully ' ] );
                        return redirect()->route('register_user_edit')->with('message', 'Data updated successfully!!!');
                    } else {
                        return back()->with('error', 'Failed to update...');
                    }
                }
            } else {
                $request->validate([
                    'id' => 'required|exists:users,id',
                    'name' => 'required|string|max:255', // Add the 'alpha' rule for alphabets only
                    'email' => [
                        'required',
                        'email',
                        'max:255',
                        Rule::unique('users', 'email')->ignore($request->id), // Add this line for checking uniqueness
                    ],
                    'mobile' => 'required',
                    'active_status' => 'required',
                    'dept_id' => 'required',
                    'ministry_id' => 'required',
                    'role_id' => 'required',
                    'password' => 'required|confirmed',
                    'post' => 'required',
                ], [
                    'email.required' => 'The email address is required.',
                    'email.unique' => 'The email address is already taken.',
                    'name.required' => 'The name is required.',
                    'name.string' => 'The name must only contain alphabets.',
                    'mobile.required' => 'The mobile number is required.',
                    'active_status.required' => 'The status is required.',
                ]);


                // Find the user by ID
                $user = User::findOrFail($request->id);

                //$userData = User::where( 'id', $request->id )->first();
                // Fetch dept_id based on dept_name
                $dept = Department::where('dept_id', $request->dept_id)->first();

                // Fetch ministry_id based on ministry
                $ministry = AdminDeptModel::where('ministry_id', $request->ministry_id)->first();
                //  dd( $request->role_id );
                // Fetch role_id based on role_name
                $role = Roles::where('role_id', $request->role_id)->first();
                // Check if dept_id is found
                // if ( $dept && $ministry && $role ) {
                // Update the user data
                //  dd( $role->role_id );
                if ($user) {
                    //   dd( $request->active_status );
                    $res = $user->update([
                        'name' => $request->name,
                        'email' => $request->email,
                        'mobile' => $request->mobile,
                        'active_status' => $request->active_status,
                        'dept_id' => $dept->dept_id,
                        'ministry_id' => $ministry->ministry_id, // Update with the fetched dept_id
                        // 'ministry_id' => isset( $ministry[ 'ministry_id' ] ) ? $ministry[ 'ministry_id' ] : null,
                        'role_id' => $role->role_id,
                        'password' => Hash::make($request->password),
                        'post_id' => $request->post,
                    ]);

                    if ($res) {
                        // Return a response indicating success
                        //return response()->json( [ 'message' => 'Data updated successfully ' ] );
                        return redirect()->route('register_user_edit')->with('message', 'Data updated successfully!!!');
                    } else {
                        return back()->with('error', 'Failed to update...');
                    }
                }
            }
        }

        if ($request->role_id == 9) {
            // dd( $request->all() );
            $request->validate([
                'id' => 'required|exists:users,id',
                'name' => 'required|string|max:255', // Add the 'alpha' rule for alphabets only
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($request->id), // Add this line for checking uniqueness
                ],
                'mobile' => 'required',
                'dept_id' => 'required',
                'ministry_id' => 'required',
                'role_id' => 'required',
                'password' => 'required|confirmed',
                'department_signing_authority' => 'required',
            ], [
                'email.required' => 'The email address is required.',
                'email.unique' => 'The email address is already taken.',
                'name.required' => 'The name is required.',
                'name.string' => 'The name must only contain string.',
                'mobile.required' => 'The mobile number is required.',

            ]);

            // Find the user by ID
            $user = User::findOrFail($request->id);

            //$userData = User::where( 'id', $request->id )->first();
            // Fetch dept_id based on dept_name
            $dept = Department::where('dept_id', $request->dept_id)->first();

            // Fetch ministry_id based on ministry
            $ministry = AdminDeptModel::where('ministry_id', $request->ministry_id)->first();
            // dd( $request->role_id );
            // Fetch role_id based on role_name
            $role = Roles::where('role_id', $request->role_id)->first();
            // Check if dept_id is found
            // if ( $dept && $ministry && $role ) {
            // Update the user data
            //dd( $user );
            if ($user) {
                $res = $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'dept_id' => $dept->dept_id,
                    'ministry_id' => $ministry->ministry_id, // Update with the fetched dept_id
                    // 'ministry_id' => isset( $ministry[ 'ministry_id' ] ) ? $ministry[ 'ministry_id' ] : null,
                    'role_id' => $role->role_id,
                    'password' => Hash::make($request->password),
                    'post_id' => $request->department_signing_authority,
                ]);

                if ($res) {
                    // Return a response indicating success
                    //return response()->json( [ 'message' => 'Data updated successfully ' ] );
                    return redirect()->route('register_user_edit')->with('message', 'Data updated successfully!!!');
                } else {
                    return back()->with('error', 'Failed to update...');
                }
            }
        }
    }
    // For Official User Edit

    public function register_user_edit(Request $request)
    {

        // dd( $request->dept_id );
        $request->session()->forget(['id']);

        $role_id = [1, 2, 3, 4, 5, 6, 8, 9];
        $users = User::orderBy('dept_id')->whereIn('role_id', $role_id)->get()->toArray();
        // dd( $users );

        $empList = User::orderBy('dept_id')->whereIn('role_id', $role_id)->paginate(5);
        // dd( $empList );

        foreach ($empList as $data) {

            $department = Department::where('dept_id', $data['dept_id'])->first();
            $ministry = AdminDeptModel::where('ministry_id', $data['ministry_id'])->first();
            $role = Roles::where('role_id', $data['role_id'])->first();

            //this is carry the name of concern names
            $data->id = $data->id;
            $data->dept_name = $department->dept_name;
            $data->ministry = $ministry->ministry;
            $data->role_name = $role->role_name;

            $cd_grade = array();
            $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
                'dept_code' => $data->dept_id,
                'token' => 'b000e921eeb20a0d395e341dfcd6117a',
            ]);
            $cd_grade = json_decode($response->getBody(), true);

            $postnames = [];
            foreach ($cd_grade as $cdgrade) {
                if (isset($cdgrade['dsg_serial_no']) && $cdgrade['dsg_serial_no'] == $data->post_id) {

                    $postnames[] = $cdgrade['dsg_desc'];
                }
            }

            // Assign the array of matching postnames to the user data
            $data->matching_postnames = $postnames;
        }

        return view('auth/register_user_edit', compact('empList', 'users', 'department', 'ministry', 'role'));
    }

    // public function indexOfficialUser( $id )
    // {

    //     //$user_id = Auth::user()->id;
    //     $id = Crypt::decryptString( $id );
    //     $user = User::findOrFail( $id );
    //     //dd( $user );
    //     $getUser = User::get()->where( 'id', $user->id )->first();
    //     //the above code needed for menu of header as per user

    //     //dd( $getUser );
    //     //Below ein is passed in the session

    //     // set a session emp EIN
    //     session()->put( 'id', $id );
    //     //$ein = session()->get( 'ein' );

    //     $rolesArray = Roles::get()->toArray();
    //     $ministryArray = AdminDeptModel::orderBy( 'ministry' )->get()->unique( 'ministry' );
    //     $departmentArray = Department::orderBy( 'dept_name' )->get()->unique( 'dept_name' );

    //     $userDetails = User::get()->where( 'id', $id )->toArray();
    //     if ( count( $userDetails ) == 0 ) {
    //         $data = null;
    //         // return view( 'admin/Form/form', compact( 'data' ) );
    //     } else {

    //         foreach ( $userDetails as $val ) {
    //             $data = $val;
    //         }

    //         return view( 'auth/updateOfficialUser', compact( 'data', 'rolesArray', 'ministryArray', 'departmentArray' ) );
    //     }
    // }

    public function indexOfficialUser($id)
    {

        //$user_id = Auth::user()->id;
        $id = Crypt::decryptString($id);
        $user = User::findOrFail($id);
        //dd( $user );
        $getUser = User::get()->where('id', $user->id)->first();
        //the above code needed for menu of header as per user

        // dd( $getUser->role_id );
        //Below ein is passed in the session
        if ($getUser->role_id == 1 || $getUser->role_id == 2 || $getUser->role_id == 3 || $getUser->role_id == 4 || $getUser->role_id == 5 || $getUser->role_id == 6  || $getUser->role_id == 8) {
            // set a session emp EIN
            session()->put('id', $id);
            //$ein = session()->get( 'ein' );
            $desiredRoleIds = [1, 2, 3, 4, 5, 6, 8, 9];

            $rolesArray = Roles::whereIn('role_id', $desiredRoleIds)->get()->toArray();
            // $rolesArray = Roles::get()->toArray();
            $ministryArray = AdminDeptModel::orderBy('ministry')->get()->unique('ministry');
            $departmentArray = Department::orderBy('dept_name')->get()->unique('dept_name');
            //  $postId = $getUser->post_id;
            // // Find the record in Sign2Model by ID
            //  $Id = Sign2Model::find( $postId );

            // // dd( $Id );
            // $name = $Id->name;
            // dd( $name );
            $departmentSigningAuthority = Sign2Model::orderBy('id')->get();

            $userDetails = User::get()->where('id', $id)->toArray();

            // dd( $userDetails );
            if (count($userDetails) == 0) {
                $data = null;
                // return view( 'admin/Form/form', compact( 'data' ) );
            } else {

                foreach ($userDetails as $val) {
                    $data = $val;
                }

                return view('auth/updateOfficialUser', compact('data', 'rolesArray', 'ministryArray', 'departmentArray', 'departmentSigningAuthority'));
            }
        }

        if ($getUser->role_id == 9) {
            // dd( $getUser->role_id );
            // set a session emp EIN
            session()->put('id', $id);
            //$ein = session()->get( 'ein' );

            $desiredRoleIds = [1, 2, 3, 4, 5, 6, 8, 9];

            $rolesArray = Roles::whereIn('role_id', $desiredRoleIds)->get()->toArray();
            //  $rolesArray = Roles::get()->toArray();
            $ministryArray = AdminDeptModel::orderBy('ministry')->get()->unique('ministry');
            $departmentArray = Department::orderBy('dept_name')->get()->unique('dept_name');
            // dd( $getUser->post_id );
            // $postId = $getUser->post_id;
            // Find the record in Sign2Model by ID
            // $Id = Sign2Model::find( $postId );

            // dd( $Id );
            // $name = $Id->name;
            // dd( $name );
            $departmentSigningAuthority = Sign2Model::orderBy('id')->get();

            $userDetails = User::get()->where('id', $id)->toArray();

            // dd( $userDetails );
            if (count($userDetails) == 0) {
                $data = null;
                // return view( 'admin/Form/form', compact( 'data' ) );
            } else {

                foreach ($userDetails as $val) {
                    $data = $val;
                }

                return view('auth/updateOfficialUser', compact('data', 'rolesArray', 'ministryArray', 'departmentArray', 'departmentSigningAuthority'));
            }
        }
    }

    public function deleteOfficialUser($id)
    {
        $id = Crypt::decryptString($id);
        $user = User::findOrFail($id);
        //dd( $user );
        //$getUser = User::get()->where( 'id', $user->id )->first();
        $res = User::where('id', $user->id)->delete();
        if ($res) {
            session()->forget('id');
            $id = null;
            //return response()->json( [ 'message' => 'Data updated successfully ' ] );
            return redirect()->route('register_user_edit')->with('message', 'Data updated successfully!!!');
        } else {
            return back()->with('error', 'Failed to delete...');
        }
    }

    public function retrieve_dept_register_user(Request $request)
    {

        // dd( $request );
        $deptId = $request->dept_id;

        $cd_grade = array();
        $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
            'dept_code' => $deptId,
            'token' => 'b000e921eeb20a0d395e341dfcd6117a',
        ]);
        $cd_grade = json_decode($response->getBody(), true);

        $postdept = [];
        foreach ($cd_grade as $cdgrade) {
            if ($cdgrade['group_code'] == 'A' || $cdgrade['group_code'] == 'B' || $cdgrade['group_code'] == 'C' || $cdgrade['group_code'] == 'D') {
                $postdept[] = $cdgrade;
            }
        }

        return $postdept;
    }
}
