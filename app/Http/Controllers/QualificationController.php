<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Qualification;

class QualificationController extends Controller
{

    public function index()
    {
        $qualifications = Qualification::all();
        return view('qualifications.index', compact('qualifications'));
    }

    public function create()
    {
        return view('qualifications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'qualification_name' => 'required|string|max:255',
        ]);

        Qualification::create($request->only('qualification_name'));

        return redirect()->route('qualifications.index')
            ->with('success', 'Qualification added successfully.');
    }

    public function show($id)
    {
        $qualification = Qualification::findOrFail($id);
        return view('qualifications.show', compact('qualification'));
    }

    public function edit($id)
    {
        $qualification = Qualification::findOrFail($id);
        return view('qualifications.edit', compact('qualification'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'qualification_name' => 'required|string|max:255',
        ]);

        $qualification = Qualification::findOrFail($id);
        $qualification->update($request->only('qualification_name'));

        return redirect()->route('qualifications.index')
            ->with('success', 'Qualification updated successfully.');
    }

    public function destroy($id)
    {
        $qualification = Qualification::findOrFail($id);
        $qualification->delete();

        return redirect()->route('qualifications.index')
            ->with('success', 'Qualification deleted successfully.');
    }
}
