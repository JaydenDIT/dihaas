<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $data      = $request->validated();
        $loginType = $data['loginType'];

        //getting user
        $user = User::where([
            'email' => $data['email'],
        ])->first();

        if (empty($user)) {
            return back()->withErrors([
                'email' => 'Sorry, your email is not registered in our app.',
            ]);
        }

        if ($loginType == "citizenLogin") {
            //for citizen role id should be 77
            //now check if the user is citizen or not
            if ($user->role_id != 77) {
                return back()->withErrors([
                    'loginType' => 'Sorry, you are not a citizen user.',
                ]);
            }
        } else {
            if ($user->role_id == 77) {
                return back()->withErrors([
                    'loginType' => 'Sorry, you are not a department user.',
                ]);
            }
        }

        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
