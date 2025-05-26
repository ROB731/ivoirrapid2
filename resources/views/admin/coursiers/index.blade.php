@extends('layouts.master')

@section('title', 'IvoirRp - Admin coursier')

@section('content')
<div class="container-fluid px-4 my-3">
    <div class="card">
        <div class="card-header">
            <h4>Liste des coursiers</h4>
        </div>
    </div>
    {{-- Recap coursier --}}





    <!-- Champ de recherche -->
    <div class="mb-3" style="max-width: 500px; margin-top: 10px">
        <div class="input-group">
            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un coursier..." aria-label="Rechercher un coursier..." />
            <button class="btn btn-primary" id="btnSearch" type="button"><i class="fas fa-search"></i></button>
        </div>
    </div>
    <div class="search-bar mb-" style="margin-left: 1420px">
        <div class="input-group">
            <!-- Bouton pour "Ramassage" avec une moto -->
            <a href="{{ url('admin/add-coursier') }}" class="btn btn-primary mx-2" title="Ajouter un coursier">
                <i class="fas fa-add"></i>
            </a>
        </div>
    </div>

    <div class="container">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark sticky-top">
                    <tr>
                        <th>#</th>
                        <th>Nom & Prénoms</th>
                        <th>Contact</th>
                        <th>Zone</th>
                        <th>Permis</th>
                        <th>Validité</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coursiers as $index => $coursier)
                        <tr>
                            <td>{{ $coursier->id }}</td>
                            <td>
                                <strong>{{ $coursier->nom }}</strong> <br>
                                <span class="text-muted">{{ $coursier->prenoms }}</span>
                            </td>
                            <td>
                                <span class="d-block"><i class="fas fa-phone-alt"></i> {{ $coursier->telephone }}</span>
                                <span class="d-block"><i class="fas fa-infos"></i> {{ $coursier->code }}</span>
                            </td>
                            {{-- <td>
                                @if(is_array($coursier->zones) && count($coursier->zones) > 0)
                                    {{ implode(', ', $coursier->zones) }} <!-- Affiche les zones séparées par des virgules -->
                                @else
                                    Pas de zone assignée
                                @endif
                            </td> --}}<td>
    @foreach($coursier->zones as $zone)
    <span>{{ $zone }}</span>@if(!$loop->last), @endif
@endforeach
</td>


                            <td>{{ $coursier->numero_de_permis }}</td>
                            <td>{{ $coursier->date_de_validite_du_permis }}</td>
                            <td class="text-center">
                                <!-- Actions -->
                                <a href="{{ url('admin/edit-coursier/'.$coursier->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ url('admin/delete-coursier/'.$coursier->id) }}" class="btn btn-sm btn-danger" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                                <!-- Détails supplémentaires -->
                                <button class="btn btn-sm btn-info" data-bs-toggle="collapse" data-bs-target="#details-{{ $coursier->id }}" aria-expanded="false" aria-controls="details-{{ $coursier->id }}">
                                    <i class="fas fa-info-circle"></i> Détails
                                </button>
                            </td>
                        </tr>
                        <!-- Section Détails -->
                        <tr class="collapse bg-light" id="details-{{ $coursier->id }}">
                            <td colspan="7">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Email :</strong>  {{ $coursier->email }}<br>
                                        <strong>Catégorie Permis :</strong> {{ $coursier->categorie_du_permis }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>CNI :</strong> {{ $coursier->numero_de_cni }} <br>
                                        <strong>Validité CNI :</strong> {{ $coursier->date_de_validite_de_la_cni }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>CMU :</strong> {{ $coursier->numero_de_la_cmu }} <br>
                                        <strong>Validité CMU :</strong> {{ $coursier->date_de_validite_de_la_cmu }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Date de naissance :</strong> {{ $coursier->date_de_naissance }} <br>
                                        <strong>Groupe sanguin :</strong> {{ $coursier->groupe_sanguin }}
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <strong>Adresse :</strong> {{ $coursier->adresse }} <br>
                                        <strong>Contact Urgence :</strong> {{ $coursier->contact_urgence }} ({{ $coursier->affiliation_urgence }})
                                    </div>
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
                @if ($coursiers->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">Précédent</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $coursiers->previousPageUrl() }}">Précédent</a>
                    </li>
                @endif

                {{-- Pages --}}
                @foreach ($coursiers->getUrlRange(1, $coursiers->lastPage()) as $page => $url)
                    <li class="page-item {{ $coursiers->currentPage() == $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                {{-- Bouton Suivant --}}
                @if ($coursiers->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $coursiers->nextPageUrl() }}">Suivant</a>
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

                // Parcourir toutes les colonnes pour trouver une correspondance
                columns.forEach(column => {
                    const cellText = column.textContent.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
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
