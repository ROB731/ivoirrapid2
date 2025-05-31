   @extends('layouts.master')

    @section('title', 'SERVICES CHEQUES ')

    @section('content')

    @include('admin.services-cheques.ajouter')

    @php
        $cheque =\App\Models\Pli::where('type','cheque')
    @endphp


 
    <div class="container">
        <h1 class="title">Gestion des Chèques</h1>

        <!-- Statistiques -->
        <div class="stats">
            <div class="card blue" data-bs-toggle="modal" data-bs-target="#chequeAjouter">
                <h4>Chèques Ajoutés</h4>
                <p>42</p>
            </div>
            <div class="card green">
                <h4>Chèques Récupérés</h4>
                <p>16</p>
            </div>
            <div class="card yellow">
                <h4>Chèques Transmis</h4>
                <p>8</p>
            </div>
        </div>

        <!-- Historique -->
        <h2 class="subtitle">Historique des Chèques</h2>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th>Aujourd'hui</th>
                    <th>Hier</th>
                    <th>La semaine dernière</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><button class="btn btn-primary">0</button></td>
                    <td><button class="btn btn-success">0</button></td>
                    <td><button class="btn btn-warning">0</button></td>
                    <td><button class="btn btn-info">Voir plus</button></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal Bootstrap -->
    <div class="modal fade" id="chequeAjouter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter un Chèque</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p>Formulaire d'ajout ici...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>




        <!-- Historique -->
        <h2 class="subtitle">Historique des Chèques</h2>
        <table>
            <thead>
                <tr>
                    <th>Aujourd'hui</th>
                    <th>Hier</th>
                    <th>La semaine dernière</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><button class="btn blue">0</button></td>
                    <td><button class="btn green">0</button></td>
                    <td><button class="btn yellow">0</button></td>
                    <td><button class="btn purple">Voir plus</button></td>
                </tr>
            </tbody>
        </table>
    </div>




    @php
    $cheques = \App\Models\Pli::where('type', 'cheque')->get();
@endphp

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Montant</th>
            <th>Date</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($cheques as $cheque)
        <tr>
            <td>{{ $cheque->id }}</td>
            {{-- <td>{{ number_format($cheque->montant, 2) }} FCFA</td> --}}
            <td>{{ $cheque->code ?? 0 }} </td>
            <td>{{ $cheque->date->date ?? 0  }}</td>
            <td>
                <span class="badge {{ $cheque->statut == 'Validé' ? 'badge-success' : 'badge-warning' }}">
                    {{ $cheque->statut ?? 0}}
                </span>
            </td>
            <td>
                <button class="btn btn-primary">Détails</button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">Aucun chèque trouvé</td>
        </tr>
        @endforelse
    </tbody>
</table>



    <style>
            /* style.css */
                /* body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 20px;
                } */

                .container {
                    max-width: 100%;
                    margin: auto;
                    background: white;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                .title {
                    text-align: center;
                    font-size: 24px;
                    color: #333;
                }

                .stats {
                    display: flex;
                    gap: 10px;
                    justify-content: space-between;
                }

                .card {
                    flex: 1;
                    padding: 5px;
                    text-align: center;
                    border-radius: 8px;
                    font-size: 14px;
                    font-weight: bold;
                    color: white;
                }

                .blue { background: #007bff; }
                .green { background: #28a745; }
                .yellow { background: #ffc107; }
                .purple { background: #6f42c1; }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }

                th, td {
                    border: 1px solid #ddd;
                    padding: 10px;
                    text-align: center;
                }

                .btn {
                    padding: 8px 15px;
                    border: none;
                    border-radius: 5px;
                    color: white;
                    cursor: pointer;
                }

                .btn.blue { background: #007bff; }
                .btn.green { background: #28a745; }
                .btn.yellow { background: #ffc107; }
                .btn.purple { background: #6f42c1; }

    </style>


<style>

        .table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

th {
    background: #007bff;
    color: white;
    font-weight: bold;
}

.badge {
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 14px;
}

.badge-success {
    background: #28a745;
    color: white;
}

.badge-warning {
    background: #ffc107;
    color: black;
}

.btn {
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    color: white;
    cursor: pointer;
    transition: background 0.3s ease-in-out;
}

.btn-primary {
    background: #007bff;
}
.btn-primary:hover {
    background: #0056b3;
}


</style>







@endsection
