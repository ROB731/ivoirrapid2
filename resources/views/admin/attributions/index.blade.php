
@include('admin.attributions.child_index')


@extends('layouts.master')

@section('content')



    <div class="container">
        <h2>Gestion des Attributions de Plis (Ramassage & Dépot)</h2>
        <div class="card">
            <div class="card-header">
                <h4>
                    <a href="{{ route('admin.plis.index') }}" class="btn btn-primary btn-sm float-end">Retour</a>
                </h4>
            </div>
        </div>


    {{-- Div et modal pour les fiches de mission le 02-05-2025---------------------------------------------- --}}
        <div class="stats-container" style="font:small; font-style:italic">
            <h5>Attribution du Jour ({{ now()->format('d/m/Y') }})</h5>

            <p>🚀 Coursiers concernés par le Ramassage : <strong>{{ $nombreCoursiersRamassage }}</strong>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalRamassage">
                    📄 Voir les fiches de mission
                </button>
            </p>

            <p>📦 Coursiers concernés par le Dépôt : <strong>{{ $nombreCoursiersDepot }}</strong>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalDepot">
                    📄 Voir les fiches de mission
                </button>
            </p>

        </div>
        {{-- Fin de mise à jour du 02 - 05 -2025 -----------------------------------------------------------------------------}}
 -


        <div class="card text-center shadow-lg">
            {{-- <div class="card-header bg-primary text-white">
                <h4>Statistiques des Plis</h4>
            </div> --}}
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="text-center mb-3">📦 Statistiques des Plis</h4>
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded">
                                    <h6 class="text-muted">Non ramassés(  <i>Aucun coursier n'a confirmé avoir ramassé ces plis</i> )</h6>
                                    <p class="fw-bold fs-4">{{ $plisNonAttribues }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded">
                                    <h6 class="text-success" id="plis-ramasses">Ramassés ( <i> <small>En attente d'un coursier pour le dépot</small> </i> )</h6>
                                    <p class="fw-bold fs-4">{{ $plisRamassage  }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded">
                                    <h6 class="text-warning">Attribués au dépôt ( <i> <small> Tous ces plis on été attribué aux cousiers correspondant pour dépot </small> </i> )</h6>
                                    <p class="fw-bold fs-4">{{  $plisDepot}}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded">
                                    <h6 class="text-primary">Plis traités</h6>
                                    <p class="fw-bold fs-4">{{ $plisTraites }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <br>

        {{-- Fin recap pli --}}
                      <!-- Messages de notification -->
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <!--  End Messages de notification -->

{{-- Pour la selection mmultiple dutableau en bas --}}
    <input type="text" id="searchPlis" placeholder="Rechercher un pli...non attribué" class="form-control mb-3">
            {{-- Formulaire pour le remplissage dynamique ramassage --}}

                    {{-- <form id="groupAttributionForm" action="{{ route('admin.attributions.attribuer.groupe') }}" method="POST"> --}}
                        <form id="groupAttributionForm" action="{{ route('attribution.process') }}" method="POST">

                        @csrf

                        <style>
                            .pli-container,.pli-container2 {
                                width: 98%;
                               /* max-width: 800px; */
                                margin: auto;
                            }
                            .pli-header2,.pli-header, .pli-row,.pli-row2 {
                                display: flex;
                                align-items: center;
                                padding: 10px;
                                border-bottom: 1px solid #ddd;
                            }
                            .pli-header,.pli-header2 {
                                font-weight: bold;
                                background: #333;
                                color: #fff;
                            }
                            .pli-list,.pli-list2 {
                                max-height: 400px; /*  Limite la hauteur pour éviter une liste trop longue */
                                overflow-y: auto; /*  Active le scroll interne */
                                border: 1px solid #ddd;
                                max-height: 400px; /*  Fixe une hauteur maximale */
                                min-height: 200px; /* 🛠 Empêche le tableau de disparaître complètement */
                                overflow-y: auto; /*  Active le scroll interne */
                                border: 1px solid #ddd;
                            }
                            .pli-row,.pli-row2 {
                                cursor: pointer;
                                background: #f8f9fa;
                                transition: background 0.2s;
                            }
                            .pli-row2,.pli-row:hover {
                                background: #e2e6ea; /*  Effet de survol */
                            }
                            .pli-checkbox2,.pli-checkbox {
                                margin-right: 10px;
                            }
                            .pli-number,.pli-number2 {
                                width: 40px;
                                text-align: center;
                            }
                            .pli-info,.pli-info2,.pli-info-code {
                                flex-grow: 1;
                                text-align: left;
                                width:12%;
                                max-width: 256px;
                                font-size:13px;
                            }

                            .pli-info-code{
                                text-align: left;
                            }

                            span{
                                /* border-right: 1px solid black; */
                                justify-content: justify;
                            }
                        </style>

                        {{-- Pour le ramassage  --}}

                        <div style="text-align:left">
                            <input id="selectAllPlis"   type="checkbox"/>
                                <label for="selectAllPlis"> Tout cocher/décocher"</label>
                        </div>

                        <div class="pli-container">
                            <!-- En-tête du tableau -->
                            <div class="pli-header">
                                <span style="width: 40px;">✔</span>
                                {{-- <span style="width: 40px;">#</span> --}}
                                <span class="pli-info">Code</span>
                                <span class="pli-info">Destinataire</span>
                                <span class="pli-info">Expéditeur</span>
                                <span class="pli-info">Zone Destinataire</span>
                                <span class="pli-info">Zone Expéditeur</span>

                            </div>

                            <!-- Corps du tableau avec scroll -->
                            <div class="pli-list">
                                    @if(!empty(count($plies))==0)
                                        <br>
                                        <br>
                                        <br>
                                            <h5 style="text-align:center">Aucun plis disponible pour pour l'instant <span> &#x1F607;</span> <span> &#x1F60E;</span></h5>
                                    @else
                                            @foreach($plies as $pli)
                                            <label for="pli{{ $pli->id }}" class="pli-row">
                                                <input type="checkbox" name="plies[]" id="pli{{ $pli->id }}" value="{{ $pli->id }}" class="pli-checkbox">
                                                <span class="pli-number">{{ $loop->iteration }}</span>
                                                <span class="pli-info-code">{{ $pli->code }}</span>
                                                <span class="pli-info">{{ $pli->destinataire->name ?? 'N/A' }}  </span>
                                                <span class="pli-info">{{ $pli->user->name }}</span>
                                                <span class="pli-info">{{ $pli->destinataire->zone ?? 'N/A' }}</span>
                                                <span class="pli-info"> {{ $pli->user->Zone }} </span>
                                            </label>
                                            @endforeach
                                    @endif
                            </div>
                        </div>
                    </div>

                        <!-- Sélection du type d'attribution -->
                        <select name="type" class="form-select my-3" required>
                            <option value="ramassage">Ramassage</option>
                            {{-- <option value="depot">Dépôt</option> --}}
                        </select>

                        <p class="alert alert-danger">Si aucun coursier disponible pour certains, voici les alternatives pour le ramassage :</p>
                        {{-- Sélection des coursiers alternatifs --}}
                        <div class="mb-3 bg-warning">
                            <label for="coursier_id_alternatif">Sélectionner un coursier  alternatif pour le ramassage :</label>
                            <select name="coursier_id1" class="form-select">
                                <option value="">Sélectionner un coursier alternatif pour le ramassage</option>
                                @foreach($coursiersAlternatifs as $coursier)
                                    <option value="{{ $coursier->id }}">
                                        {{ $coursier->nom }} {{ $coursier->prenoms }} - 📞 {{ $coursier->telephone }} - Zones: {{ implode(', ', $coursier->zones) }} - 📦 {{ $coursier->nombre_plis_attribues }} livraisons en attente
                                    </option>
                                @endforeach
                            </select>
                        </div>


                {{-- Bouton de soumission --}}
                <button type="submit" class="btn btn-primary">Valider le ramassage</button>

                        {{-- Seklection du coursier fin --}}
         </form>

         {{-- Fin du formulaire ramassé --}}

         {{-- Pour l'attribution depot -----------------------------------------------------------------------------------}}

             <!-- Modal d’attribution au dépôt -->
            <div class="modal fade" id="Tbdepot-modal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true" style="width:100%">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content" style="width:100%">
                        <!-- En-tête du modal -->
                        <div class="modal-header bg-dark text-white">
                            <h5 class="modal-title" id="modalTitle">Attribuer les plis au coursier pour le depot chez le destinataire</h5>
                            <button type="button" class="btn-close text-white" id="btn-close-attribution1" data-bs-dismiss="modal"></button>
                        </div>
                        <input type="text" id="searchPlis2" placeholder="Rechercher un pli...non attribué" class="form-control mb-3">
                        <!-- Corps du modal -->
                        <div class="modal-body">
                            <form id="groupAttributionForm" action="{{ route('attribution.process') }}" method="POST">
                                @csrf
                                    {{-- Workin depot --}}
                                    <div style="text-align:left">
                                            <input id="selectAllPlis2"   type="checkbox"/>
                                            <label for="selectAllPlis2"> Tout cocher/décocher"</label>
                                    </div>

                                    <div class="pli-container2">
                                        <!-- En-tête du tableau -->
                                        <div class="pli-header2">
                                            <span style="width: 40px;">✔ </span>
                                            {{-- <span style="width: 40px;">#</span> --}}
                                            <span class="pli-info2">Code</span>
                                            <span class="pli-info2">Destinataire</span>
                                            <span class="pli-info2">Expéditeur</span>
                                            <span class="pli-info2">Zone Destinataire</span>
                                            <span class="pli-info2">Zone Expéditeur</span>
                                        </div>
                                        <!-- Corps du tableau avec scroll -->
                                        <div class="pli-list2">
                                                @if(!empty(count($plisRamassageList))==0)
                                                    <br>
                                                    <br>
                                                    <br>
                                                        <h5 style="text-align:center">Aucun plis disponible pour pour l'instant <span> &#x1F607;</span> <span> &#x1F60E;</span></h5>
                                                @else
                                                        @foreach($plisRamassageList as $pli2)
                                                        <label for="pli{{ $pli2->id }}" class="pli-row2">
                                                            <input type="checkbox" name="plies[]" id="pli2{{ $pli2->id }}" value="{{ $pli2->id }}" class="pli-checkbox2">
                                                            <span class="pli-number2">{{ $loop->iteration }} </span>
                                                            <span class="pli-info2">#{{ $pli2->code }}</span>
                                                            <span class="pli-info2">{{ $pli2->destinataire->name ?? 'N/A' }}</span>
                                                            <span class="pli-info2">{{ $pli2->user->name }}</span>
                                                            <span class="pli-info2">{{ $pli2->destinataire->zone ?? 'N/A' }}</span>
                                                            <span class="pli-info2"> {{ $pli2->user->Zone }} </span>
                                                        </label>
                                                        @endforeach
                                                @endif
                                        </div>
                                    </div>

                                </div>

                                <!-- Sélection du type d’attribution -->
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type d’attribution :</label>
                                    <select name="type" class="form-select" required>
                                        <option value="depot">Dépot</option>
                                    </select>
                                </div>

                                <!-- Sélection du coursier -->

                                        <p class="alert alert-danger">Si aucun coursier disponible pour certains, voici les alternatives pour le depot :</p>
                                        {{-- Sélection des coursiers alternatifs --}}
                                        <div class="mb-3 bg-warning">
                                            <label for="coursier_id_alternatif">Sélectionner un coursier  alternatif pour le dépot :</label>
                                            <select name="coursier_id1" class="form-select">
                                                <option value="">Sélectionner un coursier alternatif pour le dépot</option>
                                                @foreach($coursiersAlternatifs as $coursier)
                                                    <option value="{{ $coursier->id }}">
                                                        {{ $coursier->nom }} {{ $coursier->prenoms }} - 📞 {{ $coursier->telephone }} - Zones: {{ implode(', ', $coursier->zones) }} - 📦 {{ $coursier->nombre_plis_attribues }} livraisons en attente
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                <!-- Bouton de validation -->
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Valider l’attribution</button>
                                </div>
                                <br>
                            </form>

                            {{-- Fin formulaire depot --}}
                        </div>
                    </div>
                </div>
            </div>

            {{-- fin du modal --}}

            <script> // Est fonctionnel-------------------------------------------------------------------------------------------------------------------------

                // pour le ramassage
                            document.addEventListener("DOMContentLoaded", function () {
                // Récupérer les éléments
                let modal = new bootstrap.Modal(document.getElementById("Tbdepot-modal"));
                let btnClose = document.getElementById("btn-close-attribution1");

                // Afficher le modal au clic sur "plis-ramasses"
                document.getElementById("plis-ramasses").addEventListener("click", function () {
                    modal.show();
                });

                // Fermer le modal au clic sur le bouton "X"
                btnClose.addEventListener("click", function () {
                    modal.hide();
                });
            });

            </script>

        <script>
            // Pour la recherche dans non attribué
            document.addEventListener("DOMContentLoaded", function () {
            let searchInput = document.getElementById("searchPlis");
            let rows = document.querySelectorAll(".pli-list .pli-row");
            let pliList = document.querySelector(".pli-list");

            searchInput.addEventListener("keyup", function () {
                let filter = searchInput.value.toLowerCase().trim();
                let visibleRows = 0; //  Compteur des lignes visibles

                rows.forEach(row => {
                    let text = row.innerText.toLowerCase();
                    let match = text.includes(filter);

                    row.style.display = match ? "" : "none";

                    if (match) visibleRows++; //  Compte les lignes affichées
                });

                //  Ajustement : éviter que le tableau disparaisse complètement
                pliList.style.minHeight = visibleRows > 0 ? "200px" : "auto";
            });
        });


                document.addEventListener("DOMContentLoaded", function () {
            let searchInput = document.getElementById("searchPlis");
            let selectAllButton = document.getElementById("selectAllPlis");
            let rows = document.querySelectorAll(".pli-list .pli-row");

            //  Filtrage dynamique de la recherche
            searchInput.addEventListener("keyup", function () {
                let filter = searchInput.value.toLowerCase().trim();
                rows.forEach(row => {
                    let text = row.innerText.toLowerCase();
                    row.style.display = text.includes(filter) ? "" : "none";
                });
            });

            //  Sélectionne uniquement les cases visibles après la recherche
            selectAllButton.addEventListener("click", function () {
                let visibleCheckboxes = document.querySelectorAll(".pli-list .pli-row:not([style*='display: none']) input[type='checkbox']");
                let allVisibleChecked = Array.from(visibleCheckboxes).every(checkbox => checkbox.checked);

                visibleCheckboxes.forEach(checkbox => {
                    checkbox.checked = !allVisibleChecked;
                });
            });
        });

        </script>

        {{-- Pour le depot ------------------------------------------------------------------------------------------------------------ --}}

        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                let pliContainer2 = document.querySelector(".pli-container2");
                let checkboxes2 = pliContainer2.querySelectorAll(".pli-checkbox2");
                let selectAllButton2 = document.getElementById("selectAllPlis2");
                let searchInput2 = document.getElementById("searchPlis2");

                // 🔎 Barre de recherche dynamique
                searchInput2.addEventListener("keyup", function () {
                    let filter = searchInput2.value.toLowerCase().trim();
                    pliContainer2.querySelectorAll(".pli-row2").forEach(row => {
                        let match = row.innerText.toLowerCase().includes(filter);
                        row.style.display = match ? "" : "none";
                    });
                });

                //  Sélection automatique au clic sur une ligne
                pliContainer2.querySelectorAll(".pli-row2").forEach(row => {
                    row.addEventListener("click", function (event) {
                        if (!event.target.classList.contains("pli-checkbox2")) {
                            let checkbox2 = row.querySelector(".pli-checkbox2");
                            checkbox2.checked = !checkbox2.checked;
                        }
                    });
                });

                //  Bouton "Tout cocher/décocher"
                selectAllButton2.addEventListener("click", function () {
                    let allChecked2 = Array.from(checkboxes2).every(checkbox => checkbox.checked);
                    checkboxes2.forEach(checkbox => checkbox.checked = !allChecked2);
                });
            });

        </script>

        {{-- Nouveau modal pour l'affiche simple --}}







@endsection
