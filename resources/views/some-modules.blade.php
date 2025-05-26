
@section('info-important')
        <div >
            <form action="" class="text-impo">
                <textarea placeholder="Votre Message :"></textarea>
            </form>
        </div>
        <style>
                textarea {
                    width: 100%; 
                    height: 80px; 
                    border: none; 
                    outline: none; 
                    font-size: 16px; /* Taille du texte */
                    color: black; /* Texte en noir */
                    background-color: #f9f9f9;  contraste */
                    padding: 10px; /* Espacement intérieur */
                    resize: none; /* Empêche le redimensionnement */
                    font-family: 'arial narrow';
                }

                textarea::placeholder {
                    color: gray; /* Couleur du texte d'indication */
                    font-style: italic; /* Différenciation du texte normal */
                }
        </style>
@endsection


@section('modal-msg') <!-- -----------------------------------   -->
    
    <style>
        :root {
            --primary-color-modal: rgb(24, 36, 212);
            --secondary-color-modal: rgb(60, 71, 233);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        *::-webkit-scrollbar {
            background-color: transparent;
            width: 12px;
        }

        *::-webkit-scrollbar-thumb {
            border-radius: 99px;
            background-color: #ddd;
            border: 4px solid #fff;
        }

        #back-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-container-msg {
            max-width: 500px;
            background-color: #fff;
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25);
            background-image: url('{{ asset("assets/logo IRN.png") }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
        }

        .modal-container-header-msg {
            padding: 16px 32px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        .modal-container-body-msg {
            padding: 24px 32px;
            overflow-y: auto;
            text-align: center;
        }

        .modal-container-footer-msg {
            padding: 20px;
            text-align: center;
            border-top: 1px solid #ddd;
        }

        .button-modal-msg {
            padding: 12px 20px;
            border-radius: 8px;
            background-color: var(--primary-color-modal);
            color: #fff;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: 0.15s ease;
        }

        .button-modal-msg:hover {
            background-color: var(--secondary-color-modal);
        }
    </style>
</head>
<body>

<div id="back-modal">
    <div id="modal-msg">
        <article class="modal-container-msg">
            <div class="modal-container-header-msg">
                <h2>IVOIRRAPID - Information</h2>
            </div>
            <section class="modal-container-body-msg">
                <!-- Texte to move -->
                
                <h5>La Direction IVOIRRAPID vous informe des nouvelles mises à jour de sa plateforme</h5>
                <p class="text-msg" style="color:red">
                    <b>NB</b>: Avant de tirer la fiche de Bordereau de suivi, veuillez noter dans la partie
                    <i><b>information importante</b></i> les actions à effectuers si nécessaire.
                </p>
                
                 <!-- Texte to move -->
            </section>
            <div class="modal-container-footer-msg">
                <button class="button-modal-msg" id="accept-msg">Accepter</button>
            </div>
        </article>
    </div>
</div>

<script>
    document.getElementById("accept-msg").addEventListener("click", function() {
        document.getElementById("back-modal").style.display = "none";
    });
</script>

@endsection  <!--/ -----------------------------------   -->


@section('barre_info')

    <!-- <div class="no-print">
        <div class="text-barre">
                <p style="padding:2px">
                     <b> La Direction IVOIRRAPID</b> vous informe des nouvelles mises à jour de sa plateforme 
                     <br>
                 <u>NB:</u> Avant de tirer la fiche de Bordereau de suivi, veuillez noter dans la partie information importante les actions à effectuers si nécessaire.
                </p>
        </div>
    </div>
    <style>
        .text-barre{
                    background-color:#0068cf;
                    font-style:italic;
                    color:white;
                    margin-bottom:-18px;
                    text-align:center;
                   
                }
    </style>   -->
    
    
    <!-- Barre de notification -->
<div class="no-print">
<div id="notification-bar">
    <p>
        🚀 La Direction d'IVOIRRAPID vous informe des mises à jour de sa plateforme. <br>
       <span style="color:red">
            <b>NB :</b> Avant d'imprimer la fiche de bordereau de suivi, notez les actions à effectuer si nécessaire.
        </span>
       
    </p>
    <button class="close-btn" onclick="fermerNotification()">×</button>
</div>
</div>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    #notification-bar {
        /* position: fixed; */
        top: 60px; /* Ajuste cette valeur en fonction de la hauteur de ta nav */
        left: 0;
        width: 100%;
        background-color: #87CEEB; /* Bleu ciel */
        color: #333;
        padding: 4px;
        text-align: center;
        font-size: 16px;
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 9999;
    }

    #notification-bar p {
        flex-grow: 1;
        padding: 0 20px;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
        color: #333;
        margin-right: 15px;
    }

    .close-btn:hover {
        color: red;
    }
</style>

<script>
    function fermerNotification() {
        document.getElementById("notification-bar").style.display = "none";
    }
</script>


@endsection


@section('btn-print') <!-- bouton pour imprimer -->

         
<!-- Bouton Imprimer -->


<script>

     function imprimerTableau() {
        const pliTable = document.querySelector('#pliTable');
        if (!pliTable) {
            alert("Le tableau est introuvable.");
            return;
        }

        // Cloner le tableau pour l'afficher deux fois
        const tableauClone1 = pliTable.cloneNode(true);
        const tableauClone2 = pliTable.cloneNode(true);

        // Créer un conteneur temporaire pour l'impression
        const printContainer = document.createElement('div');
        printContainer.style.textAlign = 'center'; // Centrer les éléments
        printContainer.style.fontSize = '0.7rem'; // Ajuster la taille de la police
        printContainer.style.padding = '0px'; // Réduction des marges internes

        // Ajouter le premier tableau
        printContainer.appendChild(tableauClone1);

        // Ajouter une ligne pointillée pour la délimitation
        const delimitationLine = document.createElement('hr');
        delimitationLine.style.borderTop = '2px dashed #000'; // Ligne pointillée
        delimitationLine.style.margin = '10px 0'; // Espacement réduit
        printContainer.appendChild(delimitationLine);

        // Ajouter le deuxième tableau
        printContainer.appendChild(tableauClone2);

        // Sauvegarder le contenu original de la page
        const originalContent = document.body.innerHTML;

        // Remplacer le contenu de la page par le conteneur temporaire
        document.body.innerHTML = '';
        document.body.appendChild(printContainer);

        // Ajouter des styles CSS spécifiques pour renforcer les bordures
        const style = document.createElement('style');
        style.textContent = `
            table {
                width: 90%;
                margin: 0 auto;
                border-collapse: collapse;
                word-wrap: break-word;
                white-space: normal;
                background-color: white; /* Fond blanc pour garantir la visibilité */
            }
            th, td {
                border: 2px solid #000 !important; /* Bordures plus épaisses et forcées */
                padding: 4px !important;
                text-align: left !important;
            }
            img {
                max-width: 40px;
                margin-bottom: 2px;
            }
            hr {
                border: none;
                border-top: 2px dashed #000;
                margin: 4px 0; /* Réduction de l'espacement */
            }
            @media print {
                body {
                    margin: 0px !important; /* Marges réduites entre le papier et le contenu */
                    padding: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // Lancer l'impression
        window.print();

        // Restaurer le contenu original de la page après l'impression
        document.body.innerHTML = originalContent;
        document.head.removeChild(style); // Supprimer les styles ajoutés
    }
    
</script>




@endsection  <!--  / bouton pour imprimer -->




