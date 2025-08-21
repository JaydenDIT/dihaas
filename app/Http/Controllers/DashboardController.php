<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function home(Request $request)
    {
        $user = Auth::user();
        if ($user->role_id == 77) {
            return view('dashboard');
        }
        return redirect(route('tasks.performa.all'));
    }
}
