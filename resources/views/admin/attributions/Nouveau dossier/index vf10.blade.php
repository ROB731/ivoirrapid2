
@extends('layouts.master')

@section('content')
    <div class="container">
        <h1>Gestion des Attributions de Plis</h1>
        <div class="card">
            <div class="card-header">
                <h4>
                    <a href="{{ route('admin.plis.index') }}" class="btn btn-primary btn-sm float-end">Retour</a>
                </h4>
            </div>
        </div>

        {{-- Recap des plis  --}}
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
                                    <h6 class="text-muted">Non attribués</h6>
                                    <p class="fw-bold fs-4">{{ $plisNonAttribues }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded">
                                    <h6 class="text-success" id="plis-ramasses">Attribués au ramassage</h6>
                                    <p class="fw-bold fs-4">{{ $plisRamassage  }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded">
                                    <h6 class="text-warning">Attribués au dépôt</h6>
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

 {{-- Barre de recherche --}}<input type="text" id="searchInput" class="form-control mb-3" placeholder=" Rechercher un pli...">


            {{-- Formulaire pour le remplissage dynamique --}}

                    <form id="groupAttributionForm" action="{{ route('admin.attributions.attribuer.groupe') }}" method="POST">
                        @csrf
                        <div class="table-responsive" style="height: 500px; overflow-y: auto; border: 1px solid #ddd;">

                                <table class="table table-hover table-bordered">
                                    <thead class="table-dark" style="position: sticky; top: 0; z-index: 100;">
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th>#</th>
                                            <th>Code</th>
                                            <th>Destinataire</th>
                                            <th>Expéditeur</th>
                                            <th>Zone Destinataire</th>
                                            <th>Zone Expéditeur</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pliTable">
                                        @foreach($plies as $pli)
                                            <tr>
                                                <td><input type="checkbox" name="plies[]" value="{{ $pli->id }} required"></td>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $pli->code }}</td>
                                                <td>{{ $pli->destinataire->name ?? 'N/A ou Destinataire supprimer' }}</td>
                                                <td>{{ $pli->user->name }}</td>
                                                <td>{{ $pli->destinataire->zone ?? 'N/A ou Zone Supprimé'}}</td>
                                                <td>{{ $pli->user->Zone ?? 'N/A ou Zone supprimé' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                        </div>

                    </div>

                        <!-- Sélection du type d'attribution -->
                        <select name="type" class="form-select my-3" required>
                            <option value="">Sélectionner un type d’attribution</option>
                            <option value="ramassage">Ramassage</option>
                            {{-- <option value="depot">Dépôt</option> --}}
                        </select>

                        {{-- Selection dur coursier --}}

                    {{-- Sélection des coursiers disponibles --}}
                    @if(!$coursiersDisponibles->isEmpty())
                    <div class="mb-3">
                        <label for="coursier_id">Sélectionner un coursier disponible :</label>
                        <select name="coursier_id" class="form-select" required>
                            <option value="">Sélectionner un coursier</option>
                            @foreach($coursiersDisponibles as $coursier)
                                <option value="{{ $coursier->id }}">
                                    {{ $coursier->nom }} {{ $coursier->prenoms }} - 📞 {{ $coursier->telephone }} - Zones: {{ implode(', ', $coursier->zones) }} - 📦 {{ $coursier->nombre_plis_attribues }} livraisons en attente
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <p class="alert alert-warning">Aucun coursier disponible, voici les alternatives :</p>

                    {{-- Sélection des coursiers alternatifs --}}
                    <div class="mb-3">
                        <label for="coursier_id_alternatif">Sélectionner un coursier alternatif :</label>
                        <select name="coursier_id" class="form-select">
                            <option value="">Sélectionner un coursier alternatif</option>
                            @foreach($coursiersAlternatifs as $coursier)
                                <option value="{{ $coursier->id }}">
                                    {{ $coursier->nom }} {{ $coursier->prenoms }} - 📞 {{ $coursier->telephone }} - Zones: {{ implode(', ', $coursier->zones) }} - 📦 {{ $coursier->nombre_plis_attribues }} livraisons en attente
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Bouton de soumission --}}
                <button type="submit" class="btn btn-primary">Valider cette attribution</button>

                        {{-- Seklection du coursier fin --}}

         </form>



         {{-- Pour l'attribution depot -----------------------------------------------------------------------------------}}



                        <!-- Modal d’attribution au dépôt -->
            <div class="modal fade" id="Tbdepot-modal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true" style="width:100%">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" style="width:100%">
                        <!-- En-tête du modal -->
                        <div class="modal-header bg-dark text-white">
                            <h5 class="modal-title" id="modalTitle">Attribuer les Plis au Dépôt</h5>
                            <button type="button" class="btn-close text-white" id="btn-close-attribution1" data-bs-dismiss="modal"></button>
                        </div>
                                    {{-- Barre de recherche --}}
                                <div class="mb-3">
                                    <input type="text" id="searchPlis" class="form-control" placeholder="Rechercher un pli...">
                                </div>


                        <!-- Corps du modal -->
                        <div class="modal-body">
                            <form id="groupAttributionForm" action="{{ route('admin.attributions.attribuer.groupe') }}" method="POST">
                                @csrf

                                <!-- Table des plis ramassés -->
                                <div class="table-responsive" style="height: 500px; overflow-y: auto; border: 1px solid #ddd; ">
                                    <table class="table table-hover table-bordered">
                                        <thead class="table-dark" style="position: sticky; top: 0; z-index: 100;">
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th>
                                                <th>#</th>
                                                <th>Code</th>
                                                <th>Destinataire</th>
                                                <th>Expéditeur</th>
                                                <th>Zone Destinataire</th>
                                                <th>Zone Expéditeur</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pliTable">

                                            @foreach($plisRamassageList as $pli2)
                                                <tr>
                                                    <td><input type="checkbox" name="plies[]" value="{{ $pli2->id }}"></td>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $pli2->code }}</td>
                                                    <td>{{ $pli2->destinataire->name ?? 'N/A ou Destinataire supprimé' }}</td>
                                                    <td>{{ $pli2->user->name }}</td>
                                                    <td>{{ $pli2->destinataire->zone ?? 'N/A ou Zone Supprimée' }}</td>
                                                    <td>{{ $pli2->user->Zone ?? 'N/A ou Zone supprimée' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Sélection du type d’attribution -->
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type d’attribution :</label>
                                    <select name="type" class="form-select" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="depot">Dépot</option>
                                    </select>
                                </div>

                                <!-- Sélection du coursier -->


                                        <p class="alert alert-warning">Aucun coursier disponible, voici les alternatives :</p>

                                        {{-- Sélection des coursiers alternatifs --}}
                                        <div class="mb-3">
                                            <label for="coursier_id_alternatif">Sélectionner un coursier alternatif :</label>
                                            <select name="coursier_idAt" class="form-select">
                                                <option value="">Sélectionner un coursier alternatif</option>

                                                @foreach($coursiersAlternatifs as $coursierAt)
                                                    <option value="{{ $coursierAt->id }}">
                                                        {{  $coursierAt->nom }} {{  $coursierAt->prenoms }} - 📞 {{  $coursierAt->telephone }} - Zones: {{ implode(', ',  $coursierAt->zones) }} - 📦 {{  $coursierAt->nombre_plis_attribues }} livraisons en attente
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>



                                <!-- Bouton de validation -->
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Valider l’attribution</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // pour l'attribution ramassage
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

            {{-- Cript pout l'attribution depot en bas --}}

                    <style>


                    </style>

                    <script> //pOUR LES RECHERCHES depot
                    document.addEventListener("DOMContentLoaded", function () {
                        let searchInput = document.getElementById("searchPlis");

                        searchInput.addEventListener("keyup", function () {
                            let filter = searchInput.value.toLowerCase();
                            let rows = document.querySelectorAll("#pliTable tr");

                            rows.forEach(row => {
                                let text = row.textContent.toLowerCase();
                                row.style.display = text.includes(filter) ? "" : "none";
                            });
                        });
                    });

                    </script>
                    <script> // Pour le boutoon coché  depot
                            document.addEventListener("DOMContentLoaded", function () {
                        let selectAllCheckbox = document.getElementById("selectAll");
                        let checkboxes = document.querySelectorAll("#pliTable input[type='checkbox']");

                        // Clic sur le bouton "Tout sélectionner"
                        selectAllCheckbox.addEventListener("change", function () {
                            checkboxes.forEach(checkbox => {
                                checkbox.checked = selectAllCheckbox.checked;
                            });
                        });
                    });

                    </script>



         {{-- fin attribution depot ------------------------------------------------------------------------------}}


                    <script>
                        document.getElementById('groupAttributionForm').addEventListener('submit', function(event) {
                            let checkboxes = document.querySelectorAll('input[name="plies[]"]:checked');

                            if (checkboxes.length === 0) {
                                event.preventDefault(); // Empêche l'envoi du formulaire
                                alert("🚨 Veuillez sélectionner au moins un pli avant de valider !");
                            }
                        });
                    </script>


                    <script>
                        document.getElementById('selectAll').addEventListener('change', function() {
                            let isChecked = this.checked;
                            let checkboxes = document.querySelectorAll('#pliTable input[type="checkbox"]');

                            checkboxes.forEach(checkbox => {
                                checkbox.checked = isChecked;
                            });
                        });
                    </script>
                        <script>

                    document.getElementById('searchInput').addEventListener('keyup', function() {
                            let filter = this.value.toLowerCase();
                            let rows = document.querySelectorAll('#pliTable tr');

                            // Si la barre est vide, restaurer le tableau
                            if (filter === "") {
                                rows.forEach(row => {
                                    row.style.display = '';
                                    row.querySelectorAll('td').forEach(td => {
                                        td.innerHTML = td.innerText;
                                    });
                                });
                                return;
                            }

                            rows.forEach(row => {
                                let text = row.innerText.toLowerCase();
                                let containsText = text.includes(filter);

                                // Affichage des lignes filtrées
                                row.style.display = containsText ? '' : 'none';

                                // Supprimer les anciennes surbrillances
                                row.querySelectorAll('td:not(:first-child)').forEach(td => {
                                    td.innerHTML = td.innerText;
                                });

                                // Mise en surbrillance du texte trouvé, sauf case à cocher
                                if (containsText) {
                                    row.querySelectorAll('td:not(:first-child)').forEach(td => {
                                        td.innerHTML = td.innerText.replace(new RegExp(filter, "gi"), match => `<mark>${match}</mark>`);
                                    });
                                }
                            });
                        });

                    </script>

                {{--End  Formulaire pour le remplissage dynamique  et ses elements--}}

    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#pliTable tr');

            rows.forEach(row => {
                let code = row.querySelector('.pli-code').textContent.toLowerCase();
                if (code.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>



@endsection
