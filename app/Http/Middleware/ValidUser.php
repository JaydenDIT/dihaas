<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role_ids)
    {
        //abort(403, $role_ids);
        $role_ids = explode('|',$role_ids );
         if ( ! in_array(Auth::user()->role_id, $role_ids )) {
            // Redirect...
            abort(403, "No authorization!");
            return redirect()->back()->withErrors(['msg' => 'Unauthorized user.']);
        }
 
        return $next($request);
    }
}
