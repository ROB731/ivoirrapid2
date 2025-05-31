
            @extends('layouts.master')

            @section('title', 'Vérification de plis ')

            @section('content')

{{-- Enregistrement de dezs statut et motifs 12-05-2025 ------------------------------------------------------- --}}
{{-- Enregistrement de dezs statut et motifs 12-05-2025 ------------------------------------------------------- --}}

  {{-- @if(request()->isMethod('post')) --}}
            @if($_POST)

                 @php
                        $pliIds = array_filter(request()->input('pli_id', [])); // Éviter les valeurs vides
                        $statuts = request()->input('statut', []); // Ok c'est bon les valeur charge bien dans les trois cas
                        $motifs = request()->input('motif', []); // Ok c'est bon les valeur charge bien dans les trois cas
                        // dd($motifs);
                        //  dd($status);
                        $motifsRequis = [4, 5]; // Statuts nécessitant un motif
                        $majEffectuee = false; // Vérifie si au moins une mise à jour a été faite

                        if (isset($pliIds)) {

                            foreach ($pliIds as $index => $pliId) {
                            if (!empty($pliId) && isset($statuts[$index]) && $statuts[$index] !== '') {

                                // 🚨 Ignorer les statuts "En attente" (ID = 1) et "Aucun statut" (ID = 2)
                                if (in_array($statuts[$index], [1, 2])) {
                                    continue; // Ne pas enregistrer ces statuts
                                }

                                // 🔄 Récupérer le dernier statut enregistré
                                $dernierStatut = \App\Models\PliStatuerHistory::where('pli_id', $pliId)
                                                    ->latest('date_changement')
                                                    ->value('statuer_id');

                                //  Empêcher le retour au statut "En attente" (ID = 1)
                                if ($dernierStatut !== null && $statuts[$index] == 1) {
                                    session()->flash('error', "❌ Impossible de revenir au statut 'En attente' pour le pli ID {$pliId}.");
                                    continue;
                                }

                                //  Vérifier si un motif est requis et bien rempli
                                $motifRenseigne = trim($motifs[$index] ?? '');
                                $motifsRequis = [4, 5]; // Déposé, Annulé, Refusé nécessitent un motif
                                if (in_array($statuts[$index], $motifsRequis) && empty($motifRenseigne)) {
                                    session()->flash('error', "❌ Un motif est obligatoire pour le pli ID {$pliId}.");
                                    continue;
                                }

                                //  Enregistrer uniquement si le statut a changé
                                if ($dernierStatut === null || $dernierStatut != $statuts[$index]) {
                                    \App\Models\PliStatuerHistory::create([
                                        'pli_id' => $pliId,
                                        'statuer_id' => $statuts[$index],
                                        'date_changement' => now(),
                                        'raison_annulation' => !empty($motifRenseigne) ? $motifRenseigne : null
                                    ]);

                                    $majEffectuee = true;
                                        }
                                    }
                        }

                        if ($majEffectuee) {
                            session()->flash('success', "✅ Mise à jour réussie ! Les statuts autorisés ont été enregistrés.");
                        }

                // ----------------------------------------------------------------------------

                                // Afficher un message de succès si au moins une mise à jour a été faite
                                if ($majEffectuee) {
                                    session()->flash('success', '✅ Mise à jour réussie.');
                                }
                                } else {
                                    session()->flash('error', '🚨 Erreur : Aucun pli sélectionné.');
                                }
                            @endphp

                            {{-- Affichage des messages --}}
                                @if(session()->has('error'))
                                    <script>alert("{{ session('error') }}");</script>
                                @elseif(session()->has('success'))
                                    <script>
                                        alert("{{ session('success') }}");
                                        window.location.href = "{{ url()->current() }}"; // Recharge après mise à jour
                                    </script>
                                @endif

                    @endif

                    @php
                        use Carbon\Carbon;

                        $messageRecherche = '<div><strong>🔎 Recherche effectuée :</strong> ';
                            $msgRecap ="";

                    if (request()->filled('date_debut') && request()->filled('date_fin') && request()->filled('id_coursier')) {
                                $dateDebut = Carbon::parse(request()->input('date_debut'))->startOfDay();
                                $dateFin = Carbon::parse(request()->input('date_fin'))->endOfDay();

                                $idCoursier = request()->input('id_coursier');
                                $coursier = \App\Models\Coursier::find( $idCoursier);

                                // Récupération des attributions du coursier
                                $plisAttribues = \App\Models\Attribution::where('coursier_depot_id', $idCoursier)
                                    ->whereBetween('date_attribution_depot', [$dateDebut, $dateFin]) // Filtre par intervalle de date
                                    ->with('pli') // Charge la relation des plis
                                    ->get();

                                $PlisFinals = \App\Models\PliStatuerHistory::whereIn('pli_id', $plisAttribues->pluck('pli_id'))
                                    ->whereNotIn('statuer_id', [1, 2]) // 🔹 Exclut les statuts non pertinents
                                    ->orderByDesc('date_changement') // 🔹 Trie les statuts du plus récent au plus ancien
                                    ->get()
                                    ->groupBy('pli_id') // 🔹 Regroupe les statuts par pli_id
                                    ->map(function ($statusHistory) {
                                        return $statusHistory->first(); // 🔹 Récupère uniquement le plus récent pour chaque pli
                                    });

                                    $nombrePlisFinals = \App\Models\PliStatuerHistory::whereIn('pli_id', $plisAttribues->pluck('pli_id'))
                                        ->whereNotIn('statuer_id', [1, 2]) // 🔹 Exclut les statuts non pertinents
                                        ->distinct('pli_id') // 🔹 Évite les doublons en ne comptant qu'une seule fois chaque pli
                                        ->count();

                                    $msgRecap = '<strong> Total plis trouvés </strong> : '.count($plisAttribues) . ' <strong> Plis Traité : </strong> '. $nombrePlisFinals;

                                }
                            else
                                {
                                    echo('<script> alert("Veuillez rempli tous les champs pour la recherche") </script>');
                                }

                        // Uniquement pour les dates

                                // if (request()->filled('date_debut') && request()->filled('date_fin')) { // 🔹 Supprime le filtre sur 'id_coursier'
                                //     $dateDebut = Carbon::parse(request()->input('date_debut'))->startOfDay();
                                //     $dateFin = Carbon::parse(request()->input('date_fin'))->endOfDay();

                                //     //  Récupération de TOUS les plis attribués, sans dépendre d'un coursier
                                //     $plisAttribues = \App\Models\Attribution::whereBetween('date_attribution_depot', [$dateDebut, $dateFin]) // Filtre uniquement par date
                                //         ->with('pli') // Charge la relation des plis
                                //         ->get();

                                //     // 🔄 Récupération des statuts les plus récents, sans statuts ID = 1 et 2
                                //     $PlisFinals = \App\Models\PliStatuerHistory::whereIn('pli_id', $plisAttribues->pluck('pli_id'))
                                //         ->whereNotIn('statuer_id', [1, 2]) // 🔹 Exclut les statuts non pertinents
                                //         ->orderByDesc('date_changement') // 🔹 Trie par statut le plus récent
                                //         ->get()
                                //         ->groupBy('pli_id') // 🔹 Regroupe les statuts par pli_id
                                //         ->map(function ($statusHistory) {
                                //             return $statusHistory->first(); // 🔹 Récupère uniquement le plus récent pour chaque pli
                                //         });

                                //      //Compter uniquement les plis qui ont un statut valide (hors ID = 1 et 2)
                                //     $nombrePlisFinals = \App\Models\PliStatuerHistory::whereIn('pli_id', $plisAttribues->pluck('pli_id'))
                                //         ->whereNotIn('statuer_id', [1, 2]) // 🔹 Exclut les statuts non pertinents
                                //         ->distinct('pli_id') // 🔹 Évite les doublons en ne comptant qu'une seule fois chaque pli
                                //         ->count();


                                //     $messageRecherche .= 'sur la période du <span style="color: #dc3545;">' . \Carbon\carbon::parse($dateDebut)->format('d-m-Y') . '</span> au <span style="color: #dc3545;">' . \Carbon\carbon::parse($dateFin)->format('d-m-Y') . '</span> ';
                                //     $msgRecap = '<strong> Total plis trouvés </strong> : '.count($plisAttribues) . ' <strong> Plis Traités : </strong> '. $nombrePlisFinals;
                                // }

                        // Pour le coursier uniquement

                                // if (request()->filled('id_coursier')) { // 🔹 Vérifie seulement si un coursier est sélectionné
                                //         $idCoursier = request()->input('id_coursier');
                                //         $coursier = \App\Models\Coursier::find($idCoursier);

                                //         //  Récupérer les plis attribués à ce coursier
                                //         $plisAttribues = \App\Models\Attribution::where('coursier_depot_id', $idCoursier)
                                //             ->with('pli') // Charge la relation des plis
                                //             ->get();

                                //         // 🔄 Récupération des statuts les plus récents, sans statuts ID = 1 et 2
                                //         $PlisFinals = \App\Models\PliStatuerHistory::whereIn('pli_id', $plisAttribues->pluck('pli_id'))
                                //             ->whereNotIn('statuer_id', [1, 2]) // 🔹 Exclut les statuts non pertinents
                                //             ->orderByDesc('date_changement') // 🔹 Trie par statut le plus récent
                                //             ->get()
                                //             ->groupBy('pli_id') // 🔹 Regroupe les statuts par pli_id
                                //             ->map(function ($statusHistory) {
                                //                 return $statusHistory->first(); // 🔹 Récupère uniquement le plus récent pour chaque pli
                                //             });

                                //         //  Compter uniquement les plis qui ont un statut valide (hors ID = 1 et 2)
                                //         $nombrePlisFinals = \App\Models\PliStatuerHistory::whereIn('pli_id', $plisAttribues->pluck('pli_id'))
                                //             ->whereNotIn('statuer_id', [1, 2]) // 🔹 Exclut les statuts non pertinents
                                //             ->distinct('pli_id') // 🔹 Évite les doublons en ne comptant qu'une seule fois chaque pli
                                //             ->count();

                                //         $messageRecherche .= 'avec le coursier <span style="color: #007bff;">' . optional($coursier)->name . '</span>';
                                //         $msgRecap = '<strong> Total plis trouvés </strong> : '.count($plisAttribues) . ' <strong> Plis Traité : </strong> '. $nombrePlisFinals;

                                //     }

                            @endphp
{{-- -----------------------------------------------------------------------logique ci haut ---------------------------------- --}}
{{-- -----------------------------------------------------------------------logique ci haut ----------------------------------- --}}

<div class="container">
    <h2>Vérification des statuts des plis </h2>
    <hr>
    <!-- Formulaire de sélection ---------------------------------------------------------------------------------------------->
    {{-- <form action="{{ route('admin.plis.verification') }}" method="GET" class="mb-4"> --}}

            <form method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <label for="id_coursier" class="form-label">🚴‍♂️ Sélectionner un coursier :</label>
                        <div class="mb-3">
                            {{-- <input type="text" name="id_coursier" id="id_coursier" list="coursier" class="form-control" autocomplete="off" placeholder="Sélectionner un coursier..." required> --}}

                            <input type="text" name="id_coursier" id="id_coursier" list="coursier" class="form-control" autocomplete="off" placeholder="Sélectionner un coursier..." >
                        </div>

                        <datalist id="coursier">
                            {{-- @foreach(App\Models\Coursier::whereHas('attributionsDepot')->get() as $coursier) --}}
                            @foreach(App\Models\Coursier::all() as $coursier1)
                                <option value="{{ $coursier1->id }}">{{ $coursier1->nom }} {{ $coursier1->prenoms }} | {{ $coursier1->code }}</option>
                            @endforeach
                        </datalist>
                    </div>
                        <div class="col-md-3">
                            <label for="date_debut" class="form-label">📅 Date début :</label>
                            <input type="date" name="date_debut" id="date_debut" class="form-control"  >
                        </div>

                        <div class="col-md-3">
                            <label for="date_fin" class="form-label">📅 Date fin :</label>
                            <input type="date" name="date_fin" id="date_fin" class="form-control"  >
                        </div>

                        <div class="col-md-2 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-search"></i> Rechercher
                            </button>
                            <a href="{{ route('admin.plis.verification') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-sync-alt"></i> Réinitialiser
                            </a>
                        </div>
                    </div>
                </form>

    <!-- /Pour le formulaire -------------------------------------------------------------------------------------------------------->
    <!-- Affichage des résultats  avec le statistique 12-05-2025----------------------------------------------------------------------->
                            @if(isset($coursier))
                                <div class="alert alert-info d-flex justify-content-between">
                                    📦 Vérification des statuts des  Plis attribués à : <strong>{{ $coursier->nom }} {{ $coursier->prenoms }} </strong> |

                                        <style>
                                            .status-indicator {
                                                padding: 2px;
                                                font-size: 14px;
                                                border-radius: 5px;
                                            }

                                            .status-indicator.alert {
                                                background-color: red;
                                                color: white;
                                                animation: pulse 2s infinite alternate;
                                            }

                                            @keyframes pulse {
                                                0% { opacity: 1; }
                                                100% { opacity: 0.5; }
                                            }

                                        </style>

                                    📅 Période : <strong>{{ \Carbon\carbon::parse(request()->input('date_debut'))->format('d-m-Y') }} → {{ \Carbon\carbon::parse(request()->input('date_fin'))->format('d-m-Y') }}</strong>
                                    {{-- <p><strong>Nombre de plis ayant atteint un état final :</strong> {{ $nombrePlisFinals }}</p> --}}

                                </div>

                                 <div class="mt-3 text-center fw-bold p-3 status-indicator
                                            @if(!empty($nombrePlisFinals) && !empty($plisAttribues) && $nombrePlisFinals < $plisAttribues->count()) alert @endif">
                                            📊 Nombre total : <strong> {{ $nombrePlisFinals ?? '0' }} / {{ $plisAttribues->count() ?? '0' }} Traité(s) </strong>
                                     </div>

                                <div style="display:flex; gap:5px; text-align:right">
                                        <div class="text-end mt-3">
                                            <button class="btn btn-sm btn-primary" onclick="imprimerTableau()">
                                                <i class="fas fa-print"></i> Imprimer
                                            </button>
                                        </div>
                                        <div class="text-end mt-3">
                                            <button class="btn btn-sm btn-success" onclick="exporterExcel()">
                                                <i class="fas fa-file-excel"></i> Télécharger Excel
                                            </button>
                                        </div>
                                </div>
                            @endif

     <!-- Affichage des résultats ---------------------------------------------------------------->
          <!-- Bouton pour enregistrer les modifications -->
        <hr>
    {{-- ------------------------------------------ --}}
            {{-- Pour afficher les recherches et resultat de recherche --}}
                        <div class="search-message">
                            {!! $messageRecherche !!} <br>
                            {!! $msgRecap !!}
                        </div>
                {{-- Option de filtre ------------------- --}}
                <hr>
                                 <h5>Options de filtre </h5>
                                 <div class="filter-container">
                                        <input type="checkbox" id="filter-with-status" checked>
                                        <label for="filter-with-status">Afficher les plis avec statut</label>

                                        <input type="checkbox" id="filter-without-status" checked>
                                        <label for="filter-without-status">Afficher les plis sans statut</label>

                                        <input type="text" id="search-input" placeholder="🔎 Rechercher un pli...">
                                    </div>

                {{-- Option de filtre----------- --}}
                            <style>
                                    .search-message {
                                        padding: 10px;
                                        background: #f0f0f0;
                                        border-left: 5px solid #007bff;
                                        font-size: 16px;
                                        margin-bottom: 15px;
                                    }

                            </style>
            {{-- / fin--------------- --}}
                <div id="print">
                    <br>

                    <div class="container mt-4">
                        <div class="print-logo">
                            <img src="https://ivoirrapid.ci/asset/Logo%20IRN.png" alt="Logo Entreprise" style="width: 80px; height: auto;">
                        </div>
                        <h5 class="mb-3">📋 Fiche de Mission du {{ \Carbon\Carbon::parse(request()->input('date_debut'))->format('d-m-Y') }}
                                    au {{ \Carbon\Carbon::parse(request()->input('date_fin'))->format('d-m-Y') }}- Dépôt</h5>
                        <div class="card">

                            <div class="card-header">
                            @if(isset($coursier))
                                    <h5>🚴‍♂️ Coursier : {{ $coursier->nom ?? 'Non défini' }} {{ $coursier->prenoms ?? '' }} (Code: {{ $coursier->code ?? 'N/A' }})</h5>
                                    <p><strong>Téléphone :</strong> {{ $coursier->telephone ?? 'Non disponible' }}</p>
                                    <p><strong>Zones gérées :</strong>
                                        @if(!empty($coursier->zones))
                                            @foreach ($coursier->zones as $zone)
                                                {{ $zone }},
                                            @endforeach
                                        @else
                                            Non spécifiées
                                        @endif
                                    </p>
                                    <p>Nombre de plis à livrer : {{ isset($plisAttribues) ? $plisAttribues->count() : 0 }}</p>
                                @else
                                    <div class="alert alert-warning">
                                        ⚠ Aucun coursier sélectionné !
                                    </div>
                                @endif
                            <div class="card-body">
    {{-- ------------------------------------------------------------------------------------------ --}}
    {{-- -------------------------------------------------------------------------------------------- --}}
{{-- -------------------------------FORM------------------------------------------------------------------------------- --}}
                            <form  method="post">
                                     @csrf
                                        <table class="table table-bordered mission-table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th class="sticky-column">N° du Pli</th>
                                                        <th style="background:rgb(223, 221, 221)">Destinataire (Zone)</th>
                                                        <th>D. AD</th>
                                                        <th>Statut</th>
                                                        <th>Motif (si refusé, annulé ou retourné)</th>
                                                    </tr>
                                                </thead>
                                            <tbody>
                                @if(isset($plisAttribues) && $plisAttribues->count() > 0)
                                    @foreach($plisAttribues as $index => $pli)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="sticky-column">{{ $pli->pli->code ?? '####' }}</td>
                                            <input type="hidden" name="pli_id[]" value="{{ $pli->pli->id ?? '' }}">
                                            <td style="background:rgb(223, 221, 221)">
                                                {{ $pli->pli->destinataire_name ?? '####' }} ({{ $pli->pli->destinataire_zone ?? 'Zone inconnue' }})
                                            </td>

                                            <td>{{ $pli->date_attribution_depot ?? 'Inconnu' }}</td>

                                                @php
                                                    // Récupération des statuts pour ce pli
                                                    $statutsPli = $PlisFinals->where('pli_id', $pli->pli->id)->sortByDesc('date_changement');
                                                    $dernierStatut = $statutsPli->first(); // Statut le plus récent
                                                    // dd($dernierStatut);
                                                @endphp

                                                @php
                                                    // Définir la classe de fond en fonction du statut
                                                    $bgClass = '';
                                                    if (isset($dernierStatut)) {
                                                        switch ($dernierStatut->statuer_id) {
                                                            case 3:
                                                                $bgClass = 'bg-blue'; // Déposé
                                                                break;
                                                            case 4:
                                                                $bgClass = 'bg-annule'; // Annulé
                                                                break;
                                                            case 5:
                                                                $bgClass = 'bg-refuse'; // Refusé
                                                                break;
                                                        }
                                                    }

                                                    // Vérifier si le statut n'est pas dans [3, 4, 5] → Ajoute une option "Sélectionner un statut"
                                                        $defaultOption = !isset($dernierStatut) || !in_array($dernierStatut->statuer_id, [3, 4, 5]) ? 'selected' : '';
                                                    @endphp

                                                    <td class="{{ $bgClass }}">
                                                            <select name="statut[]" class="form-select status-select">
                                                                <option value="" {{ $defaultOption }}>Sélectionner un statut</option> {{-- Option par défaut --}}

                                                                @foreach(App\Models\Statuer::whereIn('id', [3, 4, 5])->get() as $statut)
                                                                    <option value="{{ $statut->id }}"
                                                                            {{ isset($dernierStatut) && $dernierStatut->statuer_id == $statut->id ? 'selected' : '' }}>
                                                                        {{ $statut->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    <td>
                                                        <input type="text" name="motif[]" class="form-control motif-input"
                                                            placeholder="Motif..."
                                                            value="{{ $dernierStatut->raison_annulation ?? '' }}"
                                                            style="{{ !empty($dernierStatut->raison_annulation) ? '' : 'display: none;' }}">
                                                        </td>
                                                </tr>
                                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center text-danger fw-bold">🚨 Aucun pli attribué trouvé !</td>
                            </tr>
                        @endif
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .btn, .no-print { display: none !important; }
            .container { max-width: 100%; margin:auto; }
            .table { border-collapse: collapse; }
            .table th, .table td { border: 1px solid black; font-size: 14px; text-align: center; }
        }
    </style>
          <style>
            .sticky-column {
                            position: sticky;
                            left: 0;
                            background: white; /* Assure que la colonne reste visible */
                            z-index: 100; /* Met la colonne au-dessus des autres */
                        }

            </style>

        </div>

            <div class="mt-3 text-end">
                 <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
            </div>
         </form>
{{-- ----------------------------------------------------------------------------------------- --}}
{{-- ------------------------------------------------------------------------------------- --}}

                <style>
                    .bg-blue {
                            background: blue !important;
                            color: white;
                        }

                        .bg-annule {
                            background: rgb(223, 182, 110) !important;
                        }

                        .bg-refuse {
                            background: #17a2b8 !important;
                            color: white;
                        }

            </style>

            <style>
                    #print{
                            max-height: 700px;
                            overflow: auto;

                    }
            </style>

{{-- Les script ------------------------------------------------------------------------------------------------------------ --}}
{{-- Les script ------------------------------------------------------------------------------------------------------------ --}}

        <script>
            document.querySelectorAll('.status-select').forEach(select => {
                select.addEventListener('change', function() {
                    let row = this.closest('tr');
                    let motifInput = row.querySelector('.motif-input');

                    // Vérifier si le statut nécessite un motif
                    if (this.value == 4 || this.value == 5) {
                        motifInput.style.display = 'block';
                    } else {
                        motifInput.style.display = 'none';
                    }
                });
            });
        </script>


            <script>
                document.querySelectorAll('.status-select').forEach(select => {
                    select.addEventListener('change', function() {
                        let row = this.closest('tr');
                        let motifInput = row.querySelector('.motif-input');

                        // Vérifier si le statut nécessite un motif
                        if (this.value == 4 || this.value == 5) {
                            motifInput.style.display = 'block';
                        } else {
                            motifInput.style.display = 'none';
                        }
                    });
                });
            </script>

            {{-- Pour le changelent des couleurs des cellules  --}}
            <script>
                document.querySelectorAll('.status-select').forEach(select => {
                    let row = select.closest('td');
                    let currentStatus = row.getAttribute('data-status');

                    // Appliquer la couleur au chargement selon le statut actuel
                    setCellColor(row, currentStatus);

                    select.addEventListener('change', function() {
                        setCellColor(row, this.value);
                    });
                });

                function setCellColor(cell, status) {
                    if (status == 5) {
                        cell.style.backgroundColor = '#EF9A9A'; // Rouge doux (Refusé)
                        cell.style.color = 'black';
                    } else if (status == 4) {
                        cell.style.backgroundColor = '#FFCC80'; // Orange clair (Annulé)
                        cell.style.color = 'black';
                    } else if (status == 3) {
                        cell.style.backgroundColor = '#90CAF9'; // Bleu pastel (Déposé)
                        cell.style.color = 'black';
                    } else {
                        cell.style.backgroundColor = ''; // Aucun changement pour les autres statuts
                        cell.style.color = '';
                    }
                }
            </script>

                <script>
                        // Pour pouvoir lancer l'impression

                    function imprimerTableau() {
                        var contenu = document.getElementById('print').innerHTML;
                        var fenetreImpression = window.open('', '', 'height=600,width=800');
                        fenetreImpression.document.write('<html><head><title>Impression</title></head><body>');
                        fenetreImpression.document.write(contenu);
                        fenetreImpression.document.write('</body></html>');
                        fenetreImpression.document.close();
                        fenetreImpression.print();
                    }

                </script>

                {{-- Pour le telechargement en pdf  --}}

                <script>  // Pour l'exportation en excel
                    function exporterExcel() {
                        var wb = XLSX.utils.book_new();
                        var ws = XLSX.utils.table_to_sheet(document.getElementById('print'));

                        XLSX.utils.book_append_sheet(wb, ws, "Fiche_Mission_{{ $coursierSelectionne->nom ??''}}_{{ $coursierSelectionne->prenoms ??''}}");
                        XLSX.writeFile(wb, "fiche_mission_{{ $coursierSelectionne->nom ?? ''}}_{{ $coursierSelectionne->prenoms ?? ''}}.xlsx");
                    }
                </script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

{{-- Pour le filtre dans le tableau --}}
      <script>
document.addEventListener("DOMContentLoaded", function() {
    const filterWithStatus = document.getElementById("filter-with-status");
    const filterWithoutStatus = document.getElementById("filter-without-status");
    const searchInput = document.getElementById("search-input");
    const tableRows = document.querySelectorAll(".mission-table tbody tr");

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();

        tableRows.forEach(row => {
            const statutSelect = row.querySelector("td select.status-select");
            const selectedStatut = statutSelect ? statutSelect.options[statutSelect.selectedIndex].text.toLowerCase() : "";
            const pliNumber = row.querySelector("td:nth-child(2)").innerText.toLowerCase();

            const hasStatus = ["déposé", "annulé", "refusé"].includes(selectedStatut);
            const matchesSearch = pliNumber.includes(searchTerm);

            // Appliquer les filtres
            let showRow = true;

            if (!filterWithStatus.checked && hasStatus) showRow = false;
            if (!filterWithoutStatus.checked && !hasStatus) showRow = false;
            if (searchTerm && !matchesSearch) showRow = false;

            row.style.display = showRow ? "" : "none";
        });
    }

    // Attacher les événements
    filterWithStatus.addEventListener("change", filterTable);
    filterWithoutStatus.addEventListener("change", filterTable);
    searchInput.addEventListener("keyup", filterTable);
});
</script>


    <style>
                    .filter-container {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-container input[type="text"] {
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 200px;
        }

    </style>




@endsection
