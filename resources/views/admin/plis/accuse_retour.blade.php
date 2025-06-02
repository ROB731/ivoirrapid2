@extends('layouts.master')

@section('title', 'Accusé de Retour')

@section('content')




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
            <!--<p><strong>Référence : </strong>{{ $pli->reference ?? 'Non défini' }}</p>-->
            <p><strong>Numero de suivi : </strong>{{ $pli->code ?? 'Non défini' }}</p>
            <p><strong>Code du coursier : </strong>{{ optional($pli->coursier)->code ??'Non défini' }}</p>


        </div>
    </div>

    <!-- Titre principal -->
    <div class="text-center my-1">
        <h3><strong>ACCUSÉ DE RETOUR</strong></h3>
    </div>

    <!-- Nom du client -->
    <div class="text-center mt-2">
        <p><strong>Nom du Client : </strong>{{ $pli->user_name ?? 'Non défini' }}</p>
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
                <td>{{ $pli->reference ?? 'Non défini' }}</td>
                <td>{{ $pli->nombre_de_pieces ?? 0 }}</td>
                <td>{{ $pli->currentStatuer()?->raison_annulation ?? ' ' }}</td>
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





<!-- Bouton d'impression -->
<div class="btn-container mt-3">
    <button id="imprimer-btn" class="btn btn-primary" onclick="imprimerPage()">
        <i class="fas fa-print"></i> Imprimer
    </button>
</div>




@endsection

<style>
    /* Conteneur général */
    .container {
        font-family: Arial, sans-serif;
        font-size: 12px;
        line-height: 1.5;
    }

    /* Espacement entre les sections */
    .ligne-dotee {
        border-top: 2px dashed #000;
        margin: 20px 0;
    }

    /* En-tête avec logo et informations */
    .d-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    img {
        max-width: 80px;
    }

    /* Format du tableau */
    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th, .table td {
        border: 1px solid #000;
        padding: 5px;
        text-align: center;
        font-size: 12px;
    }

    .table th {
        background-color: #f0f0f0;
    }

    .table-sm td {
        padding: 3px;
    }

    /* Sections signature */
    .row .col-md-6 div {
        margin: 5px 0;
        background-color: #fff;
    }

    /* Masque le bouton lors de l'impression */
    @media print {
        #imprimer-btn {
            display: none;
        }

        .container {
            margin: 0 auto;
            width: 100%;
        }

        .table {
            page-break-inside: avoid;
        }

        .row .col-md-6 {
            display: inline-block;
            width: 48%;
            vertical-align: top;
        }

        .section {
            page-break-inside: avoid;
        }

        .ligne-dotee {
            border-top: 2px dashed #000; /* Affiche les pointillés à l'impression */
            margin: 30px 0;
        }
    }
</style>

<script>
    function imprimerPage() {
        // Masquer les éléments inutiles pour l'impression
        const elementsACacher = document.querySelectorAll('#imprimer-btn');
        elementsACacher.forEach((element) => {
            element.style.display = 'none';
        });

        // Lance l'impression
        window.print();

        // Réaffiche les éléments cachés
        elementsACacher.forEach((element) => {
            element.style.display = '';
        });
    }
</script>
