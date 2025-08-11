<?php

namespace App\Http\Controllers\Duties;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProformaRequest;
use App\Models\Caste;
use App\Models\Proforma;
use App\Models\Qualification;
use App\Models\Relationship;
use App\Models\State;
use App\Services\CmisApiService;
use Illuminate\Http\Request;

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

        $response = CmisApiService::apiAdminDepartments();

        if ($response->failed()) {
            return response()->json(['message' => 'Failed to fetch departments'], $response->status());
        }
        $adminDepartments = $response->json();

        return view('proforma.createProforma', compact('action', 'relationships', 'qualifications', 'castes', 'states', 'adminDepartments'));
    }



    public function store(StoreProformaRequest $request)
    {
        try {
            $data = $request->validated();
            $data['create_by'] = auth()->id();
            Proforma::create($data);
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
            $data['create_by'] = auth()->id();
            $proforma->update($data);
            return response()->json(['message' => 'Proforma updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Proforma update failed', 'error' => $e->getMessage()], 422);
        }
    }

    public function destroy($id)
    {
        Proforma::destroy($id);
        return response()->json(['message' => 'Proforma deleted successfully'], 200);
    }
}
