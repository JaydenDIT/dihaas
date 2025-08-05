<?php

namespace App\Http\Controllers;

use App\Models\DepartmentModel;
use App\Models\DesignationModel;
use App\Models\District;
use App\Models\Docs;
use App\Models\EducationModel;
use Illuminate\Http\Request;
use App\Models\EmpFormSubmissionStatus;
use App\Models\PensionEmployee;
use App\Models\EmpForms;
use App\Models\FamilyMembers;
use App\Models\FileModel;
use App\Models\FilesToUploadModel;
use App\Models\GenderModel;
use App\Models\grade;
use App\Models\NumberGenerator;
use App\Models\PortalModel;
use App\Models\ProformaHistoryModel;
use App\Models\ProformaModel;
use App\Models\RelationshipModel;
use App\Models\RemarksApproveModel;
use App\Models\State;
use App\Models\StateModel;
use App\Models\SubDivision;
use App\Models\User;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class OthersFormDetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index()
    {

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $ein = null;
        $empDetails = null;
        if (session()->has('ein')) {
            $ein = session()->get('ein');

            //return $ein;
            $RemarksApprove = RemarksApproveModel::get()->toArray();
            $emp_form_stat = $proforma = ProformaModel::get()->where("ein", $ein)->first();
            $empDetails = $emp_form_stat;


            if (($emp_form_stat->status == 1  || $emp_form_stat->status == 2) && $emp_form_stat->file_status == 1 && $emp_form_stat->uploader_role_id != 77) {
                // dd($emp_form_stat->uploader_role_id);
                $status = 1;
            } elseif (($emp_form_stat->file_status == 2) && ($emp_form_stat->file_status != 0)) {
                $status = 2;
            } elseif (($emp_form_stat->file_status == 3) && ($emp_form_stat->file_status != 0)) {
                $status = 3;
            } elseif (($emp_form_stat->file_status == 4) && ($emp_form_stat->file_status != 0)) {
                $status = 4;
            } elseif (($emp_form_stat->file_status == 5) && ($emp_form_stat->file_status != 0)) {
                $status = 5;
            } elseif (($emp_form_stat->file_status == 6) && ($emp_form_stat->file_status != 0)) {
                $status = 6;
            } elseif (($emp_form_stat->uploader_role_id == 77) && ($emp_form_stat->file_status != 0)) {
                $status = 7;
            } else {
                $status = 0;
            }

            // dd($status);
            // check personal details submit or not
            $emp_form_stat = EmpFormSubmissionStatus::get()->where("ein", $ein)->where('form_id', 1)->toArray();
            if (count($emp_form_stat) == 0) {
                return redirect()->route('viewPersonalDetailsFrom', Crypt::encryptString($ein))->with('errormessage', "Please Submit Personel Details & Descriptive Role First!");
            }
            // end....................
            $empForm_status = EmpFormSubmissionStatus::orderBy('form_id')->get()->where("ein", $ein);
            $formStatArray = [];
            $x = 1;
            foreach ($empForm_status as $rowData) {
                if ($rowData->form_id ==  $x) {

                    $formStatArray[] = ["form-" . $x => $rowData->submit_status];
                }
                $x = $x + 1;
            }
            //session()->put('ein', $ein);
            return view('admin/Form/form_other_details', compact('getUser', 'RemarksApprove', 'empDetails', 'formStatArray', 'ein', 'status', 'proforma'));
        } else {
            return redirect()->route('viewStartEmp');
        }
    }


    public function index1()
    {

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $ein = null;
        $empDetails = null;
        if (session()->has('ein')) {
            $ein = session()->get('ein');

            //return $ein;

            $emp_form_stat = ProformaModel::get()->where("ein", $ein)->first();
            $empDetails = $emp_form_stat;

            if (($emp_form_stat->status == 1)  || ($emp_form_stat->status == 2)) {
                $status = 1;
                // return redirect()->back()->with('message', "Already proceeded! Click Form menu to view forms!");
            } else {
                $status = 0;
                // return redirect()->back()->with('error_message', "No Data Found!");
            }
            // $ein = Crypt::decryptString($ein); 

            $empDetails = ProformaModel::get()->where("ein", $ein)->first();
            return view('admin/Form/form_other_details_submit', compact('empDetails', 'status'));
        } else {
            //return 2;   
            return redirect()->back()->with('errormessage', 'EIN not found...');
        }

        return view('admin/Form/form_other_details_submit', compact('empDetails', 'status'));
    }


    public function index2ndAppl()
    {

        $ein = null;
        $empDetails = null;
        if (session()->has('ein')) {
            $ein = session()->get('ein');

            //return $ein;

            $emp_form_stat = ProformaModel::get()->where("ein", $ein)->first();
            $empDetails = $emp_form_stat;

            if (($emp_form_stat->status == 1)  || ($emp_form_stat->status == 2)) {
                $status = 1;
                // return redirect()->back()->with('message', "Already proceeded! Click Form menu to view forms!");
            } else {
                $status = 0;
                // return redirect()->back()->with('error_message', "No Data Found!");
            }

            $empDetails = ProformaModel::get()->where("ein", $ein)->first();
            return view('admin/Form/form_other_details_submit2ndAppl', compact('empDetails', 'status'));
        } else {
            //return 2;   
            return redirect()->back()->with('errormessage', 'EIN not found...');
        }

        return view('admin/Form/form_other_details_submit2ndAppl', compact('empDetails', 'status'));
    }

    public function indexUpdate()
    {

        $ein = null;
        $empDetails = null;
        if (session()->has('ein')) {
            $ein = session()->get('ein');

            //return $ein;

            $emp_form_stat = ProformaModel::get()->where("ein", $ein)->first();
            $empDetails = $emp_form_stat;

            if (($emp_form_stat->status == 1)  || ($emp_form_stat->status == 2)) {
                $status = 1;
                // return redirect()->back()->with('message', "Already proceeded! Click Form menu to view forms!");
            } else {
                $status = 0;
                // return redirect()->back()->with('error_message', "No Data Found!");
            }

            $empDetails = ProformaModel::get()->where("ein", $ein)->first();
            return view('admin/Form/form_other_details_update', compact('empDetails', 'status'));
        } else {
            //return 2;   
            return redirect()->back()->with('errormessage', 'EIN not found...');
        }

        return view('admin/Form/form_other_details_update', compact('empDetails', 'status'));
    }

    public function index2()
    {

        $ein = null;
        $empDetails = null;
        if (session()->has('ein')) {
            $ein = session()->get('ein');

            //return $ein;

            $emp_form_stat = ProformaModel::get()->where("ein", $ein)->first();
            $empDetails = $emp_form_stat;

            if (($emp_form_stat->status == 1)  || ($emp_form_stat->status == 2)) {
                $status = 1;
                // return redirect()->back()->with('message', "Already proceeded! Click Form menu to view forms!");
            } else {
                $status = 0;
                // return redirect()->back()->with('error_message', "No Data Found!");
            }

            $empDetails = ProformaModel::get()->where("ein", $ein)->first();
            return view('admin/Form/form_other_details_submit_backlog', compact('empDetails', 'status'));
        } else {
            //return 2;   
            return redirect()->back()->with('errormessage', 'EIN not found...');
        }

        return view('admin/Form/form_other_details_submit_backlog', compact('empDetails', 'status'));
    }

    // public function store(Request $request)
    // {


    //     $ein = $request->emp_ein;
    //     // insert to form submission status 
    //     $formId = 9; // here we set familly details form id as 2 according to ui;
    //     $getFormSubmisiondetails = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->toArray();
    //     if (count($getFormSubmisiondetails) == 0) {
    //         EmpFormSubmissionStatus::create([
    //             'ein' => $ein,
    //             'form_id' => $formId,
    //             'submit_status' => 1 // status 1 = submitted
    //         ]);
    //     } else {
    //         $emp_form_stat_row = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->first();
    //         $row = EmpFormSubmissionStatus::find($emp_form_stat_row->id);
    //         $row->submit_status = 1;
    //         $row->save();
    //     }
    //     return redirect()->route('viewEmpFormList', $ein)->with('message', 'Employee govt due details succesfully saved!');
    // }

    //submit forms
    // public function submitForms($ein)
    // {
    //     $ein = Crypt::decryptString($ein);
    //     $data_yes = [];
    //     $data_no = [];
    //     $emp_form_stat_rows = EmpFormSubmissionStatus::orderBy('form_id')->get()->where('ein', $ein)->where('submit_status', 1)->toArray();
    //     $getForms = EmpForms::orderBy('form_id')->get()->where('status', 1)->toArray();
    //     foreach ($getForms as $row) {
    //         $id = $row['form_id'];
    //         $form_name = $row['form_desc'];
    //         if (array_search($row['form_id'], array_column($emp_form_stat_rows, 'form_id')) !== FALSE) {
    //             $isFormExist = "Yes";
    //             array_push($data_yes, ["id" => $id, "exist" => $isFormExist, "form_name" => $form_name]);
    //         } else {
    //             $isFormExist = "No";
    //             array_push($data_no, ["id" => $id, "exist" => $isFormExist, "form_name" => $form_name]);
    //         }
    //     }

    //     if (count($data_no) != 0) {
    //         return redirect()->back()->with('submit_error', $data_no);
    //     } else {
    //         $empDetails = PensionEmployee::get()->where('status', 1)->where("ein", $ein)->first();
    //         $empDetails->form_status = 1;
    //         if ($empDetails->save()) {
    //             return redirect()->route('viewStartEmp')->with('message', "Succesfully Submitted!");
    //         }
    //     }
    // }



    //submit forms for fresh but last update query of form_status=1 is to write code
    public function submitForms1()
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        //$ein = null;
        //$empDetails=null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');

            $formId = 4; // here we set last formid as 4 according to ui;
            $getFormSubmisiondetails = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->toArray();
            if (count($getFormSubmisiondetails) == 0) {
                EmpFormSubmissionStatus::create([
                    'ein' => $ein,
                    'form_id' => $formId,
                    'submit_status' => 1 // status 1 = submitted
                ]);
            } else {
                //   $emp_form_stat_row = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->first();
                //   $row = EmpFormSubmissionStatus::find($emp_form_stat_row->id);
                //   $row->submit_status = 1;
                //   $row->save();
                //   return redirect()->route('other_form_details_submit')->with('message', "Applicant details was Succesfully Submitted!");
                // return 1;
                return redirect()->back()->with('errormessage', "Already Submitted..........!");
            }
            $empDetails = ProformaModel::get()->where("ein", $ein)->first();

            $getUser1 = User::get()->where('id', $empDetails->forwarded_by)->first();

            $getUser2 = User::get()->where('id', $empDetails->forwarded_by)->toArray();
            //dd($getUser1->name);
            if (count($getUser2) == null) {
                $receiver = null; //previous sender
            }
            if (count($getUser2) != null) {
                $receiver = $getUser1->name; //previous sender
            }
            ////////////////////////////////////////application number/////////////////////////////////
            $einCount =  ProformaModel::orderBy('id', 'DESC')->first();

            //GETTING DATA FROM number_generator table
            $fix = NumberGenerator::get()->first();
            //dd($fix);
            $prefixData = $fix->prefix;

            if ($einCount != null) {
                $lastID = $einCount->id;
                $nextNumber = $lastID + 1;

                $applicationNo = $prefixData . str_pad($nextNumber, $fix->suffix, '0', STR_PAD_LEFT);
            } else {
                $lastID = 0;
                $nextNumber = $lastID + 1;
                $applicationNo = $prefixData . str_pad($nextNumber, $fix->suffix, '0', STR_PAD_LEFT);
            }
            //////////////////////////////////////////end here//////////////////////////////////////////////////////

            if ($empDetails->uploader_role_id != 77) {

                if ($empDetails->remark == null) {
                    //$getUser1 = User::get()->where('id', $empDetails->forwarded_by)->first();

                    $empDetails->update([
                        'received_by' => $receiver, //previous sender
                        'forwarded_by' => $getUser->id, //current sender
                        'appl_number' => $applicationNo, //appl_number here
                        // 'appl_date'=> date('Y-m-d'), //if user is 77 citizen
                        'status' => 2,
                        'rejected_status' => 0,
                        'remark' => null,
                        'remark_details' => null,
                        'file_status' => 1
                    ]);
                }
            } else {
                if ($empDetails->remark != null) {
                    //$getUser1 = User::get()->where('id', $empDetails->forwarded_by)->first();

                    $empDetails->update([
                        'received_by' => $receiver, //previous sender
                        'forwarded_by' => $getUser->id, //current sender
                        'appl_number' => $applicationNo, //appl_number here
                        //'appl_date'=> date('Y-m-d'), //if user is 77 citizen
                        'status' => 2, //hod assistant final submit is submitted and verified
                        'rejected_status' => 0,
                        'remark' => 'Rectified',
                        'remark_details' => null,
                        'file_status' => 1
                    ]);
                }
            }

            if ($empDetails->uploader_role_id == 77) {
                if ($empDetails->remark == null) {
                    $empDetails->update([

                        'received_by' => $receiver, //previous sender
                        'forwarded_by' => $getUser->id, //current sender
                        'status' => 1, //file received at hod assistant
                        'appl_number' => $applicationNo, //appl_number here
                        'appl_date' => date('Y-m-d'), //if user is 77 citizen
                        'rejected_status' => 0,
                        'remark' => null,
                        'remark_details' => null,
                        'file_status' => 1

                    ]);
                }
            } else { // this is for hod-assistant
                if ($empDetails->remark != null) {
                    $empDetails->update([

                        'received_by' => $receiver, //previous sender
                        'forwarded_by' => $getUser->id, //current sender
                        'status' => 1, //file received at hod assistant
                        'appl_number' => $applicationNo, //appl_number here
                        'appl_date' => date('Y-m-d'), //if user is 77 citizen
                        'rejected_status' => 0,
                        'remark' => 'Rectified',
                        'remark_details' => null,
                        'file_status' => 1

                    ]);
                }
            }

            //dd($empDetails);


            return redirect()->route('other_form_details_submit')->with('message', "Applicant details is Succesfully Submitted!");
            session()->forget(['ein', 'ein']);
            //session()->flush();
        }

        //  if (session()->has('ein')) {
        //  $session_ein = session()->get('ein');
        //  if ($session_ein != null) {

        //  }
        // }
    }

    //submit forms for fresh but last update query of form_status=1 is to write code
    public function submitForms2()
    {

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        //$ein = null;
        //$empDetails=null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');

            $formId = 4; // here we set last formid as 4 according to ui;
            $getFormSubmisiondetails = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->toArray();
            if (count($getFormSubmisiondetails) == 0) {
                EmpFormSubmissionStatus::create([
                    'ein' => $ein,
                    'form_id' => $formId,
                    'submit_status' => 1 // status 1 = submitted
                ]);
            } else {
                //   $emp_form_stat_row = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->first();
                //   $row = EmpFormSubmissionStatus::find($emp_form_stat_row->id);
                //   $row->submit_status = 1;
                //   $row->save();
                //   return redirect()->route('other_form_details_submit')->with('message', "Applicant details was Succesfully Submitted!");
                // return 1;
                return redirect()->back()->with('errormessage', "Already Submitted..........!");
            }
            $empDetails = ProformaModel::get()->where("ein", $ein)->first();

            $getUser1 = User::get()->where('id', $empDetails->forwarded_by)->first();

            $getUser2 = User::get()->where('id', $empDetails->forwarded_by)->toArray();
            //dd($getUser1->name);
            if (count($getUser2) == null) {
                $receiver = null; //previous sender
            }
            if (count($getUser2) != null) {
                $receiver = $getUser1->name; //previous sender
            }
            ////////////////////////////////////////application number/////////////////////////////////
            $einCount =  ProformaModel::orderBy('id', 'DESC')->first();

            //GETTING DATA FROM number_generator table
            $fix = NumberGenerator::get()->first();
            //dd($fix);
            $prefixData = $fix->prefix;

            if ($einCount != null) {
                $lastID = $einCount->id;
                $nextNumber = $lastID + 1;

                $applicationNo = $prefixData . str_pad($nextNumber, $fix->suffix, '0', STR_PAD_LEFT);
            } else {
                $lastID = 0;
                $nextNumber = $lastID + 1;
                $applicationNo = $prefixData . str_pad($nextNumber, $fix->suffix, '0', STR_PAD_LEFT);
            }
            //////////////////////////////////////////end here//////////////////////////////////////////////////////

            if ($empDetails->uploader_role_id != 77) {

                if ($empDetails->remark == null) {
                    //$getUser1 = User::get()->where('id', $empDetails->forwarded_by)->first();

                    $empDetails->update([
                        'received_by' => $receiver, //previous sender
                        'forwarded_by' => $getUser->id, //current sender
                        'appl_number' => $applicationNo, //appl_number here
                        // 'appl_date'=> date('Y-m-d'), //if user is 77 citizen
                        'status' => 2,
                        'rejected_status' => 0,
                        'remark' => null,
                        'remark_details' => null,
                        'file_status' => 1
                    ]);
                }
            } else {
                if ($empDetails->remark != null) {
                    //$getUser1 = User::get()->where('id', $empDetails->forwarded_by)->first();

                    $empDetails->update([
                        'received_by' => $receiver, //previous sender
                        'forwarded_by' => $getUser->id, //current sender
                        'appl_number' => $applicationNo, //appl_number here
                        //'appl_date'=> date('Y-m-d'), //if user is 77 citizen
                        'status' => 2, //hod assistant final submit is submitted and verified
                        'rejected_status' => 0,
                        'remark' => 'Rectified',
                        'remark_details' => null,
                        'file_status' => 1
                    ]);
                }
            }
            if ($empDetails->uploader_role_id == 77) {
                if ($empDetails->remark == null) {
                    $empDetails->update([

                        'received_by' => $receiver, //previous sender
                        'forwarded_by' => $getUser->id, //current sender
                        'status' => 1, //file received at hod assistant
                        'appl_number' => $applicationNo, //appl_number here
                        'appl_date' => date('Y-m-d'), //if user is 77 citizen
                        'rejected_status' => 0,
                        'remark' => null,
                        'remark_details' => null,
                        'file_status' => 1

                    ]);
                }
            } else { // this is for hod-assistant
                if ($empDetails->remark != null) {
                    $empDetails->update([

                        'received_by' => $receiver, //previous sender
                        'forwarded_by' => $getUser->id, //current sender
                        'status' => 1, //file received at hod assistant
                        'appl_number' => $applicationNo, //appl_number here
                        'appl_date' => date('Y-m-d'), //if user is 77 citizen
                        'rejected_status' => 0,
                        'remark' => 'Rectified',
                        'remark_details' => null,
                        'file_status' => 1

                    ]);
                }
            }


            //dd($empDetails);


            return redirect()->route('other_form_details_submit_backlog')->with('message', "Backlog Applicant details is Succesfully Submitted!");
            session()->forget(['ein', 'ein']);
        }

        //  if (session()->has('ein')) {
        //     $session_ein = session()->get('ein');
        //     if ($session_ein != null) {
        //         session()->forget('ein');
        //     }
        // }
    }

    //second appli
    public function submitForms2ndAppl()
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        //$ein = null;
        //$empDetails=null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');

            $formId = 4; // here we set last formid as 4 according to ui;
            $getFormSubmisiondetails = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->toArray();
            if (count($getFormSubmisiondetails) == 0) {
                EmpFormSubmissionStatus::create([
                    'ein' => $ein,
                    'form_id' => $formId,
                    'submit_status' => 1 // status 1 = submitted
                ]);
            } else {
                //   $emp_form_stat_row = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->first();
                //   $row = EmpFormSubmissionStatus::find($emp_form_stat_row->id);
                //   $row->submit_status = 1;
                //   $row->save();
                //   return redirect()->route('other_form_details_submit')->with('message', "Applicant details was Succesfully Submitted!");
                // return 1;
                return redirect()->back()->with('errormessage', "Already Submitted..........!");
            }
            $empDetails = ProformaModel::get()->where("ein", $ein)->first();

            $getUser1 = User::get()->where('id', $empDetails->forwarded_by)->first();

            $getUser2 = User::get()->where('id', $empDetails->forwarded_by)->toArray();
            //dd($getUser1->name);
            if (count($getUser2) == null) {
                $receiver = null; //previous sender
            }
            if (count($getUser2) != null) {
                $receiver = $getUser1->name; //previous sender
            }
            ////////////////////////////////////////application number/////////////////////////////////
            // $einCount =  ProformaModel::orderBy('id', 'DESC')->first();

            // //GETTING DATA FROM number_generator table
            // $fix = NumberGenerator::get()->first();
            // //dd($fix);
            // $prefixData = $fix->prefix;

            // if ($einCount != null) {
            //     $lastID = $einCount->id;
            //     $nextNumber = $lastID + 1;

            //     $applicationNo = $prefixData . str_pad($nextNumber, $fix->suffix, '0', STR_PAD_LEFT);
            // } else {
            //     $lastID = 0;
            //     $nextNumber = $lastID + 1;
            //     $applicationNo = $prefixData . str_pad($nextNumber, $fix->suffix, '0', STR_PAD_LEFT);
            // }
            //////////////////////////////////////////end here//////////////////////////////////////////////////////
            $empRetrieveApplno = ProformaHistoryModel::get()->where("ein", $ein)->first();

            if ($empDetails->uploader_role_id != 77) {

                if ($empDetails->remark == null) {
                    //$getUser1 = User::get()->where('id', $empDetails->forwarded_by)->first();

                    $empDetails->update([
                        'received_by' => $receiver, //previous sender
                        'forwarded_by' => $getUser->id, //current sender
                        'appl_number' => $empRetrieveApplno->appl_No, //appl_number here
                        'appl_date' => $empRetrieveApplno->appl_date, //appl_number here, //if user is 77 citizen
                        'status' => 2,
                        'rejected_status' => 0,
                        'remark' => null,
                        'remark_details' => null,
                        'file_status' => 1
                    ]);
                }
            } else {
                if ($empDetails->remark != null) {
                    //$getUser1 = User::get()->where('id', $empDetails->forwarded_by)->first();

                    $empDetails->update([
                        'received_by' => $receiver, //previous sender
                        'forwarded_by' => $getUser->id, //current sender
                        'appl_number' => $empRetrieveApplno->appl_No, //appl_number here
                        'appl_date' => $empRetrieveApplno->appl_date, //appl_number here, //if user is 77 citizen
                        'status' => 2, //hod assistant final submit is submitted and verified
                        'rejected_status' => 0,
                        'remark' => 'Rectified',
                        'remark_details' => null,
                        'file_status' => 1
                    ]);
                }
            }

            if ($empDetails->uploader_role_id == 77) {
                if ($empDetails->remark == null) {
                    $empDetails->update([

                        'received_by' => $receiver, //previous sender
                        'forwarded_by' => $getUser->id, //current sender
                        'status' => 1, //file received at hod assistant
                        'appl_number' => $empRetrieveApplno->appl_No, //appl_number here
                        'appl_date' => $empRetrieveApplno->appl_date, //appl_number here, //if user is 77 citizen
                        'rejected_status' => 0,
                        'remark' => null,
                        'remark_details' => null,
                        'file_status' => 1

                    ]);
                }
            } else { // this is for hod-assistant
                if ($empDetails->remark != null) {
                    $empDetails->update([

                        'received_by' => $receiver, //previous sender
                        'forwarded_by' => $getUser->id, //current sender
                        'status' => 1, //file received at hod assistant
                        'appl_number' => $empRetrieveApplno->appl_No, //appl_number here
                        'appl_date' => $empRetrieveApplno->appl_date, //appl_number here, //if user is 77 citizen
                        'rejected_status' => 0,
                        'remark' => 'Rectified',
                        'remark_details' => null,
                        'file_status' => 1

                    ]);
                }
            }


            //dd($empDetails);

            // return redirect()->route('viewStartEmp')->with('message', "Succesfully Submitted!");
            // return redirect()->route('viewStartEmp')->with('message', "Applicant details update is Succesfully Submitted!");
            return redirect()->route('other_form_details_submit2ndAppl')->with('message', "Applicant details Succesfully Submitted!");
            session()->forget(['ein', 'ein']);
        } else {
            return redirect()->route('other_form_details_submit2ndAppl')->with('message', "First fill all the Forms from 1 to 3 and then Submit!!!!!!");
        }
    }

    //submit forms for fresh but last update query of form_status=1 is to write code
    public function submitFormsUpdate()
    {

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();


        //check for uploader id and if 77 then update appl_date and appl_number else only appl_number




        //$ein = null;
        //$empDetails=null;
        if (session()->has('ein')) {



            $ein =  session()->get('ein');
            // dd(  $ein );
            $empCheck = ProformaModel::get()->where("ein", $ein)->where("form_status", 1)->where("family_details_status", 1)->where("upload_status", 1)->toArray();

            if (count($empCheck) == 1) {

                $formId = 4; // here we set last formid as 4 according to ui;
                $getFormSubmisiondetails = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->toArray();
                if (count($getFormSubmisiondetails) == 0) {
                    EmpFormSubmissionStatus::create([
                        'ein' => $ein,
                        'form_id' => $formId,
                        'submit_status' => 1 // status 1 = submitted
                    ]);
                } else {
                    $emp_form_stat_row = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->first();
                    $row = EmpFormSubmissionStatus::find($emp_form_stat_row->id);
                    $row->submit_status = 1;
                    $row->save();
                    // return redirect()->route('viewStartEmp')->with('message', "Applicant details updated was Succesfully Submitted!");
                    //return redirect()->back()->with('errormessage', "Already Submitted..........!");
                }
                $empDetails = ProformaModel::get()->where("ein", $ein)->first();
                $getUser1 = User::get()->where('id', $empDetails->forwarded_by)->first();

                $getUser2 = User::get()->where('id', $empDetails->forwarded_by)->toArray();
                //dd($getUser1->name);
                if (count($getUser2) == null) {
                    $receiver = null; //previous sender
                }
                if (count($getUser2) != null) {
                    $receiver = $getUser1->name; //previous sender
                }
                //dd($receiver);
                ////////////////////////////////////////application number/////////////////////////////////
                $einCount =  ProformaModel::orderBy('id', 'DESC')->first();

                //GETTING DATA FROM number_generator table
                $fix = NumberGenerator::get()->first();
                //dd($fix);
                $prefixData = $fix->prefix;

                if ($einCount != null) {
                    $lastID = $einCount->id;
                    $nextNumber = $lastID + 1;

                    $applicationNo = $prefixData . str_pad($nextNumber, $fix->suffix, '0', STR_PAD_LEFT);
                } else {
                    $lastID = 0;
                    $nextNumber = $lastID + 1;
                    $applicationNo = $prefixData . str_pad($nextNumber, $fix->suffix, '0', STR_PAD_LEFT);
                }
                //////////////////////////////////////////end here//////////////////////////////////////////////////////
                if ($empDetails->appl_number != null) {
                    if ($empDetails->uploader_role_id == 77) {
                        // this is for hod-assistant                      
                        $empDetails->update([
                            'received_by' => $receiver, //previous sender
                            'forwarded_by' => $getUser->id, //current sender
                            // 'appl_number' => $applicationNo,//appl_number here
                            // 'appl_date'=> date('Y-m-d'), //if user is 77 citizen

                            'status' => 2,
                            'rejected_status' => 0,
                            'remark' => 'Rectified',
                            'remark_details' => null,
                            'file_status' => 1
                        ]);
                    }


                    if ($empDetails->uploader_role_id != 77) {

                        $empDetails->update([
                            'received_by' => $receiver, //previous sender
                            'forwarded_by' => $getUser->id, //current sender
                            //  'appl_number' => $applicationNo,//appl_number here
                            // 'appl_date'=> date('Y-m-d'), //if user is 77 citizen
                            'status' => 2,
                            'rejected_status' => 0,
                            'remark' => 'Rectified',
                            'remark_details' => null,
                            'file_status' => 1
                        ]);
                    }
                }

                if ($empDetails->appl_number == null) {
                    if ($empDetails->uploader_role_id == 77) {

                        $empDetails->update([

                            'received_by' => $receiver, //previous sender
                            'forwarded_by' => $getUser->id, //current sender
                            'appl_number' => $applicationNo, //appl_number here
                            'appl_date' => date('Y-m-d'), //if user is 77 citizen
                            'status' => 1,
                            'rejected_status' => 0,
                            'remark' => null,
                            'remark_details' => null,
                            'file_status' => 1

                        ]);
                    }

                    if ($empDetails->uploader_role_id != 77) {

                        $empDetails->update([

                            'received_by' => $receiver, //previous sender
                            'forwarded_by' => $getUser->id, //current sender
                            'appl_number' => $applicationNo, //appl_number here
                            // 'appl_date'=> date('Y-m-d'), //if user is 77 citizen
                            'status' => 2,
                            'rejected_status' => 0,
                            'remark' => 'Verified',
                            'remark_details' => null,
                            'file_status' => 1

                        ]);
                    }
                }

                //dd($empDetails);

                session()->forget(['ein', 'from_emp_ein']);
                $ein = null;
                if ($getUser->role_id == 1) {
                    return redirect()->route('viewStartEmp')->with('message', "Applicant details Succesfully Submitted!");
                } else {
                    return redirect()->route('viewStatusApplicant')->with('message', "Applicant details Succesfully Submitted!");
                }
                // session()->forget(['ein', 'ein']);

            } else {
                return redirect()->route('other_form_details_dihas')->with('message', "First fill all the Forms from 1 to 3 and then Submit!!!!!!");
            }
        }
    }
    //////////////////////////////////////////////////////APPLICANT/////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
    public function applicantUpdate()
    {

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        //$ein = null;
        //$empDetails=null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');
            // $ein = Crypt::decryptString($ein); 
            $emp_form_stat = ProformaModel::get()->where("ein", $ein)->first();
            $empDetails = $emp_form_stat;

            if (($emp_form_stat->status == 1)  || ($emp_form_stat->status == 2)) {
                $status = 1;
                // return redirect()->back()->with('message', "Already proceeded! Click Form menu to view forms!");
            } else {
                $status = 0;
                // return redirect()->back()->with('error_message', "No Data Found!");
            }
            return view('admin/Form/form_other_applicant_update', compact('empDetails', 'status'));
        }
    }
    //submit forms for fresh but last update query of form_status=1 is to write code
    public function submitApplicantUpdate()
    {


        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        // dd($getUser->user_id);
        //check for uploader id and if 77 then update appl_date and appl_number else only appl_number

        ////////////////////////////////////////application number/////////////////////////////////
        $einCount =  ProformaModel::orderBy('id', 'DESC')->first();

        //GETTING DATA FROM number_generator table
        $fix = NumberGenerator::get()->first();
        //dd($fix);
        $prefixData = $fix->prefix;

        if ($einCount != null) {
            $lastID = $einCount->id;
            $nextNumber = $lastID + 1;

            $applicationNo = $prefixData . str_pad($nextNumber, $fix->suffix, '0', STR_PAD_LEFT);
        } else {
            $lastID = 0;
            $nextNumber = $lastID + 1;
            $applicationNo = $prefixData . str_pad($nextNumber, $fix->suffix, '0', STR_PAD_LEFT);
        }
        //////////////////////////////////////////end here//////////////////////////////////////////////////////

        //$ein = null;
        //$empDetails=null;
        if (session()->has('ein')) {

            $ein =  session()->get('ein');

            $empCheck = ProformaModel::get()->where("ein", $ein)->where("form_status", 1)->where("family_details_status", 1)->where("upload_status", 1)->toArray();

            if (count($empCheck) == 1) {

                $formId = 4; // here we set last formid as 4 according to ui;
                $getFormSubmisiondetails = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->toArray();
                if (count($getFormSubmisiondetails) == 0) {
                    EmpFormSubmissionStatus::create([
                        'ein' => $ein,
                        'form_id' => $formId,
                        'submit_status' => 1 // status 1 = submitted
                    ]);
                } else {
                    $emp_form_stat_row = EmpFormSubmissionStatus::get()->where('ein', $ein)->where('form_id', $formId)->first();
                    $row = EmpFormSubmissionStatus::find($emp_form_stat_row->id);
                    $row->submit_status = 1;
                    $row->save();
                    // return redirect()->route('viewStartEmp')->with('message', "Applicant details updated was Succesfully Submitted!");
                    //return redirect()->back()->with('errormessage', "Already Submitted..........!");
                }
                $empDetails = ProformaModel::get()->where("ein", $ein)->first();
                // $getUser1 = User::get()->where('id', $empDetails->forwarded_by)->first();
                $getUser1 = User::get()->where('id', $empDetails->forwarded_by)->first();

                $getUser2 = User::get()->where('id', $empDetails->forwarded_by)->toArray();
                //dd($getUser1->name);
                if (count($getUser2) == null) {
                    $receiver = null; //previous sender
                }
                if (count($getUser2) != null) {
                    $receiver = $getUser1->name; //previous sender
                }
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                if ($empDetails->appl_number != null || $empDetails->appl_number != '') {

                    $empDetails->update([
                        'received_by' => $receiver, //previous sender
                        'forwarded_by' => $getUser->id, //current sender
                        // 'appl_number' => $applicationNo,//appl_number here
                        //'appl_date'=> date('Y-m-d'), //if user is 77 citizen
                        'status' => 1,
                        'rejected_status' => 0,
                        'remark' => 'Rectified',
                        'remark_details' => null,
                        'file_status' => 1
                    ]);
                    // dd($empDetails);
                }
                if ($empDetails->appl_number == null || $empDetails->appl_number == '') {

                    $empDetails->update([

                        'received_by' => $receiver, //previous sender
                        'forwarded_by' => $getUser->id, //current sender
                        'appl_number' => $applicationNo, //appl_number here
                        'appl_date' => date('Y-m-d'), //if user is 77 citizen

                        'status' => 1,
                        'rejected_status' => 0,
                        'remark' => null,
                        'remark_details' => null,
                        'file_status' => 1

                    ]);
                }

                ///////////////////////////////////////////////////////////////////////////////////////////////////


                return redirect()->route('other_form_applicant_update')->with('message', "Applicant details Succesfully Submitted!");
                //return redirect()->route('viewStatusApplicant')->with('message', "Applicant details update is Succesfully Submitted!");
                session()->forget(['ein', 'ein']);
            } else {
                return redirect()->route('other_form_applicant_update')->with('message', "First fill all the Forms from 1 to 3 and then Submit!!!!!!");
            }
        }
    }
    public function downloadDetailspdf(Request $request)
    {


        //  dd($request->all());

        $getPortalName = PortalModel::where('id', 1)->first();
        //Portal name short form    
        $getProjectShortForm = $getPortalName->short_form_name;
        //Application long name
        $getSoftwareName = $getPortalName->software_name;
        $getDeptName = $getPortalName->department_name;
        $getGovtName = $getPortalName->govt_name;
        $getDeveloper = $getPortalName->developed_by;
        $getCopyright = $getPortalName->copyright;
        $getDate = Carbon::now()->format('d-m-Y');

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();


        if (session()->has('ein')) {
            $ein = session()->get('ein');
            $empDetails = ProformaModel::get()->where("ein", $ein)->first();


            $api_preference = array();
            // $notfound = "";
            //  dd( $data['transfer_post_id'] );
            $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
                'dept_code' => $empDetails->dept_id,
                'token' => "b000e921eeb20a0d395e341dfcd6117a",
            ]);
            $api_preference = json_decode($response->getBody(), true);



            $api_preference_dept_id_option = array();
            // $notfound = "";
            //  dd( $data['transfer_post_id'] );
            $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
                'dept_code' => $empDetails->dept_id_option,
                'token' => "b000e921eeb20a0d395e341dfcd6117a",
            ]);
            $api_preference_dept_id_option = json_decode($response->getBody(), true);




            // dd($empDetails);
            if ($empDetails->emp_state != null) {
                $stateDetails = State::get()->where('state_code_census', $empDetails->emp_state)->first();
            } else {
                $stateDetails = null;
            }
            if ($empDetails->emp_addr_district != null) {
                $District = District::get()->where('district_code_census', $empDetails->emp_addr_district)->first();
            } else {
                $District = null;
            }
            if ($empDetails->emp_addr_subdiv != null) {
                $subDiv = SubDivision::get()->where('sub_district_cd_lgd', $empDetails->emp_addr_subdiv)->first();
            } else {
                $subDiv = null;
            }
            //dd($empDetails->applicant_desig_id);
            if ($empDetails->applicant_desig_id != null) {
                // $post = DesignationModel::get()->where('id', $empDetails->applicant_desig_id)->first();
                $post = null; // Initialize the variable to store deg_desc

                foreach ($api_preference as $item) {
                    if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $empDetails['applicant_desig_id']) {
                        $post = $item['dsg_desc'];



                        break;
                    }
                }
                //   dd($post);


            } else {
                $post = null;
            }

            if ($empDetails->second_post_id != null) {


                // $secondpost = DesignationModel::get()->where('id', $empDetails->second_post_id)->first();


                $secondpost = null; // Initialize the variable to store deg_desc

                foreach ($api_preference as $item) {
                    if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $empDetails['second_post_id']) {
                        $secondpost = $item['dsg_desc'];



                        break;
                    }
                }
            } else {
                $secondpost = null;
            }

            if ($empDetails->dept_id_option != null) {
                // $diff_dept = DepartmentModel::get()->where('id', $empDetails->dept_id_option)->first();


                //for third preference 
                $diff_dept = null; // Initialize the variable to store deg_desc

                foreach ($api_preference_dept_id_option as $item) {
                    if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $empDetails['third_post_id']) {
                        $diff_dept = $item['field_dept_desc'];

                        //   dd( $diff_dept);
                        break;
                    }
                }
            } else {
                $diff_dept = null;
            }

            if ($empDetails->third_post_id != null) {
                // $thirdpost = DesignationModel::get()->where('id', $empDetails->third_post_id)->first();
                $thirdpost = null; // Initialize the variable to store deg_desc

                foreach ($api_preference_dept_id_option as $item) {
                    if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $empDetails['third_post_id']) {
                        $thirdpost = $item['dsg_desc'];



                        break;
                    }
                }
            } else {
                $thirdpost = null;
            }

            if ($empDetails->applicant_edu_id != null) {
                $educations = EducationModel::get()->where('id', $empDetails->applicant_edu_id)->first();
            } else {
                $educations = null;
            }

            // if ($empDetails->applicant_grade != null) {
            //     $grades = grade::get()->where('grade_id', $empDetails->applicant_grade)->first();
            // } else {
            //     $grades = null;
            // }


            if ($empDetails->relationship != null) {
                $Relationship = RelationshipModel::get()->where('id', $empDetails->relationship)->first();
            } else {
                $Relationship = null;
            }
            if ($empDetails->emp_state_ret != null) {
                $stateDetails1 = State::get()->where('state_code_census', $empDetails->emp_state_ret)->first();
            } else {
                $stateDetails1 = null;
            }
            if ($empDetails->emp_addr_district_ret != null) {
                $District1 = District::get()->where('district_code_census', $empDetails->emp_addr_district_ret)->first();
            } else {
                $District1 = null;
            }
            if ($empDetails->emp_addr_subdiv_ret != null) {
                $subDiv1 = SubDivision::get()->where('sub_district_cd_lgd', $empDetails->emp_addr_subdiv_ret)->first();
            } else {
                $subDiv1 = null;
            }
            if ($empDetails->sex != null) {
                $gender = GenderModel::get()->where('id', $empDetails->sex)->first();
            } else {
                $gender = null;
            }


            ////////Family members Extract/////////////////////////////////
            $newFamilyArray = array();
            $familyCount = FamilyMembers::get()->where("ein", $ein)->toArray();
            if ($familyCount != null) {
                foreach ($familyCount as $familyMember) {
                    if ($familyMember['relation'] != null) {
                        $RelationshipF = RelationshipModel::get()->where('id', $familyMember['relation'])->first();
                    } else {
                        $RelationshipF = null;
                    }
                    $newFamilyArray[] = $familyMember;
                }
            }
            $familyCount = $newFamilyArray;
            // dd($familyCount);
            /////////////////////////////////////////////////////////////////////////////////////////////
            ////////Uploaded file name Extract/////////////////////////////////
            $newFileArray = array();
            $filesDetails = FileModel::get()->where("ein", $ein)->toArray();
            if ($filesDetails != null) {
                foreach ($filesDetails as $fileCount) {

                    if ($fileCount['doc_id'] != null) {
                        $files = FilesToUploadModel::get()->where("doc_id", $fileCount['doc_id'])->first();
                    } else {
                        $files = null;
                    }
                    $fileCount['doc_name'] = $files['doc_name'];
                    $newFileArray[] = $fileCount;
                }
            }
            $filesDetails = $newFileArray;
            ///////////////////////////////////////////////////////////////////////////////////////           

            if ($familyCount >= 0 && $filesDetails >= 0) {

                $html = view('admin/Form/downloadDetailsPdf', [
                    //view data
                    'empDetails' => $empDetails,
                    'getProjectShortForm' => $getProjectShortForm,
                    'getSoftwareName' => $getSoftwareName,
                    'getDeptName' => $getDeptName,
                    'getGovtName' => $getGovtName,
                    'getDeveloper' => $getDeveloper,
                    'getDate' => $getDate,
                    'getCopyright' => $getCopyright,
                    'stateDetails' => $stateDetails->state_name,
                    'District' => $District->district_name_english,
                    'subDiv' => $subDiv,
                    'post' => $post,
                    'secondpost' => $secondpost,
                    'thirdpost' => $thirdpost,
                    'diff_dept' => $diff_dept,

                    'educations' => $educations->edu_name,
                    // 'grades' => $empDetails->grade_name,
                    'Relationship' => $Relationship->relationship,
                    'gender' => $gender,
                    'familyCount' => $familyCount,
                    'filesDetails' => $filesDetails,
                    'files' => $files['doc_name'],
                    'RelationshipF' => $RelationshipF['relationship'],
                    'stateDetails1' => $stateDetails1->state_name,
                    'District1' => $District1->district_name_english,
                    'subDiv1' => $subDiv1


                ])->render();

                $pdf = new Dompdf();
                $pdf->loadHtml($html);
                // Customize the content of the PDF as per your needs

                $pdf->setPaper('A4', 'portrait');
                // Set the paper size and orientation
                $pdf->render();

                // Generate the PDF file and send it as a response
                return $pdf->stream('document.pdf', ['Attachment' => false]);
            }
        } else {
            return redirect()->back()->with('error_message', 'Not found!');
        }
    }
}
