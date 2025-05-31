@extends('layout.master')

@section('title', 'IvoirRp - Modifier un Pli')

@section('content')
        @if($message = Session::get('message'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        <h2>Edité un pli</h2>
        <hr>
            @if(session('error'))
                <div class="alert alert-danger">
                    {!! session('error') !!}
                </div>
            @endif

            <div class="container">
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

            <form action="{{ url('client/update-pli/'.$pli->id) }}" method="POST" class="mt-5 bg-light p-5 rounded shadow" enctype="multipart/form-data" id="pliForm">
                @csrf
                @method('PUT')
                <div class="d-none">
                    <!-- Informations de l'utilisateur (celles de l'expéditeur) -->
                <h4>Informations du créateur</h4>

                <div>
                    <button class="btn btn-warning d-flex align-items-center gap-2" onclick="reloadPage()">
                        🔄 Recharger la page
                    </button>

                </div>

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
                    <label for="user_adresse" class="form-label">Adresse de l'utilisateur</label>
                    <input type="text" class="form-control bg-secondary text-white" id="user_adresse" name="user_adresse" value="{{ auth()->user()->adresse }}" readonly>
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

                @php
                   $id = request()->route('pli_id'); // Récupère l'ID de l'URL
                    $plimod = \App\Models\Pli::find($id);
                @endphp

                <div class="alert alert-primary d-flex align-items-center" role="alert">
                    <i class="bi bi-pencil-fill me-2"></i>
                    <strong>Modification du pli N° {{ $plimod->code }}</strong>
                </div>
                <div class="alert alert-succes d-flex align-items-center" role="alert">
                    <i class="bi bi-calendar-check me-2"></i>
                    <span>Créé le <strong>{{ $plimod->created_at->format('d/m/Y à H:i') }}</strong></span>
                </div>



                {{-- <input type="dis"> --}}
                         <hr>
                        <div class="mb-3">

                                <div class="mb-3">
                                    <label for="searchDestinataire" class="form-label fw-bold">
                                        <i class="bi bi-person-fill"></i> <h5>
                                            Sélectionner un destinataire </h5> <span class="text-danger">*</span>
                                    </label>

                                                            <!-- Informations du destinataire -->
                        <div class="card border-succes shadow-sm">
                            <div class="card-header bg-success text-white fw-bold">
                                <i class="bi bi-person-lines-fill"></i> Informations du destinataire
                            </div>
                            <div class="card-body">
                                <p class="mb-2"><strong>👤 Nom :</strong> {{ $plimod->destinataire_name }}</p>
                                <p class="mb-2"><strong>📍 Adresse :</strong> {{ $plimod->destinataire_adresse }}</p> <!-- Correction du champ affiché -->
                                <p class="mb-2"><strong>📞 Téléphone :</strong> {{ $plimod->destinataire_telephone }}</p>
                            </div>
                        </div>

                                    <!-- Input affichant le nom -->
                                    <input type="text" list="list-dests" class="form-control border-primary shadow-sm"
                                        id="searchDestinataire" placeholder="Rechercher un destinataire..." onchange="updateSelectValue()">

                                    <!-- Select caché pour stocker l'ID -->
                                    <select name="destinataire_id" id="destinataireSelect" class="form-select d-none">
                                        <option value="">Choisir un destinataire</option>
                                        @php
                                            $destinataires = \App\Models\Destinataire::all()->groupBy(function ($item) {
                                                return strtolower(trim($item->name)) . '-' . strtolower(trim($item->adresse)) . '-' . strtolower(trim($item->zone));
                                            })->map(function ($group) {
                                                return $group->first();
                                            })->values();
                                        @endphp

                                        @foreach ($destinataires as $destinataire)
                                            <option value="{{ $destinataire->id }}" data-name="{{ $destinataire->name }}"
                                                    data-adresse="{{ $destinataire->adresse }}" data-zone="{{ $destinataire->zone }}">
                                                {{ $destinataire->name }} - {{ $destinataire->adresse }} - {{ $destinataire->zone }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <!-- Liste déroulante du datalist -->
                                    <datalist id="list-dests">
                                        <option value="">Choisir un destinataire</option>
                                        @foreach ($destinataires as $destinataire)
                                            <option data-id="{{ $destinataire->id }}" value="{{ $destinataire->name }}">
                                                {{ $destinataire->name }} - {{ $destinataire->adresse }} - {{ $destinataire->zone }}
                                            </option>
                                        @endforeach
                                    </datalist>
                                </div>

                                            <!-- Informations sur le pli -->
                          <h4>Informations du pli</h4>


                                        <div class="card border-primary shadow-sm mb-3">
                                <div class="card-header bg-primary text-white fw-bold">
                                    <i class="bi bi-box-seam"></i> Information du pli
                                </div>
                                <div class="card-body">
                                    <p class="mb-2"><strong>📦 Nombre de pièces :</strong> {{ $plimod->nombre_de_pieces }}</p>
                                    <p class="mb-2"><strong>🔖 Références :</strong> {{ $plimod->reference }}</p>

                                                                        <p class="mb-2"><strong>📦 Type :</strong> {{ $plimod->type }}</p>
                                </div>
                            </div>
                <!--<div class="mb-3">
                    <label for="type" class="form-label">Type de pli<span class="text-danger">*</span></label>
                    <input type="text" class="form-control bg-primary text-white @error('type') is-invalid @enderror" id="type" name="type" value="{{ $pli->type }}" required>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>-->

                            <div class="mb-3">
                                <label for="type" class="form-label">Type de pli<span class="text-danger">*</span></label>
                                <select class="form-control bg-primary text-white @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="{{ $pli->type }}" disabled selected>Choisir un type</option>
                                    <option value="FACTURE">FACTURE</option>
                                    <option value="AVOIR">AVOIR</option>
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

                        <div class="form-group">
                            <label for="nombre_de_pieces">Nombre de pièces</label>
                            <input type="number" name="nombre_de_pieces" id="nombre_de_pieces" class="form-control" min="0" max="10" value="{{ $pli->nombre_de_pieces }}" required>
                        </div>

                        <!-- Références (si existantes) -->
                        <div id="references-container" required>
                            <!-- Les champs de référence seront ajoutés ici -->
                        </div>

                        <button type="submit" class="btn btn-primary my-3">Modifier le pli</button>
                        <a href="{{ route('client.plis.index') }}" class="btn btn-warning my-3">Ignorer les Modifications</a>


                                {{-- Fin de la div --}}
                        </div>

                    {{-- <select class="form-select @error('destinataire_id') is-invalid @enderror" id="destinataire_id" name="destinataire_id" required onchange="fillDestinataireInfo()"> --}}
                        {{-- <option value="">Choisir un destinataire</option> --}}
                     <div style="display:none">
                               @foreach($destinataires as $destinataire)
                            <option value="{{ $destinataire->id }}"
                            {{-- <option value="" --}}
                                data-name="{{ $destinataire->name }}"
                                data-adresse="{{ $destinataire->adresse }}"
                                data-telephone="{{ $destinataire->telephone }}"
                                data-email="{{ $destinataire->email }}"
                                data-zone="{{ $destinataire->zone }}"
                                data-contact="{{ $destinataire->contact }}"
                                data-autre="{{ $destinataire->autre }}"
                                @if($pli->destinataire_id == $destinataire->id) selected @endif>
                                {{ $destinataire->name }}
                            </option>
                        @endforeach
                     </div>
                    {{-- </select> --}}

                    @error('destinataire_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-none">
                    <!-- Champ du nom du destinataire pré-rempli -->
                    <div class="mb-3">
                        <label for="destinataire_name" class="form-label">Nom du destinataire</label>
                        <input type="text" class="form-control bg-primary text-white" id="destinataire_name" name="destinataire_name" value="{{ $pli->destinataire_name }}" readonly>
                    </div>

                    <!-- Autres informations du destinataire -->
                    <div class="mb-3">
                        <label for="destinataire_adresse" class="form-label">Adresse du destinataire</label>
                        <input type="text" class="form-control bg-primary text-white" id="destinataire_adresse" name="destinataire_adresse" value="{{ $pli->destinataire_adresse }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="destinataire_telephone" class="form-label">Téléphone du destinataire</label>
                        <input type="text" class="form-control bg-primary text-white" id="destinataire_telephone" name="destinataire_telephone" value="{{ $pli->destinataire_telephone }}" readonly>
                    </div>

                </div>





        </div>
    </div>
</div>


        <script>
            function fillDestinataireInfo() {
            const searchInput = document.getElementById('searchDestinataire');
            const listOptions = document.querySelectorAll("#list-dests option");
            const destinataireIdInput = document.getElementById('destinataire_id');

            let selectedDestinataire = null;

            listOptions.forEach(option => {
                if (option.value === searchInput.value) {
                    selectedDestinataire = option;
                }
            });

            if (selectedDestinataire) {
                destinataireIdInput.value = selectedDestinataire.getAttribute("data-id");
                document.getElementById('destinataire_name').value = selectedDestinataire.value || '';
                document.getElementById('destinataire_adresse').value = selectedDestinataire.getAttribute('data-adresse') || '';
                document.getElementById('destinataire_telephone').value = selectedDestinataire.getAttribute('data-telephone') || '';
                document.getElementById('destinataire_email').value = selectedDestinataire.getAttribute('data-email') || '';
                document.getElementById('destinataire_zone').value = selectedDestinataire.getAttribute('data-zone') || '';
                document.getElementById('destinataire_contact').value = selectedDestinataire.getAttribute('data-contact') || '';
                document.getElementById('destinataire_autre').value = selectedDestinataire.getAttribute('data-autre') || '';
            } else {
                destinataireIdInput.value = ""; // Réinitialise si aucune correspondance trouvée
            }
        }


                document.getElementById('nombre_de_pieces').addEventListener('input', function () {
                const container = document.getElementById('references-container');
                const nombreDePieces = parseInt(this.value, 10); // 🔹 Convertir proprement en entier
                let alertMessage = document.getElementById('alert-message');

                // 🔹 Réinitialiser les champs et l'alerte
                container.innerHTML = '';
                if (alertMessage) alertMessage.remove();

                // 🔍 Vérification des valeurs invalides
                if (isNaN(nombreDePieces) || nombreDePieces < 1) {
                    this.value = 1; // Empêche les valeurs incorrectes
                    nombreDePieces = 1;
                } else if (nombreDePieces > 10) {
                    this.value = 10; // Fixe à 10 maximum
                    nombreDePieces = 10;
                    showAlert("⚠️ Le nombre de références ne peut pas dépasser 10.", container);
                }

                // 🔄 Générer les champs dynamiques avec Bootstrap
                for (let i = 0; i < nombreDePieces; i++) {
                    const div = document.createElement('div');
                    div.className = 'form-group mb-3';
                    div.innerHTML = `

                                 <label for="reference_${i}" class="form-label fw-bold text-primary">Référence ${i + 1}</label>
                                 <input type="text" name="reference[]" id="reference_${i}" class="form-control border-primary shadow-sm"
                            placeholder="Entrez la référence" required>

                    `;
                    container.appendChild(div);
                }
            });

            //  **Fonction pour afficher un message d'alerte avec Bootstrap**
            function showAlert(message, container) {
                let alertDiv = document.createElement('div');
                alertDiv.id = 'alert-message';
                alertDiv.className = 'alert alert-warning mt-3 d-flex align-items-center';
                alertDiv.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2"></i> ${message}`;
                container.parentElement.insertBefore(alertDiv, container);
            }

        </script>


            </form>
                <script>
                        document.getElementById('searchDestinataire').addEventListener('input', function () {
                            let searchTerm = this.value.trim();
                            let listOptions = document.querySelectorAll("#list-dests option");
                            let destinataireIdInput = document.getElementById('destinataire_id');

                            let selectedId = "";

                            listOptions.forEach(option => {
                                if (option.value === searchTerm) {
                                    selectedId = option.getAttribute("data-id");
                                }
                            });

                            if (selectedId) {
                                destinataireIdInput.value = selectedId;
                            } else {
                                destinataireIdInput.value = ""; // 🔹 Réinitialise si le nom n'est pas valide
                            }
                        });
                        </script>

            <script>
            function updateSelectValue() {
                const searchInput = document.getElementById('searchDestinataire');
                const select = document.getElementById('destinataireSelect');
                const options = select.options;

                let selectedId = "";

                for (let option of options) {
                    if (option.getAttribute("data-name") === searchInput.value) {
                        selectedId = option.value;
                        break;
                    }
                }

                select.value = selectedId; // Met à jour le `<select>` avec l'ID

                // Si un ID est trouvé, met à jour les autres champs
                if (selectedId) {
                    document.getElementById('destinataire_name').value = searchInput.value;
                    document.getElementById('destinataire_adresse').value = select.options[select.selectedIndex].getAttribute("data-adresse") || '';
                    document.getElementById('destinataire_zone').value = select.options[select.selectedIndex].getAttribute("data-zone") || '';
                }
            }
            </script>

@endsection
