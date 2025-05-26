@extends('layout.master')

@section('title', 'Détails du Pli')

@section('content')

@include('some-modules')


<!-- Costumer -->


<div class="container-fluid px-4">
    <h1 class="mt-4" style="font-size: 1.5rem;">Détails du Pli</h1>
    <!-- Bouton pour générer le PDF -->
    <!--<button onclick="genererPDF()" class="btn btn-success mb-3">Télécharger en PDF</button>-->
       <button onclick="imprimerTableau()" class="btn btn-warning mb-3">Imprimer</button>
   @yield('btn-print')
   
    <a href="{{ route('client.plis.index') }}" class="btn btn-dark mb-3">Retour</a>

    <!-- Informations Client, Destinataire et Pli dans un tableau -->
    <div class="card mb-3">
        <div class="card-header">
            <strong style="font-size: 1rem;">Informations du Pli</strong>
        </div>
        <div class="card-body">
            <table id="pliTable" class="table table-sm" style="font-size: 0.90rem; max-width: 900px; margin: 0 auto;">
                <thead>
                    <tr>
                        <th colspan="3" style="text-align: center;">
                            <!-- Logo en haut du tableau -->
                            <img src="{{ asset('asset/Logo IRN.png') }}" alt="Logo" class="img-fluid logo-pli-table" style="max-width: 70px; margin-bottom: 15px;">

                            {{-- <img src="{{ asset('asset/Logo IRN.png') }}" alt="Logo" class="img-fluid logo-impression" style="max-width: 70px; margin-bottom: 15px;"> --}}
                        </th>
                    </tr>
                    <tr>
                        <th style="width: 30%;">Informations Client</th>
                        <th style="width: 30%;">Informations Destinataire</th>
                        <th style="width: 40%;">Informations Pli</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <!-- Informations Client -->
                        <td>
                            <p>{{ $pli->user_name }}</p>
                            <p>{{ $pli->user_Telephone }}</p>
                            <p>{{ $pli->user_Cellulaire }}</p>
                            <p>{{ $pli->user_Adresse }}</p>
                            <p>{{ $pli->user_Zone }}</p>
                        </td>

                        <!-- Informations Destinataire -->
                        <td>
                            <p>{{ $pli->destinataire_name }}</p>
                            <p>{{ $pli->destinataire_telephone }}</p>
                            <p>{{ $pli->destinataire_contact }}</p>
                            <p>{{ $pli->destinataire_adresse }}</p>
                            <p>{{ $pli->destinataire_zone }}</p>
                        </td>

                        <!-- Informations Pli -->
                        <td>
                            <p><strong>Date de creation :</strong> {{ $pli->created_at }}</p>
                            <p><strong>No de suivi :</strong> {{ $pli->code }}</p>
                            <p><strong>Type :</strong> {{ $pli->type }}</p>
                            <p><strong>Nombre :</strong> {{ $pli->nombre_de_pieces }}</p>
                            <p><strong>Références :</strong> <span style="word-wrap: break-word; white-space: normal;">{{ $pli->reference }}</span></p>
                        </td>
                    </tr>
                     <tr>
                        <td> <b><i>Autres informations utiles</b> <br> (Saisie par le client) </i></td>
                        <td colspan="2"> @yield('info-important')</td>
                        
                    </tr>
                    
                    
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
    /* Style du tableau */
    #pliTable {
        table-layout: fixed; /* Fixer la mise en page */
        width: 100%; /* S'assurer que le tableau utilise toute la largeur */
        border-collapse: collapse; /* Éviter les espaces entre les cellules */
    }

    /* Colonnes du tableau */
    #pliTable th, #pliTable td {
        border: 1px solid #888; /* Ajouter des bordures */
        padding: 10px; /* Espacement interne */
        text-align: left; /* Alignement du texte */
        word-wrap: break-word; /* Permettre au texte de passer à la ligne si nécessaire */
        white-space: nowrap; /* Empêcher le texte de s'étendre sur plusieurs lignes */
        overflow: hidden; /* Couper le texte s'il dépasse */
    }

    /* Largeur fixe pour chaque colonne */
    #pliTable th:nth-child(1), #pliTable td:nth-child(1) {
        width: 20%; /* Ajuster selon vos besoins */
    }

    #pliTable th:nth-child(2), #pliTable td:nth-child(2) {
        width: 30%;
    }

    #pliTable th:nth-child(3), #pliTable td:nth-child(3) {
        width: 50%;
    }

    /* Style spécifique pour la ligne de référence */
    #pliTable .reference-row td {
        text-align: center; /* Centrer les données si nécessaire */
    }
</style>

<!-- Scripts pour la génération de PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
  /*   async function genererPDF() {
        const { jsPDF } = window.jspdf;

        const pliTable = document.querySelector('#pliTable');
        if (!pliTable) {
            alert("Le tableau est introuvable.");
            return;
        }

        // Créez un conteneur temporaire
        const tempContainer = document.createElement('div');
        tempContainer.style.position = 'absolute';
        tempContainer.style.top = '-10000px';
        tempContainer.style.padding = '20px'; // Ajoute une marge autour du conteneur
        tempContainer.style.backgroundColor = 'white'; // Assure un fond blanc

        // Cloner et configurer les tableaux
        const tableClone1 = pliTable.cloneNode(true);
        const tableClone2 = pliTable.cloneNode(true);

        [tableClone1, tableClone2].forEach((table, index) => {
            table.style.fontSize = '0.9rem'; // Réduire légèrement les polices
            table.style.width = '90%'; // Réduire la largeur
            table.style.margin = '10px auto'; // Centrer avec un espacement entre les tableaux
            table.style.wordWrap = 'break-word'; // Empêcher les retours à la ligne
            table.style.whiteSpace = 'nowrap'; // Forcer le texte à rester sur une seule ligne

            if (index === 0) {
                table.style.marginTop = '45px'; // Remonter le premier tableau encore plus
            } else {
                table.style.marginTop = '280px'; // Faire descendre le deuxième tableau
            }

            // Ajouter des bordures uniquement autour des cellules
            const cells = table.querySelectorAll('td, th');
            cells.forEach(cell => {
                cell.style.border = '0.5px solid #888'; // Bordures plus légères
                cell.style.padding = '5px'; // Ajouter de l’espace à l'intérieur des cellules
                cell.style.textAlign = 'left'; // Alignement du texte pour une meilleure lisibilité
            });

            // Éviter de mettre des bordures sur le logo
            const logoCell = table.querySelector('th[colspan="3"]');
            if (logoCell) {
                logoCell.style.border = 'none';
            }
        });

        // Ajouter les deux tableaux au conteneur temporaire
        tempContainer.appendChild(tableClone1);
        tempContainer.appendChild(tableClone2);

        // Ajouter le conteneur au DOM pour la capture
        document.body.appendChild(tempContainer);

        try {
            const canvas = await html2canvas(tempContainer, { scale: 2 });
            const imgData = canvas.toDataURL('image/png');

            const pdf = new jsPDF('p', 'pt', 'a4');
            const pageWidth = pdf.internal.pageSize.getWidth();
            const imgHeight = (canvas.height * pageWidth) / canvas.width;

            // Ajouter l'image du tableau dans le PDF
            pdf.addImage(imgData, 'PNG', 0, 0, pageWidth, imgHeight);

            // Ajouter une ligne pointillée au centre de la page
            const pageHeight = pdf.internal.pageSize.getHeight();
            const middleY = pageHeight / 2;

            // Dessiner une ligne pointillée (dotted)
            pdf.setLineWidth(0.5);
            pdf.setDrawColor(0, 0, 0);
            pdf.setLineDash([5, 3]); // Utiliser setLineDash pour une ligne pointillée
            pdf.line(0, middleY, pageWidth, middleY);

            // Nom du fichier basé sur le destinataire et la date
            const destinataireName = "{{ $pli->destinataire_name }}".replace(/\s+/g, '_'); // Remplace les espaces par des underscores
            const date = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
            const filename = `${destinataireName}_${date}.pdf`;

            // Sauvegarder le fichier PDF
            pdf.save(filename);
        } catch (error) {
            console.error("Erreur lors de la génération du PDF :", error);
        } finally {
            tempContainer.remove();
        }
    } */
    /* async function genererPDF() {
    const { jsPDF } = window.jspdf;

    const pliTable = document.querySelector('#pliTable');
    if (!pliTable) {
        alert("Le tableau est introuvable.");
        return;
    }

    const tempContainer = document.createElement('div');
    tempContainer.style.position = 'absolute';
    tempContainer.style.top = '-10000px';
    tempContainer.style.padding = '20px';
    tempContainer.style.backgroundColor = 'white';

    const tableClone1 = pliTable.cloneNode(true);
    const tableClone2 = pliTable.cloneNode(true);

    [tableClone1, tableClone2].forEach((table, index) => {
        table.style.tableLayout = 'fixed'; // Fixe la mise en page
        table.style.width = '100%'; // Largeur du tableau
        table.style.fontSize = '0.9rem';
        table.style.margin = '10px auto';
        table.style.whiteSpace = 'nowrap';

        const cells = table.querySelectorAll('td, th');
        cells.forEach(cell => {
            cell.style.border = '1px solid #888';
            cell.style.padding = '5px';
            cell.style.textAlign = 'left';
            cell.style.overflow = 'hidden'; // Empêcher les débordements
        });

        if (index === 0) {
            table.style.marginTop = '45px';
        } else {
            table.style.marginTop = '280px';
        }
    });

    tempContainer.appendChild(tableClone1);
    tempContainer.appendChild(tableClone2);

    document.body.appendChild(tempContainer);

    try {
        const canvas = await html2canvas(tempContainer, { scale: 2 });
        const imgData = canvas.toDataURL('image/png');

        const pdf = new jsPDF('p', 'pt', 'a4');
        const pageWidth = pdf.internal.pageSize.getWidth();
        const imgHeight = (canvas.height * pageWidth) / canvas.width;

        pdf.addImage(imgData, 'PNG', 0, 0, pageWidth, imgHeight);

        const pageHeight = pdf.internal.pageSize.getHeight();
        const middleY = pageHeight / 2;

        pdf.setLineWidth(0.5);
        pdf.setDrawColor(0, 0, 0);
        pdf.setLineDash([5, 3]);
        pdf.line(0, middleY, pageWidth, middleY);

        const destinataireName = "{{ $pli->destinataire_name }}".replace(/\s+/g, '_');
        const date = new Date().toISOString().split('T')[0];
        const filename = `${destinataireName}_${date}.pdf`;

        pdf.save(filename);
    } catch (error) {
        console.error("Erreur lors de la génération du PDF :", error);
    } finally {
        tempContainer.remove();
    }
}
 */

 async function genererPDF() {
    const { jsPDF } = window.jspdf;

    const pliTable = document.querySelector('#pliTable');
    if (!pliTable) {
        alert("Le tableau est introuvable.");
        return;
    }

    const tempContainer = document.createElement('div');
    tempContainer.style.position = 'absolute';
    tempContainer.style.top = '-10000px';
    tempContainer.style.padding = '20px';
    tempContainer.style.backgroundColor = 'white';

    const tableClone1 = pliTable.cloneNode(true);
    const tableClone2 = pliTable.cloneNode(true);

    [tableClone1, tableClone2].forEach((table, index) => {
        table.style.tableLayout = 'fixed';
        table.style.width = '100%';
        table.style.fontSize = '0.9rem';
        table.style.margin = '10px auto';
        table.style.whiteSpace = 'nowrap';

        const cells = table.querySelectorAll('td, th');
        cells.forEach(cell => {
            cell.style.border = '1px solid #888';
            cell.style.padding = '5px';
            cell.style.textAlign = 'left';
            cell.style.overflow = 'hidden';
        });

        // Supprimer les bordures de la cellule contenant le logo
        const logoCell = table.querySelector('th[colspan]');
        if (logoCell) {
            logoCell.style.border = 'none'; // Supprimer la bordure
        }

        if (index === 0) {
            table.style.marginTop = '45px';
        } else {
            table.style.marginTop = '280px';
        }
    });

    tempContainer.appendChild(tableClone1);
    tempContainer.appendChild(tableClone2);

    document.body.appendChild(tempContainer);

    try {
        const canvas = await html2canvas(tempContainer, { scale: 2 });
        const imgData = canvas.toDataURL('image/png');

        const pdf = new jsPDF('p', 'pt', 'a4');
        const pageWidth = pdf.internal.pageSize.getWidth();
        const imgHeight = (canvas.height * pageWidth) / canvas.width;

        pdf.addImage(imgData, 'PNG', 0, 0, pageWidth, imgHeight);

        const pageHeight = pdf.internal.pageSize.getHeight();
        const middleY = pageHeight / 2;

        pdf.setLineWidth(0.5);
        pdf.setDrawColor(0, 0, 0);
        pdf.setLineDash([5, 3]);
        pdf.line(0, middleY, pageWidth, middleY);

        const destinataireName = "{{ $pli->destinataire_name }}".replace(/\s+/g, '_');
        const date = new Date().toISOString().split('T')[0];
        const filename = `${destinataireName}_${date}.pdf`;

        pdf.save(filename);
    } catch (error) {
        console.error("Erreur lors de la génération du PDF :", error);
    } finally {
        tempContainer.remove();
    }
}


</script>



@endsection
