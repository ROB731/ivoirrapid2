@extends('layouts.master')

@section('title', 'IvoirRp - Modifier un Coursier')

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

            <form action="{{ url('admin/update-coursier/' . $coursier->id) }}" method="POST" class="mt-5 bg-light p-5 rounded shadow" enctype="multipart/form-data" id="coursierEditForm">
                @csrf
                @method('PUT')

                @if ($errors->has('zones'))
                    <div class="alert alert-danger">
                        @foreach ($errors->get('zones') as $message)
                            <p>{{ $message }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- Nombre de zones -->
                <div class="mb-3">
                    <label for="zone-count" class="form-label">Nombre de zones<span class="text-danger">*</span></label>
                    <input type="number" class="form-control bg-primary text-white @error('zone_count') is-invalid @enderror"
                        id="zone-count"
                        name="zone_count"
                        min="0"
                        value="{{ old('zone_count', count($coursier->zones)) }}">
                    @error('zone_count')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="button" class="btn btn-secondary mb-3" id="generate-zones">Générer les zones</button>

                <!-- Zones dynamiques -->
                <div id="zone-container">
                    @foreach ($coursier->zones as $index => $zone)
                        <div class="mb-3">
                            <label for="zone-{{ $index + 1 }}" class="form-label">Zone {{ $index + 1 }} :</label>
                            <input type="text"
                                name="zones[]"
                                class="form-control bg-primary text-white"
                                value="{{ old('zones.' . $index, $zone) }}"
                                >
                        </div>
                    @endforeach
                </div>

                <!-- Nom -->
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom<span class="text-danger">*</span></label>
                    <input type="text" class="form-control bg-primary text-white @error('nom') is-invalid @enderror"
                        id="nom"
                        name="nom"
                        value="{{ old('nom', $coursier->nom) }}"
                        required>
                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Prénoms -->
                <div class="mb-3">
                    <label for="prenoms" class="form-label">Prénoms<span class="text-danger">*</span></label>
                    <input type="text" class="form-control bg-primary text-white @error('prenoms') is-invalid @enderror"
                        id="prenoms"
                        name="prenoms"
                        value="{{ old('prenoms', $coursier->prenoms) }}"
                        required>
                    @error('prenoms')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Téléphone -->
                <div class="mb-3">
                    <label for="telephone" class="form-label">Téléphone<span class="text-danger">*</span></label>
                    <input type="text" class="form-control bg-primary text-white @error('telephone') is-invalid @enderror"
                        id="telephone"
                        name="telephone"
                        value="{{ old('telephone', $coursier->telephone) }}"
                        required>
                    @error('telephone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Adresse -->
                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse<span class="text-danger">*</span></label>
                    <input type="text" class="form-control bg-primary text-white @error('adresse') is-invalid @enderror"
                        id="adresse"
                        name="adresse"
                        value="{{ old('adresse', $coursier->adresse) }}"
                        required>
                    @error('adresse')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control bg-primary text-white @error('email') is-invalid @enderror"
                        id="email"
                        name="email"
                        value="{{ old('email', $coursier->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Contact d'urgence -->
                <div class="mb-3">
                    <label for="contact_urgence" class="form-label">Contact d'urgence<span class="text-danger">*</span></label>
                    <input type="text" class="form-control bg-primary text-white @error('contact_urgence') is-invalid @enderror"
                        id="contact_urgence"
                        name="contact_urgence"
                        value="{{ old('contact_urgence', $coursier->contact_urgence) }}"
                        required>
                    @error('contact_urgence')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Affiliation d'urgence -->
                <div class="mb-3">
                    <label for="affiliation_urgence" class="form-label">Affiliation d'urgence<span class="text-danger">*</span></label>
                    <input type="text" class="form-control bg-primary text-white @error('affiliation_urgence') is-invalid @enderror"
                        id="affiliation_urgence"
                        name="affiliation_urgence"
                        value="{{ old('affiliation_urgence', $coursier->affiliation_urgence) }}"
                        required>
                    @error('affiliation_urgence')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Autres champs ici similaires à la création -->

                 <!-- Code -->
                 <div class="mb-3">
                    <label for="code" class="form-label">Code</label>
                    <input type="text" class="form-control bg-primary text-white @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $coursier->code) }}">
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Numéro de permis -->
                <div class="mb-3">
                    <label for="numero_de_permis" class="form-label">Numéro de permis</label>
                    <input type="text" class="form-control bg-primary text-white @error('numero_de_permis') is-invalid @enderror" id="numero_de_permis" name="numero_de_permis" value="{{ old('numero_de_permis', $coursier->numero_de_permis) }}">
                    @error('numero_de_permis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Date de validité du permis -->
                <div class="mb-3">
                    <label for="date_de_validite_du_permis" class="form-label">Date de validité du permis</label>
                    <input type="date" class="form-control bg-primary text-white @error('date_de_validite_du_permis') is-invalid @enderror" id="date_de_validite_du_permis" name="date_de_validite_du_permis" value="{{ old('date_de_validite_du_permis', $coursier->date_de_validite_du_permis) }}">
                    @error('date_de_validite_du_permis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Catégorie du permis -->
                <div class="mb-3">
                    <label for="categorie_du_permis" class="form-label">Catégorie du permis</label>
                    <input type="text" class="form-control bg-primary text-white @error('categorie_du_permis') is-invalid @enderror" id="categorie_du_permis" name="categorie_du_permis" value="{{ old('categorie_du_permis', $coursier->categorie_du_permis) }}">
                    @error('categorie_du_permis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Numéro de CNI -->
                <div class="mb-3">
                    <label for="numero_de_cni" class="form-label">Numéro de CNI</label>
                    <input type="text" class="form-control bg-primary text-white @error('numero_de_cni') is-invalid @enderror" id="numero_de_cni" name="numero_de_cni" value="{{ old('numero_de_cni', $coursier->numero_de_cni) }}">
                    @error('numero_de_cni')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Date de validité de la CNI -->
                <div class="mb-3">
                    <label for="date_de_validite_de_la_cni" class="form-label">Date de validité de la CNI</label>
                    <input type="date" class="form-control bg-primary text-white @error('date_de_validite_de_la_cni') is-invalid @enderror" id="date_de_validite_de_la_cni" name="date_de_validite_de_la_cni" value="{{ old('date_de_validite_de_la_cni', $coursier->date_de_validite_de_la_cni) }}">
                    @error('date_de_validite_de_la_cni')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Numéro de la CMU -->
                <div class="mb-3">
                    <label for="numero_de_la_cmu" class="form-label">Numéro de la CMU</label>
                    <input type="text" class="form-control bg-primary text-white @error('numero_de_la_cmu') is-invalid @enderror" id="numero_de_la_cmu" name="numero_de_la_cmu" value="{{ old('numero_de_la_cmu', $coursier->numero_de_la_cmu) }}">
                    @error('numero_de_la_cmu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Date de validité de la CMU -->
                <div class="mb-3">
                    <label for="date_de_validite_de_la_cmu" class="form-label">Date de validité de la CMU</label>
                    <input type="date" class="form-control bg-primary text-white @error('date_de_validite_de_la_cmu') is-invalid @enderror" id="date_de_validite_de_la_cmu" name="date_de_validite_de_la_cmu" value="{{ old('date_de_validite_de_la_cmu', $coursier->date_de_validite_de_la_cmu) }}">
                    @error('date_de_validite_de_la_cmu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Date de naissance -->
                <div class="mb-3">
                    <label for="date_de_naissance" class="form-label">Date de naissance</label>
                    <input type="date" class="form-control bg-primary text-white @error('date_de_naissance') is-invalid @enderror" id="date_de_naissance" name="date_de_naissance" value="{{ old('date_de_naissance', $coursier->date_de_naissance) }}">
                    @error('date_de_naissance')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Groupe sanguin -->
                <div class="mb-3">
                    <label for="groupe_sanguin" class="form-label">Groupe sanguin</label>
                    <input type="text" class="form-control bg-primary text-white @error('groupe_sanguin') is-invalid @enderror" id="groupe_sanguin" name="groupe_sanguin" value="{{ old('groupe_sanguin', $coursier->groupe_sanguin) }}">
                    @error('groupe_sanguin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Date de début du contrat -->
                <div class="mb-3">
                    <label for="date_de_debut_du_contrat" class="form-label">Date de début du contrat</label>
                    <input type="date" class="form-control bg-primary text-white @error('date_de_debut_du_contrat') is-invalid @enderror" id="date_de_debut_du_contrat" name="date_de_debut_du_contrat" value="{{ old('date_de_debut_du_contrat', $coursier->date_de_debut_du_contrat) }}">
                    @error('date_de_debut_du_contrat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Date de fin du contrat -->
                <div class="mb-3">
                    <label for="date_de_fin_du_contrat" class="form-label">Date de fin du contrat</label>
                    <input type="date" class="form-control bg-primary text-white @error('date_de_fin_du_contrat') is-invalid @enderror" id="date_de_fin_du_contrat" name="date_de_fin_du_contrat" value="{{ old('date_de_fin_du_contrat', $coursier->date_de_fin_du_contrat) }}">
                    @error('date_de_fin_du_contrat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Bouton de soumission -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('generate-zones').addEventListener('click', function () {
    const zoneContainer = document.getElementById('zone-container');
    const zoneCount = document.getElementById('zone-count').value;

    // Vider les zones existantes
    zoneContainer.innerHTML = '';

    // Générer les champs pour chaque zone
    for (let i = 1; i <= zoneCount; i++) {
        const zoneDiv = document.createElement('div');
        zoneDiv.classList.add('mb-3');

        const zoneLabel = document.createElement('label');
        zoneLabel.classList.add('form-label');
        zoneLabel.innerText = `Zone ${i} :`;
        zoneDiv.appendChild(zoneLabel);

        const zoneInput = document.createElement('input');
        zoneInput.type = 'text';
        zoneInput.name = 'zones[]';
        zoneInput.classList.add('form-control', 'bg-primary', 'text-white');
        zoneInput.required = true;
        zoneDiv.appendChild(zoneInput);

        zoneContainer.appendChild(zoneDiv);
    }
});
</script>
@endsection
