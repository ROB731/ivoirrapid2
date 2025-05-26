<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attribution;
use App\Models\Coursier;
use App\Models\Pli;
use App\Models\PliStatuerHistory;
use App\Models\Statuer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttributionController extends Controller
{
    // Afficher la liste des plis à attribuer
    public function index()
    {
        // Récupérer les plis qui n'ont pas de coursier de ramassage ou de dépôt attribué
        $plies = Pli::where(function ($query) {
            $query->whereDoesntHave('attributions', function ($subQuery) {
                $subQuery->whereNotNull('coursier_ramassage_id')
                         ->whereNotNull('coursier_depot_id');
            });
        })->get();

        // Ajouter des dates d'attribution dynamiques pour chaque pli
        // Ajouter les dates d'attribution aux plis
    foreach ($plies as $pli) {
        // Vérifier si l'attribution de ramassage existe et ajouter la date
        $pli->date_attribution_ramassage = $pli->attributions->whereNotNull('date_ramassage')->first()->date_ramassage ?? null;
        // Vérifier si l'attribution de dépôt existe et ajouter la date
        $pli->date_attribution_depot = $pli->attributions->whereNotNull('date_depot')->first()->date_depot ?? null;
    }

        return view('admin.attributions.index', compact('plies'));
    }

    // Attribuer un pli à un coursier pour le ramassage ou le dépôt
    public function attribuerPli($pliId, Request $request)
    {
        $pli = Pli::find($pliId);
        if (!$pli) {
            return redirect()->route('admin.attributions.index')->with('error', 'Pli non trouvé');
        }

        // Type d'attribution : ramassage ou dépôt
        $type = $request->input('type');
        if (!in_array($type, ['ramassage', 'depot'])) {
            return redirect()->route('admin.attributions.index')->with('error', 'Type d\'attribution invalide');
        }

        // Vérifier si une attribution existe déjà pour ce pli avec le même type
        $existingAttribution = Attribution::where('pli_id', $pli->id)
                                          ->where(function ($query) use ($type) {
                                              if ($type == 'ramassage') {
                                                  $query->whereNotNull('coursier_ramassage_id');
                                              } else {
                                                  $query->whereNotNull('coursier_depot_id');
                                              }
                                          })
                                          ->exists();

        if ($existingAttribution) {
            return redirect()->route('admin.attributions.index')->with('error', "Le pli a déjà été attribué pour le type $type.");
        }

        // Déterminer la zone en fonction du type d'attribution
        $zone = ($type == 'ramassage') ? $pli->user_Zone : $pli->destinataire_zone;

        // Rechercher des coursiers disponibles dans la zone spécifique
        $coursiersDisponibles = Coursier::where('zones', $zone)->get();

        // Vérifier si des coursiers sont disponibles dans la zone
        if ($coursiersDisponibles->isEmpty()) {
            // Si aucun coursier disponible dans la zone, récupérer tous les autres coursiers
            $coursiersDisponibles = Coursier::where('zones', '!=', $zone)->get();

            if ($coursiersDisponibles->isEmpty()) {
                return redirect()->route('admin.attributions.index')
                    ->with('error', 'Aucun coursier disponible pour l\'attribution.');
            }

            // Afficher un message d'avertissement pour l'utilisateur
            return view('admin.attributions.alternative', compact('pli', 'type', 'coursiersDisponibles'));
        }

        // Sélectionner un coursier dans la zone, s'il y en a
        $coursier = $coursiersDisponibles->first();

        // Créer ou récupérer une attribution pour le pli
        $attribution = Attribution::firstOrNew(['pli_id' => $pli->id]);

        // Affecter le coursier au ramassage ou au dépôt
        if ($type == 'ramassage') {
            $attribution->coursier_ramassage_id = $coursier->id;
            $attribution->date_attribution_ramassage = Carbon::now();  // Enregistrer la date d'attribution du ramassage
        } else {
            $attribution->coursier_depot_id = $coursier->id;
            $attribution->date_attribution_depot = Carbon::now();  // Enregistrer la date d'attribution du dépôt
        }

        // Sauvegarder l'attribution
        $attribution->save();

        return redirect()->route('admin.attributions.index')->with('success', "Pli attribué avec succès pour le $type.");
    }

    // Confirmer l'attribution du pli
    public function confirmerAttribution($pliId, $type, Request $request)
    {
        $pli = Pli::find($pliId);
        if (!$pli) {
            return redirect()->route('admin.attributions.index')->with('error', 'Pli non trouvé');
        }

        $coursierId = $request->input('coursier_id');
        $attribution = Attribution::firstOrNew(['pli_id' => $pli->id]);

        // Associer le coursier choisi au type d'attribution
        if ($type == 'ramassage') {
            $attribution->coursier_ramassage_id = $coursierId;
            $attribution->date_attribution_ramassage = Carbon::now(); // Ajouter la date de ramassage
        } else {
            $attribution->coursier_depot_id = $coursierId;
            $attribution->date_attribution_depot = Carbon::now(); // Ajouter la date de dépôt
        }

        $attribution->save();

        return redirect()->route('admin.attributions.index')->with('success', "Pli attribué avec succès pour le $type.");
    }


public function impression(Request $request)
{
    // Récupérer tous les coursiers
    $coursiers = Coursier::all();

    // Requête de base pour récupérer les plis ayant une attribution de ramassage et le statut "en attente"
    $plisQuery = Pli::whereHas('attributions', function ($query) {
        $query->whereNotNull('coursier_ramassage_id');
    })
    ->whereHas('statuerHistories', function ($query) {
        $query->where('statuer_id', 1)
              ->whereRaw('pli_statuer_history.id = (
                  SELECT MAX(id)
                  FROM pli_statuer_history AS subquery
                  WHERE subquery.pli_id = pli_statuer_history.pli_id
              )');
    });

    // Appliquer le filtre si un coursier est sélectionné
    if ($request->has('coursier_id') && $request->input('coursier_id') != '') {
        $plisQuery->whereHas('attributions', function ($query) use ($request) {
            $query->where('coursier_ramassage_id', $request->input('coursier_id'));
        });
    }

    // Récupérer les plis filtrés
    $plis = $plisQuery->get();

    // Passer les plis et les coursiers à la vue
    return view('admin.attributions.impression', compact('plis', 'coursiers'));
}
public function filtrerParCoursierDepot(Request $request)
{
    // Récupérer tous les coursiers
    $coursiers = Coursier::all();

    // Requête de base pour récupérer les plis ayant une attribution de ramassage et le statut "en attente"
    $plisQuery = Pli::whereHas('attributions', function ($query) {
        $query->whereNotNull('coursier_depot_id');
    })
    ->whereHas('statuerHistories', function ($query) {
        $query->where('statuer_id', 2)
              ->whereRaw('pli_statuer_history.id = (
                  SELECT MAX(id)
                  FROM pli_statuer_history AS subquery
                  WHERE subquery.pli_id = pli_statuer_history.pli_id
              )');
    });

    // Appliquer le filtre si un coursier est sélectionné
    if ($request->has('coursier_id') && $request->input('coursier_id') != '') {
        $plisQuery->whereHas('attributions', function ($query) use ($request) {
            $query->where('coursier_depot_id', $request->input('coursier_id'));
        });
    }

    // Récupérer les plis filtrés
    $plis = $plisQuery->get();

    // Passer les plis et les coursiers à la vue
    return view('admin.attributions.depot', compact('coursiers', 'plis'));
}  

}
