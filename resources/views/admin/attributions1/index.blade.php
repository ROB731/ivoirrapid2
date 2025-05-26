@extends('layouts.master')

@section('content')
    <div class="container">
        <h1>Gestion des Attributions de Plis</h1>
        <div class="card">
            <div class="card-header">
                <h4>
                    <a href="{{ route('admin.plis.index') }}" class="btn btn-primary btn-sm float-end">Retour</a>
                </h4>
            </div>
        </div>

        <!-- Barre de recherche en JavaScript -->
        <input type="text" id="searchInput" class="form-control mb-3" placeholder="Rechercher par code du pli">

        <!-- Messages de notification -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tableau des attributions -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Destinataire</th>
                    <th>Expéditeur</th>
                    <th>Zone Destinataire</th>
                    <th>Zone Expéditeur</th>
                    <th>Type d'Attribution</th>
                </tr>
            </thead>
            <tbody id="pliTable">
                @foreach($plies as $pli)
                    <tr>
                        <td class="pli-code">{{ $pli->code }}</td>
                        <td>
                            <strong>{{ $pli->destinataire->name }}</strong><br>
                            <span class="text-muted">Tél: {{ $pli->destinataire->telephone ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <strong>{{ $pli->user->name }}</strong><br>
                            <span class="text-muted">Tél: {{ $pli->user->Telephone ?? 'N/A' }}</span>
                        </td>
                        <td>{{ $pli->destinataire->zone ?? 'N/A' }}</td>
                        <td>{{ $pli->user->Zone ?? 'N/A' }}</td>
                        <td>
                            <form action="{{ route('admin.attributions.attribuer', $pli->id) }}" method="POST">
                                @csrf
                                <select name="type" class="form-control" required>
                                    <option value="ramassage" 
                                        @if(optional($pli->attribution)->coursier_ramassage_id) disabled @endif>
                                        Ramassage
                                    </option>
                                    <option value="depot" 
                                        @if(optional($pli->attribution)->coursier_depot_id) disabled @endif>
                                        Dépôt
                                    </option>
                                </select>
                                <button type="submit" class="btn btn-primary mt-2">Attribuer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#pliTable tr');
            
            rows.forEach(row => {
                let code = row.querySelector('.pli-code').textContent.toLowerCase();
                if (code.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
@endsection
