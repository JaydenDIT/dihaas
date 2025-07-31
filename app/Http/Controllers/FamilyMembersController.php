<?php

namespace App\Http\Controllers;
//use App\Http\Controllers\helpers;
use App\Models\FamilyMembers;
use App\Models\EmpFormSubmissionStatus;
use App\Models\GenderModel;
use App\Models\PensionEmployee;
use App\Models\ProformaModel;
use App\Models\RelationshipModel;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Library\Senitizer;


class FamilyMembersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if(isset($_REQUEST)){
            $_REQUEST = Senitizer::senitize($_REQUEST);
       }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
       
       
        //$ein = null;
        //$empDetails=null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');
            
          
            $emp_form_stat= ProformaModel::get()->where("ein", $ein)->first();
            $empDetails = $emp_form_stat;

            $emp_fami_stat= ProformaModel::get()->where("ein", $ein)->first();

            if ($emp_form_stat->status == 1 || $emp_form_stat->status == 2) {
                $status = 1;
                // return redirect()->back()->with('message', "Already proceeded! Click Form menu to view forms!");
            } else {
                $status = 0;
                // return redirect()->back()->with('error_message', "No Data Found!");
            }

            if ($emp_fami_stat->family_details_status == 1) {
                $FamilyStatus = 1;
                // return redirect()->back()->with('message', "Already proceeded! Click Form menu to view forms!");
            } else {
                $FamilyStatus = 0;
                // return redirect()->back()->with('error_message', "No Data Found!");
            }

            // check personal details submit or not
            $emp_form_stat = EmpFormSubmissionStatus::get()->where("ein", $ein)->where('form_id', 1)->toArray();

            if (count($emp_form_stat) == 0) {
                return redirect()->route('viewPersonalDetailsFrom', Crypt::encryptString($ein))->with('errormessage', "Please Submit Personel Details & Deceased Information!");
            }
            // end....................
            // view family Details form

            $emp_family_details = FamilyMembers::orderBy('id', 'ASC')->where("ein", $ein)->get();

            if (count($emp_family_details) == 0) {
                $emp_family_details = null;
                $emp_family_details_count = null;
            } else {
                $emp_family_details_count = count($emp_family_details);
            }

            $empForm_status = EmpFormSubmissionStatus::orderBy('form_id')->get()->where("ein", $ein);
            $formStatArray = [];
            $x = 1;
            foreach ($empForm_status as $rowData) {
                if ($rowData->form_id ==  $x) {

                    $formStatArray[] = ["form-" . $x => $rowData->submit_status];
                }
                $x = $x + 1;
            }

                $FamilyStatus = 1;
                $FamilyStatus = 1;
                //session()->put('ein', $ein);
                return view('admin/Form/form_family_details', compact("formStatArray", "empDetails", "emp_family_details", "emp_family_details_count", "ein", "status","FamilyStatus"));
        } else {
            return redirect()->route('viewStartEmp');
        }
    }

    
    public function indexBacklog()
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $ein = null;
        $empDetails=null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');
           // $ein = Crypt::decryptString($ein);
            $empDetails= ProformaModel::get()->where("ein", $ein)->first(); //->first says it's point to and object so in blade we write as $empDetails->ein
            //->toArray then it point to array $empDetails['ein'];
//Above code is to get the ein pass after saving the first form of proforma

        } else {
            //return 2;   
            return redirect()->back()->with('errormessage', 'EIN not found...');
        }

//dd($empDetails->toArray());
        return view('admin/Form/form_family_details_backlog', compact('empDetails'));
    }

    public function index1()
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $ein = null;
        $empDetails=null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');
            $empDetails= ProformaModel::get()->where("ein", $ein)->first(); //->first says it's point to and object so in blade we write as $empDetails->ein
           
        } else {
            //return 2;   
            return redirect()->back()->with('errormessage', 'EIN not found...');
        }

        return view('admin/Form/form_family_details_dataentry', compact('empDetails'));
    }



    public function indexupdate()
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
       
        //$ein = null;
        //$empDetails=null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');
            
         

            $emp_form_stat= ProformaModel::get()->where("ein", $ein)->first();
            $empDetails = $emp_form_stat;
//dd($empDetails);
            $emp_fami_stat= ProformaModel::get()->where("ein", $ein)->first();

           // dd($emp_form_stat);
            
            if ($emp_form_stat->form_status == 1) {
                $status = 1;
                // return redirect()->back()->with('message', "Already proceeded! Click Form menu to view forms!");
            } else {
                $status = 0;
                // return redirect()->back()->with('error_message', "No Data Found!");
            }

            if ($emp_fami_stat->family_details_status == 1) {
                $FamilyStatus = 1;
                // return redirect()->back()->with('message', "Already proceeded! Click Form menu to view forms!");
            } else {
                $FamilyStatus = 0;
                // return redirect()->back()->with('error_message', "No Data Found!");
            }

            // check personal details submit or not ->where('form_id', 1)->where("ein", $ein)
            $emp_form_stat = EmpFormSubmissionStatus::get()->where('form_id', 1)->where("ein", $ein)->toArray();
           
            //dd($ein,  $emp_form_stat);

            if (count($emp_form_stat) == 0) {
                return redirect()->route('Proforma_ApplicantDetails', Crypt::encryptString($ein))->with('errormessage', "Please Submit Personel Details & Deceased Information!");
            }
            // end....................
            // view family Details form

            $emp_family_details = FamilyMembers::orderBy('id', 'ASC')->where("ein", $ein)->get();

            if (count($emp_family_details) == 0) {
                $emp_family_details = null;
                $emp_family_details_count = null;
            } else {
                $emp_family_details_count = count($emp_family_details);
            }

            $empForm_status = EmpFormSubmissionStatus::orderBy('form_id')->get()->where("ein", $ein);
            $formStatArray = [];
            $x = 1;
            foreach ($empForm_status as $rowData) {
                if ($rowData->form_id ==  $x) {

                    $formStatArray[] = ["form-" . $x => $rowData->submit_status];
                }
                $x = $x + 1;
            }

                $FamilyStatus = 1;
                $FamilyStatus = 1;
                //session()->put('ein', $ein);
                return view('admin/Form/form_family_details_update', compact("formStatArray", "empDetails", "emp_family_details", "emp_family_details_count", "ein", "status","FamilyStatus"));
        } else {
            return redirect()->route('viewStartEmp');
        }
    }

    public function applicantupdate()
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
       
        //$ein = null;
        //$empDetails=null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');
            
        // dd($ein);

            $emp_form_stat= ProformaModel::get()->where("ein", $ein)->first();
            $empDetails = $emp_form_stat;

            $emp_fami_stat= ProformaModel::get()->where("ein", $ein)->first();

            if ($emp_form_stat->form_status == 1) {
                $status = 1;
                // return redirect()->back()->with('message', "Already proceeded! Click Form menu to view forms!");
            } else {
                $status = 0;
                // return redirect()->back()->with('error_message', "No Data Found!");
            }

            if ($emp_fami_stat->family_details_status == 1) {
                $FamilyStatus = 1;
                // return redirect()->back()->with('message', "Already proceeded! Click Form menu to view forms!");
            } else {
                $FamilyStatus = 0;
                // return redirect()->back()->with('error_message', "No Data Found!");
            }

            // check personal details submit or not
            $emp_form_stat = EmpFormSubmissionStatus::get()->where("ein", $ein)->where('form_id', 1)->toArray();

            if (count($emp_form_stat) == 0) {
                return redirect()->route('Proforma_ApplicantDetails', Crypt::encryptString($ein))->with('errormessage', "Please Submit Personel Details & Deceased Information!");
            }
            // end....................
            // view family Details form

            $emp_family_details = FamilyMembers::orderBy('id', 'ASC')->where("ein", $ein)->get();

            if (count($emp_family_details) == 0) {
                $emp_family_details = null;
                $emp_family_details_count = null;
            } else {
                $emp_family_details_count = count($emp_family_details);
            }

            $empForm_status = EmpFormSubmissionStatus::orderBy('form_id')->get()->where("ein", $ein);
            $formStatArray = [];
            $x = 1;
            foreach ($empForm_status as $rowData) {
                if ($rowData->form_id ==  $x) {

                    $formStatArray[] = ["form-" . $x => $rowData->submit_status];
                }
                $x = $x + 1;
            }

                $FamilyStatus = 1;
                $FamilyStatus = 1;
                //session()->put('ein', $ein);
                return view('admin/Form/form_family_applicant_update', compact("formStatArray", "empDetails", "emp_family_details", "emp_family_details_count", "ein", "status","FamilyStatus"));
        } else {
            return redirect()->route('viewStatusApplicant');
        }
    }

    public function index2ndAppl()
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
       
        //$ein = null;
        //$empDetails=null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');
            
         

            $emp_form_stat= ProformaModel::get()->where("ein", $ein)->first();
            $empDetails = $emp_form_stat;

            $emp_fami_stat= ProformaModel::get()->where("ein", $ein)->first();

            if ($emp_form_stat->form_status == 1 || $emp_form_stat->form_status == null) {
                $status = 1;
                // return redirect()->back()->with('message', "Already proceeded! Click Form menu to view forms!");
            } else {
                $status = 0;
                // return redirect()->back()->with('error_message', "No Data Found!");
            }

            if ($emp_fami_stat->family_details_status == 1) {
                $FamilyStatus = 1;
                // return redirect()->back()->with('message', "Already proceeded! Click Form menu to view forms!");
            } else {
                $FamilyStatus = 0;
                // return redirect()->back()->with('error_message', "No Data Found!");
            }

            // check personal details submit or not
            $emp_form_stat = EmpFormSubmissionStatus::get()->where("ein", $ein)->where('form_id', 1)->toArray();

            if (count($emp_form_stat) == 0) {
                return redirect()->route('update_second_applicant_data', Crypt::encryptString($ein))->with('errormessage', "Please Submit Personel Details & Deceased Information!");
            }
            // end....................
            // view family Details form


            $emp_family_details = FamilyMembers::orderBy('id', 'ASC')->where("ein", $ein)->get();

            if (count($emp_family_details) == 0) {
                $emp_family_details = null;
                $emp_family_details_count = null;
            } else {
                $emp_family_details_count = count($emp_family_details);
            }

            $empForm_status = EmpFormSubmissionStatus::orderBy('form_id')->get()->where("ein", $ein);
            $formStatArray = [];
            $x = 1;
            foreach ($empForm_status as $rowData) {
                if ($rowData->form_id ==  $x) {

                    $formStatArray[] = ["form-" . $x => $rowData->submit_status];
                }
                $x = $x + 1;
            }

                $FamilyStatus = 1;
                $FamilyStatus = 1;
                //session()->put('ein', $ein);
                return view('admin/Form/form_family_details2ndAppl', compact("formStatArray", "empDetails", "emp_family_details", "emp_family_details_count", "ein", "status","FamilyStatus"));
        } else {
            return redirect()->route('home');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // // 
        // $id = 'not set';
        // // return redirect()->route('upload-emp-files', ['formid' => $id])->with('message', 'Family details succesfully saved!');
        // return redirect()->route(' upload-applicant-files', ['formid' => $id])->with('message', 'Family details succesfully saved!');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          // return 1;
        // dd($request->toArray());
        $ein = null;
        $empDetails=null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');
           // $ein = Crypt::decryptString($ein);
            $empDetails= ProformaModel::get()->where("ein", $ein)->first();
            $Gender= GenderModel::get()->where("id", $empDetails->sex)->first();

            $applicant_name= $empDetails->applicant_name;
            $applicant_dob= $empDetails->applicant_dob;
            $relationship= $empDetails->relationship;
            $sex= $Gender->sex;     
                

        //$ein = $request->ein;
        $clientIP = $request->ip();
   
        if ($empDetails || $Gender) {
            $applicant_name = $empDetails->applicant_name;
            $applicant_dob = $empDetails->applicant_dob;
            $relationship = $empDetails->relationship;
            $sex = ($Gender->sex == 'Female') ? 'F' : 'M';
            //$sex = ($Gender->sex == 'Female') ? 'F' : (($Gender->sex == 'Male') ? 'M' : 'T');
            $clientIP = $request->ip();
        
            // Check if a matching record already exists in family_members table
            $existingFamilyMember = FamilyMembers::where([
                'ein' => $ein,
                'name' => $applicant_name,
                'dob' => $applicant_dob,
                'gender' => $sex,
                'relation' => $relationship,
            ])->first();
        
            if (!$existingFamilyMember) {
                $familyMember = new FamilyMembers();
                $familyMember->ein = $ein;
                $familyMember->name = $applicant_name;
                $familyMember->dob = $applicant_dob;
                $familyMember->gender = $sex;
                $familyMember->relation = $relationship;
                $familyMember->client_ip = $clientIP;
        
                $familyMember->save();
            }
        
            // Rest of your code...
        }

        $emp_form_stat = EmpFormSubmissionStatus::get()->where("ein", $ein)->toArray();
        if (array_key_exists("old_data", $request->toArray())) {
            // get old data to be update
            if (array_key_exists("old_data", $request->toArray())) {
                foreach ($request->old_data as $key => $value) {
                    if (in_array(null, $value)) {
                        $formSubStat = 0;
                    } else {
                        $formSubStat = 1;
                    }

                

                    $get_row = FamilyMembers::find($value['id']);
                    if ($get_row) {
                        $get_row->update([
                            'ein' => $ein,
                            'name' => $value['name'],
                            'dob' => $value['dob'],
                            'gender' => $value['gender'],
                            'relation' => $value['relation'],
                            
                            'client_ip' => $clientIP,
                        ]);
                    }
                }
                $emp = ProformaModel::get()->where("ein", $ein)->first(); // update for family_details_status in proforma table
                if ($emp) {
                    $emp->update([
                        'family_details_status' => '1',
                        'client_ip' => $clientIP,
                    ]);
                }
            }
        }
        // get new data to be insert
        if (array_key_exists("new_data", $request->toArray())) {
            foreach ($request->new_data as $key => $value) {
                if (in_array(null, $value)) {
                    $formSubStat = 0;
                } else {
                    $formSubStat = 1;
                }
                $value['ein'] = $ein;
                $value['client_ip'] = $clientIP;
                FamilyMembers::create($value);
            }

            $emp = ProformaModel::get()->where("ein", $ein)->first(); // update for family_details_status in proforma table
            if ($emp) {
                $emp->update([
                    'family_details_status' => '1',
                    'client_ip' => $clientIP,
                ]);
            }
           
        }
        // insert to form submission status 
        $formId = 2; // here we set familly details form id as 2 according to ui;
        $getFormSubmisiondetails = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->toArray();
        if (count($getFormSubmisiondetails) == 0) {
            EmpFormSubmissionStatus::create([
                'ein' => $ein,
                'form_id' => $formId,
                'submit_status' => 1 // status 1 = submitted
            ]);
        } else {
            // $emp_form_stat_row = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->first();
            // $row = EmpFormSubmissionStatus::find($emp_form_stat_row->id);
            // $row->submit_status = 1;
            // $row->save();
            return back()->with('errormessage', "Already Submitted..........!");
        }
        }
       // Session::put('ein', $request->ein);
       return redirect()->with('message', 'Family details succesfully saved!');
       // return redirect()->route('create-applicant-files')->with('message', 'Family details succesfully saved!');
}


   /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store1(Request $request)
    {
          // return 1;
        // dd($request->toArray());
        $ein = null;
        $empDetails=null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');
           // $ein = Crypt::decryptString($ein);
            $empDetails= ProformaModel::get()->where("ein", $ein)->first();
            $Gender= GenderModel::get()->where("id", $empDetails->sex)->first();
            $Relationship= RelationshipModel::get()->where("id", $empDetails->relationship)->first();

            $applicant_name= $empDetails->applicant_name;
            $applicant_dob= $empDetails->applicant_dob;
            $relationship= $Relationship->relation;
            $sex= $Gender->sex;
          
               
        //$ein = $request->ein;
        $clientIP = $request->ip();
   
        if ($empDetails || $Gender) {
            $applicant_name = $empDetails->applicant_name;
            $applicant_dob = $empDetails->applicant_dob;
            $relationship = $empDetails->relationship;
            $sex = ($Gender->sex == 'Female') ? 'F' : 'M';
            //$sex = ($Gender->sex == 'Female') ? 'F' : (($Gender->sex == 'Male') ? 'M' : 'T');
            $clientIP = $request->ip();
        
            // Check if a matching record already exists in family_members table
            $existingFamilyMember = FamilyMembers::where([
                'ein' => $ein,
                'name' => $applicant_name,
                'dob' => $applicant_dob,
                'gender' => $sex,
                'relation' => $relationship,
            ])->first();
        
            if (!$existingFamilyMember) {
                $familyMember = new FamilyMembers();
                $familyMember->ein = $ein;
                $familyMember->name = $applicant_name;
                $familyMember->dob = $applicant_dob;
                $familyMember->gender = $sex;
                $familyMember->relation = $relationship;
                $familyMember->client_ip = $clientIP;
        
                $familyMember->save();
            }
        
            // Rest of your code...
        }

        $emp_form_stat = EmpFormSubmissionStatus::get()->where("ein", $ein)->toArray();
        if (array_key_exists("old_data", $request->toArray())) {
            // get old data to be update
            if (array_key_exists("old_data", $request->toArray())) {
                foreach ($request->old_data as $key => $value) {
                    if (in_array(null, $value)) {
                        $formSubStat = 0;
                    } else {
                        $formSubStat = 1;
                    }
                    $get_row = FamilyMembers::find($value['id']);
                    if ($get_row) {
                        $get_row->update([
                            'ein' => $ein,
                            'name' => $value['name'],
                            'dob' => $value['dob'],
                            'gender' => $value['gender'],
                            'relation' => $value['relation'],
                            
                            'client_ip' => $clientIP,
                        ]);
                    }
                }
                $emp = ProformaModel::get()->where("ein", $ein)->first(); // update for family_details_status in proforma table
                if ($emp) {
                    $emp->update([
                        'family_details_status' => '1',
                        'client_ip' => $clientIP,
                    ]);
                }
            }
        }
        // get new data to be insert
        if (array_key_exists("new_data", $request->toArray())) {
            foreach ($request->new_data as $key => $value) {
                if (in_array(null, $value)) {
                    $formSubStat = 0;
                } else {
                    $formSubStat = 1;
                }
                $value['ein'] = $ein;
                $value['client_ip'] = $clientIP;
                FamilyMembers::create($value);
            }

            $emp = ProformaModel::get()->where("ein", $ein)->first(); // update for family_details_status in proforma table
            if ($emp) {
                $emp->update([
                    'family_details_status' => '1',
                    'client_ip' => $clientIP,
                ]);
            }
        }
        // insert to form submission status 
        $formId = 2; // here we set familly details form id as 2 according to ui;
        $getFormSubmisiondetails = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->toArray();
        if (count($getFormSubmisiondetails) == 0) {
            EmpFormSubmissionStatus::create([
                'ein' => $ein,
                'form_id' => $formId,
                'submit_status' => 1 // status 1 = submitted
            ]);
        } else {
            // $emp_form_stat_row = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->first();
            // $row = EmpFormSubmissionStatus::find($emp_form_stat_row->id);
            // $row->submit_status = 1;
            // $row->save();
            return back()->with('errormessage', "Already Submitted..........!");
        }
        }
       // return redirect()->route('create-backlog-files')->with('message', 'Backlog Family details succesfully saved!');
        return back()->with('message', 'Backlog Family details succesfully saved!');
    
    }

    
   /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeLeft(Request $request)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
       //return 1;
        // dd($request->toArray());
        $ein = null;
        $empDetails=null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');
          
          //  $ein = Crypt::decryptString($ein);
            $empDetails= ProformaModel::get()->where("ein", $ein)->first();

          //  dd($ein);
        $clientIP = $request->ip();
       // dd($clientIP);
        $emp_form_stat = EmpFormSubmissionStatus::get()->where("ein", $ein)->toArray();
        if (array_key_exists("old_data", $request->toArray())) {
            // get old data to be update
            if (array_key_exists("old_data", $request->toArray())) {
                foreach ($request->old_data as $key => $value) {
                    if (in_array(null, $value)) {
                        $formSubStat = 0;
                    } else {
                        $formSubStat = 1;
                    }
                    $get_row = FamilyMembers::find($value['id']);
                    if ($get_row) {
                        $get_row->update([
                            'ein' => $ein,
                            'name' => $value['name'],
                            'dob' => $value['dob'],
                            'gender' => $value['gender'],
                            'relation' => $value['relation'],
                           // 'remarks' => $value['remarks'],
                            'client_ip' => $clientIP,
                        ]);
                    }
                }

                $emp = ProformaModel::get()->where("ein", $ein)->first(); // update for family_details_status in proforma table
                if ($emp) {
                    $emp->update([
                        'family_details_status' => '1',
                        'client_ip' => $clientIP,
                    ]);
                }
               // dd($ein, $emp);
            }
        }
        // get new data to be insert
        if (array_key_exists("new_data", $request->toArray())) {
            foreach ($request->new_data as $key => $value) {
                if (in_array(null, $value)) {
                    $formSubStat = 0;
                } else {
                    $formSubStat = 1;
                }
                $value['ein'] = $ein;
                $value['client_ip'] = $clientIP;
                FamilyMembers::create($value);
            }
            $emp = ProformaModel::get()->where("ein", $ein)->first(); // update for family_details_status in proforma table
            if ($emp) {
                $emp->update([
                    'family_details_status' => '1',
                    'client_ip' => $clientIP,
                ]);
            }
            //dd($ein, $emp);
        }
        // insert to form submission status 
        $formId = 2; // here we set familly details form id as 2 according to ui;
        $getFormSubmisiondetails = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->toArray();
        if (count($getFormSubmisiondetails) == 0) {
            EmpFormSubmissionStatus::create([
                'ein' => $ein,
                'form_id' => $formId,
                'submit_status' => $formSubStat // status 1 = submitted
            ]);
        } else {
            $emp_form_stat_row = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->first();
            $row = EmpFormSubmissionStatus::find($emp_form_stat_row->id);
            $row->submit_status = $formSubStat;
            $row->save();
            return response()->json(['message' =>"Successfully Added....."]);

            //return redirect()->route('create-applicant-files-dihas')->with('message', 'Updated Family details succesfully saved!');
            //return redirect()->back()->with('errormessage', "Already Submitted..........!");
            //return $ein;
        }
        }
       
       // Session::put('ein', $request->ein);
       // return redirect()->route('create-applicant-files-dihas')->with('message', 'Updated Family details succesfully saved!');
       return response()->json(['message' =>"Family details succesfully saved!"]);
    
    }

/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store2ndAppl(Request $request)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
       //return 1;
        // dd($request->toArray());
        $ein = null;
        $empDetails=null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');
          
          //  $ein = Crypt::decryptString($ein);
            $empDetails= ProformaModel::get()->where("ein", $ein)->first();

        //$ein = $request->ein;
        $clientIP = $request->ip();
        $emp_form_stat = EmpFormSubmissionStatus::get()->where("ein", $ein)->toArray();
        if (array_key_exists("old_data", $request->toArray())) {
            // get old data to be update
            if (array_key_exists("old_data", $request->toArray())) {
                foreach ($request->old_data as $key => $value) {
                    if (in_array(null, $value)) {
                        $formSubStat = 0;
                    } else {
                        $formSubStat = 1;
                    }
                    $get_row = FamilyMembers::find($value['id']);
                    if ($get_row) {
                        $get_row->update([
                            'ein' => $ein,
                            'name' => $value['name'],
                            'dob' => $value['dob'],
                            'gender' => $value['gender'],
                            'relation' => $value['relation'],
                           // 'remarks' => $value['remarks'],
                            'client_ip' => $clientIP,
                        ]);
                    }
                }
                $emp = ProformaModel::get()->where("ein", $ein)->first(); // update for family_details_status in proforma table
                if ($emp) {
                    $emp->update([
                        'family_details_status' => '1',
                        'client_ip' => $clientIP,
                    ]);
                }
            }
        }
        // get new data to be insert
        if (array_key_exists("new_data", $request->toArray())) {
            foreach ($request->new_data as $key => $value) {
                if (in_array(null, $value)) {
                    $formSubStat = 0;
                } else {
                    $formSubStat = 1;
                }
                $value['ein'] = $ein;
                $value['client_ip'] = $clientIP;
                FamilyMembers::create($value);
            }
            $emp = ProformaModel::get()->where("ein", $ein)->first(); // update for family_details_status in proforma table
            if ($emp) {
                $emp->update([
                    'family_details_status' => '1',
                    'client_ip' => $clientIP,
                ]);
            }

        }
        // insert to form submission status 
        $formId = 2; // here we set familly details form id as 2 according to ui;
        $getFormSubmisiondetails = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->toArray();
        if (count($getFormSubmisiondetails) == 0) {
            EmpFormSubmissionStatus::create([
                'ein' => $ein,
                'form_id' => $formId,
                'submit_status' => $formSubStat // status 1 = submitted
            ]);
        } else {
            $emp_form_stat_row = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->first();
            $row = EmpFormSubmissionStatus::find($emp_form_stat_row->id);
            $row->submit_status = $formSubStat;
            $row->save();
            //return redirect()->route('create-applicant-files2ndAppl')->with('message', 'Updated Family details succesfully saved!');
            return back()->with('message', "Updated Family details succesfully!.....");
            //return $ein;
        }
        }
       
       // Session::put('ein', $request->ein);
       return back()->with('message', "Updated Family details succesfully!.....");
        //return redirect()->route('create-applicant-files2ndAppl')->with('message', 'Updated Family details succesfully saved!');
    
    }

/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function applicantstoreLeft(Request $request)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
       //return 1;
        // dd($request->toArray());
        $ein = null;
        $empDetails=null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');
          
          //  $ein = Crypt::decryptString($ein);
            $empDetails= ProformaModel::get()->where("ein", $ein)->first();

        //$ein = $request->ein;
        $clientIP = $request->ip();
        $emp_form_stat = EmpFormSubmissionStatus::get()->where("ein", $ein)->toArray();
        if (array_key_exists("old_data", $request->toArray())) {
            // get old data to be update
            if (array_key_exists("old_data", $request->toArray())) {
                foreach ($request->old_data as $key => $value) {
                    if (in_array(null, $value)) {
                        $formSubStat = 0;
                    } else {
                        $formSubStat = 1;
                    }
                    $get_row = FamilyMembers::find($value['id']);
                    if ($get_row) {
                        $get_row->update([
                            'ein' => $ein,
                            'name' => $value['name'],
                            'dob' => $value['dob'],
                            'gender' => $value['gender'],
                            'relation' => $value['relation'],
                           
                            'client_ip' => $clientIP,
                        ]);
                    }
                }
                $emp = ProformaModel::get()->where("ein", $ein)->first(); // update for family_details_status in proforma table
                if ($emp) {
                    $emp->update([
                        'family_details_status' => '1',
                        'client_ip' => $clientIP,
                    ]);
                }
            }
        }
        // get new data to be insert
        if (array_key_exists("new_data", $request->toArray())) {
            foreach ($request->new_data as $key => $value) {
                if (in_array(null, $value)) {
                    $formSubStat = 0;
                } else {
                    $formSubStat = 1;
                }
                $value['ein'] = $ein;
                $value['client_ip'] = $clientIP;
                FamilyMembers::create($value);
            }
            $emp = ProformaModel::get()->where("ein", $ein)->first(); // update for family_details_status in proforma table
            if ($emp) {
                $emp->update([
                    'family_details_status' => '1',
                    'client_ip' => $clientIP,
                ]);
            }

        }
        // insert to form submission status 
        $formId = 2; // here we set familly details form id as 2 according to ui;
        $getFormSubmisiondetails = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->toArray();
        if (count($getFormSubmisiondetails) == 0) {
            EmpFormSubmissionStatus::create([
                'ein' => $ein,
                'form_id' => $formId,
                'submit_status' => $formSubStat // status 1 = submitted
            ]);
        } else {
            $emp_form_stat_row = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->first();
            $row = EmpFormSubmissionStatus::find($emp_form_stat_row->id);
            $row->submit_status = $formSubStat;
            $row->save();
           // return redirect()->route('create-applicant-self-update')->with('message', 'Updated Family details succesfully saved!');
            return back()->with('message', "Updated Family details succesfully!.....");
            //return $ein;
        }
        }
       
       // Session::put('ein', $request->ein);
       return back()->with('message', "Updated Family details succesfully!.....");
       // return redirect()->route('create-applicant-self-update')->with('message', 'Updated Family details succesfully saved!');
    
    }





    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FamilyMembers  $familyMembers
     * @return \Illuminate\Http\Response
     */
    public function show(FamilyMembers $familyMembers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FamilyMembers  $familyMembers
     * @return \Illuminate\Http\Response
     */
    public function edit(FamilyMembers $familyMembers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FamilyMembers  $familyMembers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FamilyMembers $familyMembers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FamilyMembers  $familyMembers
     * @return \Illuminate\Http\Response
     */
    public function destroy(FamilyMembers $familyMembers, $id)
    {
        //
        $id = Crypt::decryptString($id);
        $row = FamilyMembers::find($id);
        $row->delete();
        return redirect()->back();
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FamilyMembers  $familyMembers
     * @return \Illuminate\Http\Response
     */
    public function destroy1(FamilyMembers $familyMembers, $id)
    {
        //
        $id = Crypt::decryptString($id);
        $row = FamilyMembers::find($id);
        $row->delete();
        return redirect()->back();
    }
      /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FamilyMembers  $familyMembers
     * @return \Illuminate\Http\Response
     */
    public function destroy2ndAppl(FamilyMembers $familyMembers, $id)
    {
        //
        $id = Crypt::decryptString($id);
        $row = FamilyMembers::find($id);
        $row->delete();
        return redirect()->back();
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FamilyMembers  $familyMembers
     * @return \Illuminate\Http\Response
     */
    public function destroyUpdate(FamilyMembers $familyMembers, $id)
    {
        //
        $id = Crypt::decryptString($id);
        $row = FamilyMembers::find($id);
        $row->delete();
        return redirect()->back();
    }

    public function applicantdestroyUpdate(FamilyMembers $familyMembers, $id)
    {
        //
        $id = Crypt::decryptString($id);
        $row = FamilyMembers::find($id);
        $row->delete();
        return redirect()->back();
    }
}
