
@extends('layout.master')

@section('title', 'IvoirRp - plis')

@section('content')

@include('client.plis.chield_index_plis')

@include('client.plis.liste_de_plis')

{{-- En tête pour faire l'impression n'apparait pas dans le blade -------------------------------------------------------------------------- --}}


{{-- fin en tête -------------------------------------------------------------------------------------------------------------------- --}}

{{-- Pour afficher les message erreur ou success de l'app --------------------------------------------------------------- --}}
{{-- Pour afficher les message erreur ou success de l'app --------------------------------------------------------------- --}}
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
{{--  Fin Pour afficher les message erreur ou success de l'app --------------------------------------------------------------- --}}
{{-- Pour afficher les message erreur ou success de l'app --------------------------------------------------------------- --}}

        {{-- Fin message --}}

    {{-- --------------------------------------------------------------------------------------------------------------------- --}}
        <div class="container-fluid px-4">
        <div class="no-print">
                <h1 class="mt-4" style="text-align:center">Suivi de plis  </h1>
                <p style="text-align:center"><i>Total plis ( {{ $totalPlis }}) </i></p>
                 <hr>

        {{-- Tous les boutons avec les modales possible ---------------07-05-2025------------------------------------------- --}}
    {{-- Tous les boutons avec les modales possible ---------------07-05-2025------------------------------------------- --}}
                <div class="d-flex justify-content-center gap-5" style="background-color:#e3fcfc; padding:4px">

                    <!-- Bouton 1 : Plis Ramassés -->
                    <div class="button-container">
                        <button type="button" class="btn btn-dark custom-btn" data-bs-toggle="modal" data-bs-target="#plisNonAttribuesModal">
                            <i class="fas fa-box"></i>
                        </button>
                        <p class="btn-label">Plis Ramassés ou en cours de livraison  ({{ $totalPlisRamassesOuAttribues }})  </p>
                    </div>

                    <!-- Bouton 2 : Plis en Dépôt -->
                    <div class="button-container">
                        <button type="button" class="btn btn-dark custom-btn" data-bs-toggle="modal" data-bs-target="#plisStatutFinal">
                            <i class="fas fa-truck"></i>
                        </button>
                        <span class="btn-label">Plis à destination ou statut final  ({{  $totalPlisFinauxClient }}) </span>
                    </div>

                    <!-- Bouton 3 : Autres Plis -->
                    <div class="button-container">
                        <button type="button" class="btn btn-dark custom-btn" data-bs-toggle="modal" data-bs-target="#autresPlisModal">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                        <span class="btn-label">Autres Plis</span>
                    </div>
                    {{-- Ajouter un nouveau pli --}}
                    <div class="button-container">
                        <a  href="{{ url('client/add-pli') }}" class="btn btn-dark custom-btn">
                            <i class="fas fa-box"></i> <i class="fas fa-plus"></i> <!-- Icônes pour représenter l'ajout -->
                        </a>
                        <p class="btn-label">Créer un nouveau pli</p>
                    </div>
                </div>

                <style>
                    /* 🌑 Fond légèrement sombre */
                .custom-btn {
                    background-color: rgba(50, 50, 50, 0.8);
                    color: white;
                    border-radius: 50%;
                    width: 50px;
                    height: 50px;
                    font-size: 18px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    transition: background 0.3s ease-in-out, transform 0.2s ease-in-out;
                }


                thead {
                        position: sticky;
                        top: 0;
                        background-color: white; /* Assure que l'en-tête reste visible */
                        z-index: 1000; /* Place l'en-tête au-dessus du contenu */
                    }



                    /* 🖱️ Effet au survol */
                    .custom-btn:hover {
                            background-color: rgba(110, 110, 112, 0.9);
                            transform: scale(1.1);
                        }

                    /* 🎨 Tooltip pour affichage du texte */
                    .custom-btn[data-bs-toggle="tooltip"]::after {
                        content: attr(title);
                        position: absolute;
                        background: rgba(0, 0, 0, 0.8);
                        color: white;
                        padding: 6px;
                        border-radius: 5px;
                        font-size: 14px;
                        white-space: nowrap;
                        opacity: 0;
                        transition: opacity 0.3s ease-in-out;
                    }

                    .custom-btn:hover::after {
                        opacity: 1;
                    }

                    .btn-label {
                            font-size: 14px;
                            font-weight: bold;
                            color: #333;
                            margin-top: 8px;
                            display: block;
                            text-align: center;
                            width: 100%; /* Assure que le texte et le bouton ont la même largeur */
                        }

                        .button-container {
                                width: 180px; /* ✅ Assure que chaque bloc fait au moins 100px */
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                justify-content: center;
                            }

                            .d-flex.justify-content-center {
                                    gap: 4rem; /* ✅ Augmente l'espace entre les boutons */
                                }

                </style>

                <script>
                    // ✅ Active les tooltips Bootstrap
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                </script>
    {{-- Fin de boutoon pour ouvrir le modal --}}

{{--  Fin  avec son css Tous les boutons avec les modales possible ---------------07-05-2025------------------------------------------- --}}
{{-- Tous les boutons avec les modales possible ---------------07-05-2025------------------------------------------- --}}


 {{-- Pour effectuer les recherches------------------------------------------------------------------------------------ --}}
 {{-- Pour effectuer les recherches------------------------------------------------------------------------------------ --}}
                            <br>
                        <div class="search-section" onclick="toggleSearch()"  style="text-align:center !important">
                            {{-- <h4 class="search-title"> Effectuer des recherches  </h4> --}}
                            <h4 class="search-title d-flex align-items-center">
                                    <i class="fas fa-search me-2 text-primary"></i> Effectuer des recherches
                                </h4>



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
                                    <strong>Résultat de recherche pour le destinataire : <b>{{ $_GET['destinataire_name'] }}, </b></strong> {{ count($pliClient) }} trouvé(s) !
                                    <i>
                                        <a href="{{ route('client.plis.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Raffraichir</a>
                                    </i>
                                </div>
                                @endif


                                @if(!empty($_GET['reference']))
                                <div class="alert alert-success mt-3">
                                    <strong>Résultat de recherche pour le numéro de reférence : <b>{{ $_GET['reference'] }}, </b></strong> {{ count($pliClient) }} trouvé(s) !
                                    <i>
                                        <a href="{{ route('client.plis.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Raffraichir</a>
                                    </i> <br> <span style="color:red">Svp cliquez sur " <strong>Voir</strong>  "  dans la colonne " <strong>référence</strong> " du tableau pour vérifier  la reférence </span>
                                </div>
                                @endif

                                {{-- /fin resultat des recherchers --}}
                          </div>

                            <div id="search-container" class="search-container" >
                                <!-- 🔎 Filtre par destinataire -->
                                        <form method="GET" action="{{ route('client.plis.index') }}">
                                            <div class="d-flex align-items-center">
                                                <select name="destinataire_name" class="form-select form-select-sm">
                                                    <option value="">-- Tous les destinataires --</option>
                                                    @foreach($destinataires as $destinataire)
                                                        <option value="{{ $destinataire->destinataire_name }}">
                                                            {{ $destinataire->destinataire_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
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
                                background-color: rgba(237, 247, 255, 0.3);
                                padding: 12px;
                                border-radius: 8px;
                                cursor: pointer;
                                transition: background 0.4s ease-in-out;
                            }

                            .search-section:hover {
                                background-color: rgba(238, 238, 238, 0.5);
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

                                {{-- ---------------------------------------------- --}}

{{-- Tableau pour afficher tous les plis non ramassés et non attribués parmis les plis 06-05-2025------------------------------ --}}
{{-- Tableau pour afficher tous les plis non ramassés et non attribués parmis les plis 06-05-2025------------------------------ --}}*

    <div class="table-responsive" style="max-height:500px; overflow-y: auto;" id="tb-print">

        <h5>Vos plis non ramassés ou nouvellement créés ({{ count($pliClient) }})  <i class="fas fa-chevron-down"></i> </h5>
        {{-- <button onclick="imprimerTableau()" class="btn-print">🖨️ Imprimer les plis </button> --}}
        <button onclick="window.print()" class="btn-print">📄 Fiche recap</button>

        <style>
            .btn-print {
                display: inline-block;
                padding: 2px 4px;
                font-size: 16px;
                font-weight: bold;
                color: #fff;
                background-color: #d1ad0f; /* Bleu vif */
                border: 2px solid #abb400; /* Bordure légèrement plus foncée */
                border-radius: 5px;
                cursor: pointer;
                transition: all 0.3s ease-in-out;
            }

            .btn-print:hover {
                background-color: #c5d700; /* Bleu foncé au survol */
                border-color: #c1d505;
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); /* Effet d'ombre */
            }
        </style>
{{-- -------------/-btn print------------------- --}}


        <table class="table table-striped table-bordered table-hover mt-3" id="table-p">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="text-nowrap-pr" style="white-space: nowrap;">#</th>
                    <th scope="col" class="text-nowrap-pr" style="white-space: nowrap;">Messages</th> <!-- Show to print-->
                    {{-- <th scope="col" class="text-nowrap-pr" style="white-space: nowrap;">Etat(Final)</th>  <!-- Show to print--> --}}
                    <th scope="col" class="text-nowrap hidden-column">Référence</th>
                    <th scope="col" class="text-nowrap">Actions</th>

                </tr>
            </thead>
            <tbody>

                @if(!empty($pliClient))
                @foreach ($pliClient as $pli1)
                <tr>
                    <td class="text-nowrap-pr hidden-column">{{ $loop->iteration }}</td>
                    <td class="text-nowrap-pr hidden-column">

                        <div class="alert ">
                        {{-- Pour afficher le message avec la date ----------------06-05-2025----------------------------- --}}
                     @if ($pli1->attributions->last())

                                @if ($pli1->attributions->last()->coursier_depot_id && $pli1->attributions->last()->coursier_ramassage_id)
                                    <div class="alert alert-primary">

        {{-- Cas 1/ lorsque le pli a été ramassé et en cours de depot ----------------- ---------------------------------------- --}}
                {{-- Cas 1/ lorsque le pli a été ramassé et en cours de depot ----------------- ---------------------------------------- --}}


                                🚀 Votre pli <strong>({{ $pli1->type }}) </strong> N° : <strong>{{ $pli1->code }}</strong> a été attribué à un coursier pour le dépot le <strong> {{ \Carbon\Carbon::parse($pli1->attributions->last()->date_attribution_depot)->format('d-m-Y H:i') }}  </strong>.
                                 Il est actuellement en transit vers son destinataire <strong>{{ $pli1->destinataire_name }} </strong> situé :    <strong>{{ $pli1->destinataire_adresse }}</strong> ! <br>

                                <strong>Statut Final :</strong>
                                <span class="status-badge">
                                    {{ $pli1->currentStatuer() ? $pli1->currentStatuer()->statuer->name : 'Statut non défini' }}
                                </span>

                                <strong>Date de mise à jour :</strong>
                                <span class="date-badge">
                                    {{ $pli1->currentStatuer() && $pli1->currentStatuer()->created_at
                                        ? \Carbon\Carbon::parse($pli1->currentStatuer()->created_at)->format('d-m-Y H:i')
                                        : 'Date inconnue' }}
                                </span>

                          {{-- demande de statut Afficher le lien si seulement le statut existe ------------------------------------ --}}
                                                @if($pli1->currentStatuer()&& $pli1->currentStatuer()->created_at)
                                                    <!-- Affiche le lien seulement si le statut existe -->
                                                    <br> <br>
                                                    <a href="mailto:webmaster@ivoirrapid.ci?subject=Demande%20d'accusé%20pour%20le%20pli%20N°%3A%20{{$pli1->code}}&body=Bonjour%20IVOIRRAPID,%0D%0A%0D%0A
                                                    Je%20souhaite%20obtenir%20l'accusé%20de%20livraison%20pour%20le%20pli%20N°%3A%20{{$pli1->code}}%20avec%20le%20statut%20{{$pli1->currentStatuer()->statuer->name}}.%0D%0A%0D%0A
                                                    Merci%20de%20me%20contacter%20pour%20plus%20d'informations%20:%20{{$pli1->user_Telephone}}" class="btn-confirmation">

                                                        📩 Demander l'accusé
                                                        {{-- {{ $pli1->currentStatuer() ? $pli1->currentStatuer()->statuer->name : 'Statut non défini' }} --}}
                                                    </a> Si  vous n'avez pas encore reçu

                                                    <style>
                                                        .btn-confirmation {
                                                            display: inline-block;
                                                            padding: 4px 8px;
                                                            font-size: 14px;
                                                            font-weight: bold;
                                                            color: #007BFF;
                                                            text-decoration: none;
                                                            border: 2px solid #007BFF;
                                                            background-color: transparent;
                                                            border-radius: 5px;
                                                            transition: all 0.3s ease-in-out;
                                                        }

                                                        .btn-confirmation:hover {
                                                            background-color: #007BFF;
                                                            color: white;
                                                        }
                                                    </style>

                                                @else
                                                    <p style="color: red;">Statut non défini</p>
                                                @endif
                         {{-- /fin ---------------------------------------------------------------------------------------- Pour le statut --}}
                            </div>
        {{-- / Cas 1/ lorsque le pli a été ramassé et en cours de depot ----------------- ---------------------------------------- --}}
                                {{--  /Cas 1/ lorsque le pli a été ramassé et en cours de depot ----------------- ---------------------------------------- --}}



   {{-- Cas 2/ lorsque le pli a été ramassé et en cours de depot ----------------- ---------------------------------------- --}}
                                    {{-- Cas 2/ lorsque le pli a été ramassé et en cours de depot ----------------- ---------------------------------------- --}}

                                    @elseif ($pli1->attributions->last()->coursier_ramassage_id)
                                        <div class="alert alert-success">
                                            ✅ Votre pli <strong>({{ $pli1->type }})</strong> N° : <strong>{{ $pli1->code }}, créé le : {{ $pli1->created_at }} </strong> a bien été ramassé le <strong>

                                                    {{-- {{ $pli->attributions->last()->date_attribution_ramassage ? $pli->attributions->last()->date_attribution_ramassage : 'Date indisponible' }}</strong> --}}

                                                    @if ($pli1->attributions->last()->date_attribution_ramassage)
                                                    <strong>
                                                        {{ \Carbon\Carbon::parse($pli1->attributions->last()->date_attribution_ramassage)->translatedFormat('l d F à H\hi s\s') }}
                                                            </strong>
                                                        @else
                                                            <strong>Date indisponible</strong>
                                                        @endif. Il est en cours de traitement pour livraison chez votre destinataire <strong> {{ $pli1->destinataire_name }} </strong> situé : <strong> {{ $pli1->destinataire_adresse }} </strong>.

                                                        {{-- Coté pour le statut et l'accusé --}}
                                                        <div class="status-container">

                                                            <strong>Statut Final :</strong>
                                                            <span class="status-badge">
                                                                {{ $pli1->currentStatuer() ? $pli1->currentStatuer()->statuer->name : 'Statut non défini' }}
                                                            </span>

                                                            <strong>Date de mise à jour :</strong>
                                                            <span class="date-badge">
                                                                {{ $pli1->currentStatuer() && $pli1->currentStatuer()->created_at
                                                                    ? \Carbon\Carbon::parse($pli1->currentStatuer()->created_at)->format('d-m-Y H:i')
                                                                    : 'Date inconnue' }}
                                                            </span>
                                                             {{-- Afficher le lien si seulement le statut existe ------------------------------------ --}}
                                            @if($pli1->currentStatuer())
                                            <!-- Affiche le lien seulement si le statut existe -->
                                                    <br> <br>
                                                    <a href="mailto:webmaster@ivoirrapid.ci?subject=Demande%20d'accusé%20pour%20le%20pli%20N°%3A%20{{$pli1->code}}&body=Bonjour%20IVOIRRAPID,%0D%0A%0D%0A
                                                    Je%20souhaite%20obtenir%20l'accusé%20de%20livraison%20pour%20le%20pli%20N°%3A%20{{$pli1->code}}%20avec%20le%20statut%20{{$pli1->currentStatuer()->statuer->name}}.%0D%0A%0D%0A
                                                    Merci%20de%20me%20contacter%20pour%20plus%20d'informations%20:%20{{$pli1->user_Telephone}}" class="btn-confirmation">

                                                        📩 Demander l'accusé
                                                        {{-- {{ $pli1->currentStatuer() ? $pli1->currentStatuer()->statuer->name : 'Statut non défini' }} --}}
                                                    </a> Si  vous n'avez pas encore reçu

                                                    <style>
                                                        .btn-confirmation {
                                                            display: inline-block;
                                                            padding: 4px 8px;
                                                            font-size: 14px;
                                                            font-weight: bold;
                                                            color: #383938;
                                                            text-decoration: none;
                                                            border: 2px solid #068d21;
                                                            background-color: transparent;
                                                            border-radius: 5px;
                                                            transition: all 0.3s ease-in-out;
                                                        }

                                                        .btn-confirmation:hover {
                                                            background-color: #06892d;
                                                            color: white;
                                                        }
                                                    </style>

                                                    @else
                                                        <p style="color: red;">Statut non défini</p>
                                                    @endif
                     {{-- /fin ---------------------------------------------------------------------------------------- Pour le statut --}}

         {{-- / Cas 2/ lorsque le pli a été ramassé et en cours de depot ----------------- ---------------------------------------- --}}
                  {{-- / Cas 2/ lorsque le pli a été ramassé et en cours de depot ----------------- ---------------------------------------- --}}
                                                        </div>
                                        </div>
                                    @endif

     {{--  Cas 3/ lorsque le pli a été ramassé et en cours de depot ----------------- ---------------------------------------- --}}
                 {{--  Cas 3/ lorsque le pli a été ramassé et en cours de depot ----------------- ---------------------------------------- --}}

                                    @else
                                        <div class="alert alert-warning">

                            ⏳ Votre pli <strong>({{ $pli1->type }})</strong> N° : <strong>{{ $pli1->code }}</strong>,
                            @if (\Carbon\Carbon::parse($pli1->created_at)->format('d-m-Y') == date('d-m-Y'))
                                créé aujourd'hui, le {{ \Carbon\Carbon::parse($pli1->created_at)->format('d-m-Y') }}</strong> à {{ \Carbon\Carbon::parse($pli1->created_at)->format('H:i') }}.
                            @else
                                créé le {{ \Carbon\Carbon::parse($pli1->created_at)->format('d-m-Y') }}.</strong> Il est en attente de ramassage.
                            @endif

                            Un coursier viendra bientôt pour le ramassage.
                            Il sera traité et, si aucun problème n’est détecté sur la facture, il sera transmis à votre destinataire <strong>{{ $pli1->destinataire_name }}</strong>, situé : <strong>{{ $pli1->destinataire_adresse }}</strong>, dans les prochaines heures.
                            <br>
                            <span>📞 **Contact du destinataire** : <strong>{{ $pli1->destinataire_contact }}</strong></span> <br>
                            <span>📦 **Nombre de pièces à envoyer** : <strong>{{ $pli1->nombre_de_pieces }}</strong></span> <br>
                            <span>📧 **Email du destinataire** : <strong>{{ $pli1->destinataire_email }}</strong></span>
                            <br> <i><strong><u>NB</u></strong> : Une fois les plis ramassés, ils ne seront plus modifiables ni supprimés.</i>.

                                             <div>
                                                <div> <br>
                                                    {{-- Pour le lien de mail --}}
                                                    <a href="mailto:webmaster@ivoirrapid.ci?subject=Pli%20urgent%20à%20livrer%20N°%3A%20{{$pli1->code}}%20--%20{{$pli1->user_name}}&body=Bonjour%20IVOIRRAPID,%0D%0A%0D%0A
                                                        Nous%20vous%20demandons%20de%20livrer%20ce%20pli%20urgemment%20pour%20des%20raisons%20précieuses.%0D%0A%0D%0A
                                                        Contactez-nous%20directement%20pour%20plus%20d’informations%3A%20{{$pli1->user_Telephone}}%20" class="urgent-btn" title="Confirmer l'envoi du mail " onclick="envoyerEmail()">
                                                            Marqué comme Urgent
                                                        </a>

                                                    <style>
                                                        .urgent-btn {
                                                            display: inline-block;
                                                            padding: 3px 6px;
                                                            font-size: 12px;
                                                            color: red;
                                                            text-decoration: none;
                                                            border: 2px solid red;
                                                            background-color: transparent;
                                                            border-radius: 5px;
                                                            transition: all 0.3s ease-in-out;
                                                        }

                                                        .urgent-btn:hover {
                                                            background-color: red;
                                                            color: white;
                                                        }
                                                    </style>

                                                </div>

                                                {{-- Pour le lien du mail --}}

                                             </div>

                                             <div class="status-container">
                                                <strong>Statut Final :</strong>
                                                <span class="status-badge">
                                                    {{ $pli1->currentStatuer() ? $pli1->currentStatuer()->statuer->name : 'Statut non défini' }}
                                                </span>
                                                <strong>Date de mise à jour :</strong>
                                                <span class="date-badge">
                                                    {{ $pli1->currentStatuer() && $pli1->currentStatuer()->created_at
                                                        ? \Carbon\Carbon::parse($pli1->currentStatuer()->created_at)->format('d-m-Y H:i')
                                                        : 'Date inconnue' }}
                                                </span>

                                                @if($pli1->currentStatuer())
                                                <!-- Affiche le lien seulement si le statut existe -->
                                                <br> <br>
                                                <a href="mailto:webmaster@ivoirrapid.ci?subject=Demande%20d'accusé%20pour%20le%20pli%20N°%3A%20{{$pli1->code}}&body=Bonjour%20IVOIRRAPID,%0D%0A%0D%0A
                                                Je%20souhaite%20obtenir%20l'accusé%20de%20livraison%20pour%20le%20pli%20N°%3A%20{{$pli1->code}}%20avec%20le%20statut%20{{$pli1->currentStatuer()->statuer->name}}.%0D%0A%0D%0A
                                                Merci%20de%20me%20contacter%20pour%20plus%20d'informations%20:%20{{$pli1->user_Telephone}}" class="btn-confirmation0">

                                                    📩 Demander l'accusé
                                                    {{-- {{ $pli1->currentStatuer() ? $pli1->currentStatuer()->statuer->name : 'Statut non défini' }} --}}
                                                </a> Si  vous n'avez pas encore reçu

                                                <style>
                                                    .btn-confirmation0 {
                                                        display: inline-block;
                                                        padding: 4px 8px;
                                                        font-size: 14px;
                                                        font-weight: bold;
                                                        color: #383938;
                                                        text-decoration: none;
                                                        border: 2px solid #d7d407;
                                                        background-color: transparent;
                                                        border-radius: 5px;
                                                        transition: all 0.3s ease-in-out;
                                                    }

                                                    .btn-confirmation0:hover {
                                                        background-color: #b4bd07;
                                                        color: white;
                                                    }
                                                </style>

                                            @else
                                                <p style="color: red;">Statut non défini</p>
                                            @endif


                                            </div>


                                        </div>
                                    @endif

     {{-- 3/ Cas 2/ lorsque le pli a été ramassé et en cours de depot ----------------- ---------------------------------------- --}}

                            {{-- / fin pour afficher les statut avec le ramassage------------------------- --}}
                        </div>

                    </td>
                    {{-- <td class="text-nowrap-pr" style="white-space: nowrap;">{{ $pli->code }}</td> <!-- Show to print--> --}}

                    {{-- <td class="text-nowrap-pr">{{ $pli->destinataire_name }}</td> --}}
                    <td class="text-nowrap">
                        <button class="btn btn-info btn-sm view-reference-btn" data-reference="{{ $pli1->reference }}" data-bs-toggle="modal" data-bs-target="#referenceModal">Voir</button>
                    </td>

               {{-- Debut action------------------------------------------- --}}
                                <td class="text-nowrap">


                                                @if ($pli1->attributions->isNotEmpty() && $pli1->attributions->contains('coursier_ramassage_id', '!=', null))

                                                       <div class="alert alert-warning d-flex align-items-center">
                                                                <i class="fas fa-exclamation-circle me-2"></i>
                                                                <strong>Ce pli a déjà été ramassé.</strong>
                                                            </div>
                                                            <a href="{{ route('client.plis.show', $pli1->id) }}" class="btn btn-primary btn-sm">
                                                                <i class="fas fa-eye"></i> Voir le détail
                                                            </a>

                                                    @else
                                                        <a href="{{ route('client.plis.show', $pli1->id) }}" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        <a href="{{ route('client.edit-pli', $pli1->id) }}"
                                                        class="btn
                                                        @if ($pli1->currentStatuer() && in_array($pli1->currentStatuer()->statuer->name, ['en attente', 'ramassé', 'déposé', 'annulé', 'retourné']))
                                                                btn-secondary disabled
                                                        @else
                                                                btn-warning btn-sm me-2
                                                        @endif">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <!-- Bouton delete -->
                                                        <a href="{{ route('plis.supprimer', $pli1->id) }}" class="btn btn-danger btn-sm delete-pli" >
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    @endif


                             </td>

                             {{-- @endif --}}
                             {{-- Nouvelle ajout pour gerer les erreurs --}}

                @endforeach
                @endif
            </tbody>
        </table>

    </div>

{{--/ fin Tableau pour afficher tous les plis non ramassés et non attribués parmis les plis 06-05-2025------------------------------ --}}
{{-- / Fin Tableau pour afficher tous les plis non ramassés et non attribués parmis les plis 06-05-2025------------------------------ --}}


{{-- Fin du tableau à gerer ------------------------------------------------------------------------------------------------ --}}


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
                                <h5 class="modal-title" id="referenceModalLabel" style="color:white !important">Références du pli</h5>
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


.status-container {
    margin-top: 10px;
    font-size: 16px;
}

.status-badge {
    padding: 5px 10px;
    font-weight: bold;
    border-radius: 5px;
    background-color: #f8f9fa;
    color: #333;
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


<script>
    // Pour afficher les donner dans le l'objet
    function genererDetailsPli(expediteur, destinataire, zoneExpediteur, zoneDestinataire, numeroSuivi) {
        // Créer une chaîne de caractères avec les détails du pli
        let detailsPli = `Expéditeur: ${expediteur}, Destinataire: ${destinataire}, Zone Expéditeur: ${zoneExpediteur}, Zone Destinataire: ${zoneDestinataire}, Numéro de suivi: ${numeroSuivi}`;

        // Afficher le résultat dans la console pour vérification
        console.log(detailsPli);

        // Mettre le texte dans un élément HTML (optionnel)
        document.getElementById('detailsPli').innerText = detailsPli;
    }
</script>

<!-- Ajout d'un conteneur pour afficher le texte -->
{{-- <div id="detailsPli"></div>

@foreach ($plis as $pli)
    <script>
        genererDetailsPli(
            "{{ $pli->user_name }}",
            "{{ $pli->destinataire_name }}",
            "{{ $pli->user_zone }}",
            "{{ $pli->destinataire_zone }}",
            "{{ $pli->reference }}"
        );
    </script>
@endforeach --}}




<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-pli').forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                let deleteUrl = this.getAttribute('href');

                Swal.fire({
                    title: "Êtes-vous sûr ?",
                    text: "Cette action est irréversible !",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Oui, Supprimer !",
                    cancelButtonText: "Annuler"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    });
</script>




@endsection







