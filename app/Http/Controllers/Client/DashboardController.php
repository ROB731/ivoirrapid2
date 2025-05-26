<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use App\Models\Pli;
use App\Models\Destinataire;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    $nombreDestinataires = Destinataire::count(); // Récupère le nombre total de tous les destinataires

     //Bottom to add new functions :)

     $derniersPlis = Pli::orderBy('created_at', 'desc')->limit(5)->get();


     // Nombre de plis créés par période
    //  $userId = auth()->id(); // ✅ Récupérer l'ID de l'utilisateur connecté

    //  // Récupérer les derniers plis créés par l'utilisateur
    //  $derniersPlis = Pli::where('user_id', $userId) // ✅ Filtrer par l'utilisateur
    //      ->orderBy('created_at', 'desc')
    //      ->with('destinataire') // Associer les informations du destinataire
    //      ->paginate(5);

    //  $aujourdhui = Pli::whereDate('created_at', Carbon::today())->count();
    //  $hier = Pli::whereDate('created_at', Carbon::yesterday())->count();
    //  $semaineDerniere = Pli::whereBetween('created_at', [Carbon::now()->startOfWeek()->subWeek(), Carbon::now()->startOfWeek()])->count();
    //  $moisDernier = Pli::whereBetween('created_at', [Carbon::now()->startOfMonth()->subMonth(), Carbon::now()->startOfMonth()])->count();



        $userId = auth()->id(); // ✅ Récupérer l'ID de l'utilisateur connecté

        // Récupérer les derniers plis créés par l'utilisateur
        $derniersPlis = Pli::where('user_id', $userId) // ✅ Filtrer par l'utilisateur
            ->orderBy('created_at', 'desc')
            ->with('destinataire') // Associer les informations du destinataire
            ->paginate(5);

        // Récupérer les statistiques des plis créés par cet utilisateur
        $aujourdhui = Pli::where('user_id', $userId)->whereDate('created_at', Carbon::today())->count();
        $hier = Pli::where('user_id', $userId)->whereDate('created_at', Carbon::yesterday())->count();
        $semaineDerniere = Pli::where('user_id', $userId)->whereBetween('created_at', [Carbon::now()->startOfWeek()->subWeek(), Carbon::now()->startOfWeek()])->count();
        $moisDernier = Pli::where('user_id', $userId)->whereBetween('created_at', [Carbon::now()->startOfMonth()->subMonth(), Carbon::now()->startOfMonth()])->count();

     // Autres
      // Récupérer les 5 derniers plis avec destinataire
    $derniersPlis = Pli::orderBy('created_at', 'desc')
        ->limit(1)
        ->with('destinataire') // Associer les informations du destinataire
        ->paginate(5);

    // Récupérer les statistiques des plis créés
    $aujourdhui = Pli::whereDate('created_at', Carbon::today())->count();
    $hier = Pli::whereDate('created_at', Carbon::yesterday())->count();
    $semaineDerniere = Pli::whereBetween('created_at', [Carbon::now()->startOfWeek()->subWeek(), Carbon::now()->startOfWeek()])->count();
    $moisDernier = Pli::whereBetween('created_at', [Carbon::now()->startOfMonth()->subMonth(), Carbon::now()->startOfMonth()])->count();


    //-------------


    return view('client.dashboard', compact('totalDestinataires', 'destinataires', 'nombreDestinataires', 'totalPlis', 'plis','derniersPlis', 'aujourdhui', 'hier', 'semaineDerniere', 'moisDernier'));
}

}
