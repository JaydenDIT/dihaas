<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Role;
use App\Models\TaskRoleMapping;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TaskController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('task.index', compact('roles'));
    }
    public function create()
    {
        $roles = Role::all();
        return view('task.create', compact('roles'));
    }

    public function ajaxlist(Request $request)
    {
        $tasks = Task::with('roles');

        return DataTables::of($tasks)
            ->addIndexColumn()
            ->addColumn('roles', function ($task) {
                return $task->roles->pluck('role_name')->implode(', ');
            })
            ->addColumn('tasks_duty_label', function ($task) {
                $map = [
                    'upload_casebody' => 'Case Body Upload and Forward',
                    'prepare_cert' => 'Preparation of Certificate',
                    'forward' => 'Check and Forward',
                    'approve' => 'Approve and Set Status',
                    'esign' => 'eSign the Certificate',
                    'hardcopy' => 'Hard Copy Dispatch',
                ];
                return $map[$task->tasks_duty] ?? $task->tasks_duty;
            })
            ->addColumn('action', function ($row) {
                $data = urlencode(json_encode([
                    "edit_url" => route('admin.task.edit', [$row->tasks_id]),
                    "delete_url" => route('admin.task.destroy', [$row->tasks_id]),
                ]));

                return "<div class='d-flex gap-2'>
                <button class='btn btn-sm btn-primary edit-button' data-row='{$data}'><i class='fa fa-edit'></i></button>
                <button class='btn btn-sm btn-danger delete-button' data-row='{$data}'><i class='fa fa-trash'></i></button>
            </div>";
            })

            ->rawColumns(['roles', 'action'])
            ->make(true);
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $roles = Role::all();

        // Get associated role IDs for checkbox pre-checking
        $task_roles = $task->roles()->pluck('roles.role_id')->toArray();

        return view('task.create', compact('task', 'roles', 'task_roles'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'tasks_name' => 'required|string',
            'tasks_description' => 'nullable|string',
            'tasks_duty' => 'required|string',
            'roles' => 'array'
        ]);

        $task = new Task();
        $task->tasks_name = $request->tasks_name;
        $task->tasks_description = $request->tasks_description;
        $task->tasks_duty = $request->tasks_duty;
        $task->create_by = Auth::id();
        $task->save();

        if ($request->has('roles')) {
            $task->roles()->sync($request->roles);
        }

        return response()->json(['status' => 'success', 'message' => 'Duty created successfully']);
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        $roles = Role::all();
        $task_roles = $task->roles()->pluck('roles.role_id')->toArray();

        return view('task.index', compact('task', 'roles', 'task_roles'));
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'tasks_name' => 'required|string',
            'tasks_description' => 'nullable|string',
            'tasks_duty' => 'required|string',
            'roles' => 'array'
        ]);

        $task->tasks_name = $request->tasks_name;
        $task->tasks_description = $request->tasks_description;
        $task->tasks_duty = $request->tasks_duty;
        $task->save();

        if ($request->has('roles')) {
            $task->roles()->sync($request->roles);
        }

        return response()->json(['status' => 'success', 'message' => 'Duty updated successfully']);
    }
}
