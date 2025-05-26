@extends('layouts.master')

@section('title', 'IvoirRp - Admin coursier')

@section('content')
<div class="container-fluid px-4 my-3">
    <div class="card">
        <div class="card-header">
            <h4>Liste des coursiers</h4>
        </div>
    </div>

    <!-- Champ de recherche -->
    <div class="mb-3" style="max-width: 500px; margin-top: 10px">
        <div class="input-group">
            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un coursier..." aria-label="Rechercher un coursier..." />
            <button class="btn btn-primary" id="btnSearch" type="button"><i class="fas fa-search"></i></button>
        </div>
    </div>

    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table class="table table-striped table-bordered mt-3">
            <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Prénoms</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Code</th>
                    <th>Numéro de Permis</th>
                    <th>Date de Validité du Permis</th>
                    <th>Catégorie du Permis</th>
                    <th>Numéro de CNI</th>
                    <th>Date de Validité de la CNI</th>
                    <th>Numéro de la CMU</th>
                    <th>Date de Validité de la CMU</th>
                    <th>Date de Naissance</th>
                    <th>Groupe Sanguin</th>
                    <th>Date de Début du Contrat</th>
                    <th>Date de Fin du Contrat</th>
                    <th>Adresse</th>
                    <th>Contact d'Urgence</th>
                    <th>Affiliation d'Urgence</th>
                    <th>Zone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coursiers as $coursier)
                    <tr>
                        <td>{{ $coursier->nom }}</td>
                        <td>{{ $coursier->prenoms }}</td>
                        <td>{{ $coursier->telephone }}</td>
                        <td>{{ $coursier->email }}</td>
                        <td>{{ $coursier->code }}</td>
                        <td>{{ $coursier->numero_de_permis }}</td>
                        <td>{{ $coursier->date_de_validite_du_permis }}</td>
                        <td>{{ $coursier->categorie_du_permis }}</td>
                        <td>{{ $coursier->numero_de_cni }}</td>
                        <td>{{ $coursier->date_de_validite_de_la_cni }}</td>
                        <td>{{ $coursier->numero_de_la_cmu }}</td>
                        <td>{{ $coursier->date_de_validite_de_la_cmu }}</td>
                        <td>{{ $coursier->date_de_naissance }}</td>
                        <td>{{ $coursier->groupe_sanguin }}</td>
                        <td>{{ $coursier->date_de_debut_du_contrat }}</td>
                        <td>{{ $coursier->date_de_fin_du_contrat }}</td>
                        <td>{{ $coursier->adresse }}</td>
                        <td>{{ $coursier->contact_urgence }}</td>
                        <td>{{ $coursier->affiliation_urgence }}</td>
                        <td>{{ $coursier->zone }}</td>
                        <td>
                            <a href="">Voir</a>
                            <a href="">Modifier</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('tbody tr');

        searchInput.addEventListener('keyup', function () {
            const searchTerm = this.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

            tableRows.forEach(function (row) {
                const columns = row.querySelectorAll('td');
                let match = false;

                // Vérifier les colonnes d'intérêt
                const searchColumns = [
                    0, // nom
                    1, // prénoms
                    2, // téléphone
                    3, // email
                    4, // code
                    5, // numéro de permis
                    6, // date de validité du permis
                    7, // catégorie du permis
                    8, // numéro de CNI
                    9, // date de validité de la CNI
                    10, // numéro de la CMU
                    11, // date de validité de la CMU
                    12, // date de naissance
                    13, // groupe sanguin
                    14, // date de début du contrat
                    15, // date de fin du contrat
                    16, // adresse
                    17, // contact d'urgence
                    18, // affiliation d'urgence
                    19  // zone
                ];

                searchColumns.forEach(colIndex => {
                    const cellText = columns[colIndex].textContent.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                    if (cellText.includes(searchTerm)) {
                        match = true;
                    }
                });

                // Afficher ou masquer la ligne
                row.style.display = match ? '' : 'none';
            });
        });
    });
</script>
@endsection
