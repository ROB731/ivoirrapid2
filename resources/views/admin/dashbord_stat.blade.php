
                    @php

                        //  Filtrage initial
                        $query = \App\Models\Pli::query();
                        $msg="";

                        if (request()->filled('date_debut') && request()->filled('date_fin')) {
                            $query->whereBetween('created_at', [request()->input('date_debut'), request()->input('date_fin')]);
                              $msg='<p style="color:red; font-weight:bolder;"> Filtre Appliqué  </p>';
                        }

                        if (request()->filled('client')) {
                            $query->whereHas('user', function($q) {
                                $q->where('name', request()->input('client'));
                            });

                              $msg='<p style="color:red; font-weight:bolder;"> Filtre Appliqué </p>';
                        }

                        if (request()->filled('etat_pli')) {
                            $query->whereHas('pliStatuerHistory', function($q) {
                                $q->orderBy('updated_at', 'desc')->limit(1);
                            });
                              $msg='<p style="color:red; font-weight:bolder;"> Filtre Appliqué </p>';
                        }

                        //  Récupération du nombre total de plis
                        $totalPlis = $query->count();

                        //  Gestion des jours ouvrables (6 jours / semaine)
                        $dateDebut = request()->filled('date_debut') ? request()->input('date_debut') : now()->startOfYear();
                        $dateFin = request()->filled('date_fin') ? request()->input('date_fin') : now();
                        $nbJoursTotal = \Carbon\Carbon::parse($dateDebut)->diffInDays(\Carbon\Carbon::parse($dateFin)) + 1;
                        $nbJoursOuvrables = ($nbJoursTotal > 0) ? floor(($nbJoursTotal / 7) * 6) : 1; //  Assurer que ce n'est jamais 0

                        //  Calcul du nombre de semaines et de mois
                        $nbSemaines = ($nbJoursOuvrables > 0) ? ceil($nbJoursOuvrables / 6) : 1; //  Empêcher 0
                        $nbMois = (\Carbon\Carbon::parse($dateDebut)->diffInMonths(\Carbon\Carbon::parse($dateFin)) + 1) > 0 ? \Carbon\Carbon::parse($dateDebut)->diffInMonths(\Carbon\Carbon::parse($dateFin)) + 1 : 1;

                        //  Calcul des moyennes arrondies avec vérification
                        $moyenneQuotidienne = ($nbJoursOuvrables > 0) ? round($totalPlis / $nbJoursOuvrables) : 0;
                        $moyenneHebdomadaire = ($nbSemaines > 0) ? round($totalPlis / $nbSemaines) : 0;
                        $moyenneMensuelle = ($nbMois > 0) ? round($totalPlis / $nbMois) : 0;

                        //  Calcul de l’évolution en pourcentage avec vérification
                        $variation = ($moyenneQuotidienne > 0) ? round((($totalPlis - $moyenneQuotidienne * $nbJoursOuvrables) / $moyenneQuotidienne) * 100) : 0;

                @endphp

  {{-- Recupération hebdomadaire ---------------------------------- --}}
                        @php
                            $debutAnnee = now()->startOfYear();
                            $totalPlis = \App\Models\Pli::where('created_at', '>=', $debutAnnee)->count();
                            $nbJours = now()->diffInDays($debutAnnee) + 1;
                            $moyenneQuotidienne = $nbJours > 0 ? $totalPlis / $nbJours : 0;

                            //  Génération des dates depuis le 1er janvier
                            $dates = [];
                            $plisParJour = [];

                            for ($i = 0; $i < $nbJours; $i++) {
                                $date = $debutAnnee->copy()->addDays($i)->format('Y-m-d');
                                $dates[] = $date;
                                $plisParJour[] = \App\Models\Pli::whereDate('created_at', $date)->count();
                            }
                        @endphp

            {{-- Nouveau bloc de code pour les variations ------------------- --}}

                               @php
                                    use Carbon\Carbon;

                                    //  Définition des périodes
                                    $dateDebutSemaine = Carbon::now()->startOfWeek();
                                    $dateDebutMois = Carbon::now()->startOfMonth();
                                    $dateDebutTrimestre = Carbon::now()->startOfQuarter();
                                    $dateDebutSemestre = Carbon::now()->subMonths(6)->startOfMonth();

                                    //  Récupération des plis créés durant chaque période

                                    // $plisSemaineActuelle = \App\Models\Pli::whereBetween('created_at', [$dateDebutSemaine, Carbon::now()])->count();
                                    // $plisMoisActuel = \App\Models\Pli::whereBetween('created_at', [$dateDebutMois, Carbon::now()])->count();
                                    // $plisTrimestreActuel = \App\Models\Pli::whereBetween('created_at', [$dateDebutTrimestre, Carbon::now()])->count();
                                    // $plisSemestreActuel = \App\Models\Pli::whereBetween('created_at', [$dateDebutSemestre, Carbon::now()])->count();

                                    // //  Récupération des plis créés dans les périodes précédentes
                                    // $plisSemainePrecedente = \App\Models\Pli::whereBetween('created_at', [$dateDebutSemaine->subWeek(), $dateDebutSemaine])->count();
                                    // $plisMoisPrecedent = \App\Models\Pli::whereBetween('created_at', [$dateDebutMois->subMonth(), $dateDebutMois])->count();
                                    // $plisTrimestrePrecedent = \App\Models\Pli::whereBetween('created_at', [$dateDebutTrimestre->subMonths(3), $dateDebutTrimestre])->count();
                                    // $plisSemestrePrecedent = \App\Models\Pli::whereBetween('created_at', [$dateDebutSemestre->subMonths(6), $dateDebutSemestre])->count();

                                    // //  Récupération des plis RAMASSÉS dans les périodes actuelles
                                    // $plisSemaineActuelle = \App\Models\Pli::whereBetween('date_attribution_ramassage', [$dateDebutSemaine, Carbon::now()])->count();
                                    // $plisMoisActuel = \App\Models\Pli::whereBetween('date_attribution_ramassage', [$dateDebutMois, Carbon::now()])->count();
                                    // $plisTrimestreActuel = \App\Models\Pli::whereBetween('date_attribution_ramassage', [$dateDebutTrimestre, Carbon::now()])->count();
                                    // $plisSemestreActuel = \App\Models\Pli::whereBetween('date_attribution_ramassage', [$dateDebutSemestre, Carbon::now()])->count();

                                    // //  Récupération des plis RAMASSÉS dans les périodes précédentes
                                    // $plisSemainePrecedente = \App\Models\Pli::whereBetween('date_attribution_ramassage', [$dateDebutSemaine->subWeek(), $dateDebutSemaine])->count();
                                    // $plisMoisPrecedent = \App\Models\Pli::whereBetween('date_attribution_ramassage', [$dateDebutMois->subMonth(), $dateDebutMois])->count();
                                    // $plisTrimestrePrecedent = \App\Models\Pli::whereBetween('date_attribution_ramassage', [$dateDebutTrimestre->subMonths(3), $dateDebutTrimestre])->count();
                                    // $plisSemestrePrecedent = \App\Models\Pli::whereBetween('date_attribution_ramassage', [$dateDebutSemestre->subMonths(6), $dateDebutSemestre])->count();


                                    //  Récupération des plis RAMASSÉS dans les périodes actuelles via la table `attribution`
                                    $plisSemaineActuelle = \App\Models\Pli::whereHas('attributions', function ($query) use ($dateDebutSemaine) {
                                        $query->whereBetween('date_attribution_ramassage', [$dateDebutSemaine, Carbon::now()]);
                                    })->count();

                                    $plisMoisActuel = \App\Models\Pli::whereHas('attributions', function ($query) use ($dateDebutMois) {
                                        $query->whereBetween('date_attribution_ramassage', [$dateDebutMois, Carbon::now()]);
                                    })->count();

                                    $plisTrimestreActuel = \App\Models\Pli::whereHas('attributions', function ($query) use ($dateDebutTrimestre) {
                                        $query->whereBetween('date_attribution_ramassage', [$dateDebutTrimestre, Carbon::now()]);
                                    })->count();

                                    $plisSemestreActuel = \App\Models\Pli::whereHas('attributions', function ($query) use ($dateDebutSemestre) {
                                        $query->whereBetween('date_attribution_ramassage', [$dateDebutSemestre, Carbon::now()]);
                                    })->count();

                                    //  Récupération des plis RAMASSÉS dans les périodes précédentes via `attribution`
                                    $plisSemainePrecedente = \App\Models\Pli::whereHas('attributions', function ($query) use ($dateDebutSemaine) {
                                        $query->whereBetween('date_attribution_ramassage', [$dateDebutSemaine->subWeek(), $dateDebutSemaine]);
                                    })->count();

                                    $plisMoisPrecedent = \App\Models\Pli::whereHas('attributions', function ($query) use ($dateDebutMois) {
                                        $query->whereBetween('date_attribution_ramassage', [$dateDebutMois->subMonth(), $dateDebutMois]);
                                    })->count();

                                    $plisTrimestrePrecedent = \App\Models\Pli::whereHas('attributions', function ($query) use ($dateDebutTrimestre) {
                                        $query->whereBetween('date_attribution_ramassage', [$dateDebutTrimestre->subMonths(3), $dateDebutTrimestre]);
                                    })->count();

                                    $plisSemestrePrecedent = \App\Models\Pli::whereHas('attributions', function ($query) use ($dateDebutSemestre) {
                                        $query->whereBetween('date_attribution_ramassage', [$dateDebutSemestre->subMonths(6), $dateDebutSemestre]);
                                    })->count();


                                    //  Récupération des plis qui ont reçu un statut durant chaque période

                                    // $plisStatuesSemaine = \App\Models\Pli::whereHas('pliStatuerHistory', function ($query) use ($dateDebutSemaine) {
                                    //     $query->whereBetween('updated_at', [$dateDebutSemaine, Carbon::now()]);
                                    // })->count();

                                            $plisStatuesSemaine = \App\Models\Pli::whereHas('pliStatuerHistory', function ($query) {
                                                $query->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()])
                                                    ->whereIn('id', function ($subQuery) {
                                                        $subQuery->selectRaw('MAX(id)')
                                                                ->from('pli_statuer_history')
                                                                ->groupBy('pli_id');
                                                    }); //  Sélectionne le dernier statut de chaque pli
                                            })->count();



                                    // $plisStatuesMois = \App\Models\Pli::whereHas('pliStatuerHistory', function ($query) use ($dateDebutMois) {
                                    //     $query->whereBetween('updated_at', [$dateDebutMois, Carbon::now()]);
                                    // })->count();


                                    $plisStatuesMois = \App\Models\Pli::whereHas('pliStatuerHistory', function ($query) {
                                            $query->whereBetween('updated_at', [Carbon::now()->startOfMonth(), Carbon::now()])
                                                ->whereIn('id', function ($subQuery) {
                                                    $subQuery->selectRaw('MAX(id)')
                                                            ->from('pli_statuer_history')
                                                            ->groupBy('pli_id');
                                                }); //  Sélectionne le dernier statut de chaque pli
                                        })->count();

                                    // Pour le semestre
                                    $plisStatuesTrimestre = \App\Models\Pli::whereHas('pliStatuerHistory', function ($query) use ($dateDebutTrimestre) {
                                        $query->whereBetween('updated_at', [ Carbon::now()->startOfQuarter(), Carbon::now()]);
                                    })->count();

                                    $plisStatuesSemestre = \App\Models\Pli::whereHas('pliStatuerHistory', function ($query) use ($dateDebutSemestre) {
                                        $query->whereBetween('updated_at', [ Carbon::now()->subMonths(6)->startOfMonth(), Carbon::now()]);
                                    })->count();

                                    //  Calcul des variations

                                    if (!function_exists('calculVariation')) {
                                    function calculVariation($actuel, $precedent) {
                                        return ($precedent > 0) ? ceil((($actuel - $precedent) / $precedent) * 100) : 0;
                                    } }

                                    $variationSemaine = calculVariation($plisSemaineActuelle, $plisSemainePrecedente);
                                    $variationMois = calculVariation($plisMoisActuel, $plisMoisPrecedent);
                                    $variationTrimestre = calculVariation($plisTrimestreActuel, $plisTrimestrePrecedent);
                                    $variationSemestre = calculVariation($plisSemestreActuel, $plisSemestrePrecedent);

                                @endphp

{{-- --------------------------------------------------------------------------------------------- --}}


                    @php
                        //  Initialisation de la requête principale

                        $query = \App\Models\Pli::query();
                        $filtresAppliques = [];

                        if (request()->filled('combine')) {
                            $filtres = request()->input('combine');

                            if (in_array('etat', $filtres) && request()->filled('etat_pli')) {
                                $query->whereHas('pliStatuerHistory', function ($q) {
                                    $q->orderBy('updated_at', 'desc')->limit(1);
                                });
                                $filtresAppliques[] = "📦 État du pli : " . request()->input('etat_pli');
                            }

                            // if (in_array('periode', $filtres) && request()->filled('date_debut') && request()->filled('date_fin')) {
                            //     $query->whereBetween('created_at', [request()->input('date_debut'), request()->input('date_fin')]);
                            //     $filtresAppliques[] = "📅 Période : du " . request()->input('date_debut') . " au " . request()->input('date_fin');
                            // }



                            if (in_array('periode', $filtres) && request()->filled('date_debut') && request()->filled('date_fin')) {
                                    $query->whereBetween('created_at', [request()->input('date_debut'), request()->input('date_fin')]);

                                    // ✅ Formatage des dates au format `dd-mm-yyyy`
                                    $dateDebut0 = \Carbon\Carbon::parse(request()->input('date_debut'))->format('d-m-Y');
                                    $dateFin0 = \Carbon\Carbon::parse(request()->input('date_fin'))->format('d-m-Y');

                                    $filtresAppliques[] = "📅 Période : du " . $dateDebut0 . " au " . $dateFin0;
                                }


                            if (in_array('client', $filtres) && request()->filled('client')) {
                                $query->whereHas('user', function ($q) {
                                    $q->where('name', request()->input('client'));
                                });
                                $filtresAppliques[] = "👤 Client : " . request()->input('client');
                            }
                        }

                        //  Récupération des données filtrées
                        $totalPlis = $query->count();

                        //  Gestion des jours ouvrables
                        $dateDebut = request()->filled('date_debut') ? request()->input('date_debut') : now()->startOfYear();
                        $dateFin = request()->filled('date_fin') ? request()->input('date_fin') : now();
                        $nbJoursTotal = \Carbon\Carbon::parse($dateDebut)->diffInDays(\Carbon\Carbon::parse($dateFin)) + 1;
                        $nbJoursOuvrables = ($nbJoursTotal > 0) ? floor(($nbJoursTotal / 7) * 6) : 1;

                        //  Calcul du nombre de semaines et mois
                        $nbSemaines = ($nbJoursOuvrables > 0) ? ceil($nbJoursOuvrables / 6) : 1;
                        $nbMois = (\Carbon\Carbon::parse($dateDebut)->diffInMonths(\Carbon\Carbon::parse($dateFin)) + 1) > 0 ? \Carbon\Carbon::parse($dateDebut)->diffInMonths(\Carbon\Carbon::parse($dateFin)) + 1 : 1;

                        //  Calcul des moyennes arrondies
                        $moyenneQuotidienne = ($nbJoursOuvrables > 0) ? round($totalPlis / $nbJoursOuvrables) : 0;
                        $moyenneHebdomadaire = ($nbSemaines > 0) ? round($totalPlis / $nbSemaines) : 0;
                        $moyenneMensuelle = ($nbMois > 0) ? round($totalPlis / $nbMois) : 0;

                        //  Déclaration et calcul de l’évolution en pourcentage
                        $variation = ($moyenneQuotidienne > 0) ? round((($totalPlis - $moyenneQuotidienne * $nbJoursOuvrables) / $moyenneQuotidienne) * 100) : 0;
                    @endphp


                        {{-- Pour les moyenne --------------- --}}
                     @php
                        //  Arrondi au chiffre supérieur
                        $moyenneQuotidienne = ceil($moyenneQuotidienne);
                        $moyenneHebdomadaire = ceil($moyenneHebdomadaire);
                        $moyenneMensuelle = ceil($moyenneMensuelle);
                        $variation = ceil($variation);
                    @endphp

{{-- ------------------------------------------------ --}}

                 @php
                        //  Initialisation des filtres appliqués
                        $messageRecherche = "🔍 Recherche effectuée : ";

                        if (count($filtresAppliques) > 0) {
                            $messageRecherche .= implode(" | ", $filtresAppliques) . ". ";
                        } else {
                            $messageRecherche .= "Aucun filtre spécifique appliqué, affichage des résultats complets.";
                        }

                        //  Ajout du nombre total de plis trouvés
                        if ($totalPlis > 0) {
                            $messageRecherche .= "📦 Nombre de plis trouvés : " . number_format($totalPlis) . ". ";
                        } else {
                            $messageRecherche .= "😕 Aucun pli trouvé avec les filtres sélectionnés.";
                        }

                        //  Indication de la tendance (hausse ou baisse)
                        if ($totalPlis > 0) {
                            if ($variation > 0) {
                                $messageRecherche .= "📈 Activité en hausse (+{$variation}%).";
                            } elseif ($variation < 0) {
                                $messageRecherche .= "📉 Activité en baisse ({$variation}%).";
                            } else {
                                $messageRecherche .= "📊 Activité stable.";
                            }
                        }

                     @endphp
            {{-- ---------------------------------------------------------- --}}

                        @php
                            // use Carbon\Carbon;

                            //  Date actuelle et hier
                            $aujourdHui = Carbon::today();
                            $hier = Carbon::yesterday();

                            //  Nombre de plis ramassés aujourd’hui via attributions
                            $plisRamassesAuj = \App\Models\Pli::whereHas('attributions', function ($q) use ($aujourdHui) {
                                $q->whereDate('created_at', Carbon::today())->whereNotNull('coursier_ramassage_id');
                            })->count();



                            $plisRamassesAvecStatutAuj = \App\Models\Pli::whereHas('attributions', function ($q) {
                                    $q->whereDate('created_at', '<', Carbon::today()); //  Exclut les plis ramassés aujourd'hui
                                })->whereHas('pliStatuerHistory', function ($q) {
                                    $q->whereDate('updated_at', Carbon::today()); //  Vérifie que le statut a été mis à jour aujourd'hui
                                })->count();



                            //  Nombre de plis ramassés hier via attributions
                            $plisRamassesHier = \App\Models\Pli::whereHas('attributions', function ($q) use ($hier) {
                                $q->whereDate('created_at', $hier)->whereNotNull('coursier_ramassage_id');
                            })->count();


                            //  Nombre de plis ramassés hier avec statut
                            // $plisRamassesAvecStatutHier = \App\Models\Pli::whereHas('attributions', function ($q) use ($hier) {
                            //     $q->whereDate('created_at', $hier)->whereNotNull('coursier_ramassage_id');
                            // })->whereHas('pliStatuerHistory')->count();


                            $plisRamassesAvecStatutHier = \App\Models\Pli::whereHas('attributions', function ($q) {
                                    $q->whereDate('created_at', '<', Carbon::yesterday()) //  Exclut les plis ramassés hier
                                    ->whereNotNull('coursier_ramassage_id');
                                })->whereHas('pliStatuerHistory', function ($q) {
                                    $q->whereDate('updated_at', Carbon::yesterday()); //  Vérifie que le statut a été mis à jour hier
                                })->count();


                        @endphp
{{-- --------------------------------------------- --}}
{{-- --------------------------------------------- --}}

                               @php
                //  Création du message récapitulatif
                $messageRecherche = "Recherche effectuée : ";

                if (request()->filled('date_debut') && request()->filled('date_fin')) {
                    $messageRecherche .= "Période du " . request()->input('date_debut') . " au " . request()->input('date_fin') . ". ";
                }

                if (request()->filled('client')) {
                    $messageRecherche .= "Filtre par client : " . request()->input('client') . ". ";
                }

                if (request()->filled('etat_pli')) {
                    $messageRecherche .= "État sélectionné : " . request()->input('etat_pli') . ". ";
                }

                if ($totalPlis > 0) {
                    $messageRecherche .= "Nombre de plis ''ramassés trouvés''  : " . number_format($totalPlis) . ". ";
                } else {
                    $messageRecherche .= "Aucun pli correspondant aux critères. ";
                }

                //  Ajout d'une conclusion sur l’activité
                if ($totalPlis > 0) {


                    if ($totalPlis > 0) {
                                    // $variation = ($moyenneQuotidienne > 0) ? round((($totalPlis - $moyenneQuotidienne * $nbJours) / $moyenneQuotidienne) * 100) : 0;
                                    $variation = ($moyenneQuotidienne > 0) ? round(((($totalPlis - ($moyenneQuotidienne * $nbJoursOuvrables)) / max($moyenneQuotidienne, 1)) * 100), 2) : 0;

                                    if ($variation > 50) {
                                        $messageRecherche .= "🚀 Forte croissance (+$variation%) : Opportunité de renforcer les opérations !";
                                    } elseif ($variation > 20) {
                                        $messageRecherche .= "📈 Bonne dynamique (+$variation%) : L’entreprise est sur une trajectoire positive.";
                                    } elseif ($variation > 0) {
                                        $messageRecherche .= "✅ Légère progression (+$variation%) : Croissance stable.";
                                    } elseif ($variation < -50) {
                                        $messageRecherche .= "⚠️ Baisse critique ($variation%) : Urgence stratégique !";
                                    } elseif ($variation < -20) {
                                        $messageRecherche .= "📉 Déclin marqué ($variation%) : À surveiller et ajuster.";
                                    } elseif ($variation < 0) {
                                        $messageRecherche .= "🔄 Léger recul ($variation%) : Analyse des facteurs en cours.";
                                    } else {
                                        $messageRecherche .= "🔍 Activité stable (0%) : Aucun changement significatif.";
                                    }
                        }


                }
            @endphp

            {{-- ----------------------------------------- --}}
            {{-- -------------------------------------- --}}

             @php

                                    //  Extraction sécurisée des dates de création pour mon graphique ---------------------

                                    // $dates = $query->pluck('created_at')->filter()->map(fn($date) => $date ? Carbon::parse($date)->format('d-m-Y') : null)->filter()->toArray();
                                    // $plisCrees = array_count_values($dates);

                                    // //  Extraction sécurisée des dates pour ramassage
                                    // $datesRamassage = $query->pluck('date_attribution_ramassage')->filter()->map(fn($date) => $date ? Carbon::parse($date)->format('d-m-Y') : null)->filter()->toArray();
                                    // $plisRamasses = array_count_values($datesRamassage);

                                    // //  Récupération des statuts les plus récents, en excluant les statuer_id non souhaités
                                    // $datesStatues = \App\Models\PliStatuerHistory::select(DB::raw('pli_id, MAX(created_at) as latest_status'))
                                    //     ->whereNotIn('statuer_id', [1]) //  Filtrage des statuts non souhaités
                                    //     ->groupBy('pli_id')
                                    //     ->pluck('latest_status')
                                    //     ->map(fn($date) => $date ? Carbon::parse($date)->format('d-m-Y') : null)->filter()->toArray();

                                    // $plisStatues = array_count_values($datesStatues);

                                    // //  Formatage JSON pour Chart.js
                                    // $jsonDates = json_encode(array_keys($plisCrees));
                                    // $jsonPlisCrees = json_encode(array_values($plisCrees));
                                    // $jsonPlisRamasses = json_encode(array_values($plisRamasses));
                                    // $jsonPlisStatues = json_encode(array_values($plisStatues));


                                                                        //  Vérification des dates de création des plis
                                    $dates = $query->pluck('created_at')
                                        ->filter(fn($date) => !is_null($date)) //  Filtre les valeurs nulles
                                        ->map(fn($date) => Carbon::parse($date)->format('d-m-Y'))
                                        ->toArray();

                                    $plisCrees = array_count_values($dates);

                                    $datesRamassage = $query->with('attributions')->get() // ✅ Charge les attributions avec `get()`
                                        ->pluck('attributions') // ✅ Pluck les attributions (collection d'objets)
                                        ->flatten() // ✅ Évite les structures imbriquées et récupère un tableau plat
                                        ->pluck('date_attribution_ramassage') // ✅ Extrait les dates de chaque attribution
                                        ->filter() // ✅ Supprime les valeurs nulles
                                        ->map(fn($date) => Carbon::parse($date)->format('d-m-Y')) // ✅ Convertit les dates au bon format
                                        ->toArray();

                                    $plisRamasses = array_count_values($datesRamassage);

                                    //  Récupération des statuts les plus récents
                                    $datesStatues = \App\Models\PliStatuerHistory::select(DB::raw('pli_id, MAX(created_at) as latest_status'))
                                        ->whereNotIn('statuer_id', [1]) //  Filtrage des statuts non souhaités
                                        ->groupBy('pli_id')
                                        ->pluck('latest_status')
                                        ->filter(fn($date) => !is_null($date))
                                        ->map(fn($date) => Carbon::parse($date)->format('d-m-Y'))
                                        ->toArray();

                                    $plisStatues = array_count_values($datesStatues);

                                    //  Formatage JSON pour Chart.js
                                    $jsonDates = json_encode(array_keys($plisCrees));
                                    $jsonPlisCrees = json_encode(array_values($plisCrees));
                                    $jsonPlisRamasses = json_encode(array_values($plisRamasses));
                                    $jsonPlisStatues = json_encode(array_values($plisStatues));

                         @endphp

{{-- -------------------------------------------------- --}}
{{-- ------------------------------------------------------- --}}

{{-- Code en haut --------------------------------------------------------------------------------------- --}}
{{-- Code en haut --------------------------------------------------------------------------------------- --}}

@section('stat-moy-day')

{{--  / Affiche de rsuletat -------------------------- --}}

{{-- Du blade ------------------------------------------------------------------------------- --}}

            @if(count($filtresAppliques) > 0)
                <div class="alert alert-warning text-center mt-3">
                    <h5>🔎 Filtres appliqués</h5>
                    <p>{{ implode(" | ", $filtresAppliques) }} @php
                        echo' <a href="#pliChart" id="" class="text text-info">Voir le graphique</a>';
                        @endphp
                    </p>
                    {{-- <p>{{ \Carbon\Carbon::parse($_GET['date_debut'])->format('d-m-Y') }}  au {{ \Carbon\Carbon::parse($_GET['date_fin'])->format('d-m-Y') }} </p> --}}
                </div>
                @endif

        <div style="display: flex; gap: 1%; justify-content: center; margin-bottom: 10px; height:350px; overflow:auto; background-color:#f0f0f0; padding:2px">
                <div class="rows" style="display:">
                        <div>
                            <small>
                            Les étapes de traitement d'un pli sont les suivantes :
                            <i>
                                <ol>
                                    <li>Création de plis par le client</li>
                                    <li>Ramassage par un coursier d'IVOIRRAPID*</li>
                                    <li>Attribution à un coursier pour le dépôt ou livraison du pli</li>
                                    <li>Changement de statut ou état du pli (Annulé, Déposé, Refusé)</li>
                                </ol>
                            </i>
                        </small>
                    </div>
{{-- ---------------------------------------Prmier affichage du statistique ------------------------------ --}}
                    <div class="alert alert-info text-center mt-3">
                        <h5>📊 Récapitulatif des statistiques générals (Moyennes) </h5>
                        {{-- <p>📅 Entreprise active 6 jours sur 7 → Calculs basés sur les jours ouvrables.</p> --}}
                        <p>📅 6 jours sur 7 → Calculs basés sur les jours ouvrables.</p>
                        {{-- <p>Nombre total de plis : <strong>{{ number_format($totalPlis) }}</strong></p> --}}
                    <div>
                        @php
                            echo$msg;
                        @endphp
         </div>

                      <p>Nombre total de plis depuis </p>


                            @if (request()->filled('date_debut') && request()->filled('date_fin'))
                                <p>le
                                    {{ \Carbon\Carbon::parse(request()->input('date_debut'))->format('d-m-Y') }}
                                    au
                                    {{ \Carbon\Carbon::parse(request()->input('date_fin'))->format('d-m-Y') }} :

                                      <span style="color:red;font-weight:bolder"><u>{{ $totalPlis }}</u></span>
                                </p>
                            @else
                                <p>le 1 Janvier {{ date('Y') }}</p>
                            @endif
                    <p>Moyenne quotidienne : <strong>{{ $moyenneQuotidienne }}</strong> plis/jour ouvrable</p>
                    <p>Moyenne hebdomadaire : <strong>{{ $moyenneHebdomadaire }}</strong> plis/semaine</p>
                    <p>Moyenne mensuelle : <strong>{{ $moyenneMensuelle }}</strong> plis/mois</p>
                    {{-- <p>Variation : <strong>{{ $variation }}%</strong> par rapport à la moyenne</p> --}}
                    <!--  Le mot "Variation" avec survol -->

               <!--  Le mot "Variation" cliquable --------------------------------------------------------------------------------------->
                        <p>📊 <span id="variationHover" style="cursor: pointer; text-decoration: underline;">Variation</span> : <strong>{{ $variation }}%</strong> par rapport à la moyenne</p>

                        <!--  Modal Bootstrap 5 -->
                        <div class="modal fade" id="modalVariation" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">ℹ️ Explication de la Variation</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>La variation indique l'évolution du nombre de plis par rapport à la moyenne quotidienne des jours ouvrables.</p>
                                        <p>🔹 Si la variation est positive (+X%), cela signifie qu'on a eu plus de plis que d’habitude.</p>
                                        <p>🔹 Si la variation est négative (-X%), l’activité est plus faible que la moyenne.</p>
                                        <p>🔹 Si la variation est proche de 0%, l’activité est stable.</p>
                                        <p>📊 Calcul :
                                        <code>(Total des plis - Moyenne quotidienne × Jours ouvrables) / Moyenne quotidienne × 100</code></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--  Activation du modal au clic -->
                        <script>
                            document.getElementById('variationHover').addEventListener('click', function() {
                                let modal = new bootstrap.Modal(document.getElementById('modalVariation'));
                                modal.show();
                            });
                        </script>

                </div>

    {{-- ------------------------------------------------------------------------------------------------------------------ --}}
            </div> <!--  Ajout de la fermeture correcte -->


          <div class="resultat-rech" style="display:flex">
                   <div>


                                <div class="row">
                                            <!--  Card pour afficher les statistiques -->

        {{-- Affiche dans le carré -------------------------------------------------}}
                        <div class="col-md" style="width:100%">
                            <div class="card text-center shadow-lg">
                                <div class="card-body">
                                    <h5 class="card-title">📊 Statistiques des Plis</h5>

                             <p>Nombre total de plis depuis </p>
                                @if (request()->filled('date_debut') && request()->filled('date_fin'))
                                         <p>le
                                                {{ \Carbon\Carbon::parse(request()->input('date_debut'))->format('d-m-Y') }}
                                                au
                                                {{ \Carbon\Carbon::parse(request()->input('date_fin'))->format('d-m-Y') }}

                                            : <span style="color:red;font-weight:bolder"><u>{{ $totalPlis }}</u></span>
                                        </p>
                                        @else
                                            {{-- <p>le 1 Janvier {{ date('Y') }}</p> --}}
                                             <p class="card-text">Depuis le <strong>1er janvier</strong> </p>

                                             <h3 class="text-primary">
                                                     <span style="color:red;font-weight:bolder"><u>{{ $totalPlis }}</u></span>

                                            </h3> <!--  Total des plis -->

                                        @endif


                                    {{-- <p class="text-muted">Plis créés aujourd'hui : <strong>{{ number_format($plisParJour[count($plisParJour) - 1]) }}</strong></p> --}}
                                    @php
                                        $plisAujourdHui = \App\Models\Pli::whereDate('created_at', \Carbon\Carbon::today())->count();
                                    @endphp
                                    <p class="text-muted">📅 Plis créés aujourd'hui : <strong>{{ number_format($plisAujourdHui) }}</strong></p>

                                    <div class="alert alert-info text-center mt-3">
                                        {{-- <h5>📦 Récapitulatif des plis ramassés</h5> --}}
                                        <p>📅 Aujourd'hui : <strong>{{ number_format($plisRamassesAuj) }}</strong> plis ramassés</p>
                                        <p>📅 Aujourd'hui avec statut : <strong>{{ number_format($plisRamassesAvecStatutAuj) }}</strong></p>
                                        <hr>
                                        <p>📅 Hier : <strong>{{ number_format($plisRamassesHier) }}</strong> plis ramassés</p>
                                        <p>📅 Hier avec statut : <strong>{{ number_format($plisRamassesAvecStatutHier) }}</strong></p>
                                    </div>

                                </div>
                            </div>
                        </div>

                {{-- -------------------------------------- --}}
                   </div>
                   <div>

                   </div>
          </div>

        </div>
                             <div class="alert alert-info text-center mt-3">
                                        <h5>📊 Évolution des plis ramassés et ceux statué sur différentes périodes</h5>

                                        <p>📅 Semaine actuelle : <strong>{{ number_format($plisSemaineActuelle) }}</strong> plis</p>
                                        <p>📦 Plis statués cette semaine : <strong>{{ number_format($plisStatuesSemaine) }}</strong></p>
                                        <p>📊 Variation hebdomadaire : <strong>{{ $variationSemaine }}%</strong></p>
                                        <hr>

                                        <p>📅 Mois actuel : <strong>{{ number_format($plisMoisActuel) }}</strong> plis</p>
                                        <p>📦 Plis statués ce mois-ci : <strong>{{ number_format($plisStatuesMois) }}</strong></p>
                                        <p>📊 Variation mensuelle : <strong>{{ $variationMois }}%</strong></p>
                                        <hr>

                                        <p>📅 Trimestre actuel : <strong>{{ number_format($plisTrimestreActuel) }}</strong> plis</p>
                                        <p>📦 Plis statués ce trimestre : <strong>{{ number_format($plisStatuesTrimestre) }}</strong></p>
                                        <p>📊 Variation trimestrielle : <strong>{{ $variationTrimestre }}%</strong></p>
                                        <hr>
                                        <p>📅 Semestre actuel : <strong>{{ number_format($plisSemestreActuel) }}</strong> plis</p>
                                        <p>📦 Plis statués ce semestre : <strong>{{ number_format($plisStatuesSemestre) }}</strong></p>
                                        <p>📊 Variation semestrielle : <strong>{{ $variationSemestre }}%</strong></p>
                                    </div>
                            {{-- </div> --}}

                        </div> <!--  Fermeture complète de `container` -->


        <form action="" method="GET" class="container">
            <br>

            <h3>Visualisation des activités</h3>
            <hr>

            <h5 class="mb-3">🔎 Combiner les recherches</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" name="combine[]" id="etat" value="etat" class="form-check-input">
                        <label for="etat" class="form-check-label">📦 État du pli</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" name="combine[]" id="periode" value="periode" class="form-check-input">
                        <label for="periode" class="form-check-label">📅 Période</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" name="combine[]" id="client" value="client" class="form-check-input">
                        <label for="client" class="form-check-label">👤 Client</label>
                    </div>
                </div>
            </div>

            <hr>

            <!--  Période -->
            <h5 class="mt-4">📅 Période</h5>
            <div class="row">
                <div class="col-md-6">
                    <label for="date_debut">Date début</label>
                    <input type="date" name="date_debut" id="date_debut" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="date_fin">Date fin</label>
                    <input type="date" name="date_fin" id="date_fin" class="form-control">
                </div>
            </div>

            <hr>

            <!--  État du pli -->  <!--  Sélection du client -->

            <h5 class="mt-4">📦 Par état de plis</h5>

            <div class="row align-items-end">
                    <!--  État du pli -->
                    <div class="col-md-6">
                        <label for="etat_pli">📦 Choisir l'état des plis</label>
                        <select name="etat_pli" id="etat_pli" class="form-control">
                            <option value="">-- Choisir un état --</option>
                            <option value="Plis ramassés">Plis ramassés</option>
                            <option value="Plis statués">Plis statués</option>
                        </select>
                    </div>

                    <!--  Sélection du client -->
                    <div class="col-md-6">
                        @php
                            $clientC = \App\Models\User::select('id', 'name')->orderBy('name', 'asc')->get();
                        @endphp

                        <label for="client">👤 Choisir un client</label>
                        <input type="text" name="client" id="client" class="form-control" placeholder="Tapez le nom d'un client..." list="clientList">
                        <datalist id="clientList">
                            <option value="">Tous les clients</option>
                            @foreach ($clientC as $clientc)
                                <option value="{{ $clientc->name }}">{{ $clientc->name }}</option>
                            @endforeach
                        </datalist>
                    </div>
                </div>
                  <!--  Boutons -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">🔍 Filtrer</button>
                <button id="refreshPageBtn" class="btn btn-text">🔄 Rafraîchir la page</button>
            </div>
            <hr>
        </form>
{{--   logique  Message de rechercxhe pour le graphique ----------------------------------------------------- --}}
    {{-- ------------------------------------------- --}}
            <div class="alert alert-info text-center mt-3">
                <h5>🔍 Résumé de la recherche</h5>
                <p>{{ $messageRecherche }}</p>
            </div>
    {{-- ----------------------------------- --}}
{{-- ------------------------------------------------------------------- --}}
{{-- /Fin message sur meessage de recherche --}}
      <!--  Graphique des plis -->

            <div class="text-cente">
                <button id="btnJour" class="btn btn-primary">📅 Afficher par Jour</button>
                <button id="btnSemaine" class="btn btn-success">🗓️ Afficher par Semaine</button>
                <button id="btnMois" class="btn btn-warning">📆 Afficher par Mois</button> <!--  Nouveau bouton -->
            </div>

            <div style="display:flex">

                  @if(count($filtresAppliques) > 0)
                <div class="alert alert-warning text-center mt-3">
                    <h5>🔎 Filtres appliqués</h5>
                    <p>{{ implode(" | ", $filtresAppliques) }} @php
                        // echo' <a href="#pliChart" id="" class="text text-info">Voir le graphique</a>';

                        @endphp
                    </p>
                    {{-- <p>{{ \Carbon\Carbon::parse($_GET['date_debut'])->format('d-m-Y') }}  au {{ \Carbon\Carbon::parse($_GET['date_fin'])->format('d-m-Y') }} </p> --}}
                </div>
                @endif
                    <div style="padding: 15px; background-color: #f8f9fa; border-radius: 8px;">
                        <p style="font-size: 16px; font-weight: bold; color: #343a40;">
                            📦 Total Plis créés cette période : <span style="color: #007bff;">{{ array_sum($plisCrees) }}</span>
                        </p>
                        <p style="font-size: 16px; font-weight: bold; color: #343a40;">
                            🚛Total Plis ramassés cette période : <span style="color: #28a745;">{{ array_sum($plisRamasses) }}</span>
                        </p>
                        <p style="font-size: 16px; font-weight: bold; color: #343a40;">
                            ✅Total Plis statués cette période : <span style="color: #ffc107;">{{ array_sum($plisStatues) }}</span>
                        </p>
                    </div>
                    {{-- <div style="font-style:oblique">
                        <h5> Quelque astuces pour le graphique</h5>
                    </div> --}}
            </div>

            {{-- <canvas id="pliChart"></canvas>

             <div class="col-md" style="height:; width:90%; margin:auto;padding-right:10px">
                <canvas id="pliChart"></canvas>
            </div> --}}

            {{-- Script pour graphique -------------------------------------------------- --}}

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


            {{-- <script>
                const ctx = document.getElementById('pliChart').getContext('2d');

                let labels = {!! $jsonDates !!}; // 📅 Dates formatées
                let dataPlisCrees = {!! $jsonPlisCrees !!}; // 📊 Plis créés
                let dataPlisRamasses = {!! $jsonPlisRamasses !!}; // 📦 Plis ramassés
                let dataPlisStatues = {!! $jsonPlisStatues !!}; // ✅ Plis statués

                let pliChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Plis créés',
                                data: dataPlisCrees,
                                borderColor: '#007bff',
                                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                                fill: true
                            },
                            {
                                label: 'Plis ramassés',
                                data: dataPlisRamasses,
                                borderColor: '#28a745',
                                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                                fill: true
                            },
                            {
                                label: 'Plis statués',
                                data: dataPlisStatues,
                                borderColor: '#dc3545',
                                backgroundColor: 'rgba(220, 53, 69, 0.2)',
                                fill: true
                            }
                        ]
                    }
                });

                // ✅ Rafraîchir uniquement le graphique
                document.getElementById("refreshChartBtn").addEventListener("click", function() {
                    pliChart.update(); // 🔄 Met à jour le graphique
                });
            </script> --}}



            <script>
    document.addEventListener("DOMContentLoaded", function () {
        // ✅ Sélection des boutons
        let btnJour = document.getElementById("btnJour");
        let btnSemaine = document.getElementById("btnSemaine");
        let btnMois = document.getElementById("btnMois");

        // ✅ Fonction pour regrouper par semaine
        function regrouperParSemaine() {
            let semaines = {};
            labels.forEach((date, index) => {
                let semaine = new Date(date).getWeek();
                semaines[semaine] = (semaines[semaine] || 0) + dataPlisCrees[index];
            });

            return {
                labels: Object.keys(semaines),
                data: Object.values(semaines)
            };
        }

        // ✅ Fonction pour regrouper par mois
        function regrouperParMois() {
            let mois = {};
            labels.forEach((date, index) => {
                let moisKey = date.substring(3, 10); // Format MM-YYYY (ex: "03-2025")
                mois[moisKey] = (mois[moisKey] || 0) + dataPlisCrees[index];
            });

            return {
                labels: Object.keys(mois),
                data: Object.values(mois)
            };
        }

        // ✅ Activation des boutons
        btnJour.addEventListener("click", function () {
            pliChart.data.labels = labels;
            pliChart.data.datasets[0].data = dataPlisCrees;
            pliChart.update();
        });

        btnSemaine.addEventListener("click", function () {
            let semaineData = regrouperParSemaine();
            pliChart.data.labels = semaineData.labels;
            pliChart.data.datasets[0].data = semaineData.data;
            pliChart.update();
        });

        btnMois.addEventListener("click", function () {
            let moisData = regrouperParMois();
            pliChart.data.labels = moisData.labels;
            pliChart.data.datasets[0].data = moisData.data;
            pliChart.update();
        });

        //  Fonction pour calculer la semaine d'une date
        Date.prototype.getWeek = function () {
            let date = new Date(this);
            date.setHours(0, 0, 0, 0);
            date.setDate(date.getDate() + 5 - (date.getDay() || 7));
            let yearStart = new Date(date.getFullYear(), 0, 1);
            return Math.ceil((((date - yearStart) / 86400000) + 1) / 7);
        };
    });
</script>

{{-- ---------------------------------------------------------------------------------------------------------- --}}
            {{-- ✅ Affichage des données pour validation --}}


{{-- ✅ Conteneur du graphique --}}

<div class="col-md" style="width: 90%; margin: auto; padding-right: 10px;">
    <canvas id="pliChart"></canvas>
</div>

{{-- ✅ Chargement de Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('pliChart').getContext('2d');

    // ✅ Formatage et tri des données
    let labels = {!! json_encode(array_keys($plisCrees)) !!}.sort((a, b) => new Date(a) - new Date(b));
    let dataPlisCrees = {!! json_encode(array_values($plisCrees)) !!};
    let dataPlisRamasses = {!! json_encode(array_values($plisRamasses)) !!};
    let dataPlisStatues = {!! json_encode(array_values($plisStatues)) !!};

    let pliChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: '📦 Plis créés',
                    data: dataPlisCrees,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    fill: true
                },
                {
                    label: '🚛 Plis ramassés',
                    data: dataPlisRamasses,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    fill: true
                },
                {
                    label: '✅ Plis statués',
                    data: dataPlisStatues,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.2)',
                    fill: true
                }
            ]
        }
    });

    // ✅ Bouton de mise à jour du graphique
    document.getElementById("refreshChartBtn").addEventListener("click", function() {
        pliChart.update();
    });

    // ✅ Fonction pour regrouper les données par semaine
    function regrouperParSemaine() {
        let semaines = {};
        labels.forEach((date, index) => {
            let semaine = getWeekNumber(date);
            semaines[semaine] = (semaines[semaine] || 0) + dataPlisCrees[index];
        });

        return {
            labels: Object.keys(semaines),
            data: Object.values(semaines)
        };
    }

    // ✅ Fonction pour regrouper les données par mois
    function regrouperParMois() {
        let mois = {};
        labels.forEach((date, index) => {
            let moisKey = date.substring(3, 10); // Format MM-YYYY (ex: "03-2025")
            mois[moisKey] = (mois[moisKey] || 0) + dataPlisCrees[index];
        });

        return {
            labels: Object.keys(mois),
            data: Object.values(mois)
        };
    }

    // ✅ Activation des boutons pour filtrer les données
    document.getElementById("btnJour").addEventListener("click", function () {
        pliChart.data.labels = labels;
        pliChart.data.datasets[0].data = dataPlisCrees;
        pliChart.update();
    });

    document.getElementById("btnSemaine").addEventListener("click", function () {
        let semaineData = regrouperParSemaine();
        pliChart.data.labels = semaineData.labels;
        pliChart.data.datasets[0].data = semaineData.data;
        pliChart.update();
    });

    document.getElementById("btnMois").addEventListener("click", function () {
        let moisData = regrouperParMois();
        pliChart.data.labels = moisData.labels;
        pliChart.data.datasets[0].data = moisData.data;
        pliChart.update();
    });

    // ✅ Fonction pour calculer la semaine d'une date
    function getWeekNumber(d) {
        d = new Date(d);
        d.setHours(0, 0, 0, 0);
        d.setDate(d.getDate() + 3 - (d.getDay() + 6) % 7);
        let week1 = new Date(d.getFullYear(), 0, 4);
        return 1 + Math.round(((d - week1) / 86400000 - 3 + (week1.getDay() + 6) % 7) / 7);
    }
</script>

@endsection
