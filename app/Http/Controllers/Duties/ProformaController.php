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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProformaController extends Controller
{
    //
    public function create()
    {
        $action = "create";
        $relationships = Relationship::all();
        $qualifications = Qualification::all();
        $castes = Caste::all();
        $states = State::all();
        $proforma = new Proforma();

        $result = CmisApiService::apiAdminDepartments();
        $adminDepartments = $result; //test for real data uncomment below and comment this line
        // if ($result->failed()) {
        //     return response()->json(['message' => 'Failed to fetch departments'], $result->status());
        // }
        // $adminDepartments = $result->json();

        return view('proforma.createProforma', compact('action', 'relationships', 'qualifications', 'castes', 'states', 'adminDepartments', 'proforma'));
    }



    public function store(StoreProformaRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $data['create_by'] = 1; //for test
            $data['proforma_status'] = 'draft-step1'; //default status
            $proforma = Proforma::create($data);
            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => $data['create_by'],
                'action_name' => 'Proforma created save draft-step1',
                'action_remark' => 'Step 1 completed',
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

            $data['create_by'] = 1; //for test
            $proforma = Proforma::findOrFail($id);
            $proforma->update($data);
            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => $data['create_by'],
                'action_name' => 'Proforma update save draft-step1',
                'action_remark' => 'Step 1 updated',
            ]);
            DB::commit();
            return response()->json(['message' => 'Proforma updated successfully', 'proforma_id' => $proforma->proforma_id], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Proforma update failed', 'error' => $e->getMessage()], 422);
        }
    }


    public function completeFamilyDetail($id)
    {
        try {
            DB::beginTransaction();
            $proforma = Proforma::findOrFail($id);
            $familyMembers = FamilyDetail::where('proforma_id', $proforma->proforma_id)->count();
            if ($familyMembers == 0) {
                return response()->json(['message' => 'Add at least one family member'], 422);
            }
            $data['create_by'] = 1; //for test
            $data['proforma_status'] = 'draft-step2'; //default status
            $proforma->update($data);
            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => 1, //test
                'action_name' => 'Family Detail save draft-step2',
                'action_remark' => 'Step 2 completed',
            ]);
            DB::commit();
            return response()->json(['message' => 'Successfully save', 'proforma_id' => $proforma->proforma_id], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Saving failed', 'error' => $e->getMessage()], 422);
        }
    }

    public function completeUploadDocument($id)
    {
        try {
            DB::beginTransaction();
            $proforma = Proforma::findOrFail($id);

            $documents = DocumentRequirementService::getDocumentsWithRequirement($proforma);
            $requiredDocumentsLeft = $documents
                ->where('required', true)
                ->filter(function ($doc) {
                    return empty($doc->uploaded_file);
                })
                ->count();

            if ($requiredDocumentsLeft > 0) {
                return response()->json(['message' => 'Upload all required documents'], 422);
            }
            $data['create_by'] = 1; //for test
            $data['proforma_status'] = 'draft-step3'; //default status
            $proforma->update($data);

            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => 1, //test
                'action_name' => 'Proforma document save draft-step3',
                'action_remark' => 'Step 3 completed',
            ]);
            DB::commit();
            return response()->json(['message' => 'Successfully save', 'proforma_id' => $proforma->proforma_id], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Saving failed', 'error' => $e->getMessage()], 422);
        }
    }



    public function edit(Request $request, $id)
    {
        $action = "edit";
        $proforma = Proforma::findOrFail($id);
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
            'familyMembers'
        ));
    }

    public function destroy($id)
    {
        Proforma::destroy($id);
        return response()->json(['message' => 'Proforma deleted successfully'], 200);
    }
}
