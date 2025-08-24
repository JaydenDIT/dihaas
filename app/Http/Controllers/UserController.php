<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Services\CmisApiService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function createOfficialUser(Request $request)
    {
        if ($request->isMethod("POST")) {
            return '';
        }
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
}
