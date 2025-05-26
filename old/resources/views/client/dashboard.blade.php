@extends('layout.master')

@section('title', 'IvoirRp - Client Dashboard')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Tableau de bord</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"></li>
        </ol>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">Total destinataire</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">{{ $totalDestinataires }}</a>
                        <div class="medium text-white"><i class="fas fa-user-check"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">Total plis</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">{{ $totalPlis }}</a>
                        <div class="medium text-white"><i class="fas fa-archive"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

