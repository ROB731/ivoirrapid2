
@extends('layouts.master')

@section('title', 'Plis Supprimés')

@section('content')


<h2>Liste des plis supprimer par les clients</h2>
<h5 style="font-style:italic">Depuis le 01/05/2025 Jusqu'a ce jour</h5>
<hr>
<div class="table-container">
    Nombre de supprimer  : {{ $totalPliesSupprimes }}

    <form method="GET" action="{{ route('admin.plis.plis_trashed') }}" class="mb-3 d-flex gap-2">
        <input type="text" name="search" class="form-control" placeholder="Rechercher un pli supprimé..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Rechercher</button>
        <a href="{{ route('admin.plis.plis_trashed') }}" class="btn btn-secondary">Vider</a> <!-- 🔥 Bouton pour réinitialiser -->
    </form>

    <table class="table ">

            <thead>
                <tr>
                    <th style="max-width:2px">N°</th>

                    <th style="min-width: 200px;">Expéditeur</th> <!--  Largeur minimale -->
                    <th style="min-width: 200px;">Destinataire</th>
                    <th style="min-width: 150px;">Zone Expéditeur</th>
                    <th style="min-width: 150px;">Zone Destinataire</th>
                    <th style="min-width: 250px;">Numéro de suivi</th>
                    <th style="min-width: 160px;">Date de suppression</th>
                    <th style="width: 90px;">Action</th> <!-- Bouton d'action plus compact -->

                </tr>
            </thead>
            <tbody>

                @if($pliesSupprimes->isNotEmpty())

                @foreach ($pliesSupprimes as $historique)
                @php
                    $ancienneValeur = json_decode($historique->ancienne_valeur, true);
                @endphp
                <tr class="pli-row" data-info="{{ json_encode($ancienneValeur) }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                       <small style="color:red"><i>Supprimé par </i></small><br>
                        {{ $ancienneValeur['user_name'] }}
                    </td>
                    <td>{{ $ancienneValeur['destinataire_name'] }}</td>
                    <td>{{ $ancienneValeur['user_Zone'] }}</td>
                    <td>{{ $ancienneValeur['destinataire_zone'] }}</td>
                    <td>{{ $ancienneValeur['code'] }}</td>
                    <td>
                        <span id="modalDateAction" data-date="{{ $historique->date_action }}">{{ $historique->date_action }}</span>
                    </td>
                    <td>
                        <a href="{{ route('plis.restaurer', $historique->pli_id) }}" class="btn btn-success btn-sm">
                            Restaurer
                        </a>
                    </td>
                </tr>
            @endforeach

                @else
                        <div class="alert alert-warning text-center">
                            <strong>📌 Aucun pli supprimé trouvé.</strong> Veuillez vérifier votre recherche ou réessayer plus tard.
                        </div>
                @endif
            </tbody>
        </table>


<div style="width:4px !important; display: inline">
    @if($pliesSupprimes instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="d-flex justify-content-center mt-3" >
      <p >  {{ $pliesSupprimes->links() }}</p>
    </div>
@endif
</div>


</div>

{{-- Style --}}

<style>

.table-container {
    overflow: hidden; /* Cache les scrollbars */
    max-width: 100%;
}

.table-responsive::-webkit-scrollbar {
    display: none; /* Supprime les scrollbars sur Chrome et Safari */
}

.table-responsive {
    -ms-overflow-style: none; /* Supprime les scrollbars sur Edge et IE */
    scrollbar-width: none; /* Supprime les scrollbars sur Firefox */
}


.table {
    background-color: #e0e2e3; /* 🔥 Fond légèrement grisé */
    border-radius: 8px; /* 🔥 Bords arrondis */
}

.table th {
    background-color: #d5d1d1; /* 🔥 Gris plus foncé pour l’en-tête */
    font-weight: bold;
}


svg{
    display:none;
}

.table {
    width: 95%;
    margin:auto;
    table-layout: auto; /* 🔥 Permet aux colonnes de s’adapter au contenu */
}

.table th,
.table td {
    padding: 10px;
    white-space: nowrap; /* Empêche le texte de casser la ligne */
    overflow: hidden;
    text-overflow: ellipsis; /* Ajoute "..." si le texte est trop long */
}


.table th,
.table td {
    overflow: hidden;
    white-space: nowrap; /* Empêche le texte de s'étendre trop */
    text-overflow: ellipsis; /* Ajoute "..." si le texte est trop long */
    padding: 4px;
}

        .pagination .page-item .page-link {
            font-size: 14px; /* Réduit la taille du texte */
            padding: 5px 10px; /* Ajuste l’espace intérieur */
        }

        .pagination .page-item .page-link svg {
            width: 16px; /*  Réduit la taille de l’icône */
            height: 16px; /*  Assure une bonne proportion */
        }


</style>

{{-- Pour le modal --}}

<div class="modal fade" id="pliModal" tabindex="-1" aria-labelledby="pliModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails du pli supprimé</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Expéditeur :</strong> <span id="modalUserName"></span></p>
                <p><strong>Destinataire :</strong> <span id="modalDestinataireName"></span></p>
                <p><strong>Zone Expéditeur :</strong> <span id="modalUserZone"></span></p>
                <p><strong>Zone Destinataire :</strong> <span id="modalDestinataireZone"></span></p>
                <p><strong>Numéro de suivi :</strong> <span id="modalReference"></span></p>
                <p><strong>Date de suppression :</strong> <span id="modalDateAction"></span></p>
                <hr>
                <p><strong>Adresse Expéditeur :</strong> <span id="modalUserAdresse"></span></p>
                <p><strong>Email Expéditeur :</strong> <span id="modalUserEmail"></span></p>
                <p><strong>Type :</strong> <span id="modalType"></span></p>
                <p><strong>Nombre de pièces :</strong> <span id="modalNombrePieces"></span></p>

            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div> --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" onclick="imprimerModal()">🖨️ Imprimer</button>
            </div>

        </div>
    </div>
</div>

<script>
    document.querySelectorAll(".pli-row").forEach(row => {
        row.addEventListener("click", function () {
            let pliData = JSON.parse(this.getAttribute("data-info"));

            document.getElementById("modalUserName").innerText = pliData.user_name;
            document.getElementById("modalDestinataireName").innerText = pliData.destinataire_name;
            document.getElementById("modalUserZone").innerText = pliData.user_Zone;
            document.getElementById("modalDestinataireZone").innerText = pliData.destinataire_zone;
            document.getElementById("modalReference").innerText = pliData.code;

            // if (pliData.date_action) {
            //         let formattedDate = new Date(pliData.date_action).toLocaleString(); // 🔥 Convertit la date au format lisible
            //         document.getElementById("modalDateAction").innerText = formattedDate;
            //     } else {
            //         document.getElementById("modalDateAction").innerText = "Date non disponible"; // 🔥 Affiche un message clair si la date est absente
            //     }

            if (pliData.date_action) {
                let formattedDate = new Date(pliData.date_action).toLocaleString("fr-FR", {
                    year: "numeric",
                    month: "2-digit",
                    day: "2-digit",
                    hour: "2-digit",
                    minute: "2-digit",
                    second: "2-digit"
                });

                document.getElementById("modalDateAction").innerText = formattedDate;
            } else {
                document.getElementById("modalDateAction").innerText = "Date indisponible";
            }



            // document.getElementById("modalDateAction").innerText = pliData.date_action;
            document.getElementById("modalUserAdresse").innerText = pliData.user_Adresse;
            document.getElementById("modalUserEmail").innerText = pliData.user_email;
            document.getElementById("modalType").innerText = pliData.type;
            document.getElementById("modalNombrePieces").innerText = pliData.nombre_de_pieces;

            new bootstrap.Modal(document.getElementById("pliModal")).show();
        });
    });





    // Pour imprimer un modal le script suivant

</script>


<div id="printArea" style="display:none;">
    <div style="text-align: center; position: relative;">
        <img src="https://ivoirrapid.ci/asset/Logo%20IRN.png"
             style="opacity: 0.2; position: absolute; top: 200%; left: 50%; transform: translate(-50%, -50%); width: 200px;"
             alt="IVOIRRAPID Logo">
        <h2>IVOIRRAPID</h2>
        <p>Plateforme de gestion des plis supprimés</p>
        <hr>
    </div>
    <div id="printContent"></div>
    <p style="font-style:italic;text-align:center; position: absolute;bottom:0">Imprimer le {{ date('d-m-Y') }}  ------------------------------------------------------------- Admin</p>
</div>
<script>
    function imprimerModal() {
        let modalBody = document.querySelector("#pliModal .modal-body").innerHTML;

        //  Copier le contenu du modal vers la div cachée
        document.getElementById("printContent").innerHTML = modalBody;

        //  Imprimer directement la div avec le logo en filigrane
        let printArea = document.getElementById("printArea");

        let originalContent = document.body.innerHTML; //  Sauvegarder le contenu original
        document.body.innerHTML = printArea.innerHTML; //  Afficher uniquement le contenu imprimable

        window.print(); //  Lancer l'impression

        document.body.innerHTML = originalContent; //  Restaurer le contenu normal après impression
    }
</script>










@endsection
