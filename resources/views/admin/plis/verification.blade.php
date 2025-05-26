
@extends('layouts.master')

@section('title', 'Vérification de plis ')

@section('content')

{{-- Enregistrement de dezs statut et motifs 12-05-2025 ------------------------------------------------------- --}}
{{-- Enregistrement de dezs statut et motifs 12-05-2025 ------------------------------------------------------- --}}

@if(request()->isMethod('post'))
    @php
        $pliIds = request()->input('pli_id', []);
        $statuts = request()->input('statut', []);
        $motifs = request()->input('motif', []);
        $statutsInterdits = [1, 2]; // "En attente" et "Ramassé"

        if (!empty($pliIds) && is_array($pliIds) && count($pliIds) === count($statuts)) {
            foreach ($pliIds as $index => $pliId) {
                if (!empty($pliId) && isset($statuts[$index]) && $statuts[$index] !== '') {
                    // Vérifier si le statut est interdit
                    if (in_array($statuts[$index], $statutsInterdits)) {
                        echo "<script>alert('Erreur : Les statuts \"En attente\" et \"Ramassé\" ne peuvent pas être enregistrés.')</script>";
                        continue; // Ne pas enregistrer ce pli
                    }

                    // Vérifier si un motif est obligatoire et s'il est bien rempli
                    $motifObligatoire = in_array($statuts[$index], [ 4, 5]); // Déposé, Annulé, Refusé nécessitent un motif
                    $motifRenseigne = isset($motifs[$index]) && trim($motifs[$index]) !== '' ? $motifs[$index] : null;

                    // S'assurer qu'un motif est bien saisi quand il est obligatoire
                    if ($motifObligatoire && !$motifRenseigne) {
                        echo "<script>alert('Erreur : Un motif est obligatoire pour le statut sélectionné.')</script>";
                        continue; // Bloquer la mise à jour si le motif est requis mais absent
                    }

                    // Récupérer le dernier statut enregistré
                    $dernierStatut = App\Models\PliStatuerHistory::where('pli_id', $pliId)
                                    ->latest('date_changement')
                                    ->value('statuer_id');

                    // Enregistrer uniquement si aucun statut n'existe OU si le statut a changé
                    if ($dernierStatut === null || $dernierStatut != $statuts[$index]) {
                        App\Models\PliStatuerHistory::create([
                            'pli_id' => $pliId,
                            'statuer_id' => $statuts[$index],
                            'date_changement' => now(),
                            'raison_annulation' => $motifRenseigne
                        ]);
                    }
                }
            }
        } else {
            echo "<script>alert('Erreur : Certains champs sont vides ou invalides. Veuillez vérifier votre saisie.')</script>";
        }
    @endphp


    @endphp
    <script>
        alert("Mise à jour enregistrée ! ");
        window.location.href = "{{ url()->current() }}"; // Recharge la page après la mise à jour
    </script>
@endif

{{-- Compte et clignantant --------------------------------------- --}}
          {{-- @php
                $dateDebut = request()->input('date_debut');
                    $dateFin = request()->input('date_fin');

                    $nombrePlisFinals = App\Models\PliStatuerHistory::whereBetween('date_changement', [$dateDebut, $dateFin])
                    // ->whereHas('pli', fn($query) => $query->where('etat_final', true))
                    ->whereHas('pli', fn($query) => $query->whereBetween('statuer_id', [3, 5])) // Vérifie les statuer_id entre 3 et 5
                    ->select('pli_id', DB::raw('MAX(date_changement) as dernierStatut'))
                    ->groupBy('pli_id')
                    ->get()
                    ->count();

        @endphp --}}



        @php
$dateDebut = request()->input('date_debut');
$dateFin = request()->input('date_fin');
$idCoursier = request()->input('id_coursier');

// Récupérer le total des plis de la période pour le coursier sélectionné
$totalPlisPeriode = App\Models\Attribution::where('coursier_depot_id', $idCoursier)
                    ->whereBetween('created_at', [$dateDebut, $dateFin])
                    ->count();

// Récupérer les plis qui ont atteint un état final (statuer_id entre 3 et 5) dans la période
$nombrePlisFinals = App\Models\PliStatuerHistory::whereBetween('date_changement', [$dateDebut, $dateFin])
                    ->whereHas('pli', fn($query) =>
                        $query->whereBetween('statuer_id', [3, 5])
                              ->whereHas('attributions', fn($attribution) =>
                                  $attribution->where('coursier_depot_id', $idCoursier))) // Vérification dans attributions
                    ->select('pli_id', DB::raw('MAX(date_changement) as dernierStatut'))
                    ->groupBy('pli_id')
                    ->get()
                    ->count();




        @endphp

{{-- ---------------------------Compte et clignontant --}}

{{-- Fin // Enregistrement de dezs statut et motifs 12-05-2025  ------------------------------------------------------------ --}}
{{-- Fin // Enregistrement de dezs statut et motifs 12-05-2025 ------------------------------------------------------- --}}


{{--  Pour les informations poour charger les formulaire------------------------------------  --}}
{{--  Pour les informations poour charger les formulaire------------------------------------  --}}

@php
    $idCoursier = request()->input('id_coursier');
    $debutIntervalle = request()->input('date_debut');
    $finIntervalle = request()->input('date_fin');
    // Vérifier si le coursier existe
    $coursierSelectionne = App\Models\Coursier::find($idCoursier);
    // Filtrer les attributions
    $plisAttribues = App\Models\Attribution::with(['pli', 'coursierDepot'])
        ->where('coursier_depot_id', $idCoursier)
        ->whereBetween('created_at', [$debutIntervalle, $finIntervalle])
        ->get();
@endphp

{{-- /pour le formulaire ------------------------------------------------------------------------------- --}}
{{-- /pour le formulaire ------------------------------------------------------------------------------- --}}



<div class="container">
    <h2>Vérification des statuts des plis </h2>
    <hr>
    <!-- Formulaire de sélection ---------------------------------------------------------------------------------------------->
    <form action="{{ route('admin.plis.verification') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="id_coursier" class="form-label">🚴‍♂️ Sélectionner un coursier :</label>
                <select name="id_coursier" id="id_coursier" class="form-select" required>
                    <option value="">-- Choisir un coursier --</option>
                    @foreach(App\Models\Coursier::all() as $coursier)
                        <option value="{{ $coursier->id }}">{{ $coursier->nom }} {{ $coursier->prenoms }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="date_debut" class="form-label">📅 Date début :</label>

               <input type="date" name="date_debut" id="date_debut" class="form-control"
                   min="2023-01-01"  required>

                   {{-- max="{{ now()->format('Y-m-d') }}" --}}

            </div>

            <div class="col-md-3">
                <label for="date_fin" class="form-label">📅 Date fin :</label>
            <input type="date" name="date_fin" id="date_fin" class="form-control"
                   min="2023-01-01" required>

                    {{-- max="{{ now()->format('Y-m-d') }}" --}}
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
         @if(isset($coursierSelectionne))
            <div class="alert alert-info d-flex justify-content-between">
                📦 Vérification des statuts des  Plis attribués à : <strong>{{ $coursierSelectionne->nom }} {{ $coursierSelectionne->prenoms }} </strong> |
                {{-- 📊 Nombre total : <strong>  {{ $nombrePlisFinals }} / {{ $plisAttribues->count() }}  Traité(s) </strong> | --}}
                   {{-- <div class="mt-3 text-center fw-bold p-3 status-indicator">
                        📊 Nombre total : <strong> {{ $nombrePlisFinals }} / {{ $plisAttribues->count() }}  Traité(s) </strong>
                    </div> --}}



                    <div class="mt-3 text-center fw-bold p-3 status-indicator
                        @if($nombrePlisFinals < $plisAttribues->count()) alert @endif">
                        📊 Nombre total : <strong> {{ $nombrePlisFinals }} / {{ $plisAttribues->count() }}  Traité(s) </strong>
                    </div>


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





                📅 Période : <strong>{{ request()->input('date_debut') }} → {{ request()->input('date_fin') }}</strong>
                {{-- <p><strong>Nombre de plis ayant atteint un état final :</strong> {{ $nombrePlisFinals }}</p> --}}

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

        <div id="print">
        <div class="container mt-4">
            <div class="print-logo">
                <img src="https://ivoirrapid.ci/asset/Logo%20IRN.png" alt="Logo Entreprise" style="width: 80px; height: auto;">
            </div>
            <h5 class="mb-3">📋 Fiche de Mission du {{ date('d-m-Y') }} - Dépôt</h5>
            <div class="card">
                <div class="card-header">
                 @if(isset($coursierSelectionne))
                        <h5>🚴‍♂️ Coursier : {{ $coursierSelectionne->nom ?? 'Non défini' }} {{ $coursierSelectionne->prenoms ?? '' }} (Code: {{ $coursierSelectionne->code ?? 'N/A' }})</h5>
                        <p><strong>Téléphone :</strong> {{ $coursierSelectionne->telephone ?? 'Non disponible' }}</p>
                        <p><strong>Zones gérées :</strong>
                            @if(!empty($coursierSelectionne->zones))
                                @foreach ($coursierSelectionne->zones as $zone)
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
                    {{-- <form action="{{ route('admin.plis.update') }}" method="POST"> --}}
                                    <!-- Formulaire pour modifier les statuts -->

    {{-- ------------------------------------------------------------------------------------------ --}}
    {{-- -------------------------------------------------------------------------------------------- --}}

                    <form method="POST">
                        @csrf
                        <table class="table table-bordered mission-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>N° du Pli</th>
                                    <th style="background:rgb(223, 221, 221)">Destinataire (Zone)</th>
                                    <th>Type</th>
                                    <th>Statut</th>
                                    <th>Motif (si refusé, annulé ou retourné)</th>
                                </tr>
                            </thead>
                         @php
                            $idCoursier = request()->input('id_coursier');
                            $debutIntervalle = request()->input('date_debut');
                            $finIntervalle = request()->input('date_fin');

                            // Récupérer les plis attribués et regrouper par destinataire
                            $plisAttribues = App\Models\Attribution::with(['pli'])
                                ->where('coursier_depot_id', $idCoursier)
                                ->whereBetween('created_at', [$debutIntervalle, $finIntervalle])
                                ->get()
                                ->groupBy(fn($attribution) =>
                                    ($attribution->pli->destinataire_name ?? 'Destinataire inconnu') . ' - ' .
                                    ($attribution->pli->destinataire_zone ?? 'Zone inconnue')
                                );
                        @endphp

                    <tbody>

                        @php
                            $idCoursier = request()->input('id_coursier');
                            $debutIntervalle = request()->input('date_debut');
                            $finIntervalle = request()->input('date_fin');

                            // Récupérer les plis attribués et les grouper uniquement par destinataire
                            $plisAttribues = App\Models\Attribution::with(['pli'])
                                ->where('coursier_depot_id', $idCoursier)
                                ->whereBetween('created_at', [$debutIntervalle, $finIntervalle])
                                ->get()
                                ->groupBy(fn($attribution) =>
                                    ($attribution->pli->destinataire_name ?? 'Destinataire inconnu') . ' - ' .
                                    ($attribution->pli->destinataire_zone ?? 'Zone inconnue')
                                );
                        @endphp

                        <tbody>
                            @foreach($plisAttribues as $destinataire => $attributions)
                                <tr>
                                    <td colspan="6" class="text-center font-weight-bold bg-light">
                                        📦 <strong>Destinataire :</strong> {{ $destinataire }}
                                    </td>
                                </tr>

                                @foreach ($attributions as $index => $pli)
                                    <tr>
                                        <td> {{ $loop->iteration ?? '####' }} </td>
                                        <td style="text-align:center; min-width:150px">
                                            {{ $pli->pli->code ?? '####' }}
                                            <input type="hidden" name="pli_id[]" value="{{ $pli->pli->id }}">
                                        </td>
                                        <td style="background:rgb(223, 221, 221); max-width:150px">
                                            {{ $pli->pli->destinataire_name ?? '####' }} ({{ $pli->pli->destinataire_zone }})
                                        </td>
                                        <td style="text-align:center; max-width:150px">
                                            {{ $pli->pli->type ?? '####' }}
                                        </td>

                                        @php
                                            // Récupérer le dernier statut
                                            $dernierStatut = App\Models\PliStatuerHistory::where('pli_id', $pli->pli->id)
                                                            ->latest('date_changement')
                                                            ->value('statuer_id');
                                        @endphp

                                        <td class="status-cell" data-status="{{ $dernierStatut }}">
                                            <select name="statut[]" class="form-select status-select">
                                                @foreach(App\Models\Statuer::all() as $statut)
                                                    <option value="{{ $statut->id }}"
                                                            {{ $dernierStatut == $statut->id ? 'selected' : '' }}>
                                                        {{ $statut->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        @php
                                            // Récupérer le dernier motif enregistré pour ce pli
                                            $motifEnregistre = App\Models\PliStatuerHistory::where('pli_id', $pli->pli->id)
                                                            ->latest('date_changement')
                                                            ->value('raison_annulation');
                                        @endphp

                                        <td>
                                            <input type="text" name="motif[]" class="form-control motif-input"
                                                placeholder="Motif..."
                                                value="{{ $motifEnregistre ?? '' }}"
                                                style="{{ !empty($motifEnregistre) ? '' : 'display: none;' }}">
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>

                        </table>

                        @if(isset($coursierSelectionne) && isset($plisAttribues) && $plisAttribues->count() > 0)
                            <div class="mt-3 text-end">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-save"></i> Enregistrer les modifications
                                </button>
                            </div>
                        @endif

                    </form>
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

</div>
{{-- ----------------------------------------------------------------------------------------- --}}
{{-- ------------------------------------------------------------------------------------- --}}

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



@endsection
