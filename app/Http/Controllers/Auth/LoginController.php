<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Library\Senitizer;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserLogModel;
use Illuminate\Support\Facades\Hash;
use App\Library\SmsSender;
use App\Models\CountryModel;
use App\Models\PasswordHistoryModel;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\CurrentSessionModel;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

     

    public function __construct(Request $request)
    {
        if( isset($_REQUEST) ){
            $_REQUEST = Senitizer::senitize($_REQUEST, $request);
       }
        $this->middleware('guest')->except('logout');

    }

    public function username()
    {
        return 'email';
    }
    public function email()
    {
        return 'email';
    }

    public function smsLoginOTP(Request $request)
    {
        $validator =  Validator::make(
            $request->all(),
            [
                'emailDept' => ['required', 'exists:users,email'],
                'passwordDept' => ['required'],
            ],
            [],
            ['email' => "Given Email Id",  'password' => "Given Credential"]
        );


        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'msg' => 'Error! Failed. Wrong Credential',
                'errors' => ['emailDept' => 'Your provided credentials do not match in our records.',]
            ]);
        }

        $user = User::where('email', $request->post('emailDept'))->first();

        // $user->attempts += 1;
        // $user->last_attempt_date = now()->toDateString();
        // $user->save();
        if( abs($user->role_id) > 10){
            return response()->json([
                'status' => 0,
                'msg' => 'Failed',
                'errors' => ['emailDept'=>'You are not authorized.',]
            ]);
        }
        if(3 <=  UserLogModel::getAttemptNumber($user->id)){
            return response()->json([
                'status' => 0,
                'msg' => 'Failed',
                'errors' => ['emailDept'=>'Please try after 1 hour.',]
            ]);
        }

        if (Hash::check($request->post('passwordDept'), $user->password)) {

            SmsSender::send_otp($user->mobile, 'login_otp_Dept');

            $mobile = substr_replace($user->mobile, "*******", 1, 7);
           
            return response()->json([
                'status' => 1,
                'msg' => "OTP has been send to your mobile number: " . $mobile,
            ]);
        }

        UserLogModel::create( [
            'user_id' => $user->id,
            'username' => $user->username,
            'mobile' => $user->mobile,
            'email' => $user->email,
        
           'attempts' => $user->attempts,
           'last_attempt_date' => $user->last_attempt_date,
       ] );
       $this->insert_login_log($user,  "smsLoginOTP", false);
        return response()->json([
            'status' => 0,
            'msg' => 'Failed',
            'errors' => ['emailDept' => 'Your provided credentials do not match in our records.',]
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
            'msg' => "OTP has been send to your mobile number: " . $mobile_number,
        ]);
    }

   /**
     * Authenticate the user.
     */
    public function authenticate(Request $request)
    {        

        $postArray = array(
            'email' => $request->post('emailDept'),
            'password' => $request->post('passwordDept')
        );

        $validator =  Validator::make($postArray, 
                    [ 'email' => ['required', 'exists:users,email'], 'password' => 'required' ], 
                    [],  
                    [ 'email' => "Given Email Id",  'password' => "Given Credential"  ]  );
        
        $credentials = $validator->validate();
        

        //user email exist 
        $user = User::where("email",$request->post('emailDept'))->first();

        if (!$user->active_status) {
            return response()->json([
                'status' => 0,
                'msg' => 'Your account is inactive.', 
            ]);
        }
        
        if(3 <=  UserLogModel::getAttemptNumber($user->id)){
            // return back()->withErrors([
            //     'email' => 'Please try after 1 hours.',
            // ])->onlyInput('email');
            return response()->json([
                'status' => 0,
                'msg' => 'Please try again after 1 hour.',
            ]);
        }

        if( !SmsSender::check_otp($request->post('login_otp_Dept'), "login_otp_Dept" ) ){
            // return back()->withErrors([
            //     'Mismatch-OTP' => 'OTP does not match.',
            // ])->onlyInput('email');
            return response()->json([
                'status' => 0,
                'msg' => 'OTP does not match.',
            ]);
        }


        //return $this->insert_login_log($user, false);
        if(Auth::attempt($credentials))
        {   
            $request->session()->regenerate();
            $this->insert_login_log($user, true);

            ///////////////////////////////////////////////////////
                                
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

           // return redirect()->route('dashboard');
           return response()->json([
            'status' => 1,
            'msg' => "Login Success",
        ]);
        }
        $this->insert_login_log($user, false);

        // return back()->withErrors([
        //     'email' => 'Your provided credentials do not match in our records.',
        // ])->onlyInput('email');
        return response()->json([
            'status' => 0,
            'msg' => 'Your provided credentials do not match in our records.',
        ]);

    } 

    public function insert_login_log($user, $flag){
        
            $attempt_number =  UserLogModel::getAttemptNumber($user->id);
            if($flag){
                $attempt_number = 0;
            }
            else{
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
     * Log out the user from application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        if(Auth::check()){        
            $this->insert_login_log(Auth::user(), true);
        }

        // Delete the record from CurrentSessionModel if the user ID exists
        $user_id = Auth::user()->id;
        CurrentSessionModel::where('user_id', $user_id)->delete();


        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome');
    }    



    public function forgotPassword(Request $request){
        return view("auth.forgotPassword");
    }

    public function passwordResetEmail(Request $request){

        $rules = [
                'email' => ['required', 'exists:users,email']
            ];
        $niceNames = [
                'email' => "Email"
        ]; 
        Validator::make($request->all(), $rules, [],  $niceNames  )->validate();

        try{
            DB::beginTransaction();
            $str_result = '23456789ABCDEFGHJKMNPQRSTUVWXYZabcdefghkpqrtyz';
            $password = substr(str_shuffle($str_result),
                            0, 8);

            $mailData=[ "view"=>"email.passwordReset",
                        "subject"=>"Reset Password",
                        "title"=>"Die-in-Harness Appointment System",
                        "body"=> $password
                    ];

            if(!SmsSender::sendEmail($request->post("email"), $mailData)){            
                throw new Exception("Failed to Reset"); 
            }

            $user = User::where('email', $request->post('email'))->first();
            $this->passwordHistorySave($user);
            $user->password = Hash::make( $password );
            $user->save();


        }     
        catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['msg' => 'Fail to Reset Password.']);
        }
        $email = explode('@', $request->post("email"));
        $email = substr_replace( $email[0],"****",2,-3).'@'. $email[1] ;
        //return success
        DB::commit();
        return redirect()->back()->with(['success' => 'Your password has been send to '.$email]);

    }

    public function passwordHistorySave($user){
        try{
            PasswordHistoryModel::create([
                'user_id' => $user->user_id,
                'old_password' => $user->password,
                'ip_address' => get_client_ip(), //this function is at app/helpers

            ]);
        }
        catch(Exception $e){
            return false;
        }

        return true;

    }

   
    /**
     * Calling Sandes API for sending OTP
     */
    //public function callSandesAPI($OTP, $receiverid){		
    //	$msg = urlencode("Your OTP to login to DIHAS Manipur is ".$OTP.". Validity of this OTP is 5 minutes. Do not share with anyone.");

    //--- curl stated ---
    //	$url = "http://localhost:8021/send?receiverid=".$receiverid."&msg=".$msg."&priority=high-volatile";

    //--- curl ended ---	
    //	$curl = curl_init();
    //   curl_setopt($curl, CURLOPT_URL, $url);
    //   curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
    //  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    //	$response = curl_exec($curl);
    //	curl_close($curl);
    // dd($response);
    //	return $response;
    //}
}
