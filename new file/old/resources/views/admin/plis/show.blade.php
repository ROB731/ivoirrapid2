@extends('layout.master')

@section('title', 'Détails du Pli')

@section('content')
<!-- New include -->
   
    @include('some-modules')
<!-- /  New include -->


<div class="container-fluid px-4">
    
<div class="no-print">
    <h1 class="mt-4" style="font-size: 1.5rem;">Détails du Pli</h1>
    <!-- Bouton pour générer le PDF -->
    
    <!--<button onclick="genererPDF()" class="btn btn-success mb-3">Télécharger en PDF</button>-->
    
   <button onclick="imprimerTableau()" class="btn btn-warning mb-3">Imprimer</button>
   @yield('btn-print')
   
    
    <a href="{{ route('admin.plis.index') }}" class="btn btn-dark mb-3">Retour</a>
</div> <!--  / No print -->


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
    @media print{
                  .no-print,nav,header,footer{
                            display:none !important;
                       }
                       
                     .card-body{
                         display:block !important;
                     }
    }
</style>

<!-- Scripts pour la génération de PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    async function genererPDF() {
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
        tempContainer.style.padding = '25px'; // Ajoute une marge autour du conteneur
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
                table.style.marginTop = '10px'; // Remonter le premier tableau:  encore plus Ajuster le tableau
            } else {
                table.style.marginTop = '10px'; // Faire descendre le deuxième tableau ajuster le tableau
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
    }
</script>



@endsection
