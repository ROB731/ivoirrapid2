




@extends('layout.master')

@section('title', 'IvoirRp - Profil Utilisateur')

@section('content')







-----------------------------------------
{{-- @extends('layouts.master')

@section('title', 'IvoirRp - ')

@section('content') --}}

        @if(Auth::check())
            <p>Bienvenue, {{ Auth::user()->name }} sur votre profil!</p>
        @else
            <p>Veuillez vous connecter.</p>
        @endif


        @php
            if(App::environment('local'))
                {
                    echo'Vous ête en mode local';
                }
        @endphp






{{-- ------------------------------------------------------- --}}

            @php
                use Illuminate\Support\Facades\Storage;

                if (request()->has('add-photos') && Auth::check()) {
                    request()->validate([
                        'photo_1' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                        'photo_2' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                        'photo_3' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                        'photo_4' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                        'photo_5' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                    ]);

                    //  Récupérer le nom de l'utilisateur et le formater correctement
                    $userName = str_replace(' ', '_', strtolower(Auth::user()->name));
                    $directory = "users/{$userName}/photos"; // Dossier propre à l'utilisateur

                    //  Vérifier si le dossier existe, sinon le créer
                    if (!Storage::disk('public')->exists($directory)) {
                        Storage::disk('public')->makeDirectory($directory);
                    }

                    $updates = [];

                    //  Enregistrement des photos avec un nom clair et unique
                    for ($i = 1; $i <= 5; $i++) {

                        if (request()->hasFile('photo_' . $i)) {
                            $file = request()->file('photo_' . $i);
                            $fileName = time() . '_' . str_replace(' ', '_', strtolower($file->getClientOriginalName()));
                            $photoPath = $file->storeAs($directory, $fileName, 'public');
                            $updates['photo_' . $i] = $photoPath;
                            // var_dump($updates);
                        }
                    }


                    //  Mise à jour des photos dans la base de données si des fichiers ont été envoyés
                    if (!empty($updates)) {
                        Auth::user()->update($updates);
                        echo '<script>alert("Mise à jour des photos effectuée")</script>';
                    } else {
                        echo '<script>alert("Aucune photo sélectionnée")</script>';
                    }
                }
                @endphp

{{-- -En haut pour les photos--------------------- --}}

            {{-- {{ dd($_POST) }} --}}

             @php
             if (isset($_POST['okInfo'])) {
                Auth::user()->update([
                    'Direction_1_Nom_et_Prenoms' => request('Direction_1_Nom_et_Prenoms'),
                    'Direction_2_Nom_et_Prenoms' => request('Direction_2_Nom_et_Prenoms'),
                    'Direction_3_Nom_et_Prenoms' => request('Direction_3_Nom_et_Prenoms'),
                    'Direction_1_Contact' => request('Direction_1_Contact'),
                    'Direction_2_Contact' => request('Direction_2_Contact'),
                    'Direction_3_Contact' => request('Direction_3_Contact'),
                    'Commune' => request('Commune'),
                    'Quartier' => request('Quartier'),
                    'Rue' => request('Rue'),
                    'AdresseMail' => request('AdresseMail'),
                    'Adresse' => request('Adresse'),
                ]);

                echo('<script>alert("Mise à jour effectuée")</script>');
            }
            else {
                // echo('<script>alert("Erreur : Il semble que la variable de validation n est pas ")</script>');
            }
            @endphp
    {{-- Logique enregistrement de description ------------ --}}

       @php
             // if (isset($_POST['save-description']) && !empty($_POST['description-user'])) {
            if (isset($_POST['save-description']) && !empty($_POST['description-user'])) {

                $userDescription = $_POST['description-user'];

                    // Vérifier si l'utilisateur est bien authentifié avant la mise à jour
                    if (Auth::check()) {
                     Auth::user()->update(['description' => $userDescription]);

                        echo '<script>alert("Description Ajoutée");</script>';
                        } else {
                         echo '<script>alert("Erreur : utilisateur non authentifié");</script>';
                                        }
                        } else {
                              // echo '<script>alert("Impossible d\'ajouter la description, une erreur est survenue");</script>';
                        }
                 @endphp

    {{-- Deuxieme logique pour le formulaire --------------------------------------- --}}

{{-- -------------------------------------------------------------------------------------------------- --}}

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
                                          background:#3c3c3b;
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

                            </style>

{{-- -------------------------------------------------------------------------- --}}

    <div class="profile-container">

        {{-- Phot de couverture  et ajout pour les utiliosateur------------------------------------------------ --}}
        <div class="cover-photo" style="height: 150px; background:#777">


             @php
                    $allUsers = \App\Models\User::all();
              @endphp

            {{-- <input type="text" list="usersAll" >
            <datalist id="usersAll">

                    <select name="" id="">
                        @foreach ( $allUsers as $allUser )
                            <option value="{{ $allUser->name }}"> <a href="#">{{ $allUser->name }} </a> </option>
                        @endforeach
                    </select>

            </datalist> --}}

            <div style="text-align:left !important; color:white">
                    <p> <button class="btn btn-text" style="color:white"> Nombre d'utilisateur actuel : {{ count($allUsers) }}  </button></p>

            </div>


        </div>




        {{--  Ajout poue tus les utilisateurs ------------------ --}}
    {{-- / Phot de couverture ------------------------------------------------ --}}

        <div class="profile-header">

            <div style="background-color: #cdcdcd; ">
                    @if (Auth::user()->logo)
                             <img src="{{ user()->logo }}" alt="Logo entreprise" class="profile-logo">
                    @else
                        @php
                            $initiales = strtoupper(substr(Auth::user()->abreviation, 0, 4));
                        @endphp
                        <div>


                                <br>
                                <div class="placeProfil">
                                    <p class="centered-text">{{ $initiales }}</p>
                                </div>
                            <br>
                        </div>
                    @endif
            </div>

            {{-- Pour la descriptoion--------- --}}
            <br> <br>
            <h1>  {{ Str::upper(Auth::user()->name) }} </h1>

            @if (is_null(Auth::user()->description))
                <p class="" syle="color:red; font-style:italic">Au cune description renseigner pour votre entreprise
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
         <p style="text-align: right">
                <i>Modifier cette section</i>
                     <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#infoUser">
                        <i class="bi bi-pencil-square"></i> <!-- Icône de modification -->
                    </button>
            </p>

        <div class="company-info" style="display:flex">

                 <div class="item">
                         <h5>Contact Direction </h5>
                        <p>Direction 1 :  <strong>{{ Auth::user()->Direction_1_Nom_et_Prenoms ?? 'Non défini' }} ( {{ Auth::user()->Direction_1_Contact ?? 'Non défini' }}) </strong> </p>
                        <p>Direction 2 : <strong>{{ Auth::user()->Direction_2_Nom_et_Prenoms ?? 'Non défini' }} ( {{ Auth::user()->Direction_2_Contact ?? 'Non défini' }})</strong> </p>
                        <p>Direction 3 :  <strong>{{ Auth::user()->Direction_3_Nom_et_Prenoms ?? 'Non défini' }} ( {{ Auth::user()->Direction_3_Contact ?? 'Non défini' }})</strong> </p>
                        <p>Téléphone :</p>
                </div>

                <div class="item">
                        <h5>Localisation</h5>
                        <p><strong>📍 Commune :</strong> {{ Str::upper(Auth::user()->Commune) ?? 'Non défini' }} </p>
                        <p><strong>📧 Quartier :</strong>  {{ Auth::user()->Quartier }} </p>
                        <p><strong>☎️ Rue :</strong>  {{ Auth::user()->Rue }} </p>
                        <p> <strong>Adresse Mail</strong> :  {{ Auth::user()->email }}</p>

                </div>
                    <div class="item">
                        <h5>Information Entreprise </h5>
                        <p>N°CC : <strong>{{ Auth::user()->Compte_contribuable ?? 'Non défini' }}</strong>  </p>
                        <p>RCCM:  <strong>{{ Auth::user()->RCCM ?? 'Non défini' }}</strong>  </p>
                        <p>Cellulaire : <strong>{{ Auth::user()->Cellulaire ?? 'Non défini' }}</strong> </p>
                        <p>Téléphone : <strong>{{ Auth::user()->Telephone ?? 'Non défini' }}</strong> </p>

                    </div>
        </div>

        <div class="gallery">
            <h2>Notre Galerie</h2>

                {{-- Fin de formulaire ------------------------------------- --}}


                    {{-- Gallery photo ------------------------- --}}
            <div class="container mt-4">
                    <h3><i class="bi bi-images"></i> Galerie des photos
                            {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formPhotos">
                                    Mettre à jour les photos
                            </button> --}}
                            <button type="button" class="btn btn-text" data-bs-toggle="modal" data-bs-target="#formPhotos">
                                <i class="bi bi-camera"></i> Mettre à jour les photos
                            </button>

                    </h3>
    <div class="row">

        @for ($i = 1; $i <= 5; $i++)
            @if(Auth::user()->{'photo_' . $i})
                <div class="col-md-4 mb-3">
                    <img src="{{ asset('storage/' . Auth::user()->{'photo_' . $i}) }}"
                         class="img-thumbnail gallery-image"
                         data-bs-toggle="modal"
                         data-bs-target="#photoModal{{ $i }}">
                </div>

                <!-- Modal pour affichage en plein écran -->
                <div class="modal fade" id="photoModal{{ $i }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Photo {{ $i }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="{{ asset('storage/' . Auth::user()->{'photo_' . $i}) }}" class="img-fluid"  style="width:80%">
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        @endfor


    </div>
</div>

                    {{-- Fin de gallery photos ------------------- --}}


                <p style="text-align"> <i> A bientôt pour plus d'options</i> </p>

            </div>

    <script src="script.js"></script>

    {{-- ----------------Le pays des modfals-------------------------------------------------- --}}
    {{-- ----------------------------------- --}}

            {{-- Debut formulaire pour le formulaire photo --}}

                    <!-- Button trigger modal -->


                            <!-- Modal -->
                            <div class="modal fade" id="formPhotos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                                   {{-- Formulaire pour recuperer les photos  ----------------------}}
                                        <form method="POST"  enctype="multipart/form-data" class="form-control">
                                            @csrf
                                            <div class="row" style="text-align: center">
                                                <h5><i class="bi bi-images"></i> Ajout de photos</h5>
                                            @for ($i = 1; $i <= 5; $i++)
                                                    <div class="col-md-2 text-center">
                                                        <label for="photo_{{ $i }}" class="btn btn-outline-success upload-label">
                                                            <i class="bi bi-plus-circle"></i> Ajouter photo {{ $i }}
                                                        </label>
                                                        <input type="file" id="photo_{{ $i }}" name="photo_{{ $i }}" class="d-none"
                                                            accept="image/png, image/jpeg, image/jpg, image/gif"
                                                            onchange="previewImage(event, {{ $i }})">
                                                        <img id="preview_{{ $i }}" class="img-thumbnail mt-2 d-none" width="100">
                                                    </div>
                                                @endfor

                                            </div>

                                            <button type="submit" class="btn btn-primary mt-3" name="add-photos">
                                                <i class="bi bi-upload"></i> Envoyer
                                            </button>
                                        </form>


{{-- Pour l'ajout du formulaire de photo --------------------------------------- --}}

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                                </div>
                                </div>
                            </div>
                            </div>

     {{-- / Modal pour le formulaire de photos --------------------------- --}}

          <!-- Inclure Bootstrap Icons dans ton projet -->
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
                            </p>
                        <!-- Modal -->
                        <div class="modal fade" id="description" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Modifier votre description</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                    <form method="POST" class="form-control d-flex align-items-center gap-3">
                                        @csrf
                                            <label for="description-user" class="me-2">Votre description :</label>
                                            <input type="text" name="description-user" placeholder="Nous sommes..." class="form-control">
                                            <button type="submit" class="btn btn-primary" name="save-description">Sauver</button>
                                    </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                {{-- <button type="button" class="btn btn-primary">Understood</button> --}}
                            </div>
                            </div>
                        </div>
                        </div>


        {{-- Fin du modal des decription--------------------- --}}

        {{-- Modal pour les informations personnelles ------------------------------------- --}}
                                        <!-- Modal -->
                 <div class="modal fade" id="infoUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                         <div class="modal-content">
                             <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Modifier votre description</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                             </div>
                     <div class="modal-body">
                                            {{-- Form ----------------------------- --}}

<form method="POST"  class="form-control">
    @csrf
    <div class="row">
        <!-- Contact Direction -->
        <div class="col-md-6">
            <h5><i class="bi bi-person-square"></i> Contact Direction</h5>

            <label>Direction 1 :</label>
            <input type="text" name="Direction_1_Nom_et_Prenoms" class="form-control"  value="{{ old('Direction_1_Nom_et_Prenoms', Auth::user()->Direction_1_Nom_et_Prenoms) }}">
            <input type="text" name="Direction_1_Contact" class="form-control" value="{{ old('Direction_1_Contact', Auth::user()->Direction_1_Contact) }}" >

            <label>Direction 2 :</label>
            <input type="text" name="Direction_2_Nom_et_Prenoms" class="form-control"  value="{{ old('Direction_2_Nom_et_Prenoms', Auth::user()->Direction_2_Nom_et_Prenoms) }}">
            <input type="text" name="Direction_2_Contact" class="form-control"value="{{ old('Direction_2_Contact', Auth::user()->Direction_2_Contact) }}" >

            <label>Direction 3 :</label>
            <input type="text" name="Direction_3_Nom_et_Prenoms" class="form-control"  value="{{ old('Direction_3_Nom_et_Prenoms', Auth::user()->Direction_3_Nom_et_Prenoms) }}">
            <input type="text" name="Direction_3_Contact" class="form-control" value="{{ old('Direction_3_Contact', Auth::user()->Direction_3_Contact) }}">
        </div>

        <!-- Localisation -->
        <div class="col-md-6">
            <h5><i class="bi bi-geo-alt-fill"></i> Localisation</h5>

            <label>📍 Commune :</label>
            <input type="text" name="Commune" class="form-control" value="{{ old('Commune', Auth::user()->Commune) }}" >

            <label>📧 Quartier :</label>
            <input type="text" name="Quartier" class="form-control"value="{{ old('Quartier', Auth::user()->Quartier) }}"">

            <label>☎️ Rue :</label>
            <input type="text" name="Rue" class="form-control" value="{{ old('Rue', Auth::user()->Rue) }}">

            <label>🏠 Adresse :</label>
            <input type="text" name="Adresse" class="form-control" value="{{ old('Adresse', Auth::user()->Adresse) }}">

            <label>Adresse Mail :</label>
            <input type="text" name="AdresseMail" class="form-control" value="{{ old('email', Auth::user()->email) }}">
        </div>
    </div>

    <button  class="btn btn-primary mt-3" name="okInfo" value="okInfo" >
        <i class="bi bi-save" ></i> Sauvegarder
    </button>
    {{-- <button type="submit" class="btn btn-primary" name="save-description">Sauver</button> --}}


</form>

<!-- Ajout de Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

            <!-- Ajout de Bootstrap Icons -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

                                {{-- Form---------------------- --}}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                {{-- <button type="button" class="btn btn-primary">Understood</button> --}}
                            </div>
                            </div>
                        </div>
                        </div>




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



@endsection



{{-- ------------------------- --}}



