<?php
/**
 * Description: no Back on browser if not login
 * @author:  Konsam Malemngalba Singh
 * 
 **/

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        return $response->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
            ->header('Pragma','no-cache')
            ->header('Expires','0');

            // $response = $next($request);
            // $response->headers->set('Cache-Control', 'no-store');
            // return $response;
    }
}
