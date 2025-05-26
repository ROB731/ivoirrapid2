<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\PliFormRequest; // Assurez-vous d'avoir ce formulaire de demande
use App\Models\Destinataire; // Importez le modèle Destinataire
use App\Models\Pli;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PliController extends Controller
{
    // Afficher tous les plis pour l'administrateur ou les plis associés pour un utilisateur basique
    public function index()
    {
        if (Auth::user()->role_as == '1') { // Vérifie si l'utilisateur est un admin
            $plis = Pli::all(); // Admins voient tous les plis
            return view('admin.plis.index', compact('plis'));
        } else {
            $plis = Pli::where('user_id', Auth::id())->get(); // Utilisateurs basiques voient seulement leurs plis
            return view('client.plis.index', compact('plis'));
        }
    }

    // Afficher le formulaire de création de pli
    public function create()
{
    // Récupérer les destinataires associés à l'utilisateur
    $destinataires = Destinataire::where('user_id', Auth::id())->get();

    // Obtenir l'année et le mois actuel pour la génération du code
    $yearMonth = Carbon::now()->format('y-m');

    // Trouver le dernier pli créé par cet utilisateur pour la génération du prochain code
    $lastPli = Pli::where('user_id', Auth::id())
        ->where('code', 'like', "$yearMonth%")
        ->orderBy('created_at', 'desc')
        ->first();

    // Calculer le prochain numéro d'incrément
    $nextNumber = $lastPli ? intval(substr($lastPli->code, -6)) + 1 : 1;
    $nextNumberPadded = str_pad($nextNumber, 6, '0', STR_PAD_LEFT); // Ajouter des zéros pour avoir 6 chiffres

    // Générer le code
    $code = "$yearMonth-$nextNumberPadded";

    return view('client.plis.create', compact('destinataires', 'code'));
}


    // Stocker un nouveau pli
    public function store(PliFormRequest $request)
{
    // Validation des données
    $data = $request->validated();

    // Créer une nouvelle instance de Pli
    $pli = new Pli();
    $pli->destinataire_id = $data['destinataire_id'];
    $pli->user_id = Auth::id(); // Associer le pli à l'utilisateur connecté

    // Récupérer les informations du destinataire
    $destinataire = Destinataire::findOrFail($data['destinataire_id']);
    $pli->destinataire_name = $destinataire->name;
    $pli->destinataire_adresse = $destinataire->adresse;
    $pli->destinataire_telephone = $destinataire->telephone;
    $pli->destinataire_email = $destinataire->email;
    $pli->destinataire_zone = $destinataire->zone;
    $pli->destinataire_contact = $destinataire->contact;
    $pli->destinataire_autre = $destinataire->autre;

    // Récupérer les informations de l'expéditeur
    $user = Auth::user();
    $pli->user_name = $user->name;
    $pli->user_adresse = $user->Adresse;
    $pli->user_telephone = $user->Telephone;
    $pli->user_email = $user->email;
    $pli->user_zone = $user->Zone;
    $pli->user_cellulaire = $user->Cellulaire;
    $pli->user_autre = $user->Autre;

    // Informations sur le colis
    $pli->type = $data['type'];
    $pli->nombre_de_pieces = $data['nombre_de_pieces'];
    $pli->reference = $data['reference'];

    // Générer le code du pli
    $year = Carbon::now()->format('y'); // Année en deux chiffres
    $month = Carbon::now()->format('m'); // Mois en deux chiffres

    // Utiliser le nom de l'utilisateur pour le code
    $userName = strtolower(str_replace(' ', '_', $user->name)); // Remplacer les espaces par des underscores et mettre en minuscules

    // Trouver le dernier pli créé par cet utilisateur pour la génération du prochain code
    $lastPli = Pli::where('user_id', Auth::id())
        ->where('code', 'like', "$userName-$year-$month%")
        ->orderBy('created_at', 'desc')
        ->first();

    // Calculer le prochain numéro d'incrément
    $nextNumber = $lastPli ? intval(substr($lastPli->code, -6)) + 1 : 1;
    $nextNumberPadded = str_pad($nextNumber, 6, '0', STR_PAD_LEFT); // Ajouter des zéros pour avoir 6 chiffres

    // Générer le code final
    $pli->code = "$userName-$year-$month-$nextNumberPadded";

    // Enregistrer le pli
    $pli->save();

    return redirect()->route('client.plis.index')->with('success', 'Pli créé avec succès.');
}



    // Méthodes pour edit, update, et destroy peuvent être ajoutées ici si nécessaire
}
