<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\CmisApiService;
use Illuminate\Http\Request;
use App\Http\Requests\CreateOfficialUserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function createOfficialUser(Request $request)
    {

        $role_ids                       = [1, 2, 3, 4, 5, 6, 8, 9];
        $roles                      = Role::whereIn('role_id', $role_ids)->get()->toArray();
        $ministry                   = CmisApiService::apiAdminDepartments();
        $departments                = CmisApiService::apiFieldDepartments();

        $departmentSigningAuthority = [];

        $data = [
            'roles'                      => $roles,
            'departments'                => $departments,
            'ministry'                   => $ministry,
            'departmentSigningAuthority' => $departmentSigningAuthority,
        ];
        //dd($data);
        return view('users.createOfficialUser', $data);
    }

    public function saveOfficialUser(CreateOfficialUserRequest $request)
    {

        $data = $request->only('fullname', 'mobile', 'email', 'password', 'role_id', 'dsg_serial_no', 'field_dept_cd');
        //dd($data);
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'User has been created successfully.',
                'user' => $user
            ]);
        }

        return redirect()->back()->with('success', 'User has been created successfully.');
    }

    //method to get only the official users
    public function getOfficialUsers()
    {
        $users = User::with('role')
            ->whereHas('role', function ($query) {
                $query->where('role_group', '!=', 'citizen');
            })->get();
        $title = "Official Users";
        return view('users.index', compact('title', 'users'));
    }

    //method to get only the citizen users
    public function getCitizenUsers()
    {
        $users = User::with('role')
            ->whereHas('role', function ($query) {
                $query->where('role_group', 'citizen');
            })->get();
        $title = "Citizen Users";
        return view('users.index', compact('title', 'users'));
    }
}
