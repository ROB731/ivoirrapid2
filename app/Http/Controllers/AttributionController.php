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


        // public function processAttribution(Request $request)
        // // public function attribuerPlis(Request $request)
        // {
        //     $pliIds = $request->input('plies', []);
        //     $type = $request->input('type');
        //     $coursier_id = $request->input('coursier_id') ?? $request->input('coursier_id1');

        //     if (empty($pliIds)) {
        //         return redirect()->route('admin.attributions.index')->with('error', 'Aucun pli sélectionné.');
        //     }

        //     $index = 0;
        //     while ($index < count($pliIds)) {
        //         $pliId = $pliIds[$index]; // 🔹 Récupérer l'ID du pli actuel
        //         $index++; // 🔹 Incrémenter pour passer au suivant

        //         $pli = Pli::find($pliId);
        //         if (!$pli) {
        //             continue; // 🔹 Ignore les plis inexistants
        //         }
        //         //  Vérifier si une attribution existe déjà pour ce pli avec le même type
        //         $existingAttribution = Attribution::where('pli_id', $pli->id)
        //             ->where(function ($query) use ($type) {
        //                 if ($type == 'ramassage') {
        //                     $query->whereNotNull('coursier_ramassage_id');
        //                 } else {
        //                     $query->whereNotNull('coursier_depot_id');
        //                 }
        //             })
        //             ->exists();

        //         if ($existingAttribution) {
        //             continue; // 🔹 Ignore les plis déjà attribués
        //         }

        //         //  Déterminer la zone ciblée
        //         $zoneCible = ($type === 'ramassage') ? $pli->user_Zone : $pli->destinataire_zone;

        //         //  Filtrer les coursiers disponibles dans la zone
        //         $coursiersDisponibles = Coursier::whereRaw("FIND_IN_SET(?, zones)", [trim($zoneCible)])->get();

        //         if ($coursiersDisponibles->isEmpty()) {

        //             continue; // 🔹 Ignore ce pli et passe au suivant si aucun coursier n'est trouvé
        //         }

        //         //  Sélectionner un coursier dans la zone
        //         $coursier = $coursiersDisponibles->shuffle()->first();

        //         //  Créer ou récupérer une attribution pour le pli
        //         $attribution = Attribution::firstOrNew(['pli_id' => $pli->id]);

        //         if ($type === 'ramassage') {
        //             $attribution->coursier_ramassage_id = $coursier->id ?? $coursier_id;
        //             $attribution->date_attribution_ramassage = now();
        //         } else {
        //             $attribution->coursier_depot_id = $coursier->id ?? $coursier_id;
        //             $attribution->date_attribution_depot = now();
        //         }

        //         $attribution->save();
        //     }

        //     return redirect()->route('admin.attributions.index')->with('success', 'Attribution effectuée avec succès!');
        // }

// --------------------------------------------------------------------------------Fonction sans le tableau pour la verification
        // public function processAttribution(Request $request)
        // {
        //     $pliIds = $request->input('plies', []);
        //     $type = $request->input('type');
        //     $coursier_id = $request->input('coursier_id') ?? $request->input('coursier_id1'); // 🔥 Récupère le coursier alternatif si sélectionné

        //     if (empty($pliIds)) {
        //         return redirect()->route('admin.attributions.index')->with('error', 'Aucun pli sélectionné.');
        //     }

        //     foreach ($pliIds as $pliId) {
        //         $pli = Pli::find($pliId);
        //         if (!$pli) {
        //             continue;
        //         }

        //         //  Vérifier si une attribution existe déjà
        //         $existingAttribution = Attribution::where('pli_id', $pli->id)
        //             ->where(function ($query) use ($type) {
        //                 if ($type == 'ramassage') {
        //                     $query->whereNotNull('coursier_ramassage_id');
        //                 } else {
        //                     $query->whereNotNull('coursier_depot_id');
        //                 }
        //             })
        //             ->exists();

        //         if ($existingAttribution) {
        //             continue;
        //         }

        //         //  Déterminer la zone ciblée
        //         $zoneCible = ($type === 'ramassage') ? $pli->user_Zone : $pli->destinataire_zone;

        //         //  Filtrer les coursiers disponibles dans la zone
        //         $coursiersDisponibles = Coursier::whereRaw("FIND_IN_SET(?, zones)", [trim($zoneCible)])->get();

        //         //  Si aucun coursier disponible, utiliser celui sélectionné manuellement
        //         $coursier = !$coursiersDisponibles->isEmpty() ? $coursiersDisponibles->shuffle()->first() : Coursier::find($coursier_id);

        //         if (!$coursier) {
        //             continue; // 🔹 Si aucun coursier n'est attribuable, passer au pli suivant
        //         }

        //         // 🔥 Créer ou récupérer une attribution pour le pli
        //         $attribution = Attribution::firstOrNew(['pli_id' => $pli->id]);

        //         if ($type === 'ramassage') {
        //             $attribution->coursier_ramassage_id = $coursier->id;
        //             $attribution->date_attribution_ramassage = now();
        //         } else {
        //             $attribution->coursier_depot_id = $coursier->id;
        //             $attribution->date_attribution_depot = now();
        //         }

        //         $attribution->save();
        //     }

        //     return redirect()->route('admin.attributions.index')->with('success', 'Attribution effectuée avec succès!');

        // }

//  /Fin sans  tableau avec vérification -----------------------------------------------------------------------------

// ----------------------- Transforme en tableau -----------------------------------------------------------------------------
        public function processAttribution(Request $request) //-------------Fonction pour l'attribution
        {
            $pliIds = $request->input('plies', []);
            $type = $request->input('type');
            $coursier_id = $request->input('coursier_id') ?? $request->input('coursier_id1');

            $coursiersImpliques = collect(); // Liste unique des coursiers impliqués

            if (empty($pliIds)) {
                return redirect()->route('admin.attributions.index')->with('error', 'Aucun pli sélectionné.');
            }

            $nombreAttributions = 1; //  Compteur des attributions validées

            foreach ($pliIds as $pliId) {
                $pli = Pli::find($pliId);
                if (!$pli) continue;

                $existingAttribution = Attribution::where('pli_id', $pli->id)
                    ->where(function ($query) use ($type) {
                        if ($type == 'ramassage') {
                            $query->whereNotNull('coursier_ramassage_id');
                        } else {
                            $query->whereNotNull('coursier_depot_id');
                        }
                    })
                    ->exists();

                if ($existingAttribution) continue;

                $zoneCible = ($type === 'ramassage') ? $pli->user_Zone : $pli->destinataire_zone;

                $coursiersDisponibles = Coursier::all()->filter(function ($coursier) use ($zoneCible) {
                    $zonesCoursier = is_array($coursier->zones) ? $coursier->zones : explode(',', $coursier->zones);
                    return in_array($zoneCible, $zonesCoursier);
                });

                $coursier = !$coursiersDisponibles->isEmpty() ? $coursiersDisponibles->shuffle()->first() : Coursier::find($coursier_id);
                if (!$coursier) continue;

                $attribution = Attribution::firstOrNew(['pli_id' => $pli->id]);

                if ($type === 'ramassage') {
                    $attribution->coursier_ramassage_id = $coursier->id;
                    $attribution->date_attribution_ramassage = now();
                } else {
                    $attribution->coursier_depot_id = $coursier->id;
                    $attribution->date_attribution_depot = now();
                }

                $attribution->save();
                $nombreAttributions++; //  Incrémentation du compteur après chaque attribution réussie

                // Coursiers impliqué

                $coursiersImpliques->push($coursier->id);
            }

            $nombreCoursiersImpliques = $coursiersImpliques->unique()->count();

            return redirect()->route('admin.attributions.index')
                // ->with('success', "Attribution effectuée avec succès! Nombre d'attributions validées : $nombreAttributions");
                ->with('success', "Attribution de type '$type' effectuée avec succès! Nombre d'attributions validées : $nombreAttributions". '  Nombre de coursier Impliqué : '.$nombreCoursiersImpliques ?? 0);
        }

//  Fin transforme en tableau -----------------------------------------------------------------------------------

        // Fin pour l'attribution automatique

                // La fonction index ici
                public function index()
                {
                    $coursiersDisponiblesDepot = Coursier::join('attributions', 'coursiers.id', '=', 'attributions.coursier_depot_id')
                            ->whereNotNull('attributions.coursier_ramassage_id') // Le pli a été ramassé
                            ->whereNull('attributions.coursier_depot_id') // Mais pas encore déposé
                            ->distinct()
                            ->get(['coursiers.id', 'coursiers.nom', 'coursiers.prenoms', 'coursiers.zones', 'coursiers.telephone']);
                                                // Fin coursier disoinible aà depot

                             // Debut pour le coursier au ramassage

                 //  Sélectionner les zones des expéditeurs qui ont encore des plis non attribués-------------------------------------------------

                         $zonesExpediteurs = Pli::pluck('user_zone')->unique()->filter(fn($zone) => !is_null($zone) && $zone !== '000') ?? collect([]);
                        $zonesAvecPlis = Pli::leftJoin('attributions', 'plies.id', '=', 'attributions.pli_id')
                            ->whereNull('attributions.coursier_ramassage_id') // On prend seulement les plis non attribués
                            ->pluck('user_zone')
                            ->unique()
                            ->filter(fn($zone) => !is_null($zone) && $zone !== '000');
                                //  Récupérer uniquement les coursiers qui travaillent dans ces zones actives
                             $coursiersDisponiblesRamassage = Coursier::whereIn('zones', $zonesAvecPlis)->get(['id', 'nom', 'prenoms', 'zones', 'telephone']);

                //  Sélectionner TOUS les coursiers comme alternative--------------------------------------------------------------------------------------------
                            $coursiersAlternatifs = Coursier::all(['id', 'nom', 'prenoms', 'zones', 'telephone']) ?? collect([]);
                // -----------------------------------------------------------------------------------------------------------------------------------------------
// **************************************************Désserer un peu pour l'attribution 20-05-2025--------------------

                // $plies = Pli::whereDoesntHave('attributions')->get() ?? collect([]);

                        //  Récupère les plis qui n'ont AUCUNE attribution ou aucune date de ramassage
                            $plies = Pli::whereDoesntHave('attributions')
                                ->orWhereHas('attributions', function ($query) {
                                    $query->whereNull('date_attribution_ramassage'); //  Vérifie que la date de ramassage n'est pas définie
                                })
                                ->get() ?? collect([]);

// ****************************Fin**********Désserer un peu pour l'attribution 20-05-2025----------------------------



                        //  Plis ramassés mais pas encore déposés

                        $plisRamassageList = Pli::whereHas('attributions', function ($query) {
                            $query->whereNotNull('coursier_ramassage_id')->whereNull('coursier_depot_id');
                        })->get() ?? collect([]);

                        //  Plis déposés (ont été ramassés et sont maintenant en dépôt)
                        $plisDepotList = Pli::whereHas('attributions', function ($query) {
                            $query->whereNotNull('coursier_depot_id');
                        })->get() ?? collect([]);

                        //  Liste complète des plis attribués (ramassés et/ou déposés)
                        $plisAttribuesList = Pli::whereHas('attributions')->paginate(10) ?? collect([]);

                    //  Ajout des dates d'attribution aux plis
                    $plies = $plies->map(function ($pli) {
                        $pli->date_attribution_ramassage = optional($pli->attributions->whereNotNull('date_ramassage')->first())->date_ramassage ?? 'Non attribué';

                        $pli->date_attribution_depot = optional($pli->attributions->whereNotNull('date_depot')->first())->date_depot ?? 'Non attribué';
                        return $pli;
                    });

                    //  Plis jamais attribués (aucun ramassage ni dépôt) -----------------------------------------------------

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

                    $coursiersDisponiblesRamassage = $coursiersDisponiblesRamassage->map(function ($coursier) {
                        $coursier->nombre_plis_attribues = Attribution::where('coursier_ramassage_id', $coursier->id)
                            ->orWhere('coursier_depot_id', $coursier->id)
                            ->count() ?? 0;
                        return $coursier;
                    });

                    // Ajout de livraisons pour chaque coursier au depot
                       // 🔹 Ajout du nombre de livraisons attribuées pour chaque coursier coté ramassage  -------------
                       $coursiersDisponiblesDepot = $coursiersDisponiblesDepot->map(function ($coursier) {
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


                    $attributionsRamassage = Attribution::with('pli')
                        ->whereDate('created_at', now()->toDateString())
                        ->get();


     // Fin des comptage ------------------------------------------------------------------------------------------------

                    //  Nombre pour attribution du jour :-------------------------------------------


    // Ajout du 02-05-2025---------------------------------------------------------------------------------------------------------
                    $dateAujourdHui = now()->toDateString();

                    // Compter les coursiers ayant une attribution de ramassage
                    $nombreCoursiersRamassage = Attribution::whereDate('created_at', $dateAujourdHui)
                        ->whereNotNull('coursier_ramassage_id')
                        ->distinct()
                        ->count('coursier_ramassage_id');

                    // Compter les coursiers ayant une attribution de dépôt
                    $nombreCoursiersDepot = Attribution::whereDate('created_at', $dateAujourdHui)
                        ->whereNotNull('coursier_depot_id')
                        ->distinct()
                        ->count('coursier_depot_id');
                        //  Attribution coursier du jour
                        $dateActuelle = now()->toDateString();


                    $listeCoursiersRamassage = Attribution::with('coursierRamassage')
                        ->whereDate('created_at', now()->toDateString())
                        ->whereNotNull('coursier_ramassage_id')
                        ->groupBy('coursier_ramassage_id')
                        ->selectRaw('coursier_ramassage_id, COUNT(*) as nombrePlis')
                        ->get();

                        $testCoursiers = Coursier::whereHas('attributionsRamassage')->get();


                    $listeCoursiersDepot = Attribution::with('coursierDepot')
                    ->whereDate('created_at', now()->toDateString())
                    ->whereNotNull('coursier_depot_id')
                    ->groupBy('coursier_depot_id')
                    ->selectRaw('coursier_depot_id, COUNT(*) as nombrePlis')
                    ->get();


                        $attributionsDepot = Attribution::with(['pli', 'coursierDepot'])
                            ->whereDate('created_at', now()->toDateString())
                            ->get();

                            $coursiersAffectes = collect()->merge($listeCoursiersRamassage)->merge($listeCoursiersDepot);

                    // Comptage du nombre total de coursiers concernés
                    $totalCoursiersRamassage = count($listeCoursiersRamassage);
                    $totalCoursiersDepot = count($listeCoursiersDepot);

// Nombre d'attribution et impression des plis

                    $nombreCoursiers = Coursier::count() ?? 0;

                    //  Envoi des données à la vue
                    return view('admin.attributions.index', compact(
                        'plies', 'plisRamassageList', 'plisDepotList', 'plisAttribuesList',
                        'plisNonAttribues', 'plisRamassage', 'plisDepot', 'plisTraites',
                        'coursiersDisponiblesRamassage', 'coursiersAlternatifs', 'nombreCoursiers','coursiersDisponiblesDepot',
                        'nombreCoursiersDepot','nombreCoursiersRamassage','listeCoursiersDepot','listeCoursiersRamassage','coursiersAffectes',
                        'attributionsRamassage','attributionsDepot'
                    ));
                }



// ------------------------------------------------------------Fiche de mission depot --------------------------------------------


                             public function suiviDepotPli(Request $requete)
        {
            //Vérifier si un coursier est sélectionné
            $idCoursier = $requete->input('id_coursier');
            $debutIntervalle = $requete->input('date_debut');
            $finIntervalle = $requete->input('date_fin');

            // Vérifier que le coursier existe
            $coursierSelectionne = Coursier::findOrFail($idCoursier);

            // Filtrer les attributions par coursier et intervalle de date
            $plisAttribues = Attribution::with(['pli', 'coursierDepot'])
                ->where('coursier_depot_id', $idCoursier)
                ->whereBetween('created_at', [$debutIntervalle, $finIntervalle])
                ->get();

            // // Envoyer les données à la vue
                        return view('admin.plis.verification', compact('coursierSelectionne', 'plisAttribues'));

            // return view('admin.plis.verification');

            // echo'Affiche dans la fonction de "SuiviDepotPli dans le controller de pli"';
        }






    //-----------------------------------pour la fiche d'impression de mission depot et ramassage ------------Ajout 02/05/2025------------------------------------------

                public function ficheDepot($coursier_id)
                        {
                            // Vérifier que le coursier existe
                            $coursierDepot = Coursier::findOrFail($coursier_id);

                            // Récupérer les attributions liées au dépôt de ce coursier

                            $attributionsDepot = Attribution::with(['pli', 'coursierDepot']) //Fonctionnelle------------------
                                ->where('coursier_depot_id', $coursier_id)
                                ->whereDate('created_at', now()->toDateString())
                                ->get();

                            // Envoyer les données à la vue
                            return view('admin.attributions.fiche_mission_depot', compact('coursierDepot', 'attributionsDepot'));
                        }






                                // -------------------------------------------

                        public function ficheRamassage($coursier_id1)
                        {
                            //  Vérifier que le coursier existe
                            $coursierRamassage = Coursier::findOrFail($coursier_id1);

                            //  Récupérer les attributions liées au **ramassage** du coursier
                            $attributionsRamassage = Attribution::with(['pli', 'coursierRamassage']) // Correction ici
                                ->where('coursier_ramassage_id', $coursier_id1)
                                ->whereDate('created_at', now()->toDateString())
                                ->get();

                            //  Envoyer les données à la vue
                            return view('admin.attributions.fiche_mission_ramassage', compact('coursierRamassage', 'attributionsRamassage'));
                        }



    // ----------------------------------Fin d'ajout le 02 /05 2025 pour les fiches de missions -------------------------------




//Fin de la fonction index

         public function attribuerEnGroupe(Request $request)
                    {
                        $validated = $request->validate([
                            'plies' => 'required|array|min:1',
                            'plies.*' => 'exists:plies,id',
                            'type' => 'required|in:ramassage,depot',
                            'coursier_id' => $request->type === 'ramassage' ? 'required|exists:coursiers,id' : 'nullable',
                            'coursier_idAt' => $request->type === 'depot' ? 'required|exists:coursiers,id' : 'nullable',
                        ]);
                        // dd($validated);

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

// -------------------------------------------------------------------------------------------------
    //  / Fin de la fonction d'attribution des pli

public function impression(Request $request) //---------------------------------------------------------------------------------------------------------
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


public function filtrerParCoursierDepot(Request $request) //  Requette pour attribution de ramassage et statut en attente -----------------------------------------------------------------------------------------------------
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
