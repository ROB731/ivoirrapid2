@extends('layouts.master')

@section('title', 'IvoirRp - Plis')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Gestion des Plis</h1>


    <div class="search-bar mb-4">
        <div class="input-group">
            <!-- Bouton pour "Ramassage" avec une moto -->
            <a href="{{ route('admin.attributions.impression') }}" class="btn btn-primary mx-2" title="Ramassage">
                <i class="fas fa-motorcycle"></i>
            </a>

            <!-- Bouton pour "Dépot" -->
            <a href="{{ route('admin.attributions.depot') }}" class="btn btn-secondary mx-2" title="Dépôt">
                <i class="fas fa-warehouse"></i>
            </a>

            <!-- Bouton pour Export PDF -->
            <button id="export-pdf" class="btn btn-primary mx-2" title="Exporter en PDF">
                <i class="fas fa-file-pdf"></i>
            </button>

                                       <!-- Boutons avec icônes -->

            <button class="btn btn-primary mx-2" >
                    <a href=" " class="btn btn-sm btn-outline-primary" title="Attribution" style="color:white">
                      <i class="fas fa-user-plus"></i>
                     </a>
            </button>
    <button class="btn btn-primary mx-2" >
        <a href="" class="btn btn-sm btn-outline-primary" title="Voir les détails" style="color:white">
            <i class="fas fa-eye"></i>
        </a>
    </button>


        </div>
    </div>

    <div class="search-bar mb-4">
        <div class="input-group">
            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un pli..." />
            <button class="btn btn-primary" id="btnSearch" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.plis.index') }}">
                <div class="row g-3 align-items-center">
                    <!-- Filtre par destinataire -->
                    <div class="col-md-4">
                        <label for="destinataireFilter" class="form-label">Destinataire</label>
                        <select name="destinataire_name" id="destinataireFilter" class="form-select">
                            <option value="">-- Tous les destinataires --</option>
                            @foreach($destinataires as $destinataire)
                                <option value="{{ $destinataire->destinataire_name }}"
                                    {{ request('destinataire_name') == $destinataire->destinataire_name ? 'selected' : '' }}>
                                    {{ $destinataire->destinataire_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtre par coursier ramassage -->
                    <div class="col-md-4">
                        <label for="coursierRamassageFilter" class="form-label">Coursier (Ramassage)</label>
                        <select name="coursier_ramassage" id="coursierRamassageFilter" class="form-select">
                            <option value="">Tous les coursiers</option>
                            @foreach($coursiers as $coursier)
                                <option value="{{ $coursier->id }}"
                                    {{ request('coursier_ramassage') == $coursier->id ? 'selected' : '' }}>
                                    {{ $coursier->prenoms }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtre par coursier dépôt -->
                    <div class="col-md-4">
                        <label for="coursierDepotFilter" class="form-label">Coursier (Dépôt)</label>
                        <select name="coursier_depot" id="coursierDepotFilter" class="form-select">
                            <option value="">Tous les coursiers</option>
                            @foreach($coursiers as $coursier)
                                <option value="{{ $coursier->id }}"
                                    {{ request('coursier_depot') == $coursier->id ? 'selected' : '' }}>
                                    {{ $coursier->prenoms }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtres par dates -->
                    <div class="col-md-6">
                        <label for="startDate" class="form-label">Date début</label>
                        <input type="date" name="start_date" id="startDate" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="endDate" class="form-label">Date fin</label>
                        <input type="date" name="end_date" id="endDate" class="form-control" value="{{ request('end_date') }}">
                    </div>
                </div>

                <!-- Boutons -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <button type="submit" class="btn btn-primary" title="Appliquer les filtres">
                            <i class="fas fa-filter"></i>
                        </button>
                        <a href="{{ route('admin.plis.index') }}" class="btn btn-secondary" title="Effacer les filtres">
                            <i class="fas fa-redo"></i>
                        </a>

                    </div>
                </div>
            </form>
        </div>
    </div>



    <div class="table-responsive scrollable-container">
        <table class="table table-bordered table-striped mt-3">
            <thead class="bg-light text-center">
                <tr>
                    <th class="text-nowrap">No de Suivie</th>
                    <th class="text-nowrap">Type</th>
                    <th class="text-nowrap">Date de création</th>
                    <th class="text-nowrap">Client</th>
                    <th class="text-nowrap">Destinataire</th>
                    <th class="text-nowrap">Coursier Ramassage</th>
                    <th class="text-nowrap">Coursier Dépôt</th>
                    <th class="text-nowrap">Date Attribution Ramassage</th>
                    <th class="text-nowrap">Date Attribution Dépôt</th>
                    <th class="text-nowrap">Statut</th>
                    <th class="text-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($plis as $pli)
                    <tr>
                        <!-- No de Suivie -->
                        <td class="text-center text-nowrap">{{ $pli->code }}</td>
                        <td class="text-center text-nowrap">{{ $pli->type }}</td>
                        <td class="text-center text-nowrap">{{ $pli->created_at }}</td>

                        <!-- Client -->
                        <td class="text-nowrap">
                            <div><strong>{{ $pli->user->name }}</strong></div>
                            <div class="text-muted small">Tél: {{ $pli->user->Telephone ?? 'N/A' }}</div>
                        </td>

                        <!-- Destinataire -->
                        <td class="text-nowrap">
                            <div><strong>{{ $pli->destinataire->name }}</strong></div>
                            <div class="text-muted small">Tél: {{ $pli->destinataire->telephone ?? 'N/A' }}</div>
                            <div class="text-muted small">Zone: {{ $pli->destinataire->zone ?? 'N/A' }}</div>
                        </td>

                        <!-- Coursiers -->
                        <td class="text-center text-nowrap">
                            {{ isset($pli->attributions[0]->coursierRamassage) ? $pli->attributions[0]->coursierRamassage->prenoms ?? 'Non défini' : 'Non défini' }}
                        </td>

                        <!-- Coursier de dépôt -->
                        <td class="text-center text-nowrap">
                            {{ isset($pli->attributions[0]->coursierDepot) ? $pli->attributions[0]->coursierDepot->prenoms ?? 'Non défini' : 'Non défini' }}
                        </td>

                        <!-- Dates -->
                        <td class="text-center text-nowrap">
                            {{ isset($pli->attributions[0]) && $pli->attributions[0]['date_attribution_ramassage']
                                ? \Carbon\Carbon::parse($pli->attributions[0]['date_attribution_ramassage'])->format('d/m/Y')
                                : 'Non défini' }}
                        </td>
                        <td class="text-center text-nowrap">
                            {{ isset($pli->attributions[0]) && $pli->attributions[0]['date_attribution_depot']
                                ? \Carbon\Carbon::parse($pli->attributions[0]['date_attribution_depot'])->format('d/m/Y')
                                : 'Non défini' }}
                        </td>



                        <td class="text-nowrap">
                            <form action="{{ route('plis.changeStatuer', $pli->id) }}" method="POST">
                                @csrf
                                <select name="statuer" class="form-select form-select-sm statut-select" required>
                                    @foreach(['en attente', 'ramassé', 'déposé', 'annulé', 'retourné'] as $statut)
                                        <option value="{{ $statut }}"
                                            {{ $pli->currentStatuer()?->statuer->name == $statut ? 'selected' : '' }}>
                                            {{ ucfirst($statut) }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="raison" class="form-control form-control-sm mt-2 raison-input"
                                    placeholder="Raison d'annulation"
                                    value="{{ $pli->currentStatuer()?->raison_annulation ?? '' }}"
                                    {{ $pli->currentStatuer()?->statuer->name == 'annulé' ? '' : 'disabled' }} />
                                <button type="submit" class="btn btn-primary btn-sm mt-2">Mettre à jour</button>
                            </form>
                        </td>

                        <!-- Actions -->
                        <td class="text-center text-nowrap">
                            <!-- Attribuer coursiers -->
                            <!-- Boutons avec icônes -->
<a href="{{ route('admin.attributions.index') }}" class="btn btn-sm btn-outline-primary" title="Attribuer">
    <i class="fas fa-user-plus"></i>
</a>
<a href="{{ route('admin.plis.show', $pli->id) }}" class="btn btn-sm btn-outline-primary" title="Voir les détails">
    <i class="fas fa-eye"></i>
</a>

<!-- Bouton pour afficher les détails des dates -->
<button class="btn btn-sm btn-outline-info mt-1 my-1" data-bs-toggle="modal" data-bs-target="#modalDates-{{ $pli->id }}" title="Voir les dates">
    <i class="fas fa-calendar-alt"></i>
</button>
<a href="{{ route('plis.accuse_retour', $pli->id) }}" class="btn btn-sm btn-outline-primary">
    <i class="fas fa-file-alt"></i> Accusé de Retour
</a>



                        </td>
                    </tr>

                    <!-- Modal pour les détails -->
                    <div class="modal fade" id="modalDates-{{ $pli->id }}" tabindex="-1" aria-labelledby="modalDatesLabel-{{ $pli->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalDatesLabel-{{ $pli->id }}">Détails des Dates</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <strong>Date En Attente :</strong>
                                            {{ $pli->statuerHistory->where('statuer_id', '1')->last()?->date_changement
                                                ? \Carbon\Carbon::parse($pli->statuerHistory->where('statuer_id', '1')->last()->date_changement)->format('d/m/Y H:i')
                                                : 'Non défini' }}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Date Ramassé :</strong>
                                            {{ $pli->statuerHistory->where('statuer_id', '2')->last()?->date_changement
                                                ? \Carbon\Carbon::parse($pli->statuerHistory->where('statuer_id', '2')->last()->date_changement)->format('d/m/Y H:i')
                                                : 'Non défini' }}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Date Déposé :</strong>
                                            {{ $pli->statuerHistory->where('statuer_id', '3')->last()?->date_changement
                                                ? \Carbon\Carbon::parse($pli->statuerHistory->where('statuer_id', '3')->last()->date_changement)->format('d/m/Y H:i')
                                                : 'Non défini' }}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Date Annulé :</strong>
                                            {{ $pli->statuerHistory->where('statuer_id', '4')->last()?->date_changement
                                                ? \Carbon\Carbon::parse($pli->statuerHistory->where('statuer_id', '4')->last()->date_changement)->format('d/m/Y H:i')
                                                : 'Non défini' }}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Date Retourné :</strong>
                                            {{ $pli->statuerHistory->where('statuer_id', '5')->last()?->date_changement
                                                ? \Carbon\Carbon::parse($pli->statuerHistory->where('statuer_id', '5')->last()->date_changement)->format('d/m/Y H:i')
                                                : 'Non défini' }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

</div>
<div class="d-flex justify-content-center">
    <ul class="pagination pagination-custom">
        {{-- Bouton Précédent --}}
        @if ($plis->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Précédent</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $plis->previousPageUrl() }}">Précédent</a>
            </li>
        @endif

        {{-- Pages --}}
        @foreach ($plis->getUrlRange(1, $plis->lastPage()) as $page => $url)
            <li class="page-item {{ $plis->currentPage() == $page ? 'active' : '' }}">
                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
            </li>
        @endforeach

        {{-- Bouton Suivant --}}
        @if ($plis->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $plis->nextPageUrl() }}">Suivant</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">Suivant</span>
            </li>
        @endif
    </ul>
</div>
<style>

    /* Pagination container */
.pagination-custom .page-link {
    border-radius: 25px; /* Boutons arrondis */
    margin: 0 5px;       /* Espacement entre les boutons */
    color: #007bff;      /* Couleur du texte */
    background-color: #f8f9fa; /* Couleur de fond */
    border: 1px solid #ddd;    /* Bordure légère */
    transition: all 0.3s ease; /* Animation lors du hover */
}
.d-flex {
    margin-top: 10px;
}



/* Effet hover */
.pagination-custom .page-link:hover {
    background-color: #007bff; /* Fond bleu au hover */
    color: #fff;               /* Texte blanc */
    border-color: #007bff;     /* Bordure bleue */
}.scrollable-container {
    max-height: 500px; /* Ajuste la hauteur max pour activer le scroll */
    overflow-x: auto; /* Permet le scroll horizontal */
    overflow-y: auto; /* Permet le scroll vertical */
    white-space: nowrap;
    position: relative;
}

.table {
    min-width: 100%; /* Empêche le tableau de rétrécir trop */
}

/* Fixe la colonne "Statut" */
th:nth-last-child(2), td:nth-last-child(2) {
    position: sticky;
    right: 100px; /* Ajuste selon la largeur de la colonne "Actions" */
    background: white;
    z-index: 2;
    box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
}

/* Fixe la colonne "Actions" */
th:last-child, td:last-child {
    position: sticky;
    right: 0;
    background: white;
    z-index: 3;
    box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
}



/* Bouton actif */
.pagination-custom .page-item.active .page-link {
    background-color: #007bff; /* Fond bleu pour l'actif */
    color: #fff;               /* Texte blanc */
    border-color: #007bff;     /* Bordure bleue */
}

/* Bouton désactivé */
.pagination-custom .page-item.disabled .page-link {
    background-color: #e9ecef; /* Fond gris clair */
    color: #6c757d;           /* Texte gris */
    border-color: #e9ecef;    /* Bordure gris clair */
    pointer-events: none;     /* Désactivation des clics */
}
.scrollable-container {
    max-height: 500px; /* Hauteur maximale pour le défilement vertical */
    overflow-x: auto; /* Défilement horizontal activé */
    overflow-y: auto; /* Défilement vertical activé */
    white-space: nowrap; /* Empêche les colonnes de se replier */
}

.table {
    min-width: 100%; /* Assure que le tableau occupe toujours la largeur nécessaire */
}
.scrollable-container::-webkit-scrollbar {
    width: 10px; /* Largeur de la barre horizontale */
    height: 10px; /* Hauteur de la barre verticale */
}

.scrollable-container::-webkit-scrollbar-thumb {
    background-color: #007bff; /* Couleur de la barre */
    border-radius: 5px;
}

.scrollable-container::-webkit-scrollbar-thumb:hover {
    background-color: #0056b3; /* Couleur survolée */
}

.scrollable-container::-webkit-scrollbar-track {
    background: #f1f1f1; /* Couleur du fond */
}
</style>
<script>
   document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.statut-select').forEach(function(select) {
        select.addEventListener('change', function () {
            const raisonInput = this.closest('form').querySelector('.raison-input');
            if (this.value === 'annulé') {
                raisonInput.disabled = false;
                raisonInput.focus();
            } else {
                raisonInput.disabled = true;
                raisonInput.value = ''; // Réinitialiser le champ
            }
        });
    });
});


    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('tbody tr');

        searchInput.addEventListener('keyup', function () {
            const searchTerm = this.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

            tableRows.forEach(function (row) {
                const columns = row.querySelectorAll('td');
                let match = false;

                const searchColumns = [
                    0, // Code de suivi
                ];

                searchColumns.forEach(colIndex => {
                    const columnText = columns[colIndex].textContent.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                    if (columnText.includes(searchTerm)) {
                        match = true;
                    }
                });

                row.style.display = match ? '' : 'none';
            });
        });
    });

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<script>
    document.getElementById('export-pdf').addEventListener('click', function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Obtenir la valeur de la recherche
    const searchInput = document.getElementById('searchInput').value.toLowerCase();

    // Récupérer les données du tableau en filtrant selon la recherche
    const table = document.querySelector('.table');
    const rows = [...table.querySelectorAll('tbody tr')]
        .filter(row => {
            const clientCell = row.querySelector('td:nth-child(4)');
            const clientName = clientCell ? clientCell.querySelector('strong').innerText.toLowerCase() : '';
            // Vérifier si le nom du client correspond à la recherche
            return clientName.includes(searchInput);
        })
        .map(row => {
            const cells = row.querySelectorAll('td');
            return [
                cells[0]?.innerText || '',  // No de Suivie
                cells[1]?.innerText || '',  // Type
                cells[2]?.innerText || '',  // Date de création
                cells[9]?.querySelector('select')?.value || '',  // Statut actuel (récupère la valeur sélectionnée)
                cells[4]?.innerText.split('Tél: ')[1] || 'N/A',  // Téléphone D (récupère après "Tél: ")
                cells[4]?.querySelector('div strong')?.innerText || ''  // Destinataire (nom du destinataire)
            ];
        });

    // Si aucune correspondance, alerte l'utilisateur
    if (rows.length === 0) {
        alert('Aucun client trouvé pour cette recherche.');
        return;
    }

    // Ajouter le texte du titre et l'image (logo)
    doc.text('Liste des Plis', 14, 15);  // Titre du PDF
    doc.addImage("{{ asset('asset/Logo IRN.png') }}", 'PNG', 150, 5, 50, 20);  // Ajouter l'image à la droite du texte

    // Configurer le tableau dans le PDF
    doc.autoTable({
        head: [['No de Suivie', 'Type', 'Date de création', 'Statut', 'Téléphone D', 'Destinataire']],
        body: rows,
        startY: 30,  // Ajuster le début du tableau après le titre et le logo
        headStyles: { fillColor: [22, 160, 133] },
        styles: { fontSize: 10, cellPadding: 4 },
    });

    // Télécharger le fichier PDF avec le nom dynamique
    const clientName = rows[0] ? rows[0][5] : 'Client';  // Utiliser le nom du destinataire (ou 'Client' si vide)
    const date = new Date();
    const formattedDate = date.toISOString().split('T')[0];  // Format: YYYY-MM-DD
    const fileName = `${clientName}_${formattedDate}.pdf`;

    doc.save(fileName);  // Utiliser le nom dynamique du fichier
});

</script>

@endsection
