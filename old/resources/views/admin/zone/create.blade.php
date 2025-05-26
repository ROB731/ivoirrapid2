@extends('layouts.master')

@section('title', 'Ajouter une zone')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Ajouter une nouvelle zone</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Nouvelle Zone</li>
        </ol>

        <div class="card">
            <div class="card-header">
                <h3>Ajouter une zone</h3>
            </div>
            <div class="card-body">
                @if($errors->any())
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger" role="alert">
                            {{ $error }}
                        </div>
                    @endforeach
                @endif

                <form id="zoneForm" action="{{ url('admin/add-zone') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Commune -->
                    <div class="mb-3">
                        <label for="Commune" class="form-label">Commune</label>
                        <input type="text" class="form-control" id="Commune" name="Commune" required>
                    </div>

                    <!-- PlageZone -->
                    <div class="mb-3">
                        <label for="PlageZone" class="form-label">Plage Zone</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="PlageZone" 
                            name="PlageZone" 
                            placeholder="Exemple : 001-005" 
                            pattern="\d{3}-\d{3}" 
                            required>
                        <div class="form-text">Entrez une plage de zone (ex : 001-005).</div>
                    </div>

                    <!-- Zone Details -->
                    <div id="zoneDetails" class="mt-4"></div>

                    <button type="submit" class="btn btn-primary mt-3">Ajouter</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const plageZoneInput = document.getElementById('PlageZone');
            const zoneDetailsContainer = document.getElementById('zoneDetails');

            plageZoneInput.addEventListener('input', function () {
                const plageValue = plageZoneInput.value.trim();
                const match = plageValue.match(/^(\d{3})-(\d{3})$/);

                if (!match) {
                    zoneDetailsContainer.innerHTML = '<div class="alert alert-danger">Format invalide. Utilisez une plage comme 001-005.</div>';
                    return;
                }

                const start = parseInt(match[1]);
                const end = parseInt(match[2]);

                if (start > end || start < 1 || end > 999) {
                    zoneDetailsContainer.innerHTML = '<div class="alert alert-danger">Plage invalide. Assurez-vous que 001 ≤ début ≤ fin ≤ 999.</div>';
                    return;
                }

                // Réinitialiser le contenu
                zoneDetailsContainer.innerHTML = '';

                for (let i = start; i <= end; i++) {
                    const paddedNumber = i.toString().padStart(3, '0');

                    const zoneGroup = `
                        <div class="mb-3 border p-3">
                            <h5 class="mb-3">Zone ${paddedNumber}</h5>

                            <!-- Numéro Zone -->
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="NumeroZone-${paddedNumber}" class="form-label">Numéro Zone</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="NumeroZone-${paddedNumber}" 
                                        name="NumeroZone[${paddedNumber}]" 
                                        value="${paddedNumber}" 
                                        required>
                                </div>

                                <!-- Nom Zone -->
                                <div class="col-md-3">
                                    <label for="NomZone-${paddedNumber}" class="form-label">Nom Zone</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="NomZone-${paddedNumber}" 
                                        name="NomZone[${paddedNumber}]" 
                                        required>
                                </div>

                                <!-- Nom du Coursier -->
                                <div class="col-md-3">
                                    <label for="CoursierName-${paddedNumber}" class="form-label">Nom du Coursier</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="CoursierName-${paddedNumber}" 
                                        name="CoursierName[${paddedNumber}]" 
                                        required>
                                </div>

                                <!-- Code Coursier -->
                                <div class="col-md-3">
                                    <label for="CoursierCode-${paddedNumber}" class="form-label">Code Coursier</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="CoursierCode-${paddedNumber}" 
                                        name="CoursierCode[${paddedNumber}]" 
                                        required>
                                </div>
                            </div>

                            <!-- Contact du Coursier -->
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="CoursierPhone-${paddedNumber}" class="form-label">Contact du Coursier</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="CoursierPhone-${paddedNumber}" 
                                        name="CoursierPhone[${paddedNumber}]" 
                                        required>
                                </div>
                            </div>
                        </div>
                    `;

                    zoneDetailsContainer.insertAdjacentHTML('beforeend', zoneGroup);
                }
            });
        });
    </script>
@endsection
