@extends('layout.master')

@section('title', 'IvoirRp - destinataires')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">destinataires</h1>

    <div class="card">
        <div class="card-header">
            <h4>Liste des Destinataires <a href="{{ url('client/add-destinataire') }}" class="btn btn-primary btn-sm float-end">Ajouter un destinataire</a></h4>
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
                    <th scope="col" class="text-nowrap">Nom</th>
                    <th scope="col" class="text-nowrap">Contact</th>
                    <th scope="col" class="text-nowrap">Telephone</th>
                    <th scope="col" class="text-nowrap">Email</th>
                    <th scope="col" class="text-nowrap">Adresse</th>
                    <th scope="col" class="text-nowrap">Zone</th>
                    <th scope="col" class="text-nowrap">Autre</th>
                    <th scope="col" class="text-nowrap" style="position: sticky; right: 0; background-color: white; z-index: 10;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($destinataires as $destinataire)
                <tr>
                    <td>{{ $destinataire->name }}</td>
                    <td>{{ $destinataire->telephone }}</td>
                    <td>{{ $destinataire->contact }}</td>
                    <td>{{ $destinataire->email }}</td>
                    <td>{{ $destinataire->adresse }}</td>
                    <td>{{ $destinataire->zone }}</td>
                    <td>{{ $destinataire->autre }}</td>
                    <td class="position-sticky" style="background-color: white; z-index: 10; right: 0;">
                        <a href="" class="btn btn-warning btn-sm my-2">Modifier</a>
                        <a href="" class="btn btn-danger btn-sm">Supprimer</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if(session('message'))
        <div class="alert alert-success" role="alert">
            {{ session('message') }}
        </div>
    @endif
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
                    1, // nom
                    2, // email
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


@extends('layout.master')

@section('title', 'IvoirRp - users')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Desti</h1>

    <div class="card">
        <div class="card-header">
            <h4>Liste des users <a href="{{ url('admin/add-user') }}" class="btn btn-primary btn-sm float-end">Ajouter un user</a></h4>
        </div>
    </div>

    <!-- Champ de recherche -->
    <div class="mb-3" style="max-width: 500px; margin-top: 10px">
        <div class="input-group">
            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un user..." aria-label="Rechercher un user..." />
            <button class="btn btn-primary" id="btnSearch" type="button"><i class="fas fa-search"></i></button>
        </div>
    </div>

    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table class="table table-striped table-bordered mt-3">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="text-nowrap">ID</th>
                    <th scope="col" class="text-nowrap">Nom Société</th>
                    <th scope="col" class="text-nowrap">Abreviation</th>
                    <th scope="col" class="text-nowrap">Téléphone</th>
                    <th scope="col" class="text-nowrap">Cellulaire</th>
                    <th scope="col" class="text-nowrap">Email</th>
                    <th scope="col" class="text-nowrap">Compte Contribuable</th>
                    <th scope="col" class="text-nowrap">RCCM</th>
                    <th scope="col" class="text-nowrap">Direction 1 (Nom et Prénoms)</th>
                    <th scope="col" class="text-nowrap">Direction 1 (Contact)</th>
                    <th scope="col" class="text-nowrap">Direction 2 (Nom et Prénoms)</th>
                    <th scope="col" class="text-nowrap">Direction 2 (Contact)</th>
                    <th scope="col" class="text-nowrap">Direction 3 (Nom et Prénoms)</th>
                    <th scope="col" class="text-nowrap">Direction 3 (Contact)</th>
                    <th scope="col" class="text-nowrap">Adresse</th>
                    <th scope="col" class="text-nowrap">Commune</th>
                    <th scope="col" class="text-nowrap">Quartier</th>
                    <th scope="col" class="text-nowrap">Rue</th>
                    <th scope="col" class="text-nowrap">Zone</th>
                    <th scope="col" class="text-nowrap">Autre</th>
                    <th scope="col" class="text-nowrap" style="position: sticky; right: 0; background-color: white; z-index: 10;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->abreviation }}</td>
                    <td>{{ $user->Telephone }}</td>
                    <td>{{ $user->Cellulaire }}</td>
                    <td>{{ $user->Email }}</td>
                    <td>{{ $user->Compte_contribuable }}</td>
                    <td class="text-nowrap">{{ $user->RCCM }}</td>
                    <td class="text-nowrap">{{ $user->Direction_1_Nom_et_Prenoms }}</td>
                    <td class="text-nowrap">{{ $user->Direction_1_Contact }}</td>
                    <td class="text-nowrap">{{ $user->Direction_2_Nom_et_Prenoms }}</td>
                    <td class="text-nowrap">{{ $user->Direction_2_Contact }}</td>
                    <td class="text-nowrap">{{ $user->Direction_3_Nom_et_Prenoms }}</td>
                    <td class="text-nowrap">{{ $user->Direction_3_Contact }}</td>
                    <td>{{ $user->Adresse }}</td>
                    <td>{{ $user->Commune }}</td>
                    <td>{{ $user->Quartier }}</td>
                    <td>{{ $user->Rue }}</td>
                    <td>{{ $user->Zone }}</td>
                    <td>{{ $user->Autre }}</td>
                    <td class="position-sticky" style="background-color: white; z-index: 10; right: 0;">
                        <a href="{{ url('admin/edit-user/'.$user->id) }}" class="btn btn-warning btn-sm my-2">Modifier</a>
                        <a href="{{ url('admin/delete-user/'.$user->id) }}" class="btn btn-danger btn-sm">Supprimer</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if(session('message'))
        <div class="alert alert-success" role="alert">
            {{ session('message') }}
        </div>
    @endif
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
                    1, // Code Unique
                    2, // Nom Société
                    7, // Compte Contribuable
                    19 // Zone
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