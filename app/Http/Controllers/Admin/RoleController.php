<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        return view('Role.index');
    }

    public function ajaxlist(Request $request)
    {
        $data = Role::with('duties')->get(); // eager load duties

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('duties', function ($row) {
                return $row->duties->pluck('tasks_name')->implode(', ');
            })
            ->addColumn('action', function ($row) {
                $data = urlencode(json_encode([
                    "edit_data" =>  ["role_id" => $row->role_id, "role_name" => $row->role_name],
                    "delete_url" =>  route('admin.role.destroy', $row->role_id),
                ]));
                return "<div class='d-flex gap-2'>
                            <button class='btn btn-sm btn-primary edit-btn' data-row='{$data}'><i class='fa fa-edit'></i></button>
                            <button class='btn btn-sm btn-danger delete-btn' data-row='{$data}'><i class='fa fa-trash'></i></button>
                        </div>";
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function store(Request $request)
    {
        $request->validate(['role_name' => 'required|string|max:255']);
        $role = Role::create($request->only('role_name'));
        return response()->json(['message' => 'Role created', 'data' => $role]);
    }

    public function show($id)
    {
        return Role::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['role_name' => 'required|string|max:255']);
        $role = Role::findOrFail($id);
        $role->update($request->only('role_name'));
        return response()->json(['message' => 'Role updated']);
    }

    public function destroy($id)
    {
        Role::destroy($id);
        return response()->json(['message' => 'Role deleted']);
    }
}
