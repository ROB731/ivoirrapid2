
@extends('layout.master')

@section('title', 'IvoirRp - Modifier un Destinataire')

@section('content')
@if($message = Session::get('message'))
    <div class="alert alert-success alert-dismissible fade show">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <strong>{{ $message }}</strong>
    </div>
@endif

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ url('client/update-destinataire/'.$destinataire->id) }}" method="POST" class="mt-5 bg-light p-5 rounded shadow" enctype="multipart/form-data" id="clientForm">
                @csrf
                @method('PUT')
                 <!-- Nom destinataire -->
                 <div class="mb-3">
                    <label for="name" class="form-label">Nom<span class="text-danger">*</span></label>
                    <input type="text" class="form-control bg-primary text-white @error('name') is-invalid @enderror" id="name" name="name" required value="{{ $destinataire->name }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Téléphone -->
                <div class="mb-3">
                    <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                    <input type="text" class="form-control bg-primary text-white @error('telephone') is-invalid @enderror" id="telephone" name="telephone" pattern="^\d{10}$" maxlength="10" required value="{{ $destinataire->telephone }}">
                    @error('telephone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- contact -->
                <div class="mb-3">
                    <label for="contact" class="form-label">Contact <span class="text-danger">*</span></label>
                    <input type="text" class="form-control bg-primary text-white @error('contact') is-invalid @enderror" id="contact" name="contact" pattern="^\d{10}$" maxlength="10" required value="{{ $destinataire->contact }}">
                    @error('Cellulaire')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control bg-primary text-white @error('email') is-invalid @enderror" id="email" name="email" value="{{ $destinataire->email }}">
                    @error('Email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <input type="text" class="form-control bg-primary text-white @error('adresse') is-invalid @enderror" id="adresse" name="adresse" value="{{ $destinataire->adresse }}">
                    @error('RCCM')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- zone -->
                <div class="mb-3">
                    <label for="zone" class="form-label">Zone <span class="text-danger">*</span></label>
                    <input type="text" class="form-control bg-primary text-white @error('zone') is-invalid @enderror" id="zone" name="zone" required value="{{ $destinataire->zone }}">
                    @error('zone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- autre (Note) -->
                <div class="mb-3">
                    <label for="autre" class="form-label">Autre</label>
                    <textarea class="form-control bg-primary text-white" id="autre" name="autre" rows="3">{{ $destinataire->autre }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Modifier</button>
        </form>
        </div>
    </div>
</div>
@endSection
