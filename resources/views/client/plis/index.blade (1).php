
@extends('layout.master')

@section('title', 'IvoirRp - plis')

@section('content')



<!--<div class="info-print" > <!-- BEGIN HEADER TO PRINT UPDATE -->
<!--    <i>-->
<!--        <br>-->
<!--        <h6 style="text-align:center; text-transform: uppercase;" > Plis du client :  {{ Auth::user()->name }} </h6>-->
<!--        <hr style="width:100%; text-align:center">-->
<!--        </h6>Pour la période du {{ request()->start_date }} au {{ request()->end_date }}-->
<!--        <br>  Imprimé le : {{ date('d-m-Y') }} à {{ date('H:i:s') }}-->
<!--        <hr style="width:100%; text-align:center">-->
<!--    </i>-->
<!--</div>-->


<div class="info-print"> <!-- BEGIN HEADER TO PRINT UPDATE -->
    <i>
        <br>
        <h6 style="text-align:center; text-transform: uppercase;"> Plis du client : {{ Auth::user()->name }} </h6>
        <hr style="width:100%; text-align:center">
        
        @if(!empty(request()->start_date) && !empty(request()->end_date))
            Pour la période du {{ request()->start_date }} au {{ request()->end_date }}
        @else
            Plis d'aujourd'hui le : {{ date('d-m-Y') }}
        @endif
        
        <br> Imprimé le : {{ date('d-m-Y') }} à {{ date('H:i:s') }}
        <hr style="width:100%; text-align:center">
    </i>
</div>


    {{-- Pour le message de suppression --}}

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif


    {{-- Fin message --}}



    <!-- CLOSE HEADER PRINT UPDATE-->


<div class="container-fluid px-4">

<div class="no-print">
        <h1 class="mt-4">Plis</h1>

            <!-- Ligne : Champ de recherche -->
            <div class="row mb-3">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <div class="input-group">
                        <input
                            type="text"
                            id="searchInput"
                            class="form-control form-control-sm border-primary"
                            placeholder="Rechercher un pli..."
                            aria-label="Rechercher un destinataire..."
                            style="height: 35px;"
                        />
                        <button class="btn btn-primary btn-sm" id="btnSearch" type="button" style="height: 35px;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
        </div>

    <!-- Ligne : Filtre par destinataire -->


                    <div class="row mb-3" >
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('client.plis.index') }}">
                                <div class="d-flex align-items-center">
                                    <select
                                        name="destinataire_name"
                                        class="form-select form-select-sm"
                                        style="max-width: 250px; height: 35px;"
                                    >
                                        <option value="">-- Tous les destinataires --</option>
                                        @foreach($destinataires as $destinataire)
                                            <option
                                                value="{{ $destinataire->destinataire_name }}"
                                                {{ request('destinataire_name') == $destinataire->destinataire_name ? 'selected' : '' }}
                                            >
                                                {{ $destinataire->destinataire_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                    <div class="ms-2">
                                        <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
                                        <a href="{{ route('client.plis.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Effacer</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

            <!-- Ligne : Filtre par statut -->
                <div class="row mb-3" id="filter-d">
                        <div class="d-flex mb-3" style="max-width: 350px;">
                            <select
                                id="statusFilter"
                                class="form-select form-select-sm me-2"
                                style="height: 35px;">
                                <option value="">Tous les statuts</option>
                                <option value="en attente">En attente</option>
                                <option value="déposé">Déposé</option>
                                <option value="ramassé">Ramassé</option>
                                <option value="annulé">Annulé</option>
                                <option value="retourné">Retourné</option>
                            </select>
                    </div>
                </div>
        </div> <!-- -->
    <!-- Ligne : Filtre par date -->
                <div class="row mb-3">
                    <form method="GET" action="{{ route('client.plis.index') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="start_date" class="form-label">Date de début</label>
                                <input
                                    type="date"
                                    name="start_date"
                                    id="start_date"
                                    class="form-control form-control-sm"
                                    value="{{ request()->start_date }}"
                                    style="height: 35px;"
                                  min="2024-01-01"
                                    max="{{ date('Y-m-d')}}"
                                > <!-- Pour la date -->
                            </div>
                            <div class="col-md-4">
                                <label for="end_date" class="form-label">Date de fin</label>
                                <input
                                    type="date"
                                    name="end_date"
                                    id="end_date"
                                    class="form-control form-control-sm"
                                    value="{{ request()->end_date }}"
                                    style="height: 35px;"
                                     min="2024-01-01"
                                       max="{{ date('Y-m-d')}}"
                                >
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-sm w-30" style="height: 35px;">Filtrer</button>
                                <a href="{{ route('client.plis.index') }}" class="btn btn-outline-secondary btn-sm ms-2 w-35" style="height: 35px;">Effacer</a>
                            </div>
                        </div>
                    </form>
                </div>

    <div class="table-responsive" style="max-height:500px; overflow-y: auto;" id="tb-print">
         <!-- Affichage du nombre total de plis -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <strong>Total de plis :</strong> {{ $totalPlis }}
                    </div>
                </div>
            </div>
<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer ce pli ? Cette action est irréversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a id="confirmDeleteButton" class="btn btn-danger" href="#">Supprimer</a>
            </div>
        </div>
    </div>
</div>

        <table class="table table-striped table-bordered table-hover mt-3" id="table-p">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="text-nowrap-pr" style="white-space: nowrap;">Type</th>
                    <th scope="col" class="text-nowrap-pr" style="white-space: nowrap;">No de suivie</th> <!-- Show to print-->
                    <th scope="col" class="text-nowrap-pr" style="white-space: nowrap;">Destinataire</th>  <!-- Show to print-->
                    <th scope="col" class="text-nowrap">Client</th>
                    <th scope="col" class="text-nowrap">Téléphone C</th>
                    <th scope="col" class="text-nowrap">Contact C</th>
                    <th scope="col" class="text-nowrap" style="min-width: 150px;">De</th>
                    <th scope="col" class="text-nowrap">À</th>
                    <th scope="col" class="text-nowrap">Téléphone D</th>
                    <th scope="col" class="text-nowrap">Contact D</th>
                    {{-- <th scope="col" class="text-nowrap">Type</th> --}}
                    <th scope="col" class="text-nowrap">Nombre</th>
                    <th scope="col" class="text-nowrap">Date de creation</th>
                    <th scope="col" class="text-nowrap">Référence</th>
                    <th scope="col" class="text-nowrap">Statut</th>
                    <th scope="col" class="text-nowrap">Raison d'annulation</th>
                    <th scope="col" class="text-nowrap">Date En Attente</th>
                    <th scope="col" class="text-nowrap">Date Ramassé</th>
                    <th scope="col" class="text-nowrap">Date Déposé</th>
                    <th scope="col" class="text-nowrap">Date Annulé</th>
                    <th scope="col" class="text-nowrap">Date Retour</th>
                    <th scope="col" class="text-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($plis as $pli)
                <tr>
                    <td class="text-nowrap-pr">{{ $pli->type }}</td>
                    <td class="text-nowrap-pr" style="white-space: nowrap;">{{ $pli->code }}</td> <!-- Show to print-->
                    <td class="text-nowrap-pr">{{ $pli->destinataire_name }}</td>
                    <td class="text-nowrap" style="white-space: nowrap;">{{ $pli->user_name }}</td> <!-- Show to print-->
                    <td class="text-nowrap">{{ $pli->user_Telephone }}</td>
                    <td class="text-nowrap">{{ $pli->user_Cellulaire }}</td>
                    <td class="text-nowrap">{{ $pli->user_Adresse }}</td>
                    <td class="text-nowrap">{{ $pli->destinataire_adresse }}</td>

                    <td class="text-nowrap">{{ $pli->destinataire_telephone }}</td>
                    <td class="text-nowrap">{{ $pli->destinataire_contact }}</td>

                    <td class="text-nowrap">{{ $pli->nombre_de_pieces }}</td>
                    <td class="text-nowrap">{{ $pli->created_at }}</td>
                    <td class="text-nowrap">
                        <button class="btn btn-info btn-sm view-reference-btn" data-reference="{{ $pli->reference }}" data-bs-toggle="modal" data-bs-target="#referenceModal">Voir</button>
                    </td>
                    <td class="text-nowrap">
                        <span class="badge
                            @if ($pli->currentStatuer() && $pli->currentStatuer()->statuer->name == 'en attente')
                                bg-warning
                            @elseif ($pli->currentStatuer() && $pli->currentStatuer()->statuer->name == 'ramassé')
                                bg-info
                            @elseif ($pli->currentStatuer() && $pli->currentStatuer()->statuer->name == 'déposé')
                                bg-success
                            @elseif ($pli->currentStatuer() && $pli->currentStatuer()->statuer->name == 'annulé')
                                bg-danger
                            @elseif ($pli->currentStatuer() && $pli->currentStatuer()->statuer->name == 'retourné')
                                bg-success
                            @endif">
                            {{ $pli->currentStatuer() ? $pli->currentStatuer()->statuer->name : 'Non défini' }}
                        </span>
                    </td>
                    <td class="text-nowrap">{{ $pli->currentStatuer() && $pli->currentStatuer()->statuer->name == 'annulé' ? $pli->currentStatuer()->raison_annulation : 'N/A' }}</td>
                    <td class="text-nowrap">{{ $pli->statuerHistory->where('statuer_id', 1)->last()?->date_changement ? \Carbon\Carbon::parse($pli->statuerHistory->where('statuer_id', 1)->last()->date_changement)->format('d/m/Y H:i') : 'Non défini' }}</td>
                    <td class="text-nowrap">{{ $pli->statuerHistory->where('statuer_id', 2)->last()?->date_changement ? \Carbon\Carbon::parse($pli->statuerHistory->where('statuer_id', 2)->last()->date_changement)->format('d/m/Y H:i') : 'Non défini' }}</td>
                    <td class="text-nowrap">{{ $pli->statuerHistory->where('statuer_id', 3)->last()?->date_changement ? \Carbon\Carbon::parse($pli->statuerHistory->where('statuer_id', 3)->last()->date_changement)->format('d/m/Y H:i') : 'Non défini' }}</td>
                    <td class="text-nowrap">{{ $pli->statuerHistory->where('statuer_id', 4)->last()?->date_changement ? \Carbon\Carbon::parse($pli->statuerHistory->where('statuer_id', 4)->last()->date_changement)->format('d/m/Y H:i') : 'Non défini' }}</td>
                    <td class="text-nowrap">{{ $pli->statuerHistory->where('statuer_id', 5)->last()?->date_changement ? \Carbon\Carbon::parse($pli->statuerHistory->where('statuer_id', 5)->last()->date_changement)->format('d/m/Y H:i') : 'Non défini' }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('client.plis.show', $pli->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('client.edit-pli', $pli->id) }}" class="btn
                            @if ($pli->currentStatuer() && in_array($pli->currentStatuer()->statuer->name, ['en attente', 'ramassé', 'déposé', 'annulé', 'retourné']))
                                btn-secondary disabled
                            @else
                                btn btn-warning btn-sm me-2
                            @endif
                            btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <!--Bouton delete-->
                        
                        <!--<a href="{{ url('client/delete-pli/'.$pli->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>-->
                        
                        <!--<a href="javascript:void(0);" class="btn-->
                        <!--@if ($pli->currentStatuer() && in_array($pli->currentStatuer()->statuer->name, ['en attente', 'ramassé', 'déposé', 'annulé', 'retourné']))-->
                        <!--        btn-secondary disabled-->
                        <!--    @else-->
                        <!--        btn btn-warning btn-sm me-2-->
                        <!--    @endif-->
                        <!--btn-danger btn-sm btn-delete"-->
                        
                        <!--data-pli-id="{{ $pli->id }}" data-delete-url="{{ url('client/delete-pli/'.$pli->id) }}"><i class="fas fa-trash"></i></a>-->
                        
                         <a href="javascript:void(0);" class="btn
                        @if ($pli->currentStatuer() && in_array($pli->currentStatuer()->statuer->name, ['en attente', 'ramassé', 'déposé', 'annulé', 'retourné']))
                                btn-secondary disabled
                            @else
                                btn btn-warning btn-sm me-2
                            @endif
                        btn-danger btn-sm btn-delete"
                        
                        data-pli-id="{{ $pli->id }}" data-delete-url="{{ route('plis.supprimer', $pli->id) }}"><i class="fas fa-trash"></i></a>
                        
                        

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    @if(session('message'))
        <div class="alert alert-success" role="alert">
            {{ session('message') }}
        </div>
    @endif


                    <!-- Modale pour afficher les détails de la référence -->
                <div class="modal fade" id="referenceModal" tabindex="-1" aria-labelledby="referenceModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="referenceModalLabel">Références du pli</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p id="referenceDetails">Chargement des détails...</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="no-print">
                <div class="d-flex justify-content-center" id="nb-plis">
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

            </div>
         {{-- <a href="print" target="_blank" rel="noopener noreferrer">Imprimer</a> --}}



   <!-- button print -->
    <p class="no-print" id="btn-print">
        <button onclick="window.print()" class="btn btn-warning">Imprimer les plis</button>
    </p>
    <!-- /button print -->

<!-- Style for print-->

    <style>
/*        @media print{*/
/*                    #table-p{*/
/*                            width:300px !important;*/
/*                            margin:auto;*/
/*                            height: 100%;*/
/*                            border: 1px !important;*/
/*                            text-align: center;*/
/*                    }*/

/*                    .text-muted,nav,*/
/*                   .sb-sidenav-menu,*/
/*                   .no-print,form,*/
/*                   .text-nowrap,nav{*/
/*                        display:none !important;*/
/*                    }*/

/*                .text-nowrap-pr{*/
/*                        height: 11px !important;*/
/*                        font-size:10px;*/
/*                        padding:0px !important;*/

/*                          }*/


/*                .table-responsive {*/
/*                        overflow: hidden;*/
/*                        max-height: 100% !important;*/
/*                        width:auto !important;*/
/*                        height: auto;*/
/*                    }*/

/*            .info-print{*/
/*                display: inline !important;*/
/*            }*/

/*        header{*/
/*            display:none;*/
/*        }*/

/*}*/



/*Style d'impression avec filigrane ---------*/


        @media print {
        #table-p {
            width: 300px !important;
            margin: auto;
            height: 100%;
            border: 1px solid !important;
            text-align: center;
        }

        .text-muted, nav,
        .sb-sidenav-menu,
        .no-print, form,
        .text-nowrap, nav {
            display: none !important;
        }

        .text-nowrap-pr {
            height: 11px !important;
            font-size: 10px;
            padding: 0px !important;
        }

        .table-responsive {
            overflow: hidden;
            max-height: 100% !important;
            width: auto !important;
            height: auto;
        }

        .info-print {
            display: inline !important;
            position: relative;
            z-index: 2;
        }

        header {
            display: none;
        }

         /*Ajout du filigrane */
         
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
              background-image: url('https://ivoirrapid.ci/asset/Logo%20IRN.png'); /* Lien vers ton image  */
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.2; /* Ajuste l'opacité du filigrane  */
            z-index: 1;
            
     print-color-adjust: exact; /* Standard CSS */
      
            
            
        }
        
    }


/*Fin style avec filigrane*/

.info-print{
        display: none;
}




  #btn-print {
    position: fixed; /* Permet de fixer le bouton à une position précise par rapport à la fenêtre */
    top: 50%; /* Place le bouton verticalement au milieu */
    right: 0; /* Aligne le bouton à droite */
    transform: translateY(-50%); /* Centre le bouton verticalement */
    z-index: 9999; /* Assure que le bouton soit au-dessus de tous les autres éléments */
}

</style>

<!-- Pour le logo qui affiche  sur la feuille d'impression--->
    <div class="info-print">
    <img src="https://ivoirrapid.ci/asset/Logo%20IRN.png" alt="Logo" class="logo-print">
</div>

<style>
        @media print {
    .logo-print {
        position: fixed;
        top: 0; /* Ajuste la position */
        left: -6px; /* Ajuste la position */
        width: 50px; /* Définit la taille */
        opacity: 0.5; /* Assure la visibilité */
        margin:0;
        padding:0;
    }
}


</style>





<!--/ Style for print-->


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
    background-color: #66ff00; /* Couleur de la barre */
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
    const statusFilter = document.getElementById('statusFilter'); // Menu déroulant pour le statut
    const clearFiltersBtn = document.getElementById('clearFilters'); // Bouton pour effacer les filtres
    const tableRows = document.querySelectorAll('table tbody tr'); // Lignes du tableau

    // Fonction pour filtrer les lignes selon le statut sélectionné
    function filterRows(status) {
        tableRows.forEach(function (row) {
            const rowStatus = row.querySelector('td:nth-child(14) .badge'); // Colonne 13 avec le statut
            if (rowStatus) {
                const statusText = rowStatus.textContent.trim(); // Texte du badge
                if (statusText === status || status === '') {
                    row.style.display = ''; // Afficher la ligne si le statut correspond ou pas de filtre
                } else {
                    row.style.display = 'none'; // Masquer la ligne si le statut ne correspond pas
                }
            }
        });
    }

    // Écouteur pour le changement de statut dans le menu déroulant
    statusFilter.addEventListener('change', function () {
        const selectedStatus = statusFilter.value; // Valeur sélectionnée
        filterRows(selectedStatus); // Appliquer le filtre
    });

    // Écouteur pour réinitialiser les filtres
    clearFiltersBtn.addEventListener('click', function () {
        statusFilter.value = ''; // Réinitialiser le menu déroulant
        filterRows(''); // Réafficher toutes les lignes
    });

    // Application initiale (afficher toutes les lignes)
    filterRows('');
});





    document.addEventListener('DOMContentLoaded', function () {
        const referenceModal = document.getElementById('referenceModal');
        const referenceDetails = document.getElementById('referenceDetails');

        document.querySelectorAll('.view-reference-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                const reference = this.getAttribute('data-reference');

                // Charger les détails de la référence (exemple avec une requête AJAX ou un contenu statique)
                referenceDetails.textContent = `Détails de la référence : ${reference}`;

                // Vous pouvez utiliser AJAX pour charger des informations supplémentaires si nécessaire
                // Exemple :
                // fetch(`/api/references/${reference}`)
                //     .then(response => response.json())
                //     .then(data => {
                //         referenceDetails.textContent = data.details;
                //     })
                //     .catch(error => {
                //         referenceDetails.textContent = "Erreur lors du chargement des détails.";
                //     });
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('select[name="statuer"]').forEach(function(select) {
            var pliId = select.id.split('_')[1];
            var raisonField = document.getElementById('raison_' + pliId);

            if (select.value === 'annulé') {
                raisonField.disabled = false;
            } else {
                raisonField.disabled = true;
            }

            select.addEventListener('change', function() {
                raisonField.disabled = this.value !== 'annulé';
            });
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.btn-delete');
    const confirmDeleteButton = document.getElementById('confirmDeleteButton');
    const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));

    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            const pliId = this.getAttribute('data-pli-id'); // Récupère l'ID du pli à supprimer
            const deleteUrl = this.getAttribute('data-delete-url'); // Récupère l'URL de suppression

            // Met à jour le lien du bouton de confirmation pour qu'il supprime le pli correspondant
            confirmDeleteButton.setAttribute('href', deleteUrl);
            confirmDeleteButton.setAttribute('data-pli-id', pliId);

            // Ouvre le modal de confirmation
            confirmDeleteModal.show();
        });
    });
});
</script>

<script>
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

<!-- Styles d'impression -->
<style>
    @media print /* {

         .container-fluid {
            visibility: visible;
            position: absolute;
            left: 0;
            top: 0;
        }

        .btn {
            display: none;
        }

        .table-responsive {
            overflow: visible;
            max-height: none;
        }

        .table {
            width: 100%;
            border: none;  /* Pas de bordures du tableau */
            /* border-collapse: collapse; */
        /* } */

        /* .table th, .table td { */
            /* padding: 5px; */
            /* text-align: left; */
            /* border: none;  Pas de bordures sur les cellules */
        /* } */

        /* Masquer les colonnes spécifiques lors de l'impression */
        /* .table th:nth-child(12), .table td:nth-child(12),
        .table th:nth-child(13), .table td:nth-child(13),
        .table th:nth-child(14), .table td:nth-child(14),
        .table th:nth-child(15), .table td:nth-child(15),
        .table th:nth-child(16), .table td:nth-child(16),
        .table th:nth-child(17), .table td:nth-child(17),
        .table th:nth-child(18), .table td:nth-child(18),
        .table th:nth-child(19), .table td:nth-child(19),
        .table th:nth-child(20), .table td:nth-child(20) {
            display: none; */
        /* }
    }  */
</style>

@endsection



