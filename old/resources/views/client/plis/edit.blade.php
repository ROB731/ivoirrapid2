@extends('layout.master')

@section('title', 'IvoirRp - Modifier un Pli')

@section('content')
@if($message = Session::get('message'))
    <div class="alert alert-success alert-dismissible fade show">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <strong>{{ $message }}</strong>
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
            
                <!-- Informations du destinataire -->
                <h4>Informations du destinataire</h4>
                <div class="mb-3">
                    <label for="destinataire_id" class="form-label">Sélectionner le destinataire<span class="text-danger">*</span></label>
                    <select class="form-select @error('destinataire_id') is-invalid @enderror" id="destinataire_id" name="destinataire_id" required onchange="fillDestinataireInfo()">
                        <option value="">Choisir un destinataire</option>
                        @foreach($destinataires as $destinataire)
                            <option value="{{ $destinataire->id }}"
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
                    </select>
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
            
                    <!-- Autres champs -->
                    <!-- Adresse, téléphone, email, zone du destinataire, etc -->
                </div>
            
                <!-- Informations sur le pli -->
                <h4>Informations du pli</h4>
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
                    <input type="number" name="nombre_de_pieces" id="nombre_de_pieces" class="form-control" min="1" max="10" value="{{ $pli->nombre_de_pieces }}" required>
                </div>
            
                <!-- Références (si existantes) -->
                <div id="references-container">
                    <!-- Les champs de référence seront ajoutés ici -->
                </div>
            
                <button type="submit" class="btn btn-primary my-3">Modifier le pli</button>
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

@endsection
