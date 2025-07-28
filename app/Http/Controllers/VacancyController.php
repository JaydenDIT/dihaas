<?php


namespace App\Http\Controllers;

use App\Models\CmisVacancyModel;
use App\Models\User;
use App\Models\PercentageModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Library\Senitizer;
use Dompdf\Dompdf;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Type\Integer;

class VacancyController extends Controller
{
    public function __construct()
    {
        if (isset($_REQUEST)) {
            $_REQUEST = Senitizer::senitize($_REQUEST);
        }
    }
    

    public function index()

    {
        Session::forget('allvacancyData'); ///forget the session of search
        $user_id = Auth::user()->id;
        $getUser = User::where('id', $user_id)->first();
        $vacancyData = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)
            ->orderBy('id', 'asc')->paginate(10);

            $vacancyDataPrint = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)
            ->orderBy('id', 'asc')->get();
        $vacancyList = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)
            ->orderBy('id', 'asc')->get()->toArray();

        $percentageModel = PercentageModel::first();

        return view('admin.Form.vacancy.index', compact('vacancyDataPrint','vacancyData', 'vacancyList', 'percentageModel'));
    }



    public function vacancySearch(Request $request)
    {

        $user_id = Auth::user()->id;
        $getUser = User::where('id', $user_id)->first();


        if ($request->searchItem != null || trim($request->searchItem) != "") {
            $vacancyData = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)
                ->where(function ($query) use ($request) {
                    $query->where('designation', 'ILIKE', $request->searchItem . '%')
                        ->orWhere('designation', 'ILIKE', '% ' . $request->searchItem . '%');
                })->paginate(10);

                $vacancyDataPrint = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)
                ->where(function ($query) use ($request) {
                    $query->where('designation', 'ILIKE', $request->searchItem . '%')
                        ->orWhere('designation', 'ILIKE', '% ' . $request->searchItem . '%');
                })->get();
            $vacancyData->appends(['searchItem' => $request->searchItem]);

            $allvacancyData = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)
                ->where(function ($query) use ($request) {
                    $query->where('designation', 'ILIKE', $request->searchItem . '%')
                        ->orWhere('designation', 'ILIKE', '% ' . $request->searchItem . '%');
                })->get();

// dd($allvacancyData);
            $vacancyList = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)
                ->where(function ($query) use ($request) {
                    $query->where('designation', 'ILIKE', $request->searchItem . '%')
                        ->orWhere('designation', 'ILIKE', '% ' . $request->searchItem . '%');
                })->get()->toArray();
            Session::put('allvacancyData', $allvacancyData);
            return view('admin.Form.vacancy.index', compact('vacancyDataPrint','vacancyData', 'allvacancyData', 'vacancyList'));
        }

        if ($request->searchItem == null || trim($request->searchItem) == "") {
            $vacancyData = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)
                ->orderBy('id', 'asc')->paginate(10);
                $vacancyDataPrint = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)
                ->orderBy('id', 'asc')->get();    
            $vacancyList = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)
                ->orderBy('id', 'asc')->get()->toArray();
            return view('admin.Form.vacancy.index', compact('vacancyDataPrint','vacancyData', 'vacancyList'));
        }
    }

    public function vacancystatusSearch(Request $request)
    {
        ////for statussearch

        $user_id = Auth::user()->id;
        $getUser = User::where('id', $user_id)->first();

        if ($request->searchItem != null || trim($request->searchItem) != "") {

            $vacancyData = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)
                ->where(function ($query) use ($request) {
                    $query->where('designation', 'ILIKE', $request->searchItem . '%')
                        ->orWhere('designation', 'ILIKE', '% ' . $request->searchItem . '%');
                })->paginate(15);

                $vacancyDataPrint = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)
                ->where(function ($query) use ($request) {
                    $query->where('designation', 'ILIKE', $request->searchItem . '%')
                        ->orWhere('designation', 'ILIKE', '% ' . $request->searchItem . '%');
                })->get();

            // Append the search query to pagination links
            $vacancyData->appends(['searchItem' => $request->searchItem]);
            //allvacancyData is for making Pdf i.e for all record(for searching by ein , 
            // paginate(10) will not create problems.)
            $allvacancyData = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)
                ->where(function ($query) use ($request) {
                    $query->where('designation', 'ILIKE', $request->searchItem . '%')
                        ->orWhere('designation', 'ILIKE', '% ' . $request->searchItem . '%');
                })->get();



            $vacancyList = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)
                ->where(function ($query) use ($request) {
                    $query->where('designation', 'ILIKE', $request->searchItem . '%')
                        ->orWhere('designation', 'ILIKE', '% ' . $request->searchItem . '%');
                })->get()->toArray();

            // Store $vacancyData in the session
            Session::put('allvacancyData', $allvacancyData);
            return view('admin.Form.vacancy.vacancystatus', compact('vacancyDataPrint','vacancyData', 'allvacancyData', 'vacancyList'));
        }
        if ($request->searchItem == null || trim($request->searchItem) == "") {
            $vacancyData = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)
                ->orderBy('id', 'asc')->paginate(10);
                $vacancyDataPrint = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)
                ->orderBy('id', 'asc')->get();
    
            $vacancyList = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)
                ->orderBy('id', 'asc')->get()->toArray();
            return view('admin.Form.vacancy.vacancystatus', compact('vacancyDataPrint','vacancyData', 'vacancyList'));
        }
    }

    /* public function vacancyCalculate($totalPost, $id){
        $percentModel = PercentageModel::find($id);
        $dih = ($percentModel->vpercentage * $totalPost)/100;
        $remainingPost = $totalPost-$dih;

        return response()->json([
            'dih' => $dih,
            'remaining' => $remainingPost
        ]);
    } */


    public function update(Request $request)
    {
        //current_employee_under_dih
        // Validate the incoming request data
        $request->validate([
            'id' => 'required|integer',
             'current_employee_under_dih' => 'nullable|integer',
            'total_post_vacant_dept' => 'required|integer',
            'employee_under_dih' => 'nullable|integer',
            'post_under_direct' => 'nullable|integer',
            // Add validation rules for other fields if necessary
        ]);
    


        // Retrieve input data
        $id = $request->input('id');
         $current_employee_under_dih = $request->input('current_employee_under_dih');
        $total_post_vacant_dept = $request->input('total_post_vacant_dept');
        $employee_under_dih = $request->input('employee_under_dih');
        $post_under_direct = $request->input('post_under_direct');
    
        // Find the vacancy by ID
        $vacancy = CmisVacancyModel::find($id);
        /* 
        return response()->json(['success' => true, 
            'message' => 'Vacancy test',
            'vaccancy' => $vacancy
        ]); */
    
        // if (!is_null($vacancy->total_post_vacant_dept)) {
        if ($vacancy) {

            $message = (is_null($vacancy->total_post_vacant_dept))?
            "Vaccant post created successfully.":"Vacancy updated successfully.";

            // If the vacancy exists, update the fields
            $vacancy->update([
                 'current_employee_under_dih' => $current_employee_under_dih,
                'total_post_vacant_dept' => $total_post_vacant_dept,
                'employee_under_dih' => $employee_under_dih,
                'post_under_direct' => $post_under_direct,
                // Update other fields as needed
            ]);
            return response()->json(['success' => true, 'message' => $message]);
        } else {
            // If the vacancy does not exist, create a new one
            CmisVacancyModel::create([
                'id' => $id,
                 'current_employee_under_dih' => $current_employee_under_dih,
                'total_post_vacant_dept' => $total_post_vacant_dept,
                'employee_under_dih' => $employee_under_dih,
                'post_under_direct' => $post_under_direct,
                // Add other fields as needed
            ]);
            return response()->json(['success' => true, 'message' => 'Vacancy created successfully']);
        }


    }



    public function vacancystatus(Request $request)
    {
        Session::forget('allvacancyData'); ///forget the session of search
        $user_id = Auth::user()->id;
        $getUser = User::where('id', $user_id)->first();
        $vacancyData = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)
            ->orderBy('id', 'asc')->paginate(10);
            $vacancyDataPrint = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)
            ->orderBy('id', 'asc')->get();


        $vacancyList = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)
            ->orderBy('id', 'asc')->get()->toArray();


        return view('admin.Form.vacancy.vacancystatus', compact('vacancyDataPrint','vacancyData', 'vacancyList'));
    }

    public function downloadpdfvacancy()
    {
        // Check if the vacancyData is in the session
        if (Session::has('allvacancyData')) {
            // Get data from session
            $vacancyData = Session::get('allvacancyData');

            // Generate PDF using $vacancyData
            $html = view('pdf.vacancy', compact('vacancyData'))->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Forget the session data

            $dompdf->stream('vacancydp', ['Attachment' => false]);
        } else {

            // Fetch data from the database
            $user_id = Auth::user()->id;
            $getUser = User::where('id', $user_id)->first();
            $vacancyData = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)->get();
            $vacancyList = CmisVacancyModel::where('field_dept_code', $getUser->dept_id)->get()->toArray();
            // Generate PDF using $vacancyData
            $html = view('pdf.vacancy', compact('vacancyData', 'vacancyList'))->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Stream the PDF to the user's browser for download
            $dompdf->stream('vacancydp.pdf', ['Attachment' => false]);
        }
    }
}
