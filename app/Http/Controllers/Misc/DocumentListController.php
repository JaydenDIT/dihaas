<?php

namespace App\Http\Controllers\Misc;

use App\Http\Controllers\Controller;
use App\Models\DocumentList;
use Illuminate\Http\Request;

class DocumentListController extends Controller
{
    public function index()
    {
        $documents = DocumentList::all();
        return response()->json($documents);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_name' => 'required|string|max:255',
            'document_criteria' => 'required|string|max:255', // must match a config key
        ]);

        $document = DocumentList::create($validated);
        return response()->json($document, 201);
    }

    public function show($id)
    {
        $document = DocumentList::findOrFail($id);
        return response()->json($document);
    }

    public function update(Request $request, $id)
    {
        $document = DocumentList::findOrFail($id);

        $validated = $request->validate([
            'document_name' => 'sometimes|required|string|max:255',
            'document_criteria' => 'sometimes|required|string|max:255',
        ]);

        $document->update($validated);
        return response()->json($document);
    }

    public function destroy($id)
    {
        DocumentList::destroy($id);
        return response()->json(['message' => 'Document deleted successfully']);
    }
}
