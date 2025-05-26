
{{-- Le blade ------------------------------------------------------------- --}}

  @php
        use Carbon\Carbon;

            //  Définition des périodes
            $debutMois = Carbon::now()->startOfMonth();
            $debutTrimestre = Carbon::now()->subMonths(3)->startOfMonth();
            $debutSemestre = Carbon::now()->subMonths(6)->startOfMonth();
            $debutAnnee = Carbon::now()->startOfYear();


    @endphp

{{-- ---------------------------------------------------------------------------------------------------------- --}}

<!-- Modal pour afficher les clients actifs et inactifs -->

    <div class="modal fade modal-lg " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Clients actifs / Client inactifs</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

            {{-- ------------------------------------------------------------------------------------- --}}
              <form action="" method="get">
                @csrf
                <button type="submit" class="btn btn-text active" name="mois-actuel">Mois actuel</button>
                 <button type="submit" class="btn btn-text active" name="trois-mois">Il y a 1 Mois</button>
                <button type="submit" class="btn bt-text active" name="deux-mois">Il y a 2 Mois</button>
            </form>

            <style>
                    .btn.active {
                    font-weight: bold;
                        border-bottom: 3px solid #007bff;
                    }

            </style>
                    @php

                    $messageFiltre = ''; //  Initialise le message
                        $periode = Carbon::now()->startOfMonth(); //  Définit par défaut le mois en cours

                    if (request()->has('mois-actuel')) {
                        $periode = Carbon::now()->startOfMonth();
                        $messageFiltre = "Données du mois actuel.";
                    } elseif (request()->has('deux-mois')) {
                        $periode = Carbon::now()->subMonths(2)->startOfMonth();
                        $messageFiltre = "Données d'il y a deux mois jusqu'à aujourd'hui.";
                    } elseif (request()->has('trois-mois')) {
                        $periode = Carbon::now()->subMonth()->startOfMonth();
                        $messageFiltre = "Données d'il y a un mois jusqu'à aujourd'hui.";
                    }
                    @endphp


                    @if ($periode)
                            @php
                                $listeClients = \App\Models\User::withCount([
                                    'plis as total_plis', //  Ajout du nombre total de plis créés
                                    'plis as plis_ramasses_count' => function ($query) use ($periode) {
                                        $query->whereHas('attributions', function ($q) use ($periode) {
                                            $q->whereBetween('date_attribution_ramassage', [$periode, Carbon::today()]);
                                        });
                                    },
                                    'plis as plis_traites_count' => function ($query) {
                                        $query->whereHas('pliStatuerHistory', function ($q) {
                                            $q->whereNotIn('statuer_id', [1, 2]); // ✅ Filtre les plis traités
                                        });
                                    }
                                ])->get();
                            @endphp
                        @endif

                    @if ($periode)
                              <p style="font-weight: bold; color: #007bff;">{{ $messageFiltre }}</p> <!-- ✅ Affichage du message -->

                    @endif
                    <div style="height: 500px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                        <input type="text" id="search" placeholder="Rechercher un client..." class="form-control mb-2">

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nom du client</th>
                                    <th>Plis créés</th>
                                    <th>Plis ramassés (traités / total)</th>
                                    <th>Fréquence (%)</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                     @php
                                         $totalNombrePlisRamasses = max($listeClients->sum('plis_ramasses_count'), 1); // ✅ Empêche division par zéro
                                    @endphp

                                    @foreach ($listeClients->sortByDesc(fn($client) => ($client->plis_ramasses_count / $totalNombrePlisRamasses) * 100) as $client)
                                        <tr>
                                            <td>{{ $client->name }}</td>
                                            <td>{{ $client->total_plis }}</td>
                                            <td>{{ $client->plis_traites_count ?? 0 }} / {{ $client->plis_ramasses_count }}</td>
                                            <td>{{ round(($client->plis_ramasses_count / $totalNombrePlisRamasses) * 100, 2) }}%</td>
                                        </tr>
                                    @endforeach


                            </tbody>
                        </table>
                    </div>

                    <script>
                    document.getElementById('search').addEventListener('keyup', function() {
                        let filter = this.value.toLowerCase();
                        let rows = document.querySelectorAll('#tableBody tr');

                        rows.forEach(row => {
                            let clientName = row.cells[0].textContent.toLowerCase();
                            row.style.display = clientName.includes(filter) ? '' : 'none';
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








