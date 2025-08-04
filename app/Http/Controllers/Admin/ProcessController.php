<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessStoreRequest;
use App\Models\Process;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class ProcessController extends Controller
{

    public function index()
    {
        $processes = Process::latest()->get();

        return view('Processes.index', compact('processes'));
    }

    public function ajaxlist(Request $request)
    {
        if ($request->ajax()) {
            $data = Process::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('process_criteria', function ($row) {
                    $criteria = json_decode($row->process_criteria, true);
                    if (!$criteria || !is_array($criteria)) return '<span class="text-muted">None</span>';

                    $html = '<ul class="mb-0 small">';
                    foreach ($criteria as $item) {
                        $html .= "<li><strong>{$item['field']}</strong> {$item['operation']} <em>{$item['value']}</em></li>";
                    }
                    $html .= '</ul>';
                    return $html;
                })
                ->addColumn('total_tasks', function ($row) {
                    // Replace with actual relationship if exists: $row->tasks->count()
                    return $row->total_tasks ?? 0;
                })
                ->addColumn('action', function ($row) {
                    $data = urlencode(json_encode([
                        "edit_url" =>  route('admin.process.edit', [$row->process_id]),
                        "delete_url" =>  route('admin.process.destroy', [$row->process_id]),
                    ]));
                    return "<div class='d-flex gap-2'>
                            <button class='btn btn-sm btn-primary edit-button' data-row='{$data}'><i class='fa fa-edit'></i></button>
                            <button class='btn btn-sm btn-danger delete-button' data-row='{$data}'><i class='fa fa-trash'></i></button>
                        </div>";
                })
                ->rawColumns(['process_criteria', 'action'])
                ->make(true);
        }
    }



    public function destroy($id)
    {
        try {
            $process = Process::findOrFail($id);
            $process->delete();

            return response()->json(['message' => 'Deleted successfully']);
        } catch (Exception $e) {
            Log::error('Process delete failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Unable to delete process'], 500);
        }
    }



    public function create()
    {
        $existingProcesses = Process::all();

        $criteria = Storage::disk('local')->exists('criteria.json')
            ? json_decode(Storage::disk('local')->get('criteria.json'), true)
            : [];

        return view('Processes.create', [
            'existingProcesses' => $existingProcesses,
            'criteria' => $criteria,
            'action' => "create"
        ]);
    }
    public function edit($id)
    {
        $process = Process::findOrFail($id);
        $criteria = Storage::disk('local')->exists('criteria.json')
            ? json_decode(Storage::disk('local')->get('criteria.json'), true)
            : [];
        $existingProcesses = Process::where('process_id', '!=', $id)->get(); // to avoid matching with itself

        return view('Processes.create', [ // reuse same Blade
            'action' => 'update',
            'process' => $process,
            'criteria' => $criteria,
            'existingProcesses' => $existingProcesses,
            'updateUrl' => route('admin.process.update', $process->process_id),
        ]);
    }

    public function store(ProcessStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            // Normalize and encode the criteria
            $normalized = collect($request->process_criteria)
                ->sortBy([
                    ['field', 'asc'],
                    ['operation', 'asc'],
                    ['value', 'asc'],
                ])
                ->values()
                ->toJson(JSON_UNESCAPED_UNICODE);

            // Check for existing identical process
            $duplicate = Process::where('process_criteria', $normalized)->first();
            if ($duplicate) {
                return response()->json([
                    'error' => 'A process with the same criteria already exists.'
                ], 409);
            }

            // Create new process
            Process::create([
                'process_name' => $request->process_name,
                'process_description' => $request->process_description,
                'process_criteria' => $normalized,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Process Created Successfully'
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Process creation failed', ['error' => $e->getMessage()]);

            return response()->json([
                'error' => 'An unexpected error occurred during process creation.'
            ], 500);
        }
    }




    public function update(ProcessStoreRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $normalized = collect($request->process_criteria)
                ->sortBy([['field', 'asc'], ['operation', 'asc'], ['value', 'asc']])
                ->values()
                ->toJson(JSON_UNESCAPED_UNICODE);

            // Check for duplicates (excluding self)
            $duplicate = Process::where('process_id', '!=', $id)
                ->where('process_criteria', $normalized)
                ->first();

            if ($duplicate) {
                return response()->json([
                    'error' => 'Another process with the same criteria already exists.'
                ], 409);
            }

            $process = Process::findOrFail($id);
            $process->update([
                'process_name' => $request->process_name,
                'process_description' => $request->process_description,
                'process_criteria' => $normalized,
            ]);

            DB::commit();

            return response()->json(['message' => 'Process updated successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Process update failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Update failed'], 500);
        }
    }
}
