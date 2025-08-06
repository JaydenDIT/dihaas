<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function login()
    {
        $user = User::where('email', 'anand.y@nic.in')->first();
        Auth::login($user);
        return view('authentication.login');
    }
}
