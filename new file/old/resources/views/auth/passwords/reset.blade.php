@extends('layouts.app')

@section('content')
<div class="container py-3"> <!-- Réduction de la marge supérieure -->
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mb-3" style="margin-top: -30px;"> <!-- Remonter le logo -->
                <img src="{{ asset('asset/Logo IRN.png') }}" alt="Logo" class="img-fluid" width="50%">
            </div>
            <div class="card shadow-sm" style="margin-top: -20px;"> <!-- Réduction de l'espacement autour du formulaire -->
                <div class="card-header text-center bg-primary text-white">
                    <h4>{{ __('Réinitialiser le mot de passe') }}</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        
                        <!-- Email Input -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse e-mail</label>
                            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Password Input -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Password Confirmation -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Réinitialiser le mot de passe') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
