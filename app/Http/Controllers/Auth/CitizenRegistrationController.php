<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Library\Senitizer;
use App\Library\SmsSender;
use App\Models\Relationship;
use App\Models\State;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CitizenRegistrationController extends Controller
{

    /**
     * Instantiate a new LoginRegisterController instance.
     */
    public function __construct(Request $request)
    {
        if (isset($_REQUEST)) {
            $_REQUEST = Senitizer::senitize($_REQUEST, $request);
        }
    }
    /**
     * Display the registration view for citizen.
     * This creation is only for citizen
     */

    public function citizenRegister(Request $request)
    {
        $states        = State::getOption()->get();
        $relationships = Relationship::orderBy('relationship_id')->get();
        return view('auth.register_citizen', compact('states', 'relationships'));
    }

    public function citizenPreRegistration(Request $request)
    {

        $this->custom_validator($request->all());
        //dd($request->all());
        $request->session()->put(['new_user_temp' => $_REQUEST,
            'expiry_time'                             => 60 * 20]);
        // dd('mobile_otp');
        SmsSender::send_otp($request->post("mobile"), 'mobile_otp');
        // SmsSender::sendEmailOtp($request->post("email"), 'email_otp');
        //$mobile = substr_replace($request->post('mobile'),"****",2,4);
        $mobile = substr_replace($request->post('mobile'), "****", 1, 7);
        //  $email = explode('@', $request->post("email"));
        // $email = substr_replace( $email[0],"****",2,-3).'@'. $email[1] ;
        return response()->json([
            'status'       => 1,
            'msg'          => "OTP send",
            //'mobileotpmsg' => "OTP has been send to your mobile number:&ensp;" .$mobile. "(". Session::get('mobile_otp') .")",
            //'emailotpmsg' => 'OTP has been send to your Email:&ensp;'.$email . "(". Session::get('email_otp') .")",
            'mobileotpmsg' => "OTP has been send to your mobile number:&ensp;" . $mobile,
            //  'emailotpmsg' => 'OTP has been send to your Email:&ensp;'.$email,
        ]);
    }

    /**
     * Registration save to database
     */
    public function citizenSaveRegistration(Request $request)
    {
        if (! Session::has('new_user_temp')) {
            return response()->json([
                'status' => 0,
                'msg'    => "Session time out.",
            ]);
        }
        $new_user_temp = $request->session()->get('new_user_temp');
        $mobile_otp    = $request->post("mobile_otp");
        // $email_otp = $request->post("email_otp");
        $otpError['flag'] = 0;
        if (! SmsSender::check_otp($mobile_otp, 'mobile_otp')) {
            $otpError['flag']           = 1;
            $otpError['mobileOtpError'] = 'Error! Mobile OTP does not match.';
        }
                                 // if( !SmsSender::check_otp( $email_otp , 'email_otp') ){
                                 //     $otpError['flag'] = 1;
                                 //     $otpError['emailOtpError'] = 'Error! Email OTP does not match.';
                                 // }
        if ($otpError['flag']) { //both mobie and email error
            unset($otpError['flag']);
            return response()->json(array_merge([
                'status' => 0,
                'msg'    => 'Error! OTP does not match.',
            ],
                $otpError)
            );
        }

        try {
            DB::beginTransaction();
            $users = User::create([
                'name'     => $new_user_temp['name'],
                'mobile'   => $new_user_temp['mobile'],
                'email'    => $new_user_temp['email'],
                'password' => Hash::make($new_user_temp['password']),
                'role_id'  => 77,
                //  'category_id'=>1
            ]);

            UserDetail::create([
                'user_id'               => $users->id,
                'gender'                => $new_user_temp['gender'],
                'relative_name'         => $new_user_temp['relative_name'],
                'relationship_id'       => $new_user_temp['relationship_id'],
                'current_address1'      => $new_user_temp['current_address1'],
                'current_address2'      => $new_user_temp['current_address2'],
                'current_address3'      => $new_user_temp['current_address3'],
                'current_pin'           => $new_user_temp['current_pin'],
                //  'current_country_id' =>  $new_user_temp['current_country_id'],
                'current_state_id'      => $new_user_temp['current_state_id'],
                'current_district_id'   => $new_user_temp['current_district_id'],
                'permanent_address1'    => $new_user_temp['permanent_address1'],
                'permanent_address2'    => $new_user_temp['permanent_address2'],
                'permanent_address3'    => $new_user_temp['permanent_address3'],
                'permanent_pin'         => $new_user_temp['permanent_pin'],
                'permanent_state_id'    => $new_user_temp['permanent_state_id'],
                'permanent_district_id' => $new_user_temp['permanent_district_id'],

            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 0,
                'msg'    => "Failed to save.",
                'errors' => $e->getMessage(),
            ]);
        }
        //return success
        DB::commit();
        Session::forget('new_user_temp'); //remove from session
        Session::forget('mobile_otp');    //remove from session
                                          // Session::forget('email_otp'); //remove from session
        return response()->json([
            'status' => 1,
            'msg'    => "Successfully registered. Login to start using the application.",
        ]);
    }

    /**
     * Resend SMSOTP
     */
    public function smsRegistrationOTP(Request $request)
    {
        if (! Session::has('new_user_temp')) {
            return response()->json([
                'status' => 0,
                'msg'    => "Session time out.",
            ]);
        }
        $new_user_temp = $request->session()->get('new_user_temp');
        SmsSender::resend_otp($new_user_temp['mobile'], 'mobile_otp');
    }

    /**
     * Resend EMAILOTP
     */
    public function emailRegistrationOTP(Request $request)
    {
        if (! Session::has('new_user_temp')) {
            return response()->json([
                'status' => 0,
                'msg'    => "Session time out.",
            ]);
        }
        $new_user_temp = $request->session()->get('new_user_temp');
        SmsSender::resend_EmailOtp($new_user_temp['email'], 'email_otp');
    }

    protected function custom_validator(array $data)
    {

        $rules = [
            'name'                  => ['required', 'string', 'max:75'],
            'mobile'                => ['required', 'integer', 'min:6000000000', 'max:9999999999'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'gender'                => ['required', 'string', 'max:20'],
            'relative_name'         => ['required', 'string', 'max:75'],
            'relationship_id'       => ['required', 'integer', 'exists:relationships,relationship_id'],
            'current_address1'      => ['required', 'string', 'max:75'],
            'current_address2'      => ['sometimes', 'nullable', 'string', 'max:75'],
            'current_address3'      => ['sometimes', 'nullable', 'string', 'max:75'],
            'current_pin'           => ['required', 'integer', 'min:100000', 'max:999999'],
            'current_state_id'      => ['required', 'integer', 'exists:states,state_id'],
            'current_district_id'   => ['required', 'integer', 'exists:districts,district_id'],
            'permanent_address1'    => ['required', 'string', 'max:75'],
            'permanent_address2'    => ['sometimes', 'nullable', 'string', 'max:75'],
            'permanent_address3'    => ['sometimes', 'nullable', 'string', 'max:75'],
            'permanent_pin'         => ['required', 'integer', 'min:100000', 'max:999999'],
            'permanent_state_id'    => ['required', 'integer', 'exists:states,state_id'],
            'permanent_district_id' => ['required', 'integer', 'exists:districts,district_id'],

            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8'],
        ];
        $niceNames = [
            'name'                  => "Applicant Name",
            'mobile'                => "Mobile Number",
            'email'                 => "Valid Email Id",
            'gender'                => "Gender",
            'relative_name'         => "Relative Name",
            'relationship_id'       => "Relationship with Applicant",
            'current_address1'      => "Address Line 1",
            'current_address2'      => "Address Line 2",
            'current_address3'      => "Address Line 3",
            'current_pin'           => "Pin Code",
            'current_state_id'      => "State",
            'current_district_id'   => "District",
            'permanent_address1'    => "Address Line 1",
            'permanent_address2'    => "Address Line 2",
            'permanent_address3'    => "Address Line 3",
            'permanent_pin'         => "Pin Code",
            'permanent_state_id'    => "State",
            'permanent_district_id' => "District",
            'password'              => "Password",
            'password_confirmation' => "Confirmation Password",
        ];
        return Validator::make($data, $rules, [], $niceNames);
    }
}
