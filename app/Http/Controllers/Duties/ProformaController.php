<?php

namespace App\Http\Controllers\Duties;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProformaRequest;
use App\Models\Caste;
use App\Models\District;
use App\Models\FamilyDetail;
use App\Models\Proforma;
use App\Models\Qualification;
use App\Models\Relationship;
use App\Models\State;
use App\Models\SubDivision;
use App\Models\Task;
use App\Services\CmisApiService;
use App\Services\DocumentRequirementService;
use App\Services\LogService;
use App\Services\ProcessMatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProformaController extends Controller
{
    //
    public function create()
    {
        $this->authorize('canPerform', [Proforma::class, 'client_form_submission']);
        $action = "create";
        $relationships = Relationship::whereNotIn('relationship_name', [
            'Father',
            'Mother',
            'Spouse',
            'Other',
        ])->orderBy('relationship_id')->get();
        $qualifications = Qualification::all();
        $castes = Caste::all();
        $states = State::all();
        $proforma = new Proforma();
        $current_step = 1;

        $result = CmisApiService::apiAdminDepartments();
        $adminDepartments = $result; //test for real data uncomment below and comment this line
        // if ($result->failed()) {
        //     return response()->json(['message' => 'Failed to fetch departments'], $result->status());
        // }
        // $adminDepartments = $result->json();

        return view('proforma.createProforma', compact(
            'action',
            'relationships',
            'qualifications',
            'castes',
            'states',
            'adminDepartments',
            'proforma',
            'current_step'
        ));
    }

    public function store(StoreProformaRequest $request)
    {
        try {
            $this->authorize('canPerform', [Proforma::class, 'client_form_submission']);
            DB::beginTransaction();
            $data = $request->validated();
            $data['create_by'] = Auth::user()->user_id;
            $data['mini_sequence'] = 'step1-completed'; //next step
            $proforma = Proforma::create($data);
            //This define which process the proforma will follow
            ProcessMatcher::matchAndAssignProcess($proforma);
            $proforma->save();

            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => $data['create_by'],
                'action_name' => 'Save Proforma on Draft',
                'action_remark' => 'Step 1 completed',
                'process_sequence' => $proforma->process_sequence
            ]);
            DB::commit();
            return response()->json(['message' => 'Proforma created successfully', 'proforma_id' => $proforma->proforma_id], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Proforma creation failed', 'error' => $e->getMessage()], 422);
        }
    }

    public function update(StoreProformaRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $proforma = Proforma::findOrFail($id);
            $this->authorize('canPerformOnProforma',  [$proforma, 'client_form_submission']);
            $data['mini_sequence'] = 'step1-completed'; //next step
            $proforma->update($data);
            //This define which process the proforma will follow
            ProcessMatcher::matchAndAssignProcess($proforma);
            $proforma->save();

            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => Auth::user()->user_id,
                'action_name' => 'Update Proforma on Draft',
                'action_remark' => 'Step 1 updated',
                'process_sequence' => $proforma->process_sequence
            ]);
            DB::commit();
            return response()->json(['message' => 'Proforma updated successfully', 'proforma_id' => $proforma->proforma_id], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Proforma update failed', 'error' => $e->getMessage()], 422);
        }
    }


    public function edit(Request $request, $id)
    {
        $action = "edit";
        $proforma = Proforma::findOrFail($id);
        $this->authorize('canPerformOnProforma',  [$proforma, 'client_form_submission']);
        $relationships = Relationship::whereNotIn('relationship_name', [
            'Father',
            'Mother',
            'Spouse',
            'Other',
        ])->orderBy('relationship_id')->get();
        $qualifications = Qualification::all();
        $castes = Caste::all();
        $states = State::all();
        $current_districts = District::where('state_id', $proforma->applicant_current_state_id)->get();
        $current_subdivisions = SubDivision::where('district_id', $proforma->applicant_current_district_id)->get();
        $permanent_districts = District::where('state_id', $proforma->applicant_permanent_state_id)->get();
        $permanent_subdivisions = SubDivision::where('district_id', $proforma->applicant_permanent_district_id)->get();

        $parentPostList = CmisApiService::apiAllPostUnderDepartment($proforma->deceased_field_dept_cd);
        $otherDepartList = CmisApiService::apiAdminDepartments($proforma->request_adm_dept_cd_3);
        $otherPostList = CmisApiService::apiAllPostUnderDepartment($proforma->request_field_dept_cd_3);
        $adminDepartments = CmisApiService::apiAdminDepartments();

        //for the document upload
        $documents = DocumentRequirementService::getDocumentsWithRequirement($proforma);
        $requiredDocumentsLeft = $documents
            ->where('required', true)
            ->filter(function ($doc) {
                return empty($doc->uploaded_file);
            })
            ->count();
        //for family members
        $familyMembers = FamilyDetail::where('proforma_id', $proforma->proforma_id)->get();

        if ($proforma->mini_sequence) { //if not null
            $current_step = $proforma->mini_sequence == "step1-completed" ? 2 : ($proforma->mini_sequence == "step2-completed" ? 3 : 4);
        } else {
            $current_step = 1;
        }

        // dd($parentPostList);

        $tasks = getPrevNextTasks($proforma->proforma_id); //from helper.php
        return view('proforma.createProforma', compact(
            'action',
            'relationships',
            'qualifications',
            'castes',
            'states',
            'adminDepartments',
            'proforma',
            'current_districts',
            'current_subdivisions',
            'permanent_districts',
            'permanent_subdivisions',
            'parentPostList',
            'otherDepartList',
            'otherPostList',
            'documents',
            'requiredDocumentsLeft',
            'familyMembers',
            'current_step',
            'tasks'
        ));
    }


    public function view(Request $request, $id)
    {
        $action = "view";
        $proforma = Proforma::findOrFail($id);
        /*
        //if want filter using role_group like what citizen should do
        if (Auth::user()->role_group === 'citizen') {
            $proforma = Proforma::where('proforma_id', $id)->where('created_by', Auth::user()->user_id)->first();
        }
        */
        //$this->authorize('canView',  [$proforma, 'client_form_submission']);
        return view('proforma.viewProforma', compact(
            'action',
            'proforma',
        ));
    }


    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $proforma = Proforma::findOrFail($id);
            $this->authorize('canPerformOnProforma',  [$proforma, 'client_form_submission']);
            $proforma->delete();
            DB::commit();
            return response()->json(['message' => 'Proforma deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Transaction failed', 'error' => $e->getMessage()], 422);
        }
    }

    //method to prepare data for data table and returns data
    private function prepareDataForDataTable($application_status, $created_by = null)
    {
        switch ($application_status) {
            case 'all':
                $query = Proforma::where(function ($qry) {
                    $qry->whereNotIn('mini_sequence', [
                        'step1-completed',
                        'step2-completed',
                        'step3-completed',
                    ])->orWhereNull('mini_sequence');
                });
                break;
            case 'pending':
                $query = Proforma::where(function ($qry) {
                    $qry->whereNotIn('mini_sequence', [
                        'step1-completed',
                        'step2-completed',
                        'step3-completed',
                    ])->orWhereNull('mini_sequence');
                })->where('proforma_status', '!=', 'completed');
                break;
            case 'completed':
                $query = Proforma::where('proforma_status', '=', 'completed');
                break;
            default:
                if (!empty($created_by)) {
                    return Proforma::where('create_by', $created_by)->get();
                }
                return Proforma::orderByRaw("expire_on_duty = false, deceased_doe, proforma_submission_date, applicant_dob")->get();
                // Do nothing
        }
        $query->orderByRaw("expire_on_duty = false, deceased_doe, proforma_submission_date, applicant_dob");
        if (!empty($created_by)) {
            return $query->where('create_by', $created_by)->get();
        }
        return $query->get();
    }

    //Method to display overall proforma in data table
    public function getProformaForDataTable(Request $request)
    {
        $application_status = $request->input('application_status');
        $created_by = $request->input('created_by', null);
        $overallSeniorityIndex = Proforma::getOverallSeniorityList();

        $data = $this->prepareDataForDataTable($application_status, $created_by);

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('deceased_doe', function ($row) {
                return date('d M, Y', strtotime($row->deceased_doe));
            })
            ->editColumn('created_at', function ($row) {
                return date('d M, Y', strtotime($row->created_at));
            })
            ->editColumn('proforma_submission_date', function ($row) {
                return date('d M, Y', strtotime($row->proforma_submission_date));
            })
            ->editColumn('applicant_dob', function ($row) {
                return date('d M, Y', strtotime($row->applicant_dob));
            })
            ->addColumn('pending_at', function ($row) {
                $tasks = getPrevNextTasks($row->proforma_id);
                if (isset($tasks['current']['tasks_id'])) {
                    $currentTask = Task::find($tasks['current']['tasks_id']);

                    //Here if currentTask is not null then we will return the user roles who can access this task
                    if ($currentTask) {
                        $roles = $currentTask->roles->pluck('role_name')->toArray();
                        return implode(" / ", $roles);
                    } else {
                        return 'N/A';
                    }
                }
                return 'N/A';
            })
            ->addColumn('remarks', function ($row) {
                /*
                return $row->proformaLogs()
                    ->whereIn('action_name', ['forwarded', 'rejected', 'reverted', 'completed'])
                    ->latest()
                    ->value('action_remark') ?? 'N/A';
                */
                $log = $row->proformaLogs()
                    ->whereIn('action_name', [
                        // 'verified', 
                        'forwarded',
                        'rejected',
                        'reverted',
                        'completed'
                    ])
                    ->latest()
                    ->first();
                if ($log) {
                    return (object)[
                        'remark' => $log->action_remark,
                        'date' => $log->created_at->format('d M, Y'),
                        'time' => $log->created_at->format('h:i A'),
                        'by' => $log->actionBy->fullname ?? 'N/A'
                    ];
                } else {
                    return null;
                }
            })
            ->addColumn('action', function ($row) {
                return "<div class='d-flex gap-2'>" .
                    "<a href='" . route('duties.proforma.view', $row->proforma_id) . "' class='btn btn-sm btn-primary view-btn'>View</a>" .
                    "</div>";
            })
            ->addColumn('overall_seniority_idx', function ($row) use ($overallSeniorityIndex) {
                return $overallSeniorityIndex[$row->proforma_id] ?? 'N/A';
            })
            ->addColumn('dept_seniority_idx', function ($row) {
                return Proforma::getDepartmentalSeniorityIndex($row->proforma_id);
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
}
