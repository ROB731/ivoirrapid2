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

                // Pour l'attribution automatique

                


                // ,/pOUR Attribution automatique

















                // La fonction index ici
                public function index()
                {
                    // 🔹 Récupérer les zones des expéditeurs et des destinataires des plis non attribués
                    $zonesExpediteurs = Pli::pluck('user_zone')->unique()->filter(fn($zone) => !is_null($zone) && $zone !== '000') ?? collect([]);
                    $zonesDestinataires = Pli::pluck('destinataire_zone')->unique()->filter(fn($zone) => !is_null($zone) && $zone !== '000') ?? collect([]);

                    // 🔹 Sélectionner les coursiers disponibles
                    $coursiersDisponibles = Coursier::where(function ($query) use ($zonesExpediteurs, $zonesDestinataires) {
                        if ($zonesExpediteurs->isNotEmpty()) {
                            $query->whereIn('zones', $zonesExpediteurs);
                        }
                        if ($zonesDestinataires->isNotEmpty()) {
                            $query->orWhereIn('zones', $zonesDestinataires);
                        }
                    })->get(['id', 'nom', 'prenoms', 'zones', 'telephone']) ?? collect([]);

                    // 🔹 Sélectionner TOUS les coursiers comme alternative
                    $coursiersAlternatifs = Coursier::all(['id', 'nom', 'prenoms', 'zones', 'telephone']) ?? collect([]);

                                                // 🔹 Plis jamais attribués (aucun ramassage ni dépôt)
                        $plies = Pli::whereDoesntHave('attributions')->get() ?? collect([]);

                        // 🔹 Plis ramassés mais pas encore déposés
                        $plisRamassageList = Pli::whereHas('attributions', function ($query) {
                            $query->whereNotNull('coursier_ramassage_id')->whereNull('coursier_depot_id');
                        })->get() ?? collect([]);

                        // 🔹 Plis déposés (ont été ramassés et sont maintenant en dépôt)
                        $plisDepotList = Pli::whereHas('attributions', function ($query) {
                            $query->whereNotNull('coursier_depot_id');
                        })->get() ?? collect([]);

                        // 🔹 Liste complète des plis attribués (ramassés et/ou déposés)
                        $plisAttribuesList = Pli::whereHas('attributions')->paginate(10) ?? collect([]);



                    // 🔹 Ajout des dates d'attribution aux plis
                    $plies = $plies->map(function ($pli) {
                        $pli->date_attribution_ramassage = optional($pli->attributions->whereNotNull('date_ramassage')->first())->date_ramassage ?? 'Non attribué';

                        $pli->date_attribution_depot = optional($pli->attributions->whereNotNull('date_depot')->first())->date_depot ?? 'Non attribué';
                        return $pli;
                    });

                    // 🔹 Déco
                    // 🔹 Plis jamais attribués (aucun ramassage ni dépôt)


                        $plisNonAttribues = Pli::whereDoesntHave('attributions')->count() ?? 0;

                        // 🔹 Plis ramassés mais pas encore déposés
                        $plisRamassage = Pli::whereHas('attributions', function ($query) {
                            $query->whereNotNull('coursier_ramassage_id')->whereNull('coursier_depot_id');
                        })->count() ?? 0;

                        // 🔹 Plis déposés (ont été ramassés et sont maintenant en dépôt)
                        $plisDepot = Pli::whereHas('attributions', function ($query) {
                            $query->whereNotNull('coursier_depot_id');
                        })->count() ?? 0;

                        // 🔹 Plis complètement traités (ramassés et déposés)
                        $plisTraites = Pli::whereHas('attributions', function ($query) {
                            $query->whereNotNull('coursier_ramassage_id')->whereNotNull('coursier_depot_id');
                        })->count() ?? 0;


                    // Calcul

                    // 🔹 Ajout du nombre de livraisons attribuées pour chaque coursier
                    $coursiersDisponibles = $coursiersDisponibles->map(function ($coursier) {
                        $coursier->nombre_plis_attribues = Attribution::where('coursier_ramassage_id', $coursier->id)
                            ->orWhere('coursier_depot_id', $coursier->id)
                            ->count() ?? 0;
                        return $coursier;
                    });

                    $coursiersAlternatifs = $coursiersAlternatifs->map(function ($coursier) {
                        $coursier->nombre_plis_attribues = Attribution::where('coursier_ramassage_id', $coursier->id)
                            ->orWhere('coursier_depot_id', $coursier->id)
                            ->count() ?? 0;
                        return $coursier;
                    });

                    $nombreCoursiers = Coursier::count() ?? 0;

                    // 🔥 Envoi des données à la vue
                    return view('admin.attributions.index', compact(
                        'plies', 'plisRamassageList', 'plisDepotList', 'plisAttribuesList',
                        'plisNonAttribues', 'plisRamassage', 'plisDepot', 'plisTraites',
                        'coursiersDisponibles', 'coursiersAlternatifs', 'nombreCoursiers'
                    ));
                }


//Fin de la fonction index

        public function selectionnerCoursiersDisponibles()
        {
            //  Récupérer les zones des expéditeurs et des destinataires des plis non attribués
            $zonesExpediteurs = Pli::pluck('user_zone')->unique()->filter(function ($zone) {
                return !is_null($zone) && $zone !== '000'; // On exclut les valeurs "000" et NULL
            });

            $zonesDestinataires = Pli::pluck('destinataire_zone')->unique()->filter(function ($zone) {
                return !is_null($zone) && $zone !== '000'; // On exclut les valeurs "000" et NULL
            });

            //  Sélectionner les coursiers dont la zone correspond à l'une des deux
            $coursiersDisponibles = Coursier::where(function ($query) use ($zonesExpediteurs, $zonesDestinataires) {
                if ($zonesExpediteurs->isNotEmpty()) {
                    $query->whereIn('zones', $zonesExpediteurs);
                }
                if ($zonesDestinataires->isNotEmpty()) {
                    $query->orWhereIn('zones', $zonesDestinataires);
                }
            })->get();

            //  Sélectionner TOUS les coursiers comme alternative
            $coursiersAlternatifs = Coursier::all();

            //  Envoyer les données à la vue
            return view('admin.attributions.index', compact('coursiersDisponibles', 'coursiersAlternatifs'));
        }









                                public function attribuerEnGroupe(Request $request)
                    {
                        $validated = $request->validate([
                            'plies' => 'required|array|min:1',
                            'plies.*' => 'exists:plies,id',
                            'type' => 'required|in:ramassage,depot',
                            'coursier_id' => $request->type === 'ramassage' ? 'required|exists:coursiers,id' : 'nullable',
                            'coursier_idAt' => $request->type === 'depot' ? 'required|exists:coursiers,id' : 'nullable',
                        ]);

                        foreach ($validated['plies'] as $pliId) {
                            $pli = Pli::findOrFail($pliId);

                            // 🔹 Si c'est un ramassage, on crée une nouvelle attribution
                            if ($validated['type'] === 'ramassage') {
                                $pli->attributions()->create([
                                    'coursier_ramassage_id' => $validated['coursier_id'],
                                    'date_attribution_ramassage' => Carbon::today(),
                                ]);
                            }
                            // 🔹 Si c'est un dépôt, on met à jour l’enregistrement existant
                            elseif ($validated['type'] === 'depot') {
                                $attribution = $pli->attributions()->whereNotNull('coursier_ramassage_id')->first();

                                if ($attribution) {
                                    $attribution->update([
                                        'coursier_depot_id' => $validated['coursier_idAt'],
                                        'date_attribution_depot' => Carbon::today(),
                                    ]);
                                } else {
                                    return back()->with('error', 'Impossible d’attribuer au dépôt : Aucun ramassage trouvé !');
                                }
                            }
                        }

                        return redirect()->back()->with('success', 'Les plis ont été attribués avec succès !');
                    }



         // Debut test attribuer groupe fin


        //Test Attribuer groupe



    // Attribuer un pli à un coursier pour le ramassage ou le dépôt ---------------------------------

    public function attribuerPli($pliId, Request $request)   // Celui la c'est pour le plis unique
    {
        $pli = Pli::find($pliId);   //Ecoute va recherche le id du pli
        if (!$pli) {
            return redirect()->route('admin.attributions.index')->with('error', 'Pli non trouvé'); // Si tu n'as pas troubvé retourne pli non trouveé
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
                $coursiers = Coursier::all()->filter(function ($coursier) use ($zone) {
                $zonesCoursier = is_array($coursier->zones) ? $coursier->zones : explode(',', $coursier->zones);
                return in_array($zone, $zonesCoursier);
            });

        // Vérifier si des coursiers sont disponibles dans la zone
        if ($coursiers->isEmpty()) {
            // Si aucun coursier disponible dans la zone, récupérer tous les autres coursiers
            $coursiers = Coursier::whereRaw("NOT FIND_IN_SET(?, zones)", [$zone])->get();

            if ($coursiers->isEmpty()) {
                return redirect()->route('admin.attributions.index')
                    ->with('error', 'Aucun coursier disponible pour l\'attribution.');
            }

            // Afficher un message d'avertissement pour l'utilisateur
            return view('admin.attributions.alternative', compact('pli', 'type', 'coursiers'));
        }

        // Sélectionner un coursier dans la zone, s'il y en a
        $coursier = $coursiers->first();

        // Créer ou récupérer une attribution pour le pli
        $attribution = Attribution::firstOrNew(['pli_id' => $pli->id]);

        // Affecter le coursier au ramassage ou au dépôt
      if($attribution)
        {
            if ($type =='ramassage') {
                $attribution->coursier_ramassage_id = $coursier->id;
                $attribution->date_attribution_ramassage = Carbon::now();  // Enregistrer la date d'attribution du ramassage
            } else {
                $attribution->coursier_depot_id = $coursier->id;
                $attribution->date_attribution_depot = Carbon::now();  // Enregistrer la date d'attribution du dépôt
            }
        }

        else{
            return redirect()->route('admin.attributions.index')->with('success', "Echec: Nous avons rencontré un probleme technique $type.");

        }

        // Sauvegarder l'attribution
        $attribution->save();

        return redirect()->route('admin.attributions.index')->with('success', "Pli attribué avec succès pour le $type.");
    }



    public function confirmerAttribution($pliId, $type, Request $request)
    {
        //  Vérification du pli
        $pli = Pli::find($pliId);
        if (!$pli) {
            return redirect()->route('admin.attributions.index')->with('error', 'Pli non trouvé.');
        }

        //  Vérification du coursier sélectionné
        $coursierId = $request->input('coursier_id');
        if (!$coursierId) {
            return redirect()->route('admin.attributions.index')->with('error', 'Aucun coursier sélectionné.');
        }

        $coursierId = $request->input('coursier_idAt');  //Pour le depot
        if (!$coursierId) {
            return redirect()->route('admin.attributions.index')->with('error', 'Aucun coursier sélectionné.');
        }



        //  Vérification si le pli est déjà attribué
        if (Attribution::where('pli_id', $pli->id)->exists()) {
            return redirect()->route('admin.attributions.index')->with('error', "Ce pli est déjà attribué au type :'$type'.");
        }

        //  Vérification du type d’attribution (Empêche les erreurs)
        if (!in_array($type, ['ramassage', 'depot'])) {
            return redirect()->route('admin.attributions.index')->with('error', "Erreur inconnue : Type '$type' invalide.");
        }

        //  Création d'une nouvelle attribution
        $attribution = new Attribution();
        $attribution->pli_id = $pli->id;

        //  Attribution du coursier selon le type
        if ($type == 'ramassage') {
            $attribution->coursier_ramassage_id = $coursierId;
            $attribution->date_attribution_ramassage = Carbon::now();

        } else { // Ici, `$type === 'depot'` est assuré
            $attribution->coursier_depot_id = $coursierId;
            $attribution->date_attribution_depot = Carbon::now();
        }

        //  Sauvegarde de l’attribution
        $attribution->save();
        $attribution->refresh(); // Recharge les nouvelles valeurs après l’enregistrement


        //  Redirection avec message de succès
        return redirect()->route('admin.attributions.index')->with('success', "Pli attribué avec succès pour le type '$type'.");
    }




// -------------------------------------------------------------------------------------------------
    //  / Fin de la fonction d'attribution des pli

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



        public function pliDejaAttribue($pliId) // Pour l'attribution de l'unique pli
        {
            $pli = Pli::find($pliId); //  Charge l'objet via son ID

            if (!$pli) {
                return false; //  Empêche une erreur si `$pli` n'existe pas
            }

            return $pli->attributions()->exists(); //  Vérifie l’existence d’une attribution
        }




}
