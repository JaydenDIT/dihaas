<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class PasswordChangeController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showChangePasswordGet()
    {
        return view('auth.passwords.change-password');
    }

    public function changePasswordPost(Request $request)
    {
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error", "Your current password does not matches with the password.");
        }

        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            // Current password and new password same
            return redirect()->back()->with("error", "New Password cannot be same as your current password.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:8|confirmed',
        ]);

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
        if (Auth::user()->role_id == 77) {           
            Session::flush();
            Auth::logout();
            return redirect("loginApplicant")->with("success", "Password successfully changed!");
        } if (Auth::user()->role_id == 999) {           
            Session::flush();
            Auth::logout();
            return redirect("superadmin")->with("success", "Password successfully changed!");; 
        }else {
            Session::flush();
            Auth::logout();
            return redirect('login')->with("success", "Password successfully changed!");
        }
        // return redirect()->back()->with("success", "Password successfully changed!");
    }
}
