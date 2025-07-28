<?php

namespace App\Http\Controllers;

use App\Models\BankBranch;
use App\Models\PensionEmployee;
use App\Models\MasterServiceDetails;
use App\Models\EmpFormSubmissionStatus;
use App\Models\EmpDocsPhotos;
use App\Models\entryAgeModel;
use App\Models\OriginalDataUpdateLog;
use App\Models\ProformaModel;
use DateTime;
use Illuminate\Support\Facades\Crypt;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DescriptiveRoleEditController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        // dd($request->toArray());
        // ein
        // form_id
        // control_name
        // remark
        // dd($request->toArray());
        $user_id = Auth::user()->id;


        // update proforma information
        $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();
        // $getBranchDetails = BankBranch::get()->where('bank_id', $request->brnm)->first();

        if ($empDetails != null) {

            if ($request->control_name == 'applicant_mobile') {
                $empDetails->update([$request->control_name => $request->applicant_mobile]);
            } elseif ($request->control_name == 'applicant_name') {
                // return  $request->control_name;
                // return  [$request->control_name=> $request->applicant_name];
                $empDetails->update([$request->control_name => $request->applicant_name]);
            } elseif ($request->control_name == 'applicant_email_id') {
                $empDetails->update([$request->control_name => $request->applicant_email_id]);
            } elseif ($request->control_name == 'relationship') {
                $empDetails->update([$request->control_name => $request->relationship]);
            } elseif ($request->control_name == 'expire_on_duty') {
                $empDetails->update([$request->control_name => $request->input('expire_on_duty')]);
            } elseif ($request->control_name == 'deceased_doe') {
                $empDetails->update([$request->control_name => $request->deceased_doe]);
            } elseif ($request->control_name == 'deceased_dob') {
                $empDetails->update([$request->control_name => $request->deceased_dob]);
            } elseif ($request->control_name == 'appl_date') {
                $empDetails->update([$request->control_name => $request->appl_date]);
            } elseif ($request->control_name == 'applicant_edu_id') {
                $empDetails->update([$request->control_name => $request->applicant_edu_id]);
            } elseif ($request->control_name == 'applicant_desig_id') {
                $empDetails->update([$request->control_name => $request->applicant_desig_id]);
            } elseif ($request->control_name == 'applicant_grade') {
                $empDetails->update([$request->control_name => $request->applicant_grade]);
            } elseif ($request->control_name == 'caste') {
                $empDetails->update([$request->control_name => $request->input('caste')]);

            } elseif ($request->control_name == 'sex') {
                $empDetails->update([$request->control_name => $request->sex]);

               // dd([$request->control_name => $request->sex]);

            } elseif ($request->control_name == 'emp_pincode') {
                $empDetails->update([$request->control_name => $request->emp_pincode]);
            } elseif ($request->control_name == 'emp_pincode_ret') {
                $empDetails->update([$request->control_name => $request->emp_pincode_ret]);
            } elseif ($request->control_name == 'second_appl_name') {
                $empDetails->update([$request->control_name => $request->second_appl_name]);
            } elseif ($request->control_name == 'physically_handicapped') {
                //return [$request->control_name => $request->input('physically_handicapped')];

                $empDetails->update([$request->control_name => $request->input('physically_handicapped')]);
            } elseif ($request->control_name == 'emp_addr_lcality') {
                $empDetails->update([$request->control_name => $request->emp_addr_lcality]);
            } elseif ($request->control_name == 'emp_addr_subdiv') {
                // dd([$request->control_name => $request->emp_addr_subdiv]);
                $empDetails->update([$request->control_name => $request->emp_addr_subdiv]);
            } elseif ($request->control_name == 'emp_addr_district') {
                $empDetails->update([$request->control_name => $request->emp_addr_district]);
            } elseif ($request->control_name == 'emp_addr_lcality_ret') { //.................................
                $empDetails->update([$request->control_name => $request->emp_addr_lcality_ret]);
            } elseif ($request->control_name == 'emp_addr_subdiv_ret') {

                //dd([$request->control_name => $request->emp_addr_subdiv_ret]);

                $empDetails->update([$request->control_name => $request->emp_addr_subdiv_ret]);
            } elseif ($request->control_name == 'emp_addr_district_ret') {
                // return [$request->control_name => $request->emp_addr_district_ret];          
                $empDetails->update([$request->control_name => $request->emp_addr_district_ret]);
            } elseif ($request->control_name == 'address_after_ret') {
                $empDetails->update([$request->control_name => $request->address_after_ret]);
            } elseif ($request->control_name == 'emp_state') {
                $empDetails->update([$request->control_name => $request->emp_state]);
            } elseif ($request->control_name == 'emp_state_ret') {
                $empDetails->update([$request->control_name => $request->emp_state_ret]);
            } elseif ($request->control_name == 'emp_pincode') {
                $empDetails->update([$request->control_name => $request->emp_pincode]);
            } elseif ($request->control_name == 'emp_pincode_ret') {
                $empDetails->update([$request->control_name => $request->emp_pincode_ret]);
            } elseif ($request->control_name == 'applicant_dob') {

                //checking whether the applicant dob is greater than or equalto 15 years or not
                
                $entryDate = entryAgeModel::get()->first();
                //return $entryDate;

                $dateToday = new DateTime(date("m/d/Y")); //confusion here what about other system       
                $appl_DOB = new DateTime($request->applicant_dob);
                $difference = $appl_DOB->diff($dateToday);
                $resultDays = $difference->format('%R%a days'); //result comes as +5606 days

                $dateExplode = explode(' days', $resultDays); //remove space days
                $dateDifference = explode('+', $dateExplode['0']); //remove +
                //return  $resultDays;//display only days

                $ageLimit=($entryDate->entry_age *365) + 3;
                //return $ageLimit;

                if ($dateDifference['1'] >= $ageLimit) {
                    $empDetails->update([$request->control_name => $request->applicant_dob]);
                } else {
                    //return $plusRemove['1'];
                    return back()->with('eligible', "You are not Eligible to apply!!!!..Needs to Complete 15 Years");
                }
            } elseif ($request->control_name == 'deceased_causeofdeath') {
                $empDetails->update([$request->control_name => $request->deceased_causeofdeath]);
            }
        }

        // insert data update log
        OriginalDataUpdateLog::create([
            'ein' => $request->ein,
            'form_id' => $request->form_id,
            'remark' => $request->remark,
            'control_name' => $request->control_name,
            'updated_by' => $user_id
        ]);


        return redirect()->back()->with('message', 'Updated successfully!');
    }

    // edit address details 
    public function saveAddressDetails(Request $request)
    {
        $ein = $request->ein;
        $getEmpDetls = ProformaModel::get()->where('ein', $ein)->first();
        $getEmpDetls->emp_addr_lcality = $request->emp_addr_lcality;
        $getEmpDetls->emp_addr_subdiv = $request->emp_addr_subdiv;
        $getEmpDetls->emp_addr_district = $request->emp_addr_district;
        //$getEmpDetls->emp_addr_assem_cons = $request->emp_addr_assem_cons;
        $getEmpDetls->emp_state = $request->emp_state;
        $getEmpDetls->emp_pincode = $request->emp_pincode;


        $getEmpDetls->emp_addr_lcality_ret = $request->emp_addr_lcality_ret;
        $getEmpDetls->emp_addr_subdiv_ret = $request->emp_addr_subdiv_ret;
        $getEmpDetls->emp_addr_district_ret = $request->emp_addr_district_ret;
        // $getEmpDetls->emp_addr_assem_cons_ret = $request->emp_addr_assem_cons_ret;
        // $getEmpDetls->address_after_ret = $request->address_after_ret;
        $getEmpDetls->emp_state_ret = $request->emp_state_ret;
        $getEmpDetls->emp_pincode_ret = $request->emp_pincode_ret;
        $getEmpDetls->save();
        $user_id = Auth::user()->id;
        OriginalDataUpdateLog::create([
            'ein' => $request->ein,
            'form_id' => $request->form_id,
            'remark' => $request->remark,
            'control_name' => $request->control_name,
            'updated_by' => $user_id
        ]);
        // return $request->emp_addr_subdiv_ret;
        return redirect()->back()->with('message', 'Updated successfully!');
    }

    // edit bank details 
    // public function saveBankDetails(Request $request)
    // {
    //     // dd($request->toArray());
    //     $ein = $request->ein;
    //     $getEmpDetls = PensionEmployee::get()->where('ein', $ein)->first();

    //     $getEmpDetls->bnk_name = $request->bnk_name;
    //     $getBranchDetails = BankBranch::get()->where('id', $request->bnk_name)->first();
    //     $getIfsc = BankBranch::get()->where('bnk_cd', $getBranchDetails->bnk_cd)->where('brnm', $request->brnm_model)->first();
    //     if($getIfsc != null) {

    //         $getEmpDetls->ifsc_cd = $getIfsc->ifsc_cd;
    //     } else {
    //         $getEmpDetls->ifsc_cd = null;
    //     }
    //     $getEmpDetls->brnm = $request->brnm_model;


    //     $getEmpDetls->bnk_ac_no = $request->bnk_ac_no;
    //     $getEmpDetls->save();
    //     $user_id = Auth::user()->id;
    //     OriginalDataUpdateLog::create([
    //         'ein' => $request->ein,
    //         'form_id' => $request->form_id,
    //         'remark' => $request->remark,
    //         'control_name' => $request->control_name,
    //         'updated_by' => $user_id
    //     ]);
    //     return redirect()->back()->with('message', 'Updated successfully!');
    // }

    // edit Pay details 
    // public function savePayDetails(Request $request)
    // {
    //     // dd($request->toArray())
    //     $ein = $request->ein;
    //     $getEmpDetls = PensionEmployee::get()->where('ein', $ein)->first();
    //     $getBranchDetails = BankBranch::get()->where('bank_id', $request->brnm)->first();
    //     $getEmpDetls->pay_comm_cd = $request->pay_comm_cd;
    //     $getEmpDetls->psc_dscr = $request->psc_dscr;
    //     $getEmpDetls->da_rate = $request->da_rate;
    //     $getEmpDetls->commutation_rate = $request->commutation_rate;
    //     $getEmpDetls->save();
    //     $user_id = Auth::user()->id;
    //     OriginalDataUpdateLog::create([
    //         'ein' => $request->ein,
    //         'form_id' => $request->form_id,
    //         'remark' => $request->remark,
    //         'control_name' => $request->control_name,
    //         'updated_by' => $user_id
    //     ]);
    //     return redirect()->back()->with('message', 'Updated successfully!');
    // }
    // Others details 
    // public function saveOtherDetails(Request $request)
    // {
    //     // dd($request->toArray());
    //     $ein = $request->ein;
    //     $getEmpDetls = PensionEmployee::get()->where('ein', $ein)->first();
    //     $getEmpDetls->family_pension_admissible = $request->family_pension_admissible;
    //     $getEmpDetls->name_of_treasury = $request->name_of_treasury;
    //     $getEmpDetls->save();
    //     $user_id = Auth::user()->id;
    //     OriginalDataUpdateLog::create([
    //         'ein' => $request->ein,
    //         'form_id' => $request->form_id,
    //         'remark' => $request->remark,
    //         'control_name' => $request->control_name,
    //         'updated_by' => $user_id
    //     ]);
    //     return redirect()->back()->with('message', 'Updated successfully!');
    // }
}
