
{{-- Le blade ------------------------------------------------------------- --}}
@php
use Carbon\Carbon;

//  Définition des périodes
$debutJour = Carbon::today();
$debutHier = Carbon::yesterday();
$debutSemaine = Carbon::now()->subDays(7)->startOfWeek();
$debutMois = Carbon::now()->startOfMonth();
$debutTrimestre = Carbon::now()->subMonths(3)->startOfMonth();
$debutSemestre = Carbon::now()->subMonths(6)->startOfMonth();
$debutAnnee = Carbon::now()->startOfYear();

//  Récupération des clients avec statistiques corrigées
$listeClients = \App\Models\User::withCount([
    'plis as total_plis',

    //  AUJOURD'HUI
    'plis as plis_crees_aujourdhui' => fn($q) => $q->whereBetween('created_at', [$debutJour, Carbon::now()]),
    'plis as plis_ramasses_aujourdhui' => fn($q) => $q->whereHas('attributions', fn($q) => $q->whereBetween('date_attribution_ramassage', [$debutJour, Carbon::now()])),
    'plis as plis_traites_aujourdhui' => fn($q) => $q->whereHas('pliStatuerHistory', fn($q) => $q
        ->whereIn('statuer_id', [3, 4, 5])
        ->whereBetween('date_changement', [$debutJour, Carbon::now()])
        ->orderByDesc('date_changement')
        ->distinct()),

    //  HIER
    'plis as plis_crees_hier' => fn($q) => $q->whereBetween('created_at', [$debutHier, $debutJour]),
    'plis as plis_ramasses_hier' => fn($q) => $q->whereHas('attributions', fn($q) => $q->whereBetween('date_attribution_ramassage', [$debutHier, $debutJour])),
    'plis as plis_traites_hier' => fn($q) => $q->whereHas('pliStatuerHistory', fn($q) => $q
        ->whereIn('statuer_id', [3, 4, 5])
        ->whereBetween('date_changement', [$debutHier, $debutJour])
        ->orderByDesc('date_changement')
        ->distinct()),

    //  SEMAINE DERNIÈRE
    'plis as plis_crees_semaine' => fn($q) => $q->whereBetween('created_at', [$debutSemaine, Carbon::now()]),
    'plis as plis_ramasses_semaine' => fn($q) => $q->whereHas('attributions', fn($q) => $q->whereBetween('date_attribution_ramassage', [$debutSemaine, Carbon::now()])),
    'plis as plis_traites_semaine' => fn($q) => $q->whereHas('pliStatuerHistory', fn($q) => $q
        ->whereIn('statuer_id', [3, 4, 5])
        ->whereBetween('date_changement', [$debutSemaine, Carbon::now()])
        ->orderByDesc('date_changement')
        ->distinct()),

    //  MOIS DERNIER
    'plis as plis_crees_mois' => fn($q) => $q->whereBetween('created_at', [$debutMois, Carbon::now()]),
    'plis as plis_ramasses_mois' => fn($q) => $q->whereHas('attributions', fn($q) => $q->whereBetween('date_attribution_ramassage', [$debutMois, Carbon::now()])),
    'plis as plis_traites_mois' => fn($q) => $q->whereHas('pliStatuerHistory', fn($q) => $q
        ->whereIn('statuer_id', [3, 4, 5])
        ->whereBetween('date_changement', [$debutMois, Carbon::now()])
        ->orderByDesc('date_changement')
        ->distinct()),

    //  TOTAL DES PLIS TRAITÉS DEPUIS LA CRÉATION

        // 🔹 TOTAL DES PLIS DEPUIS LA CRÉATION (C/R/T)
    'plis as total_crees' => function ($q) {
        $q->whereNotNull('created_at');
    },
    'plis as total_ramasses' => function ($q) {
        $q->whereHas('attributions');
    },



    'plis as total_traites' => fn($q) => $q->whereHas('pliStatuerHistory', fn($q) => $q
        ->whereIn('statuer_id', [3, 4, 5])
        ->orderByDesc('date_changement')
        ->distinct()),
])->get();
@endphp

                @php
                    //  Trier les clients en fonction des plis ramassés (du plus actif au moins actif)
                    $listeClients = $listeClients->sortByDesc(fn($client) => $client->total_ramasses);
                    @endphp


{{-- ---------------------------------------------------------------------------------------------------------- --}}

<!-- Modal pour afficher les clients actifs et inactifs -->

    <div class="modal fade modal-lg " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl " >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Clients actifs / Client inactifs</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

            <style>
                    .btn.active {
                    font-weight: bold;
                        border-bottom: 3px solid #007bff;
                    }

            </style>
                <style>
                    .table-container {
                        position: relative;
                        max-height: 500px; /* Ajuste selon besoin */
                        overflow-y: auto; /* Ajoute un défilement si nécessaire */
                    }

                    .total-row {
                        position: sticky;
                        bottom: 0;
                        background-color: #343a40; /*  Couleur sombre pour se démarquer */
                        color: white; /*  Texte en blanc pour un bon contraste */
                        font-weight: bold;
                        border-top: 3px solid black; /*  Ligne de séparation nette */
                    }
                </style>

                    <div style="height: 500px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                        <input type="text" id="searchClient" placeholder="Rechercher un client..." class="form-control mb-2">
                      <div class="table-container">

           <div class="table-container">

                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Nom du client</th>
                                <th>AJ. (C/R/T)</th> <!-- Aujourd'hui -->
                                <th>Hier (C/R/T)</th> <!-- Hier -->
                                <th>S. Der (C/R/T)</th> <!-- Semaine Dernière -->
                                <th>M. Der (C/R/T)</th> <!-- Mois Dernier -->
                                <th>Total (C/R/T)</th> <!--  Total depuis la création -->
                                <th>D. Ins.</th> <!-- Date d'inscription -->
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @foreach ($listeClients as $client)
                                <tr>
                                    <td><strong>{{ $client->name }}    ({{ $client->Telephone }} /  ({{ $client->Cellulaire }} )  </strong></td>
                                    <td>{{ $client->plis_crees_aujourdhui }} / {{ $client->plis_ramasses_aujourdhui }} / {{ $client->plis_traites_aujourdhui }}</td>
                                    <td>{{ $client->plis_crees_hier }} / {{ $client->plis_ramasses_hier }} / {{ $client->plis_traites_hier }}</td>
                                    <td>{{ $client->plis_crees_semaine }} / {{ $client->plis_ramasses_semaine }} / {{ $client->plis_traites_semaine }}</td>
                                    <td>{{ $client->plis_crees_mois }} / {{ $client->plis_ramasses_mois }} / {{ $client->plis_traites_mois }}</td>
                                    <td><strong>{{ $client->total_crees }} / {{ $client->total_ramasses }} / {{ $client->total_traites }}</strong></td> <!--  Total depuis la création -->
                                    <td>{{ $client->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                                 <tr class="total-row">
                               <td><strong>Total</strong></td>
                                    <td><strong>{{ $listeClients->sum('plis_crees_aujourdhui') }} / {{ $listeClients->sum('plis_ramasses_aujourdhui') }} / {{ $listeClients->sum('plis_traites_aujourdhui') }}</strong></td>
                                    <td><strong>{{ $listeClients->sum('plis_crees_hier') }} / {{ $listeClients->sum('plis_ramasses_hier') }} / {{ $listeClients->sum('plis_traites_hier') }}</strong></td>
                                    <td><strong>{{ $listeClients->sum('plis_crees_semaine') }} / {{ $listeClients->sum('plis_ramasses_semaine') }} / {{ $listeClients->sum('plis_traites_semaine') }}</strong></td>
                                    <td><strong>{{ $listeClients->sum('plis_crees_mois') }} / {{ $listeClients->sum('plis_ramasses_mois') }} / {{ $listeClients->sum('plis_traites_mois') }}</strong></td>
                                    <td><strong>{{ $listeClients->sum('total_crees') }} / {{ $listeClients->sum('total_ramasses') }} / {{ $listeClients->sum('total_traites') }}</strong></td>
                                    <td></td> <!--  La colonne Date Inscription reste vide pour cette ligne -->
                                </tr>

                        </tbody>
                    </table>
                </div>
                        </div>
                    </div>

                  <script>
                        document.getElementById('searchClient').addEventListener('input', function() {
                            let filter = this.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                            let rows = document.querySelectorAll('#tableBody tr');

                            rows.forEach(row => {
                                let matchFound = false;

                                //  Vérifier chaque cellule pour une correspondance
                                Array.from(row.cells).forEach(cell => {
                                    let text = cell.textContent.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                                    if (text.includes(filter)) {
                                        matchFound = true;
                                        cell.style.backgroundColor = "#ffff99"; //  Met en surbrillance la cellule correspondante
                                    } else {
                                        cell.style.backgroundColor = ""; // 🔄 Réinitialise si pas de match
                                    }
                                });

                                //  Affiche uniquement les lignes contenant un match
                                row.style.display = matchFound ? '' : 'none';
                            });
                        });
                        </script>
            {{-- --------------------------------------------------- --}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
      </div>
    </div>
  </div>
</div>
    <style>
            .table-container {
                max-height: 500px; /*  Fixe la hauteur de la table */
                overflow-y: auto;  /*  Active le défilement vertical */
                border: 1px solid #ddd;
            }

            .table thead {
                position: sticky;
                top: 0;
                background-color: #00172f; /*  Fixe l'en-tête avec un fond foncé */
                color: white; /*  Texte en blanc pour le contraste */
                z-index: 1000;
            }
</style>

























{{-- -------------------------------------------------------------------- --}}

 <div class="modal fade modal-xl bg-danger" id="exampleModal72" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
            <h4 style="color:red">Nombres de plis sans retour après 72 H</h4>
        </h5>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

            {{-- ------------------------------------------------ --}}
                @php

                $limite72h = Carbon::now()->subHours(72);
                $plisNonStatuesListes = \App\Models\Pli::whereHas('attributions', function ($query) {
                                $query->whereNotNull('coursier_depot_id'); //  Vérifie qu'un coursier a été assigné
                            }) //  Ajout de la parenthèse fermante ici
                            ->whereDoesntHave('pliStatuerHistory') //  Filtre uniquement les plis sans statut
                            ->whereHas('attributions', function ($query) use ($limite72h) {
                                $query->where('date_attribution_depot', '<', $limite72h);
                            })
                            ->get();
                @endphp


                    <div style="height: 500px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                        <input type="text" id="search" placeholder="Rechercher un client..." class="form-control mb-2">

                         {{-- <p class="small text-white stretched-link" href="#" id="alertCount"> {{ count($plisNonStatuesListes) }} Plis  sans statut final après 72H</p> --}}

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>N°Pli</th>
                                    <th>Expéditeur</th>
                                    <th>Destinataire</th>
                                    <th>Adresse destinataire</th>
                                    <th>Coursier pour le depot</th>
                                    <th>Date Attribution depot</th>
                                </tr>
                            </thead>
                            @if ($plisNonStatuesListes->count() > 0)
                            <tbody id="tableBody">
                                            @foreach ($plisNonStatuesListes as $pli0)
                                                <tr>
                                                    <td> {{ $loop->iteration }} </td>





                                                     <td>
                                                        <a href="{{ route('admin.plis.show', $pli0->id) }}" class="btn btn-sm btn-outline-primary" title="Voir les détails"> {{ $pli0->code }}
                                                            <i class="fas fa-eye"></i>
                                                         </a>
                                                    </td>

                                                    <td>{{ $pli0->user_name }}</td>
                                                    <td>{{ $pli0->destinataire_name }}</td>
                                                     <td>{{ $pli0->destinataire_adresse }}</td>
                                                    {{-- <td>{{ $pli0->attributions->coursier_depot_id ?? 'Aucun coursier' }}</td> --}}
                                            <td>{{ $pli0->attributions->first()->coursierDepot->nom ?? 'Aucun coursier' }} {{ $pli0->attributions->first()->coursierDepot->prenoms ?? 'Aucun coursier' }} ({{ $pli0->attributions->first()->coursierDepot->code ?? 'Aucun coursier' }}  )  </td>
                                                {{-- <td>Dépuis le : {{ optional($pli0->first())->date_attribution_depot ? \Carbon\Carbon::parse($pli0->first()->date_attribution_depot)->format('d-m-Y') : 'Non Défini' }}</td> --}}

                                                <td>
                                                         Dépuis le :
                                                        {{-- {{
                                                             \Carbon\Carbon::parse($pli0->date_attribution_depot)
                                                            ?? 'Non Défini' }} --}}

                                                              {{-- Dépuis le : --}}

                                                              {{ \Carbon\carbon::parse($pli0->attributions->first()->date_attribution_depot)->format('d-m-Y') ?? 'Voir dans la liste des plis' }}
                                                        {{-- {{
                                                             \Carbon\Carbon::parse($pli0->atttributions->date_attribution_depot)->format('d-m-Y')
                                                            ?? 'Non Défini' }} --}}
                                                </td>


                                                </tr>
                                            @endforeach

                            </tbody>
                        </table>
                        @else
                            <p style="color: red; font-weight: bold;">Aucun pli non statué trouvé dans cette période.</p>
                        @endif
                    </div>
            {{-- --------------------------------------------------- --}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
      </div>
    </div>
  </div>
</div>








