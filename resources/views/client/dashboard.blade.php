@extends('layout.master')

@section('title', 'IvoirRp - Client Dashboard')

@section('content')




    <div style="text-align:right; background:#eef1ff; margin-bottom:5px; margin-right:5px" >
        <h6>Action rapide</h6>
        <a href="{{ url('client/add-pli') }}" class="btn btn-primary btn-sm ms-2">
            {{-- <i class="fas fa-envelope"></i> --}}
            <i class="fas fa-file-alt"></i> <!-- Document qui peut symboliser un pli -->
            {{-- <i class="fas fa-inbox"></i>  --}}
            Ajouter un Pli
        </a>
        <br>
    </div>

    <div class="container-fluid px-4">
        <h1 class="mt-4">Tableau de bord</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"></li>
        </ol>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">Vos destinataires</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">{{ $totalDestinataires }}</a>
                        <div class="medium text-white"><i class="fas fa-user-check"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">Total plis</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">{{ $totalPlis }}</a>
                        <div class="medium text-white"><i class="fas fa-archive"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4"> <!-- ✅ Changement de couleur -->
                    <div class="card-body">Plus de destinantaires</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">{{ $nombreDestinataires }}</a>
                        <div class="medium text-white"><i class="fas fa-gift"></i></div> <!-- ✅ Icône "cadeau" pour indiquer la gratuité -->
                    </div>
                </div>
            </div>

            {{-- <h4 class="mt-4">📊 Statistiques des plis créés</h4> --}}

            <h4 class="mt-4">📊 Statistiques des plis créés</h4>

            {{-- <div class="row">
                <div class="col-md-3">
                    <div class="card bg-info text-white text-center p-3">
                        <h5>Aujourd'hui</h5>
                        <h2>{{ $aujourdhui }}</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white text-center p-3">
                        <h5>Hier</h5>
                        <h2>{{ $hier }}</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white text-center p-3">
                        <h5>Semaine dernière</h5>
                        <h2>{{ $semaineDerniere }}</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white text-center p-3">
                        <h5>Mois dernier</h5>
                        <h2>{{ $moisDernier }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

            <div class="row">
                <div class="col-md-3">
                    <div class="card bg-info text-white text-center p-2"> <!-- ✅ Réduction du padding -->
                        <small>Aujourd'hui</small> <!-- ✅ Taille de texte ajustée -->
                        <h4>{{ $aujourdhui }}</h4> <!-- ✅ Réduction du titre -->
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white text-center p-2">
                        <small>Hier</small>
                        <h4>{{ $hier }}</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white text-center p-2">
                        <small>Semaine dernière</small>
                        <h4>{{ $semaineDerniere }}</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white text-center p-2">
                        <small>Mois dernier</small>
                        <h4>{{ $moisDernier }}</h4>
                    </div>
                </div>
            </div>

    {{-- Fin recap --}}

        {{-- <h4 class="mt-4">📦 Derniers plis créés</h4>   <!-- Le tableau --->
        <table class="table table-bordered" style="width:90%;margin:auto">
            <thead class="bg-primary text-white">
                <tr>
                    <th>N°</th> <!-- ✅ Ajout de la colonne numéro -->
                    <th>Nom du destinataire</th>
                    <th>Date de création</th> --}}
                    {{-- <th>Numéro de suivi</th> --}}
                {{-- </tr>
            </thead>
            <tbody>
                @foreach($derniersPlis as $index => $pli) <!-- ✅ Utilisation de l'index pour numéroter les lignes -->
                    <tr>
                        <td>{{ $index + 1 }}</td> <!-- ✅ Affichage du numéro de ligne -->
                        <td>{{ $pli->destinataire->name ?? 'Non spécifié' }}</td> --}}
                        {{-- <td>{{ $pli->created_at->format('d/m/Y H:i') }}</td>
                        {{-- <td>{{ $pli->numero_suivi }}</td> --}}
                    {{-- </tr>
                @endforeach
            </tbody>
        </table> --}}

                    <h4 class="mt-4">📦 Derniers plis créés par vous</h4>
            <table class="table table-bordered">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>N°</th>
                        <th>Nom du destinataire</th>
                        <th>Date de création</th>
                        <th>Numéro de suivi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($derniersPlis as $index => $pli)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $pli->destinataire->name ?? 'Non spécifié' }}</td>
                            <td>{{ $pli->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $pli->numero_suivi }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @section('action-rapid')
                {{-- Pour le bouton action rapide --}}

                <a href="{{ route('client.add-pli') }}" title="Ajouter un  Pli" class="btn btn-gradient shadow-lg add-pli-btn"
                style="position: fixed; right: 20px; top: 50%; transform: translateY(-50%);
                       z-index: 1000; padding: 12px; border-radius: 50px; display: flex; align-items: center;
                       justify-content-center; width: 50px; height: 50px; font-size: 20px; transition: all 0.4s ease-in-out;">
                 <i class="fas fa-plus"></i> <span class="pli-text">Ajouter un pli</span>
             </a>

             <style>
             /* 🌟 Style du bouton flottant */
             .btn-gradient {
                 background: linear-gradient(45deg, #ff5733, #04c178);
                 color: white;
                 transition: 0.4s ease-in-out;
                 overflow: hidden;
                 white-space: nowrap;
             }

             /* 🚀 Transformation en rectangle au survol */
             .add-pli-btn:hover {
                 width: 180px; /* ✅ Le bouton s’étend pour afficher le texte complet */
                 border-radius: 8px; /* ✅ Passe d’un cercle à un rectangle arrondi */
                 padding-left: 15px;
                 padding-right: 15px;
             }

             /* 🎯 Apparition progressive du texte */
             .pli-text {
                 opacity: 0;
                 display: inline-block;
                 width: 0;
                 transition: opacity 0.4s ease-in-out, width 0.4s ease-in-out;
             }

             .add-pli-btn:hover .pli-text {
                 opacity: 1; /* ✅ Le texte devient visible */
                 width: auto;
             }
             </style>
        @endsection









@endsection  {{-- Truly the end --}}


