
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


