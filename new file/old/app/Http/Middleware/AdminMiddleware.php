<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {       
        if (Auth::check()) {
            if (Auth::user()->role_as == '1') {
                return $next($request);
            }
            else {
                return redirect('client/dashboard')->with('status', 'Access Denied! As you are not an Admin');
            }
    }
        else {
            return redirect('/login')->with('status', 'Please Login First');
        }
    }
}
