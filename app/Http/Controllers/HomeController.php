<?php

namespace App\Http\Controllers;

use App\Models\applicants_statusModel;
use App\Models\AssemblyConstituency;
use App\Models\BankBranch;
use App\Models\DepartmentModel;
use App\Models\Designationodel;
use App\Models\District;

use App\Models\EmployeeCmis;
use App\Models\EmpFormSubmissionStatus;
use App\Models\DesignationModel;
use App\Models\EducationModel;
use App\Models\PensionEmployee;
use App\Models\EmpDocsPhotos;
use App\Models\NdcTransaction;
use App\Models\NumberGenerator;
use App\Models\NdcAuthority;
use App\Models\PensionCalculationTable;
use App\Models\RequiredParamForCalculation;
use App\Models\FamilyMembers;
use App\Models\Emolument;
use App\Models\EmpMilitaryServiceDetails;
use App\Models\EmpAutonomousBodyService;
use App\Models\EmpQualifyingSvc;
use App\Models\EmpGovtDues;
use App\Models\FormFieldMaster;
use App\Models\grade;
use App\Models\PayCommissionModel;
use App\Models\PayScale;
use App\Models\ProformaApplicants;
use App\Models\ProformaModel;
use App\Models\StateModelRegister;
use App\Models\RelationshipModel;
use App\Models\SubDivision;
use App\Models\TreasuryMoel;
use App\Models\User;
use App\Models\UserRoleModel;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Doctrine\DBAL\Driver\Mysqli\Initializer\Options;
use Exception;
use Dompdf\Dompdf;

use App\Models\entryAgeModel;
use App\Models\FileModel;
use App\Models\GenderModel;
use App\Models\NotificationModel;
use App\Models\PortalModel;
use App\Models\ProformaHistoryModel;
use App\Models\RemarksApproveModel;
use App\Models\RemarksModel;
use App\Models\Sign1Model;
use App\Models\Sign2Model;
use App\Models\TimeLineModel;
use App\Models\UoNomenclatureModel;
use App\Models\UOGenerationModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

ini_set('max_execution_time', 0);
ini_set('upload_max_filesize', '40M');
ini_set('max_input_time', 300);
ini_set('post_max_size', '40M');
ini_set('memory_limit', '512M');

// use App\Http\Controllers\EmployeeCmisController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session as FacadesSession;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Translation\Provider\NullProvider;
use App\Library\Senitizer;
use App\Models\CasteModel;
use App\Models\CountryModel;
use App\Models\DistrictModel;
use App\Models\EmpFormSubmissionStatusHistoryModel;
use App\Models\FileHistoryModel;
use App\Models\State;
use App\Models\TransferModel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');

        if (isset($_REQUEST)) {
            $_REQUEST = Senitizer::senitize($_REQUEST);
        }
    }

    // Admin Dashboard landing page after login

    public function index()
    {

        // ddo or ddoassist - ddo_code
        // hod or hodassist - field_dept_cd
        // admin or adminassist - admin_dept_cd
        $common_code = '';
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $role = $getUser->role_id;
        $post = array();
        $totalApplicants = 0;
        $ProInCompleted = 0;
        $notYetVerified = 0;
        $verificationCompleted = 0;
        $underProcess = 0;
        $ProCompleted = 0;
        try {
            if ($role == 77) {
                //   dd( $getUser->id, 'fddff' );
                $user_id1 = Auth::user()->id;
                $getUser1 = User::get()->where('id', $user_id1)->first();
                // dd( $getUser ) ;

                $getPortalName = PortalModel::where('id', 1)->first();
                //Portal name short form
                $getProjectShortForm = $getPortalName->short_form_name;
                //Application long name
                $getSoftwareName = $getPortalName->software_name;
                //session()->put( 'portal_name', $getSoftwareName );
                //this is for footer
                $getDeptName = $getPortalName->department_name;
                $getGovtName = $getPortalName->govt_name;
                $getDeveloper = $getPortalName->developed_by;

                //$status = [ 0, 6 ];
                $getStatus = ProformaModel::get()->where('uploaded_id', '=', $getUser->id)->first();
                if ($getStatus != null) {
                    if ($getStatus->status == 0) {
                        $status = 'You have not submitted your application.... Incomplete Application';
                    } elseif ($getStatus->status == 1) {
                        $status = 'Application Subitted';
                    } elseif ($getStatus->status == 2) {
                        $status = 'Verified';
                    } elseif (($getStatus->status == 3 || $getStatus->status == 4 || $getStatus->status == 5)) {
                        $status = 'Under Process';
                    } else {
                        $status = 'Appointment Given';
                    }
                } else {
                    $status = 'Not yet Applied';
                }

                //$notifications = NotificationModel::all();
                $notificationsArray = NotificationModel::get()->toArray();
                $notifications = NotificationModel::orderBy('created_at')->paginate(5);

                return view('admin/dashboard', compact('status', 'getUser1', 'notificationsArray', 'getDeveloper', 'getGovtName', 'getDeptName', 'getSoftwareName', 'getProjectShortForm', 'notifications'));
            }

            if ($role == 1) {
                $user_id1 = Auth::user()->id;
                $getUser1 = User::get()->where('id', $user_id1)->first();

                $getPortalName = PortalModel::where('id', 1)->first();
                //Portal name short form
                $getProjectShortForm = $getPortalName->short_form_name;
                //Application long name
                $getSoftwareName = $getPortalName->software_name;
                //session()->put( 'portal_name', $getSoftwareName );
                //this is for footer
                $getDeptName = $getPortalName->department_name;
                $getGovtName = $getPortalName->govt_name;
                $getDeveloper = $getPortalName->developed_by;

                $status = [0, 6];
                $getUnderProcess = ProformaModel::where('status', '!=', $status)->where('dept_id', '=', $getUser->dept_id)->get();
                $getIncomplete = ProformaModel::where('status', '=', 0)->where('dept_id', '=', $getUser->dept_id)->get();
                $ProcessCompleted = ProformaModel::where('status', '=', 6)->where('dept_id', '=', $getUser->dept_id)->get();
                //1 IS SUBMITTED 2 IS VERIFIED 3 IS UO GENERATED

                $getTotalApplicants = ProformaModel::where('dept_id', '=', $getUser->dept_id)->get();
                $ProInCompleted = count($getIncomplete);

                $verificationCompleted = ProformaModel::where('status', '=', 2)->where('dept_id', '=', $getUser->dept_id)->get();
                // removed list

                $notYetVerified = ProformaModel::where('status', '=', 1)->where('dept_id', '=', $getUser->dept_id)->get();
                // not yet activated

                $totalApplicants = count($getTotalApplicants);
                //Total

                $notYetVerified = count($notYetVerified);
                // no of retiree not yet activate
                $verificationCompleted = count($verificationCompleted);
                // no of retiree removed

                $underProcess = count($getUnderProcess);
                $ProCompleted = count($ProcessCompleted);

                $notificationsArray = NotificationModel::get()->toArray();
                $notifications = NotificationModel::orderBy('created_at')->paginate(5);

                return view('admin/dashboard', compact('getUser1', 'notificationsArray', 'ProInCompleted', 'getDeveloper', 'getGovtName', 'getDeptName', 'getSoftwareName', 'getProjectShortForm', 'notYetVerified', 'verificationCompleted', 'totalApplicants', 'underProcess', 'ProCompleted', 'notifications'));
            }

            if ($role == 2 || $role == 3 || $role == 4 || $role == 9) {
                $user_id1 = Auth::user()->id;
                $getUser1 = User::get()->where('id', $user_id1)->first();
                //dd( $getUser1 );
                $getPortalName = PortalModel::where('id', 1)->first();
                //Portal name short form
                $getProjectShortForm = $getPortalName->short_form_name;
                //Application long name
                $getSoftwareName = $getPortalName->software_name;
                //session()->put( 'portal_name', $getSoftwareName );
                //this is for footer
                $getDeptName = $getPortalName->department_name;
                $getGovtName = $getPortalName->govt_name;
                $getDeveloper = $getPortalName->developed_by;

                $status = [0, 6];
                $getUnderProcess = ProformaModel::where('status', '!=', $status)->where('dept_id', '=', $getUser->dept_id)->get();
                $getIncomplete = ProformaModel::where('status', '=', 0)->where('dept_id', '=', $getUser->dept_id)->get();
                $ProcessCompleted = ProformaModel::where('status', '=', 6)->where('dept_id', '=', $getUser->dept_id)->get();
                //1 IS SUBMITTED 2 IS VERIFIED 3 IS UO GENERATED

                $getTotalApplicants = ProformaModel::where('dept_id', '=', $getUser->dept_id)->get();
                $ProInCompleted = count($getIncomplete);

                $verificationCompleted = ProformaModel::where('status', '=', 2)->where('dept_id', '=', $getUser->dept_id)->get();
                // removed list

                $notYetVerified = ProformaModel::where('status', '=', 1)->where('dept_id', '=', $getUser->dept_id)->get();
                // not yet activated

                $totalApplicants = count($getTotalApplicants);
                //Total

                $notYetVerified = count($notYetVerified);
                // no of retiree not yet activate
                $verificationCompleted = count($verificationCompleted);
                // no of retiree removed

                $underProcess = count($getUnderProcess);
                $ProCompleted = count($ProcessCompleted);

                $notificationsArray = NotificationModel::get()->toArray();
                $notifications = NotificationModel::orderBy('created_at')->paginate(5);

                return view('admin/dashboard', compact('getUser1', 'notificationsArray', 'ProInCompleted', 'getDeveloper', 'getGovtName', 'getDeptName', 'getSoftwareName', 'getProjectShortForm', 'notYetVerified', 'verificationCompleted', 'totalApplicants', 'underProcess', 'ProCompleted', 'notifications'));
            }

            if ($role == 999 || $role == 5 || $role == 6 || $role == 8) {
                $user_id1 = Auth::user()->id;
                $getUser1 = User::get()->where('id', $user_id1)->first();

                $getPortalName = PortalModel::where('id', 1)->first();
                //Portal name short form
                $getProjectShortForm = $getPortalName->short_form_name;
                //Application long name
                $getSoftwareName = $getPortalName->software_name;
                //session()->put( 'portal_name', $getSoftwareName );
                //this is for footer
                $getDeptName = $getPortalName->department_name;
                $getGovtName = $getPortalName->govt_name;
                $getDeveloper = $getPortalName->developed_by;

                $status = [0, 6];
                $getUnderProcess = ProformaModel::where('status', '!=', $status)->get();
                $getIncomplete = ProformaModel::where('status', '=', 0)->get();
                $ProcessCompleted = ProformaModel::where('status', '=', 6)->get();
                //1 IS SUBMITTED 2 IS VERIFIED 3 IS UO GENERATED

                $getTotalApplicants = ProformaModel::get();
                $ProInCompleted = count($getIncomplete);

                $verificationCompleted = ProformaModel::where('status', '=', 2)->get();
                // removed list

                $notYetVerified = ProformaModel::where('status', '=', 1)->get();
                // not yet activated

                $totalApplicants = count($getTotalApplicants);
                //Total

                $notYetVerified = count($notYetVerified);
                // no of retiree not yet activate
                $verificationCompleted = count($verificationCompleted);
                // no of retiree removed

                $underProcess = count($getUnderProcess);
                $ProCompleted = count($ProcessCompleted);

                $notificationsArray = NotificationModel::get()->toArray();
                $notifications = NotificationModel::orderBy('created_at')->paginate(5);

                return view('admin/dashboard', compact('getUser1', 'notificationsArray', 'ProInCompleted', 'getDeveloper', 'getGovtName', 'getDeptName', 'getSoftwareName', 'getProjectShortForm', 'notYetVerified', 'verificationCompleted', 'totalApplicants', 'underProcess', 'ProCompleted', 'notifications'));
            }
        } catch (Exception $e) {

            return response()->json([
                'status' => 0,
                'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                //'errors' => $e->getMessage()
            ]);
        }
    }

    public function getRemovedEmpList()
    {
        // $cmis_emp_controller = new EmployeeCmisController;
        // $removed_emp_list = EmployeeCmis::get()->where( 'status', 2 )->toArray();
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $removed_emp_list = EmployeeCmis::where('status', '=', 2)->where('form_status', '=', 2)->paginate(10);
        // $removed_emp_list = EmployeeCmis::where( 'status', '=', 2 )->paginate( 10 );

        if (count($removed_emp_list) == 0) {
            $removed_emp_list = null;
        }
        return view('admin/removedEmpLists', compact('removed_emp_list'));
    }

 public function viewApplicantsForVerificationSearch(Request $request)
    {
        try {
            $empListArray = null;
            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $Remarks = RemarksModel::get()->toArray();
            $RemarksApprove = RemarksApproveModel::get()->toArray();
            $tempEmpList = null;

            $file_status_array=[1, 2]; 
            $statusArray = [1,2,3,9]; 

            $empListArray = ProformaModel::get()->where('upload_status', 1)->where('dept_id', $getUser->dept_id)->where('form_status', 1)->whereIn('status', $statusArray)->whereIn('file_status', $file_status_array)->where('rejected_status', '<=', 1)->toArray();
            // Change for DIHAS below
            $empList = ProformaModel::where('form_status', 1)->where('upload_status', 1)->where('dept_id', $getUser->dept_id)->whereIn('status', $statusArray)->whereIn('file_status', $file_status_array)->where('rejected_status', '<=', 1)->paginate(10);
            if ($request->searchItem != null || trim($request->searchItem) != '') {
                $empListArray = ProformaModel::get()->where('upload_status', 1)->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('ein', $request->searchItem)->whereIn('status', $statusArray)->whereIn('file_status', $file_status_array)->where('rejected_status', '<=', 1)->toArray();
                $empList = ProformaModel::orderByRaw("expire_on_duty = 'no',deceased_doe,appl_date, applicant_dob")->where('upload_status', 1)->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('ein', $request->searchItem)->whereIn('status', $statusArray)->whereIn('file_status', $file_status_array)->where('rejected_status', '<=', 1)->paginate(10);
                $tempEmpList = $empListArray;
                if (count($tempEmpList) == 0) {
                    $tempEmpList = 0;

                    return view('admin/viewApplicantsForVerification', compact('empList', 'empListArray', 'Remarks', 'RemarksApprove'));
                }
            }
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verifieddp';
                    $data->status = 'Verified by DP';
                }
                if ($data->status == 9) {
                    $stat = 'verifieddept';
                    $data->status = 'Verified by Department';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }
                if ($data->status == 7) {
                    $stat = 'signed';
                    $data->status = 'Signed by DP';
                }
                if ($data->status == 8) {
                    $stat = 'transfer';
                    $data->status = 'Transferred';
                }

                $data->formSubStat = $stat;
            }
            Session::put('einsearch', $request->searchItem);

            return view('admin/viewApplicantsForVerification', compact('RemarksApprove', 'empList', 'empListArray', 'Remarks'));
        } catch (Exception $e) {

            return response()->json([
                'status' => 0,
                'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                //'errors' => $e->getMessage()
            ]);
        }
    }

    // Submitted Applicants list

    public function viewApplicantsForVerification(Request $request)
    {
        // Change for DIHAS below
        try {
            $request->session()->forget(['ein', 'ein', 'einsearch']);

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();

            $Remarks = RemarksModel::get()->toArray();
            $RemarksApprove = RemarksApproveModel::get()->toArray();
            
            $file_status_array=[1, 2]; 
            $statusArray = [1,2,3,9]; 
      
            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('upload_status', 1)->where('form_status', 1)->whereIn('status', $statusArray)->whereIn('file_status', $file_status_array)->where('rejected_status', '<=', 1)->toArray();

            $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('upload_status', 1)->where('dept_id', $getUser->dept_id)->where('form_status', 1)->whereIn('status', $statusArray)->whereIn('file_status', $file_status_array)->where('rejected_status', '<=', 1)->paginate(6);
            //dd( $empList );
            //expire_on_duty if yes top priority

            $stat = '';

            foreach ($empList as $data) {
                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verifieddp';
                    $data->status = 'Verified by DP';
                }
                if ($data->status == 9) {
                    $stat = 'verifieddept';
                    $data->status = 'Verified by Department';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }
                if ($data->status == 7) {
                    $stat = 'signed';
                    $data->status = 'Signed by DP';
                }
                if ($data->status == 8) {
                    $stat = 'transfer';
                    $data->status = 'Transferred';
                }

                $data->formSubStat = $stat;
            }

            return view('admin/viewApplicantsForVerification', compact('RemarksApprove', 'empList', 'empListArray', 'Remarks'));
        } catch (Exception $e) {

            return response()->json([
                'status' => 0,
                'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                //'errors' => $e->getMessage()
            ]);
        }
    }

    // search

    public function viewStartEmpSearch(Request $request)
    {
        try {
            $empListArray = null;
            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $Remarks = RemarksModel::get()->toArray();
            $RemarksApprove = RemarksApproveModel::get()->toArray();
            $tempEmpList = null;
            $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
            $file_status_array=[1, 5];
             $statusArray = [1,2,3]; //status of Table Proforma
            // $rejected_status = [ null, 1 ];
           //where('upload_status', 1)->where('form_status', 1)->where('file_status', 1)
            //$empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 1)->where('rejected_status', '<=', 1)->toArray();
             $empListArray = ProformaModel::get()->whereIn('status', $statusArray)->where('upload_status', 1)->where('form_status', 1)->whereIn('file_status', $file_status_array)->where('rejected_status', '<=', 1)->toArray();
            // Change for DIHAS below
            //$empList = ProformaModel::where('form_status', 1)->where('dept_id', $getUser->dept_id)->where('file_status', 1)->where('rejected_status', '<=', 1)->paginate(10);
             $empList = ProformaModel::where('upload_status', 1)->where('form_status', 1)->whereIn('file_status', $file_status_array)->whereIn('status', $statusArray)->where('rejected_status', '<=', 1)->paginate(10);
            if ($request->searchItem != null || trim($request->searchItem) != '') {
                //$empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('ein', $request->searchItem)->where('file_status', 1)->where('rejected_status', '<=', 1)->toArray();
                 $empListArray = ProformaModel::get()->whereIn('status', $statusArray)->where('upload_status', 1)->where('form_status', 1)->whereIn('file_status', $file_status_array)->where('ein', $request->searchItem)->where('rejected_status', '<=', 1)->toArray();
                $empList = ProformaModel::orderByRaw("expire_on_duty = 'no',deceased_doe,appl_date, applicant_dob")->whereIn('status', $statusArray)->where('upload_status', 1)->where('form_status', 1)->whereIn('file_status', $file_status_array)->where('ein', $request->searchItem)->where('rejected_status', '<=', 1)->paginate(10);
                $tempEmpList = $empListArray;
                if (count($tempEmpList) == 0) {
                    $tempEmpList = 0;

                    return view('admin/viewStartEmp', compact('deptListArray','empList', 'empListArray', 'Remarks', 'RemarksApprove'));
                }
            }
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verifieddp';
                    $data->status = 'Verified by DP';
                }
                if ($data->status == 9) {
                    $stat = 'verifieddept';
                    $data->status = 'Verified by Department';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }
                if ($data->status == 7) {
                    $stat = 'signed';
                    $data->status = 'Signed by DP';
                }
                if ($data->status == 8) {
                    $stat = 'transfer';
                    $data->status = 'Transferred';
                }

                $data->formSubStat = $stat;
            }
            Session::put('einsearch', $request->searchItem);

            return view('admin/viewStartEmp', compact('deptListArray','RemarksApprove', 'empList', 'empListArray', 'Remarks'));
        } catch (Exception $e) {

            return response()->json([
                'status' => 0,
                'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                //'errors' => $e->getMessage()
            ]);
        }
    }

    // Submitted Applicants list

    public function viewStartEmp(Request $request)
    {
        // Change for DIHAS below
        try {
            $request->session()->forget(['ein', 'ein', 'einsearch']);

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();

            $Remarks = RemarksModel::get()->toArray();
            $RemarksApprove = RemarksApproveModel::get()->toArray();
            // $status = [ 1, 2 ];
            $file_status_array=[1, 5];
            $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
            $statusArray = [1,2,3]; //status of Table Proforma
            //$empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 1)->where('rejected_status', '<=', 1)->toArray();
              $empListArray = ProformaModel::get()->whereIn('status', $statusArray)->where('upload_status', 1)->where('form_status', 1)->whereIn('file_status', $file_status_array)->where('rejected_status', '<=', 1)->toArray();

            //$empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 1)->where('rejected_status', '<=', 1)->paginate(6);
             $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->whereIn('status', $statusArray)->where('upload_status', 1)->where('form_status', 1)->whereIn('file_status', $file_status_array)->where('rejected_status', '<=', 1)->paginate(6);
            //dd( $empList );
            //expire_on_duty if yes top priority
            $empList = $empList->map(function($empItem, $index){
            //First dynamically assigning the seniority number (Sl No)
            $empItem->slNo = $index + 1;
            return $empItem;
        });
        
        // ->filter(function($empItem) use ($user_id){
        //     //Filter only the logged in (authenticated) user
        //     return($empItem->uploaded_id == $user_id);
        // });
        
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verifieddp';
                    $data->status = 'Verified by DP';
                }
                if ($data->status == 9) {
                    $stat = 'verifieddept';
                    $data->status = 'Verified by Department';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }
                if ($data->status == 7) {
                    $stat = 'signed';
                    $data->status = 'Signed by DP';
                }
                if ($data->status == 8) {
                    $stat = 'transfer';
                    $data->status = 'Transferred';
                }

                $data->formSubStat = $stat;
            }

            return view('admin/viewStartEmp', compact('deptListArray','RemarksApprove', 'empList', 'empListArray', 'Remarks'));
        } catch (Exception $e) {

            return response()->json([
                'status' => 0,
                'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                //'errors' => $e->getMessage()
            ]);
        }
    }
    ////////////////////////////////////////////////////////////////////

    public function downloadPDFAppl()
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        if ($getUser->role_id == 1) {
            $Remarks = RemarksModel::get()->toArray();
            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 1)->where('rejected_status', '<=', 1)->toArray();
            $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 1)->where('rejected_status', '<=', 1)->paginate(10);
            //expire_on_duty if yes top priority
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Verified by DP';
                }
                if ($data->status == 9) {
                    $stat = 'verified';
                    $data->status = 'Verified by Department';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }

                $data->formSubStat = $stat;
            }

            $html = view('admin.generatedHODpdf', ['empList' => $empList], ['empListArray' => $empListArray], ['Remarks' => $Remarks])->render();
            //return $empList;
            // dd( $html );
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();
          //  $dompdf->stream('admin.generatedHODpdf', ['Attachment' => false]);
        }
        if ($getUser->role_id == 2) {
            $Remarks = RemarksModel::get()->toArray();
            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 2)->where('rejected_status', '<=', 1)->toArray();
            $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 2)->where('rejected_status', '<=', 1)->paginate(10);
            //expire_on_duty if yes top priority
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Verified by DP';
                }
                if ($data->status == 9) {
                    $stat = 'verified';
                    $data->status = 'Verified by Department';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }

                $data->formSubStat = $stat;
            }

            $html = view('admin.generatedHODpdf', ['empList' => $empList], ['empListArray' => $empListArray], ['Remarks' => $Remarks])->render();
            //return $empList;
            // dd( $html );
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();
           // $dompdf->stream('admin.generatedHODpdf', ['Attachment' => false]);
        }
        if ($getUser->role_id == 3) {
            $Remarks = RemarksModel::get()->toArray();
            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 3)->where('rejected_status', '<=', 1)->toArray();
            $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 3)->where('rejected_status', '<=', 1)->paginate(10);
            //expire_on_duty if yes top priority
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Verified';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }

                $data->formSubStat = $stat;
            }

            $html = view('admin.generatedPDFStatus', ['empList' => $empList], ['empListArray' => $empListArray], ['Remarks' => $Remarks])->render();
            //return $empList;
            // dd( $html );
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();
           // $dompdf->stream('admin.generatedPDFStatus', ['Attachment' => false]);
        }
        if ($getUser->role_id == 4) {
            $Remarks = RemarksModel::get()->toArray();
            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 4)->where('rejected_status', '<=', 1)->toArray();
            $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 4)->where('rejected_status', '<=', 1)->paginate(10);
            //expire_on_duty if yes top priority
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Verified';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }

                $data->formSubStat = $stat;
            }

            $html = view('admin.generatedPDFStatus', ['empList' => $empList], ['empListArray' => $empListArray], ['Remarks' => $Remarks])->render();
            //return $empList;
            // dd( $html );
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();
           // $dompdf->stream('admin.generatedPDFStatus', ['Attachment' => false]);
        }
    }

    ////////////////////////////////////////////////////////////////////

    public function downloadPDFApplView()
        {

              $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();

        if (Session::has('einsearch')) {
            // Get data from session
            $einsearch = Session::get('einsearch');

          
            if ($getUser->role_id == 1) {
                $Remarks = RemarksModel::get()->toArray();
                $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 1)->where('rejected_status', '<=', 1)->toArray();
                $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 1)->where('ein', $einsearch)->where('rejected_status', '<=', 1)->paginate(10);
                //expire_on_duty if yes top priority
                $stat = '';

                foreach ($empList as $data) {
                    if ($data->status == 0 && $data->form_status == 1) {
                        $stat = 'started';
                        $data->status = 'Incomplete';
                    }

                    if ($data->status == 1) {
                        $stat = 'submitted';
                        $data->status = 'Submitted';
                    }
                    if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Verified by DP';
                }
                if ($data->status == 9) {
                    $stat = 'verified';
                    $data->status = 'Verified by Department';
                }
                    if ($data->status == 3) {
                        $stat = 'forapproval';
                        $data->status = 'Put up for Approval';
                    }

                    if ($data->status == 4) {
                        $stat = 'approved';
                        $data->status = 'Approved';
                    }
                    if ($data->status == 5) {
                        $stat = 'appointed';
                        $data->status = 'Appointed';
                    }
                    if ($data->status == 6) {
                        $stat = 'order';
                        $data->status = 'Appointment Order';
                    }
                    if ($data->status == 7) {
                        $stat = 'signed';
                        $data->status = 'Signed by DP';
                    }
                    if ($data->status == 8) {
                        $stat = 'transfer';
                        $data->status = 'Transferred';
                    }

                    $data->formSubStat = $stat;
                }

                $html = view('admin.generatedHODpdf', ['empList' => $empList], ['empListArray' => $empListArray])->render();
                //return $empList;
                // dd( $html );
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $dompdf->stream();
               // $dompdf->stream('admin.generatedHODpdf', ['Attachment' => false]);
            }
            if ($getUser->role_id == 2) {
                $Remarks = RemarksModel::get()->toArray();
                $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 2)->where('rejected_status', '=', 0)->toArray();
                $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 2)->where('ein', $einsearch)->where('rejected_status', '=', 0)->paginate(10);
                //expire_on_duty if yes top priority
                $stat = '';

                foreach ($empList as $data) {
                    if ($data->status == 0 && $data->form_status == 1) {
                        $stat = 'started';
                        $data->status = 'Incomplete';
                    }

                    if ($data->status == 1) {
                        $stat = 'submitted';
                        $data->status = 'Submitted';
                    }
                    if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Verified by DP';
                }
                if ($data->status == 9) {
                    $stat = 'verified';
                    $data->status = 'Verified by Department';
                }
                    if ($data->status == 3) {
                        $stat = 'forapproval';
                        $data->status = 'Put up for Approval';
                    }

                    if ($data->status == 4) {
                        $stat = 'approved';
                        $data->status = 'Approved';
                    }
                    if ($data->status == 5) {
                        $stat = 'appointed';
                        $data->status = 'Appointed';
                    }
                    if ($data->status == 6) {
                        $stat = 'order';
                        $data->status = 'Appointment Order';
                    }
                    if ($data->status == 7) {
                        $stat = 'signed';
                        $data->status = 'Signed by DP';
                    }
                    if ($data->status == 8) {
                        $stat = 'transfer';
                        $data->status = 'Transferred';
                    }

                    $data->formSubStat = $stat;
                }

                $html = view('admin.generatedHODpdf', ['empList' => $empList], ['empListArray' => $empListArray], ['Remarks' => $Remarks])->render();
                //return $empList;
                // dd( $html );
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $dompdf->stream();
               // $dompdf->stream('admin.generatedHODpdf', ['Attachment' => false]);
            }
            if ($getUser->role_id == 3) {
                $Remarks = RemarksModel::get()->toArray();
                $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 3)->where('rejected_status', '=', 0)->toArray();
                $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 3)->where('rejected_status', '=', 0)->where('ein', $einsearch)->paginate(10);
                //expire_on_duty if yes top priority
                $stat = '';

                foreach ($empList as $data) {
                    if ($data->status == 0 && $data->form_status == 1) {
                        $stat = 'started';
                        $data->status = 'Incomplete';
                    }

                    if ($data->status == 1) {
                        $stat = 'submitted';
                        $data->status = 'Submitted';
                    }
                    if ($data->status == 2) {
                        $stat = 'verified';
                        $data->status = 'Verified';
                    }
                    if ($data->status == 3) {
                        $stat = 'forapproval';
                        $data->status = 'Put up for Approval';
                    }

                    if ($data->status == 4) {
                        $stat = 'approved';
                        $data->status = 'Approved';
                    }
                    if ($data->status == 5) {
                        $stat = 'appointed';
                        $data->status = 'Appointed';
                    }
                    if ($data->status == 6) {
                        $stat = 'order';
                        $data->status = 'Appointment Order';
                    }
                    if ($data->status == 7) {
                        $stat = 'signed';
                        $data->status = 'Signed by DP';
                    }
                    if ($data->status == 8) {
                        $stat = 'transfer';
                        $data->status = 'Transferred';
                    }

                    $data->formSubStat = $stat;
                }

                $html = view('admin.generatedPDFStatus', ['empList' => $empList], ['empListArray' => $empListArray], ['Remarks' => $Remarks])->render();
                //return $empList;
                // dd( $html );
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $dompdf->stream();
               // $dompdf->stream('admin.generatedPDFStatus', ['Attachment' => false]);
            }
            if ($getUser->role_id == 4) {
                $Remarks = RemarksModel::get()->toArray();
                $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 4)->where('rejected_status', 0)->toArray();
                $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 4)->where('rejected_status', 0)->where('ein', $einsearch)->paginate(10);
                //expire_on_duty if yes top priority
                $stat = '';

                foreach ($empList as $data) {
                    if ($data->status == 0 && $data->form_status == 1) {
                        $stat = 'started';
                        $data->status = 'Incomplete';
                    }

                    if ($data->status == 1) {
                        $stat = 'submitted';
                        $data->status = 'Submitted';
                    }
                    if ($data->status == 2) {
                        $stat = 'verified';
                        $data->status = 'Verified';
                    }
                    if ($data->status == 3) {
                        $stat = 'forapproval';
                        $data->status = 'Put up for Approval';
                    }

                    if ($data->status == 4) {
                        $stat = 'approved';
                        $data->status = 'Approved';
                    }
                    if ($data->status == 5) {
                        $stat = 'appointed';
                        $data->status = 'Appointed';
                    }
                    if ($data->status == 6) {
                        $stat = 'order';
                        $data->status = 'Appointment Order';
                    }
                    if ($data->status == 7) {
                        $stat = 'signed';
                        $data->status = 'Signed by DP';
                    }
                    if ($data->status == 8) {
                        $stat = 'transfer';
                        $data->status = 'Transferred';
                    }

                    $data->formSubStat = $stat;
                }

                $html = view('admin.generatedPDFStatus', ['empList' => $empList], ['empListArray' => $empListArray], ['Remarks' => $Remarks])->render();
                //return $empList;
                // dd( $html );
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $dompdf->stream();
               // $dompdf->stream('admin.generatedPDFStatus', ['Attachment' => false]);
            }
        } else {
            //////not search

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();

            if ($getUser->role_id == 1) {
                $Remarks = RemarksModel::get()->toArray();
                $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 1)->where('rejected_status', '<=', 1)->toArray();
                $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 1)->where('rejected_status', '<=', 1)->paginate(10);
                //expire_on_duty if yes top priority
                $stat = '';

                foreach ($empList as $data) {
                    if ($data->status == 0 && $data->form_status == 1) {
                        $stat = 'started';
                        $data->status = 'Incomplete';
                    }

                    if ($data->status == 1) {
                        $stat = 'submitted';
                        $data->status = 'Submitted';
                    }
                    if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Verified by DP';
                }
                if ($data->status == 9) {
                    $stat = 'verified';
                    $data->status = 'Verified by Department';
                }
                    if ($data->status == 3) {
                        $stat = 'forapproval';
                        $data->status = 'Put up for Approval';
                    }

                    if ($data->status == 4) {
                        $stat = 'approved';
                        $data->status = 'Approved';
                    }
                    if ($data->status == 5) {
                        $stat = 'appointed';
                        $data->status = 'Appointed';
                    }
                    if ($data->status == 6) {
                        $stat = 'order';
                        $data->status = 'Appointment Order';
                    }
                    if ($data->status == 7) {
                        $stat = 'signed';
                        $data->status = 'Signed by DP';
                    }
                    if ($data->status == 8) {
                        $stat = 'transfer';
                        $data->status = 'Transferred';
                    }

                    $data->formSubStat = $stat;
                }

                $html = view('admin.generatedHODpdf', ['empList' => $empList], ['empListArray' => $empListArray])->render();
                //return $empList;
                // dd( $html );
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $dompdf->stream();
              //  $dompdf->stream('admin.generatedHODpdf', ['Attachment' => false]);
            }
            if ($getUser->role_id == 2) {
                $Remarks = RemarksModel::get()->toArray();
                $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 2)->where('rejected_status', '=', 0)->toArray();
                $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 2)->where('rejected_status', '=', 0)->paginate(10);
                //expire_on_duty if yes top priority
                $stat = '';

                foreach ($empList as $data) {
                    if ($data->status == 0 && $data->form_status == 1) {
                        $stat = 'started';
                        $data->status = 'Incomplete';
                    }

                    if ($data->status == 1) {
                        $stat = 'submitted';
                        $data->status = 'Submitted';
                    }
                    if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Verified by DP';
                }
                if ($data->status == 9) {
                    $stat = 'verified';
                    $data->status = 'Verified by Department';
                }
                    if ($data->status == 3) {
                        $stat = 'forapproval';
                        $data->status = 'Put up for Approval';
                    }

                    if ($data->status == 4) {
                        $stat = 'approved';
                        $data->status = 'Approved';
                    }
                    if ($data->status == 5) {
                        $stat = 'appointed';
                        $data->status = 'Appointed';
                    }
                    if ($data->status == 6) {
                        $stat = 'order';
                        $data->status = 'Appointment Order';
                    }
                    if ($data->status == 7) {
                        $stat = 'signed';
                        $data->status = 'Signed by DP';
                    }
                    if ($data->status == 8) {
                        $stat = 'transfer';
                        $data->status = 'Transferred';
                    }

                    $data->formSubStat = $stat;
                }

                $html = view('admin.generatedHODpdf', ['empList' => $empList], ['empListArray' => $empListArray], ['Remarks' => $Remarks])->render();
                //return $empList;
                // dd( $html );
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $dompdf->stream();
              //  $dompdf->stream('admin.generatedHODpdf', ['Attachment' => false]);
            }
            if ($getUser->role_id == 3) {
                $Remarks = RemarksModel::get()->toArray();
                $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 3)->where('rejected_status', '=', 0)->toArray();
                $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 3)->where('rejected_status', '=', 0)->paginate(10);
                //expire_on_duty if yes top priority
                $stat = '';

                foreach ($empList as $data) {
                    if ($data->status == 0 && $data->form_status == 1) {
                        $stat = 'started';
                        $data->status = 'Incomplete';
                    }

                    if ($data->status == 1) {
                        $stat = 'submitted';
                        $data->status = 'Submitted';
                    }
                    if ($data->status == 2) {
                        $stat = 'verified';
                        $data->status = 'Verified';
                    }
                    if ($data->status == 3) {
                        $stat = 'forapproval';
                        $data->status = 'Put up for Approval';
                    }

                    if ($data->status == 4) {
                        $stat = 'approved';
                        $data->status = 'Approved';
                    }
                    if ($data->status == 5) {
                        $stat = 'appointed';
                        $data->status = 'Appointed';
                    }
                    if ($data->status == 6) {
                        $stat = 'order';
                        $data->status = 'Appointment Order';
                    }
                    if ($data->status == 7) {
                        $stat = 'signed';
                        $data->status = 'Signed by DP';
                    }
                    if ($data->status == 8) {
                        $stat = 'transfer';
                        $data->status = 'Transferred';
                    }

                    $data->formSubStat = $stat;
                }

                $html = view('admin.generatedPDFStatus', ['empList' => $empList], ['empListArray' => $empListArray], ['Remarks' => $Remarks])->render();
                //return $empList;
                // dd( $html );
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $dompdf->stream();
               // $dompdf->stream('admin.generatedPDFStatus', ['Attachment' => false]);
            }
            if ($getUser->role_id == 4) {
                $Remarks = RemarksModel::get()->toArray();
                $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 4)->where('rejected_status', 0)->toArray();
                $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 4)->where('rejected_status', 0)->paginate(10);
                //expire_on_duty if yes top priority
                $stat = '';

                foreach ($empList as $data) {
                    if ($data->status == 0 && $data->form_status == 1) {
                        $stat = 'started';
                        $data->status = 'Incomplete';
                    }

                    if ($data->status == 1) {
                        $stat = 'submitted';
                        $data->status = 'Submitted';
                    }
                    if ($data->status == 2) {
                        $stat = 'verified';
                        $data->status = 'Verified';
                    }
                    if ($data->status == 3) {
                        $stat = 'forapproval';
                        $data->status = 'Put up for Approval';
                    }

                    if ($data->status == 4) {
                        $stat = 'approved';
                        $data->status = 'Approved';
                    }
                    if ($data->status == 5) {
                        $stat = 'appointed';
                        $data->status = 'Appointed';
                    }
                    if ($data->status == 6) {
                        $stat = 'order';
                        $data->status = 'Appointment Order';
                    }
                    if ($data->status == 7) {
                        $stat = 'signed';
                        $data->status = 'Signed by DP';
                    }
                    if ($data->status == 8) {
                        $stat = 'transfer';
                        $data->status = 'Transferred';
                    }


                    $data->formSubStat = $stat;
                }

                $html = view('admin.generatedPDFStatus', ['empList' => $empList], ['empListArray' => $empListArray], ['Remarks' => $Remarks])->render();
                //return $empList;
                // dd( $html );
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $dompdf->stream();
              //  $dompdf->stream('admin.generatedPDFStatus', ['Attachment' => false]);
            }
        }
    }
    ////////////////////////Rejected list view for HOD Assistant/////////////////////////

    // search

    public function viewRejectedListHODAssistSearch(Request $request)
    {
        $empListArray = null;
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $Remarks = RemarksModel::get()->toArray();

        $tempEmpList = null;
        $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 1)->where('rejected_status', 2)->toArray();
        // Change for DIHAS below
        $empList = ProformaModel::where('form_status', 1)->where('dept_id', $getUser->dept_id)->where('file_status', 1)->where('rejected_status', 2)->paginate(10);

        if ($request->searchItem != null || trim($request->searchItem) != '') {
            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('ein', $request->searchItem)->where('file_status', 1)->where('rejected_status', 2)->toArray();
            $empList = ProformaModel::orderByRaw("expire_on_duty = 'no',deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('ein', $request->searchItem)->where('file_status', 1)->where('rejected_status', 2)->paginate(10);
            $tempEmpList = $empListArray;
            if (count($tempEmpList) == 0) {
                $tempEmpList = 0;
                return view('admin/viewRejectedListHODAssist', compact('empList', 'empListArray', 'Remarks'));
            }
        }
        $stat = '';
        // $rejectBy1 = '';
        foreach ($empList as $data) {

            if ($data->status == 0 && $data->rejected_status != 0) {
                $stat = 'started';
                $data->status = 'Incomplete / Reverted';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }

            $data->formSubStat = $stat;
        }
        Session::put('einsearch', $request->searchItem);
        return view('admin/viewRejectedListHODAssist', compact('empList', 'empListArray', 'Remarks'));
    }

    // Submitted Applicants list

    public function viewRejectedListHODAssist(Request $request)
    {
        // Change for DIHAS below

        $request->session()->forget(['ein', 'einsearch']);

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $Remarks = RemarksModel::get()->toArray();

        $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 1)->where('rejected_status', 2)->toArray();

        $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 1)->where('rejected_status', 2)->paginate(10);

        //$tempArray = ProformaModel::get()->where( 'dept_id', $getUser->dept_id )->where( 'form_status', 1 )->where( 'file_status', 1 )->where( 'rejected_status', 2 )->first();

        //expire_on_duty if yes top priority

        $stat = '';
        // $rejectBy1 = '';
        foreach ($empList as $data) {

            if ($data->status == 0 && $data->rejected_status != 0) {
                $stat = 'started';
                $data->status = 'Incomplete / Reverted';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }

            $data->formSubStat = $stat;
        }

        return view('admin/viewRejectedListHODAssist', compact('empList', 'empListArray', 'Remarks'));
    }

    public function downloadPDFReject()
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        if (Session::has('einsearch')) {
            // Get data from session
            $einsearch = Session::get('einsearch');
            $Remarks = RemarksModel::get()->toArray();

            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 1)->where('rejected_status', 2)->toArray();

            $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 1)->where('rejected_status', 2)->where('ein', $einsearch)->paginate(10);

            //expire_on_duty if yes top priority

            // dd( $empList->toArray() );
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status == 0 && $data->rejected_status != 0) {
                    $stat = 'started';
                    $data->status = 'Incomplete / Reverted';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }

                $data->formSubStat = $stat;
            }

            $html = view('admin.generatedRevertPDF', ['empList' => $empList], ['empListArray' => $empListArray], ['Remarks' => $Remarks])->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();
           // $dompdf->stream('admin.generatedRevertPDF', ['Attachment' => false]);
        } else {

            $Remarks = RemarksModel::get()->toArray();

            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 1)->where('rejected_status', 2)->toArray();

            $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 1)->where('rejected_status', 2)->paginate(10);

            //expire_on_duty if yes top priority

            // dd( $empList->toArray() );
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status == 0 && $data->rejected_status != 0) {
                    $stat = 'started';
                    $data->status = 'Incomplete / Reverted';
                }

                $data->formSubStat = $stat;
            }

            $html = view('admin.generatedRevertPDF', ['empList' => $empList], ['empListArray' => $empListArray], ['Remarks' => $Remarks])->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();
           // $dompdf->stream('admin.generatedRevertPDF', ['Attachment' => false]);
        }
    }
    ///////////////////AD ASSISTANT REVERT SEEN BY HOD/////////////////////////////////////
    // search

    public function viewRejectedListHODSearch(Request $request)
    {
        try {
            $empListArray = null;
            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();

            //dd( $user_id );

            $Remarks = RemarksModel::get()->toArray();

            $tempEmpList = null;
            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 2)->where('rejected_status', 3)->toArray();
            // Change for DIHAS below
            $empList = ProformaModel::where('form_status', 1)->where('dept_id', $getUser->dept_id)->where('file_status', 2)->where('rejected_status', 3)->paginate(10);

            if ($request->searchItem != null || trim($request->searchItem) != '') {
                $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('ein', $request->searchItem)->where('file_status', 2)->where('rejected_status', 3)->toArray();
                $empList = ProformaModel::orderByRaw("expire_on_duty = 'no',deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('ein', $request->searchItem)->where('file_status', 2)->where('rejected_status', 3)->paginate(10);
                $tempEmpList = $empListArray;
                if (count($tempEmpList) == 0) {
                    $tempEmpList = 0;
                    return view('admin/viewRejectedListHOD', compact('empList', 'empListArray', 'Remarks'));
                }
            }

            $stat = '';
            // $rejectBy1 = '';
            foreach ($empList as $data) {

                if ($data->status != 0 && $data->rejected_status != 0) {
                    //status will be null when reverted by hod only
                    $stat = 'started';
                    $data->status = 'Incomplete / Reverted';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }

                $data->formSubStat = $stat;
            }
            Session::put('einsearch', $request->searchItem);
            return view('admin/viewRejectedListHOD', compact('empList', 'empListArray', 'Remarks'));
        } catch (Exception $e) {

            return response()->json([
                'status' => 0,
                'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                //'errors' => $e->getMessage()
            ]);
        }
    }

    // Submitted Applicants list

    public function viewRejectedListHOD(Request $request)
    {
        try {
            $request->session()->forget(['ein', 'einsearch']);

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();

            $Remarks = RemarksModel::get()->toArray();

            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 2)->where('rejected_status', 3)->toArray();

            $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 2)->where('rejected_status', 3)->paginate(10);

            $stat = '';
            // $rejectBy1 = '';
            foreach ($empList as $data) {

                if ($data->status != 0 && $data->rejected_status != 0) {
                    $stat = 'started';
                    $data->status = 'Incomplete / Reverted';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }

                $data->formSubStat = $stat;
            }

            return view('admin/viewRejectedListHOD', compact('empList', 'empListArray', 'Remarks'));
        } catch (Exception $e) {

            return response()->json([
                'status' => 0,
                'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                //'errors' => $e->getMessage()
            ]);
        }
    }

    public function downloadPDFRejectHOD()
    {

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        if (Session::has('einsearch')) {
            // Get data from session
            $einsearch = Session::get('einsearch');
            $Remarks = RemarksModel::get()->toArray();

            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 2)->where('rejected_status', 3)->toArray();

            $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('ein', $einsearch)->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 2)->where('rejected_status', 3)->paginate(10);

            //expire_on_duty if yes top priority

            // dd( $empList->toArray() );
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status != 0 && $data->rejected_status != 0) {
                    $stat = 'started';
                    $data->status = 'Incomplete / Reverted';
                }

                $data->formSubStat = $stat;
            }

            $html = view('admin.generatedRevertPDF', ['empList' => $empList], ['empListArray' => $empListArray], ['Remarks' => $Remarks])->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();
          //  $dompdf->stream('admin.generatedRevertPDF', ['Attachment' => false]);
        } else {

            $Remarks = RemarksModel::get()->toArray();

            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 2)->where('rejected_status', 3)->toArray();

            $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 2)->where('rejected_status', 3)->paginate(10);

            //expire_on_duty if yes top priority

            // dd( $empList->toArray() );
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status != 0 && $data->rejected_status != 0) {
                    $stat = 'started';
                    $data->status = 'Incomplete / Reverted';
                }

                $data->formSubStat = $stat;
            }

            $html = view('admin.generatedRevertPDF', ['empList' => $empList], ['empListArray' => $empListArray], ['Remarks' => $Remarks])->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();
           // $dompdf->stream('admin.generatedRevertPDF', ['Attachment' => false]);
        }
    }

    ////////////////////////////////////////////////////////////////////////////////SEEN BY AD NODAL REVERTED BY DP ASSISTANT

    public function viewRevertedListADAssistSearch(Request $request)
    {
        try {
            $empListArray = null;
            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();

            //dd( $user_id );

            $Remarks = RemarksModel::get()->toArray();

            $tempEmpList = null;
            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 3)->where('rejected_status', 4)->toArray();
            // Change for DIHAS below
            $empList = ProformaModel::where('form_status', 1)->where('dept_id', $getUser->dept_id)->where('file_status', 3)->where('rejected_status', 4)->paginate(10);

            if ($request->searchItem != null || trim($request->searchItem) != '') {
                $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('ein', $request->searchItem)->where('file_status', 3)->where('rejected_status', 4)->toArray();
                $empList = ProformaModel::orderByRaw("expire_on_duty = 'no',deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('ein', $request->searchItem)->where('file_status', 3)->where('rejected_status', 4)->paginate(10);
                $tempEmpList = $empListArray;
                if (count($tempEmpList) == 0) {
                    $tempEmpList = 0;
                    return view('admin/viewRevertedListADAssist', compact('empList', 'empListArray', 'Remarks'));
                }
            }

            $stat = '';
            // $rejectBy1 = '';
            foreach ($empList as $data) {

                if ($data->status != 0 && $data->rejected_status != 0) {
                    //status will be null when reverted by hod only
                    $stat = 'started';
                    $data->status = 'Incomplete / Reverted';
                }

                $data->formSubStat = $stat;
            }
            Session::put('einsearch', $request->searchItem);
            return view('admin/viewRevertedListADAssist', compact('empList', 'empListArray', 'Remarks'));
        } catch (Exception $e) {

            return response()->json([
                'status' => 0,
                'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                //'errors' => $e->getMessage()
            ]);
        }
    }

    // Submitted Applicants list

    public function viewRevertedListADAssist(Request $request)
    {
        try {
            $request->session()->forget(['ein', 'einsearch']);

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();

            $Remarks = RemarksModel::get()->toArray();

            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 3)->where('rejected_status', 4)->toArray();

            $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 3)->where('rejected_status', 4)->paginate(10);

            //expire_on_duty if yes top priority

            $stat = '';
            // $rejectBy1 = '';
            foreach ($empList as $data) {

                if ($data->status != 0 && $data->rejected_status != 0) {
                    $stat = 'started';
                    $data->status = 'Incomplete / Reverted';
                }

                $data->formSubStat = $stat;
            }

            return view('admin/viewRevertedListADAssist', compact('empList', 'empListArray', 'Remarks'));
        } catch (Exception $e) {

            return response()->json([
                'status' => 0,
                'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                //'errors' => $e->getMessage()
            ]);
        }
    }

    public function downloadPDFRejectADAssist()
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        if (Session::has('einsearch')) {
            // Get data from session
            $einsearch = Session::get('einsearch');
            $Remarks = RemarksModel::get()->toArray();

            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 3)->where('rejected_status', 4)->toArray();

            $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('ein', $einsearch)->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 3)->where('rejected_status', 4)->paginate(10);

            //expire_on_duty if yes top priority

            // dd( $empList->toArray() );
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status != 0 && $data->rejected_status != 0) {
                    $stat = 'started';
                    $data->status = 'Incomplete / Reverted';
                }

                $data->formSubStat = $stat;
            }

            $html = view('admin.generatedRevertPDF', ['empList' => $empList], ['empListArray' => $empListArray], ['Remarks' => $Remarks])->render();
            //return $empList;
            // dd( $html );
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();
           // $dompdf->stream('admin.generatedRevertPDF', ['Attachment' => false]);
        } else {

            $Remarks = RemarksModel::get()->toArray();

            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 3)->where('rejected_status', 4)->toArray();

            $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 3)->where('rejected_status', 4)->paginate(10);

            //expire_on_duty if yes top priority

            // dd( $empList->toArray() );
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status != 0 && $data->rejected_status != 0) {
                    $stat = 'started';
                    $data->status = 'Incomplete / Reverted';
                }

                $data->formSubStat = $stat;
            }

            $html = view('admin.generatedRevertPDF', ['empList' => $empList], ['empListArray' => $empListArray], ['Remarks' => $Remarks])->render();
            //return $empList;
            // dd( $html );
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();
           // $dompdf->stream('admin.generatedRevertPDF', ['Attachment' => false]);
        }
    }

    ////////////////////////////////////////////////////////////////////////////////SEEN BY AD NODAL REVERTED BY DP ASSISTANT

    public function viewRevertedListADNodalSearch(Request $request)
    {
        try {
            $empListArray = null;
            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();

            //dd( $user_id );

            $Remarks = RemarksModel::get()->toArray();

            $tempEmpList = null;
            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 4)->where('rejected_status', 5)->toArray();
            // Change for DIHAS below
            $empList = ProformaModel::where('form_status', 1)->where('dept_id', $getUser->dept_id)->where('file_status', 4)->where('rejected_status', 5)->paginate(10);

            if ($request->searchItem != null || trim($request->searchItem) != '') {
                $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('ein', $request->searchItem)->where('file_status', 4)->where('rejected_status', 5)->toArray();
                $empList = ProformaModel::orderByRaw("expire_on_duty = 'no',deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('ein', $request->searchItem)->where('file_status', 4)->where('rejected_status', 5)->paginate(10);
                $tempEmpList = $empListArray;
                if (count($tempEmpList) == 0) {
                    $tempEmpList = 0;
                    return view('admin/viewRevertedListADNodal', compact('empList', 'empListArray', 'Remarks'));
                }
            }

            $stat = '';
            // $rejectBy1 = '';
            foreach ($empList as $data) {

                if ($data->status != 0 && $data->rejected_status != 0) {
                    //status will be null when reverted by hod only
                    $stat = 'started';
                    $data->status = 'Incomplete / Reverted';
                }

                $data->formSubStat = $stat;
            }
            Session::put('einsearch', $request->searchItem);
            return view('admin/viewRevertedListADNodal', compact('empList', 'empListArray', 'Remarks'));
        } catch (Exception $e) {

            return response()->json([
                'status' => 0,
                'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                //'errors' => $e->getMessage()
            ]);
        }
    }

    // Submitted Applicants list

    public function viewRevertedListADNodal(Request $request)
    {
        try {
            $request->session()->forget(['ein', 'einsearch']);

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();

            $Remarks = RemarksModel::get()->toArray();

            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 4)->where('rejected_status', 5)->toArray();

            $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 4)->where('rejected_status', 5)->paginate(10);

            //$tempArray = ProformaModel::get()->where( 'dept_id', $getUser->dept_id )->where( 'form_status', 1 )->where( 'file_status', 1 )->where( 'rejected_status', 2 )->first();

            //expire_on_duty if yes top priority

            $stat = '';
            // $rejectBy1 = '';
            foreach ($empList as $data) {

                if ($data->status != 0 && $data->rejected_status != 0) {
                    $stat = 'started';
                    $data->status = 'Incomplete / Reverted';
                }

                $data->formSubStat = $stat;
            }

            return view('admin/viewRevertedListADNodal', compact('empList', 'empListArray', 'Remarks'));
        } catch (Exception $e) {

            return response()->json([
                'status' => 0,
                'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                //'errors' => $e->getMessage()
            ]);
        }
    }

    public function downloadPDFRejectADNodal()
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        if (Session::has('einsearch')) {
            // Get data from session
            $einsearch = Session::get('einsearch');
            $Remarks = RemarksModel::get()->toArray();

            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 4)->where('rejected_status', 5)->toArray();

            $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 4)->where('ein', $einsearch)->where('rejected_status', 5)->paginate(10);

            //expire_on_duty if yes top priority

            // dd( $empList->toArray() );
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status != 0 && $data->rejected_status != 0) {
                    $stat = 'started';
                    $data->status = 'Incomplete / Reverted';
                }

                $data->formSubStat = $stat;
            }

            $html = view('admin.generatedRevertPDF', ['empList' => $empList], ['empListArray' => $empListArray], ['Remarks' => $Remarks])->render();
            //return $empList;
            // dd( $html );
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();
           // $dompdf->stream('admin.generatedRevertPDF', ['Attachment' => false]);
        } else {
            $Remarks = RemarksModel::get()->toArray();

            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 4)->where('rejected_status', 5)->toArray();

            $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 4)->where('rejected_status', 5)->paginate(10);

            //expire_on_duty if yes top priority

            // dd( $empList->toArray() );
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status != 0 && $data->rejected_status != 0) {
                    $stat = 'started';
                    $data->status = 'Incomplete / Reverted';
                }

                $data->formSubStat = $stat;
            }

            $html = view('admin.generatedRevertPDF', ['empList' => $empList], ['empListArray' => $empListArray], ['Remarks' => $Remarks])->render();
            //return $empList;
            // dd( $html );
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();
          //  $dompdf->stream('admin.generatedRevertPDF', ['Attachment' => false]);
        }
    }

    // Submitted Applicants list

    public function viewRevertedListDP(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $deptId = $request->input('dept_id');
            $Remarks = RemarksModel::get()->toArray();
            $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
            //dd($request->input( 'dept_id' ));
            if (session()->get('deptId') != '' && $request->input('page') == '') {
                $request->session()->forget(['deptId']);
            }

            if (strlen($deptId) > 0) {
                session()->put('deptId', $deptId);
            }
            if (strlen(session()->get('deptId')) > 0) {
                $deptId = session()->get('deptId');
                $empListArray = ProformaModel::get()->where('dept_id', $deptId)->where('file_status', 5)->where('rejected_status', 6)->toArray();
                $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('dept_id', $deptId)->where('file_status', 5)->where('rejected_status', 6)->paginate(10);
            } else {
                $empListArray = ProformaModel::get()->where('file_status', 5)->where('rejected_status', 6)->toArray();
                $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('file_status', 5)->where('rejected_status', 6)->paginate(10);
            }

            $stat = '';
            // $rejectBy1 = '';
            foreach ($empList as $data) {

                if ($data->status == 0 && $data->rejected_status != 0) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Reverted by DP Nodal';
                }

                $data->formSubStat = $stat;
            }

            return view('admin/viewRevertedListDP', compact('empList', 'empListArray', 'Remarks', 'deptListArray'));
        } catch (Exception $e) {

            return response()->json([
                'status' => 0,
                'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                //'errors' => $e->getMessage()
            ]);
        }
    }

    public function downloadPDFRejectDP()
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $Remarks = RemarksModel::get()->toArray();
        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        $empListArray = ProformaModel::get()->where('form_status', 1)->where('file_status', 1)->where('rejected_status', 6)->toArray();

        $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', dept_name, deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('form_status', 1)->where('file_status', 1)->where('rejected_status', 6)->paginate(10);

        //expire_on_duty if yes top priority

        // dd( $empList->toArray() );
        $stat = '';

        foreach ($empList as $data) {
            if ($data->status == 0 && $data->rejected_status != 0) {
                $stat = 'started';
                $data->status = 'Incomplete / Reverted';
                // $rejectBy = User::get()->where( 'id', $data->forwarded_by )->first();
                // $rejectBy1 = $rejectBy->name;
            }

            $data->formSubStat = $stat;
        }

        $html = view('admin.generatedRevertPDF', ['empList' => $empList], ['empListArray' => $empListArray], ['Remarks' => $Remarks], ['deptListArray' => $deptListArray],)->render();
        //return $empList;
        // dd( $html );
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream();
       // $dompdf->stream('admin.generatedRevertPDF', ['Attachment' => false]);
    }

    ////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////End of Rejected List///////////////////

    // Submitted Applicants list, this is for HOD Assistant

    public function viewEmpSubmitted(Request $request)
    {
        // Change for DIHAS below
        $request->session()->forget(['ein', 'ein']);

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('status', 2)->where('file_status', 1)->where('rejected_status', 0)->toArray();

        $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('status', 2)->where('file_status', 1)->where('rejected_status', 0)->paginate(10);
        $Remarks = RemarksApproveModel::get()->toArray();
        //expire_on_duty if yes top priority

        // dd( $empList->toArray() );

        foreach ($empList as $data) {
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }

            $data->formSubStat = $stat;
        }

        return view('admin/viewEmpSubmitted', compact('empList', 'empListArray', 'Remarks', 'RemarksApprove'));
    }

    // completed emp search for HOD Assistant

    public function viewEmpSubmittedSearch(Request $request)
    {

        $empListArray = null;
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $tempEmpList = null;
        $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('status', 2)->where('file_status', 1)->where('rejected_status', 0)->toArray();
        // Change for DIHAS below
        $Remarks = RemarksApproveModel::get()->toArray();
        $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('status', 2)->where('file_status', 1)->where('rejected_status', 0)->paginate(10);
        if ($request->searchItem != null || trim($request->searchItem) != '') {
            $empListArray = ProformaModel::get()->where('status', 2)->where('ein', $request->searchItem)->where('dept_id', $getUser->dept_id)->where('file_status', 1)->where('rejected_status', 0)->toArray();
            // $data = ProformaModel::get()->where( 'status', 1 )->where( 'ein', $request->searchItem )->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('status', 2)->where('ein', $request->searchItem)->where('file_status', 1)->where('rejected_status', 0)->paginate(10);
            //dd( $empList );

            $tempEmpList = $empListArray;
            if (count($tempEmpList) == 0) {
                $tempEmpList = 0;
                // $data->status = 'Verified';

                return view('admin/viewEmpSubmitted', compact('empList', 'empListArray', 'Remarks', 'RemarksApprove'));
            }
        }
        $stat = '';

        foreach ($empList as $data) {
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }
            if ($data->status == 8) {
                $stat = 'transfer';
                $data->status = 'Transferred';
            }

            $data->formSubStat = $stat;
        }

        return view('admin/viewEmpSubmitted', compact('empList', 'empListArray', 'Remarks'));
    }

    //////////////////////////////////////////////HOD FORWARD APPLICANTS LIST
    // search

    public function viewForwardEmpSearch(Request $request)
    {
        $empListArray = null;
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $tempEmpList = null;
        $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('file_status', 2)->where('transfer_status', 0)->where('rejected_status', 0)->toArray();
        // DEPARTMENT ID is needed here for filtering
        $RemarksApprove = RemarksApproveModel::get()->toArray();
        $Remarks = RemarksModel::get()->toArray();
        // Change for DIHAS below
        $empList = ProformaModel::where('dept_id', $getUser->dept_id)->where('file_status', 2)->where('transfer_status', 0)->where('rejected_status', 0)->paginate(10);
        if ($request->searchItem != null || trim($request->searchItem) != '') {
            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('ein', $request->searchItem)->where('transfer_status', 0)->where('file_status', 2)->where('rejected_status', 0)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('ein', $request->searchItem)->where('transfer_status', 0)->where('file_status', 2)->where('rejected_status', 0)->paginate(10);
            $tempEmpList = $empListArray;
            if (count($tempEmpList) == 0) {
                $tempEmpList = 0;
                return view('admin/viewForwardEmp', compact('empList', 'empListArray', 'Remarks', 'RemarksApprove'));
            }
        }

        $stat = '';

        foreach ($empList as $data) {
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }
            if ($data->status == 8) {
                $stat = 'transfer';
                $data->status = 'Transferred';
            }

            $data->formSubStat = $stat;
        }
        return view('admin/viewForwardEmp', compact('empList', 'empListArray', 'Remarks', 'RemarksApprove'));
    }

    // Submitted Applicants list

    public function viewForwardEmp(Request $request)
    {
        // Change for DIHAS below
        $request->session()->forget(['ein', 'ein']);

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('transfer_status', 0)->where('file_status', 2)->where('rejected_status', 0)->toArray();

        $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('transfer_status', 0)->where('file_status', 2)->where('rejected_status', 0)->paginate(10);
        $RemarksApprove = RemarksApproveModel::get()->toArray();
        $Remarks = RemarksModel::get()->toArray();
        //expire_on_duty if yes top priority

        // dd( $empList->toArray() );
        $stat = '';

        foreach ($empList as $data) {
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }
            if ($data->status == 8) {
                $stat = 'transfer';
                $data->status = 'Transferred';
            }

            $data->formSubStat = $stat;
        }

        return view('admin/viewForwardEmp', compact('RemarksApprove', 'empList', 'empListArray', 'Remarks'));
    }

    //////////////////////////////////////////////HOD FORWARD APPLICANTS LIST
    // search

    public function viewForwardToADAssistSearch(Request $request)
    {
        $empListArray = null;
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $tempEmpList = null;
        $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('file_status', 3)->where('rejected_status', 0)->toArray();
        // DEPARTMENT ID is needed here for filtering
        $RemarksApprove = RemarksApproveModel::get()->toArray();
        $Remarks = RemarksModel::get()->toArray();
        // Change for DIHAS below
        $empList = ProformaModel::where('dept_id', $getUser->dept_id)->where('file_status', 3)->where('rejected_status', 0)->paginate(10);
        if ($request->searchItem != null || trim($request->searchItem) != '') {
            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('ein', $request->searchItem)->where('file_status', 3)->where('rejected_status', 0)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('ein', $request->searchItem)->where('file_status', 3)->where('rejected_status', 0)->paginate(10);
            $tempEmpList = $empListArray;
            if (count($tempEmpList) == 0) {
                $tempEmpList = 0;
                return view('admin/viewForwardToADAssist', compact('RemarksApprove', 'empList', 'empListArray', 'Remarks'));
            }
        }

        $stat = '';

        foreach ($empList as $data) {
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }
            if ($data->status == 8) {
                $stat = 'transfer';
                $data->status = 'Transferred';
            }

            $data->formSubStat = $stat;
        }
        return view('admin/viewForwardToADAssist', compact('empList', 'empListArray', 'Remarks', 'RemarksApprove'));
    }

    // Submitted Applicants list

    public function viewForwardToADAssist(Request $request)
    {
        // Change for DIHAS below
        $request->session()->forget(['ein', 'ein']);

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('file_status', 3)->where('rejected_status', 0)->toArray();

        $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('file_status', 3)->where('rejected_status', 0)->paginate(10);
        $RemarksApprove = RemarksApproveModel::get()->toArray();
        $Remarks = RemarksModel::get()->toArray();
        //expire_on_duty if yes top priority

        // dd( $empList->toArray() );
        $stat = '';

        foreach ($empList as $data) {
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }
            if ($data->status == 8) {
                $stat = 'transfer';
                $data->status = 'Transferred';
            }

            $data->formSubStat = $stat;
        }

        return view('admin/viewForwardToADAssist', compact('RemarksApprove', 'empList', 'empListArray', 'Remarks'));
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////HOD FORWARD APPLICANTS LIST
    // search

    public function viewForwardToADNodalSearch(Request $request)
    {
        $empListArray = null;
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $tempEmpList = null;
        $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('file_status', 4)->where('rejected_status', 0)->toArray();
        // DEPARTMENT ID is needed here for filtering
        $RemarksApprove = RemarksApproveModel::get()->toArray();
        $Remarks = RemarksModel::get()->toArray();
        // Change for DIHAS below
        $empList = ProformaModel::where('dept_id', $getUser->dept_id)->where('file_status', 4)->where('rejected_status', 0)->paginate(10);
        if ($request->searchItem != null || trim($request->searchItem) != '') {
            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('ein', $request->searchItem)->where('file_status', 4)->where('rejected_status', 0)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('ein', $request->searchItem)->where('file_status', 4)->where('rejected_status', 0)->paginate(10);
            $tempEmpList = $empListArray;
            if (count($tempEmpList) == 0) {
                $tempEmpList = 0;
                return view('admin/viewForwardToADNodal', compact('empList', 'empListArray', 'Remarks', 'RemarksApprove'));
            }
        }
        $stat = '';

        foreach ($empList as $data) {
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }
            if ($data->status == 8) {
                $stat = 'transfer';
                $data->status = 'Transferred';
            }

            $data->formSubStat = $stat;
        }
        return view('admin/viewForwardToADNodal', compact('RemarksApprove', 'empList', 'empListArray', 'Remarks'));
    }

    // Submitted Applicants list

    public function viewForwardToADNodal(Request $request)
    {
        // Change for DIHAS below
        $request->session()->forget(['ein', 'ein']);

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('file_status', 4)->where('rejected_status', 0)->toArray();

        $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('file_status', 4)->where('rejected_status', 0)->paginate(10);
        $RemarksApprove = RemarksApproveModel::get()->toArray();
        $Remarks = RemarksModel::get()->toArray();
        //expire_on_duty if yes top priority

        // dd( $empList->toArray() );
        $stat = '';

        foreach ($empList as $data) {
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }
            if ($data->status == 8) {
                $stat = 'transfer';
                $data->status = 'Transferred';
            }

            $data->formSubStat = $stat;
        }

        return view('admin/viewForwardToADNodal', compact('RemarksApprove', 'empList', 'empListArray', 'Remarks'));
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////

    //////////SELECT DEPARTMENT FOR CHECKING BY DP ASSISTANT//////////////////////////////////////////////////////////////////////////
    // Submitted Applicants list

    public function selectDeptByDPAssist(Request $request)
    {
        // Change for DIHAS below

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        $Remarks = RemarksModel::get()->toArray();
        $RemarksApprove = RemarksApproveModel::get()->toArray();
        $deptId = $request->input('dept_id');

        $empListArray = array();
        if (session()->get('deptId') != '' && $request->input('page') == '') {
            $request->session()->forget(['deptId']);
        }

        if (strlen($deptId) > 0) {
            session()->put('deptId', $deptId);
        }
        if (strlen(session()->get('deptId')) > 0) {
            $deptId = session()->get('deptId');
            $empListArray = ProformaModel::get()->where('dept_id', $deptId)->where('file_status', 5)->toArray();

            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe,appl_date, applicant_dob")->where('dept_id', $deptId)->where('file_status', 5)->paginate(10);
            $empListprint = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe,appl_date, applicant_dob")->where('dept_id', $deptId)->where('file_status', 5)->get();
            //  dd( $empList );
        } else {

            $empListArray = ProformaModel::get()->where('file_status', 5)->where('status', 2)->where('rejected_status', 0)->toArray();

            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('file_status', 5)->where('status', 2)->where('rejected_status', 0)->paginate(10);
            $empListprint = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('file_status', 5)->where('status', 2)->where('rejected_status', 0)->get();
            //  dd( $empList );
        }
        //the list is not in order of seniority here but while download and print it's in seniority

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
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }
            if ($data->status == 8) {
                $stat = 'transfer';
                $data->status = 'Transferred';
            }


            $data->formSubStat = $stat;
        }

        return view('admin/selectDeptByDPAssist', compact("empListprint", "getUser", "RemarksApprove", "deptListArray", "empListArray", "empList", "Remarks"));
    }
    public function selectDeptByDPAssistSearch(Request $request)
    {
        // Change for DIHAS below

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        $Remarks = RemarksModel::get()->toArray();
        $RemarksApprove = RemarksApproveModel::get()->toArray();
        $deptId = $request->input('dept_id');


        $empListArray = array();
        if (session()->get('deptId') != "" && $request->input('page') == "") {
            $request->session()->forget(['deptId']);
        }

        if (strlen($deptId) > 0) {
            session()->put('deptId', $deptId);
        }
        if (strlen(session()->get('deptId')) > 0) {
            $deptId = session()->get('deptId');
            $empListArray = ProformaModel::get()->where('dept_id', $deptId)->where('file_status', 5)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe,appl_date, applicant_dob")->where('ein', $request->searchItem)->where('dept_id', $deptId)->where('file_status', 5)->paginate(10);
        } else {

            $empListArray = ProformaModel::get()->where('file_status', 5)->where('status', 2)->where('rejected_status', 0)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('ein', $request->searchItem)->where('file_status', 5)->where('status', 2)->where('rejected_status', 0)->paginate(10);
        }
        //the list is not in order of seniority here but while download and print it's in seniority

        $stat = '';

        foreach ($empList as $data) {
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }
            if ($data->status == 8) {
                $stat = 'transfer';
                $data->status = 'Transferred';
            }

            $data->formSubStat = $stat;
        }

        return view('admin/selectDeptByDPAssist', compact('RemarksApprove', 'deptListArray', 'empListArray', 'empList', 'Remarks'));
    }

    public function selectDeptByDPApproveDept()
    {
        // Change for DIHAS below

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $desigListArray = DesignationModel::orderBy('desig_name')->get()->unique('desig_name');

        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        $Remarks = RemarksApproveModel::get()->toArray();

        $Sign1 = Sign1Model::get()->toArray();
        $Sign2 = Sign2Model::get()->toArray();

        $statusArray = [4, 5, 6];
        //$statusArray = [ 1, 2, 3, 4 ];
        $empListArray = ProformaModel::get()->whereIn('status', $statusArray)->where('dept_id', $getUser->dept_id)->toArray();
        $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->whereIn('status', $statusArray)->where('dept_id', $getUser->dept_id)->paginate(10);
        $empListprint = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->whereIn('status', $statusArray)->where('dept_id', $getUser->dept_id)->get();
        //the list is not in order of seniority here but while download and print it's in seniority

        $stat = "";

        foreach ($empList as $data) {
            // dd( $data);
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
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }
            if ($data->status == 8) {
                $stat = 'transfer';
                $data->status = 'Transferred';
            }

            $data->formSubStat = $stat;
        }

        return view('admin/selectDeptByDPApproveDept', compact("empListprint", "desigListArray", "Sign1", "Sign2", "deptListArray", "empListArray", "empList", "Remarks"));
    }

    //Approve list which can be seen by HOD Assistant to AD Nodal
    public function selectDeptByDPApproveDeptSearch(Request $request) //After UO Generation back for appointment from DP Nodal
    {
        // Change for DIHAS below

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $desigListArray = DesignationModel::orderBy('desig_name')->get()->unique('desig_name');

        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        $Remarks = RemarksApproveModel::get()->toArray();

        $Sign1 = Sign1Model::get()->toArray();
        $Sign2 = Sign2Model::get()->toArray();

        $statusArray = [4, 5, 6];
        //$statusArray = [1, 2, 3, 4];
        $empListArray = ProformaModel::get()->whereIn('status', $statusArray)->where('dept_id', $getUser->dept_id)->toArray();



        $ein = ProformaModel::get()->whereIn('ein', $empListArray)->toArray();
        // $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->whereIn('status', $statusArray)->paginate(10);

        // if ($request->searchItem != null || trim($request->searchItem) != "") {
        $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('ein', $request->searchItem)->where('dept_id', $getUser->dept_id)->whereIn('status', $statusArray)->paginate(10);
        $empListprint = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('ein', $request->searchItem)->where('dept_id', $getUser->dept_id)->whereIn('status', $statusArray)->get();
        //the list is not in order of seniority here but while download and print it's in seniority
        // }
        $stat = '';

        foreach ($empList as $data) {
            // dd( $data );
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }
            if ($data->status == 8) {
                $stat = 'transfer';
                $data->status = 'Transferred';
            }

            $data->formSubStat = $stat;
        }

        return view('admin/selectDeptByDPApproveDept', compact('empListprint', 'desigListArray', 'Sign1', 'Sign2', 'deptListArray', 'empListArray', 'empList', 'Remarks'));
    }
    //////////////////////////////////////////UO Approve List///////////////////

    public function selectDeptByDPNodal(Request $request)
    {
        // Change for DIHAS below
        $request->session()->forget('deptId');
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        $Remarks = RemarksModel::get()->toArray();
        $RemarksApprove = RemarksApproveModel::get()->toArray();
        $deptId = $request->input('dept_id');

        $empListArray = array();
        if (session()->get('deptId') != '' && $request->input('page') == '') {
            $request->session()->forget(['deptId']);
        }

        if (strlen($deptId) > 0) {
            session()->put('deptId', $deptId);
        }
        // $trans = [ null, 0 ];
        // $reject = [ null, 0 ];
        if (strlen(session()->get('deptId')) > 0) {

            $deptId = session()->get('deptId');
            $empListArray = ProformaModel::get()->where('dept_id', $deptId)->where('file_status', 6)->where('transfer_status', 0)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe,appl_date, applicant_dob")->where('dept_id', $deptId)->where('file_status', 6)->where('transfer_status', 0)->paginate(10);
        } else {
            $empListArray = ProformaModel::get()->where('file_status', 6)->where('status', 3)->where('rejected_status', 0)->where('transfer_status', 0)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('file_status', 6)->where('status', 3)->where('rejected_status', 0)->where('transfer_status', 0)->paginate(10);
            //dd( $empListArray );
            //  $empListArray = ProformaModel::get()->where( 'file_status', 6 )->toArray();
            //  $empList = ProformaModel::orderByRaw( "(expire_on_duty = 'no'), deceased_doe,appl_date, applicant_dob" )->where( 'file_status', 6 )->paginate( 10 );
        }

        $stat = '';

        foreach ($empList as $data) {
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }

            $data->formSubStat = $stat;
        }

        return view('admin/selectDeptByDPNodal', compact('RemarksApprove', 'deptListArray', 'empListArray', 'empList', 'Remarks'));
    }

    public function selectDeptByDPApprove()
    {
        // Change for DIHAS below //After UO Generation back for appointment from DP Nodal
        session()->forget('deptId');
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $desigListArray = DesignationModel::orderBy('desig_name')->get()->unique('desig_name');

        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        //put designation from candidate proforma

        $Remarks = RemarksApproveModel::get()->toArray();

        $Sign1 = Sign1Model::get()->toArray();
        $Sign2 = Sign2Model::get()->toArray();

        $statusArray = [4, 5, 6];
        //Approve Appointed and Order
        //$statusArray = [ 5, 6 ];
        $empListArray = ProformaModel::get()->whereIn('status', $statusArray)->toArray();
        $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->whereIn('status', $statusArray)->paginate(10);
        $empListprint = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->whereIn('status', $statusArray)->get();
        //the list is not in order of seniority here but while download and print it's in seniority

        $stat = "";

        foreach ($empList as $data) {
            // dd( $data);
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
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }
            if ($data->status == 8) {
                $stat = 'transfer';
                $data->status = 'Transferred';
            }

            $data->formSubStat = $stat;
        }

        $stat = "";

        foreach ($empListprint as $data) {
            // dd( $data);
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
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }
            if ($data->status == 8) {
                $stat = 'transfer';
                $data->status = 'Transferred';
            }

            $data->formSubStat = $stat;
        }
        // dd($empListprint);
        return view('admin/selectDeptByDPApprove', compact("getUser", "empListprint", "desigListArray", "Sign1", "Sign2", "deptListArray", "empListArray", "empList", "Remarks"));
    }

    public function selectDeptByDPApproveSearch(Request $request) //After UO Generation back for appointment from DP Nodal
    {
        // Change for DIHAS below

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $desigListArray = DesignationModel::orderBy('desig_name')->get()->unique('desig_name');



        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        $Remarks = RemarksApproveModel::get()->toArray();

        $Sign1 = Sign1Model::get()->toArray();
        $Sign2 = Sign2Model::get()->toArray();



        $statusArray = [4, 5, 6];
        //$statusArray = [5, 6];
        $empListArray = ProformaModel::get()->whereIn('status', $statusArray)->toArray();

        //  if ($request->searchItem != null || trim($request->searchItem) != "") {
        $ein = ProformaModel::get()->whereIn('ein', $empListArray)->toArray();

        $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('ein', $request->searchItem)->whereIn('status', $statusArray)->paginate(10);
        $empListprint = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('ein', $request->searchItem)->whereIn('status', $statusArray)->get();
        // }
        //the list is not in order of seniority here but while download and print it's in seniority

        $stat = '';

        foreach ($empList as $data) {
            // dd( $data );
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }
            if ($data->status == 8) {
                $stat = 'transfer';
                $data->status = 'Transferred';
            }

            $data->formSubStat = $stat;
        }

        return view('admin/selectDeptByDPApprove', compact('empListprint', 'desigListArray', 'Sign1', 'Sign2', 'deptListArray', 'empListArray', 'empList', 'Remarks'));
    }
    //////////////////////////////////////////UO Approve List///////////////////
    //////////////////////////////////////////UO Approve List///////////////////

    public function UOform($id, Request $request)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $id = Crypt::decryptString($id);

        if (session()->has('ein')) {
            $session_ein = session()->get('ein');
        }
        // set a session emp EIN
        session()->put('ein', $id);
        $ein = session()->get('ein');

        // dd( $ein );

        //HERE VACANCY IS TO BE DEDUCTED FROM THE TOTAL DIH VACANCY.....
        //ANAND 30-06-2025

        $empList = ProformaModel::get()->where('ein', $ein)->first();

        $validator = Validator::make($request->all(), [
            // 'applicant_name' => 'required',
            'dp_file_no' => 'required',
            'ad_file_no' => 'required',
            'post_given' => 'required',
            'signing_authority_1' => 'required',
            'signing_authority_2' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $uoGeneration = new UOGenerationModel();
        $uoGeneration->ein = $ein;
        $uoGeneration->dept_id = $empList->dept_id;
        $uoGeneration->appl_number = $empList->appl_number;
        $uoGeneration->file_put_up_by = $getUser->name;
        $uoGeneration->dp_file_no = $request->input('dp_file_no');
        $uoGeneration->ad_file_no = $request->input('ad_file_no');
        $uoGeneration->post_given = $request->input('post_given');
        $uoGeneration->signing_authority_1 = $request->input('signing_authority_1');
        $uoGeneration->signing_authority_2 = $request->input('signing_authority_2');

        $uoGeneration->save();

            //return redirect()->back()->with( 'success', ' record saved successfully.' )->with( compact( 'options' ) );
        ;
        return view('admin/selectDeptByDPApprove', 'record saved successfully', compact('Sign1Model', 'Sign1Mode2', 'deptListArray', 'empListArray', 'empList', 'Remarks'));
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function downloadPDF()
    {
        // $session_dept_id = session()->get( 'deptID' );
        // $deptId = $session_dept_id;
        //dd( $deptId );

        $empListArray = ProformaModel::get()->where('file_status', 5)->toArray();
        $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('file_status', 5)->paginate(10);
        $stat = '';

        foreach ($empList as $data) {
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }
            if ($data->status == 8) {
                $stat = 'transfer';
                $data->status = 'Transferred';
            }
            $data->formSubStat = $stat;
        }

        $html = view('admin.generatedDPpdf', ['empList' => $empList], ['empListArray' => $empListArray])->render();
        //return $empList;
        // dd( $html );
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream();
       // $dompdf->stream('admin.generatedDPpdf', ['Attachment' => false]);
    }

    public function downloadPDFNODAL()
    {
        // $session_dept_id = session()->get( 'deptID' );
        // $deptId = $session_dept_id;
        //dd( $deptId );
        if (Session::has('deptId')) {
            // Get data from session
            $deptId = Session::get('deptId');
            $empListArray = ProformaModel::get()->where('file_status', 6)->where('dept_id', $deptId)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('file_status', 6)->where('dept_id', $deptId)->paginate(10);
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Verified';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }
                if ($data->status == 7) {
                    $stat = 'signed';
                    $data->status = 'Signed by DP';
                }
                if ($data->status == 8) {
                    $stat = 'transfer';
                    $data->status = 'Transferred';
                }

                $data->formSubStat = $stat;
            }

            $html = view('admin.generatedDPpdf', ['empList' => $empList], ['empListArray' => $empListArray])->render();
            //return $empList;
            // dd( $html );
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();
           // $dompdf->stream('admin.generatedDPpdf', ['Attachment' => false]);
        } else {

            $empListArray = ProformaModel::get()->where('file_status', 6)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('file_status', 6)->paginate(10);
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Verified';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }

                $data->formSubStat = $stat;
            }

            $html = view('admin.generatedDPpdf', ['empList' => $empList], ['empListArray' => $empListArray])->render();
            //return $empList;
            // dd( $html );
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();
           // $dompdf->stream('admin.generatedDPpdf', ['Attachment' => false]);
        }
    }

    public function downloadPDFApproved()
    {
        if (Session::has('deptId')) {
            // Get data from session
            $deptId = Session::get('deptId');
            $empListArray = ProformaModel::get()->where('status', 3)->where('dept_id', $deptId)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('status', 3)->where('dept_id', $deptId)->paginate(10);
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Verified';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }
                if ($data->status == 7) {
                    $stat = 'signed';
                    $data->status = 'Signed by DP';
                }
                if ($data->status == 8) {
                    $stat = 'transfer';
                    $data->status = 'Transferred';
                }

                $data->formSubStat = $stat;
            }

            $html = view('admin.generatedDPpdf', ['empList' => $empList], ['empListArray' => $empListArray])->render();
            //return $empList;
            // dd( $html );
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();
           // $dompdf->stream('admin.generatedDPpdf', ['Attachment' => false]);
        } else {

            $empListArray = ProformaModel::get()->where('status', 3)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('status', 3)->paginate(10);
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Verified';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }

                $data->formSubStat = $stat;
            }

            $html = view('admin.generatedDPpdf', ['empList' => $empList], ['empListArray' => $empListArray])->render();
            //return $empList;
            // dd( $html );
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();
           // $dompdf->stream('admin.generatedDPpdf', ['Attachment' => false]);
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////

    // Submitted Applicants list

    public function viewForwardToDPAssist(Request $request)
    {
        // Change for DIHAS below
        $request->session()->forget('deptId');

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('file_status', 5)->toArray();

        $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('file_status', 5)->paginate(10);
        $Remarks = RemarksModel::get()->toArray();
        //expire_on_duty if yes top priority

        // dd( $empList->toArray() );
        $stat = '';

        foreach ($empList as $data) {
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }
            if ($data->status == 8) {
                $stat = 'transfer';
                $data->status = 'Transferred';
            }

            $data->formSubStat = $stat;
        }

        return view('admin/selectDeptByDPAssist', compact('empList', 'empListArray', 'Remarks'));
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////

    // DP Nodal forward to DP Assistant// this is after clicking forward button

    public function viewForwardToDPNodal(Request $request)
    {
        // Change for DIHAS below
        $request->session()->forget('deptId');

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('file_status', 5)->toArray();

        $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('file_status', 5)->paginate(10);
        $Remarks = RemarksModel::get()->toArray();
        //expire_on_duty if yes top priority

        // dd( $empList->toArray() );
        $stat = '';

        foreach ($empList as $data) {
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }
            if ($data->status == 8) {
                $stat = 'transfer';
                $data->status = 'Transferred';
            }
            $data->formSubStat = $stat;
        }

        return view('admin/selectDeptByDPNodal', compact('empList', 'empListArray', 'Remarks'));
    }

    /////////////////APPLICANTS View//////////////////////////////////////////////

    // Submitted Applicants list

    public function viewStatusApplicant(Request $request)
    {
        // Change for DIHAS below
        if (Auth::user()->role_id != 77) {
            // Show an error message

            return view('errors.404');
        }

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
       // dd($getUser->dept_id);
    if($getUser->dept_id == null){
        $dept_id = ProformaModel::where('uploaded_id', $user_id)->first()->dept_id;
        
        $empListArray = ProformaModel::get()->where('dept_id', $dept_id)->where('form_status', 1)->where('file_status', 1)->where('rejected_status', '<=', 1)->toArray();
        
        $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe,appl_date, applicant_dob")->where('dept_id', $dept_id)->where('form_status', 1)->where('file_status', 1)->where('rejected_status', '<=', 1)->paginate(6);
        
        


        $empList = $empList->map(function($empItem, $index){
            //First dynamically assigning the seniority number (Sl No)
            $empItem->slNo = $index + 1;
            return $empItem;
        })->filter(function($empItem) use ($user_id){
            //Filter only the logged in (authenticated) user
            return($empItem->uploaded_id == $user_id);
        });
        
        

        //$empListArray = ProformaModel::get()->where('uploaded_id', $getUser->id)->where('uploader_role_id', 77)->toArray();
        
        //$empList = ProformaModel::where('uploaded_id', $getUser->id)->paginate(10);


        $Remarks = RemarksModel::get()->toArray();
        //expire_on_duty if yes top priority

        // dd( $empList->toArray() );
        $stat = '';

        foreach ($empList as $data) {
            //sent back file user

            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }
            if ($data->status == 8) {
                $stat = 'transfer';
                $data->status = 'Transferred';
            }

            $data->formSubStat = $stat;
        }

        session()->forget(['ein', 'from_emp_ein']);
        $ein = null;

        return view('admin/viewStatusApplicant', compact('empList', 'empListArray', 'Remarks', 'getUser'));
    }

}

    /////////////////////////////////////////////////////////////////////////////////////

    public function viewFileStatusSearch(Request $request)
    {

        $empListArray = null;
        $request->session()->forget(['ein', 'from_emp_ein']);
        // session()->forget( [ 'ein', 'from_emp_ein' ] );
        // $ein = null;

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');

          $file_status_array=[1,2,5,6,7,8,9]; 
        $statusArray = [1,2,3,4,5,6,7,9];
//Status for DP
         $file_status_array1=[1,2,5,6,7,8,9]; 
        $statusArray1 = [1,2,3,4,5,6,7,9];
        // $tempEmpList = null;

        if ($getUser->role_id == 1) {
            if ($request->searchItem != null || trim($request->searchItem) != '') {

                $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('ein', $request->searchItem)->whereIn('file_status', $file_status_array)->whereIn('status', $statusArray)->toArray();
                // $Appl_List = count( $empListArray );
                // dd( $empListArray );
                $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('ein', $request->searchItem)->whereIn('file_status', $file_status_array)->whereIn('status', $statusArray)->paginate(15);
                $Remarks = RemarksModel::get()->toArray();
            } else {
                $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->whereIn('file_status', $file_status_array)->whereIn('status', $statusArray)->toArray();
                // $Appl_List = count( $empListArray );
                // dd( $empListArray );
                $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->whereIn('file_status', $file_status_array)->whereIn('status', $statusArray)->paginate(15);
                $Remarks = RemarksModel::get()->toArray();
            }

            $stat = '';

            foreach ($empList as $data) {
                // $getUser1 = User::get()->where( 'id', $data->forwarded_by )->first();

                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verifieddp';
                    $data->status = 'Verified By DP';
                }
                if($data->status == 9){
                    $stat = 'verifieddept';
                    $data->status = 'Verified By Department';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }
                if ($data->status == 7) {
                    $stat = 'signed';
                    $data->status = 'Signed by DP';
                }
                if ($data->status == 8) {
                    $stat = 'transfer';
                    $data->status = 'Transferred';
                }

                $data->formSubStat = $stat;
            }
            Session::put('einsearch', $request->searchItem);
            return view('admin/viewFileStatus', compact('empList', 'empListArray', 'Remarks', 'getUser'));
        }

        // if ($getUser->role_id == 2 || $getUser->role_id == 3 || $getUser->role_id == 4 || $getUser->role_id == 9) {
        //     if ($request->searchItem != null || trim($request->searchItem) != '') {

        //         $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('ein', $request->searchItem)->where('status', '!=', 0)->toArray();
        //         // $Appl_List = count( $empListArray );
        //         //dd( $Appl_List );
        //         $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe,appl_date, applicant_dob")->where('status', '!=', 0)->where('ein', $request->searchItem)->where('dept_id', $getUser->dept_id)->paginate(15);
        //         $Remarks = RemarksModel::get()->toArray();
        //     } else {
        //         $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('status', '!=', 0)->toArray();
        //         // $Appl_List = count( $empListArray );
        //         // dd( $empListArray );
        //         $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('status', '!=', 0)->paginate(15);
        //         $Remarks = RemarksModel::get()->toArray();
        //     }
        //     $stat = '';

        //     foreach ($empList as $data) {
        //         // $getUser1 = User::get()->where( 'id', $data->forwarded_by )->first();

        //         if ($data->status == 0 && $data->form_status == 1) {
        //             $stat = 'started';
        //             $data->status = 'Incomplete';
        //         }

        //         if ($data->status == 1) {
        //             $stat = 'submitted';
        //             $data->status = 'Submitted';
        //         }
        //         if ($data->status == 2) {
        //             $stat = 'verified';
        //             $data->status = 'Verified';
        //         }
        //         if ($data->status == 3) {
        //             $stat = 'forapproval';
        //             $data->status = 'Put up for Approval';
        //         }

        //         if ($data->status == 4) {
        //             $stat = 'approved';
        //             $data->status = 'Approved';
        //         }
        //         if ($data->status == 5) {
        //             $stat = 'appointed';
        //             $data->status = 'Appointed';
        //         }
        //         if ($data->status == 6) {
        //             $stat = 'order';
        //             $data->status = 'Appointment Order';
        //         }
        //         if ($data->status == 7) {
        //             $stat = 'signed';
        //             $data->status = 'Signed by DP';
        //         }
        //         if ($data->status == 8) {
        //             $stat = 'transfer';
        //             $data->status = 'Transferred';
        //         }

        //         $data->formSubStat = $stat;
        //     }
        //     Session::put('einsearch', $request->searchItem);
        //     return view('admin/viewFileStatus', compact('empList', 'empListArray', 'Remarks', 'getUser'));
        // }

        // dd( $getUser->role_id );
        if ($getUser->role_id == 5 || $getUser->role_id == 6 || $getUser->role_id == 8 || $getUser->role_id == 9) {
            if ($request->searchItem != null || trim($request->searchItem) != '') {

                $request->session()->forget(['deptId']);
                $empListArray = ProformaModel::get()->where('ein', $request->searchItem)->whereIn('file_status', $file_status_array1)->whereIn('status', $statusArray1)->toArray();
                // $Appl_List = count( $empListArray );
                //dd( $Appl_List );
                $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),dept_name,deceased_doe,appl_date, applicant_dob")->where('ein', $request->searchItem)->whereIn('file_status', $file_status_array1)->whereIn('status', $statusArray1)->paginate(10);
                $Remarks = RemarksModel::get()->toArray();
            } else {
                $empListArray = ProformaModel::get()->whereIn('file_status', $file_status_array1)->whereIn('status', $statusArray1)->toArray();
                // $Appl_List = count( $empListArray );
                // dd( $empListArray );
                $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe,appl_date, applicant_dob")->whereIn('file_status', $file_status_array1)->whereIn('status', $statusArray1)->paginate(15);
                $Remarks = RemarksModel::get()->toArray();
            }
            $stat = '';

            foreach ($empList as $data) {
                // $getUser1 = User::get()->where( 'id', $data->forwarded_by )->first();

                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verifieddp';
                    $data->status = 'Verified By DP';
                }
                 if ($data->status == 9) {
                    $stat = 'verifieddept';
                    $data->status = 'Verified By Department';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }
                if ($data->status == 7) {
                    $stat = 'signed';
                    $data->status = 'Signed by DP';
                }
                if ($data->status == 8) {
                    $stat = 'transfer';
                    $data->status = 'Transferred';
                }

                $data->formSubStat = $stat;
            }
            Session::put('einsearch', $request->searchItem);
            return view('admin/viewFileStatusByDP', compact('deptListArray', 'empList', 'empListArray', 'Remarks', 'getUser'));
        }
    }

    // Submitted Applicants list

    public function viewFileStatus(Request $request)
    {
        // Change for DIHAS below
        $request->session()->forget(['ein', 'einsearch']);

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        //Status for Dept
        $file_status_array=[1, 2, 5, 6, 7,8, 9]; 
        $statusArray = [1,2,3,4,5,6,7,9];
//Status for DP
         $file_status_array1=[1,2,5,6,7,8,9]; 
        $statusArray1 = [1,2,3,4,5,6,7,9];
  
    
        if ($getUser->role_id == 1 || $getUser->role_id == 2) {
            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->whereIn('file_status', $file_status_array)->whereIn('status', $statusArray)->toArray();
            // $Appl_List = count( $empListArray );
            //dd( $Appl_List );
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->whereIn('file_status', $file_status_array)->whereIn('status', $statusArray)->paginate(15);
            $Remarks = RemarksModel::get()->toArray();
            //expire_on_duty if yes top priority

            //dd( $empList->toArray() );
            $stat = '';

            foreach ($empList as $data) {
                // $getUser1 = User::get()->where( 'id', $data->forwarded_by )->first();

                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verifieddp';
                    $data->status = 'Verified By DP';
                }
                 if ($data->status == 9) {
                    $stat = 'verifieddept';
                    $data->status = 'Verified By Department';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }
                if ($data->status == 7) {
                    $stat = 'signed';
                    $data->status = 'Signed by DP';
                }
                if ($data->status == 8) {
                    $stat = 'transfer';
                    $data->status = 'Transferred';
                }

                $data->formSubStat = $stat;
            }

            return view('admin/viewFileStatus', compact('empList', 'empListArray', 'Remarks', 'getUser'));
        }

        // if ($getUser->role_id == 2 || $getUser->role_id == 3 || $getUser->role_id == 4 || $getUser->role_id == 9) {
           
        //     $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('status', '!=', 0)->toArray();
        //     // $Appl_List = count( $empListArray );
        //     //dd( $Appl_List );
        //     $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe,appl_date, applicant_dob")->where('status', '!=', 0)->where('dept_id', $getUser->dept_id)->paginate(15);
        //     $Remarks = RemarksModel::get()->toArray();
        //     //expire_on_duty if yes top priority

        //     //dd( $empList->toArray() );
        //     $stat = '';

        //     foreach ($empList as $data) {
        //         // $getUser1 = User::get()->where( 'id', $data->forwarded_by )->first();

        //         if ($data->status == 0 && $data->form_status == 1) {
        //             $stat = 'started';
        //             $data->status = 'Incomplete';
        //         }

        //         if ($data->status == 1) {
        //             $stat = 'submitted';
        //             $data->status = 'Submitted';
        //         }
        //         if ($data->status == 2) {
        //             $stat = 'verified';
        //             $data->status = 'Verified';
        //         }
        //         if ($data->status == 3) {
        //             $stat = 'forapproval';
        //             $data->status = 'Put up for Approval';
        //         }

        //         if ($data->status == 4) {
        //             $stat = 'approved';
        //             $data->status = 'Approved';
        //         }
        //         if ($data->status == 5) {
        //             $stat = 'appointed';
        //             $data->status = 'Appointed';
        //         }
        //         if ($data->status == 6) {
        //             $stat = 'order';
        //             $data->status = 'Appointment Order';
        //         }
        //         if ($data->status == 7) {
        //             $stat = 'signed';
        //             $data->status = 'Signed by DP';
        //         }
        //         if ($data->status == 8) {
        //             $stat = 'transfer';
        //             $data->status = 'Transferred';
        //         }

        //         $data->formSubStat = $stat;
        //     }

        //     return view('admin/viewFileStatus', compact('empList', 'empListArray', 'Remarks', 'getUser'));
        // }

        if ($getUser->role_id == 5 || $getUser->role_id == 6 || $getUser->role_id == 8 || $getUser->role_id == 9) {
            $request->session()->forget(['deptId']);
            $empListArray = ProformaModel::get()->whereIn('file_status', $file_status_array1)->whereIn('status', $statusArray1)->toArray();
            // $Appl_List = count( $empListArray );
            //dd( $Appl_List );
            //$empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),dept_name,deceased_doe,appl_date, applicant_dob")->where('status', '!=', 0)->paginate(15);
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),dept_name,deceased_doe,appl_date, applicant_dob")->whereIn('file_status', $file_status_array1)->whereIn('status', $statusArray1)->paginate(15);
            $Remarks = RemarksModel::get()->toArray();
            //expire_on_duty if yes top priority

            //dd( $empList->toArray() );
            $stat = '';

            foreach ($empList as $data) {
                // $getUser1 = User::get()->where( 'id', $data->forwarded_by )->first();

                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verifieddp';
                    $data->status = 'Verified By DP';
                }
                 if ($data->status == 9) {
                    $stat = 'verifieddept';
                    $data->status = 'Verified By Department';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }
                if ($data->status == 7) {
                    $stat = 'signed';
                    $data->status = 'Signed by DP';
                }
                if ($data->status == 8) {
                    $stat = 'transfer';
                    $data->status = 'Transferred';
                }

                $data->formSubStat = $stat;
            }

            return view('admin/viewFileStatusByDP', compact('deptListArray', 'empList', 'empListArray', 'Remarks', 'getUser'));
        }
    }

    public function viewFileStatusByDPDept(Request $request)
    {
        // Change for DIHAS below
        $request->session()->forget(['ein', 'ein']);
        $deptId = $request->input('dept_id');
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        if (session()->get('deptId') != '' && $request->input('page') == '') {
            $request->session()->forget(['deptId']);
        }
        if (strlen($deptId) > 0) {
            session()->put('deptId', $deptId);
        }

        if (strlen(session()->get('deptId')) > 0) {
            $deptId = session()->get('deptId');
            $empListArray = ProformaModel::get()->where('dept_id', $deptId)->where('status', '!=', 0)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe,appl_date, applicant_dob")->where('dept_id', $deptId)->where('status', '!=', 0)->paginate(15);
        } else {
            //$file_status = [ 5, 6 ];
            // $empListArray = ProformaModel::get()->whereIn( 'file_status', $file_status )->toArray();
            $empListArray = ProformaModel::get()->where('status', '!=', 0)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe,appl_date, applicant_dob")->where('status', '!=', 0)->paginate(15);
        }
        $Remarks = RemarksModel::get()->toArray();
        //expire_on_duty if yes top priority

        //dd( $empList->toArray() );
        $stat = '';

        foreach ($empList as $data) {
            // $getUser1 = User::get()->where( 'id', $data->forwarded_by )->first();

            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }
            if ($data->status == 8) {
                $stat = 'transfer';
                $data->status = 'Transferred';
            }

            $data->formSubStat = $stat;
        }

        return view('admin/viewFileStatusByDP', compact('deptListArray', 'empList', 'empListArray', 'Remarks', 'getUser'));
    }

    public function downloadPDFStatus()
    {

        $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
        //dd($getUser);
        if (Session::has('einsearch')) {
            // Get data from session
            $einsearch = Session::get('einsearch');
          
            if ($getUser->role_id == 1) {
                $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->toArray();
              
              
                $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe,appl_date, applicant_dob")->where('ein', $einsearch)->where('dept_id', $getUser->dept_id)->paginate(15);
                $Remarks = RemarksModel::get()->toArray();
                $stat = '';

                foreach ($empList as $data) {
                    if ($data->status == 0 && $data->form_status == 1) {
                        $stat = 'started';
                        $data->status = 'Incomplete';
                    }

                    if ($data->status == 1) {
                        $stat = 'submitted';
                        $data->status = 'Submitted';
                    }
                    if ($data->status == 2) {
                        $stat = 'verified';
                        $data->status = 'Verified';
                    }
                    if ($data->status == 3) {
                        $stat = 'forapproval';
                        $data->status = 'Put up for Approval';
                    }

                    if ($data->status == 4) {
                        $stat = 'approved';
                        $data->status = 'Approved';
                    }
                    if ($data->status == 5) {
                        $stat = 'appointed';
                        $data->status = 'Appointed';
                    }
                    if ($data->status == 6) {
                        $stat = 'order';
                        $data->status = 'Appointment Order';
                    }
                    if ($data->status == 7) {
                        $stat = 'signed';
                        $data->status = 'Signed by DP';
                    }
                    if ($data->status == 8) {
                        $stat = 'transfer';
                        $data->status = 'Transferred';
                    }

                    $data->formSubStat = $stat;
                }

                $html = view('admin.generatedPDFStatus', ['empList' => $empList], ['empListArray' => $empListArray])->render();
                //return $empList;
                // dd( $html );
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $dompdf->stream();
               // $dompdf->stream('admin.generatedPDFStatus', ['Attachment' => false]);
            }
            if ($getUser->role_id == 2 || $getUser->role_id == 3 || $getUser->role_id == 4 || $getUser->role_id == 9) {
               
              
                $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('form_status', '!=', 0)->toArray();
                // $Appl_List = count( $empListArray );
                //dd( $Appl_List );
                $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe,appl_date, applicant_dob")->where('ein', $einsearch)->where('dept_id', $getUser->dept_id)->where('form_status', '!=', 0)->paginate(10);
                $Remarks = RemarksModel::get()->toArray();
                $stat = '';

                foreach ($empList as $data) {
                    if ($data->status == 0 && $data->form_status == 1) {
                        $stat = 'started';
                        $data->status = 'Incomplete';
                    }

                    if ($data->status == 1) {
                        $stat = 'submitted';
                        $data->status = 'Submitted';
                    }
                    if ($data->status == 2) {
                        $stat = 'verified';
                        $data->status = 'Verified';
                    }
                    if ($data->status == 3) {
                        $stat = 'forapproval';
                        $data->status = 'Put up for Approval';
                    }

                    if ($data->status == 4) {
                        $stat = 'approved';
                        $data->status = 'Approved';
                    }
                    if ($data->status == 5) {
                        $stat = 'appointed';
                        $data->status = 'Appointed';
                    }
                    if ($data->status == 6) {
                        $stat = 'order';
                        $data->status = 'Appointment Order';
                    }
                    if ($data->status == 7) {
                        $stat = 'signed';
                        $data->status = 'Signed by DP';
                    }
                    if ($data->status == 8) {
                        $stat = 'transfer';
                        $data->status = 'Transferred';
                    }

                    $data->formSubStat = $stat;
                }

                $html = view('admin.generatedPDFStatus', ['empList' => $empList], ['empListArray' => $empListArray])->render();
                //return $empList;
                // dd( $html );
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $dompdf->stream();
               // $dompdf->stream('admin.generatedPDFStatus', ['Attachment' => false]);
            }

            if ($getUser->role_id == 5 || $getUser->role_id == 6 || $getUser->role_id == 8) {
                $empListArray = ProformaModel::get()->where('status', '!=', 0)->toArray();
               // $Appl_List = count($empListArray);
                //dd( $Appl_List );
                $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),dept_name,deceased_doe,appl_date, applicant_dob")->where('ein', $einsearch)->where('status', '!=', 0)->paginate(10);
                $Remarks = RemarksModel::get()->toArray();
                $stat = '';
                // dd( $empList );
                foreach ($empList as $data) {
                    if ($data->status == 0 && $data->form_status == 1) {
                        $stat = 'started';
                        $data->status = 'Incomplete';
                    }

                    if ($data->status == 1) {
                        $stat = 'submitted';
                        $data->status = 'Submitted';
                    }
                    if ($data->status == 2) {
                        $stat = 'verified';
                        $data->status = 'Verified';
                    }
                    if ($data->status == 3) {
                        $stat = 'forapproval';
                        $data->status = 'Put up for Approval';
                    }

                    if ($data->status == 4) {
                        $stat = 'approved';
                        $data->status = 'Approved';
                    }
                    if ($data->status == 5) {
                        $stat = 'appointed';
                        $data->status = 'Appointed';
                    }
                    if ($data->status == 6) {
                        $stat = 'order';
                        $data->status = 'Appointment Order';
                    }
                    if ($data->status == 7) {
                        $stat = 'signed';
                        $data->status = 'Signed by DP';
                    }
                    if ($data->status == 8) {
                        $stat = 'transfer';
                        $data->status = 'Transferred';
                    }

                    $data->formSubStat = $stat;
                }

                $html = view('admin.generatedPDFStatus', ['empList' => $empList], ['empListArray' => $empListArray])->render();
                //return $empList;
                // dd( $html );
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $dompdf->stream();
                //$dompdf->stream('admin.generatedPDFStatus', ['Attachment' => false]);
            }
        } else {
            // $deptListArray = DepartmentModel::orderBy( 'dept_name' )->get()->unique( 'dept_name' );
           // dd('einsearch');
            if ($getUser->role_id == 1) {
                $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->toArray();
                // $Appl_List = count( $empListArray );
                //dd( $Appl_List );
                $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe,appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->paginate(15);
                $Remarks = RemarksModel::get()->toArray();
                $stat = '';

                foreach ($empList as $data) {
                    if ($data->status == 0 && $data->form_status == 1) {
                        $stat = 'started';
                        $data->status = 'Incomplete';
                    }

                    if ($data->status == 1) {
                        $stat = 'submitted';
                        $data->status = 'Submitted';
                    }
                    if ($data->status == 2) {
                        $stat = 'verified';
                        $data->status = 'Verified';
                    }
                    if ($data->status == 3) {
                        $stat = 'forapproval';
                        $data->status = 'Put up for Approval';
                    }

                    if ($data->status == 4) {
                        $stat = 'approved';
                        $data->status = 'Approved';
                    }
                    if ($data->status == 5) {
                        $stat = 'appointed';
                        $data->status = 'Appointed';
                    }
                    if ($data->status == 6) {
                        $stat = 'order';
                        $data->status = 'Appointment Order';
                    }
                    if ($data->status == 7) {
                        $stat = 'signed';
                        $data->status = 'Signed by DP';
                    }
                    if ($data->status == 8) {
                        $stat = 'transfer';
                        $data->status = 'Transferred';
                    }

                    $data->formSubStat = $stat;
                }

                $html = view('admin.generatedPDFStatus', ['empList' => $empList], ['empListArray' => $empListArray])->render();
                //return $empList;
                // dd( $html );
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $dompdf->stream();
               // $dompdf->stream('admin.generatedPDFStatus', ['Attachment' => false]);
            }
            if ($getUser->role_id == 2 || $getUser->role_id == 3 || $getUser->role_id == 4 || $getUser->role_id == 9) {
               //ANAND
              
                //$status= [0];
              //  $test = ProformaModel::get()->where('status', '!=', 0)->where('dept_id', $getUser->dept_id)->toArray();
              //  dd($test);

                $empListArray = ProformaModel::get()->where('status', '!=', 0)->where('dept_id', $getUser->dept_id)->toArray();
               //  $Appl_List = count( $empListArray );
            //    dd($empListArray);
                          
                $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe,appl_date, applicant_dob")->where('status', '!=', 0)->where('dept_id', $getUser->dept_id)->paginate(15);
               // dd( $empList );
                $Remarks = RemarksModel::get()->toArray();
                $stat = '';

                foreach ($empList as $data) {
                    if ($data->status == 0 && $data->form_status == 1) {
                        $stat = 'started';
                        $data->status = 'Incomplete';
                    }

                    if ($data->status == 1) {
                        $stat = 'submitted';
                        $data->status = 'Submitted';
                    }
                    if ($data->status == 2) {
                        $stat = 'verified';
                        $data->status = 'Verified';
                    }
                    if ($data->status == 3) {
                        $stat = 'forapproval';
                        $data->status = 'Put up for Approval';
                    }

                    if ($data->status == 4) {
                        $stat = 'approved';
                        $data->status = 'Approved';
                    }
                    if ($data->status == 5) {
                        $stat = 'appointed';
                        $data->status = 'Appointed';
                    }
                    if ($data->status == 6) {
                        $stat = 'order';
                        $data->status = 'Appointment Order';
                    }
                    if ($data->status == 7) {
                        $stat = 'signed';
                        $data->status = 'Signed by DP';
                    }
                    if ($data->status == 8) {
                        $stat = 'transfer';
                        $data->status = 'Transferred';
                    }

                    $data->formSubStat = $stat;
                }

                $html = view('admin.generatedPDFStatus', ['empList' => $empList], ['empListArray' => $empListArray])->render();
                //return $empList;
                // dd( $html );
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                // $dompdf->stream('admin.generatedPDFStatus', ['Attachment' => false]);
                $dompdf->stream();
            }
           

            if ($getUser->role_id == 5 || $getUser->role_id == 6 || $getUser->role_id == 8) {
                $empListArray = ProformaModel::get()->where('status', '!=', 0)->toArray();
               // $Appl_List = count($empListArray);
                //dd( $Appl_List );
                $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),dept_name,deceased_doe,appl_date, applicant_dob")->where('status', '!=', 0)->paginate(10);
                $Remarks = RemarksModel::get()->toArray();
                $stat = '';
                // dd( $empList );
                foreach ($empList as $data) {
                    if ($data->status == 0 && $data->form_status == 1) {
                        $stat = 'started';
                        $data->status = 'Incomplete';
                    }

                    if ($data->status == 1) {
                        $stat = 'submitted';
                        $data->status = 'Submitted';
                    }
                    if ($data->status == 2) {
                        $stat = 'verified';
                        $data->status = 'Verified';
                    }
                    if ($data->status == 3) {
                        $stat = 'forapproval';
                        $data->status = 'Put up for Approval';
                    }

                    if ($data->status == 4) {
                        $stat = 'approved';
                        $data->status = 'Approved';
                    }
                    if ($data->status == 5) {
                        $stat = 'appointed';
                        $data->status = 'Appointed';
                    }
                    if ($data->status == 6) {
                        $stat = 'order';
                        $data->status = 'Appointment Order';
                    }
                    if ($data->status == 7) {
                        $stat = 'signed';
                        $data->status = 'Signed by DP';
                    }
                    if ($data->status == 8) {
                        $stat = 'transfer';
                        $data->status = 'Transferred';
                    }

                    $data->formSubStat = $stat;
                }

                $html = view('admin.generatedPDFStatus', ['empList' => $empList], ['empListArray' => $empListArray])->render();
                //return $empList;
                // dd( $html );
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $dompdf->stream();
               // $dompdf->stream('admin.generatedPDFStatus', ['Attachment' => false]);
            }
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////
    // get cmis employee

    public function getEmployees()
    {
        $cmis_emp_controller = EmployeeCmis::all();
        return $cmis_emp_controller;
    }

    public function discardStartEmp($ein)
    {
        //Changes done for DIHAS
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $ein = Crypt::decryptString($ein);

        EmpFormSubmissionStatus::where('ein', $ein)->delete();
        FamilyMembers::where('ein', $ein)->delete();
        $geEmpDetails = ProformaModel::get()->where('ein', $ein)->first();
        ProformaModel::where('ein', $ein)->delete();
        return back()->with('message', 'EIN: ' . $geEmpDetails->ein . 'Deleted successfully!');

        session()->forget(['ein', 'from_emp_ein']);
        // session()->flush();
        $ein = null;
    }

    public function discardApplicant($ein)
    {
        //Changes done for DIHAS
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $ein = Crypt::decryptString($ein);

        EmpFormSubmissionStatus::where('ein', $ein)->delete();
        //FileModel::where( 'ein', $ein )->delete();
        FamilyMembers::where('ein', $ein)->delete();
        $geEmpDetails = ProformaModel::get()->where('ein', $ein)->first();
        ProformaModel::where('ein', $ein)->delete();
        return back()->with('message', 'EIN: ' . $geEmpDetails->ein . 'Deleted successfully!');

        session()->forget(['ein', 'from_emp_ein']);
        // session()->flush();
        $ein = null;
    }

    // get suv division

    public function getSubDivsion($districtId)
    {

        // sub division
        $subDiv = null;
        $assemConst = null;

        $Dist = District::get()->where('id', $districtId)->first();
        if ($Dist != null) {
            $subDiv = SubDivision::get()->where('district_cd_cmis', $Dist->district_code_census)->toArray();
            $assemConst = AssemblyConstituency::get()->where('district_cd', $Dist->district_code_census)->toArray();
        }

        // sub division

        $responseData = ['suvDiv' => $subDiv, 'assemConst' => $assemConst];
        return $responseData;
    }

    // // get assembly constituency
    // public function getAssemblyConst( $districtId )
    // {

    //     // sub division
    //     $assemConst = AssemblyConstituency::get()->where( 'district_cd', $districtId );
    //     return $assemConst->toArray();
    // }
    // // get Pay commisson  pay scale
    // public function getPayComsScale( $comsCode )
    // {

    //     // sub division
    //     $payScale = PayScale::get()->where( 'psc_paycomm_cd', $comsCode );
    //     return $payScale->toArray();
    // }

    // get banck branch

    // DIHAS process form descriptive role

    public function viewForm($id)
    {

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        //the above code needed for menu of header as per user

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

        // return $ein;

        $proformas = ProformaModel::get()->where('ein', $ein)->first();
        //dd( $proformas );
        $stateDetails = State::getOption()->get();

        $cur_districts = District::getOptionByState1($proformas->emp_state)->get();
        $per_districts = District::getOptionByState1($proformas->emp_state_ret)->get();

        $cur_subdivision = SubDivision::getOptionByDistrict1($proformas->emp_addr_district)->get();
        $per_subdivision = SubDivision::getOptionByDistrict1($proformas->emp_addr_district_ret)->get();

        $educations = EducationModel::get()->toArray();

        $Gender = GenderModel::get()->toArray();
        $Caste = CasteModel::get()->toArray();
        $Relationship = RelationshipModel::get()->toArray();
        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        //return 1;

        $cd_grade = array();
        // $notfound = '';

        $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
            'dept_code' => $proformas->dept_id,
            'token' => 'b000e921eeb20a0d395e341dfcd6117a',
        ]);
        $cd_grade = json_decode($response->getBody(), true);
        $post = [];
        foreach ($cd_grade as $cdgrade) {
            if ($cdgrade['group_code'] == 'C' || $cdgrade['group_code'] == 'D') {
                $post[] = $cdgrade;
            }
            // else {
            //     $post[] = [];
            // }
        }

        //return $ein;
        $userDetails = ProformaModel::get()->where('ein', $ein)->toArray();
        // $userDetails = ProformaModel::get()->where( 'ein', $ein )->where( 'uploaded_id', $getUser->id )->toArray();
        if (count($userDetails) == 0) {
            $data = null;
            // return view( 'admin/Form/form', compact( 'data' ) );
        } else {

            foreach ($userDetails as $val) {
                $data = $val;
            }
            // check form status
            $getUploader = ProformaModel::get()->where('ein', $ein)->first();
            $emp_form_stat = ProformaModel::get()->where('ein', $ein)->first();
            //$emp_form_stat = ProformaModel::get()->where( 'ein', $ein )->where( 'uploaded_id', $getUser->id )->first();

            if ($emp_form_stat->status == 1) {
                $status = 1;
                // return redirect()->back()->with( 'message', 'Already proceeded! Click Form menu to view forms!' );
            } else {
                $status = 0;
                // return redirect()->back()->with( 'error_message', 'No Data Found!' );
            }
            // end check
            $getFormFields = FormFieldMaster::get()->where('form_id', 1)->toArray();

            $fieldCollection = [];
            foreach ($getFormFields as $dataField) {
                if ($dataField['iseditable'] == 'Y') {

                    array_push($fieldCollection, $dataField['controll_name']);
                }
            }

            $empForm_status = EmpFormSubmissionStatus::orderBy('form_id')->get()->where('ein', $ein);
            $formStatArray = [];
            if (count($empForm_status) != 0) {
                $formStatArray = [];
                $x = 1;
                foreach ($empForm_status as $rowData) {
                    if ($rowData->form_id == $x) {

                        $formStatArray[] = ['form-' . $x => $rowData->submit_status];
                    }
                    $x = $x + 1;
                }
            }

            return view('admin/Form/form', compact('deptListArray', 'getUploader', 'proformas', 'cur_districts', 'per_districts', 'cur_subdivision', 'per_subdivision', 'Caste', 'Relationship', 'Gender', 'formStatArray', 'status', 'fieldCollection', 'data', 'stateDetails', 'post', 'educations'));
        }
    }
    ///////////////////////////////////////////////////////////////////////////////

    public function viewFormWord($id, Request $request)
    {
        // this is not used as this was used for word editor

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        //the above code needed for menu of header as per user
        // if ( Session::get( $getUser->role_id ) !== null ) {
        // dd( $id )    ;
        $id = Crypt::decryptString($id);

        //     //id is encrypted and send through URL
        //    // Below ein is passed in the session
        if (session()->has('ein')) {
            $session_ein = session()->get('ein');
        }
        // set a session emp EIN
        session()->put('ein', $id);
        $ein = session()->get('ein');

        $data = ProformaModel::get()->where('ein', $ein)->where('status', 3)->first();

        $uoGeneration = UOGenerationModel::orderBy('id', 'DESC')->first();

        //GETTING DATA FROM number_generator table
        $fix = UoNomenclatureModel::get()->first();
        //dd( $fix );
        $prefixData = $fix->uo_format;
        $yearData = $fix->year;
        $suffixData = $fix->suffix;

        if ($uoGeneration != null) {
            $lastID = $uoGeneration->id;
            $nextNumber = $lastID + 1;

            $getUOFileNo = $prefixData . str_pad($nextNumber, $fix->uo_file_no, '0', STR_PAD_LEFT) . '/' . $yearData . '/' . $suffixData;
        } else {
            $lastID = 0;
            $nextNumber = $lastID + 1;
            $getUOFileNo = $prefixData . str_pad($nextNumber, $fix->uo_file_no, '0', STR_PAD_LEFT) . '/' . $yearData . '/' . $suffixData;
        }

        $getDate = Carbon::now()->format('Y-m-d');

        $uoGeneration = new UOGenerationModel();
        $uoGeneration->ein = $data->ein;
        $uoGeneration->uo_number = $getUOFileNo;
        $uoGeneration->appl_number = $data->appl_number;
        $uoGeneration->generated_by = $getUser->name;
        $uoGeneration->generated_on = $getDate;

        $uoGeneration->save();

        return view('admin/Form/form_word');
    }

    /** For Group UO */

    function viewFormWordGroup(Request $request)
    {
        // this is not used as this was used for word editor
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $selectedGrades = $request->input('selectedGrades', []);
        session()->put('selectedGrades', $selectedGrades);

        foreach ($selectedGrades as $grade) {
            $empList = ProformaModel::where('ein', $grade)->where('status', 3)->paginate(15);

            foreach ($empList as $data) {

                $uoGeneration = UOGenerationModel::orderBy('id', 'DESC')->first();

                //GETTING DATA FROM number_generator table
                $fix = UoNomenclatureModel::get()->first();
                //dd( $fix );
                $prefixData = $fix->uo_format;
                $yearData = $fix->year;
                $suffixData = $fix->suffix;

                if ($uoGeneration != null) {
                    $lastID = $uoGeneration->id;
                    $nextNumber = $lastID + 1;

                    $getUOFileNo = $prefixData . str_pad($nextNumber, $fix->uo_file_no, '0', STR_PAD_LEFT) . '/' . $yearData . '/' . $suffixData;
                } else {
                    $lastID = 0;
                    $nextNumber = $lastID + 1;
                    $getUOFileNo = $prefixData . str_pad($nextNumber, $fix->uo_file_no, '0', STR_PAD_LEFT) . '/' . $yearData . '/' . $suffixData;
                }

                $getDate = Carbon::now()->format('Y-m-d');

                $uoGeneration = new UOGenerationModel();
                $uoGeneration->ein = $data->ein;
                $uoGeneration->uo_number = $getUOFileNo;
                $uoGeneration->appl_number = $data->appl_number;
                $uoGeneration->generated_by = $getUser->name;
                $uoGeneration->generated_on = $getDate;

                $uoGeneration->save();
            }
        }

        return view('admin/Form/form_word_selected');
    }
    //////////////////////////////////////////////////////////////////////////////////////////

    // public function viewUOForm( $id, Request $request )
    // {

    //         $user_id = Auth::user()->id;
    //         $getUser = User::get()->where( 'id', $user_id )->first();
    //         //the above code needed for menu of header as per user
    //         // if ( Session::get( $getUser->role_id ) !== null ) {
    //         // dd( $id )    ;
    //         $id = Crypt::decryptString( $id );

    //         //     //id is encrypted and send through URL
    //         //    // Below ein is passed in the session
    //         if ( session()->has( 'ein' ) ) {
    //             $session_ein = session()->get( 'ein' );
    //         }
    //         // set a session emp EIN
    //         session()->put( 'ein', $id );
    //         $ein = session()->get( 'ein' );

    //         $data = ProformaModel::get()->where( 'ein', $ein )->first();

    //         return view( 'admin/Form/form_word' );

    //     }

    ////////////////////////////////////////////////////////////////////////////////
    //verify applicant

    public function verifyForm($id)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $id = Crypt::decryptString($id);
        //dd( $id );
        $dateToday = new DateTime(date('m/d/Y'));
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
        //dd( $ein );
        $empDetails = ProformaModel::get()->where('ein', $ein)->first();
       

        if ($empDetails) {
            $empDetails->update([
                'file_status' => 5, //File is with DP Assistant
                // 'received_by'=> $receiver, //previous sender
                // 'sent_by'=> $getUser->name, //current sender
                'forwarded_on' => $dateToday,
                'status' => 2,
                //2 is for verified and 1 for submitted

            ]);
        }

        return redirect()->route('viewStartEmp')->with('message', 'Applicant details is verified Succesfully!!!');
    }

    //revert applicant by HOD Assistant to Applicant

    public function revertForm($id, Request $request)
    {
        // dd( $request->toArray() );
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $dateToday = new DateTime(date('m/d/Y'));
        //dd( $request->remarks );

        $Remarks = RemarksModel::get()->toArray();
        // $empDetails = ProformaModel::get()->where( 'ein', $request->ein )->where( 'uploaded_id', $getUser->id )->first();

        $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();
        // $getUser1 = User::get()->where( 'id', $empDetails->forwarded_by )->first();
        $getUser1 = User::get()->where('id', $empDetails->uploaded_id)->first();

        $getUser2 = User::get()->where('id', $empDetails->uploaded_id)->toArray();
        //dd( $getUser1->name );
        if (count($getUser2) == null) {
            $receiver = null;
            //previous sender
        }
        if (count($getUser2) != null) {
            $receiver = $getUser1->name;
            //previous sender
        }
        //dd( $empDetails->toArray() );
        if ($empDetails != null) {
            $empDetails->update([
                'status' => 0,
                'received_by' => $receiver, //previous sender
                'sent_by' => $getUser->name, //current sender
                'forwarded_on' => $dateToday,
                'rejected_status' => 1,
                'remark' => $request->remark,
                'remark_details' => $request->remark_details
                //2 is for verified and 1 for submitted and 0 back to start
                //reject 1 is for HOD Assistant back to citizen
            ]);
        }
        //write save data for giving remarks

        applicants_statusModel::create([
            'ein' => $request->ein,
            'appl_number' => $empDetails->appl_number,
            'remark' => $request->remark,
            'remark_details' => $request->remark_details,
            'remark_date' => $dateToday,
            'entered_by' => $getUser->id
        ]);

        return redirect()->route('viewStartEmp')->with('message', 'Applicant details is revert Succesfully!!!');
    }

     public function revertFormtoDP($id, Request $request)
    {
        // dd( $request->toArray() );
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $dateToday = new DateTime(date('m/d/Y'));
        //dd( $request->remarks );

        $Remarks = RemarksModel::get()->toArray();
        // $empDetails = ProformaModel::get()->where( 'ein', $request->ein )->where( 'uploaded_id', $getUser->id )->first();

        $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();
        // $getUser1 = User::get()->where( 'id', $empDetails->forwarded_by )->first();
        $getUser1 = User::get()->where('id', $empDetails->uploaded_id)->first();

        $getUser2 = User::get()->where('id', $empDetails->uploaded_id)->toArray();
        //dd( $getUser1->name );
        if (count($getUser2) == null) {
            $receiver = null;
            //previous sender
        }
        if (count($getUser2) != null) {
            $receiver = $getUser1->name;
            //previous sender
        }
        //dd( $empDetails->toArray() );
        if ($empDetails != null) {
            $empDetails->update([
                'status' => 1,
                'file_status' => 5,
                'received_by' => $receiver, //previous sender
                'sent_by' => $getUser->name, //current sender
                'forwarded_on' => $dateToday,
                'rejected_status' => 1,
                'remark' => $request->remark,
                'remark_details' => $request->remark_details
                //2 is for verified and 1 for submitted and 0 back to start
                //reject 1 is for HOD Assistant back to citizen
            ]);
        }
        //write save data for giving remarks

        applicants_statusModel::create([
            'ein' => $request->ein,
            'appl_number' => $empDetails->appl_number,
            'remark' => $request->remark,
            'remark_details' => $request->remark_details,
            'remark_date' => $dateToday,
            'entered_by' => $getUser->id
        ]);

        return redirect()->route('viewStartEmp')->with('message', 'Applicant details is revert Succesfully to DP!!!');
    }


    //revert applicant by HOD Assistant

    public function revertFormVerified($id, Request $request)
    {
        //dd( $request->toArray() );
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $dateToday = new DateTime(date('m/d/Y'));
        //dd( $request->remarks );

        $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();
        // $getUser1 = User::get()->where( 'id', $empDetails->uploaded_id )->first();
        $getUser1 = User::get()->where('id', $empDetails->uploaded_id)->first();

        $getUser2 = User::get()->where('id', $empDetails->uploaded_id)->toArray();
        //dd( $getUser1->name );
        if (count($getUser2) == null) {
            $receiver = null;
            //previous sender
        }
        if (count($getUser2) != null) {
            $receiver = $getUser1->name;
            //previous sender
        }

        if ($empDetails != null && $empDetails->uploader_role_id == 77) {
            $empDetails->update([
                'status' => 0,
                'received_by' => $receiver, //previous sender
                'sent_by' => $getUser->name, //current sender
                'forwarded_on' => $dateToday,
                'rejected_status' => 1, //if the file is uploaded by citizen
                'remark' => $request->remark,
                'remark_details' => $request->remark_details
                //2 is for verified and 1 for submitted and 0 back to start
                //reject 1 is for HOD Assistant back to citizen
            ]);
        }
        if ($empDetails != null && $empDetails->uploader_role_id != 77) {
            $empDetails->update([
                'status' => 0,
                'received_by' => $receiver, //previous sender
                'sent_by' => $getUser->name, //current sender
                'forwarded_on' => $dateToday,
                'rejected_status' => 0, //if the file is uploaded by Assistant
                'remark' => $request->remark,
                'remark_details' => $request->remark_details
                //2 is for verified and 1 for submitted and 0 back to start
                //reject 1 is for HOD Assistant back to citizen
            ]);
        }

        //write save data for giving remarks

        applicants_statusModel::create([
            'ein' => $request->ein,
            'appl_number' => $empDetails->appl_number,
            'remark' => $request->remark,
            'remark_details' => $request->remark_details,
            'remark_date' => $dateToday,
            'entered_by' => $getUser->id
        ]);

        return redirect()->route('viewEmpSubmitted')->with('message', 'Applicant details is revert Succesfully!!!');
    }

    //revert from HOD to HODAssistant

    public function revertFromHOD($id, Request $request)
    {
        // dd( $request->toArray() );
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $dateToday = new DateTime(date('m/d/Y'));
        //dd( $request->remarks );

        // $empDetails = ProformaModel::get()->where( 'ein', $request->ein )->where( 'uploaded_id', $getUser->id )->first();

        $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();
        // $getUser1 = User::get()->where( 'id', $empDetails->forwarded_by )->first();

        // $getUser2 = User::get()->where( 'id', $empDetails->forwarded_by )->toArray();

        $getUser1 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 1)->first();

        $getUser2 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 1)->toArray();

        //dd( $getUser1->name );
        if (count($getUser2) == null) {
            $receiver = null;
            //previous sender
        }
        if (count($getUser2) != null) {
            $receiver = $getUser1->name;
            //previous sender
        }
        //dd( $empDetails->toArray() );
        if ($empDetails != null) {
            $empDetails->update([
                'status' => 0, //back to not submitted from hod to hod assistant
                'received_by' => $receiver, //previous sender
                'sent_by' => $getUser->name, //current sender
                'forwarded_on' => $dateToday,
                'rejected_status' => 2, //can be seen by HOD assistant

                'remark' => $request->remark,
                'remark_details' => $request->remark_details,
                'file_status' => 1   //now file is back to hod assistant code change from 2 to 1

                //2 is for verified and 1 for submitted and 0 back to start
            ]);
        }
        //write save data for giving remarks

        applicants_statusModel::create([
            'ein' => $request->ein,
            'appl_number' => $empDetails->appl_number,
            'remark' => $request->remark,
            'remark_details' => $request->remark_details,
            'remark_date' => $dateToday,
            'entered_by' => $getUser->id
        ]);

        return redirect()->route('viewForwardEmp')->with('message', 'Applicant details is revert Succesfully!!!');
    }
    //revert from ADAssistant to HOD

    public function revertFromADAssistant($id, Request $request)
    {
        // dd( $request->toArray() );
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $dateToday = new DateTime(date('m/d/Y'));
        //dd( $request->remarks );

        // $empDetails = ProformaModel::get()->where( 'ein', $request->ein )->where( 'uploaded_id', $getUser->id )->first();

        $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();
        // $getUser1 = User::get()->where( 'id', $empDetails->forwarded_by )->first();

        // $getUser2 = User::get()->where( 'id', $empDetails->forwarded_by )->toArray();
        $getUser1 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 2)->first();

        $getUser2 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 2)->toArray();

        //dd( $getUser1->name );
        if (count($getUser2) == null) {
            $receiver = null;
            //previous sender
        }
        if (count($getUser2) != null) {
            $receiver = $getUser1->name;
            //previous sender
        }
        //dd( $empDetails->toArray() );
        if ($empDetails != null) {
            $empDetails->update([
                'status' => 2, //back to not submitted from hod to hod assistant
                'received_by' => $receiver, //previous sender
                'sent_by' => $getUser->name, //current sender
                'forwarded_on' => $dateToday,
                'rejected_status' => 3, //can be seen by HOD
                'remark' => $request->remark,
                'remark_details' => $request->remark_details,
                'file_status' => 2   //now file is back to hod assistant code change from 2 to 1

                //2 is for verified and 1 for submitted and 0 back to start
            ]);
        }
        //write save data for giving remarks

        applicants_statusModel::create([
            'ein' => $request->ein,
            'appl_number' => $empDetails->appl_number,
            'remark' => $request->remark,
            'remark_details' => $request->remark_details,
            'remark_date' => $dateToday,
            'entered_by' => $getUser->id
        ]);

        return redirect()->route('viewForwardToADAssist')->with('message', 'Applicant details is revert Succesfully!!!');
    }

    //revert from ADAssistant to HOD

    public function revertFromADAssistant1($id, Request $request)
    {
        // dd( $request->toArray() );
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $dateToday = new DateTime(date('m/d/Y'));
        //dd( $request->remarks );

        // $empDetails = ProformaModel::get()->where( 'ein', $request->ein )->where( 'uploaded_id', $getUser->id )->first();

        $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();
        // $getUser1 = User::get()->where( 'id', $empDetails->forwarded_by )->first();

        // $getUser2 = User::get()->where( 'id', $empDetails->forwarded_by )->toArray();
        $getUser1 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 2)->first();

        $getUser2 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 2)->toArray();

        //dd( $getUser1->name );
        if (count($getUser2) == null) {
            $receiver = null;
            //previous sender
        }
        if (count($getUser2) != null) {
            $receiver = $getUser1->name;
            //previous sender
        }
        //dd( $empDetails->toArray() );
        if ($empDetails != null) {
            $empDetails->update([
                'status' => 2, //back to not submitted from hod to hod assistant
                'received_by' => $receiver, //previous sender
                'sent_by' => $getUser->name, //current sender
                'forwarded_on' => $dateToday,
                'rejected_status' => 3, //can be seen by HOD
                'remark' => $request->remark,
                'remark_details' => $request->remark_details,
                'file_status' => 2   //now file is back to hod assistant code change from 2 to 1

                //2 is for verified and 1 for submitted and 0 back to start
            ]);
        }
        //write save data for giving remarks

        applicants_statusModel::create([
            'ein' => $request->ein,
            'appl_number' => $empDetails->appl_number,
            'remark' => $request->remark,
            'remark_details' => $request->remark_details,
            'remark_date' => $dateToday,
            'entered_by' => $getUser->id
        ]);

        return redirect()->route('viewRevertedListADAssist')->with('message', 'Applicant details is revert Succesfully!!!');
    }

    //revert from ADNodal to AD Assistant

    public function revertDetailsFromADNodal($id, Request $request)
    {
        // dd( $request->toArray() );
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $dateToday = new DateTime(date('m/d/Y'));
        //dd( $request->remarks );

        // $empDetails = ProformaModel::get()->where( 'ein', $request->ein )->where( 'uploaded_id', $getUser->id )->first();

        $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();
        // $getUser1 = User::get()->where( 'id', $empDetails->forwarded_by )->first();

        // $getUser2 = User::get()->where( 'id', $empDetails->forwarded_by )->toArray();

        $getUser1 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 3)->first();

        $getUser2 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 3)->toArray();

        //dd( $getUser1->name );
        if (count($getUser2) == null) {
            $receiver = null;
            //previous sender
        }
        if (count($getUser2) != null) {
            $receiver = $getUser1->name;
            //previous sender
        }
        if ($empDetails != null) {
            $empDetails->update([
                'status' => 2, //back to not submitted from hod to hod assistant
                'received_by' => $receiver, //previous sender
                'sent_by' => $getUser->name, //current sender
                'forwarded_on' => $dateToday,
                'rejected_status' => 4, //can be seen by HOD
                'remark' => $request->remark,
                'remark_details' => $request->remark_details,
                'file_status' => 3   //now file is back to hod assistant code change from 2 to 1

                //2 is for verified and 1 for submitted and 0 back to start
            ]);
        }
        //write save data for giving remarks

        applicants_statusModel::create([
            'ein' => $request->ein,
            'appl_number' => $empDetails->appl_number,
            'remark' => $request->remark,
            'remark_details' => $request->remark_details,
            'remark_date' => $dateToday,
            'entered_by' => $getUser->id
        ]);

        return redirect()->route('viewForwardToADNodal')->with('message', 'Applicant details is revert Succesfully!!!');
    }
    //revert from ADNodal to AD Assistant

    public function revertDetailsFromADNodal1($id, Request $request)
    {
        // dd( $request->toArray() );
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $dateToday = new DateTime(date('m/d/Y'));
        //dd( $request->remarks );

        // $empDetails = ProformaModel::get()->where( 'ein', $request->ein )->where( 'uploaded_id', $getUser->id )->first();

        $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();

        // $getUser1 = User::get()->where( 'id', $empDetails->forwarded_by )->first();

        // $getUser2 = User::get()->where( 'id', $empDetails->forwarded_by )->toArray();

        $getUser1 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 3)->first();

        $getUser2 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 3)->toArray();

        //dd( $getUser1->name );
        if (count($getUser2) == null) {
            $receiver = null;
            //previous sender
        }
        if (count($getUser2) != null) {
            $receiver = $getUser1->name;
            //previous sender
        }
        if ($empDetails != null) {
            $empDetails->update([
                'status' => 2, //back to not submitted from hod to hod assistant
                'received_by' => $receiver, //previous sender
                'sent_by' => $getUser->name, //current sender
                'forwarded_on' => $dateToday,
                'rejected_status' => 4, //can be seen by HOD
                'remark' => $request->remark,
                'remark_details' => $request->remark_details,
                'file_status' => 3   //now file is back to hod assistant code change from 2 to 1

                //2 is for verified and 1 for submitted and 0 back to start
            ]);
        }
        //write save data for giving remarks

        applicants_statusModel::create([
            'ein' => $request->ein,
            'appl_number' => $empDetails->appl_number,
            'remark' => $request->remark,
            'remark_details' => $request->remark_details,
            'remark_date' => $dateToday,
            'entered_by' => $getUser->id
        ]);

        return redirect()->route('viewRevertedListADNodal')->with('message', 'Applicant details is revert Succesfully!!!');
    }

    //revert from DPAssist to AD Nodal

    public function revertDetailsFromDPAssist($id, Request $request)
    {
        // dd( $request->toArray() );
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $dateToday = new DateTime(date('m/d/Y'));
        //dd( $request->remarks );

        // $empDetails = ProformaModel::get()->where( 'ein', $request->ein )->where( 'uploaded_id', $getUser->id )->first();

        $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();
        // $getUser1 = User::get()->where( 'id', $empDetails->forwarded_by )->first();

        // $getUser2 = User::get()->where( 'id', $empDetails->forwarded_by )->toArray();

        $getUser1 = User::get()->where('dept_id', $empDetails->dept_id)->where('role_id', $getUser->role_id, 4)->first();

        $getUser2 = User::get()->where('dept_id', $empDetails->dept_id)->where('role_id', $getUser->role_id, 4)->toArray();

        //dd( $getUser1->name );
        if (count($getUser2) == null) {
            $receiver = null;
            //previous sender
        }
        if (count($getUser2) != null) {
            $receiver = $getUser1->name;
            //previous sender
        }
        if ($empDetails != null) {
            $empDetails->update([
                'status' => 2, //back to not submitted from hod to hod assistant
                'received_by' => $receiver, //previous sender
                'sent_by' => $getUser->name, //current sender
                'forwarded_on' => $dateToday,
                'rejected_status' => 5, //can be seen by AD Nodal
                'remark' => $request->remark,
                'remark_details' => $request->remark_details,
                'file_status' => 4   //now file is back to AD Nodal code change from 3 to 1

                //2 is for verified and 1 for submitted and 0 back to start
            ]);
        }
        //write save data for giving remarks

        applicants_statusModel::create([
            'ein' => $request->ein,
            'appl_number' => $empDetails->appl_number,
            'remark' => $request->remark,
            'remark_details' => $request->remark_details,
            'remark_date' => $dateToday,
            'entered_by' => $getUser->id
        ]);

        return redirect()->route('selectDeptByDPNodal')->with('message', 'Applicant details is revert Succesfully!!!');
    }

    //revert from DPAssist to AD Nodal

    public function revertDetailsFromDPAssist1($id, Request $request)
    {
        // dd( $request->toArray() );
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $dateToday = new DateTime(date('m/d/Y'));
        //dd( $request->remarks );

        // $empDetails = ProformaModel::get()->where( 'ein', $request->ein )->where( 'uploaded_id', $getUser->id )->first();

        $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();

        // $getUser1 = User::get()->where( 'id', $empDetails->forwarded_by )->first();

        // $getUser2 = User::get()->where( 'id', $empDetails->forwarded_by )->toArray();

        $getUser1 = User::get()->where('dept_id', $empDetails->dept_id)->where('role_id', $getUser->role_id, 4)->first();

        $getUser2 = User::get()->where('dept_id', $empDetails->dept_id)->where('role_id', $getUser->role_id, 4)->toArray();

        //dd( $getUser1->name );
        if (count($getUser2) == null) {
            $receiver = null;
            //previous sender
        }
        if (count($getUser2) != null) {
            $receiver = $getUser1->name;
            //previous sender
        }
        if ($empDetails != null) {
            $empDetails->update([
                'status' => 2, //back to not submitted from hod to hod assistant
                'received_by' => $receiver, //previous sender
                'sent_by' => $getUser->name, //current sender
                'forwarded_on' => $dateToday,
                'rejected_status' => 5, //can be seen by AD Nodal
                'remark' => $request->remark,
                'remark_details' => $request->remark_details,
                'file_status' => 4   //now file is back to AD Nodal code change from 3 to 1

                //2 is for verified and 1 for submitted and 0 back to start
            ]);
        }
        //write save data for giving remarks

        applicants_statusModel::create([
            'ein' => $request->ein,
            'appl_number' => $empDetails->appl_number,
            'remark' => $request->remark,
            'remark_details' => $request->remark_details,
            'remark_date' => $dateToday,
            'entered_by' => $getUser->id
        ]);

        return redirect()->route('viewRevertedListDP')->with('message', 'Applicant details is revert Succesfully!!!');
    }

    //revert from DPAssist to AD Nodal

    public function revertDetailsFromDPNodal($id, Request $request)
    {
        // dd( $request->toArray() );
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $dateToday = new DateTime(date('m/d/Y'));
        //dd( $request->remarks );

        // $empDetails = ProformaModel::get()->where( 'ein', $request->ein )->where( 'uploaded_id', $getUser->id )->first();

        $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();
        // $getUser1 = User::get()->where( 'id', $empDetails->forwarded_by )->first();

        // $getUser2 = User::get()->where( 'id', $empDetails->forwarded_by )->toArray();

        $getUser1 = User::get()->where('role_id', $getUser->role_id, 5)->first();

        $getUser2 = User::get()->where('role_id', $getUser->role_id, 5)->toArray();

        //dd( $getUser1->name );
        if (count($getUser2) == null) {
            $receiver = null;
            //previous sender
        }
        if (count($getUser2) != null) {
            $receiver = $getUser1->name;
            //previous sender
        }
        if ($empDetails != null) {
            $empDetails->update([
                'status' => 2, //back to not submitted from hod to hod assistant
                'received_by' => $receiver, //previous sender
                'sent_by' => $getUser->name, //current sender
                'forwarded_on' => $dateToday,
                'rejected_status' => 6, //can be seen by AD Nodal
                'remark' => $request->remark,
                'remark_details' => $request->remark_details,
                'file_status' => 5   //now file is back to AD Nodal code change from 3 to 1

                //2 is for verified and 1 for submitted and 0 back to start
            ]);
        }
        //write save data for giving remarks

        applicants_statusModel::create([
            'ein' => $request->ein,
            'appl_number' => $empDetails->appl_number,
            'remark' => $request->remark,
            'remark_details' => $request->remark_details,
            'remark_date' => $dateToday,
            'entered_by' => $getUser->id
        ]);

        return redirect()->route('selectDeptByDPNodal')->with('message', 'Applicant details is revert Succesfully!!!');
    }
    /////////////////////////////////////////////////////////////////////////////////////////////
public function forwardByDPAssistantToHODAssistant($id, Request $request)
    {
        //dd( $request->toArray() );
        // $request->remark_details == null;

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $dateToday = new DateTime(date('m/d/Y'));
        // dd( $getUser );
        $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();
        $getUser1 = User::get()->where('role_id', $getUser->role_id, 6)->first();

        $getUser2 = User::get()->where('role_id', $getUser->role_id, 6)->toArray();
        //dd( $getUser1->name );
       

        // $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();
        // $getUser1 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 2)->first();

        // $getUser2 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 2)->toArray();
        // dd( $getUser1, $getUser2 );
        if (count($getUser2) == null) {
            $receiver = null;
            //previous sender
        }
        if (count($getUser2) != null) {
            $receiver = $getUser1->name;
            //previous sender
        }
        // new from here change 17 may 2024
         // dd($request->all());
         if ($request->hasFile('pdf_file')) {
            $pdfFile = $request->file('pdf_file');
            $pdfFileName = $pdfFile->getClientOriginalName();
            $pdfFilePath = $pdfFile->storeAs('public', $pdfFileName);

            // Update ProformaModel with file path
            $empDetails->pdf_file = $pdfFileName;
            $empDetails->save();
        }
       // dd($request->file('pdf_file'));
        //end here change 17 may 2024

        if ($empDetails != null) {
            $empDetails->update([
                'file_status' => 2, //move to HOD Assistant
                'received_by' => $receiver, //previous sender
                'sent_by' => $getUser->name, //current sender
                'forwarded_by' => $getUser->id,
                'forwarded_on' => $dateToday,
                'remark' => $request->remark,
                'remark_details' => $request->remark_details,
                //2 is for verified and 1 for submitted and 0 back to start
            ]);
        }
        //write save data for giving remarks
        // dd( $empDetails->appl_number );

        applicants_statusModel::create([
            'ein' => $request->ein,
            'appl_number' => $empDetails->appl_number,
            'remark' => $request->remark,
            'remark_details' => $request->remark_details,
            'remark_date' => $dateToday,
            'entered_by' => $getUser->id
        ]);

        return redirect()->route('viewStartEmp')->with('message', 'Applicant details is forwarded to Department Succesfully!!!');
    }

    //forward applicant to HOD by HOD Assistant

    public function forwardHOD($id, Request $request)
    {
        //dd( $request->toArray() );
        // $request->remark_details == null;

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $dateToday = new DateTime(date('m/d/Y'));
        // dd( $getUser );

        $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();
        $getUser1 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 2)->first();

        $getUser2 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 2)->toArray();
        // dd( $getUser1, $getUser2 );
        if (count($getUser2) == null) {
            $receiver = null;
            //previous sender
        }
        if (count($getUser2) != null) {
            $receiver = $getUser1->name;
            //previous sender
        }
        // new from here change 17 may 2024
         // dd($request->all());
         if ($request->hasFile('pdf_file')) {
            $pdfFile = $request->file('pdf_file');
            $pdfFileName = $pdfFile->getClientOriginalName();
            $pdfFilePath = $pdfFile->storeAs('public', $pdfFileName);

            // Update ProformaModel with file path
            $empDetails->pdf_file = $pdfFileName;
            $empDetails->save();
        }
       // dd($request->file('pdf_file'));
        //end here change 17 may 2024

        if ($empDetails != null) {
            $empDetails->update([
                'file_status' => 2, //move to HOD Assistant
                'received_by' => $receiver, //previous sender
                'sent_by' => $getUser->name, //current sender
                'forwarded_by' => $getUser->id,
                'forwarded_on' => $dateToday,
                'remark' => $request->remark,
                'remark_details' => $request->remark_details,
                //2 is for verified and 1 for submitted and 0 back to start
            ]);
        }
        //write save data for giving remarks
        // dd( $empDetails->appl_number );

        applicants_statusModel::create([
            'ein' => $request->ein,
            'appl_number' => $empDetails->appl_number,
            'remark' => $request->remark,
            'remark_details' => $request->remark_details,
            'remark_date' => $dateToday,
            'entered_by' => $getUser->id
        ]);

        return redirect()->route('viewStartEmp')->with('message', 'Applicant details is forwarded to HOD Succesfully!!!');
    }

    //forward applicant to HODToADAssist

    public function forwardByHODToADAssist($id, Request $request)
    {
        //dd( $request->toArray() );
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $dateToday = new DateTime(date('m/d/Y'));
        //dd( $getUser );

        $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();
        $getUser1 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 3)->first();

        $getUser2 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 3)->toArray();
        // dd( $getUser1, $getUser2 );

        if (count($getUser2) == null) {
            $receiver = null;
            //previous sender
        }
        if (count($getUser2) != null) {
            $receiver = $getUser1->name;
            //previous sender
        }
        if ($empDetails != null) {
            $empDetails->update([
                'file_status' => 3, //move to HOD Assistant
                'received_by' => $receiver, //previous sender
                'sent_by' => $getUser->name, //current sender
                'forwarded_by' => $getUser->id,
                'forwarded_on' => $dateToday,
                'remark' => $request->remark,
                'remark_details' => $request->remark_details,
                //2 is for verified and 1 for submitted and 0 back to start
            ]);
        }
        //write save data for giving remarks
        // dd( $empDetails->appl_number );

        applicants_statusModel::create([
            'ein' => $request->ein,
            'appl_number' => $empDetails->appl_number,
            'remark' => $request->remark,
            'remark_details' => $request->remark_details,
            'remark_date' => $dateToday,
            'entered_by' => $getUser->id
        ]);

        return redirect()->route('viewForwardEmp')->with('message', 'Applicant details is Forwarded to Administrative Department!!!');
    }

    //forward applicant to ADAssistToADNodal

    public function forwardByADAssistToADNodal($id, Request $request)
    {
        // dd( $request->toArray() );
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $dateToday = new DateTime(date('m/d/Y'));
        //dd( $request->remarks );

        $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();
        $getUser1 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 4)->first();

        $getUser2 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 4)->toArray();
        //dd( $getUser1->name );
        if (count($getUser2) == null) {
            $receiver = null;
            //previous sender
        }
        if (count($getUser2) != null) {
            $receiver = $getUser1->name;
            //previous sender
        }
        if ($empDetails != null) {
            $empDetails->update([
                'file_status' => 4, //move to HOD Assistant
                'received_by' => $receiver, //previous sender
                'sent_by' => $getUser->name, //current sender
                'forwarded_by' => $getUser->id,
                'forwarded_on' => $dateToday,
                'remark' => $request->remark,
                'remark_details' => $request->remark_details,
                'efile_ad' => $request->efile_ad,
                'ad_file_link' => $request->ad_efile_link,

                //2 is for verified and 1 for submitted and 0 back to start
            ]);
        }
        //write save data for giving remarks
        // dd( $empDetails->appl_number );

        applicants_statusModel::create([
            'ein' => $request->ein,
            'appl_number' => $empDetails->appl_number,
            'remark' => $request->remark,
            'remark_details' => $request->remark_details,
            'remark_date' => $dateToday,
            'entered_by' => $getUser->id,
            'efile_ad' => $request->efile_ad,
            'ad_file_link' => $request->ad_efile_link,
            //  'ad_efile_increment' => $dpEfileIdMulti,
            'role_id' => $getUser->role_id,
        ]);

        return redirect()->route('viewForwardToADAssist')->with('message', 'Applicant details is Forwarded to Administrative Department Nodal Officer!!!');
    }

    //forward applicant to ADNodalToDPAssist

    public function forwardDetailsFromADNodalToDPAssist($id, Request $request)
    {
        //dd( $request->toArray() );
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $dateToday = new DateTime(date('m/d/Y'));
        //dd( $request->remarks );

        $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();
        // dd( $getUser->dept_id, $getUser->role_id, 5 );
        $getUser1 = User::get()->where('role_id', $getUser->role_id, 5)->first();

        $getUser2 = User::get()->where('role_id', $getUser->role_id, 5)->toArray();
        // dd( $getUser2 );
        if (count($getUser2) == null) {
            $receiver = null;
            //previous sender
        }
        if (count($getUser2) != null) {
            $receiver = $getUser1->name;
            //previous sender
        }
        if ($empDetails != null) {
            $empDetails->update([
                'file_status' => 5, //move to HOD Assistant
                'received_by' => $receiver, //previous sender
                'sent_by' => $getUser->name, //current sender
                'forwarded_by' => $getUser->id,
                'forwarded_on' => $dateToday,
                'remark' => $request->remark,
                'remark_details' => $request->remark_details,
                //2 is for verified and 1 for submitted and 0 back to start
            ]);
        }
        //write save data for giving remarks
        // dd( $empDetails->appl_number );

        applicants_statusModel::create([
            'ein' => $request->ein,
            'appl_number' => $empDetails->appl_number,
            'remark' => $request->remark,
            'remark_details' => $request->remark_details,
            'remark_date' => $dateToday,
            'entered_by' => $getUser->id
        ]);

        return redirect()->route('viewForwardToADNodal')->with('message', 'Applicant details is Forwarded to DP Assistant!!!');
    }

    //forward applicant to DPAssistToDPNodal

    public function forwardDetailsFromDPAssistToDPNodal($id, Request $request)
    {
        // dd( $request->toArray() );
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $dateToday = new DateTime(date('m/d/Y'));
        //dd( $request->remarks );

        $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();
        $getUser1 = User::get()->where('role_id', $getUser->role_id, 6)->first();

        $getUser2 = User::get()->where('role_id', $getUser->role_id, 6)->toArray();
        //dd( $getUser1->name );
        if (count($getUser2) == null) {
            $receiver = null;
            //previous sender
        }
        if (count($getUser2) != null) {
            $receiver = $getUser1->name;
            //previous sender
        }
        if ($empDetails != null) {
            $empDetails->update([
                'file_status' => 6, //move to HOD Assistant
                'received_by' => $receiver, //previous sender
                'sent_by' => $getUser->name, //current sender
                'forwarded_by' => $getUser->id,
                'forwarded_on' => $dateToday,
                'remark' => $request->remark,
                'remark_details' => $request->remark_details,
                'status' => 3, //Approved for UO Generation
                //2 is for verified and 1 for submitted and 0 back to start
                'efile_dp' => $request->efile_dp,
                'dp_file_link' => $request->dp_efile_link,

            ]);
        }
        //write save data for giving remarks
        // dd( $empDetails->appl_number );

        applicants_statusModel::create([
            'ein' => $request->ein,
            'appl_number' => $empDetails->appl_number,
            'remark' => $request->remark,
            'remark_details' => $request->remark_details,
            'remark_date' => $dateToday,
            'entered_by' => $getUser->id,
            'efile_dp' => $request->efile_dp,
            'dp_file_link' => $request->dp_efile_link,
            'role_id' => $getUser->role_id,
        ]);

        return redirect()->route('selectDeptByDPAssist')->with('message', 'Applicant details is Forwarded to DP Nodal!!!');
    }

    //forward applicant to DPAssistToDPNodal

    public function approvedListFromDP($id, Request $request)
    {
        //dd( $request->toArray() );
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $dateToday = new DateTime(date('m/d/Y'));
        //dd( $request->remarks );

        $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();
        $getUser1 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 5)->first();

        $getUser2 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 5)->toArray();
        //dd( $getUser1->name );
        if (count($getUser2) == null) {
            $receiver = null;
            //previous sender
        }
        if (count($getUser2) != null) {
            $receiver = $getUser1->name;
            //previous sender
        }
        if ($empDetails != null) {
            $empDetails->update([
                'file_status' => 5, //move to DP Assistant
                'status' => 4,  //4 will be approve for UO by DP Nodal
                'received_by' => $receiver, //previous sender
                'sent_by' => $getUser->name, //current sender
                'forwarded_by' => $getUser->id,
                'forwarded_on' => $dateToday,
                'remark' => $request->remark,
                'remark_details' => $request->remark_details,
                //2 is for verified and 1 for submitted and 0 back to start
            ]);
        }
        //write save data for giving remarks
        // dd( $empDetails->appl_number );

        applicants_statusModel::create([
            'ein' => $request->ein,
            'appl_number' => $empDetails->appl_number,
            'remark' => $request->remark,
            'remark_details' => $request->remark_details,
            'remark_date' => $dateToday,
            'entered_by' => $getUser->id
        ]);

        return redirect()->route('selectDeptByDPNodal')->with('message', 'Applicant details is Forwarded to DP Assistant for UO Generation!!!');
    }

    // DIHAS process form descriptive role

    public function viewFormUpdate($id)
    {
        try {
            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            //the above code needed for menu of header as per user
            $id = Crypt::decryptString($id);
            //dd( $getUser );
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

            // return $ein;
            $proformas = ProformaModel::get()->where('ein', $ein)->first();
            //dd( $proformas );
            $stateDetails = State::getOption()->get();

            $cur_districts = District::getOptionByState1($proformas->emp_state)->get();
            $per_districts = District::getOptionByState1($proformas->emp_state_ret)->get();

            $cur_subdivision = SubDivision::getOptionByDistrict1($proformas->emp_addr_district)->get();
            $per_subdivision = SubDivision::getOptionByDistrict1($proformas->emp_addr_district_ret)->get();
            $educations = EducationModel::get()->toArray();
            $Gender = GenderModel::get()->toArray();
            $Caste = CasteModel::get()->toArray();
            $Relationship = RelationshipModel::get()->toArray();
            $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');

            $cd_grade = array();
            // $notfound = '';

            $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
                'dept_code' => $proformas->dept_id,
                'token' => 'b000e921eeb20a0d395e341dfcd6117a',
            ]);
            $cd_grade = json_decode($response->getBody(), true);
            $post = [];
            foreach ($cd_grade as $cdgrade) {
                if ($cdgrade['group_code'] == 'C' || $cdgrade['group_code'] == 'D') {
                    $post[] = $cdgrade;
                }
            }

            //return $ein;
            $userDetails = ProformaModel::get()->where('ein', $ein)->toArray();
            if (count($userDetails) == 0) {
                $data = null;
                // return view( 'admin/Form/form', compact( 'data' ) );
            } else {

                foreach ($userDetails as $val) {
                    $data = $val;
                }

                // dd( $data[ 'third_post_id' ] );

                // check form status
                $getUploader = ProformaModel::get()->where('ein', $ein)->first();

                $emp_form_stat = ProformaModel::get()->where('ein', $ein)->first();
                if ($emp_form_stat->status == 1 || $emp_form_stat->status == 2) {
                    $status = 1;
                    // return redirect()->back()->with( 'message', 'Already proceeded! Click Form menu to view forms!' );
                } else {
                    $status = 0;
                    // return redirect()->back()->with( 'error_message', 'No Data Found!' );
                }
                // end check
                $getFormFields = FormFieldMaster::get()->where('form_id', 1)->toArray();

                $fieldCollection = [];
                foreach ($getFormFields as $dataField) {
                    if ($dataField['iseditable'] == 'Y') {

                        array_push($fieldCollection, $dataField['controll_name']);
                    }
                }

                $empForm_status = EmpFormSubmissionStatus::orderBy('form_id')->get()->where('ein', $ein);
                $formStatArray = [];
                if (count($empForm_status) != 0) {
                    $formStatArray = [];
                    $x = 1;
                    foreach ($empForm_status as $rowData) {
                        if ($rowData->form_id == $x) {

                            $formStatArray[] = ['form-' . $x => $rowData->submit_status];
                        }
                        $x = $x + 1;
                    }
                }

                return view('admin/Form/form_proforma_update', compact('cd_grade', 'deptListArray', 'getUser', 'proformas', 'per_subdivision', 'cur_subdivision', 'per_districts', 'cur_districts', 'Caste', 'Relationship', 'getUploader', 'Gender', 'formStatArray', 'status', 'fieldCollection', 'data', 'stateDetails', 'post', 'educations'));
            }
        } catch (Exception $e) {

            return response()->json([
                'status' => 0,
                'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                //'errors' => $e->getMessage()
            ]);
        }
    }

    /////////////////APPLICANT FORM UPDATE////////////////////////////////

    public function viewApplicantUpdate($id)
    {

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        //the above code needed for menu of header as per user

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

        // return $ein;

        $proformas = ProformaModel::get()->where('ein', $ein)->first();
        //dd( $proformas );
        $stateDetails = State::getOption()->get();

        $cur_districts = District::getOptionByState1($proformas->emp_state)->get();
        $per_districts = District::getOptionByState1($proformas->emp_state_ret)->get();

        $cur_subdivision = SubDivision::getOptionByDistrict1($proformas->emp_addr_district)->get();
        $per_subdivision = SubDivision::getOptionByDistrict1($proformas->emp_addr_district_ret)->get();
        $cd_grade = array();
        // $notfound = '';
        try {
            $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
                'dept_code' => $proformas->dept_id,
                'token' => 'b000e921eeb20a0d395e341dfcd6117a',
            ]);
            $cd_grade = json_decode($response->getBody(), true);
            $post = [];
            foreach ($cd_grade as $cdgrade) {
                if ($cdgrade['group_code'] == 'C' || $cdgrade['group_code'] == 'D') {
                    $post[] = $cdgrade;
                }
                // else {
                //     $post[] = [];
                // }
            }
        } catch (Exception $e) {

            return response()->json([
                'status' => 0,
                'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                //'errors' => $e->getMessage()
            ]);
        }
        $educations = EducationModel::get()->toArray();

        $Gender = GenderModel::get()->toArray();
        $Caste = CasteModel::get()->toArray();
        $Relationship = RelationshipModel::get()->toArray();
        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');

        //return 1;

        //return $ein;
        $userDetails = ProformaModel::get()->where('ein', $ein)->where('uploaded_id', $getUser->id)->toArray();
        if (count($userDetails) == 0) {
            $data = null;
            // return view( 'admin/Form/form', compact( 'data' ) );
        } else {

            foreach ($userDetails as $val) {
                $data = $val;
            }
            // check form status
            $getUploader = ProformaModel::get()->where('ein', $ein)->first();
            $emp_form_stat = ProformaModel::get()->where('ein', $ein)->where('uploaded_id', $getUser->id)->first();
            if ($emp_form_stat->status == 1 || $emp_form_stat->status == 2) {
                $status = 1;
                // return redirect()->back()->with( 'message', 'Already proceeded! Click Form menu to view forms!' );
            } else {
                $status = 0;
                // return redirect()->back()->with( 'error_message', 'No Data Found!' );
            }
            // end check
            $getFormFields = FormFieldMaster::get()->where('form_id', 1)->toArray();

            $fieldCollection = [];
            foreach ($getFormFields as $dataField) {
                if ($dataField['iseditable'] == 'Y') {

                    array_push($fieldCollection, $dataField['controll_name']);
                }
            }

            $empForm_status = EmpFormSubmissionStatus::orderBy('form_id')->get()->where('ein', $ein);
            $formStatArray = [];
            if (count($empForm_status) != 0) {
                $formStatArray = [];
                $x = 1;
                foreach ($empForm_status as $rowData) {
                    if ($rowData->form_id == $x) {

                        $formStatArray[] = ['form-' . $x => $rowData->submit_status];
                    }
                    $x = $x + 1;
                }
            }

            return view('admin/Form/form_pro_applicant_update', compact('deptListArray', 'getUser', 'proformas', 'per_subdivision', 'cur_subdivision', 'per_districts', 'cur_districts', 'Caste', 'Relationship', 'getUploader', 'Gender', 'formStatArray', 'status', 'fieldCollection', 'data', 'stateDetails', 'post', 'grades', 'educations'));
        }
    }

    //////////////////////////////////////////////////////////////////////

    public function removeSessionEIN()
    {
        session()->forget(['ein', 'ein']);
    }

    public function removeSessionEINBL()
    {
        session()->forget(['ein', 'ein']);
    }

    public function viewForm1(Request $request)
    {

        //Below ein is passed in the session
        // $notfound ='';
        $ein = session()->get('ein');

        if ($ein != null) {
            try {
                $user_id = Auth::user()->id;
                $getUser = User::get()->where('id', $user_id)->first();
                //the above code needed for menu of header as per user
                // $id = Crypt::decryptString( $getUser->id );

                $proformas = ProformaModel::get()->where('ein', $ein)->first();
                //dd( $proformas );
                $stateDetails = State::getOption()->get();

                $cur_districts = District::getOptionByState1($proformas->emp_state)->get();
                $per_districts = District::getOptionByState1($proformas->emp_state_ret)->get();

                $cur_subdivision = SubDivision::getOptionByDistrict1($proformas->emp_addr_district)->get();
                $per_subdivision = SubDivision::getOptionByDistrict1($proformas->emp_addr_district_ret)->get();

                $cd_grade = array();
                //dd( $data[ 'field_dept_desc' ] );
                // $notfound = '';

                $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
                    'dept_code' => $proformas->dept_id,
                    'token' => 'b000e921eeb20a0d395e341dfcd6117a',
                ]);
                $cd_grade = json_decode($response->getBody(), true);
                $post = [];
                foreach ($cd_grade as $cdgrade) {
                    if ($cdgrade['group_code'] == 'C' || $cdgrade['group_code'] == 'D') {
                        $post[] = $cdgrade;
                    }
                    // else {
                    //     $post[] = [];
                    // }
                }

                $Gender = GenderModel::get()->toArray();
                $educations = EducationModel::get()->toArray();
                $Caste = CasteModel::get()->toArray();
                $Relationship = RelationshipModel::get()->toArray();
                $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
                //return 1;

                //return $ein;
                $userDetails = ProformaModel::get()->where('ein', $ein)->toArray();

                if (count($userDetails) == 0) {
                    $data = null;
                    // return view( 'admin/Form/form', compact( 'data' ) );
                } else {

                    foreach ($userDetails as $val) {
                        $data = $val;
                    }
                    // dd( $ein );
                    // check form status
                    $getUploader = ProformaModel::get()->where('ein', $ein)->first();

                    $emp_form_stat = ProformaModel::get()->where('ein', $ein)->first();
                    if ($emp_form_stat->status == 1 || $emp_form_stat->status == 2) {
                        $status = 1;
                        // return redirect()->back()->with( 'message', 'Already proceeded! Click Form menu to view forms!' );
                    } else {
                        $status = 0;
                        // return redirect()->back()->with( 'error_message', 'No Data Found!' );
                    }
                    // end check
                    $getFormFields = FormFieldMaster::get()->where('form_id', 1)->toArray();

                    $fieldCollection = [];
                    foreach ($getFormFields as $dataField) {
                        if ($dataField['iseditable'] == 'Y') {

                            array_push($fieldCollection, $dataField['controll_name']);
                        }
                    }

                    $empForm_status = EmpFormSubmissionStatus::orderBy('form_id')->get()->where('ein', $ein);
                    $formStatArray = [];
                    if (count($empForm_status) != 0) {
                        $formStatArray = [];
                        $x = 1;
                        foreach ($empForm_status as $rowData) {
                            if ($rowData->form_id == $x) {

                                $formStatArray[] = ['form-' . $x => $rowData->submit_status];
                            }
                            $x = $x + 1;
                        }
                    }

                    return view('admin/Form/form_proforma_update', compact('deptListArray', 'getUser', 'proformas', 'per_subdivision', 'cur_districts', 'per_districts', 'cur_subdivision', 'Caste', 'Relationship', 'getUploader', 'Gender', 'formStatArray', 'status', 'fieldCollection', 'data', 'stateDetails', 'post', 'educations'));
                }
            } catch (Exception $e) {

                return response()->json([
                    'status' => 0,
                    'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                    //'errors' => $e->getMessage()
                ]);
            }
        } else {

            // dd( $ein );
            //  try {
            $request->session()->forget(['ein']);
            //close previous session
            // $notfound ='';
            ////////////////////////////////////////////////////////////////////////////////////////
            $deptId = $request->input('dept_id_option');

            if (session()->get('deptId') != '' && $request->input('page') == '') {
                $request->session()->forget(['deptId']);
            }

            if (strlen($deptId) > 0) {
                session()->put('deptId', $deptId);
            }
            if (strlen(session()->get('deptId')) > 0) {
                $deptId = session()->get('deptId');
            }
            ///////////////////////////////////////////////////////////////////////////////
            $desigId = $request->input('third_post_id');

            if (session()->get('desigId') != '' && $request->input('page') == '') {
                $request->session()->forget(['desigId']);
            }

            if (strlen($desigId) > 0) {
                session()->put('desigId', $desigId);
            }
            if (strlen(session()->get('desigId')) > 0) {
                $desigId = session()->get('desigId');
            }

            //above for dept and desig in part of form for different dept selection

            $user_id = Auth::user()->id;
            // dd( Auth::user()->id );
            $getUser = User::get()->where('id', $user_id)->first();
            //dd( $getUser );
            $getData = ProformaModel::get()->where('uploaded_id', $user_id)->where('uploader_role_id', 77)->first();
            // dd( $getUser );
            if ($getData != null) {

                return back()->with('error_message', 'You have already applied!!!!');
                //check for citizen who have applied twice reject
            }

            $data = array();
            //dd( $data[ 'field_dept_desc' ] );
            $notfound = '';

            if ($request->input('ein') != '') {
                $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-employee-profile', [
                    'ein' => $request->input('ein'),
                    'token' => 'b000e921eeb20a0d395e341dfcd6117a',
                ]);
                $data = json_decode($response->getBody(), true);
                //dd( $data );

                if (count($data) > 0)
                    $data = $data[0];
                else
                    //dd( $request->input( 'ein' ) );
                    $notfound = 'EIN ' . $request->input('ein') . ' not found in CMIS';
            }

            //dd( $data );

            if ($data != null) {
                $getDept_id = DepartmentModel::get()->where('dept_name', $data['field_dept_desc'])->first();
                //dd( $getDept_id );
                $cmis_dept_id_int = intval($getDept_id->dept_id);
                // dd( $getUser->dept_id, $cmis_dept_id_int );

                if ($getUser->dept_id != null && $getUser->dept_id > 0) {
                    if ($getUser->dept_id != $cmis_dept_id_int) {
                        $notfound = '';
                        $data = array();
                        $notfound = 'EIN ' . $request->input('ein') . ' has department which is not same as the User Department!!!!!';
                        // dd( $notfound );
                    }
                }
                // if ( $getUser->dept_id == null && $getUser->dept_id == 0 ) {

                // }
            }

            $getData1 = ProformaModel::get()->where('uploaded_id', $user_id)->where('ein', $request->input('ein'))->first();
            //dd( $getData );
            if ($getData1 != null) {

                return back()->with('error_message', 'You have already applied!!!!');
                //check for citizen who have applied twice reject
            }

            //to check whether getUser->dept_id is equal to ata[ 'field_dept_desc' ] if not alert

            if ($getData == null && $getUser->role_id != 77) {
                //allow to enter data for departments
                // $notfound ='';
                $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');

                $stateDetails = State::getOption()->get();

                $cd_grade = array();
                //dd( $data[ 'field_dept_desc' ] );

                //  try {
                $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
                    'dept_code' => $getUser->dept_id,
                    'token' => 'b000e921eeb20a0d395e341dfcd6117a',
                ]);
                $cd_grade = json_decode($response->getBody(), true);

                // dd( $post );

                $post = [];
                foreach ($cd_grade as $cdgrade) {
                    if ($cdgrade['group_code'] == 'C' || $cdgrade['group_code'] == 'D') {
                        $post[] = $cdgrade;
                    }
                    // else {
                    //     $post[] = [];
                    // }
                }

                // } catch ( Exception $e ) {

                //     return response()->json( [
                //         'status' => 0,
                //         'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                //         //'errors' => $e->getMessage()
                // ] );
                // }

                $educations = EducationModel::get()->toArray();
                $Gender = GenderModel::get()->toArray();
                $Caste = CasteModel::get()->toArray();
                $Relationship = RelationshipModel::get()->toArray();

                $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');

                //TODO

                //////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //get session for select_deptid

                // $selected_deptid = session()->get( 'select_deptid' );
                // if ( $selected_deptid != '' ) {
                //    // dd( $selected_deptid );
                //     $cd_grade1 = array();
                //     $response = Http::post( 'http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
                //         'dept_code' => $selected_deptid,
                //         'token' => 'b000e921eeb20a0d395e341dfcd6117a',
                // ] );
                //     $cd_grade1 = json_decode( $response->getBody(), true );

                //     $postdept = [];
                //     foreach ( $cd_grade1 as $cdgrade ) {
                //         if ( $cdgrade[ 'group_code' ] == 'C' || $cdgrade[ 'group_code' ] == 'D' ) {
                //             $postdept[] = $cdgrade;
                //         }
                //     }
                //     session()->forget( [ 'select_deptid' ] );

                // } else {
                //    // dd( $selected_deptid );
                //    session()->forget( [ 'select_deptid' ] );
                //     $postdept = [];

                // }
                ///////////////////////////////////////////////////////////////////////////////////////

                return view('admin/Form/form_proforma', compact('deptListArray', 'Caste', 'Relationship', 'getUser', 'Gender', 'stateDetails', 'post', 'educations', 'data', 'notfound'));
            }

            if ($getUser != null && $getUser->role_id == 77) {
                // allow to enter data for fresh citizen
                // $notfound ='';
                $stateDetails = State::getOption()->get();

                $Gender = GenderModel::get()->toArray();
                $Caste = CasteModel::get()->toArray();
                $Relationship = RelationshipModel::get()->toArray();
                $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
                // dd( count( $data ) );
                //  try {
                if (count($data) > 0) {
                    //After EIN pass the designation of concern department will populate in the drop down
                    $cd_grade = array();

                    $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
                        'dept_code' => $data['dept_cd'],
                        'token' => 'b000e921eeb20a0d395e341dfcd6117a',
                    ]);
                    $cd_grade = json_decode($response->getBody(), true);
                } else {
                    $cd_grade = [];
                }

                $post = [];
                foreach ($cd_grade as $cdgrade) {
                    if ($cdgrade['group_code'] == 'C' || $cdgrade['group_code'] == 'D') {
                        $post[] = $cdgrade;
                    }
                    // else {
                    //     $post[] = [];
                    // }
                }
                // } catch ( Exception $e ) {

                //     return response()->json( [
                //         'status' => 0,
                //         'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                //         //'errors' => $e->getMessage()
                // ] );
                // }

                $educations = EducationModel::get()->toArray();
                $formattedDate = today()->format('Y-m-d');
                return view('admin/Form/form_proforma', compact('deptListArray', 'formattedDate', 'Caste', 'Relationship', 'getUser', 'Gender', 'stateDetails', 'post', 'educations', 'data', 'notfound'));
            }
            // } catch ( Exception $e ) {

            //     return response()->json( [
            //         'status' => 0,
            //         'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
            //         //'errors' => $e->getMessage()
            // ] );
            //   }
        }
    }

    public function viewFormBacklog(Request $request)
    {
        //Below ein is passed in the session
        // $notfound ='';
        try {
            $ein = session()->get('ein');
            $notfound = '';
            if ($ein != null) {
                $user_id = Auth::user()->id;
                $getUser = User::get()->where('id', $user_id)->first();

                $proformas = ProformaModel::get()->where('ein', $ein)->first();
                //dd( $proformas );
                $stateDetails = State::getOption()->get();

                $cur_districts = District::getOptionByState1($proformas->emp_state)->get();
                $per_districts = District::getOptionByState1($proformas->emp_state_ret)->get();

                $cur_subdivision = SubDivision::getOptionByDistrict1($proformas->emp_addr_district)->get();
                $per_subdivision = SubDivision::getOptionByDistrict1($proformas->emp_addr_district_ret)->get();
                //only data from CMIS API for post
                $cd_grade = array();
                //dd( $data[ 'field_dept_desc' ] );
                // $notfound = '';

                $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
                    'dept_code' => $proformas->dept_id,
                    'token' => 'b000e921eeb20a0d395e341dfcd6117a',
                ]);
                $cd_grade = json_decode($response->getBody(), true);

                $post = [];
                foreach ($cd_grade as $cdgrade) {
                    if ($cdgrade['group_code'] == 'C' || $cdgrade['group_code'] == 'D') {
                        $post[] = $cdgrade;
                    }
                    // else {
                    //     $post[] = [];
                    // }
                }

                $educations = EducationModel::get()->toArray();

                //grade display from CMIS API
                $Gender = GenderModel::get()->toArray();
                $Caste = CasteModel::get()->toArray();
                $Relationship = RelationshipModel::get()->toArray();
                $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');

                //return 1;

                //return $ein;
                $userDetails = ProformaModel::get()->where('ein', $ein)->toArray();

                if (count($userDetails) == 0) {
                    $data = null;
                    // return view( 'admin/Form/form', compact( 'data' ) );
                } else {

                    foreach ($userDetails as $val) {
                        $data = $val;
                    }
                    // dd( $ein );
                    // check form status
                    $getUploader = ProformaModel::get()->where('ein', $ein)->first();

                    $emp_form_stat = ProformaModel::get()->where('ein', $ein)->first();
                    if ($emp_form_stat->status == 1 || $emp_form_stat->status == 2) {
                        $status = 1;
                        // return redirect()->back()->with( 'message', 'Already proceeded! Click Form menu to view forms!' );
                    } else {
                        $status = 0;
                        // return redirect()->back()->with( 'error_message', 'No Data Found!' );
                    }
                    // end check
                    $getFormFields = FormFieldMaster::get()->where('form_id', 1)->toArray();

                    $fieldCollection = [];
                    foreach ($getFormFields as $dataField) {
                        if ($dataField['iseditable'] == 'Y') {

                            array_push($fieldCollection, $dataField['controll_name']);
                        }
                    }

                    $empForm_status = EmpFormSubmissionStatus::orderBy('form_id')->get()->where('ein', $ein);
                    $formStatArray = [];
                    if (count($empForm_status) != 0) {
                        $formStatArray = [];
                        $x = 1;
                        foreach ($empForm_status as $rowData) {
                            if ($rowData->form_id == $x) {

                                $formStatArray[] = ['form-' . $x => $rowData->submit_status];
                            }
                            $x = $x + 1;
                        }
                    }

                    return view('admin/Form/form_proforma_update', compact('deptListArray', 'getUser', 'Caste', 'proformas', 'per_subdivision', 'cur_districts', 'per_districts', 'cur_subdivision', 'Relationship', 'getUploader', 'Gender', 'formStatArray', 'status', 'fieldCollection', 'data', 'stateDetails', 'post', 'educations'));
                }
            } else {

                //dd( $ein );
                //  $notfound ='';
                $request->session()->forget(['ein', 'ein']);
                //close previous session

                $user_id = Auth::user()->id;
                // dd( Auth::user()->id );
                $getUser = User::get()->where('id', $user_id)->first();
                //dd( $getUser );
                $getData = ProformaModel::get()->where('uploaded_id', $user_id)->where('uploader_role_id', 77)->first();
                //dd( $getData );
                if ($getData != null) {

                    return back()->with('error_message', 'You have already applied!!!!');
                    //check for citizen who have applied twice reject
                }

                $data = array();
                //dd( $data[ 'field_dept_desc' ] );
                $notfound = '';
                // try {
                if ($request->input('ein') != '') {
                    $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-employee-profile', [
                        'ein' => $request->input('ein'),
                        'token' => 'b000e921eeb20a0d395e341dfcd6117a',
                    ]);
                    $data = json_decode($response->getBody(), true);
                    if (count($data) > 0)
                        $data = $data[0];
                    else
                        $notfound = 'EIN ' . $request->input('ein') . ' not found in CMIS';
                }
                // } catch ( Exception $e ) {

                //     return response()->json( [
                //         'status' => 0,
                //         'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                //         //'errors' => $e->getMessage()
                // ] );
                // }

                if ($data != null) {
                    $getDept_id = DepartmentModel::get()->where('dept_name', $data['field_dept_desc'])->first();

                    $cmis_dept_id_int = intval($getDept_id->dept_id);
                    // dd( $getUser->dept_id, $cmis_dept_id_int );

                    if ($getUser->dept_id != null && $getUser->dept_id > 0) {
                        if ($getUser->dept_id != $cmis_dept_id_int) {
                            $notfound = '';
                            $data = array();
                            $notfound = 'EIN ' . $request->input('ein') . ' has department which is not same as the User Department!!!!!';
                        }
                    }
                }

                //to check whether getUser->dept_id is equal to ata[ 'field_dept_desc' ] if not alert

                if ($getData == null && $getUser->role_id != 77) {
                    //allow to enter data for departments
                    // $notfound ='';
                    //$countries = CountryModel::getOption()->get();
                    // dd( $getUser->dept_id );
                    $stateDetails = State::getOption()->get();

                    $cd_grade = array();
                    //dd( $data[ 'field_dept_desc' ] );
                    // $notfound = '';
                    // try {
                    $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
                        'dept_code' => $getUser->dept_id,
                        'token' => 'b000e921eeb20a0d395e341dfcd6117a',
                    ]);
                    $cd_grade = json_decode($response->getBody(), true);

                    $post = [];
                    foreach ($cd_grade as $cdgrade) {
                        if ($cdgrade['group_code'] == 'C' || $cdgrade['group_code'] == 'D') {
                            $post[] = $cdgrade;
                        }
                        // else {
                        //     $post[] = [];
                        // }
                    }
                    // } catch ( Exception $e ) {

                    //     return response()->json( [
                    //         'status' => 0,
                    //         'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                    //         //'errors' => $e->getMessage()
                    // ] );
                    // }
                    $educations = EducationModel::get()->toArray();

                    $Gender = GenderModel::get()->toArray();
                    $Caste = CasteModel::get()->toArray();
                    $Relationship = RelationshipModel::get()->toArray();
                    $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');

                    return view('admin/Form/form_backlog', compact('deptListArray', 'Caste', 'Relationship', 'getUser', 'Gender', 'stateDetails', 'post', 'educations', 'data', 'notfound'));
                }
                if ($getUser != null && $getUser->role_id == 77) {
                    // allow to enter data for fresh citizen
                    //   $notfound ='';
                    $stateDetails = State::getOption()->get();
                    // $District = District::get()->toArray();
                    // $subDiv = SubDivision::get()->toArray();
                    //$countries = CountryModel::getOption()->get();
                    $Gender = GenderModel::get()->toArray();
                    $Caste = CasteModel::get()->toArray();
                    $Relationship = RelationshipModel::get()->toArray();
                    $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');

                    //dd( count( $data ) );
                    //  try {
                    if (count($data) > 0) {
                        //After EIN pass the designation of concern department will populate in the drop down
                        $cd_grade = array();
                        // $notfound = '';
                        $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
                            'dept_code' => $data['dept_cd'],
                            'token' => 'b000e921eeb20a0d395e341dfcd6117a',
                        ]);
                        $cd_grade = json_decode($response->getBody(), true);
                    } else {
                        $cd_grade = [];
                    }
                    $post = [];
                    foreach ($cd_grade as $cdgrade) {
                        if ($cdgrade['group_code'] == 'C' || $cdgrade['group_code'] == 'D') {
                            $post[] = $cdgrade;
                        }
                        // else {
                        //     $post[] = [];
                        // }
                    }
                    // } catch ( Exception $e ) {

                    //     return response()->json( [
                    //         'status' => 0,
                    //         'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                    //         //'errors' => $e->getMessage()
                    // ] );
                    // }
                    $educations = EducationModel::get()->toArray();
                    $grades = Grade::get()->toArray();
                    //grade display from cmis api
                    $formattedDate = today()->format('Y-m-d');
                    return view('admin/Form/form_backlog', compact('deptListArray', 'formattedDate', 'Caste', 'Relationship', 'getUser', 'Gender', 'stateDetails', 'post', 'grades', 'educations', 'data', 'notfound'));
                }
            }
        } catch (Exception $e) {

            return response()->json([
                'status' => 0,
                'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                //'errors' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    // public function create( Request $request ) {

    //     // dd( $ein );
    //     $notfound ='';
    //     $request->session()->forget( [ 'ein', 'ein' ] );
    //     //close previous session
    //    // $notfound ='';
    //     $user_id = Auth::user()->id;
    //     // dd( Auth::user()->id );
    //     $getUser = User::get()->where( 'id', $user_id )->first();
    //     //dd( $getUser );
    //     $getData = ProformaModel::get()->where( 'uploaded_id', $user_id )->where( 'uploader_role_id', 77 )->first();
    //     //dd( $getData );
    //     if ( $getData != null ) {

    //         return back()->with( 'error_message', 'You have already applied!!!!' );
    //         //check for citizen who have applied twice reject
    //     }

    //     $data = array();
    //     //dd( $data[ 'field_dept_desc' ] );
    //     $notfound = '';
    //     try {
    //         if ( $request->input( 'ein' ) != '' ) {
    //             $response = Http::post( 'http://manipurtemp02.nic.in/cmis_api/public/api/get-employee-profile', [
    //                 'ein' => $request->input( 'ein' ),
    //                 'token' => 'b000e921eeb20a0d395e341dfcd6117a',
    //             ] );
    //             $data = json_decode( $response->getBody(), true );

    //             if ( count( $data ) > 0 )
    //             $data = $data[ 0 ];
    //             else
    //             $notfound = 'EIN ' . $request->input( 'ein' ) . ' not found in CMIS';
    //         }
    //     } catch ( Exception $e ) {

    //         return response()->json( [
    //             'status' => 0,
    //             'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
    //             //'errors' => $e->getMessage()
    //         ] );
    //     }
    //     if ( $data != null ) {
    //       //  $notfound ='';
    //         $getDept_id = DepartmentModel::get()->where( 'dept_name', $data[ 'field_dept_desc' ] )->first();

    //         $cmis_dept_id_int = intval( $getDept_id->dept_id );
    //         // dd( $getUser->dept_id, $cmis_dept_id_int );

    //         if ( $getUser->dept_id != null && $getUser->dept_id > 0 ) {
    //             if ( $getUser->dept_id != $cmis_dept_id_int ) {
    //                 $notfound ='';
    //                 $data = array();
    //                 $notfound = 'EIN ' . $request->input( 'ein' ) . ' has department which is not same as the User Department!!!!!';
    //             }
    //         }
    //     }

    //     $getData1 = ProformaModel::get()->where( 'uploaded_id', $user_id )->where( 'ein', $request->input( 'ein' ) )->first();
    //     //dd( $getData );
    //     if ( $getData1 != null ) {

    //         return back()->with( 'error_message', 'You have already applied!!!!' );
    //         //check for citizen who have applied twice reject
    //     }

    //     //to check whether getUser->dept_id is equal to ata[ 'field_dept_desc' ] if not alert

    //     if ( $getData == null && $getUser->role_id != 77 ) {
    //         //allow to enter data for departments
    //      //   $notfound ='';
    //         //$countries = CountryModel::getOption()->get();
    //         $stateDetails = State::getOption()->get();
    //         //only data from CMIS API for post
    //         $educations = EducationModel::get()->toArray();
    //         $cd_grade = array();
    //         //dd( $data[ 'field_dept_desc' ] );
    //         // $notfound = '';
    //         try {
    //             $response = Http::post( 'http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
    //                 'dept_code' => $getUser->dept_id,
    //                 'token' => 'b000e921eeb20a0d395e341dfcd6117a',
    //             ] );
    //             $cd_grade = json_decode( $response->getBody(), true );
    //             $post = [];
    //             foreach ( $cd_grade as $cdgrade ) {
    //                 if ( $cdgrade[ 'group_code' ] == 'C' || $cdgrade[ 'group_code' ] == 'D' ) {
    //                     $post[] = $cdgrade;
    //                 }
    //                 // else {
    //                 //     $post[] = [];
    //                 // }
    //             }
    //         } catch ( Exception $e ) {

    //             return response()->json( [
    //                 'status' => 0,
    //                 'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
    //                 //'errors' => $e->getMessage()
    //             ] );
    //         }
    //         $Gender = GenderModel::get()->toArray();
    //         $Caste = CasteModel::get()->toArray();
    //         $Relationship = RelationshipModel::get()->toArray();
    //         $deptListArray = DepartmentModel::orderBy( 'dept_name' )->get()->unique( 'dept_name' );

    //         return view( 'admin/Form/form_proforma', compact( 'deptListArray', 'Caste', 'Relationship', 'getUser', 'Gender', 'stateDetails', 'post', 'educations', 'data', 'notfound' ) );
    //     }
    //     if ( $getUser != null && $getUser->role_id == 77 ) {
    //         // allow to enter data for fresh citizen
    //       //  $notfound ='';
    //         $stateDetails = State::getOption()->get();

    //         $Gender = GenderModel::get()->toArray();
    //         $Caste = CasteModel::get()->toArray();
    //         $Relationship = RelationshipModel::get()->toArray();
    //         $deptListArray = DepartmentModel::orderBy( 'dept_name' )->get()->unique( 'dept_name' );

    //         //dd( count( $data ) );
    //         try {
    //             if ( count( $data ) > 0 ) {
    //                 //After EIN pass the designation of concern department will populate in the drop down
    //                 $cd_grade = array();
    //                 // $notfound = '';
    //                 $response = Http::post( 'http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
    //                     'dept_code' => $data[ 'dept_cd' ],
    //                     'token' => 'b000e921eeb20a0d395e341dfcd6117a',
    //                 ] );
    //                 $cd_grade = json_decode( $response->getBody(), true );
    //             } else {
    //                 $cd_grade = [];
    //             }
    //             $post = [];
    //             foreach ( $cd_grade as $cdgrade ) {
    //                 if ( $cdgrade[ 'group_code' ] == 'C' || $cdgrade[ 'group_code' ] == 'D' ) {
    //                     $post[] = $cdgrade;
    //                 }
    //                 // else {
    //                 //     $post[] = [];
    //                 // }
    //             }
    //         } catch ( Exception $e ) {

    //             return response()->json( [
    //                 'status' => 0,
    //                 'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
    //                 //'errors' => $e->getMessage()
    //             ] );
    //         }
    //         $educations = EducationModel::get()->toArray();

    //         ///grade display from cmis API
    //         return view( 'admin/Form/form_proforma', compact( 'deptListArray', 'Caste', 'Relationship', 'getUser', 'Gender', 'stateDetails', 'post', 'educations', 'data', 'notfound' ) );
    //     }
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function store(Request $request)
     {
 
         $user_id = Auth::user()->id;
         $getUser = User::get()->where('id', $user_id)->first();
 
         // http://manipurtemp02.nic.in/cmis_api/public/api/get-employee-profile?ein=088323&token=b000e921eeb20a0d395e341dfcd6117a
         //////////////////////////////////////////////////////////////////////////
 
         // Calculates the difference between DateTime objects
 
         $entryDate = entryAgeModel::get()->first();
         //return $entryDate;
         $dateToday = date('Y-m-d');
         // $dateToday = new DateTime(date("m/d/Y")); //confusion here what about other system       
         $appl_DOB = date($request->applicant_dob);
         //$difference = $appl_DOB->diff($dateToday);
 
         $difference = strtotime($dateToday) - strtotime($appl_DOB);
 
         //Calculate difference in days
         $days = abs($difference / (60 * 60) / 24);
 
 
         $ageLimit = ($entryDate->eligible_age * 365) + 3;
         //return $ageLimit;
 
         //Calculate the date of submission which is valid only upto 6 months
 
         $SubmissionDate = TimeLineModel::get()->first();
 
 
         $dateofsubmission = new DateTime($request->appl_date); //confusion here what about other system    
 
         $dateofexpiry = new DateTime($request->deceased_doe);
 
         $difference = $dateofexpiry->diff($dateofsubmission);
 
         $DaysDifferent = $difference->format('%R%a days'); //result comes as +5606 days
 
         $diffExplode = explode(' days', $DaysDifferent); //remove space days
         $dateDiff = explode('+', $diffExplode['0']); //remove +
 
         //return  $resultDays;//display only days
 
         $AllowPeriod = ($SubmissionDate->timeline_months * 30) + 3;
 
         $validatedData = $request->validate([
             'ein' => 'required',
             'deceased_emp_name' => 'required',
             'dept_name' => 'required|string',
             'deceased_doa' => 'required',
             'desig_name' => 'required|string',
             'grade_name' => 'required|string',
             'expire_on_duty' => 'required',
             'deceased_doe' => 'required|string',
             'deceased_causeofdeath' => 'required|string',
             'applicant_name' => 'required|string',
             'appl_date' => 'required|string',
             'applicant_dob' => 'required',
             'relationship' => 'required', // This part of the rule checks the existence
             //  of the relationship field's value in the id column of the relationship table in the database.
             'applicant_mobile' => 'required|string',
             'applicant_edu_id' => 'required',
             'physically_handicapped' => 'required',
             'applicant_email_id' => 'required|string',
             'caste_id' => 'required',
             'sex' => 'required',
             'emp_addr_lcality' => 'required|string',
             'emp_addr_district' => 'required',
             'emp_addr_subdiv' => 'required',
             'emp_state' => 'required',
             'deceased_emp_name' => 'required',
             'dept_name' => 'required|string',
             'deceased_doa' => 'required',
             'desig_name' => 'required|string',
             'grade_name' => 'required|string',
             'emp_pincode' => 'required',
             'emp_addr_lcality_ret' => 'required|string',
             'emp_addr_district_ret' => 'required',
             'emp_addr_subdiv_ret' => 'required',
             'emp_state_ret' => 'required',
             'emp_pincode_ret' => 'required',
             'applicant_desig_id' => 'required',
             'applicant_grade' => 'required',
             'ministry' => 'required',
             'deceased_dob' => 'required',
 
             'second_post_id' => 'required',
             'second_grade_id' => 'required',
             'dept_id_option' => 'required',
             'third_post_id' => 'required',
             'third_grade_id' => 'required',
             'other_qualification'=>''
 
 
 
             // Add other fields and validation rules as needed
         ]);
 
 
         if (($days >= $ageLimit) && ($dateDiff['1'] <= $AllowPeriod)) {
 
             // Get the EIN and relationship from the request
             $ein = $request->input('ein');
 
             // Find the ProformaModel instance by EIN
             $proforma = ProformaModel::where('ein', $ein)->first();
 
             // Check if the ProformaModel instance exists
             if ($proforma) {
                 return response()->json(['error' => 'Already Entered'], 409);
             }
 
 
             $emp = ProformaModel::get()->where("ein", $request->ein)->toArray(); // form not yet submitted
 
             $emp_desig = DepartmentModel::get()->where("dept_name", $request->dept_name)->unique('dept_name')->first();
 
             /////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
             if ($getUser->role_id != 77) {
                 $submitDate = $request->appl_date;
                 $fileStatus = 1;
             }
             if ($getUser->role_id == 77) {
                 $submitDate = null;
                 $fileStatus = 0;
             }
 
             // if (count($emp) == 0) {
             if (!$proforma) {
 
                 ProformaModel::create([
 
                     'ein' => $validatedData['ein'],
                     'deceased_emp_name' => $validatedData['deceased_emp_name'],
                     'dept_name' => $validatedData['dept_name'],
                     'desig_name' => $validatedData['desig_name'],
                     'deceased_doa' => $validatedData['deceased_doa'],
                     'ministry' =>  $validatedData['ministry'],
                     'grade_name' =>  $validatedData['grade_name'],
                     'deceased_dob' =>  $validatedData['deceased_dob'],
 
                     'relationship' => $validatedData['relationship'],
                     'expire_on_duty' => $validatedData['expire_on_duty'],
                     'deceased_doe' => $validatedData['deceased_doe'],
                     'deceased_causeofdeath' => $validatedData['deceased_causeofdeath'],
                     'applicant_name' => $validatedData['applicant_name'],
                     'appl_date' => $validatedData['appl_date'],
                     'applicant_dob' => $validatedData['applicant_dob'],
                     'applicant_mobile' => $validatedData['applicant_mobile'],
                     'applicant_edu_id' => $validatedData['applicant_edu_id'],
                     'physically_handicapped' => $validatedData['physically_handicapped'],
                     'applicant_email_id' => $validatedData['applicant_email_id'],
                     'caste_id' => $validatedData['caste_id'],
                     'sex' =>  $validatedData['sex'],
                     'emp_addr_lcality' => $validatedData['emp_addr_lcality'],
                     'emp_addr_district' => $validatedData['emp_addr_district'],
                     'emp_addr_subdiv' => $validatedData['emp_addr_subdiv'],
                     'emp_state' => $validatedData['emp_state'],
                     'emp_pincode' => $validatedData['emp_pincode'],
                     'emp_addr_lcality_ret' => $validatedData['emp_addr_lcality_ret'],
                     'emp_addr_district_ret' => $validatedData['emp_addr_district_ret'],
                     'emp_addr_subdiv_ret' => $validatedData['emp_addr_subdiv_ret'],
                     'emp_state_ret' => $validatedData['emp_state_ret'],
                     'emp_pincode_ret' => $validatedData['emp_pincode_ret'],
                     'applicant_desig_id' => $validatedData['applicant_desig_id'],
                     'applicant_grade' => $validatedData['applicant_grade'],
 
                     'second_post_id' => $validatedData['second_post_id'],
                     'second_grade_id' => $validatedData['second_grade_id'],
                     'dept_id_option' => $validatedData['dept_id_option'],
                     'third_post_id' => $validatedData['third_post_id'],
                     'third_grade_id' => $validatedData['third_grade_id'],
                     'other_qualification' => $validatedData['other_qualification'],
 
                     'uploaded_id' => $getUser->id,
                     'uploader_role_id' => $getUser->role_id,
                     'dept_id' => $emp_desig->dept_id,
                     'ministry_id' => $emp_desig->ministry_id,
                     'file_status' => $fileStatus,
                     'form_status' => 1,
                     'upload_status' => 0,
                     'change_status' => 0,
                     'status' => 0,
                     'rejected_status' => 0
                 ]);
 
                 // dd($newApplicant);
                 // insert to form submission status 
                 $formId = 1; // here we set familly details form id as 2 according to ui;
                 $getFormSubmisiondetails = EmpFormSubmissionStatus::get()->where('ein', $request->ein)->where('form_id', $formId)->toArray();
                 if (count($getFormSubmisiondetails) == 0) {
                     EmpFormSubmissionStatus::create([
                         'ein' => $request->ein,
                         'form_id' => $formId,
                         'submit_status' => 1 // status 1 = submitted
                     ]);
                 } else {
                     //  $emp_form_stat_row = EmpFormSubmissionStatus::get()->where('ein', $request->ein)->where('form_id', $formId)->first();
                     //  $row = EmpFormSubmissionStatus::find($emp_form_stat_row->id);
                     //  $row->submit_status = 1;
                     //  $row->save();
                     //  return back()->with('errormessage', "Already Submitted..........!");
                     return response()->json(['errormessage' => 'Already Submitted..........! ']);
                 }
 
                 Session::put('ein', $request->ein);
 
                 // return $plusRemove['1'];
                 //return back()->with('status', "Data save successfully...."); 
                 // Return a response, for example, a success message
                 return response()->json(['message' => 'Data save successfully ']);
                 // return response()->json(['message' => '']);
                 //ANAND BELOW
             } else {
                 // return $plusRemove['1'];
                 // return back()->with('duplicate', "Deceased EIN already entered!............");
                 return response()->json(['duplicate' => 'Already Entered'], 409);
             }
         } else {
             // return redirect()->route('enterProformaDetails')
             // // ->withInput($request->except('appl_date'))
             // ->with('error', 'Your application cannot be accepted!!!! Please check the DOB and Date of Submission');
             return response()->json(['eligible' => 'Your application cannot be accepted!!!! Please check the DOB and Date of Submission']);
             // return back()->with(
             //     'eligible',
             //     "Your application cannot be accepted!!!! Please check the DOB and Date of Submission"
             // );
         }
     }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create1(Request $request)
    // {
    //     $user_id = Auth::user()->id;
    //     $getUser = User::get()->where('id', $user_id)->first();
    //     $getData = ProformaModel::get()->where('uploaded_id', $user_id)->where('uploader_role_id', 77)->first();
    //     // dd($getUser);
    //     if ($getData != null) {

    //         return back()->with('error_message', "You have already applied!!!!"); //check for citizen who have applied twice reject
    //     }

    //     $data = array();
    //     $notfound = "";
    //     if ($request->input('ein') != '') {
    //         $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-employee-profile', [
    //             'ein' => $request->input('ein'),
    //             'token' => "b000e921eeb20a0d395e341dfcd6117a",
    //         ]);
    //         $data = json_decode($response->getBody(), true);
    //         if (count($data) > 0)
    //             $data = $data[0];
    //         else
    //             $notfound = "EIN " . $request->input('ein') . " not found in CMIS";
    //     }
    //     if ($getData == null && $getUser->uploader_role_id != 77) { //allow to enter data for departments
    //         $stateDetails = State::getOption()->get();

    //         $post = DesignationModel::orderBy('desig_name')->get()->unique('desig_name')->toArray();
    //         $educations = EducationModel::get()->toArray();
    //         $grades = Grade::get()->toArray();
    //         $Gender = GenderModel::get()->toArray();
    //         $Caste = CasteModel::get()->toArray();
    //         $Relationship = RelationshipModel::get()->toArray();

    //         return view('admin/Form/form_backlog', compact('getUser', 'Caste', 'Relationship', 'Gender', 'stateDetails', 'post', 'grades', 'educations'));
    //     }
    //     if ($getUser != null && $getUser->uploader_role_id == 77) { // allow to enter data for fresh citizen
    //         $stateDetails = State::getOption()->get();

    //         $post = DesignationModel::orderBy('desig_name')->get()->unique('desig_name')->toArray();
    //         $educations = EducationModel::get()->toArray();
    //         $grades = Grade::get()->toArray();
    //         $Gender = GenderModel::get()->toArray();
    //         $Caste = CasteModel::get()->toArray();
    //         $Relationship = RelationshipModel::get()->toArray();

    //         return view('admin/Form/form_backlog', compact('getUser', 'Caste', 'Relationship', 'Gender', 'stateDetails', 'post', 'grades', 'educations'));
    //     }
    // }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store1(Request $request)
    {

        // dd($request->all());
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        // http://manipurtemp02.nic.in/cmis_api/public/api/get-employee-profile?ein=088323&token=b000e921eeb20a0d395e341dfcd6117a
        //////////////////////////////////////////////////////////////////////////

        // Calculates the difference between DateTime objects

        $entryDate = entryAgeModel::get()->first();
        //return $entryDate;
        $dateToday = date('Y-m-d');
        // $dateToday = new DateTime(date("m/d/Y")); //confusion here what about other system       
        $appl_DOB = date($request->applicant_dob);
        //$difference = $appl_DOB->diff($dateToday);

        $difference = strtotime($dateToday) - strtotime($appl_DOB);

        //Calculate difference in days
        $days = abs($difference / (60 * 60) / 24);

        // $resultDays = $difference->format('%R%a days'); //result comes as +5606 days

        // $dateExplode = explode(' days', $resultDays); //remove space days
        // $dateDifference = explode('+', $dateExplode['0']); //remove +


        $ageLimit = ($entryDate->eligible_age * 365) + 3;
        //return $ageLimit;

        //Calculate the date of submission which is valid only upto 6 months

        $SubmissionDate = TimeLineModel::get()->first();


        $dateofsubmission = new DateTime($request->appl_date); //confusion here what about other system    

        $dateofexpiry = new DateTime($request->deceased_doe);

        $difference = $dateofexpiry->diff($dateofsubmission);

        $DaysDifferent = $difference->format('%R%a days'); //result comes as +5606 days

        $diffExplode = explode(' days', $DaysDifferent); //remove space days
        $dateDiff = explode('+', $diffExplode['0']); //remove +

        //return  $resultDays;//display only days

        $AllowPeriod = ($SubmissionDate->timeline_months * 30) + 3;
        // dd($AllowPeriod);

        //dd($dateToday,$appl_DOB,$difference,$days);

        $request->validate(
            [
                'ein' => 'required|numeric',
                'deceased_emp_name' => 'required',
                'dept_name' => 'required|string',
                'deceased_doa' => 'required',
                'desig_name' => 'required|string',
                'grade_name' => 'required|string',
                'expire_on_duty' => 'required',
                'deceased_doe' => 'required',
                'deceased_causeofdeath' => 'required|string',
                'deceased_dob' => 'required',

                // 'appl_number' => 'required|numeric',
                'appl_date' => 'required',
                'applicant_name' => 'required',
                'relationship' => 'required|string',
                'applicant_dob' => 'required',
                'applicant_edu_id' => 'required',
                'applicant_mobile' => 'required|numeric|digits:10',
                'applicant_email_id' => 'required|email|unique:proforma,applicant_email_id',
                'physically_handicapped' => 'required',
                'emp_addr_lcality' => 'required|string',
                'emp_addr_district' => 'required',
                'sex' => 'required',
                'emp_state' => 'required',
                'emp_pincode' => 'required',
                'applicant_desig_id' => 'required',
                'applicant_grade' => 'required',
                'caste_id' => 'required',
                'other_qualification'=>''


            ],
            [

                'ein.required' => 'Please fill up this field',
                'deceased_emp_name.required' => 'Please fill up this field',
                'dept_name.required' => 'Please fill up this field',
                'deceased_doa.required' => 'Please fill up this field',
                'desig_name.required' => 'Please fill up this field',
                'grade_name.required' => 'Please fill up this field',
                'expire_on_duty.required' => 'Please fill up this field',
                'deceased_doe.required' => 'Please fill up this field',
                'deceased_causeofdeath.required' => 'Please fill up this field',
                'deceased_dob.required' => 'Please fill up this field',

                // 'appl_number.required' => 'Please fill up this field',
                // 'appl_date.required' => 'Please fill up this field',
                'relationship.required' => 'Please select relationship',
                'applicant_name.required' => 'Please fill up this field',
                'applicant_dob.required' => 'Please fill up this field',
                'applicant_edu_id.required' => 'Please fill up this field',
                'applicant_mobile.required' => 'Please fill up this field',
                'applicant_email_id.required' => 'Please fill up this field',
                'physically_handicapped.required' => 'Please fill up this field',
                'emp_addr_lcality.required' => 'Please fill up this field',
                'emp_addr_district.required' => 'Please fill up this field',
                'emp_state.required' => 'Please fill up this field',
                'emp_pincode.required' => 'Please fill up this field',
                'sex' => 'Please select rrsex',

                'applicant_desig_id.required' => 'Please fill up this field',
                'applicant_grade.required' => 'Please fill up this field',
                'caste_id.required' => 'Please select caste',

            ]
        );
        // if ($dateDifference['1']<5478){
        //     return back()->with('eligible', "Your Date of Birth is not Eligible to apply!!!!");

        // }


        if (($days >= $ageLimit) && ($dateDiff['1'] <= $AllowPeriod)) {

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

            $emp = ProformaModel::get()->where("ein", $request->ein)->toArray(); // form not yet submitted

            $emp_desig = DepartmentModel::get()->where("dept_name", $request->dept_name)->unique('dept_name')->first();

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // if ($request->deceased_doe == "") {
            //     return redirect()->route('enterProformaDetails')
            //         ->withInput($request->except('deceased_doe'))
            //         ->with('error', 'Please select deceased expired date...');
            // }

            // if ($request->deceased_causeofdeath == "") {
            //     return redirect()->route('enterProformaDetails')
            //         ->withInput($request->except('deceased_causeofdeath'))
            //         ->with('error', 'Please mention cause of death...');
            // }
            // if ($request->applicant_name == "") {
            //     return redirect()->route('enterProformaDetails')
            //         ->withInput($request->except('applicant_name'))
            //         ->with('error', 'Please fill the applicant name...');
            // }

            // if ($request->appl_date == "") {
            //     return redirect()->route('enterProformaDetails')
            //         ->withInput($request->except('appl_date'))
            //         ->with('error', 'Please select date of submission...');
            // }

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////


            if (count($emp) == 0) {

                $newApplicant = ProformaModel::create([

                    // 'id'=>$request->id,
                    'ein' => $request->ein,
                    'deceased_emp_name' => $request->deceased_emp_name,
                    'ministry' => $request->ministry,
                    'dept_name' => $request->dept_name,
                    'desig_name' => $request->desig_name,
                    'grade_name' => $request->grade_name,
                    'deceased_doa' => $request->deceased_doa,
                    'deceased_doe' => $request->deceased_doe,
                    'deceased_dob' => $request->deceased_dob,
                    'deceased_causeofdeath' => $request->deceased_causeofdeath,
                    //'is_gazetted' => $request->input('is_gazetted'),
                    'caste_id' => $request->caste_id,
                    'sex' => $request->sex,

                    // 'appl_number' => $applicationNo, // this 2 should update at final submit
                    'appl_date' => $request->appl_date,

                    'applicant_name' => $request->applicant_name,
                    'relationship' => $request->relationship,
                    'applicant_dob' => $request->applicant_dob,
                    'applicant_edu_id' => $request->applicant_edu_id,
                    'applicant_mobile' => $request->applicant_mobile,
                    'applicant_email_id' => $request->applicant_email_id,
                    'emp_addr_lcality' => $request->emp_addr_lcality,
                    'emp_addr_subdiv' => $request->emp_addr_subdiv,
                    'emp_addr_district' => $request->emp_addr_district,
                    'emp_state' => $request->emp_state,
                    'emp_pincode' => $request->emp_pincode,
                    'emp_addr_lcality_ret' => $request->emp_addr_lcality_ret,
                    'emp_addr_subdiv_ret' => $request->emp_addr_subdiv_ret,
                    'emp_addr_district_ret' => $request->emp_addr_district_ret,
                    'emp_state_ret' => $request->emp_state_ret,
                    'emp_pincode_ret' => $request->emp_pincode_ret,
                    'applicant_desig_id' => $request->applicant_desig_id,
                    'applicant_grade' => $request->applicant_grade,
                    'expire_on_duty' => $request->input('expire_on_duty'),
                    // 'accept_transfer' => $request->input('accept_transfer'),
                    'physically_handicapped' => $request->input('physically_handicapped'),
                    'other_qualification' => $request->input('other_qualification'),
                    'uploaded_id' => $getUser->id,
                    'uploader_role_id' => $getUser->role_id,
                    'dept_id' => $emp_desig->dept_id,
                    'ministry_id' => $emp_desig->ministry_id,
                    'file_status' => 1,
                    'rejected_status' => 0,
                    'upload_status' => 0,
                    'change_status' => 0,
                    'status' => 0,
                    'form_status' => 1,
                    'second_post_id' => 0,
                    'second_grade_id' => "NA",
                    'dept_id_option' => 0,
                    'third_post_id' => 0,
                    'third_grade_id'=>"NA",
                ]);


                // insert to form submission status 
                $formId = 1; // here we set familly details form id as 2 according to ui;
                $getFormSubmisiondetails = EmpFormSubmissionStatus::get()->where('ein', $request->ein)->where('form_id', $formId)->toArray();
                if (count($getFormSubmisiondetails) == 0) {
                    EmpFormSubmissionStatus::create([
                        'ein' => $request->ein,
                        'form_id' => $formId,
                        'submit_status' => 1 // status 1 = submitted
                    ]);
                } else {
                    //  $emp_form_stat_row = EmpFormSubmissionStatus::get()->where('ein', $request->ein)->where('form_id', $formId)->first();
                    //  $row = EmpFormSubmissionStatus::find($emp_form_stat_row->id);
                    //  $row->submit_status = 1;
                    //  $row->save();
                    //return redirect()->back()->with('error', "Already Submitted..........!");
                    return response()->json(['error' => 'Already Submitted..........!']);
                }

                Session::put('ein', $request->ein);

                // return $plusRemove['1'];

                //  return back()->with('status', "Data save successfully....");
                return response()->json(['message' => 'Data save successfully ']);
                //return redirect('ddo-assist/enter-family-details-backlog')->with('status', "Data save successfully....");;
                //ANAND BELOW
            } else {
                // return $plusRemove['1'];
                //return redirect()->back()->with('duplicate', "Deceased EIN already entered!............");
                return response()->json(['error' => '"Deceased EIN already entered!............"']);
            }
        } else {
            //return $plusRemove['1'];
            return response()->json(['error' => '"You are not Eligible to apply!!!!..Needs to Complete 15 Years!"']);
            // return back()->with('eligible', "You are not Eligible to apply!!!!..Needs to Complete 15 Years!");
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create2ndAppl(Request $request)
    {
        $ein = session()->get('ein');

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $getData = ProformaModel::get()->where('uploaded_id', $user_id)->where('uploader_role_id', 77)->first();
        //dd($getData);
        if ($getData != null) {

            return back()->with('error_message', "You have already applied!!!!"); //check for citizen who have applied twice reject
        }

        $data = array();
        //dd($data['field_dept_desc']);
        $notfound = "";
        if ($ein != '') {
            $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-employee-profile', [
                'ein' => $ein,
                'token' => "b000e921eeb20a0d395e341dfcd6117a",
            ]);
            $data = json_decode($response->getBody(), true);
            if (count($data) > 0)
                $data = $data[0];
            else
                $notfound = "EIN " . $ein . " not found in CMIS";
        }

        session()->put('ein', $ein);

        $get2nd_appl = ProformaHistoryModel::get()->where('ein', $ein)->first();

        //dd($get2nd_appl->second_appl_name);

        //to check whether getUser->dept_id is equal to ata['field_dept_desc'] if not alert

        if ($getData == null && $getUser->role_id != 77) { //allow to enter data for departments


            $stateDetails = State::getOption()->get();

            $post = DesignationModel::where('field_dept_cd', $getUser->dept_id)->orderBy('desig_name')->get()->unique('desig_name')->toArray();
            $educations = EducationModel::get()->toArray();
            $grades = Grade::get()->toArray();
            $Gender = GenderModel::get()->toArray();
            $Caste = CasteModel::get()->toArray();
            $Relationship = RelationshipModel::get()->toArray();
            $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');

            return view('admin/Form/form_proforma2ndAppl', compact('deptListArray', 'Caste', 'Relationship', 'Gender', 'get2nd_appl', 'stateDetails', 'post', 'grades', 'educations', 'data', 'notfound'));
        }
        //$changeapplicants = ProformaModel::all(); //fetch all sites from proforma table
        // return view('hod.viewChangeApplicant', ['changeapplicants' => $changeapplicants]);
    }





    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     */



     public function store2ndAppl(Request $request)
     {
 
         // dd($request->all());
         $ein = session()->get('ein');
 
 
         $user_id = Auth::user()->id;
         $getUser = User::get()->where('id', $user_id)->first();
 
         // http://manipurtemp02.nic.in/cmis_api/public/api/get-employee-profile?ein=088323&token=b000e921eeb20a0d395e341dfcd6117a
         //////////////////////////////////////////////////////////////////////////
 
         // Calculates the difference between DateTime objects
 
         $entryDate = entryAgeModel::get()->first();
         //return $entryDate;
         $dateToday = date('Y-m-d');
         // $dateToday = new DateTime(date("m/d/Y")); //confusion here what about other system       
         $appl_DOB = date($request->applicant_dob);
         //$difference = $appl_DOB->diff($dateToday);
 
         $difference = strtotime($dateToday) - strtotime($appl_DOB);
 
         //Calculate difference in days
         $days = abs($difference / (60 * 60) / 24);
 
         // $resultDays = $difference->format('%R%a days'); //result comes as +5606 days
 
         // $dateExplode = explode(' days', $resultDays); //remove space days
         // $dateDifference = explode('+', $dateExplode['0']); //remove +
 
 
         $ageLimit = ($entryDate->eligible_age * 365) + 3;
         //return $ageLimit;
 
         //Calculate the date of submission which is valid only upto 6 months
 
         $SubmissionDate = TimeLineModel::get()->first();
 
 
         $dateofsubmission = new DateTime($request->appl_date); //confusion here what about other system    
 
         $dateofexpiry = new DateTime($request->deceased_doe);
 
         $difference = $dateofexpiry->diff($dateofsubmission);
 
         $DaysDifferent = $difference->format('%R%a days'); //result comes as +5606 days
 
         $diffExplode = explode(' days', $DaysDifferent); //remove space days
         $dateDiff = explode('+', $diffExplode['0']); //remove +
 
         //return  $resultDays;//display only days
 
         $AllowPeriod = ($SubmissionDate->timeline_months * 30) + 3;
         // dd($AllowPeriod);
 
         //dd($dateToday,$appl_DOB,$difference,$days);
 
         $request->validate(
             [
                 'ein' => 'required|numeric',
                 'deceased_emp_name' => 'required',
                 'dept_name' => 'required|string',
                 'deceased_doa' => 'required',
                 'desig_name' => 'required|string',
                 'grade_name' => 'required|string',
                 'expire_on_duty' => 'required',
                 'deceased_doe' => 'required',
                 'deceased_causeofdeath' => 'required|string',
                 'deceased_dob' => 'required',
 
                 // 'appl_number' => 'required|numeric',
                 //  'appl_date' => 'required',
                 'applicant_name' => 'required',
                 'relationship' => 'required|string',
                 'applicant_dob' => 'required',
                 'applicant_edu_id' => 'required',
                 'applicant_mobile' => 'required|numeric|digits:10',
                 'applicant_email_id' => 'required|email|unique:proforma,applicant_email_id',
                 'physically_handicapped' => 'required',
                 'emp_addr_lcality' => 'required|string',
                 'emp_addr_district' => 'required',
                 'sex' => 'required',
                 'emp_state' => 'required',
                 'emp_pincode' => 'required',
                 'applicant_desig_id' => 'required',
                 'applicant_grade' => 'required',
                 'caste_id' => 'required',
                 'second_post_id' => 'required',
                 'second_grade_id' =>'required',
                 'dept_id_option' =>'required',
                 'third_post_id' => 'required',
                 'third_grade_id' =>'required',
                 'other_qualification'=>''
 
             ],
             [
 
                 'ein.required' => 'Please fill up this field',
                 'deceased_emp_name.required' => 'Please fill up this field',
                 'dept_name.required' => 'Please fill up this field',
                 'deceased_doa.required' => 'Please fill up this field',
                 'desig_name.required' => 'Please fill up this field',
                 'grade_name.required' => 'Please fill up this field',
                 'expire_on_duty.required' => 'Please fill up this field',
                 'deceased_doe.required' => 'Please fill up this field',
                 'deceased_causeofdeath.required' => 'Please fill up this field',
                 'deceased_dob.required' => 'Please fill up this field',
 
                 // 'appl_number.required' => 'Please fill up this field',
                 // 'appl_date.required' => 'Please fill up this field',
                 'relationship.required' => 'Please select relationship',
                 'applicant_name.required' => 'Please fill up this field',
                 'applicant_dob.required' => 'Please fill up this field',
                 'applicant_edu_id.required' => 'Please fill up this field',
                 'applicant_mobile.required' => 'Please fill up this field',
                 'applicant_email_id.required' => 'Please fill up this field',
                 'physically_handicapped.required' => 'Please fill up this field',
                 'emp_addr_lcality.required' => 'Please fill up this field',
                 'emp_addr_district.required' => 'Please fill up this field',
                 'emp_state.required' => 'Please fill up this field',
                 'emp_pincode.required' => 'Please fill up this field',
                 'sex' => 'Please select rrsex',
 
                 'applicant_desig_id.required' => 'Please fill up this field',
                 'applicant_grade.required' => 'Please fill up this field',
                 'caste_id.required' => 'Please select caste',
                 'second_post_id' =>  'Please fill up this field',
                 'second_grade_id' =>  'Please fill up this field',
                 'dept_id_option' => 'Please fill up this field',
                 'third_post_id' =>  'Please fill up this field',
                 'third_grade_id' =>  'Please fill up this field',
 
             ]
         );
         // if ($dateDifference['1']<5478){
         //     return back()->with('eligible', "Your Date of Birth is not Eligible to apply!!!!");
 
         // }
 
 
         if (($days >= $ageLimit) && ($dateDiff['1'] <= $AllowPeriod)) {
 
 
             $emp = ProformaModel::get()->where("ein", $request->ein)->toArray(); // form not yet submitted
 
             $emp_desig = DepartmentModel::get()->where("dept_name", $request->dept_name)->unique('dept_name')->first();
 
 
             if (count($emp) == 0) {
 
                 $newApplicant = ProformaModel::create([
 
                     // 'id'=>$request->id,
                     'ein' => $request->ein,
                     'deceased_emp_name' => $request->deceased_emp_name,
                     'ministry' => $request->ministry,
                     'dept_name' => $request->dept_name,
                     'desig_name' => $request->desig_name,
                     'grade_name' => $request->grade_name,
                     'deceased_doa' => $request->deceased_doa,
                     'deceased_doe' => $request->deceased_doe,
                     'deceased_dob' => $request->deceased_dob,
                     'deceased_causeofdeath' => $request->deceased_causeofdeath,
                     //'is_gazetted' => $request->input('is_gazetted'),
                     'caste_id' => $request->caste_id,
                     'sex' => $request->sex,
 
                     // 'appl_number' => $applicationNo, // this 2 should update at final submit
                     'appl_date' => $request->appl_date,
 
                     'applicant_name' => $request->applicant_name,
                     'relationship' => $request->relationship,
                     'applicant_dob' => $request->applicant_dob,
                     'applicant_edu_id' => $request->applicant_edu_id,
                     'applicant_mobile' => $request->applicant_mobile,
                     'applicant_email_id' => $request->applicant_email_id,
                     'emp_addr_lcality' => $request->emp_addr_lcality,
                     'emp_addr_subdiv' => $request->emp_addr_subdiv,
                     'emp_addr_district' => $request->emp_addr_district,
                     'emp_state' => $request->emp_state,
                     'emp_pincode' => $request->emp_pincode,
                     'emp_addr_lcality_ret' => $request->emp_addr_lcality_ret,
                     'emp_addr_subdiv_ret' => $request->emp_addr_subdiv_ret,
                     'emp_addr_district_ret' => $request->emp_addr_district_ret,
                     'emp_state_ret' => $request->emp_state_ret,
                     'emp_pincode_ret' => $request->emp_pincode_ret,
                     'applicant_desig_id' => $request->applicant_desig_id,
                     'applicant_grade' => $request->applicant_grade,
                   
                     'expire_on_duty' => $request->input('expire_on_duty'),
                    
                     // 'accept_transfer' => $request->input('accept_transfer'),
                     'physically_handicapped' => $request->input('physically_handicapped'),
                     'uploaded_id' => $getUser->id,
                     'uploader_role_id' => $getUser->role_id,
                     'dept_id' => $emp_desig->dept_id,
                     'ministry_id' => $emp_desig->ministry_id,
                     'file_status' => 1,
                     'rejected_status' => 0,
                     'upload_status' => 0,
                     'family_details_status' => 1,
                     'status' => 0,
                     'change_status' => 2,
                     'form_status' => 1,
                     'second_post_id' =>  $request->second_post_id,
                     'second_grade_id' =>  $request->second_grade_id,
                     'dept_id_option' => $request->dept_id_option,
                     'third_post_id' => $request->third_post_id,
                     'third_grade_id'=> $request->third_grade_id,
                     'other_qualification' => $request->other_qualification,
                 ]);
 
 
                 // insert to form submission status 
                 $formId = 1; // here we set familly details form id as 2 according to ui;
                 $getFormSubmisiondetails = EmpFormSubmissionStatus::get()->where('ein', $request->ein)->where('form_id', $formId)->toArray();
                 if (count($getFormSubmisiondetails) == 0) {
                     EmpFormSubmissionStatus::create([
                         'ein' => $request->ein,
                         'form_id' => $formId,
                         'submit_status' => 1 // status 1 = submitted
                     ]);
                 } else {
                     //  $emp_form_stat_row = EmpFormSubmissionStatus::get()->where('ein', $request->ein)->where('form_id', $formId)->first();
                     //  $row = EmpFormSubmissionStatus::find($emp_form_stat_row->id);
                     //  $row->submit_status = 1;
                     //  $row->save();
                     //return redirect()->back()->with('error', "Already Submitted..........!");
                     return response()->json(['error' => 'Already Submitted..........!']);
                 }
 
                 // Session::put('ein', $request->ein);
                 Session::put('ein', $request->ein);
 
                 ProformaHistoryModel::where('ein', $ein)->delete();
                 FileHistoryModel::where('ein', $ein)->delete();
                 EmpFormSubmissionStatusHistoryModel::where('ein', $ein)->delete();
 
                 //  return back()->with('status', "Data save successfully....");
                  return response()->json(['message' => 'Second applicant data save successfully ']);
                 //return redirect('ddo-assist/enter-family-details-backlog')->with('status', "Data save successfully....");;
                 //ANAND BELOW
             } else {
                 // return $plusRemove['1'];
                 // return redirect()->back()->with('duplicate', "Deceased EIN already entered!............");
                  return response()->json(['error' => '"Deceased EIN already entered!............"']);
             }
         } else {
             //return $plusRemove['1'];
              return response()->json(['error' => '"You are not Eligible to apply!!!!..Needs to Complete 15 Years!"']);
             // return back()->with('eligible', "You are not Eligible to apply!!!!..Needs to Complete 15 Years!");
         }
     }


    public function store2ndApplhannagi(Request $request)
    {
        $ein = session()->get('ein');


        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        // http://manipurtemp02.nic.in/cmis_api/public/api/get-employee-profile?ein=088323&token=b000e921eeb20a0d395e341dfcd6117a
        //////////////////////////////////////////////////////////////////////////

        // Calculates the difference between DateTime objects

        $entryDate = entryAgeModel::get()->first();
        //return $entryDate;
        $dateToday = date('Y-m-d');
        // $dateToday = new DateTime(date("m/d/Y")); //confusion here what about other system       
        $appl_DOB = date($request->applicant_dob);
        //$difference = $appl_DOB->diff($dateToday);

        $difference = strtotime($dateToday) - strtotime($appl_DOB);

        //Calculate difference in days
        $days = abs($difference / (60 * 60) / 24);

        // $resultDays = $difference->format('%R%a days'); //result comes as +5606 days

        // $dateExplode = explode(' days', $resultDays); //remove space days
        // $dateDifference = explode('+', $dateExplode['0']); //remove +


        $ageLimit = ($entryDate->eligible_age * 365) + 3;
        //return $ageLimit;

        //Calculate the date of submission which is valid only upto 6 months

        $SubmissionDate = TimeLineModel::get()->first();


        $dateofsubmission = new DateTime($request->appl_date); //confusion here what about other system    

        $dateofexpiry = new DateTime($request->deceased_doe);

        $difference = $dateofexpiry->diff($dateofsubmission);

        $DaysDifferent = $difference->format('%R%a days'); //result comes as +5606 days

        $diffExplode = explode(' days', $DaysDifferent); //remove space days
        $dateDiff = explode('+', $diffExplode['0']); //remove +

        //return  $resultDays;//display only days

        $AllowPeriod = ($SubmissionDate->timeline_months * 30) + 3;
        // dd($AllowPeriod);

        //dd($dateToday,$appl_DOB,$difference,$days);

        $request->validate(
            [
                'ein' => 'required|numeric',
                'deceased_emp_name' => 'required',
                'dept_name' => 'required|string',
                'deceased_doa' => 'required',
                'desig_name' => 'required|string',
                'grade_name' => 'required|string',
                'expire_on_duty' => 'required',
                'deceased_doe' => 'required',
                'deceased_causeofdeath' => 'required|string',
                'deceased_dob' => 'required',

                // 'appl_number' => 'required|numeric',
                //  'appl_date' => 'required',
                'applicant_name' => 'required',
                'relationship' => 'required|string',
                'applicant_dob' => 'required',
                'applicant_edu_id' => 'required',
                'applicant_mobile' => 'required|numeric|digits:10',
                'applicant_email_id' => 'required|email|unique:proforma, applicant_email_id',
                'physically_handicapped' => 'required',
                'emp_addr_lcality' => 'required|string',
                'emp_addr_district' => 'required',
                'sex' => 'required',
                'emp_state' => 'required',
                'emp_pincode' => 'required',
                'applicant_desig_id' => 'required',
                'applicant_grade' => 'required',
                'caste_id' => 'required',
                'second_post_id' => 'required',
                'second_grade_id' => 'required',
                'dept_id_option' => 'required',
                'third_post_id' => 'required',
                'third_grade_id' => 'required',


            ],
            [

                'ein.required' => 'Please fill up this field',
                'deceased_emp_name.required' => 'Please fill up this field',
                'dept_name.required' => 'Please fill up this field',
                'deceased_doa.required' => 'Please fill up this field',
                'desig_name.required' => 'Please fill up this field',
                'grade_name.required' => 'Please fill up this field',
                'expire_on_duty.required' => 'Please fill up this field',
                'deceased_doe.required' => 'Please fill up this field',
                'deceased_causeofdeath.required' => 'Please fill up this field',
                'deceased_dob.required' => 'Please fill up this field',

                // 'appl_number.required' => 'Please fill up this field',
                // 'appl_date.required' => 'Please fill up this field',
                'relationship.required' => 'Please select relationship',
                'applicant_name.required' => 'Please fill up this field',
                'applicant_dob.required' => 'Please fill up this field',
                'applicant_edu_id.required' => 'Please fill up this field',
                'applicant_mobile.required' => 'Please fill up this field',
                'applicant_email_id.required' => 'Please fill up this field',
                'physically_handicapped.required' => 'Please fill up this field',
                'emp_addr_lcality.required' => 'Please fill up this field',
                'emp_addr_district.required' => 'Please fill up this field',
                'emp_state.required' => 'Please fill up this field',
                'emp_pincode.required' => 'Please fill up this field',
                'sex' => 'Please select sex',

                'applicant_desig_id.required' => 'Please fill up this field',
                'applicant_grade.required' => 'Please fill up this field',
                'caste_id.required' => 'Please select caste',
                'second_post_id' => 'Please fill up this field',
                'second_grade_id' => 'Please fill up this field',
                'dept_id_option' => 'Please fill up this field',
                'third_post_id' => 'Please fill up this field',
                'third_grade_id' => 'Please fill up this field',

            ]
        );
        // if ($dateDifference['1']<5478){
        //     return back()->with('eligible', "Your Date of Birth is not Eligible to apply!!!!");

        // }


        if (($days >= $ageLimit) && ($dateDiff['1'] <= $AllowPeriod)) {


            $emp = ProformaModel::get()->where("ein", $request->ein)->toArray(); // form not yet submitted

            $emp_desig = DepartmentModel::get()->where("dept_name", $request->dept_name)->unique('dept_name')->first();


            if (count($emp) == 0) {

                $newApplicant = ProformaModel::create([

                    // 'id'=>$request->id,
                    'ein' => $request->ein,
                    'deceased_emp_name' => $request->deceased_emp_name,
                    'ministry' => $request->ministry,
                    'dept_name' => $request->dept_name,
                    'desig_name' => $request->desig_name,
                    'grade_name' => $request->grade_name,
                    'deceased_doa' => $request->deceased_doa,
                    'deceased_doe' => $request->deceased_doe,
                    'deceased_dob' => $request->deceased_dob,
                    'deceased_causeofdeath' => $request->deceased_causeofdeath,
                    //'is_gazetted' => $request->input('is_gazetted'),
                    'caste_id' => $request->caste_id,
                    'sex' => $request->sex,

                    // 'appl_number' => $applicationNo, // this 2 should update at final submit
                    'appl_date' => $request->appl_date,

                    'applicant_name' => $request->applicant_name,
                    'relationship' => $request->relationship,
                    'applicant_dob' => $request->applicant_dob,
                    'applicant_edu_id' => $request->applicant_edu_id,
                    'applicant_mobile' => $request->applicant_mobile,
                    'applicant_email_id' => $request->applicant_email_id,
                    'emp_addr_lcality' => $request->emp_addr_lcality,
                    'emp_addr_subdiv' => $request->emp_addr_subdiv,
                    'emp_addr_district' => $request->emp_addr_district,
                    'emp_state' => $request->emp_state,
                    'emp_pincode' => $request->emp_pincode,
                    'emp_addr_lcality_ret' => $request->emp_addr_lcality_ret,
                    'emp_addr_subdiv_ret' => $request->emp_addr_subdiv_ret,
                    'emp_addr_district_ret' => $request->emp_addr_district_ret,
                    'emp_state_ret' => $request->emp_state_ret,
                    'emp_pincode_ret' => $request->emp_pincode_ret,
                    'applicant_desig_id' => $request->applicant_desig_id,
                    'applicant_grade' => $request->applicant_grade,
                    'expire_on_duty' => $request->input('expire_on_duty'),
                    // 'accept_transfer' => $request->input('accept_transfer'),
                    'physically_handicapped' => $request->input('physically_handicapped'),
                    'uploaded_id' => $getUser->id,
                    'uploader_role_id' => $getUser->role_id,
                    'dept_id' => $emp_desig->dept_id,
                    'ministry_id' => $emp_desig->ministry_id,
                    'file_status' => 1,
                    'rejected_status' => 0,
                    'upload_status' => 0,
                    'family_details_status' => 1,
                    'status' => 0,
                    'change_status' => 2,
                    'form_status' => 1,
                    'second_post_id' => $request->second_post_id,
                    'second_grade_id' => $request->second_grade_id,
                    'dept_id_option' => $request->dept_id_option,
                    'third_post_id' => $request->third_post_id,
                    'third_grade_id' => $request->third_grade_id,
                ]);


                // insert to form submission status 
                $formId = 1; // here we set familly details form id as 2 according to ui;
                $getFormSubmisiondetails = EmpFormSubmissionStatus::get()->where('ein', $request->ein)->where('form_id', $formId)->toArray();
                if (count($getFormSubmisiondetails) == 0) {
                    EmpFormSubmissionStatus::create([
                        'ein' => $request->ein,
                        'form_id' => $formId,
                        'submit_status' => 1 // status 1 = submitted
                    ]);
                } else {
                    //  $emp_form_stat_row = EmpFormSubmissionStatus::get()->where('ein', $request->ein)->where('form_id', $formId)->first();
                    //  $row = EmpFormSubmissionStatus::find($emp_form_stat_row->id);
                    //  $row->submit_status = 1;
                    //  $row->save();
                    //return redirect()->back()->with('error', "Already Submitted..........!");
                    return response()->json(['error' => 'Already Submitted..........!']);
                }

                // Session::put('ein', $request->ein);
                Session::put('ein', $request->ein);

                ProformaHistoryModel::where('ein', $ein)->delete();
                FileHistoryModel::where('ein', $ein)->delete();
                EmpFormSubmissionStatusHistoryModel::where('ein', $ein)->delete();

                //  return back()->with('status', "Data save successfully....");
                return response()->json(['message' => 'Second applicant data save successfully ']);
                //return redirect('ddo-assist/enter-family-details-backlog')->with('status', "Data save successfully....");;
                //ANAND BELOW
            } else {
                // return $plusRemove['1'];
                //return redirect()->back()->with('duplicate', "Deceased EIN already entered!............");
                return response()->json(['error' => 'Deceased EIN already entered!............']);
            }
        } else {
            //return $plusRemove['1'];
            return response()->json(['error' => 'You are not Eligible to apply!!!!..Needs to Complete 15 Years!']);
            // return back()->with('eligible', "You are not Eligible to apply!!!!..Needs to Complete 15 Years!");
        }
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProformaModel  $site
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $ein = $request->route('ein');
        $proformas = ProformaModel::find($ein);

        return view('admin.Form.edit', [
            'proforma' => $proformas,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProformaModel  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProformaModel $proforma)
    {
        // $proforma->delete();
        // return redirect('grade/'); //change grade
    }


    public function viewChangeApplicant(Request $request)
    {
        $request->session()->forget(['ein', 'ein']);

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $change_status = array(0, 1);
        $changeapplicants = ProformaModel::whereIn('change_status', $change_status)->where('status', '!=', 0)->where('dept_id', $getUser->dept_id)->get(); //fetch all sites from proforma table
        return view('hod.viewChangeApplicant', ['changeapplicants' => $changeapplicants]);
    }


    public function show($id)
    {
        $applicant = ProformaModel::find($id);

        if (!$applicant) {
            return view('hod.showApplicantDetails', [
                'ein' => null,
                'applicantName' => null,
                'familyMembers' => [],
            ]);
        }


        // Retrieve the 'ein' value from the ProformaModel
        $ein = $applicant->ein;

        // Store the 'ein' value in the session
        Session::put('ein', $ein);



        $familyMembers = FamilyMembers::where('ein', $applicant->ein)->get();

        return view('hod.showApplicantDetails', [
            'ein' => $applicant->ein,
            'applicantName' => $applicant->applicant_name,
            'familyMembers' => $familyMembers,
            'change_status' => $applicant->change_status,
            'second_applicant' => $applicant->second_appl_name
        ]);
    }

    /////////////////SECOND APPLICANT NAME SAVE/////////////////////////////
    public function saveApplicant(Request $request)
    {
        $ein = session()->get('ein');

        // Fetch the proforma record with the given EIN from the database
        $proforma = ProformaModel::get()->where('ein', $ein)->first();
        $filesuploaded = FileModel::get()->where('ein', $ein)->all();
        //  dd($filesuploaded);
        $empformsubmissionstatus = EmpFormSubmissionStatus::get()->where('ein', $ein)->all();
        // dd($empformsubmissionstatus);
        $second_applicant = $request->input('applicant_name');
        // Update the second applicant name for the found proforma record
        if ($proforma != null) {
            $proforma->update([
                'second_appl_name' => $second_applicant,
                'change_status' => 1
            ]);

            //dd($second_applicant);

            $newApplicant = ProformaHistoryModel::create([

                // 'id'=>$request->id,
                'ein' => $proforma->ein,
                'deceased_emp_name' => $proforma->deceased_emp_name,
                'ministry' => $proforma->ministry,
                'dept_name' => $proforma->dept_name,
                'desig_name' => $proforma->desig_name,
                'grade_name' => $proforma->grade_name,
                'deceased_doa' => $proforma->deceased_doa,
                'deceased_doe' => $proforma->deceased_doe,
                'deceased_dob' => $proforma->deceased_dob,
                'deceased_causeofdeath' => $proforma->deceased_causeofdeath,
                'sex' => $proforma->sex,
                'caste_id' => $proforma->caste_id,

                'appl_number' => $proforma->appl_number,
                'appl_date' => $proforma->appl_date,
                'applicant_name' => $proforma->applicant_name,
                'relationship' => $proforma->relationship,
                'applicant_dob' => $proforma->applicant_dob,
                'applicant_edu_id' => $proforma->applicant_edu_id,
                'applicant_mobile' => $proforma->applicant_mobile,

                'second_appl_name' => $proforma->second_appl_name,

                'applicant_email_id' => $proforma->applicant_email_id,

                'emp_addr_lcality' => $proforma->emp_addr_lcality,
                'emp_addr_subdiv' => $proforma->emp_addr_subdiv,
                'emp_addr_district' => $proforma->emp_addr_district,

                'emp_state' => $proforma->emp_state,
                'emp_pincode' => $proforma->emp_pincode,

                'emp_addr_lcality_ret' => $proforma->emp_addr_lcality_ret,
                'emp_addr_subdiv_ret' => $proforma->emp_addr_subdiv_ret,

                'emp_addr_district_ret' => $proforma->emp_addr_district_ret,



                'emp_state_ret' => $proforma->emp_state_ret,
                'emp_pincode_ret' => $proforma->emp_pincode_ret,

                'applicant_desig_id' => $proforma->applicant_desig_id,
                'applicant_grade' => $proforma->applicant_grade,

                'expire_on_duty' => $proforma->expire_on_duty,
                // 'accept_transfer' => $request->input('accept_transfer'),
                'physically_handicapped' => $proforma->physically_handicapped,
                'uploaded_id' => $proforma->uploaded_id,
                'uploader_role_id' => $proforma->uploader_role_id,

                'dept_id' => $proforma->dept_id,
                'ministry_id' => $proforma->ministry_id,
                'rejected_status' => 0,
                'file_status' => $proforma->file_status,
                'form_status' => $proforma->form_status,
                'status' => $proforma->status,
                'upload_status' => $proforma->upload_status,
                'family_details_status' => $proforma->family_details_status,
                // seema
                'second_post_id' => $proforma->second_post_id,
                'second_grade_id' => $proforma->second_grade_id,
                'dept_id_option' => $proforma->dept_id_option,
                'third_post_id' => $proforma->third_post_id,
                'third_grade_id' => $proforma->third_grade_id,
                //seema


            ]);
        }


        //dd($second_applicant);

        foreach ($filesuploaded as $file) {
            $newApplicantFile = FileHistoryModel::create([
                'ein' => $file->ein,
                'appl_number' => $file->appl_number,
                'uploaded_by' => $file->uploaded_by,
                'file_name' => $file->file_name,
                'file_path' => $file->file_path,
                'doc_id' => $file->doc_id,

            ]);
        }



        //dd($second_applicant);
        foreach ($empformsubmissionstatus as $formStatus) {
            $newApplicantSubmissionStatus = EmpFormSubmissionStatusHistoryModel::create([


                'ein' => $formStatus->ein,
                'form_id' => $formStatus->form_id,
                'form_desc' => $formStatus->form_desc,
                'submit_status' => $formStatus->submit_status,
                'client_ip' => $formStatus->client_ip,


            ]);
        }


        return back()->with('success', 'Second applicants name saved successfully.');
    }


    public function updateSecondApplicantData()
    {
        $ein = session()->get('ein');



        $proformasori = ProformaModel::get()->where('ein', $ein)->first();
        $proformashistory = ProformaHistoryModel::get()->where('ein', $ein)->first();
        // dd($ein, $proformasori->ein, $proformashistory);

        if ($ein != null) {
            // dd($ein, $proformasori, $proformashistory);

            ProformaModel::where('ein', $ein)->delete(); //this data will replace by 2nd applicant
            $test = EmpFormSubmissionStatus::where('ein', $ein);
            $test->delete();
            FileModel::where('ein', $ein)->delete();
            //put form_proforma2ndAppl.blade below

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            //dd($test);
            // $getDeceased = ProformaModel::get()->where('ein', $ein)->first(); //NOT USED

            $getData = ProformaModel::get()->where('ein', $ein)->where('uploaded_id', $user_id)->where('uploader_role_id', 77)->first();
            //dd($getData);
            if ($getData != null) {

                return back()->with('error_message', "You have already applied!!!!"); //check for citizen who have applied twice reject
            }

            $data = array();
            //dd($data['field_dept_desc']);
            $notfound = "";
            if ($ein != '') {
                $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-employee-profile', [
                    'ein' => $ein,
                    'token' => "b000e921eeb20a0d395e341dfcd6117a",
                ]);
                $data = json_decode($response->getBody(), true);
                if (count($data) > 0)
                    $data = $data[0];
                else
                    $notfound = "EIN " . $ein . " not found in CMIS";
            }

            session()->put('ein', $ein);


            $get2nd_appl = ProformaHistoryModel::get()->where('ein', $ein)->first();

            //dd($get2nd_appl->second_appl_name);

            //to check whether getUser->dept_id is equal to ata['field_dept_desc'] if not alert

            if ($getData == null && $getUser->role_id != 77) { //allow to enter data for departments


                $stateDetails = State::getOption()->get();
                // $District = District::get()->toArray();
                // $subDiv = SubDivision::get()->toArray();
                // $post = DesignationModel::where('field_dept_cd', $getUser->dept_id)->orderBy('desig_name')->get()->unique('desig_name')->toArray();
                $cd_grade = array();
                // $notfound = "";

                $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
                    'dept_code' => $get2nd_appl->dept_id,
                    'token' => "b000e921eeb20a0d395e341dfcd6117a",
                ]);
                $cd_grade = json_decode($response->getBody(), true);
                $post = [];
                foreach ($cd_grade as $cdgrade) {
                    if ($cdgrade['group_code'] == 'C' || $cdgrade['group_code'] == 'D') {
                        $post[] = $cdgrade;
                    }
                    // else{
                    //     $post[]=[];
                    // }
                }

                $educations = EducationModel::get()->toArray();

                $Gender = GenderModel::get()->toArray();
                $Caste = CasteModel::get()->toArray();
                $Relationship = RelationshipModel::get()->toArray();
                $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');

                return view('admin/Form/form_proforma2ndAppl', compact('deptListArray', 'Caste', 'Relationship', 'getUser', 'Gender', 'get2nd_appl', 'stateDetails', 'post', 'educations', 'data', 'notfound'));
                // ProformaModel::where('ein', $ein)->delete(); //this data will replace by 2nd applicant
            }
            if ($getUser != null && $getUser->uploader_role_id == 77) { // allow to enter data for fresh citizen
                $stateDetails = State::getOption()->get();
                // $District = District::get()->toArray();
                // $subDiv = SubDivision::get()->toArray();
                //$post = DesignationModel::orderBy('desig_name')->get()->unique('desig_name')->toArray();
                $educations = EducationModel::get()->toArray();
                $cd_grade = array();
                // $notfound = "";

                $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
                    'dept_code' => $get2nd_appl->dept_id,
                    'token' => "b000e921eeb20a0d395e341dfcd6117a",
                ]);
                $cd_grade = json_decode($response->getBody(), true);
                $post = [];
                foreach ($cd_grade as $cdgrade) {
                    if ($cdgrade['group_code'] == 'C' || $cdgrade['group_code'] == 'D') {
                        $post[] = $cdgrade;
                    }
                    // else{
                    //     $post[]=[];
                    // }
                }
                $Gender = GenderModel::get()->toArray();
                
                $Caste = CasteModel::get()->toArray();
                $Relationship = RelationshipModel::get()->toArray();
                $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');

                return view('admin/Form/form_proforma2ndAppl', compact('deptListArray', 'Caste', 'Relationship', 'getUser', 'Gender', 'get2nd_appl', 'stateDetails', 'post', 'educations', 'data', 'notfound'));
                // ProformaModel::where('ein', $ein)->delete(); //this data will replace by 2nd applicant
            }
            //$changeapplicants = ProformaModel::all(); //fetch all sites from proforma table
            // return view('hod.viewChangeApplicant', ['changeapplicants' => $changeapplicants]);
        }
    }

   

    
    public function update(Request $request)
    {


        // dd( $request->all());
        // Validate the incoming request
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $ein = $request->input('ein');

        $educations = EducationModel::where('id', $request->applicant_edu_id)->first();
        // dd( $ein);

        // Find the ProformaModel instance by EIN
        $proforma = ProformaModel::where('ein', $ein)->first();
        if ($proforma->second_post_id != 0) {
            if ($getUser->role_id == 1) {
             
                $validatedData = $request->validate([
                    'expire_on_duty' => 'required',
                    'deceased_doe' => 'required|string',
                    'deceased_causeofdeath' => 'required|string',
                    'applicant_name' => 'required|string',
                    'appl_date' => 'required|string',
                    'applicant_dob' => 'required',
                    'relationship' => 'required', // This part of the rule checks the existence
                    //  of the relationship field's value in the id column of the relationship table in the database.
                    'applicant_mobile' => 'required|string',
                    'applicant_edu_id' => 'required',
                    'physically_handicapped' => 'required',
                    'applicant_email_id' => 'required|string',
                    'caste_id' => 'required',
                    'sex' => 'required',
                    'emp_addr_lcality' => 'required|string',
                    'emp_addr_district' => 'required',
                    'emp_state' => 'required',
                    'emp_pincode' => 'required',
                    'emp_addr_lcality_ret' => 'required|string',
                    'emp_addr_district_ret' => 'required',
                    'emp_state_ret' => 'required',
                    'emp_pincode_ret' => 'required',
                    'applicant_desig_id' => 'required',
                    'applicant_grade' => 'required',
                    'second_post_id' => 'required',
                    'second_grade_id' => 'required',
                    'third_post_id' => 'required',
                    'third_grade_id' => 'required',
                    'dept_id_option' => 'required',
                    'other_qualification' =>'',
                    // Add other fields and validation rules as needed
                ]);



                // Get the EIN and relationship from the request


                // Check if the ProformaModel instance exists
                if (!$proforma) {
                    return response()->json(['error' => 'Proforma not found'], 404);
                }

              
                //dd($educations);
                if($educations->edu_name== "Others"){
                        
               
                ///PUTTING ALL DATA IN SESSION////////////
                session()->put([
                    'ein' => $ein, 'deceased_emp_name' => $proforma->deceased_emp_name, 'ministry' => $proforma->ministry, 'dept_name' => $proforma->dept_name,
                    'desig_name' => $proforma->desig_name, 'grade_name' => $proforma->grade_name, 'deceased_doa' => $proforma->deceased_doa,
                    'deceased_doe' => $proforma->deceased_doe, 'deceased_dob' => $proforma->deceased_dob, 'deceased_causeofdeath' => $proforma->deceased_causeofdeath,
                    'caste_id' => $proforma->caste_id, 'sex' => $proforma->sex, 'appl_date' => $proforma->appl_date, 'applicant_name' => $proforma->applicant_name,
                    'relationship' => $proforma->relationship, 'applicant_dob' => $proforma->applicant_dob, 'applicant_edu_id' => $proforma->applicant_edu_id,
                    'applicant_mobile' => $proforma->applicant_mobile, 'applicant_email_id' => $proforma->applicant_email_id, 'emp_addr_lcality' => $proforma->emp_addr_lcality,
                    'emp_addr_subdiv' => $proforma->emp_addr_subdiv, 'emp_addr_district' => $proforma->emp_addr_district, 'emp_state' => $proforma->emp_state,
                    'emp_pincode' => $proforma->emp_pincode, 'emp_addr_lcality_ret' => $proforma->emp_addr_lcality_ret, 'emp_addr_subdiv_ret' => $proforma->emp_addr_subdiv_ret,
                    'emp_addr_district_ret' => $proforma->emp_addr_district_ret, 'emp_state_ret' => $proforma->emp_state_ret, 'emp_pincode_ret' => $proforma->emp_pincode_ret,
                    'applicant_desig_id' => $proforma->applicant_desig_id, 'applicant_grade' => $proforma->applicant_grade, 'expire_on_duty' => $proforma->expire_on_duty,
                    'physically_handicapped' => $proforma->physically_handicapped, 'uploaded_id' => $proforma->uploaded_id, 'uploader_role_id' => $proforma->uploader_role_id,
                    'dept_id' => $proforma->dept_id, 'ministry_id' => $proforma->ministry_id, 'file_status' => $proforma->file_status, 'rejected_status' => $proforma->rejected_status,
                    'upload_status' => $proforma->upload_status, 'family_details_status' => $proforma->family_details_status, 'status' => $proforma->status,
                    'change_status' => $proforma->change_status, 'form_status' => $proforma->form_status,
                    'second_post_id' => $proforma->second_post_id,
                    'second_grade_id' => $proforma->second_grade_id,
                    'dept_id_option' => $proforma->dept_id_option,
                    'third_post_id' => $proforma->third_post_id,
                    'third_grade_id' => $proforma->third_grade_id,
                    'other_qualification' => $proforma->other_qualification,
                ]);
                ////END OF SESSION
                //dd($proforma);
              
                // Update the relationship and other columns in the ProformaModel instance
                $proforma->update([
                    'relationship' => $validatedData['relationship'],
                    'expire_on_duty' => $validatedData['expire_on_duty'],
                    'deceased_doe' => $validatedData['deceased_doe'],
                    'deceased_causeofdeath' => $validatedData['deceased_causeofdeath'],
                    'applicant_name' => $validatedData['applicant_name'],
                    'appl_date' => $validatedData['appl_date'],
                    'applicant_dob' => $validatedData['applicant_dob'],
                    'applicant_mobile' => $validatedData['applicant_mobile'],
                    'applicant_edu_id' => $validatedData['applicant_edu_id'],
                   

                    'physically_handicapped' => $validatedData['physically_handicapped'],
                    'applicant_email_id' => $validatedData['applicant_email_id'],
                    'caste_id' => $validatedData['caste_id'],
                    'sex' =>  $validatedData['sex'],
                    'emp_addr_lcality' => $validatedData['emp_addr_lcality'],
                    'emp_addr_district' => $validatedData['emp_addr_district'],
                    'emp_state' => $validatedData['emp_state'],
                    'emp_pincode' => $validatedData['emp_pincode'],
                    'emp_addr_lcality_ret' => $validatedData['emp_addr_lcality_ret'],
                    'emp_addr_district_ret' => $validatedData['emp_addr_district_ret'],
                    'emp_state_ret' => $validatedData['emp_state_ret'],
                    'emp_pincode_ret' => $validatedData['emp_pincode_ret'],
                    'applicant_desig_id' => $validatedData['applicant_desig_id'],
                    'applicant_grade' => $validatedData['applicant_grade'],

                    'second_post_id' => $validatedData['second_post_id'],
                    'second_grade_id' => $validatedData['second_grade_id'],
                    'dept_id_option' => $validatedData['dept_id_option'],
                    'third_post_id' => $validatedData['third_post_id'],
                    'third_grade_id' => $validatedData['third_grade_id'],
                    'other_qualification' => $validatedData['other_qualification'],

                    'rejected_status' => 0

                    // Add other columns as needed
                ]);
                //dd($request->all());
                // Return a response, for example, a success message
                return response()->json(['message' => 'Data updated successfully ']);
                //return back()->with('status', "1 Data save successfully....");
            }else{
                session()->put([
                    'ein' => $ein, 'deceased_emp_name' => $proforma->deceased_emp_name, 'ministry' => $proforma->ministry, 'dept_name' => $proforma->dept_name,
                    'desig_name' => $proforma->desig_name, 'grade_name' => $proforma->grade_name, 'deceased_doa' => $proforma->deceased_doa,
                    'deceased_doe' => $proforma->deceased_doe, 'deceased_dob' => $proforma->deceased_dob, 'deceased_causeofdeath' => $proforma->deceased_causeofdeath,
                    'caste_id' => $proforma->caste_id, 'sex' => $proforma->sex, 'appl_date' => $proforma->appl_date, 'applicant_name' => $proforma->applicant_name,
                    'relationship' => $proforma->relationship, 'applicant_dob' => $proforma->applicant_dob, 'applicant_edu_id' => $proforma->applicant_edu_id,
                    'applicant_mobile' => $proforma->applicant_mobile, 'applicant_email_id' => $proforma->applicant_email_id, 'emp_addr_lcality' => $proforma->emp_addr_lcality,
                    'emp_addr_subdiv' => $proforma->emp_addr_subdiv, 'emp_addr_district' => $proforma->emp_addr_district, 'emp_state' => $proforma->emp_state,
                    'emp_pincode' => $proforma->emp_pincode, 'emp_addr_lcality_ret' => $proforma->emp_addr_lcality_ret, 'emp_addr_subdiv_ret' => $proforma->emp_addr_subdiv_ret,
                    'emp_addr_district_ret' => $proforma->emp_addr_district_ret, 'emp_state_ret' => $proforma->emp_state_ret, 'emp_pincode_ret' => $proforma->emp_pincode_ret,
                    'applicant_desig_id' => $proforma->applicant_desig_id, 'applicant_grade' => $proforma->applicant_grade, 'expire_on_duty' => $proforma->expire_on_duty,
                    'physically_handicapped' => $proforma->physically_handicapped, 'uploaded_id' => $proforma->uploaded_id, 'uploader_role_id' => $proforma->uploader_role_id,
                    'dept_id' => $proforma->dept_id, 'ministry_id' => $proforma->ministry_id, 'file_status' => $proforma->file_status, 'rejected_status' => $proforma->rejected_status,
                    'upload_status' => $proforma->upload_status, 'family_details_status' => $proforma->family_details_status, 'status' => $proforma->status,
                    'change_status' => $proforma->change_status, 'form_status' => $proforma->form_status,
                    'second_post_id' => $proforma->second_post_id,
                    'second_grade_id' => $proforma->second_grade_id,
                    'dept_id_option' => $proforma->dept_id_option,
                    'third_post_id' => $proforma->third_post_id,
                    'third_grade_id' => $proforma->third_grade_id,
                   // 'other_qualification' => $proforma->other_qualification,
                ]);
                ////END OF SESSION
                //dd($proforma);
              
                // Update the relationship and other columns in the ProformaModel instance
                $proforma->update([
                    'relationship' => $validatedData['relationship'],
                    'expire_on_duty' => $validatedData['expire_on_duty'],
                    'deceased_doe' => $validatedData['deceased_doe'],
                    'deceased_causeofdeath' => $validatedData['deceased_causeofdeath'],
                    'applicant_name' => $validatedData['applicant_name'],
                    'appl_date' => $validatedData['appl_date'],
                    'applicant_dob' => $validatedData['applicant_dob'],
                    'applicant_mobile' => $validatedData['applicant_mobile'],
                    'applicant_edu_id' => $validatedData['applicant_edu_id'],
                   

                    'physically_handicapped' => $validatedData['physically_handicapped'],
                    'applicant_email_id' => $validatedData['applicant_email_id'],
                    'caste_id' => $validatedData['caste_id'],
                    'sex' =>  $validatedData['sex'],
                    'emp_addr_lcality' => $validatedData['emp_addr_lcality'],
                    'emp_addr_district' => $validatedData['emp_addr_district'],
                    'emp_state' => $validatedData['emp_state'],
                    'emp_pincode' => $validatedData['emp_pincode'],
                    'emp_addr_lcality_ret' => $validatedData['emp_addr_lcality_ret'],
                    'emp_addr_district_ret' => $validatedData['emp_addr_district_ret'],
                    'emp_state_ret' => $validatedData['emp_state_ret'],
                    'emp_pincode_ret' => $validatedData['emp_pincode_ret'],
                    'applicant_desig_id' => $validatedData['applicant_desig_id'],
                    'applicant_grade' => $validatedData['applicant_grade'],

                    'second_post_id' => $validatedData['second_post_id'],
                    'second_grade_id' => $validatedData['second_grade_id'],
                    'dept_id_option' => $validatedData['dept_id_option'],
                    'third_post_id' => $validatedData['third_post_id'],
                    'third_grade_id' => $validatedData['third_grade_id'],
                    'other_qualification' =>  "",

                    'rejected_status' => 0

                    // Add other columns as needed
                ]);
                //dd($request->all());
                // Return a response, for example, a success message
                return response()->json(['message' => 'Data updated successfully ']);
               // return back()->with('status', "2 Data save successfully....");
            }
        }else {
                $validatedData = $request->validate([
                    'expire_on_duty' => 'required',
                    'deceased_doe' => 'required|string',
                    'deceased_causeofdeath' => 'required|string',
                    'applicant_name' => 'required|string',
                    // 'appl_date' => 'required|string',
                    'applicant_dob' => 'required',
                    'relationship' => 'required', // This part of the rule checks the existence
                    //  of the relationship field's value in the id column of the relationship table in the database.
                    'applicant_mobile' => 'required|string',
                    'applicant_edu_id' => 'required',
                    'physically_handicapped' => 'required',
                    'applicant_email_id' => 'required|string',
                    'caste_id' => 'required',
                    'sex' => 'required',
                    'emp_addr_lcality' => 'required|string',
                    'emp_addr_district' => 'required',
                    'emp_state' => 'required',
                    'emp_pincode' => 'required',
                    'emp_addr_lcality_ret' => 'required|string',
                    'emp_addr_district_ret' => 'required',
                    'emp_state_ret' => 'required',
                    'emp_pincode_ret' => 'required',
                    'applicant_desig_id' => 'required',
                    'applicant_grade' => 'required',
                    'second_post_id' => 'required',
                    'second_grade_id' => 'required',
                    'dept_id_option' => 'required',
                    'third_post_id' => 'required',
                    'third_grade_id' => 'required',
                    'other_qualification' =>'',
                    // Add other fields and validation rules as needed
                ]);



                // Get the EIN and relationship from the request
                $ein = $request->input('ein');
                // dd( $ein);

                // Find the ProformaModel instance by EIN
                $proforma = ProformaModel::where('ein', $ein)->first();

                // Check if the ProformaModel instance exists
                if (!$proforma) {
                    return response()->json(['error' => 'Proforma not found'], 404);
                }
             
               // dd($educations);
               if($educations->edu_name== "Others"){

                ///PUTTING ALL DATA IN SESSION////////////
                session()->put([
                    'ein' => $ein, 'deceased_emp_name' => $proforma->deceased_emp_name, 'ministry' => $proforma->ministry, 'dept_name' => $proforma->dept_name,
                    'desig_name' => $proforma->desig_name, 'grade_name' => $proforma->grade_name, 'deceased_doa' => $proforma->deceased_doa,
                    'deceased_doe' => $proforma->deceased_doe, 'deceased_dob' => $proforma->deceased_dob, 'deceased_causeofdeath' => $proforma->deceased_causeofdeath,
                    'caste_id' => $proforma->caste_id, 'sex' => $proforma->sex, 'appl_date' => $proforma->appl_date, 'applicant_name' => $proforma->applicant_name,
                    'relationship' => $proforma->relationship, 'applicant_dob' => $proforma->applicant_dob, 'applicant_edu_id' => $proforma->applicant_edu_id,
                    'applicant_mobile' => $proforma->applicant_mobile, 'applicant_email_id' => $proforma->applicant_email_id, 'emp_addr_lcality' => $proforma->emp_addr_lcality,
                    'emp_addr_subdiv' => $proforma->emp_addr_subdiv, 'emp_addr_district' => $proforma->emp_addr_district, 'emp_state' => $proforma->emp_state,
                    'emp_pincode' => $proforma->emp_pincode, 'emp_addr_lcality_ret' => $proforma->emp_addr_lcality_ret, 'emp_addr_subdiv_ret' => $proforma->emp_addr_subdiv_ret,
                    'emp_addr_district_ret' => $proforma->emp_addr_district_ret, 'emp_state_ret' => $proforma->emp_state_ret, 'emp_pincode_ret' => $proforma->emp_pincode_ret,
                    'applicant_desig_id' => $proforma->applicant_desig_id, 'applicant_grade' => $proforma->applicant_grade, 'expire_on_duty' => $proforma->expire_on_duty,
                    'physically_handicapped' => $proforma->physically_handicapped, 'uploaded_id' => $proforma->uploaded_id, 'uploader_role_id' => $proforma->uploader_role_id,
                    'dept_id' => $proforma->dept_id, 'ministry_id' => $proforma->ministry_id, 'file_status' => $proforma->file_status, 'rejected_status' => $proforma->rejected_status,
                    'upload_status' => $proforma->upload_status, 'family_details_status' => $proforma->family_details_status, 'status' => $proforma->status,
                    'change_status' => $proforma->change_status, 'form_status' => $proforma->form_status,
                    'second_post_id' => $proforma->second_post_id,
                    'second_grade_id' => $proforma->second_grade_id,
                    'dept_id_option' => $proforma->dept_id_option,
                    'third_post_id' => $proforma->third_post_id,
                    'third_grade_id' => $proforma->third_grade_id,
                    'other_qualification' => $proforma->other_qualification,
                ]);
                ////END OF SESSION

                // Update the relationship and other columns in the ProformaModel instance
                $proforma->update([
                    'relationship' => $validatedData['relationship'],
                    'expire_on_duty' => $validatedData['expire_on_duty'],
                    'deceased_doe' => $validatedData['deceased_doe'],
                    'deceased_causeofdeath' => $validatedData['deceased_causeofdeath'],
                    'applicant_name' => $validatedData['applicant_name'],
                    // 'appl_date' => $validatedData['appl_date'],
                    'applicant_dob' => $validatedData['applicant_dob'],
                    'applicant_mobile' => $validatedData['applicant_mobile'],
                    'applicant_edu_id' => $validatedData['applicant_edu_id'],
                    'physically_handicapped' => $validatedData['physically_handicapped'],
                    'applicant_email_id' => $validatedData['applicant_email_id'],
                    'caste_id' => $validatedData['caste_id'],
                    'sex' =>  $validatedData['sex'],
                    'emp_addr_lcality' => $validatedData['emp_addr_lcality'],
                    'emp_addr_district' => $validatedData['emp_addr_district'],
                    'emp_state' => $validatedData['emp_state'],
                    'emp_pincode' => $validatedData['emp_pincode'],
                    'emp_addr_lcality_ret' => $validatedData['emp_addr_lcality_ret'],
                    'emp_addr_district_ret' => $validatedData['emp_addr_district_ret'],
                    'emp_state_ret' => $validatedData['emp_state_ret'],
                    'emp_pincode_ret' => $validatedData['emp_pincode_ret'],
                    'applicant_desig_id' => $validatedData['applicant_desig_id'],
                    'applicant_grade' => $validatedData['applicant_grade'],

                    'second_post_id' => $validatedData['second_post_id'],
                    'second_grade_id' => $validatedData['second_grade_id'],
                    'dept_id_option' => $validatedData['dept_id_option'],
                    'third_post_id' => $validatedData['third_post_id'],
                    'third_grade_id' => $validatedData['third_grade_id'],
                    'other_qualification' =>  $validatedData['other_qualification'],
                    'rejected_status' => 0

                    // Add other columns as needed
                ]);
                //dd($request->all());
                // Return a response, for example, a success message
                return response()->json(['message' => 'Data updated successfully ']);
                //return back()->with('status', "3 Data save successfully....");
            }else {
                session()->put([
                    'ein' => $ein, 'deceased_emp_name' => $proforma->deceased_emp_name, 'ministry' => $proforma->ministry, 'dept_name' => $proforma->dept_name,
                    'desig_name' => $proforma->desig_name, 'grade_name' => $proforma->grade_name, 'deceased_doa' => $proforma->deceased_doa,
                    'deceased_doe' => $proforma->deceased_doe, 'deceased_dob' => $proforma->deceased_dob, 'deceased_causeofdeath' => $proforma->deceased_causeofdeath,
                    'caste_id' => $proforma->caste_id, 'sex' => $proforma->sex, 'appl_date' => $proforma->appl_date, 'applicant_name' => $proforma->applicant_name,
                    'relationship' => $proforma->relationship, 'applicant_dob' => $proforma->applicant_dob, 'applicant_edu_id' => $proforma->applicant_edu_id,
                    'applicant_mobile' => $proforma->applicant_mobile, 'applicant_email_id' => $proforma->applicant_email_id, 'emp_addr_lcality' => $proforma->emp_addr_lcality,
                    'emp_addr_subdiv' => $proforma->emp_addr_subdiv, 'emp_addr_district' => $proforma->emp_addr_district, 'emp_state' => $proforma->emp_state,
                    'emp_pincode' => $proforma->emp_pincode, 'emp_addr_lcality_ret' => $proforma->emp_addr_lcality_ret, 'emp_addr_subdiv_ret' => $proforma->emp_addr_subdiv_ret,
                    'emp_addr_district_ret' => $proforma->emp_addr_district_ret, 'emp_state_ret' => $proforma->emp_state_ret, 'emp_pincode_ret' => $proforma->emp_pincode_ret,
                    'applicant_desig_id' => $proforma->applicant_desig_id, 'applicant_grade' => $proforma->applicant_grade, 'expire_on_duty' => $proforma->expire_on_duty,
                    'physically_handicapped' => $proforma->physically_handicapped, 'uploaded_id' => $proforma->uploaded_id, 'uploader_role_id' => $proforma->uploader_role_id,
                    'dept_id' => $proforma->dept_id, 'ministry_id' => $proforma->ministry_id, 'file_status' => $proforma->file_status, 'rejected_status' => $proforma->rejected_status,
                    'upload_status' => $proforma->upload_status, 'family_details_status' => $proforma->family_details_status, 'status' => $proforma->status,
                    'change_status' => $proforma->change_status, 'form_status' => $proforma->form_status,
                    'second_post_id' => $proforma->second_post_id,
                    'second_grade_id' => $proforma->second_grade_id,
                    'dept_id_option' => $proforma->dept_id_option,
                    'third_post_id' => $proforma->third_post_id,
                    'third_grade_id' => $proforma->third_grade_id,
                   // 'other_qualification' => $proforma->other_qualification,
                ]);
                ////END OF SESSION

                // Update the relationship and other columns in the ProformaModel instance
                $proforma->update([
                    'relationship' => $validatedData['relationship'],
                    'expire_on_duty' => $validatedData['expire_on_duty'],
                    'deceased_doe' => $validatedData['deceased_doe'],
                    'deceased_causeofdeath' => $validatedData['deceased_causeofdeath'],
                    'applicant_name' => $validatedData['applicant_name'],
                    // 'appl_date' => $validatedData['appl_date'],
                    'applicant_dob' => $validatedData['applicant_dob'],
                    'applicant_mobile' => $validatedData['applicant_mobile'],
                    'applicant_edu_id' => $validatedData['applicant_edu_id'],
                    'physically_handicapped' => $validatedData['physically_handicapped'],
                    'applicant_email_id' => $validatedData['applicant_email_id'],
                    'caste_id' => $validatedData['caste_id'],
                    'sex' =>  $validatedData['sex'],
                    'emp_addr_lcality' => $validatedData['emp_addr_lcality'],
                    'emp_addr_district' => $validatedData['emp_addr_district'],
                    'emp_state' => $validatedData['emp_state'],
                    'emp_pincode' => $validatedData['emp_pincode'],
                    'emp_addr_lcality_ret' => $validatedData['emp_addr_lcality_ret'],
                    'emp_addr_district_ret' => $validatedData['emp_addr_district_ret'],
                    'emp_state_ret' => $validatedData['emp_state_ret'],
                    'emp_pincode_ret' => $validatedData['emp_pincode_ret'],
                    'applicant_desig_id' => $validatedData['applicant_desig_id'],
                    'applicant_grade' => $validatedData['applicant_grade'],

                    'second_post_id' => $validatedData['second_post_id'],
                    'second_grade_id' => $validatedData['second_grade_id'],
                    'dept_id_option' => $validatedData['dept_id_option'],
                    'third_post_id' => $validatedData['third_post_id'],
                    'third_grade_id' => $validatedData['third_grade_id'],
                    'other_qualification' =>  "",
                    'rejected_status' => 0

                    // Add other columns as needed
                ]);
                //dd($request->all());
                // Return a response, for example, a success message
                return response()->json(['message' => 'Data updated successfully ']);
               // return back()->with('status', "4 Data save successfully....");
         } 
        }
    }else {
            //for those selected only 1 post(applicant_desig_id)
            if ($getUser->role_id == 1) {

                $validatedData = $request->validate([
                    'expire_on_duty' => 'required',
                    'deceased_doe' => 'required|string',
                    'deceased_causeofdeath' => 'required|string',
                    'applicant_name' => 'required|string',
                    'appl_date' => 'required|string',
                    'applicant_dob' => 'required',
                    'relationship' => 'required', // This part of the rule checks the existence
                    //  of the relationship field's value in the id column of the relationship table in the database.
                    'applicant_mobile' => 'required|string',
                    'applicant_edu_id' => 'required',
                    'physically_handicapped' => 'required',
                    'applicant_email_id' => 'required|string',
                    'caste_id' => 'required',
                    'sex' => 'required',
                    'emp_addr_lcality' => 'required|string',
                    'emp_addr_district' => 'required',
                    'emp_state' => 'required',
                    'emp_pincode' => 'required',
                    'emp_addr_lcality_ret' => 'required|string',
                    'emp_addr_district_ret' => 'required',
                    'emp_state_ret' => 'required',
                    'emp_pincode_ret' => 'required',
                    'applicant_desig_id' => 'required',
                    'applicant_grade' => 'required',
                    'other_qualification' =>'',
                    // 'second_grade_id' =>'required',
                    // 'third_post_id' =>'required',
                    // 'third_grade_id' =>'required',
                    // 'dept_id_option' =>'required',
                    // Add other fields and validation rules as needed
                ]);

                // Get the EIN and relationship from the request


                // Check if the ProformaModel instance exists
                if (!$proforma) {
                    return response()->json(['error' => 'Proforma not found'], 404);
                }
              
                //dd($educations);
                if($educations->edu_name== "Others"){
                ///PUTTING ALL DATA IN SESSION////////////
                //dd($educations, "jhgjhgjh");
                session()->put([
                    'ein' => $ein, 'deceased_emp_name' => $proforma->deceased_emp_name, 'ministry' => $proforma->ministry, 'dept_name' => $proforma->dept_name,
                    'desig_name' => $proforma->desig_name, 'grade_name' => $proforma->grade_name, 'deceased_doa' => $proforma->deceased_doa,
                    'deceased_doe' => $proforma->deceased_doe, 'deceased_dob' => $proforma->deceased_dob, 'deceased_causeofdeath' => $proforma->deceased_causeofdeath,
                    'caste_id' => $proforma->caste_id, 'sex' => $proforma->sex, 'appl_date' => $proforma->appl_date, 'applicant_name' => $proforma->applicant_name,
                    'relationship' => $proforma->relationship, 'applicant_dob' => $proforma->applicant_dob, 'applicant_edu_id' => $proforma->applicant_edu_id,
                    'applicant_mobile' => $proforma->applicant_mobile, 'applicant_email_id' => $proforma->applicant_email_id, 'emp_addr_lcality' => $proforma->emp_addr_lcality,
                    'emp_addr_subdiv' => $proforma->emp_addr_subdiv, 'emp_addr_district' => $proforma->emp_addr_district, 'emp_state' => $proforma->emp_state,
                    'emp_pincode' => $proforma->emp_pincode, 'emp_addr_lcality_ret' => $proforma->emp_addr_lcality_ret, 'emp_addr_subdiv_ret' => $proforma->emp_addr_subdiv_ret,
                    'emp_addr_district_ret' => $proforma->emp_addr_district_ret, 'emp_state_ret' => $proforma->emp_state_ret, 'emp_pincode_ret' => $proforma->emp_pincode_ret,
                    'applicant_desig_id' => $proforma->applicant_desig_id, 'applicant_grade' => $proforma->applicant_grade, 'expire_on_duty' => $proforma->expire_on_duty,
                    'physically_handicapped' => $proforma->physically_handicapped, 'uploaded_id' => $proforma->uploaded_id, 'uploader_role_id' => $proforma->uploader_role_id,
                    'dept_id' => $proforma->dept_id, 'ministry_id' => $proforma->ministry_id, 'file_status' => $proforma->file_status, 'rejected_status' => $proforma->rejected_status,
                    'upload_status' => $proforma->upload_status, 'family_details_status' => $proforma->family_details_status, 'status' => $proforma->status,
                    'change_status' => $proforma->change_status, 'form_status' => $proforma->form_status,
                    'second_post_id' => $proforma->second_post_id,
                    'second_grade_id' => $proforma->second_grade_id,
                    'dept_id_option' => $proforma->dept_id_option,
                    'third_post_id' => $proforma->third_post_id,
                    'third_grade_id' => $proforma->third_grade_id,
                    'other_qualification' =>  $proforma->other_qualification,
                ]);
                ////END OF SESSION
                //dd($proforma);
                // Update the relationship and other columns in the ProformaModel instance
                $proforma->update([
                    'relationship' => $validatedData['relationship'],
                    'expire_on_duty' => $validatedData['expire_on_duty'],
                    'deceased_doe' => $validatedData['deceased_doe'],
                    'deceased_causeofdeath' => $validatedData['deceased_causeofdeath'],
                    'applicant_name' => $validatedData['applicant_name'],
                    'appl_date' => $validatedData['appl_date'],
                    'applicant_dob' => $validatedData['applicant_dob'],
                    'applicant_mobile' => $validatedData['applicant_mobile'],
                    'applicant_edu_id' => $validatedData['applicant_edu_id'],
                    'physically_handicapped' => $validatedData['physically_handicapped'],
                    'applicant_email_id' => $validatedData['applicant_email_id'],
                    'caste_id' => $validatedData['caste_id'],
                    'sex' =>  $validatedData['sex'],
                    'emp_addr_lcality' => $validatedData['emp_addr_lcality'],
                    'emp_addr_district' => $validatedData['emp_addr_district'],
                    'emp_state' => $validatedData['emp_state'],
                    'emp_pincode' => $validatedData['emp_pincode'],
                    'emp_addr_lcality_ret' => $validatedData['emp_addr_lcality_ret'],
                    'emp_addr_district_ret' => $validatedData['emp_addr_district_ret'],
                    'emp_state_ret' => $validatedData['emp_state_ret'],
                    'emp_pincode_ret' => $validatedData['emp_pincode_ret'],
                    'applicant_desig_id' => $validatedData['applicant_desig_id'],
                    'applicant_grade' => $validatedData['applicant_grade'],
                    'other_qualification' =>  $validatedData['other_qualification'],
                    'second_post_id' => 0,
                    'second_grade_id' => "NA",
                    'dept_id_option' => 0,
                    'third_post_id' => 0,
                    'third_grade_id' => "NA",

                    'rejected_status' => 0

                    // Add other columns as needed
                ]);
                //dd($request->all());
                // Return a response, for example, a success message
                return response()->json(['message' => 'Data updated successfully ']);
               // return back()->with('status', "5 Data save successfully....");

            }else{

                session()->put([
                    'ein' => $ein, 'deceased_emp_name' => $proforma->deceased_emp_name, 'ministry' => $proforma->ministry, 'dept_name' => $proforma->dept_name,
                    'desig_name' => $proforma->desig_name, 'grade_name' => $proforma->grade_name, 'deceased_doa' => $proforma->deceased_doa,
                    'deceased_doe' => $proforma->deceased_doe, 'deceased_dob' => $proforma->deceased_dob, 'deceased_causeofdeath' => $proforma->deceased_causeofdeath,
                    'caste_id' => $proforma->caste_id, 'sex' => $proforma->sex, 'appl_date' => $proforma->appl_date, 'applicant_name' => $proforma->applicant_name,
                    'relationship' => $proforma->relationship, 'applicant_dob' => $proforma->applicant_dob, 'applicant_edu_id' => $proforma->applicant_edu_id,
                    'applicant_mobile' => $proforma->applicant_mobile, 'applicant_email_id' => $proforma->applicant_email_id, 'emp_addr_lcality' => $proforma->emp_addr_lcality,
                    'emp_addr_subdiv' => $proforma->emp_addr_subdiv, 'emp_addr_district' => $proforma->emp_addr_district, 'emp_state' => $proforma->emp_state,
                    'emp_pincode' => $proforma->emp_pincode, 'emp_addr_lcality_ret' => $proforma->emp_addr_lcality_ret, 'emp_addr_subdiv_ret' => $proforma->emp_addr_subdiv_ret,
                    'emp_addr_district_ret' => $proforma->emp_addr_district_ret, 'emp_state_ret' => $proforma->emp_state_ret, 'emp_pincode_ret' => $proforma->emp_pincode_ret,
                    'applicant_desig_id' => $proforma->applicant_desig_id, 'applicant_grade' => $proforma->applicant_grade, 'expire_on_duty' => $proforma->expire_on_duty,
                    'physically_handicapped' => $proforma->physically_handicapped, 'uploaded_id' => $proforma->uploaded_id, 'uploader_role_id' => $proforma->uploader_role_id,
                    'dept_id' => $proforma->dept_id, 'ministry_id' => $proforma->ministry_id, 'file_status' => $proforma->file_status, 'rejected_status' => $proforma->rejected_status,
                    'upload_status' => $proforma->upload_status, 'family_details_status' => $proforma->family_details_status, 'status' => $proforma->status,
                    'change_status' => $proforma->change_status, 'form_status' => $proforma->form_status,
                    'second_post_id' => $proforma->second_post_id,
                    'second_grade_id' => $proforma->second_grade_id,
                    'dept_id_option' => $proforma->dept_id_option,
                    'third_post_id' => $proforma->third_post_id,
                    'third_grade_id' => $proforma->third_grade_id,
                   // 'other_qualification' =>  $proforma->other_qualification,
                ]);
                ////END OF SESSION
                //dd($proforma);
              //  dd($educations, $validatedData['other_qualification']);
                // Update the relationship and other columns in the ProformaModel instance
                $proforma->update([
                    'relationship' => $validatedData['relationship'],
                    'expire_on_duty' => $validatedData['expire_on_duty'],
                    'deceased_doe' => $validatedData['deceased_doe'],
                    'deceased_causeofdeath' => $validatedData['deceased_causeofdeath'],
                    'applicant_name' => $validatedData['applicant_name'],
                    'appl_date' => $validatedData['appl_date'],
                    'applicant_dob' => $validatedData['applicant_dob'],
                    'applicant_mobile' => $validatedData['applicant_mobile'],
                    'applicant_edu_id' => $validatedData['applicant_edu_id'],
                    'physically_handicapped' => $validatedData['physically_handicapped'],
                    'applicant_email_id' => $validatedData['applicant_email_id'],
                    'caste_id' => $validatedData['caste_id'],
                    'sex' =>  $validatedData['sex'],
                    'emp_addr_lcality' => $validatedData['emp_addr_lcality'],
                    'emp_addr_district' => $validatedData['emp_addr_district'],
                    'emp_state' => $validatedData['emp_state'],
                    'emp_pincode' => $validatedData['emp_pincode'],
                    'emp_addr_lcality_ret' => $validatedData['emp_addr_lcality_ret'],
                    'emp_addr_district_ret' => $validatedData['emp_addr_district_ret'],
                    'emp_state_ret' => $validatedData['emp_state_ret'],
                    'emp_pincode_ret' => $validatedData['emp_pincode_ret'],
                    'applicant_desig_id' => $validatedData['applicant_desig_id'],
                    'applicant_grade' => $validatedData['applicant_grade'],
                    'other_qualification' =>  "",
                    'second_post_id' => 0,
                    'second_grade_id' => "NA",
                    'dept_id_option' => 0,
                    'third_post_id' => 0,
                    'third_grade_id' => "NA",

                    'rejected_status' => 0

                    // Add other columns as needed
                ]);
                //dd($request->all());
                // Return a response, for example, a success message
                return response()->json(['message' => 'Data updated successfully ']);
               // return back()->with('status', "6 Data save successfully....");
            }
        } else {
                $validatedData = $request->validate([
                    'expire_on_duty' => 'required',
                    'deceased_doe' => 'required|string',
                    'deceased_causeofdeath' => 'required|string',
                    'applicant_name' => 'required|string',
                    // 'appl_date' => 'required|string',
                    'applicant_dob' => 'required',
                    'relationship' => 'required', // This part of the rule checks the existence
                    //  of the relationship field's value in the id column of the relationship table in the database.
                    'applicant_mobile' => 'required|string',
                    'applicant_edu_id' => 'required',
                    'physically_handicapped' => 'required',
                    'applicant_email_id' => 'required|string',
                    'caste_id' => 'required',
                    'sex' => 'required',
                    'emp_addr_lcality' => 'required|string',
                    'emp_addr_district' => 'required',
                    'emp_state' => 'required',
                    'emp_pincode' => 'required',
                    'emp_addr_lcality_ret' => 'required|string',
                    'emp_addr_district_ret' => 'required',
                    'emp_state_ret' => 'required',
                    'emp_pincode_ret' => 'required',
                    'applicant_desig_id' => 'required',
                    'applicant_grade' => 'required',
                    'other_qualification' =>'',
                    // 'second_post_id' => 'required',
                    // 'second_grade_id' => 'required',
                    // 'dept_id_option' => 'required',
                    // 'third_post_id' => 'required',
                    // 'third_grade_id' => 'required',

                    // Add other fields and validation rules as needed
                ]);

                // Get the EIN and relationship from the request
                $ein = $request->input('ein');
                // dd( $ein);

                // Find the ProformaModel instance by EIN
                $proforma = ProformaModel::where('ein', $ein)->first();

                // Check if the ProformaModel instance exists
                if (!$proforma) {
                    return response()->json(['error' => 'Proforma not found'], 404);
                }
           
                if($educations->edu_name== "Others"){
                ///PUTTING ALL DATA IN SESSION////////////
                session()->put([
                    'ein' => $ein, 'deceased_emp_name' => $proforma->deceased_emp_name, 'ministry' => $proforma->ministry, 'dept_name' => $proforma->dept_name,
                    'desig_name' => $proforma->desig_name, 'grade_name' => $proforma->grade_name, 'deceased_doa' => $proforma->deceased_doa,
                    'deceased_doe' => $proforma->deceased_doe, 'deceased_dob' => $proforma->deceased_dob, 'deceased_causeofdeath' => $proforma->deceased_causeofdeath,
                    'caste_id' => $proforma->caste_id, 'sex' => $proforma->sex, 'appl_date' => $proforma->appl_date, 'applicant_name' => $proforma->applicant_name,
                    'relationship' => $proforma->relationship, 'applicant_dob' => $proforma->applicant_dob, 'applicant_edu_id' => $proforma->applicant_edu_id,
                    'applicant_mobile' => $proforma->applicant_mobile, 'applicant_email_id' => $proforma->applicant_email_id, 'emp_addr_lcality' => $proforma->emp_addr_lcality,
                    'emp_addr_subdiv' => $proforma->emp_addr_subdiv, 'emp_addr_district' => $proforma->emp_addr_district, 'emp_state' => $proforma->emp_state,
                    'emp_pincode' => $proforma->emp_pincode, 'emp_addr_lcality_ret' => $proforma->emp_addr_lcality_ret, 'emp_addr_subdiv_ret' => $proforma->emp_addr_subdiv_ret,
                    'emp_addr_district_ret' => $proforma->emp_addr_district_ret, 'emp_state_ret' => $proforma->emp_state_ret, 'emp_pincode_ret' => $proforma->emp_pincode_ret,
                    'applicant_desig_id' => $proforma->applicant_desig_id, 'applicant_grade' => $proforma->applicant_grade, 'expire_on_duty' => $proforma->expire_on_duty,
                    'physically_handicapped' => $proforma->physically_handicapped, 'uploaded_id' => $proforma->uploaded_id, 'uploader_role_id' => $proforma->uploader_role_id,
                    'dept_id' => $proforma->dept_id, 'ministry_id' => $proforma->ministry_id, 'file_status' => $proforma->file_status, 'rejected_status' => $proforma->rejected_status,
                    'upload_status' => $proforma->upload_status, 'family_details_status' => $proforma->family_details_status, 'status' => $proforma->status,
                    'change_status' => $proforma->change_status, 'form_status' => $proforma->form_status,
                    'second_post_id' => $proforma->second_post_id,
                    'second_grade_id' => $proforma->second_grade_id,
                    'dept_id_option' => $proforma->dept_id_option,
                    'third_post_id' => $proforma->third_post_id,
                    'third_grade_id' => $proforma->third_grade_id,
                    'other_qualification' =>  $proforma->other_qualification,
                ]);
                ////END OF SESSION

                // Update the relationship and other columns in the ProformaModel instance
                $proforma->update([
                    'relationship' => $validatedData['relationship'],
                    'expire_on_duty' => $validatedData['expire_on_duty'],
                    'deceased_doe' => $validatedData['deceased_doe'],
                    'deceased_causeofdeath' => $validatedData['deceased_causeofdeath'],
                    'applicant_name' => $validatedData['applicant_name'],
                    // 'appl_date' => $validatedData['appl_date'],
                    'applicant_dob' => $validatedData['applicant_dob'],
                    'applicant_mobile' => $validatedData['applicant_mobile'],
                    'applicant_edu_id' => $validatedData['applicant_edu_id'],
                    'physically_handicapped' => $validatedData['physically_handicapped'],
                    'applicant_email_id' => $validatedData['applicant_email_id'],
                    'caste_id' => $validatedData['caste_id'],
                    'sex' =>  $validatedData['sex'],
                    'emp_addr_lcality' => $validatedData['emp_addr_lcality'],
                    'emp_addr_district' => $validatedData['emp_addr_district'],
                    'emp_state' => $validatedData['emp_state'],
                    'emp_pincode' => $validatedData['emp_pincode'],
                    'emp_addr_lcality_ret' => $validatedData['emp_addr_lcality_ret'],
                    'emp_addr_district_ret' => $validatedData['emp_addr_district_ret'],
                    'emp_state_ret' => $validatedData['emp_state_ret'],
                    'emp_pincode_ret' => $validatedData['emp_pincode_ret'],
                    'applicant_desig_id' => $validatedData['applicant_desig_id'],
                    'applicant_grade' => $validatedData['applicant_grade'],
                    'other_qualification' =>  $validatedData['other_qualification'],
                    'second_post_id' => 0,
                    'second_grade_id' => "NA",
                    'dept_id_option' => 0,
                    'third_post_id' => 0,
                    'third_grade_id' => "NA",

                    'rejected_status' => 0

                    // Add other columns as needed
                ]);
                //dd($request->all());
                // Return a response, for example, a success message
                return response()->json(['message' => 'Data updated successfully ']);
                //return back()->with('status', "7 Data save successfully....");
            }else{
                session()->put([
                    'ein' => $ein, 'deceased_emp_name' => $proforma->deceased_emp_name, 'ministry' => $proforma->ministry, 'dept_name' => $proforma->dept_name,
                    'desig_name' => $proforma->desig_name, 'grade_name' => $proforma->grade_name, 'deceased_doa' => $proforma->deceased_doa,
                    'deceased_doe' => $proforma->deceased_doe, 'deceased_dob' => $proforma->deceased_dob, 'deceased_causeofdeath' => $proforma->deceased_causeofdeath,
                    'caste_id' => $proforma->caste_id, 'sex' => $proforma->sex, 'appl_date' => $proforma->appl_date, 'applicant_name' => $proforma->applicant_name,
                    'relationship' => $proforma->relationship, 'applicant_dob' => $proforma->applicant_dob, 'applicant_edu_id' => $proforma->applicant_edu_id,
                    'applicant_mobile' => $proforma->applicant_mobile, 'applicant_email_id' => $proforma->applicant_email_id, 'emp_addr_lcality' => $proforma->emp_addr_lcality,
                    'emp_addr_subdiv' => $proforma->emp_addr_subdiv, 'emp_addr_district' => $proforma->emp_addr_district, 'emp_state' => $proforma->emp_state,
                    'emp_pincode' => $proforma->emp_pincode, 'emp_addr_lcality_ret' => $proforma->emp_addr_lcality_ret, 'emp_addr_subdiv_ret' => $proforma->emp_addr_subdiv_ret,
                    'emp_addr_district_ret' => $proforma->emp_addr_district_ret, 'emp_state_ret' => $proforma->emp_state_ret, 'emp_pincode_ret' => $proforma->emp_pincode_ret,
                    'applicant_desig_id' => $proforma->applicant_desig_id, 'applicant_grade' => $proforma->applicant_grade, 'expire_on_duty' => $proforma->expire_on_duty,
                    'physically_handicapped' => $proforma->physically_handicapped, 'uploaded_id' => $proforma->uploaded_id, 'uploader_role_id' => $proforma->uploader_role_id,
                    'dept_id' => $proforma->dept_id, 'ministry_id' => $proforma->ministry_id, 'file_status' => $proforma->file_status, 'rejected_status' => $proforma->rejected_status,
                    'upload_status' => $proforma->upload_status, 'family_details_status' => $proforma->family_details_status, 'status' => $proforma->status,
                    'change_status' => $proforma->change_status, 'form_status' => $proforma->form_status,
                    'second_post_id' => $proforma->second_post_id,
                    'second_grade_id' => $proforma->second_grade_id,
                    'dept_id_option' => $proforma->dept_id_option,
                    'third_post_id' => $proforma->third_post_id,
                    'third_grade_id' => $proforma->third_grade_id,
                  //  'other_qualification' =>  $proforma->other_qualification,
                ]);
                ////END OF SESSION

                // Update the relationship and other columns in the ProformaModel instance
                $proforma->update([
                    'relationship' => $validatedData['relationship'],
                    'expire_on_duty' => $validatedData['expire_on_duty'],
                    'deceased_doe' => $validatedData['deceased_doe'],
                    'deceased_causeofdeath' => $validatedData['deceased_causeofdeath'],
                    'applicant_name' => $validatedData['applicant_name'],
                    // 'appl_date' => $validatedData['appl_date'],
                    'applicant_dob' => $validatedData['applicant_dob'],
                    'applicant_mobile' => $validatedData['applicant_mobile'],
                    'applicant_edu_id' => $validatedData['applicant_edu_id'],
                    'physically_handicapped' => $validatedData['physically_handicapped'],
                    'applicant_email_id' => $validatedData['applicant_email_id'],
                    'caste_id' => $validatedData['caste_id'],
                    'sex' =>  $validatedData['sex'],
                    'emp_addr_lcality' => $validatedData['emp_addr_lcality'],
                    'emp_addr_district' => $validatedData['emp_addr_district'],
                    'emp_state' => $validatedData['emp_state'],
                    'emp_pincode' => $validatedData['emp_pincode'],
                    'emp_addr_lcality_ret' => $validatedData['emp_addr_lcality_ret'],
                    'emp_addr_district_ret' => $validatedData['emp_addr_district_ret'],
                    'emp_state_ret' => $validatedData['emp_state_ret'],
                    'emp_pincode_ret' => $validatedData['emp_pincode_ret'],
                    'applicant_desig_id' => $validatedData['applicant_desig_id'],
                    'applicant_grade' => $validatedData['applicant_grade'],
                    
                    'other_qualification' =>  "",
                    'second_post_id' => 0,
                    'second_grade_id' => "NA",
                    'dept_id_option' => 0,
                    'third_post_id' => 0,
                    'third_grade_id' => "NA",

                    'rejected_status' => 0

                    // Add other columns as needed
                ]);
                //dd($request->all());
                // Return a response, for example, a success message
                return response()->json(['message' => 'Data updated successfully ']);
                //return back()->with('status', "8 Data save successfully....");
            }
        }
        }
    }

    /////////////////////////////EDIT PROFORMA///////////////////////////////////////////

    public function discardChanges(Request $request)
    {
        // Validate the incoming request
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        if ($getUser->role_id == 1) {

            $ein = session('ein');
            // dd( $ein);

            // Find the ProformaModel instance by EIN
            $proforma = ProformaModel::where('ein', $ein)->first();

            // Check if the ProformaModel instance exists
            if (!$proforma) {
                return response()->json(['error' => 'Proforma not found'], 404);
            }

            //Get DATA from SESSION////////////


            $deceased_emp_name = session('deceased_emp_name');
            $ministry = session('ministry');
            $dept_name = session('dept_name');
            $desigName = session('desig_name');
            $gradeName = session('grade_name');
            $deceasedDoa = session('deceased_doa');
            $deceasedDoe = session('deceased_doe');
            $deceasedDob = session('deceased_dob');
            $deceasedCauseOfDeath = session('deceased_causeofdeath');
            $casteId = session('caste_id');
            $sex = session('sex');
            $applDate = session('appl_date');
            $applicantName = session('applicant_name');
            $relationship = session('relationship');
            $applicantDob = session('applicant_dob');
            $applicantEduId = session('applicant_edu_id');
            $applicantMobile = session('applicant_mobile');
            $applicantEmailId = session('applicant_email_id');
            $empAddrLocality = session('emp_addr_lcality');

            $empAddrSubdiv = session('emp_addr_subdiv');
            $empAddrDistrict = session('emp_addr_district');
            $empState = session('emp_state');
            $empPincode = session('emp_pincode');
            $empAddrLocalityRet = session('emp_addr_lcality_ret');
            $empAddrSubdivRet = session('emp_addr_subdiv_ret');
            $empAddrDistrictRet = session('emp_addr_district_ret');
            $empStateRet = session('emp_state_ret');
            $empPincodeRet = session('emp_pincode_ret');
            $applicantDesigId = session('applicant_desig_id');
            $applicantGradeId = session('applicant_grade');
            $expireOnDuty = session('expire_on_duty');
            $physicallyHandicapped = session('physically_handicapped');
            $uploadedId = session('uploaded_id');
            $uploaderRoleId = session('uploader_role_id');
            $deptId = session('dept_id');
            $ministryId = session('ministry_id');
            $fileStatus = session('file_status');
            $rejectedStatus = session('rejected_status');
            $uploadStatus = session('upload_status');
            $familyDetailsStatus = session('family_details_status');
            $status = session('status');
            $changeStatus = session('change_status');
            $formStatus = session('form_status');

            $second_post_id = session('second_post_id');
            $dept_id_option = session('dept_id_option');
            $second_grade_id = session('second_grade_id');
            $third_post_id = session('third_post_id');
            $third_grade_id = session('third_grade_id');


            ////END OF SESSION

            // Update the relationship and other columns in the ProformaModel instance
            $proforma->update([

                // 'ein' =>$ein,//I add this because of error Payload is not invalid
                'relationship' => $relationship,
                'expire_on_duty' => $expireOnDuty,
                'deceased_doe' => $deceasedDoe,
                'deceased_causeofdeath' => $deceasedCauseOfDeath,
                'applicant_name' => $applicantName,
                'appl_date' => $applDate,
                'applicant_dob' => $applicantDob,
                'applicant_mobile' => $applicantMobile,
                'applicant_edu_id' => $applicantEduId,
                'physically_handicapped' => $physicallyHandicapped,
                'applicant_email_id' => $applicantEmailId,
                'caste_id' => $casteId,
                'sex' => $sex,
                'emp_addr_lcality' => $empAddrLocality,

                'emp_addr_district' => $empAddrDistrict,
                'emp_state' => $empState,
                'emp_pincode' => $empPincode,
                'emp_addr_lcality_ret' => $empAddrLocalityRet,
                'emp_addr_subdiv' => $empAddrSubdiv,
                'emp_addr_subdiv_ret' => $empAddrSubdivRet,
                'emp_addr_district_ret' => $empAddrDistrictRet,
                'emp_state_ret' => $empStateRet,
                'emp_pincode_ret' => $empPincodeRet,
                'applicant_desig_id' => $applicantDesigId,
                'applicant_grade' => $applicantGradeId,
                'rejected_status' => 0,

                'second_post_id' => $second_post_id,
                'second_grade_id' => $second_grade_id,
                'dept_id_option' => $dept_id_option,
                'third_post_id' => $third_post_id,
                'third_grade_id' => $third_grade_id,

                // Add other columns as needed
            ]);
            //dd($request->all());
            // Return a response, for example, a success message
            return response()->json(['message' => 'Discarded Data Changes successfully.....! ']);
        } else {




            // Get the EIN and relationship from the request
            $ein = session('ein');
            // dd( $ein);

            // Find the ProformaModel instance by EIN
            $proforma = ProformaModel::where('ein', $ein)->first();

            // Check if the ProformaModel instance exists
            if (!$proforma) {
                return response()->json(['error' => 'Proforma not found'], 404);
            }


            //Get DATA from SESSION////////////


            $deceasedEmpName = session('deceased_emp_name');
            $ministry = session('ministry');
            $deptName = session('dept_name');
            $desigName = session('desig_name');
            $gradeName = session('grade_name');
            $deceasedDoa = session('deceased_doa');
            $deceasedDoe = session('deceased_doe');
            $deceasedDob = session('deceased_dob');
            $deceasedCauseOfDeath = session('deceased_causeofdeath');
            $casteId = session('caste_id');
            $sex = session('sex');
            $applDate = session('appl_date');
            $applicantName = session('applicant_name');
            $relationship = session('relationship');
            $applicantDob = session('applicant_dob');
            $applicantEduId = session('applicant_edu_id');
            $applicantMobile = session('applicant_mobile');
            $applicantEmailId = session('applicant_email_id');
            $empAddrLocality = session('emp_addr_lcality');

            $empAddrSubdiv = session('emp_addr_subdiv');
            $empAddrDistrict = session('emp_addr_district');
            $empState = session('emp_state');
            $empPincode = session('emp_pincode');
            $empAddrLocalityRet = session('emp_addr_lcality_ret');
            $empAddrSubdivRet = session('emp_addr_subdiv_ret');
            $empAddrDistrictRet = session('emp_addr_district_ret');
            $empStateRet = session('emp_state_ret');
            $empPincodeRet = session('emp_pincode_ret');
            $applicantDesigId = session('applicant_desig_id');
            $applicantGradeId = session('applicant_grade');
            $expireOnDuty = session('expire_on_duty');
            $physicallyHandicapped = session('physically_handicapped');
            $uploadedId = session('uploaded_id');
            $uploaderRoleId = session('uploader_role_id');
            $deptId = session('dept_id');
            $ministryId = session('ministry_id');
            $fileStatus = session('file_status');
            $rejectedStatus = session('rejected_status');
            $uploadStatus = session('upload_status');
            $familyDetailsStatus = session('family_details_status');
            $status = session('status');
            $changeStatus = session('change_status');
            $formStatus = session('form_status');

            $second_post_id = session('second_post_id');
            $dept_id_option = session('dept_id_option');
            $second_grade_id = session('second_grade_id');
            $third_post_id = session('third_post_id');
            $third_grade_id = session('third_grade_id');


            ////END OF SESSION


            // Update the relationship and other columns in the ProformaModel instance
            $proforma->update([


                // 'ein' =>$ein,
                'relationship' => $relationship,
                'expire_on_duty' => $expireOnDuty,
                'deceased_doe' => $deceasedDoe,
                'deceased_causeofdeath' => $deceasedCauseOfDeath,
                'applicant_name' => $applicantName,
                'appl_date' => $applDate,
                'applicant_dob' => $applicantDob,
                'applicant_mobile' => $applicantMobile,
                'applicant_edu_id' => $applicantEduId,
                'physically_handicapped' => $physicallyHandicapped,
                'applicant_email_id' => $applicantEmailId,
                'caste_id' => $casteId,
                'sex' => $sex,
                'emp_addr_lcality' => $empAddrLocality,
                'emp_addr_district' => $empAddrDistrict,
                'emp_addr_subdiv' => $empAddrSubdiv,
                'emp_addr_subdiv_ret' => $empAddrSubdivRet,
                'emp_state' => $empState,
                'emp_pincode' => $empPincode,
                'emp_addr_lcality_ret' => $empAddrLocalityRet,
                'emp_addr_district_ret' => $empAddrDistrictRet,
                'emp_state_ret' => $empStateRet,
                'emp_pincode_ret' => $empPincodeRet,
                'applicant_desig_id' => $applicantDesigId,
                'applicant_grade' => $applicantGradeId,
                'rejected_status' => 0,

                'second_post_id' => $second_post_id,
                'second_grade_id' => $second_grade_id,
                'dept_id_option' => $dept_id_option,
                'third_post_id' => $third_post_id,
                'third_grade_id' => $third_grade_id,

                // Add other columns as needed
            ]);
            //dd($request->all());
            // Return a response, for example, a success message
            return response()->json(['message' => 'Discarded Data Changes successfully.....! ']);
        }
    }



    //////////////////////////DISCARD CHANGES IS ABOVE/////////////////////////////////

    public function deleteRecord($ein)
    {

        $record = ProformaModel::where('ein', $ein)->first();


        // If the record exists, delete it
        if ($record) {
            $record->delete();
            FamilyMembers::where('ein', $ein)->delete();
            FileModel::where('ein', $ein)->delete();
            EmpFormSubmissionStatus::where('ein', $ein)->delete();
            session()->forget(['ein', 'from_emp_ein']);
            $ein = null;
            return response()->json(['message' => 'Record deleted successfully']);
        } else {
            return response()->json(['error' => 'Record not found'], 404);
        }
    }
    //////////////////////////////////////SAVE DATA


    public function checkEin(Request $request)
    {
        $ein = $request->input('ein');

        // Check if EIN exists in the database
        $exists = ProformaModel::where('ein', $ein)->exists();

        if ($exists) {
            //session()->flash('message', 'EIN exists in the database.');
        } else {
            session()->forget('message');
        }

        return response()->json(['exists' => $exists]);
    }



    public function checkEmail(Request $request)
    {
        $applicant_email_id = $request->input('applicant_email_id');

        // Check if EIN exists in the database
        $exists = ProformaModel::where('applicant_email_id', $applicant_email_id)->exists();

        return response()->json(['exists' => $exists]);
    }

    //////////////////////////////////////
    ///////////////AJAX EDIT PROFORMA FORM///////
    public function updateself(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'expire_on_duty' => 'required',
            'deceased_doe' => 'required|string',
            'deceased_causeofdeath' => 'required|string',
            'applicant_name' => 'required|string',
            // 'appl_date' => 'required|string',
            'applicant_dob' => 'required',
            'relationship' => 'required', // This part of the rule checks the existence
            //  of the relationship field's value in the id column of the relationship table in the database.
            'applicant_mobile' => 'required|string',
            'applicant_edu_id' => 'required',
            'physically_handicapped' => 'required',
            'applicant_email_id' => 'required|string',
            'caste_id' => 'required',
            'sex' => 'required',
            'emp_addr_lcality' => 'required|string',
            'emp_addr_district' => 'required',
            'emp_state' => 'required',
            'emp_pincode' => 'required',
            'emp_addr_lcality_ret' => 'required|string',
            'emp_addr_district_ret' => 'required',
            'emp_state_ret' => 'required',
            'emp_pincode_ret' => 'required',
            'applicant_desig_id' => 'required',
            'applicant_grade' => 'required',

            'second_post_id' => 'required',
            'second_grade_id' => 'required',
            'dept_id_option' => 'required',
            'third_post_id' =>  'required',
            'third_grade_id' => 'required',            

        ]);
        $ein = $request->input('ein');
        // dd( $ein );

        // Find the ProformaModel instance by EIN
        $proforma = ProformaModel::where('ein', $ein)->first();

        // Check if the ProformaModel instance exists
        if (!$proforma) {
            return response()->json(['error' => 'Proforma not found'], 404);
        }

        // Update the relationship and other columns in the ProformaModel instance
        if ($proforma->second_post_id != 0) {
      
        // Get the EIN and relationship from the request
      
        $proforma->update([
            'relationship' => $validatedData['relationship'],
            'expire_on_duty' => $validatedData['expire_on_duty'],
            'deceased_doe' => $validatedData['deceased_doe'],
            'deceased_causeofdeath' => $validatedData['deceased_causeofdeath'],
            'applicant_name' => $validatedData['applicant_name'],
            // 'appl_date' => $validatedData[ 'appl_date' ],
            'applicant_dob' => $validatedData['applicant_dob'],
            'applicant_mobile' => $validatedData['applicant_mobile'],
            'applicant_edu_id' => $validatedData['applicant_edu_id'],
            'physically_handicapped' => $validatedData['physically_handicapped'],
            'applicant_email_id' => $validatedData['applicant_email_id'],
            'caste_id' => $validatedData['caste_id'],
            'sex' => $validatedData['sex'],
            'emp_addr_lcality' => $validatedData['emp_addr_lcality'],
            'emp_addr_district' => $validatedData['emp_addr_district'],
            'emp_state' => $validatedData['emp_state'],
            'emp_pincode' => $validatedData['emp_pincode'],
            'emp_addr_lcality_ret' => $validatedData['emp_addr_lcality_ret'],
            'emp_addr_district_ret' => $validatedData['emp_addr_district_ret'],
            'emp_state_ret' => $validatedData['emp_state_ret'],
            'emp_pincode_ret' => $validatedData['emp_pincode_ret'],
            'applicant_desig_id' => $validatedData['applicant_desig_id'],
            'applicant_grade' => $validatedData['applicant_grade'],
            'rejected_status' => 0,

            'second_post_id' =>  $validatedData['second_post_id'],
            'second_grade_id' =>  $validatedData['second_grade_id'],
            'dept_id_option' => $validatedData['dept_id_option'],
            'third_post_id' =>  $validatedData['third_post_id'],
            'third_grade_id' =>  $validatedData['third_grade_id'],

            // Add other columns as needed
        ]);
        //dd( $request->all() );
        // Return a response, for example, a success message
        return response()->json(['message' => 'Data updated successfully ']);
    }else{
        $proforma->update([
            'relationship' => $validatedData['relationship'],
            'expire_on_duty' => $validatedData['expire_on_duty'],
            'deceased_doe' => $validatedData['deceased_doe'],
            'deceased_causeofdeath' => $validatedData['deceased_causeofdeath'],
            'applicant_name' => $validatedData['applicant_name'],
            // 'appl_date' => $validatedData[ 'appl_date' ],
            'applicant_dob' => $validatedData['applicant_dob'],
            'applicant_mobile' => $validatedData['applicant_mobile'],
            'applicant_edu_id' => $validatedData['applicant_edu_id'],
            'physically_handicapped' => $validatedData['physically_handicapped'],
            'applicant_email_id' => $validatedData['applicant_email_id'],
            'caste_id' => $validatedData['caste_id'],
            'sex' => $validatedData['sex'],
            'emp_addr_lcality' => $validatedData['emp_addr_lcality'],
            'emp_addr_district' => $validatedData['emp_addr_district'],
            'emp_state' => $validatedData['emp_state'],
            'emp_pincode' => $validatedData['emp_pincode'],
            'emp_addr_lcality_ret' => $validatedData['emp_addr_lcality_ret'],
            'emp_addr_district_ret' => $validatedData['emp_addr_district_ret'],
            'emp_state_ret' => $validatedData['emp_state_ret'],
            'emp_pincode_ret' => $validatedData['emp_pincode_ret'],
            'applicant_desig_id' => $validatedData['applicant_desig_id'],
            'applicant_grade' => $validatedData['applicant_grade'],
            'rejected_status' => 0,

            'second_post_id' =>  0,
            'second_grade_id' =>  "NA",
            'dept_id_option' => 0,
            'third_post_id' => 0,
            'third_grade_id' =>  "NA",

            // Add other columns as needed
        ]);
        //dd( $request->all() );
        // Return a response, for example, a success message
        return response()->json(['message' => 'Data updated successfully ']);
    }

    }


    public function deleteRecordself($ein)
    {

        $record = ProformaModel::where('ein', $ein)->first();

        // If the record exists, delete it
        if ($record) {
            $record->delete();
            FamilyMembers::where('ein', $ein)->delete();
            FileModel::where('ein', $ein)->delete();
            EmpFormSubmissionStatus::where('ein', $ein)->delete();
            return response()->json(['message' => 'Record deleted successfully']);
        } else {
            return response()->json(['error' => 'Record not found'], 404);
        }
    }

    public function delete2ndAppl($ein)
    {
        // Check if the record exists in ProformaHistoryModel
        $proformaHistoryRecord = ProformaHistoryModel::where('ein', $ein)->first();
        $proformaRecord = ProformaModel::get()->where('ein', $ein)->first();
        // dd( $proformaRecord );

        if ($proformaHistoryRecord) {
            // If the record exists in ProformaHistoryModel, update the corresponding record in ProformaModel( After save )

            if ($proformaRecord) {
                //  dd( $proformaRecord );
                // Update the fields in ProformaModel with the values from ProformaHistoryModel
                $proformaRecord->update([
                    'ein' => $proformaHistoryRecord->ein,

                    'deceased_emp_name' => $proformaHistoryRecord->deceased_emp_name,
                    'ministry' => $proformaHistoryRecord->ministry,
                    'dept_name' => $proformaHistoryRecord->dept_name,
                    'desig_name' => $proformaHistoryRecord->desig_name,
                    'grade_name' => $proformaHistoryRecord->grade_name,
                    'deceased_doa' => $proformaHistoryRecord->deceased_doa,
                    'deceased_doe' => $proformaHistoryRecord->deceased_doe,
                    'deceased_dob' => $proformaHistoryRecord->deceased_dob,
                    'deceased_causeofdeath' => $proformaHistoryRecord->deceased_causeofdeath,
                    'sex' => $proformaHistoryRecord->sex,
                    'caste_id' => $proformaHistoryRecord->caste_id,

                    'appl_number' => $proformaHistoryRecord->appl_number,
                    'appl_date' => $proformaHistoryRecord->appl_date,
                    'applicant_name' => $proformaHistoryRecord->applicant_name,
                    'relationship' => $proformaHistoryRecord->relationship,
                    'applicant_dob' => $proformaHistoryRecord->applicant_dob,
                    'applicant_edu_id' => $proformaHistoryRecord->applicant_edu_id,
                    'applicant_mobile' => $proformaHistoryRecord->applicant_mobile,

                    'applicant_email_id' => $proformaHistoryRecord->applicant_email_id,

                    'emp_addr_lcality' => $proformaHistoryRecord->emp_addr_lcality,
                    'emp_addr_subdiv' => $proformaHistoryRecord->emp_addr_subdiv,
                    'emp_addr_district' => $proformaHistoryRecord->emp_addr_district,

                    'emp_state' => $proformaHistoryRecord->emp_state,
                    'emp_pincode' => $proformaHistoryRecord->emp_pincode,

                    'emp_addr_lcality_ret' => $proformaHistoryRecord->emp_addr_lcality_ret,
                    'emp_addr_subdiv_ret' => $proformaHistoryRecord->emp_addr_subdiv_ret,

                    'emp_addr_district_ret' => $proformaHistoryRecord->emp_addr_district_ret,

                    'emp_state_ret' => $proformaHistoryRecord->emp_state_ret,
                    'emp_pincode_ret' => $proformaHistoryRecord->emp_pincode_ret,

                    'applicant_desig_id' => $proformaHistoryRecord->applicant_desig_id,
                    'applicant_grade' => $proformaHistoryRecord->applicant_grade,

                    'expire_on_duty' => $proformaHistoryRecord->expire_on_duty,
                    // 'accept_transfer' => $request->input( 'accept_transfer' ),
                    'physically_handicapped' => $proformaHistoryRecord->physically_handicapped,
                    'uploaded_id' => $proformaHistoryRecord->uploaded_id,
                    'uploader_role_id' => $proformaHistoryRecord->role_id,

                    'dept_id' => $proformaHistoryRecord->dept_id,
                    'ministry_id' => $proformaHistoryRecord->ministry_id,
                    'rejected_status' => 0,
                    'change_status' => 0,
                    'file_status' => $proformaHistoryRecord->file_status,
                    'form_status' => $proformaHistoryRecord->form_status,
                    'status' => $proformaHistoryRecord->status,
                    'upload_status' => $proformaHistoryRecord->upload_status,
                    'family_details_status' => $proformaHistoryRecord->family_details_status,

                    'second_post_id' =>  $proformaHistoryRecord->second_post_id,
                    'second_grade_id' =>  $proformaHistoryRecord->second_grade_id,
                    'dept_id_option' => $proformaHistoryRecord->dept_id_option,
                    'third_post_id' =>  $proformaHistoryRecord->third_post_id,
                    'third_grade_id' =>  $proformaHistoryRecord->third_grade_id,

                ]);

                $fileHistoryRecord = FileHistoryModel::get()->where('ein', $ein)->toArray();
                $fileRecord = FileModel::get()->where('ein', $ein)->toArray();

                if (count($fileHistoryRecord) > 0) {
                    // If the record exists in FileHistoryModel, update the corresponding record in ProformaModel( After save )
                    //  $fileRecord = FileModel::where( 'ein', $ein )->get();
                    //dd( $fileRecord );
                    // Update the fields in FileModel with the values from FileHistoryModel
                    if ($fileRecord) {
                        foreach ($fileHistoryRecord as $fileHistory) {
                            $fileRecord->update([
                                'ein' => $fileHistory['ein'],
                                'appl_number' => $fileHistory['appl_number'],
                                'uploaded_by' => $fileHistory['uploaded_by'],
                                'file_name' => $fileHistory['file_name'],
                                'file_path' => $fileHistory['file_path'],
                                'doc_id' => $fileHistory['doc_id'],

                            ]);
                        }
                    } else {
                        // If the record doesn't exist in FileModel, create a new one(before save)
                        foreach ($fileHistoryRecord as $fileHistory) {
                            FileModel::create([
                                'ein' => $fileHistory['ein'],
                                'appl_number' => $fileHistory['appl_number'],
                                'uploaded_by' => $fileHistory['uploaded_by'],
                                'file_name' => $fileHistory['file_name'],
                                'file_path' => $fileHistory['file_path'],
                                'doc_id' => $fileHistory['doc_id'],
                            ]);
                        }

                        // Delete the record from ProformaHistoryModel           
                        // return response()->json(['message' => 'Discarded successfully']);
                    }
                }


                $empFormSubmissionStatusHistoryRecord = EmpFormSubmissionStatusHistoryModel::get()->where('ein', $ein)->toArray();
                $empFormSubmissionStatus = EmpFormSubmissionStatus::where('ein', $ein)->get()->toArray();
                if (count($empFormSubmissionStatus) > 0) {
                    // If the record exists in EmpFormSubmissionHistoryModel, update the corresponding record in ProformaModel(After save)
                    // $empFormSubmissionStatus = EmpFormSubmissionStatus::where('ein', $ein)->get();
                    // if ($empFormSubmissionStatus) {
                    // Update the fields in EmpFormSubmissionStatus with the values from EmpFormSubmissionHistoryModel
                    foreach ($empFormSubmissionStatusHistoryRecord as $empForm) {
                        $empFormSubmissionStatus->update([
                            'ein' => $empForm['ein'],
                            'form_id' => $empForm['form_id'],
                            'form_desc' => $empForm['form_desc'],
                            'submit_status' => $empForm['submit_status'],
                            'client_ip' => $empForm['client_ip'],



                        ]);
                    }
                } else {
                    // If the record doesn't exist in EmpFormSubmissionStatus, create a new one( before save )
                    foreach ($empFormSubmissionStatusHistoryRecord as $empForm) {
                        EmpFormSubmissionStatus::create([
                            'ein' => $empForm['ein'],
                            'form_id' => $empForm['form_id'],
                            'form_desc' => $empForm['form_desc'],
                            'submit_status' => $empForm['submit_status'],
                            'client_ip' => $empForm['client_ip'],

                        ]);
                    }
                    // }

                    // Delete the record from ProformaHistoryModel

                }
                ProformaHistoryModel::where('ein', $ein)->delete();
                FileHistoryModel::where('ein', $ein)->delete();
                EmpFormSubmissionStatusHistoryModel::where('ein', $ein)->delete();
                session()->forget(['ein', 'from_emp_ein']);
                $ein = null;

                return response()->json(['message' => 'Discarded successfully']);
            } else {
                // If the record doesn't exist in ProformaModel, create a new one(before save)
                ProformaModel::create([
                    'ein' => $proformaHistoryRecord->ein,

                    'deceased_emp_name' => $proformaHistoryRecord->deceased_emp_name,
                    'ministry' => $proformaHistoryRecord->ministry,
                    'dept_name' => $proformaHistoryRecord->dept_name,
                    'desig_name' => $proformaHistoryRecord->desig_name,
                    'grade_name' => $proformaHistoryRecord->grade_name,
                    'deceased_doa' => $proformaHistoryRecord->deceased_doa,
                    'deceased_doe' => $proformaHistoryRecord->deceased_doe,
                    'deceased_dob' => $proformaHistoryRecord->deceased_dob,
                    'deceased_causeofdeath' => $proformaHistoryRecord->deceased_causeofdeath,
                    'sex' => $proformaHistoryRecord->sex,
                    'caste_id' => $proformaHistoryRecord->caste_id,

                    'appl_number' => $proformaHistoryRecord->appl_number,
                    'appl_date' => $proformaHistoryRecord->appl_date,
                    'applicant_name' => $proformaHistoryRecord->applicant_name,
                    'relationship' => $proformaHistoryRecord->relationship,
                    'applicant_dob' => $proformaHistoryRecord->applicant_dob,
                    'applicant_edu_id' => $proformaHistoryRecord->applicant_edu_id,
                    'applicant_mobile' => $proformaHistoryRecord->applicant_mobile,



                    'applicant_email_id' => $proformaHistoryRecord->applicant_email_id,

                    'emp_addr_lcality' => $proformaHistoryRecord->emp_addr_lcality,
                    'emp_addr_subdiv' => $proformaHistoryRecord->emp_addr_subdiv,
                    'emp_addr_district' => $proformaHistoryRecord->emp_addr_district,

                    'emp_state' => $proformaHistoryRecord->emp_state,
                    'emp_pincode' => $proformaHistoryRecord->emp_pincode,

                    'emp_addr_lcality_ret' => $proformaHistoryRecord->emp_addr_lcality_ret,
                    'emp_addr_subdiv_ret' => $proformaHistoryRecord->emp_addr_subdiv_ret,

                    'emp_addr_district_ret' => $proformaHistoryRecord->emp_addr_district_ret,



                    'emp_state_ret' => $proformaHistoryRecord->emp_state_ret,
                    'emp_pincode_ret' => $proformaHistoryRecord->emp_pincode_ret,

                    'applicant_desig_id' => $proformaHistoryRecord->applicant_desig_id,
                    'applicant_grade' => $proformaHistoryRecord->applicant_grade,

                    'expire_on_duty' => $proformaHistoryRecord->expire_on_duty,
                    // 'accept_transfer' => $request->input('accept_transfer'),
                    'physically_handicapped' => $proformaHistoryRecord->physically_handicapped,
                    'uploaded_id' => $proformaHistoryRecord->uploaded_id,
                    'uploader_role_id' => $proformaHistoryRecord->role_id,

                    'dept_id' => $proformaHistoryRecord->dept_id,
                    'ministry_id' => $proformaHistoryRecord->ministry_id,
                    'rejected_status' => 0,
                    'file_status' => $proformaHistoryRecord->file_status,
                    'form_status' => $proformaHistoryRecord->form_status,
                    'status' => $proformaHistoryRecord->status,
                    'upload_status' => $proformaHistoryRecord->upload_status,
                    'change_status' => 0,
                    'family_details_status' => $proformaHistoryRecord->family_details_status,

                    'second_post_id' =>  $proformaHistoryRecord->second_post_id,
                    'second_grade_id' =>  $proformaHistoryRecord->second_grade_id,
                    'dept_id_option' => $proformaHistoryRecord->dept_id_option,
                    'third_post_id' =>  $proformaHistoryRecord->third_post_id,
                    'third_grade_id' =>  $proformaHistoryRecord->third_grade_id,
                ]);

                $fileHistoryRecord = FileHistoryModel::get()->where('ein', $ein)->toArray();
                $fileRecord = FileModel::get()->where('ein', $ein)->toArray();

                //$fileHistorydata = FileHistoryModel::where('ein', $ein)->get();
                //dd( $fileHistoryRecord );
                if (count($fileRecord) > 0) {
                    // If the record exists in FileHistoryModel, update the corresponding record in ProformaModel(After save)
                    //$fileRecord = FileModel::get()->where('ein', $ein)->first();

                    // dd( $fileRecord, "skjlsdklfjdk" );

                    // if ($fileRecord) {
                    foreach ($fileHistoryRecord as $fileHistory) {
                        // Update the fields in FileModel with the values from FileHistoryModel
                        $fileRecord->update([
                            'ein' => $fileHistory['ein'],
                            'appl_number' => $fileHistory['appl_number'],
                            'uploaded_by' => $fileHistory['uploaded_by'],
                            'file_name' => $fileHistory['file_name'],
                            'file_path' => $fileHistory['file_path'],
                            'doc_id' => $fileHistory['doc_id'],


                        ]);
                    }
                } else {
                    // If the record doesn't exist in FileModel, create a new one( before save )
                    foreach ($fileHistoryRecord as $fileHistory) {
                        FileModel::create([
                            'ein' => $fileHistory['ein'],
                            'appl_number' => $fileHistory['appl_number'],
                            'uploaded_by' => $fileHistory['uploaded_by'],
                            'file_name' => $fileHistory['file_name'],
                            'file_path' => $fileHistory['file_path'],
                            'doc_id' => $fileHistory['doc_id'],
                        ]);
                    }
                    // dd( $test, 'skjlsdklfjdk' );
                }

                // Delete the record from ProformaHistoryModel

                // return response()->json( [ 'message' => 'Discarded successfully' ] );

                $empFormSubmissionStatusHistoryRecord = EmpFormSubmissionStatusHistoryModel::get()->where('ein', $ein)->toArray();
                $empFormSubmissionStatus = EmpFormSubmissionStatus::where('ein', $ein)->get()->toArray();

                if (count($empFormSubmissionStatus) > 0) {
                    // If the record exists in EmpFormSubmissionHistoryModel, update the corresponding record in ProformaModel( After save )
                    //$empFormSubmissionStatus = EmpFormSubmissionStatus::where( 'ein', $ein )->get()->first();
                    //if ( $empFormSubmissionStatus ) {
                    // Update the fields in EmpFormSubmissionStatus with the values from EmpFormSubmissionHistoryModel
                    foreach ($empFormSubmissionStatusHistoryRecord as $empForm) {
                        $empFormSubmissionStatus->update([
                            'ein' => $empForm['ein'],
                            'form_id' => $empForm['form_id'],
                            'form_desc' => $empForm['form_desc'],
                            'submit_status' => $empForm['submit_status'],
                            'client_ip' => $empForm['client_ip'],

                        ]);
                    }
                } else {
                    // If the record doesn't exist in EmpFormSubmissionStatus, create a new one(before save)
                    foreach ($empFormSubmissionStatusHistoryRecord as $empForm) {
                        EmpFormSubmissionStatus::create([
                            'ein' => $empForm['ein'],
                            'form_id' => $empForm['form_id'],
                            'form_desc' => $empForm['form_desc'],
                            'submit_status' => $empForm['submit_status'],
                            'client_ip' => $empForm['client_ip'],

                        ]);
                    }
                    //}

                    // Delete the record from ProformaHistoryModel


                    // return response()->json(['message' => 'Discarded successfully']);
                }
                ProformaHistoryModel::where('ein', $ein)->delete();

                FileHistoryModel::where('ein', $ein)->delete();


                EmpFormSubmissionStatusHistoryModel::where('ein', $ein)->delete();
                session()->forget(['ein', 'from_emp_ein']);
                $ein = null;

                return response()->json(['message' => 'Discarded successfully']);
            }
        } else {

            return response()->json(['error' => 'No record found']);
        }

        // Delete the record from ProformaHistoryModel



        // Handle the case where the record doesn't exist in ProformaHistoryModel
        return response()->json(['message' => 'Record not found']);
    }

    public function viewSeniorityStatus(Request $request)
    {
        // Change for DIHAS below
        $request->session()->forget(['ein', 'ein']);

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        if ($getUser->role_id == 1) {
            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->toArray();
            // $Appl_List = count( $empListArray );
            //dd( $Appl_List );
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe, appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->paginate(15);
            $Remarks = RemarksModel::get()->toArray();
            //expire_on_duty if yes top priority

            //dd( $empList->toArray() );
            $stat = '';

            foreach ($empList as $data) {
                // $getUser1 = User::get()->where( 'id', $data->forwarded_by )->first();

                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Verified';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }
                if ($data->status == 7) {
                    $stat = 'signed';
                    $data->status = 'Signed by DP';
                }
                if ($data->status == 8) {
                    $stat = 'transfer';
                    $data->status = 'Transferred';
                }

                $data->formSubStat = $stat;
            }

            return view('admin/viewSeniorityStatusBySelf', compact('empList', 'empListArray', 'Remarks', 'getUser'));
        }

        if ($getUser->role_id == 2 || $getUser->role_id == 3 || $getUser->role_id == 4 || $getUser->role_id == 9) {
            // $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->whereNotIn('status', [0])->toArray();
            // $Appl_List = count( $empListArray );
            //dd( $Appl_List );
            // $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe, appl_date, applicant_dob")->whereNotIn('status', [0])->where('dept_id', $getUser->dept_id)->paginate(15);
             $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('status', '!=', 0)->toArray();
             
           

             $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe, appl_date, applicant_dob")->where('status', '!=', 0)->where('dept_id', $getUser->dept_id)->paginate(15);
            $Remarks = RemarksModel::get()->toArray();
            //expire_on_duty if yes top priority

            //dd( $empList->toArray() );
            $stat = '';

            foreach ($empList as $data) {
                // $getUser1 = User::get()->where( 'id', $data->forwarded_by )->first();

                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Verified';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }
                if ($data->status == 7) {
                    $stat = 'signed';
                    $data->status = 'Signed by DP';
                }
                if ($data->status == 8) {
                    $stat = 'transfer';
                    $data->status = 'Transferred';
                }

                $data->formSubStat = $stat;
            }

            return view('admin/viewSeniorityStatusBySelf', compact('empList', 'empListArray', 'Remarks', 'getUser'));
        }

        if ($getUser->role_id == 5 || $getUser->role_id == 6 || $getUser->role_id == 8) {
            $request->session()->forget(['deptId']);
            $empListArray = ProformaModel::get()->toArray();
            // $Appl_List = count( $empListArray );
            //dd( $Appl_List );
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe, appl_date, applicant_dob")->where('status', '!=', 0)->paginate(15);
            $Remarks = RemarksModel::get()->toArray();
            //expire_on_duty if yes top priority

            //dd( $empList->toArray() );
            $stat = '';

            foreach ($empList as $data) {
                // $getUser1 = User::get()->where( 'id', $data->forwarded_by )->first();

                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Verified';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }
                if ($data->status == 7) {
                    $stat = 'signed';
                    $data->status = 'Signed by DP';
                }
                if ($data->status == 8) {
                    $stat = 'transfer';
                    $data->status = 'Transferred';
                }

                $data->formSubStat = $stat;
            }

            return view('admin/viewSeniorityStatusByDP', compact('deptListArray', 'empList', 'empListArray', 'Remarks', 'getUser'));
        }
    }

    public function viewSeniorityStatusSearch(Request $request)
    {

        $empListArray = null;
        $request->session()->forget(['ein', 'from_emp_ein']);
        // session()->forget( [ 'ein', 'from_emp_ein' ] );
        // $ein = null;

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');

        // $tempEmpList = null;

        if ($getUser->role_id == 1) {
            if ($request->searchItem != null || trim($request->searchItem) != '') {

                $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('ein', $request->searchItem)->toArray();
                // $Appl_List = count( $empListArray );
                // dd( $empListArray );
                $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe, appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('ein', $request->searchItem)->paginate(15);
                $Remarks = RemarksModel::get()->toArray();
            } else {
                $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->toArray();
                // $Appl_List = count( $empListArray );
                // dd( $empListArray );
                $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe, appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->paginate(15);
                $Remarks = RemarksModel::get()->toArray();
            }

            $stat = '';

            foreach ($empList as $data) {
                // $getUser1 = User::get()->where( 'id', $data->forwarded_by )->first();

                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Verified';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }
                if ($data->status == 7) {
                    $stat = 'signed';
                    $data->status = 'Signed by DP';
                }
                if ($data->status == 8) {
                    $stat = 'transfer';
                    $data->status = 'Transferred';
                }

                $data->formSubStat = $stat;
            }

            return view('admin/viewSeniorityStatusBySelf', compact('empList', 'empListArray', 'Remarks', 'getUser'));
        }

        if ($getUser->role_id == 2 || $getUser->role_id == 3 || $getUser->role_id == 4 || $getUser->role_id == 9) {
            if ($request->searchItem != null || trim($request->searchItem) != '') {

                $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('ein', $request->searchItem)->where('status', '!=', 0)->toArray();
                // $Appl_List = count( $empListArray );
                //dd( $Appl_List );
                $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe, appl_date, applicant_dob")->where('status', '!=', 0)->where('ein', $request->searchItem)->where('dept_id', $getUser->dept_id)->paginate(15);
                $Remarks = RemarksModel::get()->toArray();
            } else {
                $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('status', '!=', 0)->toArray();
                // $Appl_List = count( $empListArray );
                // dd( $empListArray );
                $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe, appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('status', '!=', 0)->paginate(15);
                $Remarks = RemarksModel::get()->toArray();
            }
            $stat = '';

            foreach ($empList as $data) {
                // $getUser1 = User::get()->where( 'id', $data->forwarded_by )->first();

                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Verified';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }
                if ($data->status == 7) {
                    $stat = 'signed';
                    $data->status = 'Signed by DP';
                }
                if ($data->status == 8) {
                    $stat = 'transfer';
                    $data->status = 'Transferred';
                }

                $data->formSubStat = $stat;
            }

            return view('admin/viewSeniorityStatusBySelf', compact('empList', 'empListArray', 'Remarks', 'getUser'));
        }

        // dd( $getUser->role_id );
        if ($getUser->role_id == 5 || $getUser->role_id == 6 || $getUser->role_id == 8) {
            if ($request->searchItem != null || trim($request->searchItem) != '') {

                $request->session()->forget(['deptId']);
                $empListArray = ProformaModel::get()->where('ein', $request->searchItem)->where('status', '!=', 0)->toArray();
                // $Appl_List = count( $empListArray );
                //dd( $Appl_List );
                $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe, appl_date, applicant_dob")->where('ein', $request->searchItem)->where('status', '!=', 0)->paginate(10);
                $Remarks = RemarksModel::get()->toArray();
            } else {
                $empListArray = ProformaModel::get()->where('status', '!=', 0)->toArray();
                // $Appl_List = count( $empListArray );
                // dd( $empListArray );
                $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe, appl_date, applicant_dob")->where('status', '!=', 0)->paginate(15);
                $Remarks = RemarksModel::get()->toArray();
            }
            $stat = '';

            foreach ($empList as $data) {
                // $getUser1 = User::get()->where( 'id', $data->forwarded_by )->first();

                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Verified';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }
                if ($data->status == 7) {
                    $stat = 'signed';
                    $data->status = 'Signed by DP';
                }
                if ($data->status == 8) {
                    $stat = 'transfer';
                    $data->status = 'Transferred';
                }

                $data->formSubStat = $stat;
            }

            return view('admin/viewSeniorityStatusByDP', compact('deptListArray', 'empList', 'empListArray', 'Remarks', 'getUser'));
        }
    }

    public function seniorityDeptSelect(Request $request)
    {
        // Change for DIHAS below
        $request->session()->forget(['ein', 'ein']);
        $deptId = $request->input('dept_id');
        session()->put('dept_id',$deptId);
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        if (session()->get('deptId') != '' && $request->input('page') == '') {
            $request->session()->forget(['deptId']);
        }
        if (strlen($deptId) > 0) {
            session()->put('deptId', $deptId);
        }

        if (strlen(session()->get('deptId')) > 0) {
            $deptId = session()->get('deptId');
            $empListArray = ProformaModel::get()->where('dept_id', $deptId)->where('status', '!=', 0)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe, appl_date, applicant_dob")->where('dept_id', $deptId)->where('status', '!=', 0)->paginate(15);
        } else {
            //$file_status = [ 5, 6 ];
            // $empListArray = ProformaModel::get()->whereIn( 'file_status', $file_status )->toArray();
            $empListArray = ProformaModel::get()->where('status', '!=', 0)->toArray();
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe, appl_date, applicant_dob")->where('status', '!=', 0)->paginate(15);
        }
        $Remarks = RemarksModel::get()->toArray();
        //expire_on_duty if yes top priority

        //dd( $empList->toArray() );
        $stat = '';

        foreach ($empList as $data) {
            // $getUser1 = User::get()->where( 'id', $data->forwarded_by )->first();

            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }
            if ($data->status == 8) {
                $stat = 'transfer';
                $data->status = 'Transferred';
            }

            $data->formSubStat = $stat;
        }

        return view('admin/viewSeniorityStatusByDP', compact('deptListArray', 'empList', 'empListArray', 'Remarks', 'getUser'));
    }

    public function downloadseniorityPDF(Request $request)
    {
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        // $deptListArray = DepartmentModel::orderBy( 'dept_name' )->get()->unique( 'dept_name' );
        if ($getUser->role_id == 1) {
            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->toArray();
            // $Appl_List = count( $empListArray );
            //dd( $Appl_List );
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe, appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->paginate(15);
            $Remarks = RemarksModel::get()->toArray();
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Verified';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }
                if ($data->status == 7) {
                    $stat = 'signed';
                    $data->status = 'Signed by DP';
                }
                if ($data->status == 8) {
                    $stat = 'transfer';
                    $data->status = 'Transferred';
                }



                $data->formSubStat = $stat;
            }

            $html = view('admin.seniorityPDF', ['empList' => $empList], ['empListArray' => $empListArray])->render();
            //return $empList;
            // dd( $html );
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();
           // $dompdf->stream('admin.seniorityPDF', ['Attachment' => false]);
        }
        if ($getUser->role_id == 2 || $getUser->role_id == 3 || $getUser->role_id == 4 || $getUser->role_id == 9) {
            $empListArray = ProformaModel::get()->where('dept_id', $getUser->dept_id)->where('status', '!=', 0)->toArray();
            // $Appl_List = count( $empListArray );
            //dd( $Appl_List );
            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe, appl_date, applicant_dob")->where('dept_id', $getUser->dept_id)->where('status', '!=', 0)->paginate(10);
            $Remarks = RemarksModel::get()->toArray();
            $stat = '';

            foreach ($empList as $data) {
                if ($data->status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->status = 'Incomplete';
                }

                if ($data->status == 1) {
                    $stat = 'submitted';
                    $data->status = 'Submitted';
                }
                if ($data->status == 2) {
                    $stat = 'verified';
                    $data->status = 'Verified';
                }
                if ($data->status == 3) {
                    $stat = 'forapproval';
                    $data->status = 'Put up for Approval';
                }

                if ($data->status == 4) {
                    $stat = 'approved';
                    $data->status = 'Approved';
                }
                if ($data->status == 5) {
                    $stat = 'appointed';
                    $data->status = 'Appointed';
                }
                if ($data->status == 6) {
                    $stat = 'order';
                    $data->status = 'Appointment Order';
                }
                if ($data->status == 7) {
                    $stat = 'signed';
                    $data->status = 'Signed by DP';
                }
                if ($data->status == 8) {
                    $stat = 'transfer';
                    $data->status = 'Transferred';
                }

                $data->formSubStat = $stat;
            }

            $html = view('admin.seniorityPDF', ['empList' => $empList], ['empListArray' => $empListArray])->render();
            //return $empList;
            // dd( $html );
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream();
           // $dompdf->stream('admin.seniorityPDF', ['Attachment' => false]);
        }

        if ($getUser->role_id == 5 || $getUser->role_id == 6 || $getUser->role_id == 8) {

            $empListArray = null;         
    
            $deptId = session()->get('dept_id');//$request->input('dept_id');
           //dd($deptId);
    
            if (session()->get('deptId') != "" && $request->input('page') == "") {
                $request->session()->forget(['deptId']);
            }
    
            if (strlen($deptId) > 0) {
                session()->put('deptId', $deptId);
            }
            if (strlen(session()->get('deptId')) > 0) {
                $deptId = session()->get('deptId');
                $empListArray = ProformaModel::where('dept_id', $deptId)->where('status', '!=', 0)->get()->toArray();
                $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),dept_name,deceased_doe, appl_date, applicant_dob, dept_id")->where('dept_id', $deptId)->where('status', '!=', 0)->paginate(15);
                $Remarks = RemarksModel::get()->toArray();
                $stat = '';
                 //dd( $empList );
                foreach ($empList as $data) {
                    if ($data->status == 0 && $data->form_status == 1) {
                        $stat = 'started';
                        $data->status = 'Incomplete';
                    }
    
                    if ($data->status == 1) {
                        $stat = 'submitted';
                        $data->status = 'Submitted';
                    }
                    if ($data->status == 2) {
                        $stat = 'verified';
                        $data->status = 'Verified';
                    }
                    if ($data->status == 3) {
                        $stat = 'forapproval';
                        $data->status = 'Put up for Approval';
                    }
    
                    if ($data->status == 4) {
                        $stat = 'approved';
                        $data->status = 'Approved';
                    }
                    if ($data->status == 5) {
                        $stat = 'appointed';
                        $data->status = 'Appointed';
                    }
                    if ($data->status == 6) {
                        $stat = 'order';
                        $data->status = 'Appointment Order';
                    }
                    if ($data->status == 7) {
                        $stat = 'signed';
                        $data->status = 'Signed by DP';
                    }
                    if ($data->status == 8) {
                        $stat = 'transfer';
                        $data->status = 'Transferred';
                    }
    
                    $data->formSubStat = $stat;
                }
    
                $html = view('admin.interseniorityPDF', ['empList' => $empList], ['empListArray' => $empListArray])->render();
                //return $empList;
                // dd( $html );
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $dompdf->stream();
               // $dompdf->stream('admin.interseniorityPDF', ['Attachment' => false]);
            
            } else {
                $empListArray = ProformaModel::get()->where('status', '!=', 0)->toArray();
                $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),dept_name,deceased_doe, appl_date, applicant_dob, dept_id")->where('status', '!=', 0)->paginate(15);
                $Remarks = RemarksModel::get()->toArray();
                $stat = '';
                // dd( $empList );
                foreach ($empList as $data) {
                    if ($data->status == 0 && $data->form_status == 1) {
                        $stat = 'started';
                        $data->status = 'Incomplete';
                    }
    
                    if ($data->status == 1) {
                        $stat = 'submitted';
                        $data->status = 'Submitted';
                    }
                    if ($data->status == 2) {
                        $stat = 'verified';
                        $data->status = 'Verified';
                    }
                    if ($data->status == 3) {
                        $stat = 'forapproval';
                        $data->status = 'Put up for Approval';
                    }
    
                    if ($data->status == 4) {
                        $stat = 'approved';
                        $data->status = 'Approved';
                    }
                    if ($data->status == 5) {
                        $stat = 'appointed';
                        $data->status = 'Appointed';
                    }
                    if ($data->status == 6) {
                        $stat = 'order';
                        $data->status = 'Appointment Order';
                    }
                    if ($data->status == 7) {
                        $stat = 'signed';
                        $data->status = 'Signed by DP';
                    }
                    if ($data->status == 8) {
                        $stat = 'transfer';
                        $data->status = 'Transferred';
                    }
    
                    $data->formSubStat = $stat;
                }
    
                $html = view('admin.interseniorityPDF', ['empList' => $empList], ['empListArray' => $empListArray])->render();
                //return $empList;
                // dd( $html );
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $dompdf->stream();
               // $dompdf->stream('admin.interseniorityPDF', ['Attachment' => false]);
            }
              
            }           
    }

    public function retrieve_dept(Request $request)
    {
        $deptId = $request->dept_id;

        $cd_grade = array();
        $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
            'dept_code' => $deptId,
            'token' => 'b000e921eeb20a0d395e341dfcd6117a',
        ]);
        $cd_grade = json_decode($response->getBody(), true);

        $postdept = [];
        foreach ($cd_grade as $cdgrade) {
            if ($cdgrade['group_code'] == 'C' || $cdgrade['group_code'] == 'D') {
                $postdept[] = $cdgrade;
            }
        }
        //only grade C and D are return
        return $postdept;
    }

    public function forwardSelectedEINs(Request $request)
    {
        // Get the array of selected EINs from the form submission
        $selectedEINs = explode(', ', $request->input('modalSelectedEIN', ''));
        // dd( $selectedEINs );

        // Find the maximum value of ad_efile_id_multi in the database
        $maxAdEfileIdMulti = applicants_statusModel::max('ad_efile_increment');
        // dd( $maxAdEfileIdMulti );
        // Initialize the counter variable to the maximum value

        // Increment the counter variable
        $adEfileIdMulti = $maxAdEfileIdMulti + 1;
        // $adEfileIdMulti = 0;
        // $adEfileIdMulti++;
        // Process each selected EIN
        foreach ($selectedEINs as $ein) {
            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $dateToday = new DateTime(date('m/d/Y'));

            $empDetails = ProformaModel::get()->where('ein', $ein)->first();
            $getUser1 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 4)->first();
            $getUser2 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 4)->toArray();

            if (count($getUser2) == null) {
                $receiver = null;
                //previous sender
            }
            if (count($getUser2) != null) {
                $receiver = $getUser1->name;
                //previous sender
            }

            // Handle file upload for each iteration
            if ($request->hasFile('ad_efile_link')) {
                $file = $request->file('ad_efile_link');
                $filename = $file->getClientOriginalName();
                // dd( $filename );
                $file->storeAs('public', $filename);
                // dd( $file );
                // Add the file path to the request for further processing if needed
                $request->merge(['ad_efile_path' => $filename]);
            }
            // Increment the counter for each iteration

            if ($empDetails != null) {

                $empDetails->update([
                    'file_status' => 4, //move to HOD Assistant
                    'received_by' => $receiver, //previous sender
                    'sent_by' => $getUser->name, //current sender
                    'forwarded_by' => $getUser->id,
                    'forwarded_on' => $dateToday,
                    'remark' => $request->remark,
                    'remark_details' => $request->remark_details,
                    'efile_ad' => $request->efile_ad,
                    'ad_file_link' => $filename, // Add the file link to the database
                    // ... other fields
                ]);
            }

            // Write save data for giving remarks
            applicants_statusModel::create([
                'ein' => $ein,
                'appl_number' => $empDetails->appl_number,
                'remark' => $request->remark,
                'remark_details' => $request->remark_details,
                'remark_date' => $dateToday,
                'entered_by' => $getUser->id,
                'efile_ad' => $request->efile_ad,
                'ad_efile_increment' => $adEfileIdMulti,
                'ad_file_link' => $filename, // Add the file link to the database
                'role_id' => $getUser->role_id,
                // ... other fields
            ]);
        }

        // Redirect or return a response
        return redirect()->route('viewForwardToADAssist')->with('message', 'Applicant details is Forwarded to Administrative Department Nodal Officer!!!');
    }

    public function forwardSelectedEINsDPAssistToDPNodal(Request $request)
    {
        // Get the array of selected EINs from the form submission
        $selectedEINs = explode(', ', $request->input('modalSelectedEIN', ''));
        // dd( $selectedEINs );

        // Find the maximum value of ad_efile_id_multi in the database
        $maxAdEfileIdMulti = applicants_statusModel::max('dp_efile_increment');
        // dd( $maxAdEfileIdMulti );
        // Initialize the counter variable to the maximum value

        // Increment the counter variable
        $dpEfileIdMulti = $maxAdEfileIdMulti + 1;

        //  dd( $dpEfileIdMulti );
        // $adEfileIdMulti = 0;
        // $adEfileIdMulti++;
        // Process each selected EIN
        foreach ($selectedEINs as $ein) {
            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $dateToday = new DateTime(date('m/d/Y'));

            $empDetails = ProformaModel::get()->where('ein', $ein)->first();
            $getUser1 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 6)->first();
            $getUser2 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 6)->toArray();

            if (count($getUser2) == null) {
                $receiver = null;
                //previous sender
            }
            if (count($getUser2) != null) {
                $receiver = $getUser1->name;
                //previous sender
            }

            // Handle file upload for each iteration
            if ($request->hasFile('dp_efile_link')) {
                $file = $request->file('dp_efile_link');
                $filename = $file->getClientOriginalName();
                // dd( $filename );
                $file->storeAs('public', $filename);
                // dd( $file );
                // Add the file path to the request for further processing if needed
                // $request->merge( [ 'dp_efile_path' => $filename ] );
            }
            // Increment the counter for each iteration

            if ($empDetails != null) {

                $empDetails->update([
                    'file_status' => 6, //move to HOD Assistant
                    'received_by' => $receiver, //previous sender
                    'sent_by' => $getUser->name, //current sender
                    'forwarded_by' => $getUser->id,
                    'forwarded_on' => $dateToday,
                    'remark' => $request->remark,
                    'remark_details' => $request->remark_details,
                    'status' => 3, //Approved for UO Generation
                    'efile_dp' => $request->efile_dp,
                    'dp_file_link' => $filename, // Add the file link to the database
                    // ... other fields
                ]);
            }

            // Write save data for giving remarks
            applicants_statusModel::create([
                'ein' => $ein,
                'appl_number' => $empDetails->appl_number,
                'remark' => $request->remark,
                'remark_details' => $request->remark_details,
                'remark_date' => $dateToday,
                'entered_by' => $getUser->id,
                'efile_dp' => $request->efile_dp,
                'dp_efile_increment' => $dpEfileIdMulti,
                'dp_file_link' => $filename, // Add the file link to the database
                'role_id' => $getUser->role_id,
                // ... other fields
            ]);
        }

        // Redirect or return a response
        return redirect()->route('selectDeptByDPAssist')->with('message', 'Applicant details is Forwarded to Administrative Department Nodal Officer!!!');
    }

    public function viewFileByADNodal($filename)
    {
        $filePath = storage_path('app/public/' . $filename);

        if (file_exists($filePath)) {
            return response()->file($filePath);
        } else {
            abort(404, 'File not found');
        }
    }

    public function forwardSelectedEINsADNodalToDPAssist(Request $request)
    {
        // Get the array of selected EINs from the form submission
        $selectedEINs = explode(', ', $request->input('modalSelectedEIN', ''));
        //  dd( $selectedEINs );

        // Find the maximum value of ad_efile_id_multi in the database

        foreach ($selectedEINs as $ein) {
            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $dateToday = new DateTime(date('m/d/Y'));

            $empDetails = ProformaModel::get()->where('ein', $ein)->first();
            $getUser1 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 5)->first();
            $getUser2 = User::get()->where('dept_id', $getUser->dept_id)->where('role_id', $getUser->role_id, 5)->toArray();

            if (count($getUser2) == null) {
                $receiver = null;
                //previous sender
            }
            if (count($getUser2) != null) {
                $receiver = $getUser1->name;
                //previous sender
            }

            // Increment the counter for each iteration

            if ($empDetails != null) {

                $empDetails->update([
                    'file_status' => 5, //move to HOD Assistant
                    'received_by' => $receiver, //previous sender
                    'sent_by' => $getUser->name, //current sender
                    'forwarded_by' => $getUser->id,
                    'forwarded_on' => $dateToday,
                    'remark' => $request->remark,
                    'remark_details' => $request->remark_details,

                    // ... other fields
                ]);
            }

            // Write save data for giving remarks
            applicants_statusModel::create([
                'ein' => $ein,
                'appl_number' => $empDetails->appl_number,
                'remark' => $request->remark,
                'remark_details' => $request->remark_details,
                'remark_date' => $dateToday,
                'entered_by' => $getUser->id,

                // ... other fields
            ]);
        }

        // Redirect or return a response
        return redirect()->route('viewForwardToADNodal')->with('message', 'Applicant details is Forwarded to  DP Assistant!!!');
    }

    public function viewEsignByDP()
    {

        session()->forget('deptId');
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');

        $statusArray = [6];
        // Order

        $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->whereIn('status', $statusArray)->paginate(10);

        $stat = '';

        foreach ($empList as $data) {
            //  dd( $data );
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }

            $data->formSubStat = $stat;
        }
        // dd( $empListprint );
        return view('admin/viewEsignByDP', compact('deptListArray', 'empList',));
    }

    public function viewEsignByDPSearch(Request $request)
    {
        // Change for DIHAS below

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        $statusArray = [6];
        $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe,appl_date, applicant_dob")->where('ein', $request->searchItem)->whereIn('status', $statusArray)->paginate(10);

        $stat = '';

        foreach ($empList as $data) {
            // dd( $data );
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }

            $data->formSubStat = $stat;
        }

        return view('admin/viewEsignByDP', compact('deptListArray', 'empList'));
    }

    public function viewEsignByDepartmentSigningAuthority()
    {

        session()->forget('deptId');
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');

        $statusArray = [7];
        // Order

        $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe, appl_date,applicant_dob")->whereIn('status', $statusArray)->paginate(10);

        $stat = '';

        foreach ($empList as $data) {
            //  dd( $data );
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }

            $data->formSubStat = $stat;
        }
        // dd( $empListprint );
        return view('admin/viewEsignByDepartmentSigningAuthority', compact('deptListArray', 'empList',));
    }

    public function viewEsignByDepartmentSigningAuthoritySearch(Request $request)
    {
        // Change for DIHAS below

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        $statusArray = [7];
        $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe, appl_date,applicant_dob")->where('ein', $request->searchItem)->whereIn('status', $statusArray)->paginate(10);

        $stat = '';

        foreach ($empList as $data) {
            // dd( $data );
            if ($data->status == 0 && $data->form_status == 1) {
                $stat = 'started';
                $data->status = 'Incomplete';
            }

            if ($data->status == 1) {
                $stat = 'submitted';
                $data->status = 'Submitted';
            }
            if ($data->status == 2) {
                $stat = 'verified';
                $data->status = 'Verified';
            }
            if ($data->status == 3) {
                $stat = 'forapproval';
                $data->status = 'Put up for Approval';
            }

            if ($data->status == 4) {
                $stat = 'approved';
                $data->status = 'Approved';
            }
            if ($data->status == 5) {
                $stat = 'appointed';
                $data->status = 'Appointed';
            }
            if ($data->status == 6) {
                $stat = 'order';
                $data->status = 'Appointment Order';
            }
            if ($data->status == 7) {
                $stat = 'signed';
                $data->status = 'Signed by DP';
            }

            $data->formSubStat = $stat;
        }

        return view('admin/viewEsignByDepartmentSigningAuthority', compact('deptListArray', 'empList'));
    }

    //below are for transfer

    public function updateTransferStatus(Request $request)
    {
        $dataId = $request->input('dataId');
        // dd( $dataId );

        $empDetails = ProformaModel::get()->where('id', $dataId)->first();

        if ($empDetails != null) {

            $empDetails->update([
                'transfer_status' => 1, //transfer to  any department department
            ]);
        }

        return response()->json(['success' => true]);
    }
    public function viewTransferListByHod(Request $request)
    {
        // Change for DIHAS below
        try {
            $request->session()->forget(['ein', 'ein', 'einsearch']);

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();


            $Remarks = RemarksModel::get()->toArray();
            $RemarksApprove = RemarksApproveModel::get()->toArray();
            // $status = [1, 2];

            $empListArray = ProformaModel::get()->where('transfer_dept_id', $getUser->dept_id)->where('form_status', 1)->where('status', 8)->where('file_status', 2)->where('transfer_status', 2)->toArray();
            // dd($empListArray);

            $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe, appl_date,applicant_dob")->where('transfer_dept_id', $getUser->dept_id)->where('form_status', 1)->where('status', 8)->where('file_status', 2)->where('transfer_status', 2)->paginate(10);
            // dd($empList);
            //expire_on_duty if yes top priority


            $stat = "";

            foreach ($empList as $data) {
                // $getUser1 = User::get()->where( 'id', $data->forwarded_by )->first();

                if ($data->transfer_status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->transfer_status = 'Incomplete';
                }

                
                if ($data->transfer_status == 2) {
                    $stat = 'transfer';
                    $data->transfer_status = 'Transferred';
                }

                $data->formSubStat = $stat;


                $cd_grade = array();
                $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
                    'dept_code' => $data->transfer_dept_id,
                    'token' => "b000e921eeb20a0d395e341dfcd6117a",
                ]);
                $cd_grade = json_decode($response->getBody(), true);
                
                $postnames = [];
                foreach ($cd_grade as $cdgrade) {
                    if (isset($cdgrade['dsg_serial_no']) && $cdgrade['dsg_serial_no'] == $data->transfer_post_id) {
                       
                        $postnames[] = $cdgrade['dsg_desc'];
                    }
                }
                
                // Assign the array of matching postnames to the user data
                $data->matching_postnames = $postnames;

               
                
            }

          

            return view('admin/viewTransferListByHod', compact("RemarksApprove", "empList", "empListArray", "Remarks"));
        } catch (Exception $e) {

            return response()->json([
                'status' => 0,
                'msg' => "Server not responding!!Pls see your internet connection!!or CMIS portal down",
                //'errors' => $e->getMessage()
            ]);
        }
    }
    public function viewTransferListByHodAssistant(Request $request)
    {
        // Change for DIHAS below
        try {
            $request->session()->forget(['ein', 'ein', 'einsearch']);

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();

            $Remarks = RemarksModel::get()->toArray();
            $RemarksApprove = RemarksApproveModel::get()->toArray();
            // $status = [ 1, 2 ];

            $empListArray = ProformaModel::get()->where('transfer_dept_id', $getUser->dept_id)->where('form_status', 1)->where('status', 8)->where('file_status', 1)->where('transfer_status', 2)->toArray();
            // dd( $empListArray );

            $empList = ProformaModel::orderByRaw("expire_on_duty = 'no', deceased_doe, appl_date,applicant_dob")->where('transfer_dept_id', $getUser->dept_id)->where('form_status', 1)->where('status', 8)->where('file_status', 1)->where('transfer_status', 2)->paginate(10);
            // dd( $empList );
            //expire_on_duty if yes top priority

            $stat = '';

            foreach ($empList as $data) {
                if ($data->transfer_status == 0 && $data->form_status == 1) {
                    $stat = 'started';
                    $data->transfer_status = 'Incomplete';
                }

                if ($data->transfer_status == 2) {
                    $stat = 'transfer';
                    $data->transfer_status = 'Transferred';
                }

                $data->formSubStat = $stat;
                $cd_grade = array();
                $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
                    'dept_code' => $data->transfer_dept_id,
                    'token' => 'b000e921eeb20a0d395e341dfcd6117a',
                ]);
                $cd_grade = json_decode($response->getBody(), true);

                $postnames = [];
                foreach ($cd_grade as $cdgrade) {
                    if (isset($cdgrade['dsg_serial_no']) && $cdgrade['dsg_serial_no'] == $data->transfer_post_id) {

                        $postnames[] = $cdgrade['dsg_desc'];
                    }
                }

                // Assign the array of matching postnames to the user data
                $data->matching_postnames = $postnames;
            }

            return view('admin/viewTransferListByHodAssistant', compact('RemarksApprove', 'empList', 'empListArray', 'Remarks'));
        } catch (Exception $e) {

            return response()->json([
                'status' => 0,
                'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                //'errors' => $e->getMessage()
            ]);
        }
    }

    public function viewTransferListByHodAssistantSearch(Request $request)
    {
        try {
            $empListArray = null;
            $request->session()->forget(['ein', 'from_emp_ein']);
            // session()->forget( [ 'ein', 'from_emp_ein' ] );
            // $ein = null;

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $Remarks = RemarksModel::get()->toArray();
            $RemarksApprove = RemarksApproveModel::get()->toArray();
            $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');

            // $tempEmpList = null;

            if ($getUser->role_id == 1) {
                if ($request->searchItem != null || trim($request->searchItem) != '') {

                    $empListArray = ProformaModel::get()->where('transfer_dept_id', $getUser->dept_id)->where('ein', $request->searchItem)->toArray();
                    // $Appl_List = count( $empListArray );
                    // where( 'transfer_dept_id', $getUser->dept_id )->where( 'form_status', 1 )->where( 'status', 8 )->where( 'file_status', 1 )->where( 'transfer_status', 2 )
                    // dd( $empListArray );
                    $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe, appl_date,applicant_dob")->where('transfer_dept_id', $getUser->dept_id)->where('ein', $request->searchItem)->paginate(15);
                    $Remarks = RemarksModel::get()->toArray();
                } else {
                    $empListArray = ProformaModel::get()->where('transfer_dept_id', $getUser->dept_id)->toArray();
                    // $Appl_List = count( $empListArray );
                    // dd( $empListArray );
                    $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'),deceased_doe, appl_date,applicant_dob")->where('transfer_dept_id', $getUser->dept_id)->paginate(15);
                    $Remarks = RemarksModel::get()->toArray();
                }

                $stat = '';

                foreach ($empList as $data) {
                    // $getUser1 = User::get()->where( 'id', $data->forwarded_by )->first();

                    if ($data->transfer_status == 0 && $data->form_status == 1) {
                        $stat = 'started';
                        $data->status = 'Incomplete';
                    }

                    if ($data->transfer_status == 2) {
                        $stat = 'transfer';
                        $data->transfer_status = 'Transferred';
                    }

                    $data->formSubStat = $stat;

                    $cd_grade = array();
                    $response = Http::post('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-dept-details-by-dept-cd', [
                        'dept_code' => $data->transfer_dept_id,
                        'token' => 'b000e921eeb20a0d395e341dfcd6117a',
                    ]);
                    $cd_grade = json_decode($response->getBody(), true);

                    $postnames = [];
                    foreach ($cd_grade as $cdgrade) {
                        if (isset($cdgrade['dsg_serial_no']) && $cdgrade['dsg_serial_no'] == $data->transfer_post_id) {

                            $postnames[] = $cdgrade['dsg_desc'];
                        }
                    }

                    // Assign the array of matching postnames to the user data
                    $data->matching_postnames = $postnames;
                }
                Session::put('einsearch', $request->searchItem);

                return view('admin/viewTransferListByHodAssistant', compact('RemarksApprove', 'empList', 'empListArray', 'Remarks'));
            }
        } catch (Exception $e) {

            return response()->json([
                'status' => 0,
                'msg' => 'Server not responding!!Pls see your internet connection!!or CMIS portal down',
                //'errors' => $e->getMessage()
            ]);
        }
    }

    public function transfer_applicant_By_DPAssist(Request $request)
    {
        // Change for DIHAS below

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        $Remarks = RemarksModel::get()->toArray();
        $RemarksApprove = RemarksApproveModel::get()->toArray();
        $deptId = $request->input('dept_id');
        $departments = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
        $empListArray = array();
        if (session()->get('deptId') != '' && $request->input('page') == '') {
            $request->session()->forget(['deptId']);
        }

        if (strlen($deptId) > 0) {
            session()->put('deptId', $deptId);
        }
        if (strlen(session()->get('deptId')) > 0) {
            $deptId = session()->get('deptId');
            $empListArray = ProformaModel::get()->where('dept_id', $deptId)->where('transfer_status', 1)->toArray();

            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe, appl_date,applicant_dob")->where('dept_id', $deptId)->where('transfer_status', 1)->paginate(10);
            $empListprint = ProformaModel::orderByRaw("(expire_on_duty = 'no'), deceased_doe, appl_date,applicant_dob")->where('dept_id', $deptId)->where('transfer_status', 1)->get();
            //  dd( $empList );
        } else {

            $empListArray = ProformaModel::get()->where('transfer_status', 1)->toArray();

            $empList = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe, appl_date,applicant_dob")->where('transfer_status', 1)->paginate(10);
            $empListprint = ProformaModel::orderByRaw("(expire_on_duty = 'no'), dept_name, deceased_doe, appl_date,applicant_dob")->where('transfer_status', 1)->get();
            //  dd( $empList );
        }
        //the list is not in order of seniority here but while download and print it's in seniority

        $stat = "";

        foreach ($empList as $data) {
            if ($data->transfer_status == 0 && $data->form_status == 1) {
                $stat = "started";
                $data->status = "Incomplete";
            }

            if ($data->transfer_status == 1) {
                $stat = "signed";
                $data->transfer_status = "Approved for Transfer";
            }


            $data->formSubStat = $stat;
        }

        return view('admin/transfer_applicant_By_DPAssist', compact("departments", "empListprint", "getUser", "RemarksApprove", "deptListArray", "empListArray", "empList", "Remarks"));
    }

    //forward applicant to DPAssistToDPNodal
    public function transferFromDPAssistToAnyDepartment($id, Request $request)
    {
        //   dd($request->post);
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $dateToday = new DateTime(date("m/d/Y"));
        //dd($request->remarks);         

        $empDetails = ProformaModel::get()->where("ein", $request->ein)->first();
        $getUser1 = User::get()->where('role_id', $getUser->role_id, 6)->first();

        $getUser2 = User::get()->where('role_id', $getUser->role_id, 6)->toArray();
        //dd($getUser1->name);
        if (count($getUser2) == null) {
            $receiver = null; //previous sender
        }
        if (count($getUser2) != null) {
            $receiver = $getUser1->name; //previous sender
        }
        if ($empDetails != null) {
            $empDetails->update([
                'file_status' => 1, //move to HOD Assistant 
                'received_by' => $receiver, //previous sender
                'sent_by' => $getUser->name, //current sender
                'forwarded_by' => $getUser->id,
                'forwarded_on' => $dateToday,
                'remark' => $request->remark,
                'remark_details' => $request->remark_details,
                'status' => 8, //Transfer to any department
                'transfer_status' => 2,
                'transfer_dept_id' => $request->dept_id,
                'transfer_post_id' => $request->post,
                //2 is for verified and 1 for submitted and 0 back to start          
                //Left side = column name ;right side = name in the blade


            ]);
        }

        applicants_statusModel::create([
            'ein' => $request->ein,
            'appl_number' => $empDetails->appl_number,
            'transfer_dept_id' => $request->dept_id,
            'transfer_post_id' => $request->post,
            'remark' => $request->remark,
            'remark_details' => $request->remark_details,
            'remark_date' => $dateToday,
            'entered_by' => $getUser->id,


            'role_id' => $getUser->role_id,
        ]);
        //write save data for giving remarks

        TransferModel::create([
            'ein' => $request->ein,
            'transfer_dept_id' => $request->dept_id,
            'transfer_post_id' => $request->post,

            'remark' => $request->remark,
            'remark_details' => $request->remark_details,

            //Left side = column name ;right side = name in the blade


        ]);

        return redirect()->route('transfer_applicant_By_DPAssist')->with('message', 'Applicant details is transfer successfully !!!');
    }

    public function transferFromDPNodal($id, Request $request)
{
    //dd( $request->toArray() );
    $user_id = Auth::user()->id;
   
    //dd( $request->remarks );

    $empDetails = ProformaModel::get()->where('ein', $request->ein)->first();
    if ($empDetails != null) {
        $empDetails->update([
            'transfer_status' => 1, //transfer to  any department department
          
            'transfer_remark' => $request->transfer_remark,
            'transfer_remark_details' => $request->transfer_remark_details,
        ]);
    }

    return redirect()->route('selectDeptByDPNodal')->with('message', ' Transfer Successfully!!!');
}

public function viewFileForwardByHODAssistant($filename)
    {
        $filePath = storage_path('app/public/' . $filename);

        if (file_exists($filePath)) {
            return response()->file($filePath);
        } else {
            abort(404, 'File not found');
        }
    }
}
