
@extends('layout.master')

@section('title', 'IvoirRp - Ajouter un Pli')

@section('content')
@if($message = Session::get('message'))
    <div class="alert alert-success alert-dismissible fade show">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <strong>{{ $message }}</strong>
    </div>
@endif
<div class="container">
    <h2>Création de Plis</h2>
    <hr>
    <br>
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center">
                <!-- 🔥 Informations du créateur -->
                <h4></h4>
                <!-- 🔄 Bouton de rechargement aligné à droite -->
                <button class="btn btn-outline-primary rounded-circle p-2" onclick="reloadPage()" title="Recharger la page">
                    <i class="fas fa-sync-alt fa-lg"></i>
                </button>
            </div>


            <form action="{{ url('client/add-pli') }}" method="POST" class="mt-5 bg-light p-5 rounded shadow" enctype="multipart/form-data" id="pliForm">
                @csrf

                <div class="d-none">
                    <!-- Informations de l'utilisateur -->

                    {{-- Nouvelle mise à jour  --}}
                         <h2>Création de pli</h2>
                    {{-- Nouvelle mise à jour --}}

                    <h4>Informations du créateur</h4>

                    <div class="mb-3">
                        <label for="user_id" class="form-label">Nom de l'utilisateur</label>
                        <input type="text" class="form-control bg-secondary text-white" id="user_id" name="user_id" value="{{ auth()->user()->id }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="user_name" class="form-label">Nom de l'utilisateur</label>
                        <input type="text" class="form-control bg-secondary text-white" id="user_name" name="user_name" value="{{ auth()->user()->name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="user_email" class="form-label">Email de l'utilisateur</label>
                        <input type="email" class="form-control bg-secondary text-white" id="user_email" name="user_email" value="{{ auth()->user()->email }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="user_Telephone" class="form-label">Téléphone de l'utilisateur</label>
                        <input type="text" class="form-control bg-secondary text-white" id="user_Telephone" name="user_Telephone" value="{{ auth()->user()->Telephone }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="user_Adresse" class="form-label">Adresse de l'utilisateur</label>
                        <input type="text" class="form-control bg-secondary text-white" id="user_Adresse" name="user_Adresse" value="{{ auth()->user()->Adresse }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="user_Zone" class="form-label">Zone de l'utilisateur</label>
                        <input type="text" class="form-control bg-secondary text-white" id="user_Zone" name="user_Zone" value="{{ auth()->user()->Zone }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="user_Cellulaire" class="form-label">Numéro de cellulaire</label>
                        <input type="text" class="form-control bg-secondary text-white" id="user_Cellulaire" name="user_Cellulaire" value="{{ auth()->user()->Cellulaire }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="user_Autre" class="form-label">Autres informations</label>
                        <textarea class="form-control bg-secondary text-white" id="user_Autre" name="user_Autre" rows="3" readonly>{{ auth()->user()->Autre }}</textarea>
                    </div>
                </div>


                <!-- Sélection du destinataire -->
                <h4>Informations du destinataire</h4>
                {{-- Debut pour le destinatatire --}}
                <!--  Zone de texte visible pour la recherche -->
                <input type="text" id="searchDestinataire" name="searchDestinataire" class="form-control mb-2" placeholder="Tapez un nom..." autocomplete="off">
                <ul id="destinataireList" class="list-group mt-2" style="max-height: 200px; overflow-y: auto; display: none; position: absolute; z-index: 1000;"></ul>

                <div id="alert-message" class="alert alert-warning mt-2 d-none">
                    ❗ Aucun destinataire trouvé. Voulez-vous en ajouter un nouveau ?

                    <a href="{{ url('client/add-destinataire') }}" class="btn btn-primary btn-sm ms-2">
                        <i class="fas fa-user-plus"></i> Ajouter un destinataire
                    </a>
                </div>

                {{-- / Nouvelle mise à jour --------------------------------}}
                <div class="mb-3">
                    <label for="destinataire_zone">Zone et Adresse :</label>
                    <div class="d-flex gap-2">
                        <input type="text" id="destinataire_zoneShow" class="form-control form-control-sm" placeholder="Zone" disabled>
                        <input type="text" id="destinataire_adresseShow" class="form-control form-control-sm" placeholder="Adresse" disabled>
                    </div>
                </div>
                {{-- / Nouvelle mise à jour -------------------------------------}}

                <!--  Champs cachés pour stocker les informations du destinataire sélectionné -->
                <input type="hidden" id="destinataire_id" name="destinataire_id">
                <input type="hidden" id="destinataire_name" name="destinataire_name"> <!--  Correction du champ caché -->
                <input type="hidden" id="destinataire_adresse" name="destinataire_adresse">
                <input type="hidden" id="destinataire_telephone" name="destinataire_telephone">
                <input type="hidden" id="destinataire_email" name="destinataire_email">
                <input type="hidden" id="destinataire_zone" name="destinataire_zone">
                <input type="hidden" id="destinataire_contact" name="destinataire_contact">
                <input type="hidden" id="destinataire_autre" name="destinataire_autre">
                

            <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const searchInput = document.getElementById('searchDestinataire');
                        const destinataireList = document.getElementById('destinataireList');
                        const alertMessage = document.getElementById('alert-message');

                        searchInput.addEventListener('keyup', function () {
                            let searchTerm = searchInput.value.trim().toLowerCase();

                            if (searchTerm.length < 2) {
                                destinataireList.style.display = "none";
                                alertMessage.classList.add("d-none");
                                return;
                            }

                            fetch(`/get-destinataires?query=${searchTerm}`)
                                .then(response => response.json())
                                .then(destinataires => {
                                    destinataireList.innerHTML = ""; // 🔄 Réinitialisation de la liste

                                    if (destinataires.length > 0) {
                                        destinataireList.style.display = "block";
                                        alertMessage.classList.add("d-none");

                                        let uniqueZones = new Map(); // 🔹 Stocke les zones uniques avec un seul élément

                                        destinataires.forEach(destinataire => {
                                            let key = destinataire.zone ? destinataire.zone.trim().toLowerCase() : ""; // 🔍 Clé basée sur la zone

                                            if (!uniqueZones.has(key)) { // ✅ Vérifie si la zone est déjà ajoutée
                                                uniqueZones.set(key, destinataire);
                                            }
                                        });

                                        // 🔹 Affichage des 5 premiers résultats uniques par zone
                                        Array.from(uniqueZones.values()).slice(0, 5).forEach(destinataire => {
                                            let li = document.createElement("li");
                                            li.classList.add("list-group-item");
                                            li.textContent = `${destinataire.name} - ${destinataire.adresse} (${destinataire.zone})`;

                                            li.onclick = () => {
                                                console.log("✅ Destinataire sélectionné :", destinataire);

                                                document.getElementById('searchDestinataire').value = destinataire.name;
                                                document.getElementById('destinataire_name').value = destinataire.name;
                                                document.getElementById('destinataire_id').value = destinataire.id;
                                                document.getElementById('destinataire_adresse').value = destinataire.adresse || '';
                                                document.getElementById('destinataire_telephone').value = destinataire.telephone || '';
                                                document.getElementById('destinataire_email').value = destinataire.email || '';
                                                document.getElementById('destinataire_zone').value = destinataire.zone || '';
                                                document.getElementById('destinataire_contact').value = destinataire.contact || '';
                                                document.getElementById('destinataire_autre').value = destinataire.autre || '';
                                                document.getElementById('destinataire_adresseShow').value = destinataire.adresse || '';
                                                document.getElementById('destinataire_zoneShow').value = destinataire.zone || '';

                                                destinataireList.style.display = "none";
                                            };

                                            destinataireList.appendChild(li);
                                        });
                                    } else {
                                        destinataireList.style.display = "none";
                                        alertMessage.classList.remove("d-none");
                                    }
                                })
                                .catch(error => {
                                    console.error("❌ Erreur lors de la récupération des destinataires :", error);
                                    alertMessage.classList.remove("d-none");
                                    destinataireList.style.display = "none";
                                });
                        });
                    });
            </script>






                {{-- Fin pour le destinataire  --}}
                <!-- Informations sur le colis -->
                <h4>Informations du pli</h4>
                <div class="mb-3">
                    <label for="type" class="form-label">Type de pli<span class="text-danger">*</span></label>
                    <select class="form-control bg-primary text-white @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="" disabled selected>Choisir un type</option>
                        <option value="FACTURE">FACTURE</option>
                        <option value="AVOIR">AVOIR</option>
                          <option value="CHEQUE">CHEQUE</option>
                        <option value="PRO-FORMAT">PRO-FORMAT</option>
                        <option value="BON DE LIVRAISON">BON DE LIVRAISON</option>
                        <option value="BON DE COMMANDE">BON DE COMMANDE</option>
                        <option value="COURRIER ADMINISTRATIF">COURRIER ADMINISTRATIF</option>
                        <option value="PIECE MECANIQUE">PIECE MECANIQUE</option>
                        <option value="PIECE ELECTRONIQUE">PIECE ELECTRONIQUE</option>
                        <option value="PIECE DE MEUBLE">ALIMENTAIRE HUMAIN</option>
                        <option value="PIECE DE MONTAGE">ALIMENTAIRE ANIMAL</option>
                        <option value="PIECE DE REPARATION">VETEMENT</option>
                        <option value="PIECE DE REVISION">CHAUSSURE</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                 <div class="mb-3">
                    <label for="nombre_de_pieces" class="form-label">Nombre de pièces<span class="text-danger">*</span></label>
                    <input type="number" class="form-control bg-primary text-white @error('nombre_de_pieces') is-invalid @enderror" id="nombre_de_pieces" name="nombre_de_pieces" required>
                    @error('nombre_de_pieces')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div id="references-container">
                    <!-- Les champs de référence seront ajoutés ici -->
                </div>

                <button type="submit" class="btn btn-primary" style="margin-top: 10px; margin-left: 255px">Enregistrer</button>
            </form>
        </div>
    </div>
</div>

<script>

    function fillDestinataireInfo() {
        const select = document.getElementById('destinataire_id');
        const option = select.options[select.selectedIndex];

        document.getElementById('destinataire_name').value = option.getAttribute('data-name') || '';
        document.getElementById('destinataire_adresse').value = option.getAttribute('data-adresse') || '';
        document.getElementById('destinataire_telephone').value = option.getAttribute('data-telephone') || '';
        document.getElementById('destinataire_email').value = option.getAttribute('data-email') || '';
        document.getElementById('destinataire_zone').value = option.getAttribute('data-zone') || '';
        document.getElementById('destinataire_contact').value = option.getAttribute('data-contact') || '';
        document.getElementById('destinataire_autre').value = option.getAttribute('data-autre') || '';
    }

    document.getElementById('nombre_de_pieces').addEventListener('input', function () {
        const container = document.getElementById('references-container');
        const nombreDePieces = this.value; // Récupérer la valeur du champ nombre_de_pieces
        const alertMessage = document.getElementById('alert-message'); // Div d'alerte

        // Réinitialiser les champs de références et le message d'alerte
        container.innerHTML = '';
        if (alertMessage) alertMessage.remove();

        // Vérifier si le nombre dépasse 10
        if (nombreDePieces > 10) {
            // Ajouter un message d'avertissement
            const alertDiv = document.createElement('div');
            alertDiv.id = 'alert-message';
            alertDiv.className = 'alert alert-warning mt-3';
            alertDiv.textContent = 'Le nombre de références ne peut pas dépasser 10.';
            container.parentElement.insertBefore(alertDiv, container);

            // Fixer la valeur du champ à 10
            this.value = 10;
            return;
        }

        // Générer les champs pour les références
        for (let i = 0; i < nombreDePieces; i++) {
            const div = document.createElement('div');
            div.className = 'form-group';
            div.innerHTML = `
                <label for="reference_${i}">Référence ${i + 1}</label>
                <input type="text" name="reference[]" id="reference_${i}" class="form-control" placeholder="Entrez la référence" required>
            `;
            container.appendChild(div);
        }
    });
</script>



<script> // Rafraichir la page
    function reloadPage() {
        location.reload(); // 🔄 Rafraîchit la page
    }
    </script>


@endsection
