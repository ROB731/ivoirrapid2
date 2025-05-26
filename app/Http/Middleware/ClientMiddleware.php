<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ClientMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     */
            // public function handle(Request $request, Closure $next): Response //Ancienne fonction middleware enlever j05-05-2025
            // {
            //     if (Auth::check()) {
            //         if (Auth::user()->role_as == '0') { // Roles_as==0 signifie rien donc les actions du clien client, il
            //             return $next($request);
            //         }
            //         else {
            //             return redirect('admin/dashboard')->with('status', 'Access Denied! As you are not a Client'); //Si le client s'amuse à aller sur le dashbored j de l'admin on va simplement lui dire que l'acces a été réfusé
            //         }
            //     }
            //     else {
            //         return redirect('/login')->with('status', 'Please Login First');
            //     }
            // }




    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            return $next($request); // Supprime la vérification du rôle pour laisser tout le monde passer
        }
        else {
            return redirect('/login')->with('status', 'Please Login First');
        }
    }



    // public function handle(Request $request, Closure $next): Response    //Nouvelle fonction pour le middleware ajouter le 05-05-2025
    // {
    //     // Vérifier si la session est encore valide
    //     if (!Auth::check() || !Auth::user()) {
    //         Auth::logout();
    //         return redirect('/login')->with('status', 'Session Expired. Please Login Again.');
    //     }

    //     // Rafraîchir les données de l'utilisateur
    //     Auth::user()->refresh();

    //     // Vérifier si l'utilisateur est bien un client
    //     if (Auth::user()->role_as == '0') {
    //         return $next($request);
    //     }

    //     // Redirection vers le dashboard admin si ce n'est pas un client
    //     return redirect('admin/dashboard')->with('status', 'Access Denied! As you are not a Client');
    // }





}
