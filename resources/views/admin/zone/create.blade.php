@extends('layouts.master')

@section('title', 'Ajouter une zone')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Ajouter une plage de zone</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Nouvelle Zone</li>
        </ol>

        <div class="card">
            <div class="card-header">
                <h3>Ajouter une zone</h3>
                     <div style="text-align:right">
                    {{-- Navigation zone --}}
                    {{-- <a href="{{ url('admin/edit-zone/'.$zone->id) }}" class="btn btn-warning">Modifier</a> --}}
                    {{-- <a class="btn btn-primary" href="{{ url('admin/add-zone') }}">Ajouter une zone</a> --}}
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

                <br>
                    <h5>Informations sur la plage de zone </h5>

                <form id="zoneForm" action="{{ url('admin/add-zone') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Commune -->
                    <div class="mb-3">
                        <label for="Commune" class="form-label">Commune/Ville</label>
                        {{-- <input type="text" class="form-control"  required> --}}
                        {{-- Commune ou ville --}}
                            <select class="form-select" id="Commune" name="Commune">
                            <option  selected disabled >Choisissez une commune ou une ville</option>
                            <!-- Communes d'Abidjan -->
                            <optgroup label="🌆 Communes d'Abidjan">
                                <option value="Abobo">Abobo</option>
                                <option value="Adjamé">Adjamé</option>
                                <option value="Attécoubé">Attécoubé</option>
                                <option value="Cocody">Cocody</option>
                                <option value="Koumassi">Koumassi</option>
                                <option value="Marcory">Marcory</option>
                                <option value="Plateau">Plateau</option>
                                <option value="Port-Bouët">Port-Bouët</option>
                                <option value="Treichville">Treichville</option>
                                <option value="Yopougon">Yopougon</option>
                                 <option value="Hors Zone">Hors Zone</option>
                                <option value="Autre">Autre</option>
                            </optgroup>

                            <!-- Villes de Côte d'Ivoire -->
                            <optgroup label="🏙️ Villes de Côte d'Ivoire">
                                <option value="Anyama">Anyama</option>
                                <option value="Bingerville">Bingerville</option>
                                <option value="Grand-Bassam">Grand-Bassam</option>
                                <option value="Bonoua">Bonoua</option>
                                {{-- <option value="San-Pédro">San-Pédro</option>
                                <option value="Bouaké">Bouaké</option>
                                <option value="Korhogo">Korhogo</option>
                                <option value="Daloa">Daloa</option>
                                <option value="Man">Man</option>
                                <option value="Gagnoa">Gagnoa</option>
                                <option value="Bondoukou">Bondoukou</option>
                                <option value="Odienné">Odienné</option>
                                <option value="Soubré">Soubré</option>
                                <option value="Yamoussoukro">Yamoussoukro</option> --}}
                            </optgroup>
                        </select>
                        {{-- / Commuine ou ville --}}
                    </div>

                    {{-- Fin pour commune --}}

                    <div>
                        @php
                            $coursiers= \App\Models\Coursier::All();
                        @endphp

                        <label for="Coursier" class="form-label">Lier un coursier</label> ( <small>Ce coursier sera le seul responsable de cette plage dezone créée</small> )
                        <select name="code_coursier" id="" class="form-select" required>
                                <option value=""><--Choisir un coursier --></option>
                                @foreach ($coursiers as $coursier )
                                    <option value="{{$coursier->code}}">  {{ $coursier->nom }}  {{ $coursier->prenoms }}  ({{ $coursier->code }})</option>
                                @endforeach
                        </select>
                    </div>

                    <br>
                     <div>
                        <label for="libelle_zone" class="form-label">Donnez une description à votre plage de zone</label>
                        <input name="libelle_zone" id="" class="form-control" type="text">
                    </div>
                    <br>

                    <!-- PlageZone -->
                    <div class="mb-3">
                        <label for="PlageZone" class="form-label">Plage Zone</label>
                        <input
                            type="text"
                            class="form-control"
                            id="PlageZone"
                            name="PlageZone"
                            placeholder="Exemple : 001-005"
                            pattern="\d{3}-\d{3}"
                            required>
                        <div class="form-text">Entrez une plage de zone (ex : 001-005).</div>
                        @php
                           $lastPlage = App\Models\Zone::latest()->pluck('PlageZone')->first();
                        @endphp
                        <h6>Dernière plage enregistré :  {{$lastPlage }} </h6>

                    </div>
                    <br>
                    <hr>
            <h5>Détail  des Zones </h5>

                    <!-- Zone Details -->
                    <div id="zoneDetails" class="mt-4"></div>

                    <button type="submit" class="btn btn-primary mt-3">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
    @php
        $DonneesZones = \App\Models\Destinataire::select('adresse', 'zone')->get();
    @endphp

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const plageZoneInput = document.getElementById('PlageZone');
            const zoneDetailsContainer = document.getElementById('zoneDetails');

            plageZoneInput.addEventListener('input', function () {
                const plageValue = plageZoneInput.value.trim();
                const match = plageValue.match(/^(\d{3})-(\d{3})$/);

                if (!match) {
                    zoneDetailsContainer.innerHTML = '<div class="alert alert-danger">Format invalide. Utilisez une plage comme 001-005.</div>';
                    return;
                }

                const start = parseInt(match[1]);
                const end = parseInt(match[2]);

                if (start > end || start < 1 || end > 999) {
                    zoneDetailsContainer.innerHTML = '<div class="alert alert-danger">Plage invalide. Assurez-vous que 001 ≤ début ≤ fin ≤ 999.</div>';
                    return;
                }

                // Réinitialiser le contenu

                zoneDetailsContainer.innerHTML = '';

                for (let i = start; i <= end; i++) {
                    const paddedNumber = i.toString().padStart(3, '0');

                    const zoneGroup = `
                        <div class="mb-3 border p-3">
                            <h5 class="mb-3">Zone ${paddedNumber}</h5>

                            <!-- Numéro Zone -->
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="NumeroZone-${paddedNumber}" class="form-label">Numéro Zone</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="NumeroZone-${paddedNumber}"
                                        name="NumeroZone[${paddedNumber}]"
                                        value="${paddedNumber}"
                                        required>
                                </div>

                                <!-- Nom Zone -->
                                <div class="col-md-3">
                                    <label for="NomZone" class="form-label">Nom Zone</label>
                                    <input type="text"
                                        class="form-control"
                                        id="NomZone"
                                        name="NomZone[${paddedNumber}]"
                                        list="zonesList"
                                        placeholder="Ex: Cocody/CDY4"
                                        required
                                        autocomplete="off">
                                    <datalist id="zonesList">
                                        @foreach ($DonneesZones as $DonneesZone)
                                            <option value="{{ $DonneesZone->adresse }}">{{ $DonneesZone->adresse }} - {{ $DonneesZone->zone }}</option>
                                        @endforeach
                                    </datalist>
                                </div>


                            <!-- Libéllée de la zone -->
                            <div class="row mt-4">
                                <div class="">
                                    <label for="libelle_detail_zone-${paddedNumber}" class="form-label">Libélé de la zone (Décriver un peu plus la zone de sorte à pourvoirdonner la possibilité au autre de se retouver)</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="CoursierPhone-${paddedNumber}"
                                        name="libelle_detail_zone[${paddedNumber}]"
                                        required>
                                </div>
                            </div>

                        </div>
                    `;

                    zoneDetailsContainer.insertAdjacentHTML('beforeend', zoneGroup);
                }
            });
        });
    </script>

<script>
    // document.addEventListener("DOMContentLoaded", function () {
    //     let inputPlage = document.getElementById("PlageZone");
    //     let lastPlage = "{{ App\Models\Zone::latest()->pluck('PlageZone')->first() }}"; // Récupérer la dernière plage enregistrée

    //     inputPlage.addEventListener("input", function () {
    //         let plageSaisie = inputPlage.value.trim();

    //         if (plageSaisie === lastPlage || chevauchementPlages(plageSaisie, lastPlage) || plageInferieure(plageSaisie, lastPlage)) {
    //             inputPlage.classList.add("border-danger", "text-danger");
    //             alert("⚠️ Attention ! La plage saisie est identique, chevauche ou est inférieure à la dernière plage enregistrée !");
    //         } else {
    //             inputPlage.classList.remove("border-danger", "text-danger");
    //         }
    //     });

    //     function chevauchementPlages(plage1, plage2) {
    //         let [deb1, fin1] = plage1.split("-").map(Number);
    //         let [deb2, fin2] = plage2.split("-").map(Number);

    //         return (deb1 <= fin2 && fin1 >= deb2); // Vérifie si les plages se chevauchent
    //     }

    //     function plageInferieure(plage1, plage2) {
    //         let [deb1, fin1] = plage1.split("-").map(Number);
    //         let [deb2, fin2] = plage2.split("-").map(Number);

    //         return fin1 < deb2; // Vérifie si la plage saisie est inférieure à la dernière plage enregistrée
    //     }
    // });

    document.addEventListener("DOMContentLoaded", function () {
        let inputPlage = document.getElementById("PlageZone");
        let lastPlage = "{{ App\Models\Zone::latest()->pluck('PlageZone')->first() }}"; // Récupérer la dernière plage enregistrée
        let suggestionPlage = document.createElement("div");
        suggestionPlage.classList.add("form-text", "text-success");
        inputPlage.insertAdjacentElement("afterend", suggestionPlage);

        function calculerProchainePlage(plage) {
            let [deb, fin] = plage.split("-").map(Number);
            let nouvelleDeb = fin + 1;
            let nouvelleFin = nouvelleDeb + (fin - deb);
            return `${String(nouvelleDeb).padStart(3, '0')}-${String(nouvelleFin).padStart(3, '0')}`;
        }

        inputPlage.addEventListener("input", function () {
            let plageSaisie = inputPlage.value.trim();

            if (plageSaisie === lastPlage || chevauchementPlages(plageSaisie, lastPlage) || plageInferieure(plageSaisie, lastPlage)) {
                inputPlage.classList.add("border-danger", "text-danger");
                alert("⚠️ Attention ! La plage saisie est identique, chevauche ou est inférieure à la dernière plage enregistrée !");
            } else {
                inputPlage.classList.remove("border-danger", "text-danger");
            }
        });

        // Afficher la suggestion de plage suivante
        suggestionPlage.innerText = `🟢 Suggestion : ${calculerProchainePlage(lastPlage)}`;

        function chevauchementPlages(plage1, plage2) {
            let [deb1, fin1] = plage1.split("-").map(Number);
            let [deb2, fin2] = plage2.split("-").map(Number);
            return (deb1 <= fin2 && fin1 >= deb2);
        }

        function plageInferieure(plage1, plage2) {
            let [deb1, fin1] = plage1.split("-").map(Number);
            let [deb2, fin2] = plage2.split("-").map(Number);
            return fin1 < deb2;
        }
    });
</script>



@endsection
