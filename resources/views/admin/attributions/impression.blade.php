@extends('layouts.master')

@section('title', 'IvoirRp - Plis')

@section('content')
    <style>
        /* Styles généraux */
        .btn-container {
            display: flex;
            justify-content: space-between; /* Aligne les boutons à gauche et à droite */
            align-items: center;
            margin-bottom: 20px;
        }

        .btn-container .btn {
            margin-left: 10px; /* Petit espace entre les boutons */
        }

        .form-container {
            display: flex;
            justify-content: flex-start; /* Formulaire aligné à gauche */
            gap: 15px; /* Espace entre le formulaire et les boutons */
            flex-wrap: wrap; /* S'adapte en cas d'écran étroit */
        }

        .logo-impression {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Styles pour l'impression */
        @media print {
            table.table {
                table-layout: fixed;
                width: 100%;
            }

            table.table th,
            table.table td {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            body {
                margin: 0;
                font-size: 12px;
            }

            .btn, .form-container, .btn-container {
                display: none !important;
            }
        }
    </style>

    <!-- Container pour le formulaire et les boutons -->
    <div class="btn-container">
        <!-- Formulaire de filtrage par coursier -->
        <div class="form-container my-3 mx-3">
            <form action="{{ route('admin.attributions.impression') }}" method="GET">
                <div class="form-group">
                    <label for="coursier_id">Sélectionner un Coursier :</label>
                    <select name="coursier_id" id="coursier_id" class="form-control">
                        <option value="">Tous les Coursiers</option>
                        @foreach($coursiers as $coursier)
                            <option value="{{ $coursier->id }}" {{ request('coursier_id') == $coursier->id ? 'selected' : '' }}>
                                {{ $coursier->prenoms }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Bouton pour filtrer -->
                <button type="submit" class="btn btn-primary my-2 mx-0">
                    <i class="fas fa-filter"></i>
                </button>
                <!-- Bouton pour générer le PDF -->
                <button type="button" onclick="genererPDF()" class="btn btn-success">
                    <i class="fas fa-file-download"></i>
                </button>
                
            </form>
        

        </div>

       
    </div>

    <!-- Logo en haut du tableau -->
    <div class="logo-impression">
        <img src="{{ asset('asset/Logo IRN.png') }}" alt="Logo" class="img-fluid" style="max-width: 150px;">
    </div>

    <!-- Affichage du message si aucun pli n'est trouvé -->
    @if($plis->isEmpty())
        <div class="alert alert-info">
            Aucun pli trouvé pour les critères sélectionnés.
        </div>
    @else
        <!-- Tableau des plis attribués -->
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>N° de Suivi</th>
                    <th>Client</th>
                   <!-- <th>Téléphone Client</th>
                    <th>Contact Client</th>-->
                    <th>Nombre de Pièces</th>
                    <th>Signature et cachet</th>
                </tr>
            </thead>
            <tbody>
                @foreach($plis as $pli)
                <tr>
                    <td>{{ $pli->created_at->format('d/m/Y') }}</td> <!-- Format de la date -->
                    <td>{{ $pli->code }}</td>
                    <td>{{ $pli->user_name }}</td>
                   <!-- <td>{{ $pli->user_Telephone }}</td>
                    <td>{{ $pli->user_Cellulaire }}</td>-->
                    <td>{{ $pli->nombre_de_pieces }}</td>
                    <td></td> <!-- Gestion de la signature -->
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Scripts pour la génération de PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
    async function genererPDF() {
    const { jsPDF } = window.jspdf;
    const logo = document.querySelector('.logo-impression');
    const tableau = document.querySelector('table');

    if (!logo || !tableau) {
        alert("Logo ou tableau introuvable pour la génération du PDF.");
        return;
    }

    // Récupérer le nom du coursier sélectionné
    const coursierNom = document.querySelector('#coursier_id option:checked').textContent;

    // Récupérer la date actuelle
    const date = new Date();
    const dateString = date.toISOString().slice(0, 10); // Format YYYY-MM-DD

    const tempContainer = document.createElement('div');
    tempContainer.appendChild(logo.cloneNode(true));
    tempContainer.appendChild(tableau.cloneNode(true));

    document.body.appendChild(tempContainer);
    tempContainer.style.position = "absolute";
    tempContainer.style.top = "-10000px";

    try {
        const canvas = await html2canvas(tempContainer, { scale: 2 });
        const imgData = canvas.toDataURL('image/png');

        const pdf = new jsPDF('p', 'pt', 'a4');
        const pageWidth = pdf.internal.pageSize.getWidth();
        const pageHeight = pdf.internal.pageSize.getHeight();

        const imgWidth = pageWidth;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;

        pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);

        // Générer le nom de fichier avec la date et le nom du coursier
        const nomFichier = `plis ramassage_${coursierNom.replace(/\s+/g, '_')}_${dateString}.pdf`; // Remplacer les espaces par des underscores

        pdf.save(nomFichier); // Sauvegarder avec le nom généré
    } catch (error) {
        console.error("Erreur lors de la génération du PDF :", error);
        alert("Une erreur est survenue lors de la génération du PDF.");
    } finally {
        tempContainer.remove();
    }
}

    </script>
@endsection
