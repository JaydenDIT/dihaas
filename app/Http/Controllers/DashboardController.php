<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function home(Request $request)
    {

        $user = Auth::user();
        if ($user->role->role_group == "citizen") {
            return view('dashboard.citizen-dashboard');
        } else if ($user->role->role_group == "superadmin") {
            $officialCount = User::whereNotIn('role_id', Role::where('role_group', 'citizen')->pluck('role_id'))->count();
            $citizenCount = User::whereIn('role_id', Role::where('role_group', 'citizen')->pluck('role_id'))->count();
            return view('dashboard.superadmin-dashboard', compact('officialCount', 'citizenCount'));
        }

        return redirect(route('tasks.performa.all'));
    }
}
