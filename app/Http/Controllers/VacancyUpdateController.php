<?php

namespace App\Http\Controllers;

use App\Models\CmisVacancyModel;
use App\Models\DepartmentModel;
use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\RemarksModel;
use App\Models\User;
use App\Models\VacancyModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Library\Senitizer;
use App\Models\DesignationModel;

class VacancyUpdateController extends Controller
{

    public function __construct()
    {
        if (isset($_REQUEST)) {
            $_REQUEST = Senitizer::senitize($_REQUEST);
        }
    }

    // public function showForm()
    // {
    //     $user_id = Auth::user()->id;
    //     $getUser = User::get()->where('id', $user_id)->first();

    //     $vacanciesCMIS = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)->get();
    //     return view('admin/Form/vacancyUpdate', compact('vacanciesCMIS'));
    // }
   

    public function vacancy_list_dpview(Request $request)
    {
        $vacancyList1 = null;
        $vacancyList = null;
        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $deptId = $request->input('dept_id');

        $vacancies = array();
        $vacancies2 = array();

        if (session()->get('deptId') != "" && $request->input('page') == "") {
            $request->session()->forget(['deptId']);
        }

        if (strlen($deptId) > 0) {
            session()->put('deptId', $deptId);
        }
        if (strlen(session()->get('deptId')) > 0) {
            $deptId = session()->get('deptId');
            $vacancyList1 = CmisVacancyModel::where('field_dept_code', $deptId)->get()->toArray();
            $vacancyArray1 = CmisVacancyModel::where('field_dept_code', $deptId)->paginate(15);
            $vacancyArray1print = CmisVacancyModel::orderBy('field_dept_code')->where('field_dept_code', $deptId)->get()->toArray(); //THE CODE IS USE FOR PRINTING PURPOSE
        
        } else {
            $vacancyList1 = CmisVacancyModel::get()->toArray();
            $vacancyArray1 = CmisVacancyModel::orderBy('field_dept_code')->paginate(15);
            $vacancyArray1print = CmisVacancyModel::orderBy('field_dept_code')->get()->toArray(); //THE CODE IS USE FOR PRINTING PURPOSE
          
        }

        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name'); //drop down

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $vacancies = array();
        foreach ($vacancyArray1 as $vacancy) {
            $empDept = DepartmentModel::get()->where('dept_id', $vacancy->field_dept_code)->first();
            $vacancy->department = $empDept->dept_name;
            $vacancies[] = $vacancy;
        }
        //THE BELOW CODE IS USE FOR PRINTING PURPOSE
        $vacancies2 = array();

        foreach ($vacancyArray1print as $vacancy) {
            $empDept = DepartmentModel::where('dept_id', $vacancy['field_dept_code'])->first();

            if ($empDept) {
                $vacancy['dept_name'] = $empDept['dept_name'];
                $vacancies2[] = $vacancy;
            }
        }

        return view('admin/Form/display_vacancies_dpview', compact('vacancyArray1print', 'vacancyArray1', 'vacancyList1', 'vacancies', 'vacancies2', 'deptListArray'));
    }


    public function vacancy_list_dpviewSearch(Request $request)
    {
        $vacancyList1 = null;

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();

        $vacancies = array();

        if ($request->searchItem != null || trim($request->searchItem) != '') {

            $vacancyList1 = CmisVacancyModel::get()->toArray();
            $vacancyArray1 = CmisVacancyModel::where(function ($query) use ($request) {
                $query->where('designation', 'ILIKE', $request->searchItem . '%')
                    ->orWhere('designation', 'ILIKE', '% ' . $request->searchItem . '%');
            })->paginate(15);

            $vacancyArray1print = CmisVacancyModel::where(function ($query) use ($request) {
                $query->where('designation', 'ILIKE', $request->searchItem . '%')
                    ->orWhere('designation', 'ILIKE', '% ' . $request->searchItem . '%');})->get()->toArray(); //THE CODE IS USE FOR PRINTING PURPOSE
              
        } else {
            //if seacrh is null
            $vacancyList1 = CmisVacancyModel::get()->toArray();
            $vacancyArray1 = CmisVacancyModel::orderBy('field_dept_code')->paginate(15);
            //below is for print
            $vacancyArray1print = CmisVacancyModel::orderBy('field_dept_code')->get()->toArray(); //THE CODE IS USE FOR PRINTING PURPOSE
        }
        //////////////////////////
        $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name'); //drop down

        $vacancies = array();
        foreach ($vacancyArray1 as $vacancy) {
            $empDept = DepartmentModel::get()->where('dept_id', $vacancy->field_dept_code)->first();
            $vacancy->department = $empDept->dept_name;
            $vacancies[] = $vacancy;
        }
        ////////////////////////PRINT FOR SEARCHING
        $vacancies2 = array();

        foreach ($vacancyArray1print as $vacancy) {
          
            $empDept = DepartmentModel::where('dept_id', $vacancy['field_dept_code'])->first();

            if ($empDept) {
                $vacancy['dept_name'] = $empDept['dept_name'];
                $vacancies2[] = $vacancy;
            }
        }
            ////////////////////////PRINT FOR SEARCHING          

        return view('admin/Form/display_vacancies_dpview', compact('vacancyArray1print', 'vacancyArray1', 'vacancyList1', 'vacancies', 'vacancies2', 'deptListArray'));
      }



 
}
