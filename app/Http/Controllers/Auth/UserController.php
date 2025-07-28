<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Support\Facades\Auth;
use App\Models\CountryModel;
use App\Models\IdproofModel;
use App\Models\RelationshipModel;

use App\Library\SmsSender;
use App\Models\DistrictModel;
use App\Models\User;

use App\Models\StateModel;
use App\Models\UserDetailModel;
use App\Models\PasswordHistoryModel;
use App\Models\StateModelRegister;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;










class UserController extends Controller
{
    //
     //
     public function displayUsers(Request $request){
        $users = User::getUsers()->get();
        return view('user.displayUsers',['users'=>$users,'page_title'=>'Users']);
    }

    
    public function officialViewUser(Request $request){
        $users = User::getUsers();
       // dd( $users);
        //$users = User::getUsers([1,2,3,4,5,6])->get();
        return view('auth.viewOfficial',['users'=>$users,'page_title'=>'Users']);
    }

    public function profileSetting(Request $request){
        $user = Auth::user();

        if($user->role_id != 77){
            return view('auth.profileEdit', compact('user'));
        }
      // $user_detail = UserDetailModel::getUserDetail($user->user_id)->first();
       $countries = CountryModel::getOption()->get();
       //dd($user_detail);
       //$idproofs = IdproofModel::getOption()->get();
      // $relationships = RelationshipModel::getOption()->get();
        
       //$cur_states = StateModelRegister::getOptionByCountry($user_detail->current_country_id)->get();
       //$per_states = StateModelRegister::getOptionByCountry($user_detail->permanent_country_id)->get();


      // $cur_districts = DistrictModel::getOptionByState($user_detail->current_state_id)->get();
      // $per_districts = DistrictModel::getOptionByState($user_detail->permanent_state_id)->get();


        return view('auth.profileEdit', compact('user'));
    }

    public function saveProfilePassword(Request $request){
       // dd($request->post('password'));
        $user = Auth::user();
        $user_id = $user->id;
        // $user_id = Auth::user()->id;
        // $getUser = User::get()->where('id', $user_id)->first();
      

       //dd(Auth::user());
                $rules = [
                        'old_password' => ['required', 'string'],
                        'password' => ['required', 'string', 'min:8', 'confirmed' ],
                        'password_confirmation' => ['required', 'string', 'min:8' ],
                    ];
                $niceNames = [
                        'old_password' => "Old Password",
                        'password' => "New Password",
                        'password_confirmation' => "Confirmation Password"
                ]; 
                Validator::make($request->all(), $rules, [],  $niceNames  )->validate();

                if (!Hash::check($request->post('old_password'), $user->password) ) { 
                    return response()->json([
                        'status' => 0,
                        'msg' => "Old Password doesn't match.",
                    ]);
                }
                 try{
             //  return Hash::make($request->post('password'));
                   DB::beginTransaction();
                  // $this->passwordHistorySave(Auth::user());
                   $getUser = User::get()->where('id', $user_id)->first();
                    $getUser->password = Hash::make($request->post('password'));
                    $getUser->save();
                //   User::where('id', $user_id)
                //                     ->update([
                //                         'password' => Hash::make($request->post('password'))
                //                     ]);
                                    
               }     
                catch (Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 0,
                        'msg' => "Failed to save.",
                        'error' => $e->getMessage()
                    ]); 
                }

            //return success
            DB::commit();
            return response()->json([
                        'status' => 1,
                        'msg' => "Successfully Saved. Kindly log in new password.",
                ]); 
       
    }

    
//    public function userProofIdDoc(Request $request, $doc_id){
//         $resp = UserDetailModel::select('idproof_link')->where('user_id',$doc_id)->first();
//         $url = $resp->idproof_link;
//         return response()->make(file_get_contents(Storage::path($url)), 200, [
//             'Content-Type' => 'application/pdf',
//             'Content-Disposition' => 'inline;'
//         ]);
//     }

    

    // public function profileSetting(Request $request){
    //     $user = Auth::user();
    //     if($user->role_id != 77){
    //         return view('authentication.profileEdit', compact('user'));
    //     }
    //     $user_detail = UserDetailModel::getUserDetail($user->user_id)->first();
    //     $countries = CountryModel::getOption()->get();
    //     $idproofs = IdproofModel::getOption()->get();
    //     $relationships = RelationshipModel::getOption()->get();
    //     $cur_states = StateModel::getOptionByCountry($user_detail->current_country_id)->get();
    //     $per_states = StateModel::getOptionByCountry($user_detail->permanent_country_id)->get();
    //     return view('authentication.profileEdit', compact('user','user_detail','countries',
    //                                             'idproofs','relationships', 'cur_states', 'per_states'));
    // }
    
//    public function saveProfileMain(Request $request){
//         $user_id = Auth::user()->user_id;

//         $rules = [
//                 'fullname' => ['required', 'string', 'max:75'],
//                 'mobile' => ['required', 'integer', 'min:6000000000', 'max:9999999999'],
//                 'gender' => ['required', 'string', 'max:20'],
//                 'relative_name' => ['required', 'string', 'max:75'],
//                 'relationship_id' => ['required', 'exists:relationships,relationship_id'],
//                 'idproof_id' => ['required', 'exists:idproofs,idproof_id'],
//                 'idproof_document' => ['sometimes', 'mimetypes:application/pdf', 'max:10000'],

//             ];


        
//         if((int) $request->post('idproof_id',0) != 1){
//             $rules = array_merge( ['idproof_number'=> ['required', 'string', 'max:75']], $rules);
//         }

//         $niceNames = [
//                 'fullname' => "Applicant Name",
//                 'mobile' => "Mobile Number",
//                 'email' => "Valid Email Id",
//                 'gender' => "Gender",
//                 'relative_name' => "Relative Name",
//                 'relationship_id' => "Relationship with Applicant",
//                 'idproof_id' => "ID proof",
//                 'idproof_number' => "ID Card Number",
//                 'idproof_document' => "Id Document",
//         ]; 
//         Validator::make($request->all(), $rules, [],  $niceNames  )->validate();
//         try{
//             DB::beginTransaction();
//             User::where('user_id', $user_id)
//                             ->update([
//                                 'fullname' => $request->post( 'fullname' ),
//                                 'mobile_number' => $request->post( 'mobile' ),
//                             ]);

//             UserDetailModel::where('user_id', $user_id)
//                             ->update([
//                                 'gender' => $request->post( 'gender' ),
//                                 'relative_name' =>  $request->post( 'relative_name' ),
//                                 'relationship_id' =>  $request->post( 'relationship_id' ),
//                                 'idproof_id' =>  $request->post( 'idproof_id' ),
//                                 'idproof_number' =>  $request->post( 'idproof_number' ),
//                             ]);

//             if(!is_null($request->file('idproof_document'))){
                    
//                     $filename = "id_".$user_id.".pdf";
//                     $fileUrl = $request->file('idproof_document')->storeAs(
//                     'user_document',  $filename
//                     );
//                     $fileDir = storage_path('app/user_document/'.$filename);
//                     $exists = File::exists($fileDir);
//                     if(!$exists){
//                         throw new Exception("Failed to add");
//                     }
//                     //doc link save
//                     $user_details = UserDetailModel::where('user_id', $user_id)
//                             ->update([
//                                 'idproof_link' => $fileUrl
//                             ]);
//                 }
//         }     
//         catch (Exception $e) {
//             DB::rollBack();
//             return response()->json([
//                 'status' => 0,
//                 'msg' => "Failed to save.",
//             ]); 
//         }

//        //return success
//        DB::commit();
//        return response()->json([
//                 'status' => 1,
//                 'msg' => "Successfully Save",
//         ]); 

//     }
    
//    public function saveProfileAddress(Request $request){
//         $user_id = Auth::user()->user_id;
//             $rules = [
//                     'current_address1' => ['required', 'string', 'max:75'],
//                     'current_address2' => ['sometimes', 'nullable',  'string', 'max:75'],
//                     'current_address3' => ['sometimes', 'nullable',  'string', 'max:75'],
//                     'current_pin' => ['required', 'integer',  'min:100000',  'max:999999'],
//                     'current_country_id' => ['required', 'exists:countries,country_id'],
//                     'current_state_id' => ['required', 'exists:states,state_id'],

//                     'permanent_address1' => ['required', 'string', 'max:75'],
//                     'permanent_address2' => ['sometimes', 'nullable',  'string', 'max:75'],
//                     'permanent_address3' => ['sometimes', 'nullable',  'string', 'max:75'],
//                     'permanent_pin' => ['required', 'integer',  'min:100000',  'max:999999'],
//                     'permanent_country_id' => ['required', 'exists:countries,country_id'],
//                     'permanent_state_id' => ['required', 'exists:states,state_id'],
//                 ];
//             $niceNames = [
//                      'current_address1' => "Address Line 1",
//                     'current_address2' => "Address Line 2",
//                     'current_address3' => "Address Line 3",
//                     'current_pin' => "Pin Code",
//                     'current_country_id' => "Country",
//                     'current_state_id' => "State",

//                     'permanent_address1' => "Address Line 1",
//                     'permanent_address2' => "Address Line 2",
//                     'permanent_address3' => "Address Line 3",
//                     'permanent_pin' => "Pin Code",
//                     'permanent_country_id' => "Country",
//                     'permanent_state_id' => "State",
//             ]; 
//             Validator::make($request->all(), $rules, [],  $niceNames  )->validate();
//             try{
//                 DB::beginTransaction();                

//                 UserDetailModel::where('user_id', $user_id)
//                                 ->update([
//                                     'current_address1' =>  $request->post( 'current_address1' ),
//                                     'current_address2' =>  $request->post( 'current_address2' ),
//                                     'current_address3' =>  $request->post( 'current_address3' ),
//                                     'current_pin' =>  $request->post( 'current_pin' ),
//                                     'current_country_id' =>  $request->post( 'current_country_id' ),
//                                     'current_state_id' =>  $request->post( 'current_state_id' ),

//                                     'permanent_address1' =>  $request->post( 'permanent_address1' ),
//                                     'permanent_address2' => $request->post( 'permanent_address2' ),
//                                     'permanent_address3' =>  $request->post( 'permanent_address3' ),
//                                     'permanent_pin' =>  $request->post( 'permanent_pin' ),
//                                     'permanent_country_id' =>  $request->post( 'permanent_country_id' ),
//                                     'permanent_state_id' =>  $request->post( 'permanent_state_id' ),
//                                 ]);
//             }     
//             catch (Exception $e) {
//                 DB::rollBack();
//                 return response()->json([
//                     'status' => 0,
//                     'msg' => "Failed to save.",
//                 ]); 
//             }

//         //return success
//         DB::commit();
//         return response()->json([
//                     'status' => 1,
//                     'msg' => "Successfully Save",
//             ]); 
       
//     }
//    public function saveProfilePassword(Request $request){
//         $user = Auth::user();
//         $user_id = $user->user_id;
//                 $rules = [
//                         'old_password' => ['required', 'string'],
//                         'password' => ['required', 'string', 'min:8', 'confirmed' ],
//                         'password_confirmation' => ['required', 'string', 'min:8' ],
//                     ];
//                 $niceNames = [
//                         'old_password' => "Old Password",
//                         'password' => "New Password",
//                         'password_confirmation' => "Confirmation Password"
//                 ]; 
//                 Validator::make($request->all(), $rules, [],  $niceNames  )->validate();

//                 if ( !Hash::check($request->post('old_password'), $user->password) ) { 
//                     return response()->json([
//                         'status' => 0,
//                         'msg' => "Old Password doesn't match.",
//                     ]);
//                 }
//                 try{
//                     DB::beginTransaction();
//                     $this->passwordHistorySave(Auth::user());
//                     User::where('user_id', $user_id)
//                                     ->update([
//                                         'password' => Hash::make($request->post('password')),
//                                     ]);
//                 }     
//                 catch (Exception $e) {
//                     DB::rollBack();
//                     return response()->json([
//                         'status' => 0,
//                         'msg' => "Failed to save.",
//                     ]); 
//                 }

//             //return success
//             DB::commit();
//             return response()->json([
//                         'status' => 1,
//                         'msg' => "Successfully Save",
//                 ]); 
       
//     }


    public function forgotPassword(Request $request){
        return view("auth.forgotPassword");
    }

    public function passwordResetEmail(Request $request){
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
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
                        "title"=>"Justice Gita Mittal Commsission",
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

    public function passwordHistorySave(){
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        try{
            PasswordHistoryModel::create([
                'user_id' => $getUser->id,
                'old_password' => $getUser->password,
                // 'ip_address' =>  get_client_ip() ,
               // 'ip_address' => 1 ,

            ]);
        }
        catch(Exception $e){
            return false;
        }

        return true;
       
    }

}
