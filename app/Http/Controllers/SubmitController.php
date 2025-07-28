<?php

namespace App\Http\Controllers;

use App\Models\CmisVacancyModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProformaModel;
use App\Models\DepartmentModel;
use App\Models\DesignationModel;
use App\Models\RemarksApproveModel;
use App\Models\User;
use App\Models\RemarksModel;
use App\Models\Sign1Model;
use App\Models\Sign2Model;
use App\Models\VacancyModel;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Session;
use App\Library\Senitizer;

class SubmitController extends Controller
{
    public function __construct()
    {
       if(isset($_REQUEST)){
            $_REQUEST = Senitizer::senitize($_REQUEST);
       }
    }


    public function submitForm(Request $request)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $deptId = $request->input('dept_id');
        $RemarksApprove = RemarksApproveModel::get()->toArray();
       // Session::put('deptID', $deptId);
        //dd(strlen($deptId));
        $empListArray = array();
        
        
        if(session()->get('deptId') != "" && $request->input('page') == ""){
            $request->session()->forget(['deptId']);
        }

        if(strlen($deptId) > 0 ){
            session()->put('deptId', $deptId);
        }
        if (strlen(session()->get('deptId'))>0 ) {
            $deptId = session()->get('deptId');
            $empListArray = ProformaModel::get()->where('dept_id', $deptId)->where('file_status', 5)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe, appl_date, applicant_dob")->where('dept_id', $deptId)->where('file_status', 5)->paginate(10);
            $empListprint = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe, appl_date, applicant_dob")->where('dept_id', $deptId)->where('file_status', 5)->get();
        }
        else {
            $empListArray = ProformaModel::get()->where('file_status', 5)->where( 'status', 2 )->where( 'rejected_status', 0 )->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe, appl_date, applicant_dob")->where('file_status', 5)->where( 'status', 2 )->where( 'rejected_status', 0 )->paginate(10);
            $empListprint = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe, appl_date, applicant_dob")->where('file_status', 5)->where( 'status', 2 )->where( 'rejected_status', 0 )->get();
        }

        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        $Remarks = RemarksModel::get()->toArray();
        $stat = "";

        foreach ($empList as $data) {
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = "started";
                $data->status = "Incomplete";
            }

            if ($data->status == 1) {
                $stat = "submitted";
                $data->status = "Submitted";
            }
            if ($data->status == 2) {
                $stat = "verified";
                $data->status = "Verified";
            }
            if ($data->status == 3) {
                $stat = "forapproval";
                $data->status = "Put up for Approval";
            }

            if ($data->status == 4) {
                $stat = "approved";
                $data->status = "Approved";
            }
            if ($data->status == 5) {
                $stat = "appointed";
                $data->status = "Appointed";
            }
            if ($data->status == 6) {
                $stat = "order";
                $data->status = "Appointment Order";
            }


            $data->formSubStat = $stat;
        }
        return view('admin/selectDeptByDPAssist', compact("empListprint","RemarksApprove","deptListArray", "empList", "deptId", "empListArray", "Remarks"));
    }
    public function submitFormApprove(Request $request)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $desigListArray = DesignationModel::orderBy('desig_name')->get()->unique('desig_name');
        $deptId = $request->input('dept_id');
        $RemarksApprove = RemarksApproveModel::get()->toArray();
       // Session::put('deptID', $deptId);
        //dd(strlen($deptId));
        $statusArray = [4, 5, 6];
        if($getUser->role_id==5 || $getUser->role_id==6){
        $empListArray = array();
        if(session()->get('deptId') != "" && $request->input('page') == ""){
            $request->session()->forget(['deptId']);
        }

        if(strlen($deptId) > 0 ){
            session()->put('deptId', $deptId);
        }
        if (strlen(session()->get('deptId'))>0 ) {
            $deptId = session()->get('deptId');
            $empListArray = ProformaModel::get()->where('dept_id', $deptId)->whereIn('status', $statusArray)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe, appl_date, applicant_dob")->where('dept_id', $deptId)->whereIn('status', $statusArray)->paginate(10);
            $empListprint = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe, appl_date, applicant_dob")->where('dept_id', $deptId)->whereIn('status', $statusArray)->get();
        }
        else {
            $empListArray = ProformaModel::get()->whereIn('status', $statusArray)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe, appl_date, applicant_dob")->whereIn('status', $statusArray)->paginate(10);
            $empListprint = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe, appl_date, applicant_dob")->whereIn('status', $statusArray)->get();
        }
        $Sign1 = Sign1Model::get()->toArray();
        $Sign2 = Sign2Model::get()->toArray();
        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        $Remarks = RemarksModel::get()->toArray();
        $stat = "";

        foreach ($empList as $data) {
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = "started";
                $data->status = "Incomplete";
            }

            if ($data->status == 1) {
                $stat = "submitted";
                $data->status = "Submitted";
            }
            if ($data->status == 2) {
                $stat = "verified";
                $data->status = "Verified";
            }
            if ($data->status == 3) {
                $stat = "forapproval";
                $data->status = "Put up for Approval";
            }

            if ($data->status == 4) {
                $stat = "approved";
                $data->status = "Approved";
            }
            if ($data->status == 5) {
                $stat = "appointed";
                $data->status = "Appointed";
            }
            if ($data->status == 6) {
                $stat = "order";
                $data->status = "Appointment Order";
            }


            $data->formSubStat = $stat;
        }
        return view('admin/selectDeptByDPApprove', compact("empListprint","RemarksApprove","desigListArray","Sign1", "Sign2","deptListArray", "empList", "deptId", "empListArray", "Remarks"));
    }
}


 public function submitFormApplicant(Request $request)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $desigListArray = DesignationModel::orderBy('desig_name')->get()->unique('desig_name');
        $deptId = $request->input('dept_id');
        $RemarksApprove = RemarksApproveModel::get()->toArray();
       // Session::put('deptID', $deptId);
        //dd(strlen($deptId));
        $statusArray = [1,2,3]; //status of Table Proforma
        if($getUser->role_id==5 || $getUser->role_id==6){
        $empListArray = array();
        if(session()->get('deptId') != "" && $request->input('page') == ""){
             // if($request->input('page') == ""){
            $request->session()->forget(['deptId']);
        }

        if(strlen($deptId) > 0 ){
            session()->put('deptId', $deptId);
        }
        if (strlen(session()->get('deptId'))>0 ) {
            $deptId = session()->get('deptId');
            $empListArray = ProformaModel::get()->where('dept_id', $deptId)->whereIn('status', $statusArray)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe, appl_date, applicant_dob")->where('dept_id', $deptId)->whereIn('status', $statusArray)->paginate(10);
            $empListprint = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe, appl_date, applicant_dob")->where('dept_id', $deptId)->whereIn('status', $statusArray)->get();
        }
        else {
            $empListArray = ProformaModel::get()->whereIn('status', $statusArray)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe, appl_date, applicant_dob")->whereIn('status', $statusArray)->paginate(10);
            $empListprint = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe, appl_date, applicant_dob")->whereIn('status', $statusArray)->get();
        }
        $Sign1 = Sign1Model::get()->toArray();
        $Sign2 = Sign2Model::get()->toArray();
        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        $Remarks = RemarksModel::get()->toArray();
        $stat = "";

        foreach ($empList as $data) {
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = "started";
                $data->status = "Incomplete";
            }

            if ($data->status == 1) {
                $stat = "submitted";
                $data->status = "Submitted";
            }
            if ($data->status == 2) {
                $stat = "verified";
                $data->status = "Verified";
            }
            if ($data->status == 3) {
                $stat = "forapproval";
                $data->status = "Put up for Approval";
            }

            if ($data->status == 4) {
                $stat = "approved";
                $data->status = "Approved";
            }
            if ($data->status == 5) {
                $stat = "appointed";
                $data->status = "Appointed";
            }
            if ($data->status == 6) {
                $stat = "order";
                $data->status = "Appointment Order";
            }


            $data->formSubStat = $stat;
        }
        return view('admin/viewStartEmp', compact("empListprint","RemarksApprove","desigListArray","Sign1", "Sign2","deptListArray", "empList", "deptId", "empListArray", "Remarks"));
    }
}

    public function submitFormDP(Request $request)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $deptId = $request->input('dept_id');
        $RemarksApprove = RemarksApproveModel::get()->toArray();
        //Session::put('deptID', $deptId);
        //dd(strlen($deptId));
        $empListArray = array();
        if(session()->get('deptId') != "" && $request->input('page') == ""){
            $request->session()->forget(['deptId']);
        }

        if(strlen($deptId) > 0 ){
            session()->put('deptId', $deptId);
        }
        if (strlen(session()->get('deptId'))>0 ) {
            $deptId = session()->get('deptId');
            $empListArray = ProformaModel::get()->where('dept_id', $deptId)->where('file_status', 5)->where('rejected_status', 6)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('dept_id', $deptId)->where('file_status', 5)->where('rejected_status', 6)->paginate(10);
        }
        else {
            $empListArray = ProformaModel::get()->where('file_status', 5)->where('rejected_status', 6)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('file_status', 5)->where('rejected_status', 6)->paginate(10);
        }

        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        $Remarks = RemarksModel::get()->toArray();
        $stat = "";

        foreach ($empList as $data) {
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = "started";
                $data->status = "Incomplete";
            }

            if ($data->status == 1) {
                $stat = "submitted";
                $data->status = "Submitted";
            }
            if ($data->status == 2) {
                $stat = "verified";
                $data->status = "Verified";
            }
            if ($data->status == 3) {
                $stat = "forapproval";
                $data->status = "Put up for Approval";
            }

            if ($data->status == 4) {
                $stat = "approved";
                $data->status = "Approved";
            }
            if ($data->status == 5) {
                $stat = "appointed";
                $data->status = "Appointed";
            }
            if ($data->status == 6) {
                $stat = "order";
                $data->status = "Appointment Order";
            }


            $data->formSubStat = $stat;
        }
        return view('admin/viewRevertedListDP', compact("RemarksApprove","deptListArray", "empList", "deptId", "empListArray", "Remarks"));
    }

    

    public function downloadPDF(Request $request)
    {
        $session_dept_id = session()->get('deptID');
        $deptId = $session_dept_id;
        //dd($deptId);
        $RemarksApprove = RemarksApproveModel::get()->toArray();
    
        if($deptId == null){
            $empListArray = ProformaModel::get()->where('file_status', 5)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe,appl_date, applicant_dob")->where('file_status', 5)->paginate(10);
        }
        else{
            $empListArray = ProformaModel::get()->where('dept_id', $deptId)->where('file_status', 5)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe,appl_date, applicant_dob")->where('dept_id', $deptId)->where('file_status', 5)->paginate(10);
        }
        $stat = "";

        foreach ($empList as $data) {
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = "started";
                $data->status = "Incomplete";
            }

            if ($data->status == 1) {
                $stat = "submitted";
                $data->status = "Submitted";
            }
            if ($data->status == 2) {
                $stat = "verified";
                $data->status = "Verified";
            }
            if ($data->status == 3) {
                $stat = "forapproval";
                $data->status = "Put up for Approval";
            }

            if ($data->status == 4) {
                $stat = "approved";
                $data->status = "Approved";
            }
            if ($data->status == 5) {
                $stat = "appointed";
                $data->status = "Appointed";
            }
            if ($data->status == 6) {
                $stat = "order";
                $data->status = "Appointment Order";
            }


            $data->formSubStat = $stat;
        }
       
        $html = view('admin.generatedDPpdf', ['empList' => $empList], ['empListArray' => $empListArray])->render();
      
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        $dompdf->stream('admin.generatedDPpdf', ['Attachment' => false]);
   }
    
   ///////////////////////DP NODAL// selectDeptByDPNodal at HomeController//
//    public function submitFormNODAL(Request $request)
//    {
//        $user_id = Auth::user()->id;
//        $getUser = User::get()->where('id', $user_id)->first();
//        $deptId = $request->input('dept_id');
//        $RemarksApprove = RemarksApproveModel::get()->toArray();
//       // Session::put('deptID', $deptId);
//        //dd(strlen($deptId));
//        $empListArray = array();
//        if(session()->get('deptId') != "" && $request->input('page') == ""){
//         $request->session()->forget(['deptId']);
//     }

//     if(strlen($deptId) > 0 ){
//         session()->put('deptId', $deptId);
//     }
//     if (strlen(session()->get('deptId'))>0 ) {
//         $deptId = session()->get('deptId');
//            $empListArray = ProformaModel::get()->where('dept_id', $deptId)->where('file_status', 6)->toArray();
//            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe,appl_date, applicant_dob")->where('dept_id', $deptId)->where('file_status', 6)->paginate(10);
//        }
//        else {
//            $empListArray = ProformaModel::get()->where('file_status', 6)->toArray();
//            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe,appl_date, applicant_dob")->where('file_status', 6)->paginate(10);
//        }

//        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
//        $Remarks = RemarksModel::get()->toArray();

//        $stat = "";

//     foreach ($empList as $data) {
//             if ($data->status == null && $data->form_status == 1) {
//                 $stat = "started";
//                 $data->status = "Incomplete";
//             }

//             if ($data->status == 1) {
//                 $stat = "submitted";
//                 $data->status = "Submitted";
//             }
//             if ($data->status == 2) {
//                 $stat = "verified";
//                 $data->status = "Verified";
//             }
//             if ($data->status == 3) {
//                 $stat = "forapproval";
//                 $data->status = "Put up for Approval";
//             }

//             if ($data->status == 4) {
//                 $stat = "approved";
//                 $data->status = "Approved";
//             }
//             if ($data->status == 5) {
//                 $stat = "appointed";
//                 $data->status = "Appointed";
//             }
//             if ($data->status == 6) {
//                 $stat = "order";
//                 $data->status = "Appointment Order";
//             }


//             $data->formSubStat = $stat;
//         }
//        return view('admin/selectDeptByDPNodal', compact("RemarksApprove", "deptListArray", "empList", "deptId", "empListArray", "Remarks"));
//    }

   public function downloadPDFNODAL(Request $request)
   {
       $session_dept_id = session()->get('deptID');
       $deptId = $session_dept_id;
       //dd($deptId);
       $RemarksApprove = RemarksApproveModel::get()->toArray();

   
       if($deptId == null){
           $empListArray = ProformaModel::get()->where('file_status', 6)->toArray();
           $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe,appl_date, applicant_dob")->where('file_status', 6)->paginate(10);
       }
       else{
           $empListArray = ProformaModel::get()->where('dept_id', $deptId)->where('file_status', 6)->toArray();
           $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe,appl_date, applicant_dob")->where('dept_id', $deptId)->where('file_status', 6)->paginate(10);
          
       }
       $stat = "";
       //dd($empListArray);
       foreach ($empList as $data) {
        if ($data->status == 0 && $data->form_status == 1) {
            $stat = "started";
            $data->status = "Incomplete";
        }

        if ($data->status == 1) {
            $stat = "submitted";
            $data->status = "Submitted";
        }
        if ($data->status == 2) {
            $stat = "verified";
            $data->status = "Verified";
        }
        if ($data->status == 3) {
            $stat = "forapproval";
            $data->status = "Put up for Approval";
        }

        if ($data->status == 4) {
            $stat = "approved";
            $data->status = "Approved";
        }
        if ($data->status == 5) {
            $stat = "appointed";
            $data->status = "Appointed";
        }
        if ($data->status == 6) {
            $stat = "order";
            $data->status = "Appointment Order";
        }


        $data->formSubStat = $stat;
    }
      
       $html = view('admin.generatedDPpdf', ['empList' => $empList], ['empListArray' => $empListArray])->render();
     
       $dompdf = new Dompdf();
       $dompdf->loadHtml($html);
       $dompdf->setPaper('A4', 'portrait');
       $dompdf->render();
   
       $dompdf->stream('admin.generatedDPpdf', ['Attachment' => false]);

       $request->session()->forget(['deptID']);
  }

  public function downloadPDFRejectDP(Request $request)
  {
      $session_dept_id = session()->get('deptID');
      $deptId = $session_dept_id;
      $RemarksApprove = RemarksApproveModel::get()->toArray();

  
      if($deptId == null){
          $empListArray = ProformaModel::get()->where('file_status', 5)->where('rejected_status', 6)->toArray();
          $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('file_status', 5)->where('rejected_status', 6)->paginate(10);
      }
      else{
          $empListArray = ProformaModel::get()->where('dept_id', $deptId)->where('file_status', 5)->where('rejected_status', 6)->toArray();
          $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('dept_id', $deptId)->where('file_status', 5)->where('rejected_status', 6)->paginate(10);
      }
      $stat = "";

      foreach ($empList as $data) {
        if ($data->status == 0 && $data->form_status == 1) {
            $stat = "started";
            $data->status = "Incomplete";
        }

        if ($data->status == 1) {
            $stat = "submitted";
            $data->status = "Submitted";
        }
        if ($data->status == 2) {
            $stat = "verified";
            $data->status = "Verified";
        }
        if ($data->status == 3) {
            $stat = "forapproval";
            $data->status = "Put up for Approval";
        }

        if ($data->status == 4) {
            $stat = "approved";
            $data->status = "Approved";
        }
        if ($data->status == 5) {
            $stat = "appointed";
            $data->status = "Appointed";
        }
        if ($data->status == 6) {
            $stat = "order";
            $data->status = "Appointment Order";
        }


        $data->formSubStat = $stat;
    }
      
     
      $html = view('admin.generatedDPpdf', ['empList' => $empList], ['empListArray' => $empListArray])->render();
    
      $dompdf = new Dompdf();
      $dompdf->loadHtml($html);
      $dompdf->setPaper('A4', 'portrait');
      $dompdf->render();
  
      $dompdf->stream('admin.generatedDPpdf', ['Attachment' => false]);
      $request->session()->forget(['deptID']);
 }

 public function viewEsignByDPDeptSelect(Request $request)
 {
     $user_id = Auth::user()->id;
     $getUser = User::get()->where('id', $user_id)->first();
     $deptId = $request->input('dept_id');
 
    // Session::put('deptID', $deptId);
     //dd(strlen($deptId));
   

     if(session()->get('deptId') != "" && $request->input('page') == ""){
         $request->session()->forget(['deptId']);
     }

     if(strlen($deptId) > 0 ){
         session()->put('deptId', $deptId);
     }
     if (strlen(session()->get('deptId'))>0 ) {
         $deptId = session()->get('deptId');
        
         $statusArray = [6]; // Order

         $empListArray = ProformaModel::get()->whereIn('status', $statusArray)->toArray();
         $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('dept_id', $deptId)->whereIn('status', $statusArray)->paginate(10);
        
       
     }
     else {
       
         $statusArray = [6]; // Order

      
         $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->whereIn('status', $statusArray)->paginate(10);
       
     }

     $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
   
     $stat = "";

     foreach ($empList as $data) {
         if ($data->status == 0 && $data->form_status == 1) {
             $stat = "started";
             $data->status = "Incomplete";
         }

         if ($data->status == 1) {
             $stat = "submitted";
             $data->status = "Submitted";
         }
         if ($data->status == 2) {
             $stat = "verified";
             $data->status = "Verified";
         }
         if ($data->status == 3) {
             $stat = "forapproval";
             $data->status = "Put up for Approval";
         }

         if ($data->status == 4) {
             $stat = "approved";
             $data->status = "Approved";
         }
         if ($data->status == 5) {
             $stat = "appointed";
             $data->status = "Appointed";
         }
         if ($data->status == 6) {
             $stat = "order";
             $data->status = "Appointment Order";
         }


         $data->formSubStat = $stat;
     }
     return view('admin/viewEsignByDP', compact("deptListArray", "empList", "deptId"));
 }

}
