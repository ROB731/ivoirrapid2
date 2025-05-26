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
                    // public function handle(Request $request, Closure $next): Response    // Ancienne fonction midllware changer le 05-05-2025
                    // {
                    //     if (Auth::check()) {
                    //         if (Auth::user()->role_as == '1') {  // Role_as = 1 pour les administrateur si il s'agit d'un administrateur
                    //             return $next($request); // On peut procéder au requete suivante
                    //         }
                    //         else {
                    //             return redirect('client/dashboard')->with('status', 'Access Denied! As you are not an Admin'); // Ou bien vous êtes tout simplement un client, aller sur le dashbord client
                    //             // Si non on va dire que vous n'etes pas un administrateur
                    //             //Si l'admin va sur le compte du clientà, on va lui dire que l'accès lui a été réfusé
                    //         }
                    // }
                    //     else {
                    //         return redirect('/login')->with('status', 'Please Login First'); // On va lui demander de se connecter avant de faire tout autre chose
                    //     }
                    // }


                    public function handle(Request $request, Closure $next): Response   // Nouvelle fonction middlware admin ajouter le 05-05-2025
                    {
                        // Vérifier si la session est encore valide
                        if (!Auth::check() || !Auth::user()) {
                            Auth::logout();
                            return redirect('/login')->with('status', 'Session Expired. Please Login Again.');
                        }

                        // Rafraîchir les données de l'utilisateur
                        Auth::user()->refresh();

                        // Vérifier si l'utilisateur est bien un administrateur
                        if (Auth::user()->role_as == '1') {
                            return $next($request);
                        }

                        // Redirection vers le dashboard client si ce n'est pas un admin
                        return redirect('client/dashboard')->with('status', 'Access Denied! As you are not an Admin');
                    }



}
