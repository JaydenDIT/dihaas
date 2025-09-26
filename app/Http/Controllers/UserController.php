<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\CmisApiService;
use Illuminate\Http\Request;
use App\Http\Requests\CreateOfficialUserRequest;
use App\Http\Requests\UpdateOfficialUserRequest;
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

        //sorting ministry in alphabetical order
        usort($ministry, function ($a, $b) {
            return strcasecmp($a['adm_dept_desc'], $b['adm_dept_desc']);
        });

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
        $departments = CmisApiService::apiFieldDepartments();
        $belongingDepts = [];
        foreach ($departments as $dept) {
            $belongingDepts[$dept['field_dept_cd']] = $dept;
        }

        $users = User::with('role')
            ->whereHas('role', function ($query) {
                $query->where('role_group', '!=', 'citizen');
            })->get()->map(function (User $user) use ($belongingDepts) {
                //Finding concerned department
                $concernDept = $belongingDepts[$user->field_dept_cd] ?? [];
                $user->field_dept_desc = $concernDept['field_dept_desc'] ?? '--';
                $user->adm_dept_cd = $concernDept['adm_dept']['adm_dept_cd'] ?? '--';
                $user->adm_dept_desc = $concernDept['adm_dept']['adm_dept_desc'] ?? '--';

                //Getting post
                $availablePosts = !is_null($user->field_dept_cd) ? CmisApiService::apiAllPostUnderDepartment($user->field_dept_cd) : [];
                foreach ($availablePosts as $post) {
                    if ($post['dsg_srno'] == $user->dsg_serial_no) {
                        $user->dsg_desc = $post['dsg_desc'] ?? '--';
                        break;
                    }
                }
                return $user;
            });

        //dd($users);
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

    public function editOfficialUser($user_id)
    {
        $user                       = User::findOrFail($user_id);
        $role_ids                   = [1, 2, 3, 4, 5, 6, 8, 9, 999];
        $roles                      = Role::whereIn('role_id', $role_ids)->orderBy('role_name')->get()->toArray();

        $user_department            = !is_null($user->field_dept_cd) ? CmisApiService::apiFieldDepartments($user->field_dept_cd) : [];
        $adm_dept_cd                = $user_department['adm_dept_cd'] ?? '';

        $ministry                   = CmisApiService::apiAdminDepartments();
        $departments                = ($adm_dept_cd == "") ? CmisApiService::apiFieldDepartments() //List all the available departments if the admin department is not available
            : CmisApiService::apiAdminDepartments($adm_dept_cd)['field_dept']; //Other wise list the departments within the administrative department(ministry)
        $posts                      = !is_null($user->field_dept_cd) ? CmisApiService::apiAllPostUnderDepartment($user->field_dept_cd) : [];
        $departmentSigningAuthority = [];

        //sorting ministry in alphabetical order
        usort($ministry, function ($a, $b) {
            return strcasecmp($a['adm_dept_desc'], $b['adm_dept_desc']);
        });

        //sorting departments in alphabetical order
        usort($departments, function ($a, $b) {
            return strcasecmp($a['field_dept_desc'], $b['field_dept_desc']);
        });

        $data = [
            'roles'                      => $roles,
            'departments'                => $departments,
            'ministry'                   => $ministry,
            'departmentSigningAuthority' => $departmentSigningAuthority,
            'user' => $user,
            'posts' => $posts,
            'adm_dept_cd' => $adm_dept_cd,
        ];
        return view('users.edit', $data);
    }

    public function updateOfficialUser(UpdateOfficialUserRequest $request)
    {
        $data = $request->validated();
        //dd($data);

        $user = User::findOrFail($data['user_id']);

        $user->update($data);
        return redirect()->back()->with('success', 'User has been updated');
    }
}
