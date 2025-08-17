<?php

namespace App\Http\Controllers\Duties;

use App\Http\Controllers\Controller;

use App\Models\FamilyDetail;
use App\Models\Proforma;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FamilyDetailController extends Controller
{
    public function store(Request $request, $proforma_id)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'relationship_id' => 'required|exists:relationships,relationship_id',
            'family_detail_gender' => 'required|string',
            'dob' => 'nullable|date',
        ]);

        $member = FamilyDetail::create([
            'proforma_id' => $proforma_id,
            'fullname' => $request->fullname,
            'relationship_id' => $request->relationship_id,
            'gender' => $request->family_detail_gender,
            'dob' => $request->dob
        ]);

        $member->relationshipText = $member->relationship->relationship_name;

        return response()->json(['data' => urlencode(json_encode($member))], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'relationship_id' => 'required|exists:relationships,relationship_id',
            'family_detail_gender' => 'required|string',
            'dob' => 'nullable|date',
        ]);

        $member = FamilyDetail::findOrFail($id);
        $member->update([
            'fullname' => $request->fullname,
            'relationship_id' => $request->relationship_id,
            'gender' => $request->family_detail_gender,
            'dob' => $request->dob
        ]);

        $member->relationshipText = $member->relationship->relationship_name;
        $member->relationshipId = $member->relationship_id;

        return response()->json(['data' => urlencode(json_encode($member))], 200);
    }


    public function destroy($id)
    {


        try {
            DB::beginTransaction();
            $member = FamilyDetail::findOrFail($id);
            $proforma = Proforma::findOrFail($member->proforma_id);
            $data['mini_sequence'] = 'step1-completed';
            $proforma->update($data);
            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => Auth::user()->user_id,
                'action_name' => 'Family member deleted draft-step1',
                'action_remark' => 'Step 1 completed',
            ]);
            $member->delete();
            DB::commit();
            return response()->json(['message' => 'Deleted successfully', 'proforma_id' => $proforma->proforma_id], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Saving failed', 'error' => $e->getMessage()], 422);
        }
    }
}
