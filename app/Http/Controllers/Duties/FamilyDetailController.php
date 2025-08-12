<?php

namespace App\Http\Controllers\Duties;

use App\Http\Controllers\Controller;

use App\Models\FamilyDetail;
use Illuminate\Http\Request;

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
        FamilyDetail::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
