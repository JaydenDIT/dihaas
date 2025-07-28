<?php

namespace App\Http\Controllers;

use App\Models\ProformaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UOGeneration;
use App\Models\Sign1Model;
use App\Models\Sign2Model;
use App\Models\DesignationModel;
use Illuminate\Support\Facades\Http;
use App\Models\UOGenerationModel;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\RelationshipModel;
use App\Models\DepartmentModel;
use DateTime;
use Illuminate\Support\Facades\Crypt;

class FillUOController extends Controller
{
  
    public function update_view($id)
    {



        
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        //dd($getUser->username);
        $id = Crypt::decryptString($id);
        //id is encrypted and send through URL
        //Below ein is passed in the session
        if (session()->has('ein')) {
            $session_ein = session()->get('ein');
            if ($session_ein != $id) {
                session()->forget('ein');
            }
        }
        // set a session emp EIN 
        session()->put('ein', $id);
        $ein = session()->get('ein');
        // dd($ein);
        $data = ProformaModel::get()->where('ein', $ein)->first();
        // dd($data['transfer_post_id']) ;
        $uo_filled_data = UOGenerationModel::get()->where('ein', $ein)->first();
//    dd( $uo_filled_data);
      
            $relationshipId = $data->relationship; 
            $relationship = RelationshipModel::find($relationshipId);
        // dd($relationship->relationship);
            $deptId = $data->dept_id;
            $deptname = DepartmentModel::where('dept_id', $deptId)->first(); 
           
            //  dd($deptname->dept_name);
            $deptIdOther = $data->dept_id_option;  
         
          $deptnameOther = DepartmentModel::where('dept_id', $deptIdOther)->first(); 
        //    dd($deptnameOther);
        
        //  $data = ProformaModel::findOrFail($id);

        // You can pass the $vacancy object to the view for displaying the editable form
        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        $Sign1 = Sign1Model::get()->toArray();
        $Sign2 = Sign2Model::get()->toArray();
        // dd( $data['transfer_post_id'] );
       
        // hannagi sidei
            $api_preference = array();
            // $notfound = "";
//  dd( $data['transfer_post_id'] );
            $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
                'dept_code' => $data->dept_id,
                'token' => "b000e921eeb20a0d395e341dfcd6117a",
            ]);
            $api_preference = json_decode($response->getBody(), true);


            //for transfer_post_id and transfer_dept_id which is different from other 
            $api_preference_for_transfer_dept_id = array();
            // $notfound = "";

            $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
                'dept_code' => $data->transfer_dept_id,
                'token' => "b000e921eeb20a0d395e341dfcd6117a",
            ]);

        //    dd($data['transfer_post_id']) ;
            
            $api_preference_for_transfer_dept_id = json_decode($response->getBody(), true);



            
            $dsgSerialNumbers = [];

            
            foreach ($api_preference as $item) {
            
                if (isset($item['dsg_serial_no'])) {
                    
                    $dsgSerialNumbers[] = $item['dsg_serial_no'];
                }
            }


            //for first preference 
            $first_preference = null; // Initialize the variable to store deg_desc

            foreach ($api_preference as $item) {
                if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data['applicant_desig_id']) {
                    $first_preference = $item['dsg_desc'];

                

                    break; 
                }
            }

            $preference_update_filled = null; // Initialize the variable to store deg_desc

            foreach ($api_preference as $item) {
                if (isset($item['dsg_desc']) && $item['dsg_desc'] == $uo_filled_data['post']) {
                    $preference_update_filled = $item['dsg_desc'];

                

                    break; 
                }
            }

                        
            

            //for second preference dsg_serial_no
            $second_preference = null; // Initialize the variable to store deg_desc

            foreach ($api_preference as $item) {
                if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data['second_post_id']) {
                    $second_preference = $item['dsg_desc'];

                        // dd( $second_preference);
                    break; 
                }
            }


                //for third preference 
                $third_preference = null; // Initialize the variable to store deg_desc

                foreach ($api_preference as $item) {
                    if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data['third_post_id']) {
                        $third_preference = $item['dsg_desc'];

                        //  dd( $third_preference);
                        break; 
                    }
                }

            

                $first_preference_dept = null; // Initialize the variable to store deg_desc

                foreach ($api_preference as $item) {
                    if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data['applicant_desig_id']) {
                        $first_preference_dept = $item['field_dept_desc'];

                    

                        break; 
                    }
                }
                
                $second_preference_dept = null; // Initialize the variable to store deg_desc

                foreach ($api_preference as $item) {
                    if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data['second_post_id']) {
                        $second_preference_dept = $item['field_dept_desc'];

                        // dd( $second_preference_dept);here we get the department of second preference
                        break; 
                    }
                }
            

                //for third preference 
                $third_preference_dept = null; // Initialize the variable to store deg_desc

                foreach ($api_preference as $item) {
                    if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data['third_post_id']) {
                        $third_preference_dept = $item['field_dept_desc'];

                        //    dd( $third_preference_dept);
                        break; 
                    }
                }
            


                $first_preference_grade = null; // Initialize the variable to store deg_desc

                foreach ($api_preference as $item) {
                    if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data['applicant_desig_id']) {
                        $first_preference_grade = $item['group_code'];
            
                
                        break;
                    }
                }


                $second_preference_grade = null; // Initialize the variable to store deg_desc

                foreach ($api_preference as $item) {
                    if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data['second_post_id']) {
                        $second_preference_grade = $item['group_code'];


                
                        break; 
                    }
                }
                $third_preference_grade = null; // Initialize the variable to store deg_desc

                foreach ($api_preference as $item) {
                    if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data['third_post_id']) {
                        $third_preference_grade = $item['group_code'];

                        //    dd( $third_preference_grade);
                        break; 
                    }
                }





                
                $uo_data = UOGenerationModel::where("ein", $data->ein)->get()->first();

                $third_post = null; // Initialize the variable to store deg_desc

                foreach ($api_preference as $item) {
                    if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $uo_data['third_post_id']) {
                        $third_post = $item['dsg_desc'];

                            //   dd( $third_post);
                        break; 
                    }
                }
                

                $empname = UOGenerationModel::where("ein", $data->ein)->get()->first();
            


                $sign1Name = Sign1Model::find($empname->signing_authority_1);
                $sign2Name = Sign2Model::find($empname->signing_authority_2);
                if ($sign1Name) {
                    $authorityName1 = $sign1Name->authority_name;
                    // dd($authorityName1);
                }

                if ($sign2Name) {
                    $authorityName2 = $sign2Name->name;
                    //  dd($authorityName2);
                }

             
        return view('admin.Form.filluoform_update',compact('api_preference_for_transfer_dept_id','api_preference','uo_data','third_post','uo_filled_data','empname','authorityName1','authorityName2','preference_update_filled','first_preference_grade','second_preference_grade','third_preference_grade','first_preference_dept','second_preference_dept','third_preference_dept','deptListArray','first_preference','second_preference','third_preference','dsgSerialNumbers','deptnameOther','deptname','relationship','data', 'Sign1', 'Sign2'));



            
       
    }

        public function fillUOUpdate($id, Request $request)
        {
    
         //dd($request->all());
            $user_id = Auth::user()->id;
            $getUser = User::find($user_id);
        
            $id = Crypt::decryptString($id);
        //dd($id);
            if (session()->has('ein')) {
                $session_ein = session()->get('ein');
                if ($session_ein != $id) {
                    session()->forget('ein');
                }
            }
        
            session()->put('ein', $id);
            $ein = session()->get('ein');
        
            $data = ProformaModel::where('ein', $ein)->first();
            $relationshipId = $data->relationship;
            $relationship_name = RelationshipModel::where('id',$relationshipId)->first();
            $emp = UOGenerationModel::where("ein", $data->ein)->get()->toArray();
            $empUpdate = UOGenerationModel::where("ein", $data->ein)->get()->first();
            // dd( $emp);
            //dd( $relationship_name->relationship, $data->efile_dp, $data->efile_ad,$request->preferenceSelect,$request->grade1, $request->deptname1 );
    
 //HERE VACANCY IS TO BE DEDUCTED FROM THE TOTAL DIH VACANCY.....
        //ANAND 30-06-2025

            if (count($emp) != null ) {
                if($request->preferenceSelect != null){
                $empUpdate->update([
                    'ein' =>  $data->ein,
                    'deceased_dept_id' => $data->dept_id,
                    'appl_number' => $data->appl_number,
                    'file_put_up_by' => $getUser->name,
                    'dp_file_number' => $data->efile_dp,
                    'ad_file_number' => $data->efile_ad,
                    'relationship' => $relationship_name->relationship,
                     'post' => $request->preferenceSelect,
                     'grade'=> $request->hiddenGrade,
                     'department'=>$request->hiddenDept,
                        
                    'signing_authority_1' => $request->signing_authority_1,
                    'signing_authority_2' => $request->signing_authority_2
                  
                 ]);
                }  
    
                else{
                    $empUpdate->update([
                        'ein' =>  $data->ein,
                        'deceased_dept_id' => $data->dept_id,
                        'appl_number' => $data->appl_number,
                        'file_put_up_by' => $getUser->name,
                        'dp_file_number' => $data->efile_dp,
                        'ad_file_number' => $data->efile_ad,
                        'relationship' => $relationship_name->relationship,
                        'post' => $request->third_post_id,
                        'grade'=> $request->third_grade_id,
                        'department'=>$request->dept_id_option,
                       
                         'signing_authority_1' => $request->signing_authority_1,
                         'signing_authority_2' => $request->signing_authority_2
                   
                    ]);
                }
                  
            
                $dateToday = new DateTime(date("m/d/Y"));
                $empDetails = ProformaModel::where("ein", $ein)->first();
        
                if ($empDetails != null) {
                    $empDetails->update([
                        'status' => 5, // Appointment ready
                        'forwarded_on' =>  $dateToday,
    
                    ]);
                }
               // return redirect()->route('selectDeptByDPApprove')->with('success', 'Data Updated Successfully..');
               return response()->json(['message' => 'Data Updated Successfully..........']);
            }
        
            
        }

    public function fill_uo_selected(Request $request)
    {
        $selectedEinIds = explode(',', $request->input('selectedEinIds', ''));
        //  dd( $selectedEinIds);
        $records = ProformaModel::whereIn('ein', $selectedEinIds)->get();

        // Extract distinct dept_id values
        // $same_ad_efile_id = $records->pluck('ad_efile_id_multi')->unique()->toArray();


        //  $same_dp_efile_id = $records->pluck('dp_efile_id_multi')->unique()->toArray();

        $same_dept_id = $records->pluck('dept_id')->unique()->toArray();
        $same_transfer_dept_id = $records->pluck('transfer_dept_id')->unique()->toArray();
        $api_preference = array();
        // $notfound = "";

        $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
            'dept_code' => $same_dept_id[0],
            'token' => "b000e921eeb20a0d395e341dfcd6117a",
        ]);
        $api_preference = json_decode($response->getBody(), true);


        $api_preference_for_transfer_dept_id = array();
        // $notfound = "";

        $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
            'dept_code' => $same_transfer_dept_id[0],
            'token' => "b000e921eeb20a0d395e341dfcd6117a",
        ]);
        $api_preference_for_transfer_dept_id = json_decode($response->getBody(), true);


        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        $Sign1 = Sign1Model::get()->toArray();
        $Sign2 = Sign2Model::get()->toArray();
        // $designationData = DesignationModel::get();
        return view('admin/Form/filluo_checked', compact('api_preference_for_transfer_dept_id', 'api_preference', 'deptListArray', 'records', 'Sign1', 'Sign2', 'selectedEinIds'));
    }
    

    public function fill_uo_save_checked($id, Request $request)
    {
        //  dd($request->all());

         //HERE VACANCY IS TO BE DEDUCTED FROM THE TOTAL DIH VACANCY.....
        //ANAND 30-06-2025

        
        $user_id = Auth::user()->id;
        $getUser = User::find($user_id);

        $id = Crypt::decryptString($id);

        if (session()->has('ein')) {
            $session_ein = session()->get('ein');
            if ($session_ein != $id) {
                session()->forget('ein');
            }
        }

        session()->put('ein', $id);
        $ein = session()->get('ein');
        //dd($request->applicantSelect);

        $data = ProformaModel::where('ein', $request->applicantSelect)->first();

        $relationshipId = $data->relationship;
        $relationship_name = RelationshipModel::where('id', $relationshipId)->first();

        $emp = UOGenerationModel::where("ein", $data->ein)->get()->toArray();

        if (count($emp) == 0) {

            if ($request->post != null) {


                $uoGeneration = UOGenerationModel::create([
                    'ein' =>  $request->applicantSelect,
                    'deceased_dept_id' => $data->dept_id,
                    'appl_number' => $data->appl_number,
                    'file_put_up_by' => $getUser->name,
                    'dp_file_number' => $data->efile_dp,
                    'ad_file_number' => $data->efile_ad,
                    'relationship' => $relationship_name->relationship,
                    'post' => $request->post,
                    'grade' => $request->gradeDisplay,
                    'department' => $request->departmentDisplay,
                    'signing_authority_1' => $request->signing_authority_1,
                    'signing_authority_2' => $request->signing_authority_2

                ]);
            } else {
                $uoGeneration = UOGenerationModel::create([
                    'ein' =>  $request->applicantSelect,
                    'deceased_dept_id' => $data->dept_id,
                    'appl_number' => $data->appl_number,
                    'file_put_up_by' => $getUser->name,
                    'dp_file_number' => $data->efile_dp,
                    'ad_file_number' => $data->efile_ad,
                    'relationship' => $relationship_name->relationship,

                    'post' => $request->third_post_id,
                    'grade' => $request->third_grade_id,
                    'department' => $request->dept_id_option,

                    'signing_authority_1' => $request->signing_authority_1,
                    'signing_authority_2' => $request->signing_authority_2

                ]);
            }

            $dateToday = new DateTime(date("m/d/Y"));
            $empDetails = ProformaModel::where("ein", $request->applicantSelect)->first();

            if ($empDetails != null) {
                $empDetails->update([
                    'status' => 5, // Appointment ready
                    'forwarded_on' =>  $dateToday
                ]);
            }
        } else {

            $empUpdate = UOGenerationModel::where("ein", $data->ein)->get()->first();
            if ($request->post != null) {
                $empUpdate->update([
                    'ein' =>  $request->applicantSelect,
                    'deceased_dept_id' => $data->dept_id,
                    'appl_number' => $data->appl_number,
                    'file_put_up_by' => $getUser->name,
                    'dp_file_number' => $data->efile_dp,
                    'ad_file_number' => $data->efile_ad,
                    'relationship' => $relationship_name->relationship,
                    'post' => $request->post,
                    'grade' => $request->gradeDisplay,
                    'department' => $request->departmentDisplay,
                    'signing_authority_1' => $request->signing_authority_1,
                    'signing_authority_2' => $request->signing_authority_2

                ]);
            } else {


                $empUpdate->update([
                    'ein' =>  $request->applicantSelect,
                    'deceased_dept_id' => $data->dept_id,
                    'appl_number' => $data->appl_number,
                    'file_put_up_by' => $getUser->name,
                    'dp_file_number' => $data->efile_dp,
                    'ad_file_number' => $data->efile_ad,
                    'relationship' => $relationship_name->relationship,

                    'post' => $request->third_post_id,
                    'grade' => $request->third_grade_id,
                    'department' => $request->dept_id_option,

                    'signing_authority_1' => $request->signing_authority_1,
                    'signing_authority_2' => $request->signing_authority_2

                ]);
            }

            $dateToday = new DateTime(date("m/d/Y"));
            $empDetails = ProformaModel::where("ein", $request->applicantSelect)->first();

            if ($empDetails != null) {
                $empDetails->update([
                    'status' => 5, // Appointment ready
                    'forwarded_on' =>  $dateToday
                ]);
            }
        }

        return response()->json(['message' => 'Save Successfully']);
    }


}