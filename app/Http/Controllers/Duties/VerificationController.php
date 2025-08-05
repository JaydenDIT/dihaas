<?php

namespace App\Http\Controllers\Duties;

use App\Http\Controllers\Controller;
use App\Models\CasteModel;
use App\Models\DepartmentModel;
use App\Models\District;
use App\Models\EducationModel;
use App\Models\EmpFormSubmissionStatus;
use App\Models\FormFieldMaster;
use App\Models\GenderModel;
use App\Models\ProformaModel;
use App\Models\RelationshipModel;
use App\Models\State;
use App\Models\SubDivision;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class VerificationController extends Controller
{
    //This will display detail about an employee bearing the EIN as passed as parameter
    public function viewDetail($id)
    {
        //return $ein;
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
        // dd( $cd_grade );
        foreach ($cd_grade as $cdgrade) {
            if ($cdgrade['group_code'] == 'C' || $cdgrade['group_code'] == 'D') {
                $post[] = $cdgrade;
            }
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

            if ($emp_form_stat->status == 1) {
                $status = 1;
            } else {
                $status = 0;
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

            return view('duties.verify_and_approve.verify_new_application', compact('deptListArray', 'getUploader', 'proformas', 'cur_districts', 'per_districts', 'cur_subdivision', 'per_subdivision', 'Caste', 'Relationship', 'Gender', 'formStatArray', 'status', 'fieldCollection', 'data', 'stateDetails', 'post', 'educations'));
        }
    }
}
