<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Rediriger l'utilisateur après la réinitialisation du mot de passe.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return string
     */
    protected function redirectTo()
    {
        $user = Auth::user();  // Obtenez l'utilisateur actuellement authentifié

        // Rediriger selon le rôle de l'utilisateur
        if ($user->role_as == '1') {
            return route('admin.dashboard');  // Redirige vers le tableau de bord admin
        }

        if ($user->role_as == '0') {
            return route('client.dashboard');  // Redirige vers le tableau de bord client
        }

        return '/';  // Par défaut, redirige vers la page d'accueil si aucun rôle n'est défini
    }
}
