@extends('layouts.master')

@section('title', 'Liste des zones')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Liste des zones</h1>

        <!-- Message de succès -->
        @if(session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Zones</li>
        </ol>

        <!-- Barre de recherche -->
        <div class="mb-3">
            <input type="text" id="searchCommune" class="form-control" placeholder="Rechercher une commune..." onkeyup="searchTable()">
        </div>

        <!-- Liste des zones -->
        <div class="card">
            <div class="card-header">
                <h3>Zones</h3>
            </div>
            <div class="card-body">
                @if($zones->isEmpty())
                    <p>Aucune zone n'a été ajoutée pour le moment.</p>
                @else
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-bordered" id="zonesTable">
                            <thead>
                                <tr>
                                    <th>Commune</th>
                                    <th>Plage de Zone</th>
                                    <th>Zones</th>
                                    <th>Nombre de Zones</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($zones as $zone)
                                    <tr>
                                        <td>{{ $zone->Commune }}</td>
                                        <td>{{ $zone->PlageZone }}</td>
                                        <td>
                                            <ul>
                                                @foreach($zone->details as $detail)
                                                    <li>{{ $detail->NomZone }} (Numéro: {{ $detail->NumeroZone }})</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>{{ $zone->details->count() }} zones</td>
                                        <td>
                                            <a href="{{ route('admin.zone.show', $zone->id) }}" class="text-info me-2" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ url('admin/edit-zone/'.$zone->id) }}" class="text-primary me-2" title="Éditer">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ url('admin/delete-zone/'.$zone->id) }}" class="text-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette zone ?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $zones->links() }}
                    </div>
                @endif
            </div>
        </div>
        
    </div>

    <!-- Script de recherche en temps réel -->
    <script>
        // Fonction de recherche pour filtrer les communes en temps réel
        function searchTable() {
            let input = document.getElementById('searchCommune');
            let filter = input.value.toUpperCase();
            let table = document.getElementById('zonesTable');
            let trs = table.getElementsByTagName('tr');

            for (let i = 1; i < trs.length; i++) { // Commencer à partir de 1 pour ignorer l'en-tête
                let tds = trs[i].getElementsByTagName('td');
                let matched = false;
                // On recherche dans la première colonne (Commune)
                if (tds[0]) {
                    let txtValue = tds[0].textContent || tds[0].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        matched = true;
                    }
                }
                trs[i].style.display = matched ? "" : "none";
            }
        }
    </script>
@endsection
