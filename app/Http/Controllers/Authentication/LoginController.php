<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

    public function login()
    {
        $user = User::where('email', 'lkonsam@gmail.com')->first();
        Auth::login($user);
        return redirect()->route('duties.proforma.create'); // or any route you have
    }
}
