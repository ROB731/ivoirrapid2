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

            <form action="{{ url('client/add-pli') }}" method="POST" class="mt-5 bg-light p-5 rounded shadow" enctype="multipart/form-data" id="pliForm">
                @csrf

                <div class="d-none">
                    <!-- Informations de l'utilisateur -->
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
                                data-autre="{{ $destinataire->autre }}">
                                {{ $destinataire->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('destinataire_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-none">
                    <!-- Nom du destinataire -->
                    <div class="mb-3">
                        <label for="destinataire_name" class="form-label">Nom du destinataire</label>
                        <input type="text" class="form-control bg-primary text-white" id="destinataire_name" name="destinataire_name" readonly>
                    </div>

                    <!-- Adresse du destinataire -->
                    <div class="mb-3">
                        <label for="destinataire_adresse" class="form-label">Adresse du destinataire</label>
                        <input type="text" class="form-control bg-primary text-white" id="destinataire_adresse" name="destinataire_adresse" readonly>
                    </div>

                    <!-- Téléphone du destinataire -->
                    <div class="mb-3">
                        <label for="destinataire_telephone" class="form-label">Téléphone du destinataire</label>
                        <input type="text" class="form-control bg-primary text-white" id="destinataire_telephone" name="destinataire_telephone" readonly>
                    </div>

                    <!-- Email du destinataire -->
                    <div class="mb-3">
                        <label for="destinataire_email" class="form-label">Email du destinataire</label>
                        <input type="email" class="form-control bg-primary text-white" id="destinataire_email" name="destinataire_email" readonly>
                    </div>

                    <!-- Zone du destinataire -->
                    <div class="mb-3">
                        <label for="destinataire_zone" class="form-label">Zone du destinataire</label>
                        <input type="text" class="form-control bg-primary text-white" id="destinataire_zone" name="destinataire_zone" readonly>
                    </div>

                    <!-- Contact alternatif du destinataire -->
                    <div class="mb-3">
                        <label for="destinataire_contact" class="form-label">Contact alternatif du destinataire</label>
                        <input type="text" class="form-control bg-primary text-white" id="destinataire_contact" name="destinataire_contact" readonly>
                    </div>

                    <!-- Autres informations -->
                    <div class="mb-3">
                        <label for="destinataire_autre" class="form-label">Autres informations</label>
                        <textarea class="form-control bg-primary text-white" id="destinataire_autre" name="destinataire_autre" rows="3" readonly></textarea>
                    </div>
                </div>

                <!-- Informations sur le colis -->
                <h4>Informations du pli</h4>
                <div class="mb-3">
                    <label for="type" class="form-label">Type de pli<span class="text-danger">*</span></label>
                    <input type="text" class="form-control bg-primary text-white @error('type') is-invalid @enderror" id="type" name="type" required>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 d-none">
                    <label for="code" class="form-label">Code du Pli</label>
                    <input type="text" class="form-control" id="code" name="code" value="{{ $code }}" readonly>
                </div>

                <div class="form-group">
                    <label for="nombre_de_pieces">Nombre de pièces</label>
                    <input type="number" name="nombre_de_pieces" id="nombre_de_pieces" class="form-control" min="1" required>
                </div>

                <div id="references-container">
                    <!-- Les champs de référence seront ajoutés ici -->
                </div>


                {{-- <div class="mb-3">
                    <label for="nombre_de_pieces" class="form-label">Nombre de pièces<span class="text-danger">*</span></label>
                    <input type="number" class="form-control bg-primary text-white @error('nombre_de_pieces') is-invalid @enderror" id="nombre_de_pieces" name="nombre_de_pieces" required>
                    @error('nombre_de_pieces')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div> --}}

                <button type="submit" class="btn btn-primary">Enregistrer le pli</button>
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

    <script>
    document.getElementById('nombre_de_pieces').addEventListener('input', function() {
        const container = document.getElementById('references-container');
        container.innerHTML = ''; // Réinitialiser le contenu

        const nombreDePieces = this.value; // Récupérer la valeur du champ nombre_de_pieces

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
