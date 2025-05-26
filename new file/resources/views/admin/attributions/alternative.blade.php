@extends('layouts.master')

@section('content')
    <div class="container">
        <h1>Choix de Coursier Alternatif</h1>

        <p>Il n'y a pas de coursier disponible pour {{ $type }} dans la zone sélectionnée.</p>
        <p>Veuillez choisir un coursier alternatif :</p>

        <form action="{{ route('admin.attributions.confirmer', [$pli->id, $type]) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="coursier">Coursier disponible :</label>
                <select name="coursier_id" id="coursier" class="form-control" required>
                    @foreach($coursiers as $coursier)
                        <option value="{{ $coursier->id }}">{{ $coursier->nom }} {{ $coursier->prenoms }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Confirmer l'Attribution</button>
        </form>
    </div>
@endsection
