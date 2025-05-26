@extends('layouts.master')

@section('title', 'IvoirRp - users')

@section('content')
<div class="container mt-5">
    <h3 class="text-center mb-4">Liste des Utilisateurs</h3>

    <!-- Barre de recherche -->
    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un utilisateur..." onkeyup="searchTable()">
    </div>

    <!-- Tableau Responsif avec données optimisées -->
    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table class="table table-striped table-bordered mt-3" style="white-space: nowrap;">
            <thead class="table-light sticky-top" style="z-index: 1020;">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom Société</th>
                    <th scope="col">Abreviation</th>
                    <th scope="col">Téléphone</th>
                    <th scope="col">Email</th>
                    <th scope="col">RCCM</th>
                    <th scope="col">Adresse</th>
                    <th scope="col">Détails</th>
                    <th scope="col" class="text-center sticky-top" style="right: 0; background-color: white;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->abreviation }}</td>
                    <td>{{ $user->Telephone }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->RCCM }}</td>
                    <td>
                        <span class="text-truncate" style="max-width: 150px;" data-bs-toggle="tooltip" title="{{ $user->Adresse }}">
                            {{ Str::limit($user->Adresse, 20) }}
                        </span>
                    </td>
                    <td>
                        <!-- Bouton pour afficher les détails -->
                        <button class="btn btn-sm btn-info" data-bs-toggle="collapse" data-bs-target="#user-details-{{ $user->id }}" aria-expanded="false" aria-controls="user-details-{{ $user->id }}">
                            <i class="fas fa-info-circle"></i> Détails
                        </button>
                    </td>
                    <!-- Actions -->
                    <td class="text-center position-sticky" style="right: 0; background-color: white;">
                        <a href="{{ url('admin/edit-user/'.$user->id) }}" class="btn btn-sm btn-warning" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ url('admin/delete-user/'.$user->id) }}" class="btn btn-sm btn-danger" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>

                <!-- Détails supplémentaires cachés sous chaque ligne -->
                <tr id="user-details-{{ $user->id }}" class="collapse">
                    <td colspan="9" style="background-color: #f9f9f9;">
                        <div class="p-3">
                            <strong>Adresse Complète:</strong> {{ $user->Adresse }} <br>
                            <strong>Commune :</strong>{{ $user->Commune }} <br>
                            <strong>Rue :</strong>{{ $user->Rue }} <br>
                           <strong>Quartier :</strong>{{ $user->Quartier }} <br>
                            <strong>Zone :</strong>{{ $user->Zone }} <br>
                            <strong>Directions:</strong>
                            <ul>
                                @foreach (['Direction_1', 'Direction_2', 'Direction_3'] as $direction)
                                    @if ($user->{$direction . '_Nom_et_Prenoms'})
                                        <li>
                                            <strong>{{ $user->{$direction . '_Nom_et_Prenoms'} }}:</strong> {{ $user->{$direction . '_Contact'} }}
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        <ul class="pagination pagination-custom">
            {{-- Bouton Précédent --}}
            @if ($users->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">Précédent</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $users->previousPageUrl() }}">Précédent</a>
                </li>
            @endif
    
            {{-- Pages --}}
            @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                <li class="page-item {{ $users->currentPage() == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach
    
            {{-- Bouton Suivant --}}
            @if ($users->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $users->nextPageUrl() }}">Suivant</a>
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
</div>

<!-- Script de recherche pour filtrer en temps réel -->
<script>
    // Fonction de recherche pour filtrer les utilisateurs en temps réel
    function searchTable() {
        let input = document.getElementById('searchInput');
        let filter = input.value.toUpperCase();
        let table = document.querySelector('table');
        let trs = table.getElementsByTagName('tr');

        for (let i = 1; i < trs.length; i++) {
            let tds = trs[i].getElementsByTagName('td');
            let matched = false;
            for (let j = 0; j < tds.length; j++) {
                if (tds[j]) {
                    let txtValue = tds[j].textContent || tds[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        matched = true;
                        break;
                    }
                }
            }
            trs[i].style.display = matched ? "" : "none";
        }
    }
</script>

<!-- Script pour activer les tooltips sans jQuery -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection