@extends('layouts.master')

{{-- ------------------pOUR la supression des sous zones ------------------- --}}
                 @php
                    if (isset($_GET['idSouZone']) && is_numeric($_GET['idSouZone'])) {
                        $idSousZone = $_GET['idSouZone']; //  Corrige la variable (avant `id-szone`)
                        $szoneDel = \App\Models\ZoneDetail::find($idSousZone);

                        if ($szoneDel) { //  Vérifie que la sous-zone existe
                            $szoneDel->delete(); //  `delete()` au lieu de `destroy()`
                            echo "<script>  alert('Sous-zone supprimée avec succès !') </script>";
                        } else {
                            // echo "<script> alert('❌ Sous-zone introuvable'.)</script>";
                        }
                    }
                @endphp
    {{-- ------------------------------------------------ --}}
                    @section('title', 'Détails de la zone')

                    @section('content')

                    @include('admin.zone.showEntreprise')
                        @include('admin.zone.showDestinataires')

                        <div class="container-fluid px-4">
                            <h1 class="mt-4">Détails de la zone</h1>
                            <ol class="breadcrumb mb-4">
                                <li class="breadcrumb-item"><a href="{{ route('admin.zone.index') }}">Zones</a></li>
                                <li class="breadcrumb-item active">Détails de la zone</li>
                            </ol>
                            <div class="card">
                                <div class="card-header">
                                    <h3>Zone : {{ $zone->Commune }} ({{ $zone->PlageZone }})   </h3>

                                    <div style="text-align:right">
                                        {{-- Navigation zone --}}
                                        <a href="{{ url('admin/edit-zone/'.$zone->id) }}" class="btn btn-warning">Modifier</a>
                                        <a class="btn btn-primary" href="{{ url('admin/add-zone') }}">Ajouter une zone</a>
                                        <a class=" btn btn-success" href="{{ url('admin/zone') }}">Liste de zones</a>
                                        {{-- <a href="#">Retourne</a> --}}
                                    </div>

                                </div>
                                <div class="card-body">
                                    <h5>Information sur la plage de Zone</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Plage de la zone</th>
                                                        <th>Commune de la zone</th>
                                                        <th>Code du coursier</th>
                                                        <th>Description de la plage</th>
                                                        <th>Nombre de zone</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{ $zone->PlageZone }}</td>
                                                        <td>{{ $zone->Commune }}</td>
                                                        <td>  <button class="btn btn-text">{{ $zone->code_coursier ?? 'Svp Ajouter Un coursier pour cette Plage' }}</button></td>
                                                        <td>{{ $zone->libelle_zone ?? 'Ajouter une description' }}</td>
                                                        @php
                                                            [$d, $e]= explode('-', $zone->PlageZone)
                                                        @endphp
                                                        <td> @php
                                                                // echo count($zone1)+1;
                                                                // echo $e-$d +1;
                                                                // echo'<i> (Sous Zones Principales) </i>'
                                                                echo(count($zone->details));
                                                            @endphp
                                                        </td>
                                                        {{-- <td>{{ $zone->libelle_zone }}</td> --}}
                                                    </tr>
                                            </tbody>
                                            </table>
                                        </div>
                            {{-- --------------------------------------------------------------- --}}
                                        <h6>Information sur les sous zones</h6>
                                        <div class="table-responsive" style="height:250px; overflow:auto">
                                            <table class="table table-bordered">
                                                <thead style="position: sticky;">
                                                    <tr>
                                                        <th >#</th>
                                                        <th style="max-width:100px">Numéro ds sous zones</th>
                                                        <th >Nom des sous zones</th>
                                                        <th>Description de la plage</th>
                                                        <th>Actions</th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                @foreach ( $zone->details as $detail )
                                                    <tr>
                                                        <td> {{ $loop->iteration }} </td>
                                                        <td>{{ $detail->NumeroZone }}</td>
                                                        <td>{{ $detail->NomZone }}</td>
                                                        <td>{{ $detail->libelle_detail_zones }}</td>
                                                        <td>
                                <!-- Modal ---------------------------------------------------------------------------------->
                                                                    <!--  Bouton pour ouvrir le modal -->
                                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#entreprise{{ $loop->iteration }}">
                                                                    <i class="fas fa-building"></i>
                                                                </button>

                                                                <!--  Modal -->
                                                                <div class="modal fade" id="entreprise{{ $loop->iteration }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-lg">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                @php
                                                                                    //  Filtrer les entreprises selon la zone du détail
                                                                                    $entrepriseZone = \App\Models\User::where('Zone', $detail->NumeroZone)->get();
                                                                                @endphp

                                                                                <h5 class="modal-title">Liste des entreprises dans la sous-zone :
                                                                                    <p style="color:red">{{ $detail->NomZone }} zone : {{ $detail->NumeroZone }} <br>
                                                                                        Nombre d'entreprise :   {{ count($entrepriseZone) }}

                                                                                    </p>
                                                                                </h5>
                                                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">

                                                                                @if($entrepriseZone->isNotEmpty())
                                                                                    <ul>
                                                                                        @foreach ($entrepriseZone as $entreprise)
                                                                                            <li>{{ $entreprise->name }}</li> <!--  Affichage des entreprises -->
                                                                                        @endforeach
                                                                                    </ul>
                                                                                @else
                                                                                    <p>❌ Aucune entreprise trouvée.</p>
                                                                                @endif
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                                                {{-- <button type="button" class="btn btn-primary">Sauvegarder les modifications</button> --}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                {{-- Entreprise ------------------------------------------------ --}}
                                                                        <button class="btn btn-circle" title="Voir les destinataires de cette sous zones"    data-bs-toggle="modal" data-bs-target="#destinataire{{ $loop->iteration }}>
                                                                            <i class="fas fa-users"></i> <!-- Icône destinataires -->
                                                                        </button>
                                {{-- Pour les destinataires ------------------------------------------------------------------ --}}
                                <!--  Bouton pour ouvrir le modal des destinataires -->
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#destinataire{{ $loop->iteration }}">
                                        <i class="fas fa-user"></i>
                                    </button>
                    <!--  Modal pour afficher les destinataires -->
                    <div class="modal fade" id="destinataire{{ $loop->iteration }}" tabindex="-1" role="dialog" aria-labelledby="destinataireLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    @php
                                        //  Filtrer les destinataires liés à la sous-zone
                                        $destinataires = \App\Models\Destinataire::where('zone', $detail->NumeroZone)->get();
                                    @endphp

                                    <h5 class="modal-title">Liste des destinataires dans la sous-zone :
                                    <p style="color:red">
                                            <span >{{ $detail->NomZone }}</span>
                                            zone : {{ $detail->NumeroZone }} <br>
                                            Nombre de destinataire:  {{ count($destinataires) }}
                                    </p>

                                    </h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @if($destinataires->isNotEmpty())
                                        <ol>
                                            @foreach ($destinataires as $destinataire)
                                                <li>{{ $destinataire->name }}</li> <!--  Affichage des destinataires -->
                                            @endforeach
                                        </ol>
                                    @else
                                        <p>❌ Aucun destinataire trouvé.</p>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    {{-- <button type="button" class="btn btn-primary">Sauvegarder les modifications</button> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Fin pour les destinbataires ---------------------------------- --}}
                                                            {{-- <form method="get" style="display:inline">
                                                                <button type="submit"  class="btn btn-danger remove-zone" value="{{ $detail->id }}" name="idSouZone">
                                                                    <i class="fas fa-trash-alt"></i> <!-- Icône poubelle -->
                                                                </button>
                                                            </form> --}}

                                                                <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                         <i class="fas fa-trash-alt"></i>
                                                    </button>

                                            <!-- Modal pour confirmer la suppression ------------------------------->
                                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel"> Êtes vous de supprimer la sous zones ??</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">

                                                             <form method="get" style="display:block; text-align:center">
                                                                <button type="submit" class="btn btn-danger remove-zone" value="{{ $detail->id }}" name="idSouZone">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        {{-- Fin de modal pour suppression----------------------------------- --}}







                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                            </table>
                                        </div>
                                        {{-- --------------- Autres informations ------------------- --}}
                                        <h5>Carte Google Maps : {{ $zone->Commune }}  </h5>
                                        <h6> {{ $zone->libelle_zone }}</h6>
                                            <div id="map"></div>
                                            <script>
                                                    function initMap() {
                                                        let zones = @json($zone->commune); //  Récupère les noms des zones dynamiquement

                                                        let map = new google.maps.Map(document.getElementById('map'), {
                                                            zoom: 12,
                                                            center: { lat: 5.336, lng: -4.026 } // 📍 Centre sur Abidjan
                                                        });

                                                        zones.forEach(zone => {
                                                            fetch(`https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(zones)}&key=AIzaSyDMSfHlPf4v2yTLoH2xk80_YXwVgIvBv34`)
                                                                .then(response => response.json())
                                                                .then(data => {
                                                                    if (data.status === "OK") {
                                                                        let location = data.results[0].geometry.location;

                                                                        let marker = new google.maps.Marker({
                                                                            position: location,
                                                                            map: map,
                                                                            title: zone
                                                                        });

                                                                        let infoWindow = new google.maps.InfoWindow({
                                                                            content: `<strong>${zone}</strong>`
                                                                        });

                                                                        marker.addListener("click", () => {
                                                                            infoWindow.open(map, marker);
                                                                        });
                                                                    } else {
                                                                        console.error("Erreur de géocodage pour :", zone, data.status);
                                                                    }
                                                                })
                                                                .catch(error => console.error("Erreur API :", error));
                                                        });
                                                    }

                                                    window.onload = initMap;
                                                </script>
                                    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDMSfHlPf4v2yTLoH2xk80_YXwVgIvBv34"></script>
                                        <style>
                                            #map {
                                                height: 500px;
                                                width: 100%;
                                            }


                                            th {
                                                background-color: #e7e6e6 !important;
                                                position: sticky;
                                                top: 0; /* Fixe l'en-tête en haut */
                                                z-index: 1; /* Assure que l'en-tête reste au-dessus des autres éléments */
                                            }


                                            tbody tr:nth-child(even) {
                                                    background-color: #ececec;
                                                    }

                                        </style>

                                    <a href="{{ route('admin.zone.index') }}" class="btn btn-secondary mt-3">Retour</a>
                                </div>
                            </div>

                        </div>
                    @endsection
