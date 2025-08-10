<?php

namespace App\Http\Controllers\Duties;

use App\Http\Controllers\Controller;

use App\Http\Requests\FamilyRequest;
use App\Models\Family;

class FamilyController extends Controller
{
    public function store(FamilyRequest $request)
    {
        $family = Family::create($request->validated());
        return response()->json(['message' => 'Family member added successfully', 'data' => $family]);
    }

    public function update(FamilyRequest $request, $id)
    {
        $family = Family::findOrFail($id);
        $family->update($request->validated());
        return response()->json(['message' => 'Family member updated successfully', 'data' => $family]);
    }

    public function destroy($id)
    {
        $family = Family::findOrFail($id);
        $family->delete();
        return response()->json(['message' => 'Family member deleted successfully']);
    }

    public function index($proformaId)
    {
        $families = Family::with('relationship')
            ->where('proforma_id', $proformaId)
            ->get();
        return response()->json($families);
    }
}
