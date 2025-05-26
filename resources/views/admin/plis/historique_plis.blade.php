@extends('layouts.master')

@section('title', 'IvoirRp - Plis')

@section('content')

<!--  Modal centré et optimisé -->
<div id="pliModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h4>Détails du pli</h4>
        <div id="pliDetails"></div>
        <button onclick="printModal()" class="btn btn-primary mt-3">🖨️ Imprimer</button>
    </div>
</div>

<!--  Bouton retour -->
<div class="mt-3 text-end">
    <a href="{{ url()->previous() }}" class="btn btn-secondary">⬅ Retour</a>
</div>

<!--  Mode d'affichage -->
<div class="mb-3">
    <h6>Mode d'affichage</h6>
    <label><input type="checkbox" class="filter-status" value="déposé" checked> Déposé ✅</label> |
    <label><input type="checkbox" class="filter-status" value="annulé" checked> Annulé ❌</label> |
    <label><input type="checkbox" class="filter-status" value="refusé" checked> Refusé ⚠️</label>
</div>

<!--  Barre de recherche dynamique -->
<input type="text" id="search" class="form-control mb-3" placeholder="🔍 Rechercher..." onkeyup="filterTable()">

<!--  Formulaire de recherche -->
<form action="{{ route('historiquePlis') }}" method="GET" class="d-flex gap-3 align-items-end mb-3">
    <div>
        <label class="form-label">📜 Statut :</label>
        <select name="statut" class="form-select">
            <option value="">Tous</option>
            <option value="déposé">Déposé ✅</option>
            <option value="annulé">Annulé ❌</option>
            <option value="refusé">Refusé ⚠️</option>
        </select>
    </div>
    <div>
        <label class="form-label">📅 Période :</label>
        <div class="d-flex gap-2">
            <input type="date" name="date_debut" class="form-control">
            <input type="date" name="date_fin" class="form-control">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">🔎 Rechercher</button>
</form>

<!--  Message de recherche si filtre appliqué -->
@if(request()->filled('statut') || (request()->filled('date_debut') && request()->filled('date_fin')))
    <div class="alert alert-info">
        📌 Résultats de la recherche
        @if(request()->filled('statut'))
            pour les statuts <strong>{{ request()->input('statut') }}</strong>
        @endif
        @if(request()->filled('date_debut') && request()->filled('date_fin'))
            du <strong>{{ request()->input('date_debut') }}</strong> au <strong>{{ request()->input('date_fin') }}</strong>.
        @endif
    </div>
@endif

<!--  Tableau des résultats -->
<div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
    <table class="table table-bordered table-sm table-condensed">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Statut</th>
                <th>N° Suivi</th>
                <th>Destinataire</th>
                <th>Expéditeur</th>
                <th>Coursier dépôt</th>
                <th>Date Dépôt</th>
                {{-- <th>Date de Décision</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach(($listePlisFinalises1 ?? $listePlisFinalises) as $index => $pli)
                <tr class="
                    @if($pli->statuerHistories->last()?->statuer->name === 'déposé') table-success
                    @elseif($pli->statuerHistories->last()?->statuer->name === 'annulé') table-danger
                    @elseif($pli->statuerHistories->last()?->statuer->name === 'refusé') table-warning
                    @else table-light
                    @endif
                " onclick="showModal(`

                    <p><strong>N° Suivi:</strong> {{ $pli->id }}</p>
                    <p><strong>Expéditeur:</strong> {{ $pli->user?->prenom }} {{ $pli->user?->nom }}</p>
                    <p><strong>Destinataire:</strong> {{ $pli->destinataire?->prenom }} {{ $pli->destinataire?->nom }}</p>
                     <p><strong>Nombre de pièce:</strong> {{ $pli->destinataire?->prenom }} {{ $pli->destinataire?->nom }}</p>
                     <p><strong>Type :</strong> {{ $pli->destinataire?->prenom }} {{ $pli->destinataire?->nom }}</p>
                    <p><strong>Les références :</strong> {{ $pli->destinataire?->prenom }} {{ $pli->destinataire?->nom }}</p>
                    <p><strong>Date de Ramassage:</strong> {{ $pli->date_attribution_ramassage }}</p>
                    <p><strong>Date de Dépôt:</strong> {{ $pli->date_attribution_depot }}</p>
                    <p><strong>Date de Décision:</strong> {{ $pli->dateDecision }}</p>
                    <p><strong>Raison d'annulation ou du refus:</strong> {{ $pli->dateDecision }}</p>


                `)">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pli->statuerHistories->last()?->statuer->name ?? 'Non défini' }}</td>
                    <td>{{ $pli->user?->prenoms }} {{ $pli->user?->nom }}</td>
                    <td>{{ $pli->destinataire?->prenoms }} {{ $pli->destinataire?->nom }}</td>
                    {{-- <td>{{ $pli->attributions->first()?->coursierRamassage->nom ?? 'Non défini' }}</td> --}}
                    <td>{{ $pli->attributions->first()?->coursierDepot->nom ?? 'Non défini' }}</td>
                    {{-- <td>{{ $pli->dureeLivraison }}</td> --}}
                    {{-- <td>{{ $pli->date_attribution_ramassage }}</td> --}}
                    <td>{{ $pli->date_attribution_depot }}</td>
                    <td>{{ $pli->dateDecision }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!--  Scripts corrigés -->
<script>
function showModal(details) {
    document.getElementById("pliDetails").innerHTML = details;
    document.getElementById("pliModal").style.display = "block";
}

function closeModal() {
    document.getElementById("pliModal").style.display = "none";
}

function printModal() {
    let modalContent = document.getElementById("pliDetails").innerHTML;
    let newWindow = window.open("", "_blank");
    newWindow.document.write(`<html><head><title>Impression</title></head><body>${modalContent}</body></html>`);
    newWindow.document.close();
    newWindow.print();
}
</script>

<script> //la recherche
    function filterTable() {
        let input = document.getElementById("search").value.toLowerCase().trim();
        let rows = document.querySelectorAll("tbody tr");

        rows.forEach(row => {
            let text = row.textContent.toLowerCase().trim();
            row.style.display = text.includes(input) ? "" : "none";
        });
    }
    </script>

<script> //Pour mes chebox
    document.querySelectorAll('.filter-status').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            let selectedStatuses = Array.from(document.querySelectorAll('.filter-status:checked'))
                                       .map(cb => cb.value.toLowerCase().trim());

            document.querySelectorAll("tbody tr").forEach(row => {
                let cell = row.querySelector("td:nth-child(2)"); //  Vérifie bien que la colonne statut est la bonne
                let rowStatus = cell ? cell.textContent.toLowerCase().trim() : "";

                row.style.display = selectedStatuses.includes(rowStatus) ? "" : "none";
            });
        });
    });
    </script>



@endsection
