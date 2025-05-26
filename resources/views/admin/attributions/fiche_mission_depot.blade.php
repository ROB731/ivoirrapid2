@extends('layouts.master')

@section('content')

<br>

<p style="text-align:right">
    <a href="{{ url()->previous() }}" class="btn btn-primary">
         Retour
    </a>
 </p>
 <div class="mt-3" style="text-align:right">
    {{-- <button onclick="printSection()" class="btn btn-secondary">🖨️ Imprimer la fiche</button> --}}
    <button onclick="window.print()" class="btn btn-secondary">
        {{-- 🖨️ Imprimer la fiche --}}
        <i class="fas fa-print"></i> 🖨️
    </button>

    <button id="downloadPDF" class="btn btn-danger">
        {{-- 📄 Télécharger le PDF --}}
        <i class="fas fa-file-pdf"></i> 📄
        </button>

    {{-- Ajout pour exportation en excel ------------------03-05-2025--------------------------------------------- --}}
    <button onclick="exportFullPrintSectionToExcel('fiche-mission')" class="btn btn-success">
        {{-- 📝 Exporter la fiche en Excel --}}
        <i class="fas fa-file-excel"></i> 📊

    </button>

    {{-- Fon du bouton pour l'exportation excel ------------------------- --}}

</div>


<div id="print">
<div class="container mt-4">
    <div class="print-logo"> <br>
        <img src="https://ivoirrapid.ci/asset/Logo%20IRN.png" alt="Logo Entreprise" style="width: 80px; height: auto;">
    </div>
    <h5 class="mb-3">📋 Fiche de Mission du {{ date('d-m-Y') }} - Dépôt </h5>
    <div class="card">
        <div class="card-header">
            <h5>🚴‍♂️ Coursier : {{ $coursierDepot->nom }} {{ $coursierDepot->prenoms }} (Code: {{ $coursierDepot->code }}) </h5>
            <p><strong>Téléphone :</strong> {{ $coursierDepot->telephone }}</p>
            <p><strong>Zones gérées par le coursier :  </strong>

                @if(!empty($coursierDepot->zones))
                    @foreach ($coursierDepot->zones as $z )
                        {{ $z }},
                    @endforeach
                @endif
          </p>
          <p>Nombre de plis à livrer :  {{ count($attributionsDepot) }} </p>
          <small> <i>NB: Si aucun coursier ne correspond à la zone concernée c'est qu'il à été choisi par defaut </i> </small>
        </div>

        <div class="card-body">
            <table class="table table-bordered mission-table" id="missionTable" >
                <thead>
                    <tr>
                        <th>#</th>

                        <th>N° du Pli </th>
                        {{-- <th>Expéditeur (Zone)</th> --}}
                        <th style="background:rgb(223, 221, 221)" >Destinataire (Zone)</th>
                        {{-- <th >Type</th> --}}
                        {{-- <th style="max-width:50px">Heure d'Attribution</th> --}}
                        <th>Déposé</th>
                    </tr>
                </thead>
{{-- Body ---------------------------------------------------------- --}}
                    {{-- ------------------------ --}}


        @php
            // Grouper les attributions uniquement par destinataire
            $groupedAttributions = $attributionsDepot->groupBy(fn($attribution) =>
                ($attribution->pli->destinataire_name ?? 'Destinataire inconnu') . ' - ' .
                ($attribution->pli->destinataire_adresse ?? 'Adresse inconnue') . ' - ' .
                ($attribution->pli->destinataire_autre ?? '')
            );
        @endphp

<tbody>
    @foreach($groupedAttributions as $destinataire => $attributions)
        {{-- <tr>
            <td colspan="5" class="text-center font-weight-bold bg-light;background:black">📦 Destinataire : {{ $destinataire }}</td>
        </tr> --}}

        @foreach ($attributions as $attribution)
            <tr>
                <td> {{ $loop->iteration ?? '####' }} </td>
                <td style="text-align:center;min-width:150px">{{ $attribution->pli->code ?? '####' }}</td>
                <td style="background:rgb(223, 221, 221); max-width:150px">
                    {{ $attribution->pli->destinataire_name ?? '####' }} ({{ $attribution->pli->destinataire_zone }})
                </td>
                {{-- <td style="text-align:center;max-width:150px">{{ $attribution->pli->type ?? '####' }}</td> --}}
                <td style="text-align:center"> <input type="checkbox" name="" id=""> </td>
            </tr>
        @endforeach
    @endforeach
</tbody>

{{-- Body----------------------------------- --}}

            </table>
        </div>

        Total de facture à livré par  {{ $coursierDepot->nom }} {{ $coursierDepot->prenoms }} (Code: {{ $coursierDepot->code }}) .................../  {{ count($attributionsDepot) }}

         <br> <br> Nom&Prénoms Signature  ........................................................................ / Autre: ......................................................................................... <br>

    </div>
    </div>

    </div>

    <div class="mt-3">
        {{-- <button onclick="printSection()" class="btn btn-secondary">🖨️ Imprimer la fiche</button> --}}
        <button onclick="window.print()" class="btn btn-secondary">🖨️ Imprimer la fiche</button>
    </div>

    <style>
            @media print {
                    body {
                        margin: 0;
                        padding: 0;
                    }

                    .card{
                        width:1000px !important;
                    }

                    .container {
                        width: 100%;
                        max-width: 120%;
                        margin:auto;
                    }

                    .table {
                        width: 100% !important;
                        border-collapse: collapse;



                    }

                    .table th, .table td {
                        padding: 0;
                        border: 1px solid black;
                        font-size: 16px;
                        vertical-align: middle;
                        text-align: center !important;

                    }

                    .sb-sidenav-menu,.btn, .no-print {
                        display: none !important; /*  Cache les boutons inutiles lors de l'impression */
                    }

                    th{
                        background:black !important;
                        color:white;
                        font-weight:400;
                        text-align: center !important;
                    }

                        nav,footer{
                            display:none !important;
                        }
                }

        .print-logo {
            text-align: center;
            margin-bottom: 0;
            margin-top:-70px;
        }

    </style>

    <script>
            function printSection() {
                    let printContent = document.getElementById("print").innerHTML; //  Récupère uniquement #print
                    let originalContent = document.body.innerHTML; // Sauvegarde le contenu initial

                    document.body.innerHTML = printContent; // 💾 Affiche seulement #print
                    window.print(); // 🖨 Lance l’impression
                    document.body.innerHTML = originalContent; // 🔄 Restaure la page après impression
                }
    </script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    document.getElementById('downloadPDF').addEventListener('click', function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');

        html2canvas(document.querySelector("#print")).then(canvas => {
            let imgData = canvas.toDataURL('image/png');
            let imgWidth = 190;
            let imgHeight = (canvas.height * imgWidth) / canvas.width;
            doc.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight);
            doc.save("fiche_mission_{{ $coursierDepot->nom }} {{ $coursierDepot->prenoms }}.pdf");
        });
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>




    {{-- Pour convertir le tableau en excel download  03-05-2025-------------------------------------------------------------------------------------------}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
        <script>

                function exportFullPrintSectionToExcel(filename = 'fiche-mission_{{$coursierDepot->nom}}_{{$coursierDepot->prenoms}}') {
                    let printContent = document.getElementById("print");
                    let wb = XLSX.utils.book_new();

                    let wsData = []; //  Stocke toutes les informations sous forme de tableau

                    //  1 Capturer les titres, paragraphes et autres textes
                    let textElements = printContent.querySelectorAll("h5, h4, p, small");
                    textElements.forEach(el => {
                        wsData.push([el.innerText]); //  Chaque élément texte sur une ligne
                    });

                    wsData.push([""]); //  Ajoute une ligne vide avant le tableau

                    //  Capturer le tableau et ajouter ses lignes
                    let tableRows = printContent.querySelectorAll("table tr");
                    tableRows.forEach(row => {
                        let cells = row.querySelectorAll("th, td");
                        let rowData = Array.from(cells).map(cell => cell.innerText);
                        wsData.push(rowData); //  Ajoute chaque ligne du tableau au même format
                    });

                    //  Générer et exporter la feuille Excel
                    let ws = XLSX.utils.aoa_to_sheet(wsData);
                    XLSX.utils.book_append_sheet(wb, ws, "Fiche Mission");

                    XLSX.writeFile(wb, filename + ".xlsx");
                }


        </script>

{{-- Fin de script pour l'exportation excel 03-05-2025----------------------------------------------------------------------------- --}}

@endsection
