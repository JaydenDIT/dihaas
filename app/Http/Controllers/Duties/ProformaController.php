<?php

namespace App\Http\Controllers\Duties;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProformaRequest;
use App\Models\Caste;
use App\Models\District;
use App\Models\Proforma;
use App\Models\Qualification;
use App\Models\Relationship;
use App\Models\State;
use App\Models\SubDivision;
use App\Services\CmisApiService;
use App\Services\ProformaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $result = CmisApiService::apiAdminDepartments();
        $adminDepartments = $result; //test for real data uncomment below and comment this line
        // if ($result->failed()) {
        //     return response()->json(['message' => 'Failed to fetch departments'], $result->status());
        // }
        // $adminDepartments = $result->json();

        return view('proforma.createProforma', compact('action', 'relationships', 'qualifications', 'castes', 'states', 'adminDepartments'));
    }



    public function storeStep1(StoreProformaRequest $request)
    {
        try {
            $data = $request->validated();

            $data['create_by'] = 1; //for test
            $data['proforma_status'] = 'draft-step1'; //default status
            $proforma = Proforma::create($data);
            ProformaService::addLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => $data['create_by'],
                'action_name' => 'Proforma created save draft-step1',
                'action_remark' => 'Step 1 completed',
            ]);
            return response()->json(['message' => 'Proforma created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Proforma creation failed', 'error' => $e->getMessage()], 422);
        }
    }

    public function update(Request $request, $id)
    {


        try {
            $data = $request->validated();
            $proforma = Proforma::findOrFail($id);
            $data['create_by'] = 1; //for test
            $proforma->update($data);
            return response()->json(['message' => 'Proforma updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Proforma update failed', 'error' => $e->getMessage()], 422);
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
        $result = CmisApiService::apiAdminDepartments();
        $current_districts = District::where('state_id', $proforma->applicant_current_state_id)->get();
        $current_subdivisions = SubDivision::where('district_id', $proforma->applicant_current_district_id)->get();
        $permanent_districts = District::where('state_id', $proforma->applicant_permanent_state_id)->get();
        $permanent_subdivisions = SubDivision::where('district_id', $proforma->applicant_permanent_district_id)->get();

        $parentPostList = CmisApiService::apiAllPostUnderDepartment($proforma->deceased_field_dept_cd);
        $otherDepartList = CmisApiService::apiAdminDepartments($proforma->request_adm_dept_cd_3);
        $otherPostList = CmisApiService::apiAllPostUnderDepartment($proforma->request_field_dept_cd_3);


        $adminDepartments = $result; //test for real data uncomment below and comment this line
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
        ));
    }

    public function destroy($id)
    {
        Proforma::destroy($id);
        return response()->json(['message' => 'Proforma deleted successfully'], 200);
    }
}
