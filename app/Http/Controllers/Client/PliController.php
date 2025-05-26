<?php

namespace App\Http\Controllers\Client;
use Carbon\Carbon;
use App\Models\Pli;
use App\Models\User;
use App\Models\Statuer;
use App\Models\Coursier;
use App\Models\Attribution;
use App\Models\Destinataire;
use Illuminate\Http\Request;
use App\Models\HistoriquePli;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PliStatuerHistory;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Client\PliFormRequest;



// namespace App\Http\Controllers\Client;
// use Carbon\Carbon;
// use App\Models\Pli;
// use App\Models\User;
// use App\Models\Statuer;
// use App\Models\Coursier;
// use App\Models\Attribution;
// use App\Models\Destinataire;
// use Illuminate\Http\Request;
// use App\Models\HistoriquePli;
// use Barryvdh\DomPDF\Facade\Pdf;
// use App\Models\PliStatuerHistory;
// use Illuminate\Support\Facades\Log;
// use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Auth;
// use App\Http\Requests\Client\PliFormRequest;





class PliController extends Controller
{

// -----------------------Pour l'historique des plis finalisé



// Fonction pour admin 14-05-2025 ----------------------------------------------------------------------------------------------------
// Fonction pour admin 14-05-2025 ----------------------------------------------------------------------------------------------------

public function historique(Request $request) {
    // Tous les avec statu pour l'administrateur


    // Pour le formulaire de filtre
            //  Ajout du filtre par date à la requête-------------------------------------------------

            $query = Pli::query();

            //  Filtrer par statut si sélectionné
            if ($request->filled('statut')) {
                $query->whereHas('statuerHistories', function ($q) use ($request) {
                    $q->where('statuer_id', $request->input('statut'));
                });
            }

            //  Filtrer par plage de dates si sélectionnée
            if ($request->filled('date_debut') && $request->filled('date_fin')) {
                $query->whereBetween('date_attribution_depot', [
                    $request->input('date_debut'),
                    $request->input('date_fin')
                ]);
            }

            //  Exécuter la requête avec pagination
            $listePlisFinalisesr1 = $query->orderBy('date_attribution_depot', 'desc')->paginate(10);

            // Fin formulaire de filtre--------------------------------

            // Définition des statuts finalisés
            $statutsFinalises = ['déposé', 'annulé', 'refusé'];

            // Récupération des IDs des statuts finalisés
            $statuerIdsFinalises = Statuer::whereIn('name', $statutsFinalises)->pluck('id')->toArray();


            // $listePlisFinalises = Pli::whereHas('statuerHistories', function ($query) use ($statuerIdsFinalises) {
            //     $query->whereIn('statuer_id', $statuerIdsFinalises);
            // })
            // ->with([
            //     'statuerHistories.statuer',
            //     'destinataire',
            //     'user',
            //     'attributions.coursierRamassage',
            //     'attributions.coursierDepot',
            // ])
            // ->orderBy('date_attribution_depot', 'desc') //  Trie du plus récent au moins récent
            // ->paginate(200); //  Ajoute la pagination avec 10 plis par page


                $listePlisFinalises = Pli::whereHas('statuerHistories', function ($query) use ($statuerIdsFinalises) {
                        $query->whereIn('statuer_id', $statuerIdsFinalises);
                    })
                    ->with([
                        'statuerHistories' => function ($query) {
                            $query->latest('updated_at'); //  Trie par la dernière modification du statut
                        },
                        'destinataire',
                        'user',
                        'attributions.coursierRamassage',
                        'attributions.coursierDepot',
                    ])
                    ->orderByRaw('(SELECT MAX(updated_at) FROM statuer_histories WHERE statuer_histories.pli_id = plis.id)', 'asc') //  Trie par dernier statut modifié
                    ->paginate(200);




            foreach ($listePlisFinalises as $pli) {
                $dateRamassage = $pli->date_attribution_ramassage ? Carbon::parse($pli->date_attribution_ramassage) : null;
                $dateDepot = $pli->date_attribution_depot ? Carbon::parse($pli->date_attribution_depot) : null;

                if ($dateRamassage && $dateDepot) {
                    $pli->dureeLivraison = $dateDepot->diffInDays($dateRamassage) . ' jours';
                } else {
                    $pli->dureeLivraison = 'Non défini';
                }
            }

            foreach ($listePlisFinalises as $pli) { // pour la date du statut
                $dernierStatut = $pli->statuerHistories->last();
                $pli->dateDecision = $dernierStatut ? Carbon::parse($dernierStatut->created_at)->format('d/m/Y') : 'Non défini';
            }

            return view('admin.plis.historique_plis', compact('listePlisFinalises', 'statuerIdsFinalises'));
    }

// Fonction pour admin 14-05-2025 ----------------------------------------------------------------------------------------------------
// Fonction pour admin 14-05-2025 ----------------------------------------------------------------------------------------------------




// ------------------------------------------------------------Début fonction index ----------------------------------------------------

public function index(Request $request) //La fonction index d'affichage
{
    $codeRecherche = $request->input('code');//  Récupère le code depuis la requête utilisateur
    // Optimiser pour les admoinistrateur

    // Pour l'affichage des statistique des suivi de pli
    $statutsFinaux = ['déposé', 'annulé', 'refusé'];
    // Récupération des IDs des statuts finaux
    $statuerIds = Statuer::whereIn('name', $statutsFinaux)->pluck('id')->toArray();

            $detailsPlis = Pli::join('pli_statuer_history', 'plies.id', '=', 'pli_statuer_history.pli_id')
            ->whereIn('pli_statuer_history.statuer_id', $statuerIds)
            ->groupBy('pli_statuer_history.statuer_id')
            ->selectRaw('pli_statuer_history.statuer_id, COUNT(*) as total')
            ->pluck('total', 'pli_statuer_history.statuer_id')
            ->toArray();

            //  Vérifier si les `pli_id` existent bien dans la table `attributions`
            $pliIds = Pli::join('pli_statuer_history', 'plies.id', '=', 'pli_statuer_history.pli_id')
                ->whereIn('pli_statuer_history.statuer_id', $statuerIds)
                ->pluck('plies.id')
                ->toArray();

            $pliAttribues = Attribution::whereIn('pli_id', $pliIds)->pluck('pli_id')->toArray();

            //  Identifier les `pli_id` qui n'ont pas d'attribution
            $pliSansAttribution = array_diff($pliIds, $pliAttribues);

                if (!empty($pliSansAttribution)) {
                    //  Il y a des plis sans attribution, on peut les afficher ou les traiter
                    Log::info("Attention, certains plis n'ont pas d'attribution : " . implode(', ', $pliSansAttribution));
                    }

            //  Charger les noms des statuts pour éviter l'erreur dans Blade
            $statuerNames = Statuer::whereIn('id', array_keys($detailsPlis))->pluck('name', 'id')->toArray();
            $totalPlisFinalises = array_sum($detailsPlis);

            // dd($statuerIds);

            //  Plis attribués au dépôt
            $plisDeposes = Pli::leftJoin('attributions', 'plies.id', '=', 'attributions.pli_id')
            ->whereNotNull('attributions.coursier_depot_id')
            ->select('plies.*', 'attributions.coursier_depot_id')
            ->paginate(500);

     //  Récupérer tous les plis qui ont été ramassés

            $plisRamasses = Pli::whereHas('attributions', function ($query) {
                $query->whereNotNull('coursier_ramassage_id'); // Sélectionne les attributions où le pli a été ramassé
            })->with('attributions')->get(); // Charge aussi les infos des attributions liées


            $plisNonAttribues = Pli::whereDoesntHave('attributions')
            ->orWhereHas('attributions', function ($query) {
                $query->whereNull('coursier_ramassage_id')->whereNull('coursier_depot_id');
            })->with('attributions')->get();

    // Fin optimoiser pour les admin------------------------------------------------------------------------
    // ---------------------------debut pour l'affichage -------------------------------------------------------



    // Vérifie si l'utilisateur est un administrateur ou un utilisateur basique----------------------------------------------------------
              $query = Pli::query();
            if (Auth::user()->role_as == '1') {
                // Administrateur : récupération de tous les plis
                $query->orderBy('created_at', 'desc');
            } else {
                // Utilisateur basique : récupérer uniquement ses propres plis
                $query->where('user_id', Auth::id())->orderBy('created_at', 'desc');
            }
    // ----------------------------------------------------------------------------------------------------------------------------------

    // Pour le formulaire de filtre debut  et pour les recherche dans le formulaire ------------------------------------------------------------------------------------------------
    // Filtrer par nom de destinataire

    // Filtrer par statut
                if ($request->filled('status')) {
                    $query->whereHas('currentStatus', function ($query) use ($request) {
                        $query->where('name', 'like', '%' . $request->status . '%');
                    });
    //Par numero de suivi filtre
                 }


        // Filtrer par intervalle de dates
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

            // Filtrer par coursier ramassage
            if ($request->filled('coursier_ramassage')) {
                $query->whereHas('attributions', function ($q) use ($request) {
                    $q->where('coursier_ramassage_id', $request->coursier_ramassage);
                });
            }

        // Filtrer par coursier dépôt
        if ($request->filled('coursier_depot')) {
            $query->whereHas('attributions', function ($q) use ($request) {
                $q->where('coursier_depot_id', $request->coursier_depot);
            });
        }

    // Rechercher avec le code

            if ($request->filled('code')) { //  Vérifie si 'code' a été renseigné
                $query->where('plies.code', $request->input('code')); //  Filtre les plis par code
            }



            if ($request->filled('reference')) { //  Vérifie si 'code' a été renseigné
                $query->where('plies.reference', $request->input('reference')); //  Filtre les plis par code
            }

// Recherche par le client --------------------------------------------------------------------------------
            if ($request->filled('client')) { //  Vérifie si 'code' a été renseigné
                $query->where('plies.user_name', $request->input('client')); //  Filtre les plis par code
            }


            // Recherche pour le destinataire avec les destinataire ------------------------------------------

            if ($request->filled('destinataire_name')) {
                // ✅ Filtre uniquement les plis de l'utilisateur connecté
                $query->where('user_id', auth()->id())
                    ->where('plies.destinataire_name', $request->input('destinataire_name'));
            }


// -----------------------------------Fin formulaire de recherche ---------------------------------------------------------------------------------



    // **Nombre total de plis après filtrage**
    $totalPlis = $query->count();

    // Récupérer les plis filtrés avec pagination



    // - formulaire  fin --------------------------------------------------------------------------------------------------------------------


            // Récupérer les destinataires de l'utilisateur connecté
            $destinataires = Pli::where('user_id', Auth::id())
                ->select('destinataire_name')
                ->distinct()
                ->get();

                // Récupérer les coursiers
                $coursiers = Coursier::select('id', 'prenoms', 'nom')
                    ->orderBy('nom', 'asc')
                    ->orderBy('prenoms', 'asc')
                    ->get();

            //Bottom to add new functions :)
                $plisNonAttrib = Pli::whereDoesntHave('attributions')->get();
                $derniersPlis = Pli::orderBy('created_at', 'desc')->limit(5)->get();

        // Nombre de plis créés par période
        $aujourdhui = Pli::whereDate('created_at', Carbon::today())->count();
        $hier = Pli::whereDate('created_at', Carbon::yesterday())->count();
        $semaineDerniere = Pli::whereBetween('created_at', [Carbon::now()->startOfWeek()->subWeek(), Carbon::now()->startOfWeek()])->count();
        $moisDernier = Pli::whereBetween('created_at', [Carbon::now()->startOfMonth()->subMonth(), Carbon::now()->startOfMonth()])->count();



        $plisAttribuesSansStatutFinal = 0;

        // Pour afficher les plis à suivre -------------------------------------------------------------------------------------------------

        // $plis = $query->paginate(1000);


// Gestion des plis pour admin 09-05-2025----- ------------------------------------------------------------------------------------------
            // Gestion des plis pour admin 09-05-2025----- ------------------------------------------------------------------------------------------


        $plisASuivre = Pli::whereDoesntHave('statuerHistories', function ($query) {
            $query->whereIn('statuer_id', [3, 4, 5]); // Exclure les statuts finaux (Déposé, Annulé, Refusé)
        })->whereHas('attributions', function ($query) {
            $query->whereNotNull('coursier_ramassage_id'); // Vérifier qu’un coursier a été assigné pour le ramassage
        })->orderByRaw("
            CASE
                WHEN EXISTS (SELECT 1 FROM attributions WHERE attributions.pli_id = plies.id
                    AND attributions.coursier_ramassage_id IS NOT NULL
                    AND attributions.coursier_depot_id IS NOT NULL) THEN 1
                ELSE 2
            END, created_at DESC
        ")->paginate(500); //  Paginer avec 50 résultats par page


        // -----------------------------------------------

           // Pour la recherche avec le code de reference
        //    if ($request->filled('reference')) {
        //     $references = explode('|', $request->input('reference')); // Convertir en tableau

        //     $plis = Pli::where(function ($query) use ($references) {
        //         foreach ($references as $reference) {
        //             $query->orWhere('reference', 'LIKE', '%' . $reference . '%'); // Recherche partielle
        //         }
        //     })
        //     ->orderBy('reference', 'desc')
        //     ->paginate(250);


        // }



            if ($request->filled('coursier_depot') || $request->filled('coursier_ramassage')
            || $request->filled('start_date') && $request->filled('end_date')
            || $request->filled('code') || $request->filled('reference') || $request->filled('client'))
                    {
                        $plis = $query->paginate(250);
                    }
                else {
                    $plis = $plisASuivre;
                }


// fin  09-05-2025 Gestion des plis admin--------------------------------------------------
// fin  09-05-2025 Gestion des plis admin--------------------------------------------------


 // Pour la vu des clients, l'index pli ou élément du pli ------------------------------------------------------------------------------------------
                    // Plis ramasse ou arribué

                        if ($request->filled('coursier_depot') || $request->filled('coursier_ramassage')
                        || ($request->filled('start_date') && $request->filled('end_date'))
                                    || $request->filled('code') || $request->filled('destinataire_name') )
                                {
                                    $pliClient = $query->where('user_id', auth()->id()) // Filtrer par utilisateur
                                                ->orderBy('created_at', 'Desc') // Trier par date de création (asc = plus anciens en premier)
                                                ->paginate(250);
                                }
                                else {


                                    $pliClient = Pli::where('user_id', auth()->id()) //il s'agit ici des plis qui non pas été ramassé par ir
                                            ->whereDoesntHave('attributions')
                                            ->orWhereHas('attributions', function ($query) {
                                                $query->whereNull('coursier_ramassage_id')->whereNull('coursier_depot_id');
                                            })
                                            ->with('attributions')
                                            ->orderBy('created_at', 'Desc') // Affiche les plus anciens en premier
                                            ->get();

                                        }

                                        // Pour la recherche avec le code de reference
                                        if($request->filled('reference')) {
                                            $pliClient = Pli::where('user_id', auth()->id())
                                                ->where(function($query) use ($request) {
                                                    $references = explode('|', $request->input('reference')); // Convertir en tableau
                                                    foreach ($references as $reference) {
                                                        $query->orWhere('reference', 'LIKE', '%'.$reference.'%'); // Recherche partielle
                                                    }
                                                })
                                                ->orderBy('reference', 'desc')
                                                ->paginate(250);
                                        }


                        // Pour le destinaantaire --------------------------------------------------------------
                                //  $pliClient = Pli::where('user_id', auth()->id()) //il s'agit ici des plis qui non pas été ramassé par ir
                                //             ->whereDoesntHave('attributions')
                                //             ->orWhereHas('attributions', function ($query) {
                                //                 $query->whereNull('coursier_ramassage_id')->whereNull('coursier_depot_id');
                                //             })
                                //             ->with('attributions')
                                //             ->orderBy('created_at', 'Desc') // Affiche les plus anciens en premier
                                //             ->get();



                        $plisRamassesOuAttribues = Pli::where('user_id', auth()->id())
                        ->whereHas('attributions') // Sélectionner uniquement les plis attribués
                        ->whereDoesntHave('pliStatuerHistory', function ($query) {
                            $query->whereIn('statuer_id', [3, 4, 5]); // Exclure les statuts finaux (déposé, annulé, refusé)
                        })
                        ->with('attributions')
                        ->orderByRaw("COALESCE(date_attribution_ramassage, date_attribution_depot) ASC") // Tri par la première date disponible
                        ->get()
                        ->groupBy(function ($pli) {
                            $lastAttribution = $pli->attributions->last();

                            return $lastAttribution
                                ? ($lastAttribution->date_attribution_ramassage ?? $lastAttribution->date_attribution_depot) // Grouper par la première date existante
                                : 'Date inconnue';
                        });

                            $totalPlisRamassesOuAttribues = Pli::where('user_id', auth()->id())
                                    ->whereHas('attributions')
                                    ->whereDoesntHave('pliStatuerHistory', function ($query) {
                                        $query->whereIn('statuer_id', [3, 4, 5]); // Exclure les statuts finaux
                                    })
                                    ->count(); // ✅ Récupère le nombre total de plis

                        // Plis avec statut final

                        // $plisFinauxClient = Pli::where('user_id', auth()->id()) //On va prendre ça de cette manière parce que ça reduit un gros blem
                        // ->whereHas('pliStatuerHistory', function ($query) {
                        //     $query->whereIn('statuer_id', [3, 4, 5]); // Statuts finaux (déposé, annulé, refusé)
                        // })
                        // ->orderBy('updated_at', 'desc') // Trier les plus récents en premier
                        // ->paginate(50); // Ajout de la pagination

                        $plisFinauxClient = Pli::where('user_id', auth()->id())
                            ->whereHas('pliStatuerHistory', function ($query) {
                                $query->whereIn('statuer_id', [3, 4, 5])
                                    ->latest('updated_at'); // Sélectionne le statut le plus récent
                            })
                            ->orderBy('updated_at', 'desc') // Trie les plis récents en premier
                            ->paginate(100); // Ajoute la pagination

                        $totalPlisFinauxClient = Pli::where('user_id', auth()->id())
                            ->whereHas('pliStatuerHistory', function ($query) {
                                $query->whereIn('statuer_id', [3, 4, 5]); // Statuts finaux (déposé, annulé, refusé)
                            })
                            ->count(); // ✅ Compter tous les plis finaux

                            //  $plis = $plisASuivre->where('user_id', auth()->id()) // Filtrer par utilisateur connecté
                            //                             ->orderBy('created_at', 'asc'); // Trier par date

// Fin pour la vues des clients pour la vue index ----------------------------------------------------------
                    // Fin pour la vues des clients pour la vue index ----------------------------------------------------------

    // Fin d'affichage  pour  l'affichage coté index plis--------------------------------------------------------------------------------------------

    // Retourner la vue avec les données supplémentaires
    if (Auth::user()->role_as == '1') {
        return view('admin.plis.index', compact('plis', 'destinataires', 'coursiers', 'totalPlis','plisRamasses','plisASuivre','detailsPlis','totalPlisFinalises','statuerNames','plisNonAttrib','plisAttribuesSansStatutFinal')); // Pour l'administrateur
    } else {
        // return view('client.plis.index', compact('plis', 'destinataires', 'totalPlis')); // Pour l'utilisateur
        return view('client.plis.index',
         compact('plis', 'destinataires', 'totalPlis','derniersPlis',
         'aujourdhui', 'hier', 'semaineDerniere', 'moisDernier','pliClient',
         'plisNonAttribues','plisRamassesOuAttribues','plisFinauxClient','totalPlisRamassesOuAttribues',
        'totalPlisFinauxClient' )); // Pour l'utilisateur plus les statistiques

            }
        }


// Pour gerer les actions des utilisateurs

// Pour les plis qu'on va attribué sur la fiche  10-05-2025 -----------------------------------------

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



//\ La fonction-------------------------------------10-05-2025---------------------------------



// -------------------------------------------------------------01-05-2025----------------------------------------------------------------------------------------------------------------------------
public function pliesSupprimesParClient(Request $request)
{
    //  Sélectionner uniquement les plis supprimés
    $query = HistoriquePli::where('action', 'supprimé');

    // 📌 Vérifier si une recherche est faite
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('ancienne_valeur', 'LIKE', "%$search%")
              ->orWhere('nouvelle_valeur', 'LIKE', "%$search%");
        });
    }

    //  Comptage du total de plis supprimés
    $totalPliesSupprimes = $query->count();

    //  Paginé à 10 résultats par page
    $pliesSupprimes = $query->paginate(40);

    return view('admin.plis.plis_trashed', compact('pliesSupprimes', 'totalPliesSupprimes'));
}


// Restauration des plis supprimer-------------------------------------01-05-2025-------------------------------------------------------------
public function restaurerPli($historique_id)
{
    //  Trouver l'historique du pli supprimé
    $historique = HistoriquePli::findOrFail($historique_id);

    // 🛠 Convertir les anciennes valeurs JSON en tableau
    $ancienneValeur = json_decode($historique->ancienne_valeur, true);

    // 🔄 Réinsérer le pli supprimé dans la table `plies`
    $pliRestauré = Pli::create($ancienneValeur);

    //  Ajouter un enregistrement dans l'historique pour signaler la restauration
    HistoriquePli::create([
        'pli_id' => $pliRestauré->id,
        'client_id' => auth()->id(),
        'action' => 'restauré',
        'ancienne_valeur' => json_encode($ancienneValeur),
        'nouvelle_valeur' => json_encode($pliRestauré->getAttributes()),
        'date_action' => now(),
    ]);

    return redirect()->route('admin.plis.plis_trashed')->with('success', 'Pli restauré avec succès.');

}


public function supprimerPli(Pli $pli)
{
    HistoriquePli::create([
        'pli_id' => $pli->id,
        'client_id' => auth()->id(),
        'action' => 'supprimé',
        'ancienne_valeur' => json_encode($pli->getOriginal()),
        'date_action' => now(),
    ]);

    $pli->forceDelete();

    return redirect()->route('client.plis.index')->with('success', 'Pli N°:'.$pli->code .' a été Supprimé définitivement Aujourd\'hui.');
}




// Fin de d'ajout fonction pour les action    01-05-2025 ---------------------------------------------------------------------------------------------------------------------------------------------------

// Fonction de création corrigée 03-05------------------------------------------------------------------------------------------------

        public function create() // pROBLEME D'icrementation lorsque on clique deux fois, on doit pas cliqué deux fois
    {
            // Récupérer les destinataires pour l'utilisateur connecté
            // $destinataires = Destinataire::where('user_id', Auth::id())->get();

            $destinataires = Destinataire::orderBy('name', 'asc')->get(); // Ici pour créer les plis, les utilisateur on accès à touts les utilisateur

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
            // Vérifier si le code existe déjà
            $existingPli = Pli::where('code', $code)->first();

        if ($existingPli) {
            // Le code existe encore, incrémenter à nouveau
                $nextNumber++;
                $nextNumberPadded = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
                $code = "$yearMonth-$nextNumberPadded";
            }
        else // Nouvelle ajout
            {
                $nextNumber;
                $nextNumberPadded = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
                $code = "$yearMonth-$nextNumberPadded";
            }


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
        // $pli->statut='1';

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

// ---------------------------------------------------------------------------------------------------------------------------------

    // public function changeStatuer($pliId, Request $request)
    // {
    //     $pli = Pli::findOrFail($pliId);
    //     $statuer = Statuer::where('name', $request->statuer)->first();

    //     if (!$statuer) {
    //         return redirect()->route('admin.plis.index')->with('error', 'Le statut spécifié est invalide.');
    //     }

    //     // Vérifier si le statut est "annulé"
    //     $raisonAnnulation = $request->statuer === 'annulé' ? $request->raison : null;

    //     // Créer un nouvel enregistrement dans pli_statuer_history
    //     $pliStatuerHistory = new PliStatuerHistory([
    //         'pli_id' => $pli->id,
    //         'statuer_id' => $statuer->id,
    //         'date_changement' => now(),
    //         'raison_annulation' => $raisonAnnulation,
    //     ]);
    //     $pliStatuerHistory->save();

    //     return redirect()->route('admin.plis.index')->with('success', 'Statut mis à jour avec succès.');
    // }




    // ------------------------Changement du statut par l'admin à partir d'ici que le client doit voir le statut de sont bpli va s'enregistrer dans la bd-----------------------------------------------------------------------

    public function changeStatuer(Request $request, Pli $pli)
{
    $nouveauStatut = $request->input('statuer');
    $raison = $request->input('raison');

    // Vérification des prérequis avant changement de statut
    if (in_array($nouveauStatut, ['déposé', 'refusé'])) {
        if (!$pli->attributions()->whereNotNull('coursier_ramassage_id')->whereNotNull('coursier_depot_id')->exists()) {
            // dd($nouveauStatut);
            return redirect()->back()->with('error', 'Le pli doit être attribué à un coursier pour le ramassage et le dépôt avant de pouvoir être déposé ou refusé.');

        }
    } elseif ($nouveauStatut == 'annulé') {
        if ($pli->attributions()->whereNotNull('coursier_depot_id')->exists()) {
            // dd($nouveauStatut);
            return redirect()->back()->with('error', 'Le pli ne peut plus être annulé après avoir reçu un coursier pour le dépôt.');

        }
    }

    // Mise à jour du statut avec enregistrement de la raison
    PliStatuerHistory::create([
        'pli_id' => $pli->id,
        'statuer_id' => Statuer::where('name', $nouveauStatut)->first()->id,
        'date_changement' => now(),
        'raison_annulation' => in_array($nouveauStatut, ['annulé', 'refusé']) ? $raison : null
    ]);

    return redirect()->back()->with('success', 'Le statut du pli a été mis à jour avec succès.');
}


    // -----------------------------------------------------------------------------------------------------------------------------------------


    public function edit($id)
{
    $pli = Pli::findOrFail($id); // Récupérer le pli à éditer
    $destinataires = Destinataire::where('user_id', Auth::id())->get(); // Récupérer les destinataires de l'utilisateur authentifié

    return view('client.plis.edit', compact('pli', 'destinataires'));
}

public function update(PliFormRequest $request, $pli_id)
{
    $data = $request->validated();
    $pli = Pli::find($pli_id);
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
    $userName = strtolower(str_replace(' ', '_', $user->name));

    $lastPli = Pli::where('user_id', Auth::id())
        ->where('code', 'like', "$userName-$year-$month%")
        ->orderBy('created_at', 'desc')
        ->first();

    $nextNumber = $lastPli ? intval(substr($lastPli->code, -6)) + 1 : 1;
    $nextNumberPadded = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    // $pli->code = "$userName-$year-$month-$nextNumberPadded";

    // Ajout des dates d'attribution dynamiques pour ramassage et dépôt
    $pli->date_attribution_ramassage = Carbon::now();
    $pli->date_attribution_depot = Carbon::now(); // exemple d'ajout de 3 jours pour dépôt

    $pli->update();

    // Rediriger avec un message de succès
    return redirect()->route('client.plis.index')->with('success', 'Pli mis à jour avec succès!');
}

/*public function destroy($pli_id){
    $pli = Pli::find($pli_id);
    if ($pli)
    {
        $pli->delete();
        return redirect()->route('client.plis.index')->with('success', 'Pli supprimé avec succès.');
    }
    else{
        return redirect()->route('client.plis.index')->with('error', 'Pli non trouvé');
    }
}*/

public function destroy($pli_id){
    $pli = Pli::find($pli_id);
    if ($pli)
    {
        // Utilise forceDelete pour supprimer définitivement le pli
        $pli->forceDelete();
        return redirect()->route('client.plis.index')->with('success', 'Pli supprimé définitivement avec succès.');
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
