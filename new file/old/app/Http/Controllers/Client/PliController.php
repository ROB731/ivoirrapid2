<?php

namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\PliFormRequest;
use App\Models\Coursier;
use App\Models\Destinataire;
use App\Models\Pli;
use App\Models\PliStatuerHistory;
use App\Models\Statuer;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class PliController extends Controller
{

    public function index(Request $request)
    {
        $query = Pli::with('destinataire', 'user'); // Charge les relations destinataire et user
    
        // Vérifie si l'utilisateur est un administrateur ou un utilisateur basique
        if (Auth::user()->role_as == '1') {
            // Pour l'administrateur : récupération de tous les plis
            $query->orderBy('created_at', 'desc');
        } else {
            // Pour un utilisateur basique : récupérer les plis de l'utilisateur authentifié
            $query->where('user_id', Auth::id())->orderBy('created_at', 'desc');
        }
    
        // Filtrer par nom de destinataire si spécifié
        if ($request->has('destinataire_name') && $request->destinataire_name != '') {
            $query->where('destinataire_name', 'like', '%' . $request->destinataire_name . '%');
        }
    
        // Filtrer par statut actuel si spécifié
        if ($request->has('status') && $request->status != '') {
            $query->whereHas('currentStatus', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->status . '%');
            });
        }
    
        // Si des dates sont spécifiées dans la requête, filtrer en conséquence
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        // Filtrer par coursier ramassage
        if ($request->has('coursier_ramassage') && $request->coursier_ramassage != '') {
            $query->whereHas('attributions', function ($q) use ($request) {
                $q->where('coursier_ramassage_id', $request->coursier_ramassage);
            });
        }
    
        // Filtrer par coursier dépôt
        if ($request->has('coursier_depot') && $request->coursier_depot != '') {
            $query->whereHas('attributions', function ($q) use ($request) {
                $q->where('coursier_depot_id', $request->coursier_depot);
            });
        }
    
        // Récupérer les plis filtrés
        $plis = $query->paginate(5);
    
        // Récupérer tous les destinataires pour la liste déroulante
        $destinataires = Pli::select('destinataire_name')->distinct()->get();
    
        // Récupérer tous les coursiers pour la liste déroulante
        $coursiers = Coursier::select('id')->get();
    
        // Retourner la vue appropriée selon le rôle de l'utilisateur
        if (Auth::user()->role_as == '1') {
            return view('admin.plis.index', compact('plis', 'destinataires', 'coursiers'));
        } else {
            return view('client.plis.index', compact('plis', 'destinataires'));
        }
    }
    






    // Afficher le formulaire de création de pli
    public function create()
{
    // Récupérer les destinataires pour l'utilisateur connecté
    $destinataires = Destinataire::where('user_id', Auth::id())->get();

    // Format 'yy-mm' de l'année et du mois en cours
    $yearMonth = Carbon::now()->format('y-m');

    // Trouver le dernier pli créé pour cet utilisateur avec un code commençant par 'yy-mm'
    $lastPli = Pli::where('user_id', Auth::id())
        ->where('code', 'like', "$yearMonth%")
        ->orderBy('created_at', 'desc')
        ->first();

    // Si un pli existe, incrémenter le numéro de suivi, sinon commencer à 1
    $nextNumber = $lastPli ? intval(substr($lastPli->code, -6)) + 1 : 1;

    // Compléter le numéro pour qu'il ait 6 chiffres
    $nextNumberPadded = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

    // Générer le code unique avec le format 'yy-mm-XXXXXX'
    $code = "$yearMonth-$nextNumberPadded";

    // La référence est vide pour l'instant, tu peux ajouter une logique pour la générer si nécessaire
    $reference = '';

    // Passer les destinataires, le code et la référence à la vue
    return view('client.plis.create', compact('destinataires', 'code', 'reference'));
}

    // Fonction show pour afficher un pli spécifique
    public function show($id)
    {
        $pli = Pli::findOrFail($id);

        // Vérifier si l'utilisateur est admin ou client
        if (auth()->user()->role_as == 1) {  // Admin
            return view('admin.plis.show', compact('pli'));
        } else {  // Client
            return view('client.plis.show', compact('pli'));
        }
    }

    // Stocker un nouveau pli
    public function store(PliFormRequest $request)
    {
        $data = $request->validated();
        $pli = new Pli();
        $pli->destinataire_id = $data['destinataire_id'];
        $pli->user_id = Auth::id();

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

        $pli->type = $data['type'];
        $pli->nombre_de_pieces = $data['nombre_de_pieces'];
        $pli->reference = implode(' | ', $data['reference']);

        $year = Carbon::now()->format('y');
        $month = Carbon::now()->format('m');
        $userAbreviation = strtolower(str_replace(' ', '_', $user->abreviation));

        $lastPli = Pli::where('user_id', Auth::id())
            ->where('code', 'like', "$userAbreviation-$year-$month%")
            ->orderBy('created_at', 'desc')
            ->first();

        $nextNumber = $lastPli ? intval(substr($lastPli->code, -6)) + 1 : 1;
        $nextNumberPadded = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        $pli->code = "$userAbreviation-$year-$month-$nextNumberPadded";
        
        // Ajout des dates d'attribution dynamiques pour ramassage et dépôt
        $pli->date_attribution_ramassage = Carbon::now();
        $pli->date_attribution_depot = Carbon::now(); // exemple d'ajout de 3 jours pour dépôt

        $pli->save();

        return redirect()->route('client.plis.index')->with('success', 'Pli créé avec succès.');
    }
   
    public function changeStatuer($pliId, Request $request)
{
    $pli = Pli::findOrFail($pliId);
    $statuer = Statuer::where('name', $request->statuer)->first();

    if (!$statuer) {
        return back()->withErrors(['statuer' => 'Statut invalide.']);
    }

    $raison = $request->statuer === 'annulé' ? $request->raison : null;

    PliStatuerHistory::create([
        'pli_id' => $pli->id,
        'statuer_id' => $statuer->id,
        'date_changement' => now(),
        'raison_annulation' => $raison,
    ]);

    return redirect()->route('admin.plis.index')->with('success', 'Statut mis à jour avec succès.');
}

    

    public function edit($id)
{
    $pli = Pli::findOrFail($id); // Récupérer le pli à éditer
    $destinataires = Destinataire::where('user_id', Auth::id())->get(); // Récupérer les destinataires de l'utilisateur authentifié

    return view('client.plis.edit', compact('pli', 'destinataires'));
}

public function update(PliFormRequest $request, $pli_id)
{
    // Valider les données envoyées dans la requête
    $data = $request->validated();

    // Trouver le pli à mettre à jour
    $pli = Pli::findOrFail($pli_id);

    // Mettre à jour les informations liées au destinataire
    $destinataire = Destinataire::findOrFail($data['destinataire_id']);
    $pli->destinataire_id = $destinataire->id;
    $pli->destinataire_name = $destinataire->name;
    $pli->destinataire_adresse = $destinataire->adresse;
    $pli->destinataire_telephone = $destinataire->telephone;
    $pli->destinataire_email = $destinataire->email;
    $pli->destinataire_zone = $destinataire->zone;
    $pli->destinataire_contact = $destinataire->contact;
    $pli->destinataire_autre = $destinataire->autre;

    // Mettre à jour les informations liées à l'utilisateur connecté (expéditeur)
    $user = Auth::user();
    $pli->user_id = $user->id;
    $pli->user_name = $user->name;
    $pli->user_adresse = $user->Adresse;
    $pli->user_telephone = $user->Telephone;
    $pli->user_email = $user->email;
    $pli->user_zone = $user->Zone;
    $pli->user_cellulaire = $user->Cellulaire;
    $pli->user_autre = $user->Autre;

    // Mettre à jour les informations du pli
    $pli->type = $data['type'];
    $pli->nombre_de_pieces = $data['nombre_de_pieces'];
    $pli->reference = implode(' | ', $data['reference']);

    // Générer un nouveau code si nécessaire
    $year = Carbon::now()->format('y');
    $month = Carbon::now()->format('m');
    $userName = strtolower(str_replace(' ', '_', $user->name));

    $lastPli = Pli::where('user_id', $user->id)
        ->where('code', 'like', "$userName-$year-$month%")
        ->orderBy('created_at', 'desc')
        ->first();

    $nextNumber = $lastPli ? intval(substr($lastPli->code, -6)) + 1 : 1;
    $nextNumberPadded = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    $pli->code = "$userName-$year-$month-$nextNumberPadded";

    // Mettre à jour les dates d'attribution
    $pli->date_attribution_ramassage = Carbon::now();
    $pli->date_attribution_depot = Carbon::now();

    // Sauvegarder les modifications dans la base de données
    $pli->save();

    // Rediriger avec un message de succès
    return redirect()->route('client.plis.index')->with('success', 'Pli mis à jour avec succès.');
}

public function destroy($pli_id){
    $pli = Pli::find($pli_id);
    if ($pli)
    {
        $pli->delete();
        return redirect()->route('client.plis.index')->with('success', 'Pli supprimé avec succès.');
    }
    else{
        return redirect()->route('client.plis.index')->with('error', 'Pli non trouvé');
    }
}

public function showAccuseDeRetour($pliId)
{
    $pli = Pli::with('statuerHistories.statuer')->findOrFail($pliId); // Charge l'historique avec le statut
    return view('admin.plis.accuse_retour', compact('pli'));
}
}
