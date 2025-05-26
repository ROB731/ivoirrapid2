<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ForceLogout
{
    public function handle($request, Closure $next)
    {
        if (!Session::has('lastActivityTime')) {
            Auth::logout();
            Session::flush();
            return redirect('/login')->with('message', 'Vous avez été déconnecté après fermeture du navigateur.');
        }

        return $next($request);
    }
}
