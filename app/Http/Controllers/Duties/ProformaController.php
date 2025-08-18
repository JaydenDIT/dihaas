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
use App\Services\CmisApiService;
use App\Services\DocumentRequirementService;
use App\Services\LogService;
use App\Services\ProcessMatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProformaController extends Controller
{
    //
    public function create()
    {
        $this->authorize('canPerform', [Proforma::class, 'client_form_submission']);
        $action = "create";
        $relationships = Relationship::all();
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
        $relationships = Relationship::all();
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

        //test for real data uncomment below and comment this line
        /*
        if ($result->failed()) {
            return response()->json(['message' => 'Failed to fetch departments'], $result->status());
        }
        $adminDepartments = $result->json();
        */

        if ($proforma->mini_sequence) { //if not null
            $current_step = $proforma->mini_sequence == "step1-completed" ? 2 : ($proforma->mini_sequence == "step2-completed" ? 3 : 4);
        } else {
            $current_step = 1;
        }

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
        $this->authorize('canView',  [$proforma, 'client_form_submission']);
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
}
