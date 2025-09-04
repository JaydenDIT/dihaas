<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Proforma;
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

            //Finding total applications submitted          

            $totalAppCount = Proforma::where(function ($query) {
                $query->whereNotIn('mini_sequence', [
                    'step1-completed',
                    'step2-completed',
                    'step3-completed',
                ])->orWhereNull('mini_sequence');
            })->count();

            //Finding number of applications pending
            $pendingAppCount = Proforma::where(function ($query) {
                $query->whereNotIn('mini_sequence', [
                    'step1-completed',
                    'step2-completed',
                    'step3-completed',
                ])->orWhereNull('mini_sequence');
            })->where('proforma_status', '!=', 'completed')->count();


            //Finding number of applications pending
            $completedAppCount = Proforma::where('proforma_status', '=', 'completed')->count();

            //getting notifications
            $notifications = Notification::all();

            return view(
                'dashboard.superadmin-dashboard',
                compact(
                    'officialCount',
                    'citizenCount',
                    'totalAppCount',
                    'pendingAppCount',
                    'completedAppCount',
                    'notifications',
                )
            );
        }

        return redirect(route('tasks.performa.all'));
    }
}
