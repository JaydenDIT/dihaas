<?php

namespace App\Http\Controllers\Duties;

use App\Http\Controllers\Controller;
use App\Models\Caste;
use App\Models\Proforma;
use App\Models\Qualification;
use App\Models\Relationship;
use App\Models\State;
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
        return view('proforma.createProforma', compact('action', 'relationships', 'qualifications', 'castes', 'states'));
    }

    public function store(Request $request)
    {
        Proforma::create($request->all());
        return response()->json(['status' => 'success', 'message' => 'Proforma created successfully']);
    }

    public function update(Request $request, $id)
    {
        $proforma = Proforma::findOrFail($id);
        $proforma->update($request->all());
        return response()->json(['status' => 'success', 'message' => 'Proforma updated successfully']);
    }

    public function destroy($id)
    {
        Proforma::destroy($id);
        return response()->json(['status' => 'success', 'message' => 'Proforma deleted successfully']);
    }
}
