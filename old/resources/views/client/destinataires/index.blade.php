@extends('layout.master')

@section('title', 'IvoirRp - destinataires')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Destinataires</h1>

    <div class="card">
        <div class="card-header">
            <h4>Liste des Destinataires <a href="{{ url('client/add-destinataire') }}" class="btn btn-primary btn-sm float-end">Ajouter un destinataire</a></h4>
        </div>
    </div>

    <!-- Champ de recherche -->
    <div class="mb-3" style="max-width: 500px; margin-top: 10px">
        <div class="input-group">
            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un destinataire..." aria-label="Rechercher un destinataire..." />
            <button class="btn btn-primary" id="btnSearch" type="button"><i class="fas fa-search"></i></button>
        </div>
    </div>

    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table class="table table-striped table-bordered mt-3">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="text-nowrap">Nom</th>
                    <th scope="col" class="text-nowrap">Téléphone</th>
                    <th scope="col" class="text-nowrap">Contact</th>
                    <th scope="col" class="text-nowrap">Email</th>
                    <th scope="col" class="text-nowrap">Adresse</th>
                    <th scope="col" class="text-nowrap">Zone</th>
                    <th scope="col" class="text-nowrap">Autre</th>
                    <th scope="col" class="text-nowrap" style="position: sticky; right: 0; background-color: white; z-index: 10;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($destinataires as $destinataire)
                    <tr>
                        <td class="text-nowrap">{{ $destinataire->name }}</td>
                        <td class="text-nowrap">{{ $destinataire->telephone }}</td>
                        <td class="text-nowrap">{{ $destinataire->contact }}</td>
                        <td class="text-nowrap">{{ $destinataire->email }}</td>
                        <td class="text-nowrap">{{ $destinataire->adresse }}</td>
                        <td class="text-nowrap">{{ $destinataire->zone }}</td>
                        <td class="text-nowrap">{{ $destinataire->autre }}</td>
                        <td class="text-nowrap position-sticky" style="background-color: white; z-index: 10; right: 0;">
                            <a href="{{ url('client/edit-destinataire/'.$destinataire->id) }}" class="btn btn-warning btn-sm me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ url('client/delete-destinataire/'.$destinataire->id) }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-center">
        <ul class="pagination pagination-custom">
            {{-- Bouton Précédent --}}
            @if ($destinataires->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">Précédent</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $destinataires->previousPageUrl() }}">Précédent</a>
                </li>
            @endif
    
            {{-- Pages --}}
            @foreach ($destinataires->getUrlRange(1, $destinataires->lastPage()) as $page => $url)
                <li class="page-item {{ $destinataires->currentPage() == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach
    
            {{-- Bouton Suivant --}}
            @if ($destinataires->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $destinataires->nextPageUrl() }}">Suivant</a>
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
    <style>
        /* Colonne des actions sticky */
        .sticky-column {
            position: sticky;
            right: 0;
            background-color: white;
            z-index: 10;
            box-shadow: inset -1px 0px 5px rgba(0,0,0,0.1); /* Légère ombre pour un meilleur contraste */
        }
    
        /* Assure que le texte ne dépasse pas dans les colonnes */
        td.text-truncate {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
    

    @if(session('message'))
        <div class="alert alert-success" role="alert">
            {{ session('message') }}
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('tbody tr');

        searchInput.addEventListener('keyup', function () {
            const searchTerm = this.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

            tableRows.forEach(function (row) {
                const columns = row.querySelectorAll('td');
                let match = false;

                // Vérifier les colonnes d'intérêt
                const searchColumns = [
                    0,
                    1, // nom
                    2,
                    5, // email
                ];

                searchColumns.forEach(colIndex => {
                    const cellText = columns[colIndex].textContent.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                    if (cellText.includes(searchTerm)) {
                        match = true;
                    }
                });

                // Afficher ou masquer la ligne
                row.style.display = match ? '' : 'none';
            });
        });
    });
</script>
@endsection
