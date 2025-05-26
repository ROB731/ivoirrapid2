<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Déconnecter l'utilisateur
     */
    public function logout()
    {
        // Déconnecte l'utilisateur
        Auth::logout();

        // Invalide la session
        request()->session()->invalidate();

        // Régénère le token CSRF
        request()->session()->regenerateToken();

        // Redirige ou renvoie une réponse
        return redirect('/login')->with('success', 'Vous êtes déconnecté.');
    }
}
