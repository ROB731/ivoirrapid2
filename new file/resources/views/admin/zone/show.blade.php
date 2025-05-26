@extends('layouts.master')

@section('title', 'Détails de la zone')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Détails de la zone</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('admin.zone.index') }}">Zones</a></li>
            <li class="breadcrumb-item active">Détails de la zone</li>
        </ol>

        <div class="card">
            <div class="card-header">
                <h3>Zone : {{ $zone->Commune }} ({{ $zone->PlageZone }})</h3>
            </div>
            <div class="card-body">
                @if($zone->details->isEmpty())
                    <p>Aucun détail disponible pour cette zone.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nom de la Zone</th>
                                    <th>Nom du Coursier</th>
                                    <th>Code du Coursier</th>
                                    <th>Contact du Coursier</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($zone->details as $detail)
                                    <tr>
                                        <td>{{ $detail->NomZone }}</td>
                                        <td>{{ $detail->CoursierName }}</td>
                                        <td>{{ $detail->CoursierCode }}</td>
                                        <td>{{ $detail->CoursierPhone }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <a href="{{ route('admin.zone.index') }}" class="btn btn-secondary mt-3">Retour</a>
            </div>
        </div>
    </div>
@endsection
