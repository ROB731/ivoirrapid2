@extends('layouts.master')

@section('title', 'IvoirRp - Admin Destinataires')

@section('content')
<div class="container-fluid px-4 my-3">
    <div class="card">
        <div class="card-header">
            <h4>Liste des destinataires</h4>
        </div>
    </div>

    <!-- Champ de recherche -->
    <div class="mb-3" style="max-width: 500px; margin-top: 10px">
        <div class="input-group">
            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un destinataire..." aria-label="Rechercher un destinataire..." />
            <button class="btn btn-primary" id="btnSearch" type="button"><i class="fas fa-search"></i></button>
        </div>
    </div>

    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table class="table table-striped table-bordered mt-3">
            <thead class="table-light">
                <tr>
                    <th>Client ID</th>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Zone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($destinataires as $destinataire)
                    <tr>
                        <td>{{ $destinataire->user_id }}</td>
                        <td>{{ $destinataire->name }}</td>
                        <td>{{ $destinataire->adresse }}</td>
                        <td>{{ $destinataire->telephone }}</td>
                        <td>{{ $destinataire->email }}</td>
                        <td>{{ $destinataire->zone }}</td>
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
                    0, // client id
                    1, // nom
                    4, // zone

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
