
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
    <button onclick="window.print()" class="btn btn-secondary">🖨️ Imprimer la fiche</button>

  {{-- Ajout pour exportation en excel ------------------03-05-2025--------------------------------------------- --}}
    <button onclick="exportFullPrintSectionToExcel('fiche-mission')" class="btn btn-success">
        📝 Exporter la fiche en Excel
    </button>

    {{-- Fon du bouton pour l'exportation excel ------------------------- --}}

</div>

<div id="print">
<div class="container mt-4">
    <div class="print-logo"> <br>
        <img src="https://ivoirrapid.ci/asset/Logo%20IRN.png" alt="Logo Entreprise" style="width: 80px; height: auto;">
    </div>
    <h5 class="mb-3">📋 Fiche de Mission du {{ date('d-m-Y') }} - Ramassage</h5>
    <div class="card">
        <div class="card-header">
            <h5>🚴‍♂️ Coursier : {{  $coursierRamassage->nom ??'####' }} {{  $coursierRamassage->prenoms ??'####'}}</h5>
            <p><strong>Téléphone :</strong> {{  $coursierRamassage->telephone ??'####' }}</p>
            <p><strong>Zones gérées par le coursier :  </strong>

                @if(!empty( $coursierRamassage->zones))
                    @foreach ($coursierRamassage->zones as $z )
                        {{ $z }},
                    @endforeach
                @endif
          </p>
          <p>Nombre de plis à livrer :  {{ count($attributionsRamassage) }} </p>
          <small> <i>NB: Si aucun coursier ne correspond à la zone concernée c'est qu'il à été choisi par defaut </i> </small>
        </div>

        <div class="card-body">
            <table class="table table-bordered mission-table" id="missionTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>N° du Pli </th>
                        <th style="background:rgb(223, 221, 221)" >Expéditeur (Zone)</th>
                        <th  >Destinataire (Zone)</th>
                        <th>Type</th>
                        <th style="width:30px">Heure d'Attribution</th>
                        <th>Ramassé</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attributionsRamassage as $attribution)
                        <tr>
                            <td> {{ $loop->iteration ??'####' }} </td>
                            <td style="text-align:center">{{ $attribution->pli->code ??'####' }} </td>
                            <td style="text-align:center; background:rgb(223, 221, 221)">{{ $attribution->pli->user_name ??'####' }} ({{ $attribution->pli->user_Zone ??'####' }})</td>
                            <td  >{{ $attribution->pli->destinataire_name }} ({{ $attribution->pli->destinataire_zone ??'####' }})</td>
                            <td style="text-align:center">{{ $attribution->pli->type ??'####' }}</td>
                            <td style="width:30px">{{ $attribution->pli->created_at->format('H:i') }}</td>
                            <td style="text-align:center"> <input type="checkbox" name="" id=""> </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">🚫 Aucune mission attribuée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
                        font-size: 14px;
                        text-align:center;
                    }

                    .sb-sidenav-menu,.btn, .no-print {
                        display: none !important; /*  Cache les boutons inutiles lors de l'impression */
                    }

                        nav,footer{
                            display:none !important;
                        }

                    th{
                        background:black !important;
                        color:white;
                        font-weight:400;
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





    {{-- Pour convertir le tableau en excel download  03-05-2025-------------------------------------------------------------------------------------------}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
        <script>

                function exportFullPrintSectionToExcel(filename = 'fiche-mission') {
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

@endsection
