@extends('layouts.master')

@section('title', 'Modifier une zone')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Modifier une zone</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Modification Zone</li>
        </ol>

        <div class="card">
            <div class="card-header">
                <h3>Modifier une zone</h3>
            </div>
            <div class="card-body">
                @if($errors->any())
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger" role="alert">
                            {{ $error }}
                        </div>
                    @endforeach
                @endif
                <form id="zoneForm" method="POST" action="{{ route('admin.zone.update', $zone->id) }}">
                    @csrf
                    @method('PUT')
                

                    <!-- Commune -->
                    <div class="mb-3">
                        <label for="Commune" class="form-label">Commune</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="Commune" 
                            name="Commune" 
                            value="{{ old('Commune', $zone->Commune) }}" 
                            required>
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
                            value="{{ old('PlageZone', $zone->PlageZone) }}" 
                            pattern="\d{3}-\d{3}" 
                            required>
                        <div class="form-text">Entrez une plage de zone (ex : 001-005). Les champs seront générés dynamiquement.</div>
                    </div>

                    <!-- Zone Details -->
                    <div id="zoneDetails" class="mt-4">
                        @foreach ($zone->details as $zoneDetail)
                            <div class="mb-3 border p-3">
                                <h5 class="mb-3">Zone {{ $zoneDetail->NumeroZone }}</h5>

                                <!-- Numéro Zone -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="NumeroZone-{{ $zoneDetail->NumeroZone }}" class="form-label">Numéro Zone</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="NumeroZone-{{ $zoneDetail->NumeroZone }}" 
                                            name="NumeroZone[{{ $zoneDetail->NumeroZone }}]" 
                                            value="{{ old('NumeroZone.' . $zoneDetail->NumeroZone, $zoneDetail->NumeroZone) }}" 
                                            required>
                                    </div>

                                    <!-- Nom Zone -->
                                    <div class="col-md-3">
                                        <label for="NomZone-{{ $zoneDetail->NumeroZone }}" class="form-label">Nom Zone</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="NomZone-{{ $zoneDetail->NumeroZone }}" 
                                            name="NomZone[{{ $zoneDetail->NumeroZone }}]" 
                                            value="{{ old('NomZone.' . $zoneDetail->NumeroZone, $zoneDetail->NomZone) }}" 
                                            required>
                                    </div>

                                    <!-- Nom du Coursier -->
                                    <div class="col-md-3">
                                        <label for="CoursierName-{{ $zoneDetail->NumeroZone }}" class="form-label">Nom du Coursier</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="CoursierName-{{ $zoneDetail->NumeroZone }}" 
                                            name="CoursierName[{{ $zoneDetail->NumeroZone }}]" 
                                            value="{{ old('CoursierName.' . $zoneDetail->NumeroZone, $zoneDetail->CoursierName) }}" 
                                            required>
                                    </div>

                                    <!-- Code Coursier -->
                                    <div class="col-md-3">
                                        <label for="CoursierCode-{{ $zoneDetail->NumeroZone }}" class="form-label">Code Coursier</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="CoursierCode-{{ $zoneDetail->NumeroZone }}" 
                                            name="CoursierCode[{{ $zoneDetail->NumeroZone }}]" 
                                            value="{{ old('CoursierCode.' . $zoneDetail->NumeroZone, $zoneDetail->CoursierCode) }}" 
                                            required>
                                    </div>
                                </div>

                                <!-- Contact du Coursier -->
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label for="CoursierPhone-{{ $zoneDetail->NumeroZone }}" class="form-label">Contact du Coursier</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="CoursierPhone-{{ $zoneDetail->NumeroZone }}" 
                                            name="CoursierPhone[{{ $zoneDetail->NumeroZone }}]" 
                                            value="{{ old('CoursierPhone.' . $zoneDetail->NumeroZone, $zoneDetail->CoursierPhone) }}" 
                                            required>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Modifier</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Script pour gérer la génération dynamique des champs -->
    <script>
        document.getElementById('PlageZone').addEventListener('input', function () {
            const zoneDetails = document.getElementById('zoneDetails');
            const plageZone = this.value.trim();
            const regex = /^\d{3}-\d{3}$/;

            if (!regex.test(plageZone)) {
                zoneDetails.innerHTML = '';
                return;
            }

            const [start, end] = plageZone.split('-').map(Number);
            const numZones = end - start + 1;

            zoneDetails.innerHTML = '';

            for (let i = 0; i < numZones; i++) {
                const numeroZone = start + i;

                zoneDetails.innerHTML += `
                    <div class="mb-3 border p-3">
                        <h5 class="mb-3">Zones ${numeroZone}</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Nom Zone</label>
                                <input type="text" class="form-control" name="NomZone[${numeroZone}]" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Nom du Coursier</label>
                                <input type="text" class="form-control" name="CoursierName[${numeroZone}]" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Code Coursier</label>
                                <input type="text" class="form-control" name="CoursierCode[${numeroZone}]" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Contact du Coursier</label>
                                <input type="text" class="form-control" name="CoursierPhone[${numeroZone}]" required>
                            </div>
                        </div>
                    </div>
                `;
            }
        });
    </script>
@endsection
