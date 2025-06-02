   @if(Auth::check())
            {{-- <p>Bienvenue, {{ Auth::user()->name }} sur votre profil!</p> --}}
        @else
            <p>Veuillez vous connecter.</p>
        @endif
                @php

                if (!empty($_GET['archive'])) {
                    $archiveDest = $_GET['archive'];

                                  $pliArchive = \App\Models\Pli::where('user_id', Auth::id())
                                ->where('destinataire_id', $archiveDest)
                                ->whereHas('attributions')
                                ->whereHas('pliStatuerHistory', function ($query) {
                                    $query->orderBy('date_changement', 'desc'); //  Trie du plus récent au moins récent
                                })
                                ->with(['destinataire', 'pliStatuerHistory' => function ($query) {
                                    $query->orderBy('date_changement', 'desc'); //  Trie les statuts récents dans la relation
                                }])
                                // ->with('currentStatuer')
                                ->get();

                              } ;
                        @endphp

                 {{-- @php

                     $archivePlis = \App\Models\Pli::select(
                          'destinataire_id',
                            DB::raw('MAX(destinataire_name) as destinataire_name'),
                            DB::raw('MAX(destinataire_adresse) as destinataire_adresse'),
                            DB::raw('MAX(destinataire_telephone) as destinataire_telephone'),
                            DB::raw('COUNT(*) as nombre_de_plis')
                            )
                            ->where('user_id', Auth::id()) //  Filtre uniquement les plis envoyés par l'utilisateur
                            ->groupBy('destinataire_id') //  Regroupe les résultats pour éviter les répétitions
                            ->get();

                    $totalPlisUser = \App\Models\Pli::where('user_id', Auth::id())
                        ->count(); //  Compte tous les plis envoyés par l'utilisateur
                @endphp --}}


                @php
                        $archivePlis = \App\Models\Pli::select(
                                'destinataire_id',
                                DB::raw('MAX(destinataire_name) as destinataire_name'),
                                DB::raw('MAX(destinataire_adresse) as destinataire_adresse'),
                                DB::raw('MAX(destinataire_telephone) as destinataire_telephone'),
                                DB::raw('COUNT(*) as nombre_de_plis'),
                                DB::raw('MAX(date_changement) as derniere_modification'),
                                DB::raw('(SELECT status FROM pli_statuer_histories WHERE pli_statuer_histories.pli_id = plis.id ORDER BY date_changement DESC LIMIT 1) as dernier_statut')
                            )
                            ->where('user_id', Auth::id()) //  Filtre les plis envoyés par l'utilisateur
                            ->groupBy('destinataire_id') //  Regroupe les résultats pour éviter les répétitions
                            ->get();

                        $totalPlisUser = \App\Models\Pli::where('user_id', Auth::id())
                            ->count(); //  Compte tous les plis envoyés par l'utilisateur
                    @endphp


        {{-- Logique ------------------------------------------------------------------------------- --}}

                @extends('layout.master')

                @section('title', 'IvoirRp - Profil Utilisateur')

                @section('content')

              <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

                         {{-- Vos achirves --------------------------------------------}}


    {{--  --}}
    <div class="profile-container">

        {{-- Phot de couverture  et ajout pour les utiliosateur------------------------------------------------ --}}
          <div class="cover-photo" style="
                    height: 200px;
                    background: #d9ceff url('https://blog.delivery365.app/wp-content/uploads/2019/06/logistica-reversa-ecommerce.jpg') no-repeat center/cover;
                ">
        </div>
                {{-- / Phot de couverture ------------------------------------------------ --}}
                    <div class="profile-header">
                        <div style="background-color: #e7e8e9; ">
                                @if (Auth::user()->logo)
                                        <img src="{{ user()->logo }}" alt="Logo entreprise" class="profile-logo">
                                @else
                                    @php
                                        $initiales = strtoupper(substr(Auth::user()->abreviation, 0, 5));
                                    @endphp
                                    <div>
                                            <br>
                                            <div class="placeProfil">

                                                {{-- <p class="centered-text" style="vertical-align: middle !important; text-align:center !important">{{ $initiales }}</p> --}}
                                                <div style="border-radius: 50%; background: ; text-align:center; vertical-align:middle; font-weight:700; color:white">
                                                        {{ $initiales }}
                                                </div>
                                            </div>
                                        <br>
                                    </div>
                                @endif
                        </div>
                    {{-- Pour la descriptoion--------- --}}
                    <br> <br>
                    <h1>  {{ Str::upper(Auth::user()->name) }} </h1>
                    @if (is_null(Auth::user()->description))
                        <p class="" syle="color:red; font-style:italic">Aucune description renseigner pour votre entreprise
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#description">
                        <i class="bi bi-pencil-square"></i> <!-- Icône de modification -->
                        </button>
                    @else

                <div class="description-box p-3 border rounded bg-light">
                        <p class="text-dark fst-italic" style="text-align:center !important ">
                            {{ Auth::user()->description ?? 'Aucune description disponible' }}
                        </p>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#description">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </div>
                    <style>
                            .description-box {
                                transition: all 0.3s ease-in-out;
                            }
                            .description-box:hover {
                                background-color: #f0f0f0;
                                transform: scale(1.02);
                            }

                    </style>
                    @endif
                    {{-- Fin pour la description ------------------ --}}
                </div>

        {{-- -------------------------------------------------------------------- --}}


                {{-- Vos achirves --------------------------------------------}}

             <div>
                <h4> Mes archives  </h4>
                <h5>Archives du destinataire
                            {{ $pliArchive->first()->destinataire_name ?? 'Nom inconnu' }}
                </h5>
            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un utilisateur..." onkeyup="searchTable()">
                <div class="row" style="height:200px; overflow:auto" >
                                <table class="table" style="text-align: left !important" id="tableUser">
                                <tbody>
                                    @foreach ( $pliArchive  as $archivePli )
                                        <tr>

                                            <th scope="row"> {{ $loop->iteration }} </th>
                                            <td>
                                                      <a href="#" title="Voir l'archive" class="link link-dark" >
                                                        {{ $archivePli->code }}  |
                                                          {{ $archivePli->type ?? 'Non défini' }} |
                                                           {{-- {{ $pliArchive->first()->pliStatuerHistory->first()->statuer->optional()->name ?? 'Nom inconnu' }} --}}
                                                </a>
                                            </td>
                                            <td>


                                                 {{-- <a href="/client/archives-client?archive={{ $archivePli->destinataire_id }}" title="Voir l'archive" class="link link-dark" >
                                                        {{ $archivePli->code }}  |
                                                          {{ $archivePli->type ?? 'Non défini' }} |

                                                           {{-- {{ $pliArchive->first()->pliStatuerHistory->first()->statuer->optional()->name ?? 'Nom inconnu' }} --}}
                                                </a>
                                         </td>
                                                <style>
                                                    .link-dark{
                                                        text-decoration:none;
                                                    }
                                                    .link-dark:hover{
                                                        color:#2800aa !important;
                                                    }
                                                </style>
                                                <td>

                                                    <button class="btn btn-text"   data-bs-toggle="modal" data-bs-target="#accuseRetour{{ $archivePli->code}}" >
                                                          <span class="link link-dark">Ajouter l'accusé
                                                            {{-- </i> <i class="bi bi-eye"></i> --}}
                                                              {{-- <i class="bi bi-printer-fill"> --}}
                                                         </span>
                                                    </button>

                                                       <button class="btn btn-text"   data-bs-toggle="modal" data-bs-target="#accuseRetour{{ $archivePli->code}}" >
                                                          <span class="link link-dark">Voir l'accusé  </i> <i class="bi bi-eye"></i>
                                                            {{-- </i> <i class="bi bi-eye"></i> --}}
                                                         </span>
                                                    </button>


                                                    <button class="btn btn-text"    data-bs-toggle="modal" data-bs-target="#chemin{{ $archivePli->code}}">
                                                         <span class="link link-dark">Le Parcours <i class="bi bi-signpost"></i> </span>
                                                     </button>
                                                </td>
                                                <td>
                                                          {{ \Carbon\carbon::parse($archivePli->pliStatuerHistory->first()->date_changement)->format('d-m-Y h:i:s') ?? 'Non défini' }}
                                                </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                </table>
                        </div>
                    </div>
                {{-- Fin d'archives  -------------------------------------------------------------------------}}

        {{--  Pour les destinatairee  --}} <br><br>

            </div>
            {{-- Accusé de retour  --}}
<!-- Modal -->

              @foreach ( $pliArchive  as $archivePli )

                <div class="modal fade" id="accuseRetour{{ $archivePli->code }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> Accusé de retour N°{{ $archivePli->code }} </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Element ----------------------- --}}


                                    <div class="container">
                             @for ($i = 0; $i < 2; $i++) <!-- Répète deux fois la section -->
                                <!-- En-tête avec logo et informations principales -->
                                <div class="d-flex justify-content-between align-items-center my-1">
                                    <!-- Logo -->
                                    <div class="text-left">
                                        <img src="{{ asset('asset/Logo IRN.png') }}" alt="Logo" class="img-fluid" style="max-width: 80px;">
                                    </div>
                                    <!-- Informations à droite -->
                                    <div class="text-end">
                                        <p><strong>Date : </strong>{{ now()->format('d/m/Y') }}</p>
                                        <!--<p><strong>Référence : </strong> </p>-->
                                        <p><strong>Numero de suivi : </strong></p>
                                        <p><strong>Code du coursier : </strong></p>
                                    </div>
                                </div>

                                <!-- Titre principal -->
                                <div class="text-center my-1">
                                    <h3><strong>ACCUSÉ DE RETOUR</strong></h3>
                                </div>

                                <!-- Nom du client -->
                                <div class="text-center mt-2">
                                    <p><strong>Nom du Client : </strong></p>
                                </div>

                                <!-- Tableau des informations -->
                                <table class="table table-bordered table-sm mt-3">
                                    <thead>
                                        <tr>
                                            <th style="width: 33%;">Référence</th>
                                            <th style="width: 33%;">Nombre total de pli</th>
                                            <th style="width: 33%;">Raison d'annulation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td> </td>
                                            <td> </td></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Signature -->
                                <div class="row mt-4">
                                    <div class="col-md-6 text-center">
                                        <p><strong>Signature et cachet du Destinataire</strong></p>
                                        <div style="border: 1px solid #000; height: 60px;"></div>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <p><strong>Signature et cachet Direction IvoirRapid</strong></p>
                                        <div style="border: 1px solid #000; height: 60px;"></div>
                                    </div>
                                </div>

                                <!-- Note -->
                                <div class="mt-4">
                                    <p><strong>Note :</strong> Cet accusé de retour doit être conservé comme preuve de réception par le destinataire.</p>
                                </div>

                                <!-- Ligne en pointillés entre les sections -->
                                @if ($i == 0)
                                <div class="ligne-dotee my-3"></div>
                                @endif
                                @endfor
                            </div>


                        {{-- Fin element ------------------------------ --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                    </div>
                </div>
                </div>

            @endforeach



                {{-- Modal our l'accuser de retour---------------- --}}








                    <!-- Button trigger modal -->
{{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#accuseRetour">
  Launch demo modal
</button> --}}

<!-- Modal pour le parcourq -------------------->

              @foreach ( $pliArchive  as $archivePli )

                <div class="modal fade" id="chemin{{ $archivePli->code }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> Parcours du pli {{ $archivePli->code }} </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Element ----------------------- --}}

                            <div style="display:flex; justify-content:center; gap:4px !important" class="parcours">
                                    <div>
                                        <p style="justify-content: center"><i class="bi bi-person-raised-hand" style="font-size:100px"></i></p>
                                        <p>   créé le {{ $archivePli->created_at ?? '' }}</p>
                                    </div>

                                      <div style="width:100px;justify-content: center">
                                        <p style="justify-content: center"> <br>
                                        <br>
                                        <br>
                                        <br> <i class="bi bi-arrow-90deg-right" style="width:100px !important" ></i> </p>
                                        {{-- <p>   -------------  </p> --}}
                                    </div>


                                    <div>
                                         <p>
                                            <i class="bi bi-arrow-right"  style="font-size:100px" ></i>
                                        </p>
                                        <p>  Ramassé le {{ $archivePli->first()->date_attribution_ramassage  }}</p>
                                    </div>

                                            <div style="width:100px;justify-content: center">
                                                <p style="justify-content: center"> <br>
                                                <br>
                                                <br>
                                                <br> <i class="bi bi-arrow-90deg-right" style="width:100px !important" ></i> </p>
                                                {{-- <p>   -------------  </p> --}}
                                            </div>


                                    <div>
                                               <p> <i class="bi bi-check-circle-fill"  style="font-size:100px" ></i></p>
                                             <p>
                                                @if ($archivePli->pliStatuerHistory->first()->statuer_id == 3 )
                                                    <i class="bi bi-check-circle"></i>
                                                        Déposé le {{ \Carbon\carbon::parse($archivePli->pliStatuerHistory->first()->date_changement)->format('d-m-Y h:i:s') ?? 'Non défini' }}
                                                @endif
                                                @if ($archivePli->pliStatuerHistory->first()->statuer_id  == 4 )

                                                        Réfusé le  {{ \Carbon\carbon::parse($archivePli->pliStatuerHistory->first()->date_changement)->format('d-m-Y h:i:s') ?? 'Non défini' }}

                                                @endif
                                                @if ($archivePli->pliStatuerHistory->first()->statuer_id  == 5)
                                                    {{ \Carbon\carbon::parse($archivePli->pliStatuerHistory->first()->date_changement)->format('d-m-Y h:i:s') ?? 'Non défini' }}
                                                @endif
                                        </p>


                                    </div>

                            </div>






                        {{-- Fin element ------------------------------ --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                    </div>
                </div>
                </div>

            @endforeach



                {{-- Modal pour le chemin ---------------- --}}






























    <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
                text-align: center;
            }

            .profile-container {
                max-width: 80%;
                margin: 20px auto;
                background: white;
                padding: 20px;
                border-radius: 8px;
            }


            .profile-logo {
                width: 100px;
                border-radius: 50%;
                border: 3px solid white;
            }

                    .slogan {
                        font-style: italic;
                        color: #777;
                    }

                    .company-info {
                        background: #e3e3e3;
                        padding: 10px;
                        border-radius: 5px;
                        text-align: justify !important;
                        gap:10px;
                    }

                    .gallery img {
                        width: 100px;
                        margin: 5px;
                    }

                    .contact-btn {
                        background: #004A99;
                        color: white;
                        padding: 10px;
                        border: none;
                        cursor: pointer;
                        margin-top: 10px;
                    }

                    .message {
                        background: white;
                        padding: 20px;
                        margin: 20px auto;
                        border-radius: 8px;
                    }

                    form input, form textarea {
                        width: 100%;
                        padding: 10px;
                        margin: 5px 0;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                    }

                    button {
                        background: #004A99;
                        color: white;
                        padding: 10px;
                        border: none;
                        cursor: pointer;
                    }


                    .company-info {
                                display: flex;
                                justify-content: space-between; /* Répartit les colonnes uniformément */
                                flex-wrap: wrap;
                                background: #f8f9fa;
                                padding: 10px;
                                border-radius: 10px;
                                gap: 8px;
                                font-size: 14px;
                            }

                            .item {
                                flex: 1; /* Permet un ajustement dynamique */
                                background: white;
                                padding: 5px;
                                border-radius: 8px;
                                box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
                            }

                            h5 {
                                font-weight: bold;
                                color: #004A99;
                                display: flex;
                                align-items: center;
                                gap: 10px;
                            }

                            p {
                                display: flex;
                                align-items: center;
                                gap: 5px;
                                margin: 5px 0;
                            }

                    </style>



                     <style>
                                .placeProfil{
                                        width:100px;
                                        height:100px;

                                        text-align:center;
                                         vertical-align:middle;
                                          background:#60605f;
                                           margin:auto;
                                           border-radius: 50%;
                                           color:white;
                                     }

                                     .placeProfil {
                                            display: flex;
                                            justify-content: center; /* Centre horizontalement */
                                            align-items: center; /* Centre verticalement */
                                            height: 100px; /* Ajuste selon le besoin */
                                            text-align: center;
                                        }

                                        .centered-text {
                                            width: 80%;
                                            margin: auto;
                                            font-size: 24px; /* Ajuste la taille si besoin */
                                            font-weight: bold;
                                        }
                                    svg{
                                        display:none;
                                    }

                            </style>

                                   {{-- Scrpt pour voir les les images --}}

                        <script>
                        function previewImage(event, id) {
                            let reader = new FileReader();
                            reader.onload = function(){
                                let output = document.getElementById('preview_' + id);
                                output.src = reader.result;
                                output.classList.remove('d-none');
                            };
                            reader.readAsDataURL(event.target.files[0]);
                        }
                        </script>


            <!--  Script de recherche dynamique dans le tb des utilisateurs -->
                            <script>
                                function searchTable() {
                                    let input = document.getElementById("searchInput").value.toLowerCase();
                                    let table = document.getElementById("tableUser");
                                    let rows = table.getElementsByTagName("tr");

                                    for (let i = 0; i < rows.length; i++) {
                                        let cells = rows[i].getElementsByTagName("td");
                                        let match = false;

                                        for (let j = 0; j < cells.length; j++) {
                                            if (cells[j].innerText.toLowerCase().includes(input)) {
                                                match = true;
                                                break;
                                            }
                                        }

                                        rows[i].style.display = match ? "" : "none";
                                    }
                                }
                            </script>


@endsection
