@extends('layouts.master')

@section('title', 'IvoirRp - Plis')

@section('content')

<div class="container-fluid px-4">
            <h2 class="mt-4" style="text-align:center">Gestion des Plis (Suivi de statuts)</h2>
            <hr>

{{-- Ajout le 02-05-2025---------------------------------------------pour le signalement en cas de facture sans état final ------------------------}}
            <div>
                @if(($plisAttribuesSansStatutFinal)> 0)
                <div class="clignotant">
                    {{-- ⚠️ Plis Non livrés après 72H : {{ count($plisAttribuesSansStatutFinal) }} ⚠️ --}}
                    ⚠️ Plis Non livrés après 72H : ⚠️
                </div>
                @else
                {{-- <p>Aucun pli en retard</p> --}}
                @endif
            </div>

        <style>
        @keyframes clignoter {
            0% { color: red; }
            50% { color: white; }
            100% { color: red; }
        }

        .clignotant {
            font-weight: bold;
            font-size: 40px;
            animation: clignoter 1s infinite;
            text-align: center;
        }
        </style>
{{-- Fin ajout 02-05-2025--------------------------------------------------------------------------------------------------------------- --}}

{{-- Oiur le recap des plis bouton non cliquable ------------------------------------------------------------------------------------------------ --}}
<div class="card card-sm" style="gap:; display: flex; text-align:">
    <div class="card-body">

    {{-- -----------------------------09-05-2025------------------------------- --}}

    <div class="card card-sm" style="display: flex; text-align: center;">
        <div class="card-body">
            {{-- <h6 class="card-title">📌 Plis Finalisés (Total : <strong>{{ $totalPlisFinalises }}</strong>)</h6> --}}
            <h6 class="card-title">Actions rapides</h6>

            <div class="statuts-wrapper d-flex justify-content-center align-items-center flex-wrap gap-3">

                <!-- Plis à suivre -->
                <div class="statut-item circle-item">
                    @if (count($plisASuivre)!==0)
                        <i class="fas fa-eye text-success"></i>
                        <span><strong style="color:green;">Plis à suivre : <br> {{ count($plisASuivre) }}</strong></span>
                    @else
                        <h6>Vous n'avez aucun plis à suivre</h6>
                    @endif
                </div>

                <!-- Liens et actions dans des cercles -->
                <div class="circle-link">
                    <a href="{{ route('plis.historique') }}" class="circle-item text-danger">
                        <i class="fas fa-history"></i> <span>Historique</span>
                    </a>
                </div>

                <div class="circle-link">
                    <a href="{{ route('admin.attributions.index') }}" class="circle-item text-outline-primary">
                        <i class="fas fa-user-plus"></i> <span>Attributions</span>
                    </a>
                </div>

                <div class="circle-link">
                    <a href="{{ route('admin.plis.plis_trashed') }}" class="circle-item btn-warning btn-sm">
                        <i class="fas fa-trash"></i> <span>Plis supprimés</span>
                    </a>
                </div>

            </div>
        </div>
    </div>


        <style>

.circle-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    text-align: center;
    background-color: #f2f2f2;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    padding: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
}

.circle-item i {
    font-size: 16px;
    margin-bottom: 4px;
}

.circle-item:hover {
    background-color: #d9d9d9;
    transform: scale(1.1);
}

/* Style spécial pour les liens */
.circle-link a {
    text-decoration: none;
    color: black;
    font-weight: bold;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.circle-link a span {
    font-size: 11px;
    margin-top: 5px;
}


        </style>



    {{-- -------------------09+-05-2025 --}}
{{-- Fin de recap button non cliquable -------------------------------------------------------------------------------------------- --}}



    <div class="search-bar mb-4">
        {{-- <div class="input-group">
            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un pli..." />
            <button class="btn btn-primary" id="btnSearch" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div> --}}
    </div>

    <!-- Filtres -->
    <div class="card mb-4">

    {{--    09 05------------------------------------------------------------------------ --}}

                {{-- Pour effectuer les recherches------------------------------------------------------------------------------------ --}}
 {{-- Pour effectuer les recherches------------------------------------------------------------------------------------ --}}
 <br>
 <div class="search-section" onclick="toggleSearch()"  style="text-align:center !important">
     <h4 class="search-title"> Effectuer des recherches</h4>
     <div>
         {{-- Affiche resultat des rechercher --}}
         @if(!empty($_GET['code']))
         <div class="alert alert-success mt-3">
             <strong>Résultat de recherche pour  : Numéro de pli <b>{{ $_GET['code'] }}</b></strong> {{ count($plis) }} trouvé(s) !
                <i>
                     <a href="{{ route('client.plis.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Raffraichir</a>
                 </i>
         </div>
         @endif

         @if(!empty($_GET['start_date'])&&!empty($_GET['end_date']))
         <div class="alert alert-success mt-3">
             <strong>Résultat de recherche pour  la période du  <b>{{ $_GET['start_date'] }}</b></strong> au  {{ $_GET['end_date'] }},  {{ count($plis) }} trouvé (s) !
             <i>
                 <a href="{{ route('client.plis.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Raffraichir</a>
             </i>
         </div>
         @endif

         @if(!empty($_GET['destinataire_name']))
         <div class="alert alert-success mt-3">
             <strong>Résultat de recherche pour le destinataire : <b>{{ $_GET['destinataire_name'] }}, </b></strong> {{ count($plis) }} trouvé(s) !
             <i>
                 <a href="{{ route('client.plis.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Raffraichir</a>
             </i>
         </div>
         @endif


         @if(!empty($_GET['reference']))
         <div class="alert alert-success mt-3">
             <strong>Résultat de recherche pour le numéro de reférence : <b>{{ $_GET['reference'] }}, </b></strong> {{ count($plis) }} trouvé(s) !
             <i>
                 <a href="{{ route('client.plis.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Raffraichir</a>
             </i> <br> <span style="color:red">Svp cliquez sur " <strong>Voir</strong>  "  dans la colonne " <strong>référence</strong> " du tableau pour vérifier  la reférence </span>
         </div>
         @endif


            @if(!empty($_GET['client']))
         <div class="alert alert-success mt-3">
             <strong>Résultat de recherche pour le client : <b>{{ $_GET['client'] }}, </b></strong> {{ count($plis) }} trouvé(s) !
             <i>
                 <a href="{{ route('client.plis.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Raffraichir</a>

<p>Cette recherche affiche tous les plis du client <strong>{{ $_GET['client'] }}</strong>, depuis sa création jusqu'à aujourd'hui.</p>

         </div>
         @endif

         {{-- /fin resultat des recherchers --}}
   </div>

     <div id="search-container" class="search-container" >

         <!-- 🔎 Filtre par Client------------ -->
                 <form method="GET" action="{{ route('client.plis.index') }}">
                     <div class="d-flex align-items-center">

                         @php
                            $clientA = \App\Models\Pli::pluck('user_name')->unique(); // ✅ Liste des destinataires
                        @endphp

                        {{-- <input list="destinatairesList" name="destinataire_name" class="form-control" placeholder="Recherchez un destinataire..."> --}}
                        <input list="destinatairesList" name="client" class="form-control" placeholder="Recherchez avec un client..." autocomplete="off">

                        <datalist id="destinatairesList">
                            @foreach($clientA as $destinataire)
                                <option value="{{ $destinataire }}"></option>
                            @endforeach
                        </datalist>




                         <button type="submit" class="btn btn-primary btn-sm ms-2">Filtrer</button>
                         <a href="{{ route('client.plis.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Effacer</a>
                     </div>
                 </form>

         <!-- 🏷️ Filtre par code -->
                       <!-- 🔍 Recherche par code -->
                       <form method="GET" action="{{ route('client.plis.index') }}" class="mt-3">
                         <div class="row">
                             <div class="col-md-12 d-flex align-items-center">
                                 <input type="text" name="code" class="form-control form-control-sm me-2" style="width: 70%;" placeholder="Entrez le code du pli...">
                                 <button type="submit" class="btn btn-primary btn-sm">Rechercher</button>
                                 <a href="{{ route('client.plis.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Effacer</a>
                             </div>
                         </div>
                     </form>


      <!-- 🏷️ Filtre par reference de facture -->
                       <!-- 🔍 Recherche reference de facture -->
                       <form method="GET" action="{{ route('client.plis.index') }}" class="mt-3">
                         <div class="row">
                             <div class="col-md-12 d-flex align-items-center">
                                 <input type="text" name="reference" class="form-control form-control-sm me-2" style="width: 70%;" placeholder="Entrez la reference  de la facture...">
                                 <button type="submit" class="btn btn-primary btn-sm">Rechercher</button>
                                 <a href="{{ route('client.plis.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Effacer</a>
                             </div>
                         </div>
                     </form>


         <!-- 🗓️ Filtre par date -->
                 <form method="GET" action="{{ route('client.plis.index') }}" class="mt-3">
                     <div class="row g-3">
                         <div class="col-md-4">
                             <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request()->start_date }}">
                         </div>
                         <div class="col-md-4">
                             <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request()->end_date }}">
                         </div>
                         <div class="col-md-4 d-flex align-items-end">
                             <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
                             <a href="{{ route('client.plis.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Effacer</a>
                         </div>
                     </div>
                 </form>
           </div>
       </div>

 <!-- 🎨 Styles améliorés -->
 <style>
     .search-section {
         background-color: rgba(200, 200, 200, 0.3);
         padding: 12px;
         border-radius: 8px;
         cursor: pointer;
         transition: background 0.4s ease-in-out;
     }

     .search-section:hover {
         background-color: rgba(200, 200, 200, 0.5);
     }

     .search-title {
         font-weight: bold;
         color: #01264e;
     }

     .search-container {
             visibility: hidden;
             opacity: 0;
             max-height: 0;
             overflow: hidden;
             transition: opacity 0.5s ease-in-out, max-height 0.5s ease-in-out;
         }

         .search-container.visible {
             visibility: visible;
             opacity: 1;
             max-height: 600px !important;
         }


     .search-input {
         padding: 10px;
         border: 1px solid #ccc;
         border-radius: 5px;
     }

     .search-button {
         padding: 10px;
         background: #007bff;
         color: #fff;
         border: none;
         cursor: pointer;
         transition: background 0.3s ease;
     }

     .search-button:hover {
         background: #0056b3;
     }
 </style>

 <!-- 🎬 Script de transition fluide -->

 <script>
     document.addEventListener("click", function(event) {
         let searchSection = document.querySelector(".search-section");
         let searchContainer = document.getElementById("search-container");

         // Vérifie si le clic est à l'intérieur ou à l'extérieur
         if (searchSection.contains(event.target)) {
             searchContainer.classList.add("visible"); // Garde ouvert lorsqu'on clique à l'intérieur
         } else {
             searchContainer.classList.remove("visible"); // Ferme uniquement si le clic est en dehors
         }
     });
 </script>

{{-- fin Pour effectuer les recherches------------------------------------------------------------------------------------ --}}
{{--Fin  Pour effectuer les recherches------------------------------------------------------------------------------------ --}}

 {{--  ------------------------09-05-2025-  ------- -------------------------- --}}

    </div>
    </div> <br>

{{-- Debut du tableau ---------------------------------------------------------------------------------------------------------------- --}}

{{-- Nombre de plis à suivre --}}

                <style>
                .statuts-wrapper {
                    display: flex;
                    flex-wrap: nowrap; /* Empêche les éléments de passer à la ligne */
                    gap: 10px; /* Espacement entre les éléments */
                    overflow-x: auto; /* Ajoute un scroll horizontal si nécessaire */
                }

                .statut-item {
                    background-color: #f8f9fa;
                    padding: 6px 12px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    font-size: 13px;
                    white-space: nowrap; /* Évite le retour à la ligne */
                }

                </style>

        </div>

<br>
<h5>Recherche rapide</h5>
<div class="input-group">

    <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un pli par code..." />
    <button class="btn btn-primary" id="btnSearch" type="button">
        <i class="fas fa-search"></i>
    </button>
</div>

<style>
    .checkbox-container {
        background-color: #f8f9fa; /* Fond légèrement gris */
        padding: 10px;
        border-radius: 8px;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
        justify-content: start;
    }

    .checkbox-container label {
        display: flex;
        align-items: center;
        cursor: pointer;
        font-weight: bold;
        gap: 8px;
        padding: 6px 10px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .checkbox-container label:hover {
        background-color: #e2e6ea;
    }

    /* Personnalisation du checkbox */
    .checkbox-container input[type="checkbox"] {
        accent-color: #007bff; /* Couleur lorsqu'il est coché */
        transform: scale(1.2); /* Taille légèrement augmentée */
    }
</style>

<br>
<h6>Mode d'affichage du tableau</h6>
<div class="checkbox-container">

    <label>
        <input type="checkbox" class="toggle-column" data-column="2" >
        Type
    </label>
    <label>
        <input type="checkbox" class="toggle-column" data-column="3" >
        Date de création du pli
    </label>
    <label>
        <input type="checkbox" class="toggle-column" data-column="9" >
        Date de Attribution Dépot
    </label>
    <label>
        <input type="checkbox" class="toggle-column" data-column="8" >
        Date Attribution Ramassage
    </label>

    <label>
        <input type="checkbox" class="toggle-column" data-column="11" >
        Actions
    </label>
</div>

{{-- Message d'erreur pour le suivi des stauts --}}

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

{{-- / Message d'erreur pour le suivi des stauts --}}

    <div class="table-responsive scrollable-container">
        <table class="table table-bordered table-striped mt-3">
            <thead class="bg-light text-center">
                <tr>
                    <th class="text-nowrap">#</th>
                    <th class="text-nowrap">No de Suivie</th>
                    <th class="text-nowrap hidden-column">Type</th>
                    <th class="text-nowrap hidden-column">Date de création</th>
                    <th class="text-nowrap">Client</th>
                    <th class="text-nowrap">Destinataire</th>
                    <th class="text-nowrap">Coursier Ramassage</th>
                    <th class="text-nowrap">Coursier Dépôt</th>
                    <th class="text-nowrap hidden-column">Date Attribution Ramassage</th>
                    <th class="text-nowrap hidden-column">Date Attribution Dépôt</th>
                    <th class="text-nowrap">Statut</th>
                    <th class="text-nowrap hidden-column">Actions</th>
                </tr>
            </thead>
            <style>
                         .table-responsive {
                    max-height: 500px; /* Ajuste la hauteur max selon ton besoin */
                    overflow-y: auto; /* Permet le défilement uniquement en vertical */
                    position: relative;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                }

                thead {
                    position: sticky;
                    top: 0;
                    background-color: #f8f9fa; /* Fond fixe pour l’en-tête */
                    z-index: 10; /* Assure qu’il reste au-dessus du reste du tableau */
                    box-shadow: 0px 2px 5px rgba(0,0,0,0.1); /* Effet léger pour démarquer l’en-tête */
                }

            </style>
            <tbody>
                {{-- @foreach ($plis as $pli) --}}


{{-- --------------------Ajout avec le statut--------- 09-05-2025---------- --}}
                @foreach ($plis as $pli)

             @if(!$pli->currentStatuer())

                {{-- @if (!empty($pli1) && $pli1->id) --}}

                    <tr>
                        <!-- No de Suivie -->
                        <td class="text-center text-nowrap">{{ $loop->iteration }}
                                <span style="size:9px !important">
                                    {{-- <td class="text-nowrap position-sticky" style="background-color: white; z-index: 10; right: 0;"> --}}
                                        @if (isset($pli->destinataire->id))
                                            <a href="{{ url('client/edit-destinataire/'.$pli->destinataire->id) }}" class="btn btn-warning btn-sm me-1" title="Editer le destinataire">
                                                <i class="fas fa-edit" title="Editer le destinataire"></i>
                                                {{-- <span> {{ $pli->user_name }} </span> --}}
                                            </a>
                                        @else
                                            <strong><span>Destinataire Supprimé</span></strong>

                                        @endif

                                </span>
                        </td>


                        <td class="text-center text-nowrap">{{ $pli->code }}

                        </td> {{-- Code du pli --}}
                        <td class="text-center text-nowrap hidden-column">{{ $pli->type }}</td> {{-- Type de pli --}}

                             <td class="text-center text-nowrap hidden-column">

                                    {{ $pli->created_at }}

                            </td> {{-- Date de création du pli --}}

                        <!-- Client -->
                        <td class="text-nowrap">
                            <div><strong>{{ $pli->user->name }}</strong></div> {{--Information du client  --}}
                            <div class="text-muted small">Tél: {{ $pli->user->Telephone ?? 'N/A' }}</div>
                            <div class="text-muted small"> {{ $pli->user->Zone ?? 'N/A' }}</div>
                        </td>
                        <!-- Destinataire -->
                        <td class="text-nowrap">
                            <div><strong>{{ $pli->destinataire->name ?? 'N/A' }}</strong>

                            </div>

                            <div class="text-muted small">Tél: {{ $pli->destinataire->telephone ?? 'N/A' }}</div>
                            <div class="text-muted small">Zone: {{ $pli->destinataire->zone ?? 'N/A' }}</div>
                        </td>
                        <!-- Coursiers -->
                        <td class="text-center text-nowrap">
                            {{ isset($pli->attributions[0]->coursierRamassage) ? $pli->attributions[0]->coursierRamassage->prenoms   ?? 'Non défini' : 'Non défini' }}

                            <div class="text-muted small">
                              {{ isset($pli->attributions[0]->coursierRamassage) ? $pli->attributions[0]->coursierRamassage->nom   ?? 'Non défini' : 'Non défini' }}
                            </div>


                              <div class="text-muted small">
                                {{ isset($pli->attributions[0]->coursierRamassage) ? implode(', ', $pli->attributions[0]->coursierRamassage->zones) ?? 'Non défini' : 'Non défini' }}
                            </div>
                        </td>
                        <!-- Coursier de dépôt ------------------------------>

{{--
                        <td class="text-center text-nowrap">
                            {{ isset($pli->attributions[0]->coursierDepot) ? $pli->attributions[0]->coursierDepot->prenoms ?? 'Aucun coursier' : 'Aucun coursier' }}

                            <div class="text-muted small">
                                {{ isset($pli->attributions[0]->coursierDepot) ? $pli->attributions[0]->coursierDepot->nom   ?? 'Non défini' : 'Non défini' }}
                              </div>


                                <div class="text-muted small">
                                  {{ isset($pli->attributions[0]->coursierDepot) ? implode(', ', $pli->attributions[0]->coursierDepot->zones) ?? 'Non défini' : 'Non défini' }}
                              </div>

                        </td> --}}



                        <td class="text-center text-nowrap
                                @if(isset($pli->attributions[0]->coursierDepot)) bg-coursier-present
                                @else bg-coursier-absent
                                @endif">

                                {{ isset($pli->attributions[0]->coursierDepot) ? $pli->attributions[0]->coursierDepot->prenoms ?? 'Aucun coursier' : 'Aucun coursier' }}

                                <div class="text-muted small">
                                    {{ isset($pli->attributions[0]->coursierDepot) ? $pli->attributions[0]->coursierDepot->nom ?? 'Non défini' : 'Non défini' }}
                                </div>

                                <div class="text-muted small">
                                    {{ isset($pli->attributions[0]->coursierDepot) && !empty($pli->attributions[0]->coursierDepot->zones) ? implode(', ', $pli->attributions[0]->coursierDepot->zones) : 'Non défini' }}
                                </div>

                                 <style>
                                    .bg-coursier-present { background-color: #d6fadf !important ; color: white; } /* ✅ Vert = coursier présent */
                                    .bg-coursier-absent { background-color: #fbc8cd !important; color: white; }  /* ❌ Rouge = coursier absent */

                            </style>

                            </td>





                        <!-- Dates -->

                        <td class="text-center text-nowrap hidden-column">
                            {{ isset($pli->attributions[0]) && $pli->attributions[0]['date_attribution_ramassage']
                                ? \Carbon\Carbon::parse($pli->attributions[0]['date_attribution_ramassage'])->format('d/m/Y')
                                : 'Non défini' }}
                        </td>
                        <td class="text-center text-nowrap hidden-column">
                            {{ isset($pli->attributions[0]) && $pli->attributions[0]['date_attribution_depot']
                                ? \Carbon\Carbon::parse($pli->attributions[0]['date_attribution_depot'])->format('d/m/Y')
                                : 'Non défini' }}
                        </td>
                    {{-- Premier formulaire dans le tableau --}}
                        <td class="text-nowrap">
                            {{-- ---------------------------------------------------------- --}}
                            <form action="{{ route('plis.changeStatuer', $pli->id) }}" method="POST">
                                @csrf
                                <div id="statut-wrapper" class="zone-selection">
                                    <select name="statuer" id="statut-choice" class="form-control form-control-sm select-statut" required>
                                        <option value="">Sélectionner un statut</option>
                                        @foreach(['déposé', 'annulé', 'refusé'] as $statut)
                                            <option value="{{ $statut }}"
                                                {{ $pli->currentStatuer()?->statuer->name == $statut ? 'selected' : '' }}>
                                                {{ ucfirst($statut) }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <div id="raison-wrapper" style="display: none;">
                                        <input type="text" name="raison" id="raison-field"
                                            class="form-control form-control-sm input-raison mt-2"
                                            placeholder="Raison de refus ou annulation"
                                            value="{{ $pli->currentStatuer()?->raison_annulation ?? '' }}">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm mt-2">Mettre à jour</button>
                            </form>
                            {{-- ---------------------------------------------------- --}}
                            <style>
                                /* Couleurs des statuts */
                                .statut-en-attente { background-color: #f4de79; } /* Jaune */
                                .statut-ramasse { background-color: #79c4f4 !important; } /* Bleu */
                                .statut-depose { background-color: #79f48c !important; } /* Vert */
                                .statut-annule { background-color: #f47a79 !important   ; } /* Rouge */
                                .statut-refuse { background-color: #d679f4 !important; } /* Violet */

                                         #raison-container.statut-actif {
                                        display: block !important;
                                        visibility: visible !important;
                                        opacity: 1 !important;
                                    }

                            </style>
                        </td>

                    {{-- Fin formulaire dans le tableau --}}

                        <!-- Actions -->
                        {{-- <td class="text-center text-nowrap">
                        </td> --}}

                        <td class="text-center text-nowrap hidden-column">
                            {{-- Pour les actions  ------------------------------------------------}}


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

                    {{-- @endif --}}

                    @else
                        {{-- ------------------- CAS OU LE statut existe ------------------ --}}


                        <tr>
                            <!-- No de Suivie -->
                            <td class="text-center text-nowrap">{{ $loop->iteration }}
                                    <span style="size:9px !important">
                                        {{-- <td class="text-nowrap position-sticky" style="background-color: white; z-index: 10; right: 0;"> --}}
                                            @if (isset($pli->destinataire->id))
                                                <a href="{{ url('client/edit-destinataire/'.$pli->destinataire->id) }}" class="btn btn-warning btn-sm me-1" title="Editer le destinataire">
                                                    <i class="fas fa-edit" title="Editer le destinataire"></i>
                                                    {{-- <span> {{ $pli->user_name }} </span> --}}
                                                </a>
                                            @else
                                                <strong><span>Destinataire Supprimé</span></strong>

                                            @endif

                                    </span>
                            </td>


                            <td class="text-center text-nowrap">{{ $pli->code }}

                            </td> {{-- Code du pli --}}
                            <td class="text-center text-nowrap hidden-column">{{ $pli->type }}</td> {{-- Type de pli --}}

                                 <td class="text-center text-nowrap hidden-column">

                                        {{ $pli->created_at }}

                                </td> {{-- Date de création du pli --}}

                            <!-- Client -->
                            <td class="text-nowrap">
                                <div><strong>{{ $pli->user->name }}</strong></div> {{--Information du client  --}}
                                <div class="text-muted small">Tél: {{ $pli->user->Telephone ?? 'N/A' }}</div>
                                <div class="text-muted small"> {{ $pli->user->Zone ?? 'N/A' }}</div>
                            </td>
                            <!-- Destinataire -->
                            <td class="text-nowrap">
                                <div><strong>{{ $pli->destinataire->name ?? 'N/A' }}</strong>

                                </div>

                                <div class="text-muted small">Tél: {{ $pli->destinataire->telephone ?? 'N/A' }}</div>
                                <div class="text-muted small">Zone: {{ $pli->destinataire->zone ?? 'N/A' }}</div>
                            </td>
                            <!-- Coursiers ------------------------->

                            <td class="text-center text-nowrap">

                                {{ isset($pli->attributions[0]->coursierRamassage) ? $pli->attributions[0]->coursierRamassage->nom   ?? 'Non défini' : 'Non défini' }}
                                {{ isset($pli->attributions[0]->coursierRamassage) ? $pli->attributions[0]->coursierRamassage->prenoms   ?? 'Non défini' : 'Non défini' }}


                                <div class="text-muted small">
                                  {{ isset($pli->attributions[0]->coursierRamassage) ? $pli->attributions[0]->coursierRamassage->nom   ?? 'Non défini' : 'Non défini' }}
                                </div>


                                  <div class="text-muted small">
                                    {{ isset($pli->attributions[0]->coursierRamassage) ? implode(', ', $pli->attributions[0]->coursierRamassage->zones) ?? 'Non défini' : 'Non défini' }}
                                </div>

                            </td>



                            <style>
                                    .bg-coursier-present { background-color: #a7f9ba !!important; color: white; } /* ✅ Vert = coursier présent */
                                .bg-coursier-absent { background-color: #ffafb7 !important; color: white !important; }  /* ❌ Rouge = coursier absent */

                            </style>

                            <!-- Coursier de dépôt ------------------------------------------------->
                            <td class="text-center text-nowrap
                                        @if(isset($pli->attributions[0]->coursierDepot)) bg-coursier-present
                                        @else bg-coursier-absent
                                        @endif">
                                {{ isset($pli->attributions[0]->coursierDepot) ? $pli->attributions[0]->coursierDepot->prenoms ?? 'Aucun coursier' : 'Aucun coursier' }}

                                <div class="text-muted small">
                                    {{ isset($pli->attributions[0]->coursierDepot) ? $pli->attributions[0]->coursierDepot->nom   ?? 'Non défini' : 'Non défini' }}
                                  </div>
                                  {{-- <div class="text-muted small">
                                      {{ isset($pli->attributions[0]->coursierRamassage) ? $pli->attributions[0]->coursierRamassage->zones  ?? 'Non défini' : 'Non défini' }}
                                    </div> --}}

                                    <div class="text-muted small">
                                      {{ isset($pli->attributions[0]->coursierDepot) ? implode(', ', $pli->attributions[0]->coursierDepot->zones) ?? 'Non défini' : 'Non défini' }}
                                  </div>

                            </td>

                            <!-- Dates -->

                            <td class="text-center text-nowrap hidden-column">
                                {{ isset($pli->attributions[0]) && $pli->attributions[0]['date_attribution_ramassage']
                                    ? \Carbon\Carbon::parse($pli->attributions[0]['date_attribution_ramassage'])->format('d/m/Y')
                                    : 'Non défini' }}
                            </td>
                            <td class="text-center text-nowrap hidden-column">
                                {{ isset($pli->attributions[0]) && $pli->attributions[0]['date_attribution_depot']
                                    ? \Carbon\Carbon::parse($pli->attributions[0]['date_attribution_depot'])->format('d/m/Y')
                                    : 'Non défini' }}
                            </td>
                        {{-- Premier formulaire dans le tableau --}}
                            <td class="text-nowrap" style="background:#faf3b1">
                                {{-- ---------------------------------------------------------- --}}
                                <form action="{{ route('plis.changeStatuer', $pli->id) }}" method="POST">
                                    @csrf
                                    <div id="statut-wrapper" class="zone-selection">
                                        <select name="statuer" id="statut-choice" class="form-control form-control-sm select-statut" required>
                                            <option value="">Sélectionner un statut</option>
                                            @foreach(['déposé', 'annulé', 'refusé'] as $statut)
                                                <option value="{{ $statut }}"
                                                    {{ $pli->currentStatuer()?->statuer->name == $statut ? 'selected' : '' }}>
                                                    {{ ucfirst($statut) }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <div id="raison-wrapper" style="display: none;">
                                            <input type="text" name="raison" id="raison-field"
                                                class="form-control form-control-sm input-raison mt-2"
                                                placeholder="Raison de refus ou annulation"
                                                value="{{ $pli->currentStatuer()?->raison_annulation ?? '' }}">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-sm mt-2">Mettre à jour</button>
                                </form>
                                {{-- ---------------------------------------------------- --}}
                                <style>
                                    /* Couleurs des statuts */
                                    .statut-en-attente { background-color: #f4de79; } /* Jaune */
                                    .statut-ramasse { background-color: #79c4f4 !important; } /* Bleu */
                                    .statut-depose { background-color: #79f48c !important; } /* Vert */
                                    .statut-annule { background-color: #f47a79 !important   ; } /* Rouge */
                                    .statut-refuse { background-color: #d679f4 !important; } /* Violet */

                                             #raison-container.statut-actif {
                                            display: block !important;
                                            visibility: visible !important;
                                            opacity: 1 !important;
                                        }

                                </style>
                            </td>

                        {{-- Fin formulaire dans le tableau --}}

                            <!-- Actions -->
                            {{-- <td class="text-center text-nowrap">
                            </td> --}}

                            <td class="text-center text-nowrap hidden-column">
                                {{-- Pour les actions  ------------------------------------------------}}


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

                        <div class="alert alert-info d-flex align-items-center">
                            <i class="fas fa-exclamation-circle" style="margin-right: 8px; font-size: 18px; color: #007BFF;"></i>
                            <p style="margin: 0; font-size: 16px; font-weight: bold;">
                                Vous avez déjà mis à jour le statut de ce/ces plis.
                            </p>
                        </div>




                    {{-- Fin cas ------------------------------------------------------- --}}
                    @endif
                @endforeach

            </tbody>
        </table>

    </div>



    {{-- fin du tableau --------------------------------------------------------------------------------------------------------------- --}}

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

.hidden-column {
    display: none;
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
<script> // Pour la rechezrche dynamaique

    // document.addEventListener('DOMContentLoaded', function () {
    //     const searchInput = document.getElementById('searchInput');
    //     const tableRows = document.querySelectorAll('tbody tr');

    //     searchInput.addEventListener('keyup', function () {
    //         const searchTerm = this.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

    //         tableRows.forEach(function (row) {
    //             const columns = row.querySelectorAll('td');
    //             let match = false;

    //             const searchColumns = [
    //                 0, // Code de suivi
    //             ];

    //             searchColumns.forEach(colIndex => {
    //                 const columnText = columns[colIndex].textContent.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    //                 if (columnText.includes(searchTerm)) {
    //                     match = true;
    //                 }
    //             });

    //             row.style.display = match ? '' : 'none';
    //         });
    //     });
    // });

            document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('tbody tr');

            searchInput.addEventListener('keyup', function () {
                const searchTerm = this.value.toLowerCase().trim();
                let hasMatch = false;

                tableRows.forEach(function (row) {
                    const columns = row.querySelectorAll('td');
                    let match = false;

                    if (columns.length > 1) {
                        const columnText = columns[1].textContent.toLowerCase().trim();

                        if (columnText.includes(searchTerm)) {
                            match = true;
                            hasMatch = true;
                        }
                    }

                    row.style.display = match ? '' : 'none';
                });

                // ✅ Si le champ de recherche est vide, réafficher toutes les lignes
                if (searchTerm === "") {
                    tableRows.forEach(row => row.style.display = '');
                }
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


<script> // Pour les afficher et masquer les colonnes
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".toggle-column").forEach(function (checkbox) {
            checkbox.addEventListener("change", function () {
                let columnIndex = this.getAttribute("data-column");
                let isVisible = this.checked;
                document.querySelectorAll("table tr").forEach(function (row) {
                    row.children[columnIndex].classList.toggle("hidden-column", !isVisible);
                });
            });
        });
    });
</script>



<script> // Pour les zones du formulaire
        document.addEventListener("DOMContentLoaded", function() {
    // Sélectionner tous les formulaires de statut
    const selectStatuts = document.querySelectorAll(".select-statut");

    selectStatuts.forEach(selectElement => {
        // Détection de la zone de raison liée à ce select
        const raisonContainer = selectElement.closest('.zone-selection').querySelector("#raison-wrapper");

        function updateUI() {
            let statut = selectElement.value.trim().toLowerCase();

            if (statut === "annulé" || statut === "refusé") {
                raisonContainer.style.display = "block";
            } else {
                raisonContainer.style.display = "none";
            }
        }

        // Appliquer la logique lors du changement de statut
        selectElement.addEventListener("change", updateUI);

        // Vérification initiale lors du chargement de la page
        updateUI();
    });
});

</script>

        <audio id="alertSound">
            <source src="https://www.myinstants.com/media/sounds/alarm.mp3" type="audio/mpeg">
        </audio>

        <script> // Pour l'audio
                // window.onload = function() {
                //     document.getElementById("alertSound").play(); // 🔊 Joue le son à l’ouverture de la page
                // };
        </script>

        <script>
            window.onload = function() {
                let nombrePlisNonLivres = {{ $plisAttribuesSansStatutFinal }}; // 🔥 Récupère la valeur PHP


                if (nombrePlisNonLivres > 0) { // 🚨 Joue le son uniquement si danger
                    document.getElementById("alertSound").play();
                }
            };
        </script>

            {{-- <button onclick="playAlertSound()" class="btn btn-danger">🚨 Alerte Plis Non Livrés 🚨</button> --}}

                    <audio id="alertSound">
                        <source src="https://www.myinstants.com/media/sounds/alarm.mp3" type="audio/mpeg">
                    </audio>

                    <script>
                    function playAlertSound() {
                        let audio = document.getElementById("alertSound");
                        audio.play(); // 🔊 Joue le son lorsqu'on clique sur le bouton
                    }
                </script>

                <audio id="alertSound">
                    <source src="https://www.myinstants.com/media/sounds/alarm.mp3" type="audio/mpeg">
                </audio>

                <audio id="alertSound">
                    <source src="https://www.myinstants.com/media/sounds/alarm.mp3" type="audio/mpeg">
                </audio>

                <script>
                document.addEventListener("DOMContentLoaded", function() {
                    let nombrePlisNonLivres = {{ $plisAttribuesSansStatutFinal }}; // 🔥 Récupère la valeur Blade

                    function jouerAlerte() {
                        let count = 0;
                        if (nombrePlisNonLivres > 0) { // 🚨 Si nombre > 0, joue l'alerte 10 fois
                            let repeatSound = setInterval(() => {
                                if (count < 10) {
                                    document.getElementById("alertSound").play();
                                    count++;
                                } else {
                                    clearInterval(repeatSound);
                                }
                            }, 3000);
                        }
                    }

                    jouerAlerte(); // 🔥 Déclenche le son au chargement SI nombre > 0
                });
                </script>







@endsection
