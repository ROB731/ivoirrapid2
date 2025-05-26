@extends('layouts.master')

    {{-- ------------------pOUR la supression des sous zones ------------------- --}}
                 @php
                    if (isset($_GET['idSouZone']) && is_numeric($_GET['idSouZone'])) {
                        $idSousZone = $_GET['idSouZone']; //  Corrige la variable (avant `id-szone`)
                        $szoneDel = \App\Models\ZoneDetail::find($idSousZone);

                        if ($szoneDel) { //  Vérifie que la sous-zone existe
                            $szoneDel->delete(); //  `delete()` au lieu de `destroy()`
                            echo "<script>  alert('✅Sous-zone supprimée avec succès !') </script>";
                        } else {
                            echo "<script> alert('❌ Sous-zone introuvable'.)</script>";
                        }
                    }
                @endphp
    {{-- ------------------------------------------------ --}}


@section('title', 'Modifier une zone')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Modifier la plage zone </h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Modification des zones</li>
        </ol>

        <div class="card">
            <div class="card-header">
                <h3>Modifier une zone</h3>

                  <div style="text-align:right">
                    {{-- Navigation zone --}}
                    {{-- <a href="{{ url('admin/edit-zone/'.$zone->id) }}" class="btn btn-warning">Modifier</a> --}}
                    <a class="btn btn-primary" href="{{ url('admin/add-zone') }}">Ajouter une zone</a>
                    <a class=" btn btn-success" href="{{ url('admin/zone') }}">Liste de zones</a>
                    {{-- <a href="#">Retourne</a> --}}
                </div>
            </div>
            <div class="card-body">
                @if($errors->any())
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger" role="alert">
                            {{ $error }}
                        </div>
                    @endforeach
                @endif
                <form id="zoneForm" method="POST" action="{{ route('admin.zone.update', $zone->id) }}">
                    @csrf
                    @method('PUT')

                <h5>Informations relative à la zone</h5>
                    <div class="mb-3">
                        <label for="Commune" class="form-label">Commune/Ville</label>
                        {{-- <input type="text" class="form-control"  required> --}}
                        {{-- Commune ou ville --}}

                           <select class="form-select" id="Commune" name="Commune">
                                <option selected disabled>Choisissez une commune ou une ville</option>

                                <!-- Communes d'Abidjan -->
                                <optgroup label="🌆 Communes d'Abidjan">
                                    <option value="Abobo" {{ old('Commune', $zone->Commune ?? '') == 'Abobo' ? 'selected' : '' }}>Abobo</option>
                                    <option value="Adjamé" {{ old('Commune', $zone->Commune ?? '') == 'Adjamé' ? 'selected' : '' }}>Adjamé</option>
                                    <option value="Attécoubé" {{ old('Commune', $zone->Commune ?? '') == 'Attécoubé' ? 'selected' : '' }}>Attécoubé</option>
                                    <option value="Cocody" {{ old('Commune', $zone->Commune ?? '') == 'Cocody' ? 'selected' : '' }}>Cocody</option>
                                    <option value="Koumassi" {{ old('Commune', $zone->Commune ?? '') == 'Koumassi' ? 'selected' : '' }}>Koumassi</option>
                                    <option value="Marcory" {{ old('Commune', $zone->Commune ?? '') == 'Marcory' ? 'selected' : '' }}>Marcory</option>
                                    <option value="Plateau" {{ old('Commune', $zone->Commune ?? '') == 'Plateau' ? 'selected' : '' }}>Plateau</option>
                                    <option value="Port-Bouët" {{ old('Commune', $zone->Commune ?? '') == 'Port-Bouët' ? 'selected' : '' }}>Port-Bouët</option>
                                    <option value="Treichville" {{ old('Commune', $zone->Commune ?? '') == 'Treichville' ? 'selected' : '' }}>Treichville</option>
                                    <option value="Yopougon" {{ old('Commune', $zone->Commune ?? '') == 'Yopougon' ? 'selected' : '' }}>Yopougon</option>
                                </optgroup>

                                <!-- Villes de Côte d'Ivoire -->
                                <optgroup label="🏙️ Villes de Côte d'Ivoire">
                                    <option value="Anyama" {{ old('Commune', $zone->Commune ?? '') == 'Anyama' ? 'selected' : '' }}>Anyama</option>
                                    <option value="Bingerville" {{ old('Commune', $zone->Commune ?? '') == 'Bingerville' ? 'selected' : '' }}>Bingerville</option>
                                    <option value="Grand-Bassam" {{ old('Commune', $zone->Commune ?? '') == 'Grand-Bassam' ? 'selected' : '' }}>Grand-Bassam</option>
                                    <option value="Bonoua" {{ old('Commune', $zone->Commune ?? '') == 'Bonoua' ? 'selected' : '' }}>Bonoua</option>
                                </optgroup>
                            </select>

                        {{-- / Commuine ou ville --}}
                    </div> <br>
            {{-- Selection du coursier ------------------------------------------------------- --}}
                <div>
                    @php $coursiers = \App\Models\Coursier::all(); @endphp
                    <label for="Coursier" class="form-label">Lier un coursier</label>
                    <small>Ce coursier sera le seul responsable de cette plage de zone créée</small>

                    <select name="code_coursier" id="Coursier" class="form-select" required>
                        <option value=""><-- Choisir un coursier --></option>

                        @foreach ($coursiers as $coursier)
                            <option value="{{ $coursier->code }}"
                                {{ old('code_coursier', $zone->code_coursier ?? '') == $coursier->code ? 'selected' : '' }}>
                                {{ $coursier->nom }} {{ $coursier->prenoms }} ({{ $coursier->code }})
                            </option>
                        @endforeach
                    </select>
                </div> <br>
            {{-- / Fin pour chois de coursier----------------- --}}

                    {{-- Fin pour commune --}}

                    <!-- PlageZone -->
                    <p>La plage actuelle : {{ $zone->PlageZone }}  </p>
                    <div class="mb-3">
                        <label for="PlageZone" class="form-label">Plage Zone</label>
                        <input type="text"
                            class="form-control"
                            id="PlageZone"
                            name="PlageZone"
                            value="{{ old('PlageZone', $zone->PlageZone ?? '') }}"
                            > <!-- Désactivé pour empêcher la modification manuelle  "readonly" -->
                        <div class="form-text">Modifier la plage.</div>
                    </div>
                    <br>
                    <div>
                        <label for="libelle_zone" class="form-label" >Donnez une description à votre plage de zone</label>
                        <input name="libelle_zone" id="" class="form-control" type="text" placeholder="Décrivez votre zone" value="{{ old('libelle_zone.' . $zone->libelle_zone, $zone->libelle_zone) }}">
                    </div>

            <!-- Boutons pour ajouter/supprimer les lignes -->
            {{-- <div class="mt-3">
                <label class="form-label">Nombre de lignes à ajouter :</label>
                <input type="number" id="zoneCount" class="form-control d-inline w-auto" value="1" min="1">
                <button type="button" class="btn btn-success ms-2" id="addZone" onclick="alert('Vous venez d\'ajouter une sous zone ✅')">➕ Ajouter</button>
            </div> --}}

            <div class="mt-3">
                <label class="form-label">Nombre de lignes à ajouter :</label>
                <input type="number" id="zoneCount" class="form-control d-inline w-auto" value="1" min="1">
                <button type="button" class="btn btn-success ms-2" id="addZone">➕ Ajouter</button>
            </div>

        <!--  Conteneur pour les nouvelles sous-zones -->




    <hr> <br>

     <br>
     {{-- ------------------------------------------------------------------------------------------------------------- --}}
<h5>Informations sur les  sous zones</h5>

    <div id="zoneDetails" class="mt-4">

    @foreach ($zone->details as $zoneDetail)

        <div class="mb-3 border p-3 zone-item">
            <h5 class="mb-3">Zone <span class="zone-number">{{ $zoneDetail->NumeroZone }}</span></h5>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Numéro Zone</label>
                    <input type="text" class="form-control" name="NumeroZone[{{ $zoneDetail->id }}]" value="{{ old('NumeroZone.' . $zoneDetail->NumeroZone, $zoneDetail->NumeroZone) }}" required>
                </div>
                <div class="col-md-3">

                     <label for="choixZone" class="form-label">Sélectionnez ou entrez une zone</label>
                    <input type="text" class="form-control" name="NomZone[{{ $zoneDetail->id }}]" value="{{ old('NomZone.' . $zoneDetail->NumeroZone, $zoneDetail->NomZone) }}" required>
                        <datalist id="zonesList">
                            <option value="Abobo">
                            <option value="Adjamé">
                        </datalist>

                </div>

            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <label class="form-label">Libéllée de la sous zone</label>
                    <input type="text" class="form-control"  name="libelle_detail_zone[{{ $zoneDetail->id }}]" value="{{ old('libelle_detail_zones.' . $zoneDetail->NumeroZone, $zoneDetail->libelle_detail_zones) }}" required>
                </div>
            </div>
        </div>

        @endforeach

         <div id="zoneContainer">
            <!-- ✅ Les nouvelles lignes seront insérées ici -->
        </div>
</div>

{{-- --------------- en bas fonctionnelle------------------------------------------------------------------------ --}}




       <script>
let zoneCounter = 1; //  Définir le compteur initial

document.getElementById("addZone").addEventListener("click", function () {
    let zoneContainer = document.getElementById("zoneContainer"); //  Où ajouter les nouvelles lignes
    let zoneCount = document.getElementById("zoneCount").value; //  Nombre de lignes à ajouter

    let count = parseInt(zoneCount);
    if (isNaN(count) || count < 1) {
        alert("❌ Veuillez entrer un nombre valide !");
        return;
    }

    for (let i = 0; i < count; i++) {
        let paddedNumber = String(zoneCounter).padStart(3, '0'); //  Format `001`, `002`, `003`
        zoneCounter++; //  Incrémenter le compteur

        let newZone = `
            <div class="mb-3 border p-3 zone-item">
                <h5 class="mb-3">Zone <span class="zone-number">${paddedNumber}</span></h5>
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Numéro Zone</label>
                        <input type="text" class="form-control" name="NumeroZone[${paddedNumber}]" value="${paddedNumber}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nom Zone</label>
                        <input type="text" class="form-control" name="NomZone[${paddedNumber}]" list="zonesList" required>
                        <datalist id="zonesList">
                            <option value="Abobo">
                            <option value="Adjamé">
                        </datalist>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Libellé de la sous-zone</label>
                        <input type="text" class="form-control" name="libelle_detail_zone[${paddedNumber}]" required>
                    </div>
                </div>
            </div>
        `;

        zoneContainer.insertAdjacentHTML("beforeend", newZone); // ✅ Ajoute la nouvelle ligne
    }

    alert(`✅ ${count} sous-zone(s) ajoutée(s) avec ID incrémenté !`);
});
</script>


{{-- Pour le formulaire ajouter par du js --}}

                {{-- ---------------------------------------- --}}
                 <button type="submit" class="btn btn-success"> Modifier</button>
                </form>


    <!-- Script pour gérer la génération dynamique des champs -->

@endsection
