@extends('layouts.master')

@section('title', 'IvoirRp - Admin')

@section('content')


@include('admin.dashbord_stat')

@include('admin.dashbord_admin_views_client')

@include('admin.coorection_destinataire')

    <div class="container-fluid px-4" style="justify-content:center">
        <h1 class="mt-4">Tableau de bord</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"></li>
        </ol>

        <h3 class="mt-4">Récapitulatif général </h3>
        <hr>

        <div class="row">
            {{-- Statistiques générales --}}
            <div class="col-xl-3 col-md-6" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <div class="card bg-info text-white mb-4">
                    <div class="card-body">Total Clients</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">{{ $totalUsers }}</a>
                        <div class="medium text-white"><i class="fas fa-address-book"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4" data-bs-toggle="modal" data-bs-target="#destinataires">
                    <div class="card-body">Total Destinataires</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">{{ $totalDestinataires }}</a>
                        <div class="medium text-white"><i class="fas fa-user-check"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">Total Coursiers</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">  {{ $totalCoursiers }} </a>
                        <div class="medium text-white"><i class="fas fa-user-check"></i></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- EPlis apres 72h  --}}

            @php
                use Carbon\Carbon;

             //  Correction du délai de 72 heures
                $limite72h = Carbon::now()->subHours(72);
              $plisNonStatues =  \App\Models\Pli::whereHas('attributions', function ($query) {
                        $query->whereNotNull('coursier_depot_id'); // ✅ Vérifie qu'un coursier a été assigné
                    }) // ✅ Ajout de la parenthèse fermante ici
                    ->whereDoesntHave('pliStatuerHistory') // ✅ Filtre uniquement les plis sans statut
                    ->whereHas('attributions', function ($query) {
                        $query->where('date_attribution_depot', '<', Carbon::now()->subHours(72)); // ✅ Vérifie les attributions datant de plus de 72 heures
                    })
                    ->count();

                       $plisNonStatuesListes = \App\Models\Pli::whereHas('attributions', function ($query) {
                                $query->whereNotNull('coursier_depot_id'); // ✅ Vérifie qu'un coursier a été assigné
                            }) // ✅ Ajout de la parenthèse fermante ici
                            ->whereDoesntHave('pliStatuerHistory') // ✅ Filtre uniquement les plis sans statut
                            ->whereHas('attributions', function ($query) use ($limite72h) {
                                $query->where('date_attribution_depot', '<', $limite72h);
                            })
                            ->get();

                 @endphp

             <div class="col-xl-3 col-md-6" data-bs-toggle="modal" data-bs-target="#exampleModal72" >
                    <div id="alertCard" class="card bg-danger text-white mb-4" style="cursor: pointer;">
                        <div class="card-body">Plis ramassés sans statut (+72H)</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="#" id="alertCount">{{ number_format($plisNonStatues) }}</a>
                            <div class="medium text-white"><i class="fas fa-exclamation-circle"></i></div>
                        </div>
                    </div>
                </div>


        </div>



{{-- Nouvelle ajout ----------------------------15-05-2025--------------------------- --}}


        {{-- Pour l'alert----- --}}

        {{-- Pour faire clignoter l'alert--------- --}}


                {{-- Pour le modal  du clignotant --}}




            <!-- ✅ Ajout du son d'alerte -->
            <audio id="alertSound" muted="false">
    <source src="https://www.myinstants.com/media/sounds/alarm.mp3" type="audio/mpeg">
</audio>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let nombrePlis = parseInt(document.getElementById("alertCount").innerText);
        let alertCard = document.getElementById("alertCard");
        let alertSound = document.getElementById("alertSound");

        function jouerAlerte() {
            let count = 0;
            if (nombrePlis > 0) {
                // ✅ Autoriser le son sur certains navigateurs
                alertSound.play().catch(() => {
                    document.body.addEventListener("click", () => alertSound.play(), { once: true });
                });

                // ✅ Joue l'alerte sonore 10 fois avec un délai
                let repeatSound = setInterval(() => {
                    if (count < 10) {
                        alertSound.play();
                        count++;
                    } else {
                        clearInterval(repeatSound);
                    }
                }, 3000);
            }
        }

        // ✅ Activation au chargement
        jouerAlerte();

        // ✅ Effet de clignotement
        setInterval(() => {
            if (nombrePlis > 0) {
                alertCard.style.visibility = (alertCard.style.visibility === "hidden") ? "visible" : "hidden";
            }
        }, 500);
    });
</script>

{{-- Modal pour le pli non ramassé --}}

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let alertCard = document.getElementById("alertCard");

        alertCard.addEventListener("click", function() {
            // ✅ Récupération des données dynamiques via AJAX ou Blade
            let coursierNom = "Nom du coursier"; // 🔹 À récupérer dynamiquement
            let numPlis = "123, 456, 789"; // 🔹 À récupérer dynamiquement
            let destinataires = "Jean Dupont, Alice Martin"; // 🔹 À récupérer dynamiquement

            // ✅ Mise à jour des éléments du modal
            document.getElementById("coursierNom").innerText = coursierNom;
            document.getElementById("numPlis").innerText = numPlis;
            document.getElementById("destinataires").innerText = destinataires;

            // ✅ Affichage du modal
            let modal = new bootstrap.Modal(document.getElementById("modalDetails"));
            modal.show();
        });
    });
</script>

         <h3 class="mt-4">Statistique de Traitement des plis et Moyenne, Variation  </h3>
         <hr>
                @yield('stat-moy-day')

{{-- Inclusion ---------------------------------- --}}

@endsection

{{-- Pour le graphique --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

