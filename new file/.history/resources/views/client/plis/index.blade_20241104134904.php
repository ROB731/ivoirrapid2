@extends('layout.master')

@section('title', 'IvoirRp - Détails des Plis')

@section('content')
<div class="container-fluid px-4 my-3">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Détails des Plis</h4>
            <button class="btn btn-primary" onclick="printTable()">Imprimer</button>
        </div>
        <div class="card-body">
            @foreach($plis as $pli)
            <div class="row mb-4 border-bottom pb-3">
                {{-- Informations sur le Pli --}}
                <div class="col-md-4">
                    <h5>Informations du Pli {{ $pli->code }}</h5>
                    <ul class="list-group mb-3 border-0">
                        <li class="list-group-item border-0"><strong>Type:</strong> {{ $pli->type }}</li>
                        <li class="list-group-item border-0"><strong>Nombre de pièces:</strong> {{ $pli->nombre_de_pieces }}</li>
                        <li class="list-group-item border-0"><strong>Date de creation:</strong> {{ $pli->created_at->format('d/m/Y H:i') }}</li>
                    </ul>
                    <h5>Références</h5>
                    <ul class="list-group mb-3 border-0">
                        @foreach(explode(',', $pli->reference) as $reference) {{-- Split the references by comma --}}
                            <li class="list-group-item border-0">{{ trim($reference) }}</li> {{-- Display each reference on a new line --}}
                        @endforeach
                    </ul>
                </div>
                

                {{-- Informations sur l'Expéditeur --}}
                <div class="col-md-4">
                    <h5>Informations de l'Expéditeur</h5>
                    <ul class="list-group mb-3 border-0">
                        <li class="list-group-item border-0"><strong>Nom:</strong> {{ $pli->user_name }}</li>
                        <li class="list-group-item border-0"><strong>Email:</strong> {{ $pli->user_email }}</li>
                        <li class="list-group-item border-0"><strong>Tél:</strong> {{ $pli->user_Telephone }}</li>
                        <li class="list-group-item border-0"><strong>Adresse:</strong> {{ $pli->user_Adresse }}</li>
                        <li class="list-group-item border-0"><strong>Zone:</strong> {{ $pli->user_Zone }}</li>
                        <li class="list-group-item border-0"><strong>Cell:</strong> {{ $pli->user_Cellulaire }}</li>
                        <li class="list-group-item border-0"><strong>Autres:</strong> {{ $pli->user_Autre }}</li>
                    </ul>
                </div>

                {{-- Informations sur le Destinataire --}}
                <div class="col-md-4">
                    <h5>Informations du Destinataire</h5>
                    <ul class="list-group mb-3 border-0">
                        <li class="list-group-item border-0"><strong>Nom:</strong> {{ $pli->destinataire_name }}</li>
                        <li class="list-group-item border-0"><strong>Adresse:</strong> {{ $pli->destinataire_adresse }}</li>
                        <li class="list-group-item border-0"><strong>Tél:</strong> {{ $pli->destinataire_telephone }}</li>
                        <li class="list-group-item border-0"><strong>Email:</strong> {{ $pli->destinataire_email }}</li>
                        <li class="list-group-item border-0"><strong>Zone:</strong> {{ $pli->destinataire_zone }}</li>
                        <li class="list-group-item border-0"><strong>Contact:</strong> {{ $pli->destinataire_contact }}</li>
                        <li class="list-group-item border-0"><strong>Autres:</strong> {{ $pli->destinataire_autre }}</li>
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@section('scripts')
<script>
    function printTable() {
        window.print();
    }
</script>
@endsection
@endsection



@extends('layout.master')

@section('title', 'IvoirRp - Admin Plis')

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
