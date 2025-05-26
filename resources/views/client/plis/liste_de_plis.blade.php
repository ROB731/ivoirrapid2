<!-- Bouton pour lancer l'impression -->
{{-- <button onclick="imprimerContenu()" class="btn-print">🖨️ Imprimer</button> --}}

<!-- Zone d'impression (cachée par défaut) -->
<div id="zone-impression" style="display: none;">
    <div class="document-container">
        <!-- En-tête du document (centré en haut) -->
        <div class="header">
            {{-- <h4 class="header-title">Liste des Plis à Ramasser chez {{ auth()->user()->name }} </h4> --}}
            {{-- <p>Date de génération : {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}</p> --}}

            {{-- <p>Responsable : {{ auth()->user()->name }}</p> --}}


           <div class="info-print" > <!-- BEGIN HEADER TO PRINT UPDATE -->
                    <i>
                        <br>
                        <h6 style="text-align:center; text-transform: uppercase;" > Plis du client :  {{ Auth::user()->name }} </h6>
                        <p style="width:100%; text-align:center" >Fiche recapitulatif</p>
                        <hr style="width:100%; text-align:center">
                        </h6>

                            @if(request()->start_date && request()->end_date)
                                {
                                    Pour la période du {{ \carbon\Carbon::parse(request()->start_date )->format('d-m-Y')}} au {{ \carbon\Carbon::parse(request()->end_date )->format('d-m-Y')}}
                                }
                            @else
                                {
                                    Plis d'aujourd'hui le {{ date('d-m-Y') }} ou récemment créés
                                }
                            @endif
                        <br>  Imprimé le : {{ date('d-m-Y') }} à {{ date('H:i:s') }}
                        <hr style="width:100%; text-align:center">
                    </i>
                </div>

                <div id="logo">
                    <img src="https://ivoirrapid.ci/asset/Logo IRN.png" alt="Ivoirrapid">
                </div>

                <h6>Total des plis : {{ count($pliClient) }} </h6>

    <!-- CLOSE HEADER PRINT UPDATE-->

        </div>

        <!-- Tableau -->
        <div class="table-container">
            <table class="print-table">
                <thead>
                    <tr>
                        <th>Numéro</th>
                        <th>Code du pli</th>
                        <th>Type de pli</th>
                        <th>Destinataire</th>
                        <th>Date de création</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pliClient as $index => $pli)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pli->code }}</td>
                        <td>{{ $pli->type }}</td>
                        <td>{{ $pli->destinataire_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($pli->created_at)->format('d-m-Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pied de page du document (centré en bas) -->
        <br>
        <br>
        <br>
        <div class="footer-print">
            <p>Contact {{ Auth::user()->name }} <br>
                {{ Auth::user()->Telephone }}

            </p>

            <p>Coursier lié </p>
        </div>
    </div>
</div>
<style>

        #zone-impression{
                display:none;
        }



                @media print {

                    /* #zone-impression{
                            display:block !important;
                            z-index: 99999;
                            background-image: url('https://ivoirrapid.ci/asset/Logo IRN.png')
                             } */



                             #logo {
                                    position: absolute; /* Position absolue pour ne pas affecter le flux du document */
                                    top: 0; /* Ajuste la position verticale */
                                    left: 0; /* Place le logo à gauche */
                                    width: 80px; /* Taille ajustable */
                                }

                                #logo img {
                                    width: 100%; /* Assure un bon affichage */
                                    opacity: 0.8; /* Légère transparence pour ne pas trop dominer la mise en page */
                                }



                             #zone-impression {
                                    display: block !important;
                                    z-index: 99999;
                                    position: relative;
                                }

                    /* Ajout d'un filigrane */
                    body::before {
                        content: "";
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background-image: url('https://ivoirrapid.ci/asset/Logo IRN.png');
                        background-repeat: no-repeat;
                        background-position: center;
                        background-size: contain; /* Ajuste la taille */
                        opacity: 0.10; /* Réduction de l'opacité pour un effet filigrane */
                        z-index: -1; /* Place le filigrane derrière le contenu */
                    }

            body {
                font-size: 12px;
                padding: auto;
            }

            /* En-tête centré en haut */
            .header {
                text-align: center;
                font-weight: bold;
                /* position: absolute; */
                top: 0;
                width: 100%;
                background: white;
                padding: 10px;
            }

            /* Pied de page centré en bas */
            .footer-print {
                text-align: center !important;
                font-weight: bold !important;
                /* position: fixed !important; */
                /* bottom: 5px !important; */
                width: 100% !important;
                /* background: white; */
                padding: 10px 0;
                display:flex;
                gap: 300px;
                justify-content: justify;
                margin:auto;
            }

            /* Bordures du tableau visibles */
            .print-table {
                width: 95%;

                border-collapse: collapse;
                margin-top: 10px auto; /* Ajustement pour éviter l'en-tête qui est fixe */
            }

            .print-table th, .print-table td {
                border: 1px solid black !important;
                padding: 4px;
                text-align: center;
            }

            .print-table th {
                background-color: #373434;
                color: white;
                font-weight: bold;
                border: 2px solid black !important;
            }
        }

/* ------------------------------------------------------------------------------------- */

        @media print{
                    #table-p{
                            width:300px !important;
                            margin:auto;
                            height: 100%;
                            border: 1px !important;
                            text-align: center;
                    }

                    .text-muted,nav,
                   .sb-sidenav-menu,
                   .no-print,form,
                   .text-nowrap,nav{
                        display:none !important;
                    }

                .text-nowrap-pr{
                        height: 11px !important;
                        font-size:10px;
                        padding:0px !important;

                          }


                .table-responsive {
                        overflow: hidden;
                        max-height: 100% !important;
                        width:auto !important;
                        height: auto;
                    }

            .info-print{
                display: inline !important;
            }

        header{
            display:none;
        }

        }

        .info-print{
                display: none;
        }


        #btn-print {
            position: fixed; /* Permet de fixer le bouton à une position précise par rapport à la fenêtre */
            top: 50%; /* Place le bouton verticalement au milieu */
            right: 0; /* Aligne le bouton à droite */
            transform: translateY(-50%); /* Centre le bouton verticalement */
            z-index: 9999; /* Assure que le bouton soit au-dessus de tous les autres éléments */
            display:none;
        }


        td, th {
            /* text-align: center; */
            vertical-align: middle;
        }


</style>



