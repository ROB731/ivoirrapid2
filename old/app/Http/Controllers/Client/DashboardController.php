<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Destinataire;
use App\Models\Pli;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
{
    $userId = auth()->id(); // Récupère l'ID de l'utilisateur connecté
    
    // Filtrer les données spécifiques à l'utilisateur connecté
    $destinataires = Destinataire::where('user_id', $userId)->get();
    $plis = Pli::where('user_id', $userId)->get();
    
    // Compter uniquement les données de l'utilisateur connecté
    $totalDestinataires = $destinataires->count();
    $totalPlis = $plis->count();
    
    return view('client.dashboard', compact('totalDestinataires', 'destinataires', 'totalPlis', 'plis'));
}

}
