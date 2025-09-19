<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CitizenPreRegisterRequest;
use App\Library\Senitizer;
use App\Library\SmsSender;
use App\Models\Relationship;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use App\Models\UserDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class CitizenRegistrationController extends Controller
{
    public function __construct(Request $request)
    {
        if (isset($_REQUEST)) {
            $_REQUEST = Senitizer::senitize($_REQUEST, $request);
        }
    }

    public function citizenRegister()
    {
        $states        = State::getOption()->get();
        $relationships = Relationship::whereIn('relationship_name', [
            'Father',
            'Mother',
            'Spouse',
            'Other',
        ])->orderBy('relationship_id')->get();
        return view('auth.register_citizen', compact('states', 'relationships'));
    }

    public function citizenPreRegistration(CitizenPreRegisterRequest $request)
    {
        // Validation already done by FormRequest
        $request->session()->put([
            'new_user_temp' => $request->validated(),
            'expiry_time'   => 60 * 20,
        ]);

        SmsSender::resend_SmsOtp($request->post("mobile"), 'mobile_otp');

        $mobile = substr_replace($request->post('mobile'), "****", 1, 7);

        return response()->json([
            'message'      => "OTP send",
            'mobileotpmsg' => "OTP has been send to your mobile number:&ensp;" . $mobile,
        ]);
    }

    public function citizenSaveRegistration(Request $request)
    {
        try {
            $new_user_temp = $request->session()->get('new_user_temp');
            $mobile_otp    = $request->post("mobile_otp");

            if (!SmsSender::check_otp($mobile_otp, 'mobile_otp')) {
                return response()->json([
                    'message' => 'Error! Mobile OTP does not match.',
                ], 422);
            }

            DB::beginTransaction();
            //Find citizen role
            $role = Role::where('role_name', 'Citizen')->first();
            $citizen_role_id = empty($role) ? 77 : $role->role_id;
            $user = User::create([
                'fullname' => $new_user_temp['name'],
                'mobile'   => $new_user_temp['mobile'],
                'email'    => $new_user_temp['email'],
                'password' => Hash::make($new_user_temp['password']),
                'role_id'  => $citizen_role_id,
            ]);

            $new_user_temp['user_id'] = $user->user_id;
            UserDetail::create($new_user_temp);

            DB::commit();

            Session::forget(['new_user_temp', 'mobile_otp']);

            return response()->json([
                'message' => "Successfully registered. Login to start using the application.",
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => "Internal Server Error.",
                'errors'  => $e->getMessage(),
            ], 500);
        }
    }

    public function smsRegistrationOTP(Request $request)
    {
        if (!Session::has('new_user_temp')) {
            return response()->json(['message' => "Session time out."], 422);
        }
        $new_user_temp = $request->session()->get('new_user_temp');
        SmsSender::resend_SmsOtp($new_user_temp['mobile'], 'mobile_otp');
        return response()->json(['message' => "Resend Successfully"], 200);
    }

    public function emailRegistrationOTP(Request $request)
    {
        if (!Session::has('new_user_temp')) {
            return response()->json(['message' => "Session time out."], 422);
        }
        $new_user_temp = $request->session()->get('new_user_temp');
        SmsSender::resend_EmailOtp($new_user_temp['email'], 'email_otp');
        return response()->json(['message' => "Resend Successfully"], 200);
    }
}
