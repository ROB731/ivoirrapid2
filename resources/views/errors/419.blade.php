

@extends('layouts.app')

<style>
    body {
        background: url('https://ivoirrapid.ci/asset/logo.png') no-repeat center center fixed;
        background-size: cover;
        color: #fff;
        text-align: center;
        font-family: 'Arial', sans-serif;
    }

    .error-container {
        margin-top: 12%;
    }

    h1 {
        font-size: 80px;
        font-weight: bold;
        color: #e63946;
        animation: fadeOut 1.5s infinite alternate;
    }

    @keyframes fadeOut {
        0% { opacity: 1; }
        100% { opacity: 0.5; }
    }

    .countdown {
        font-size: 24px;
        font-weight: bold;
        color: #ffcc00;
    }

    .contact-section {
        margin-top: 20px;
        background: rgba(0, 0, 0, 0.7);
        padding: 15px;
        border-radius: 10px;
        display: inline-block;
        color: #fff;
    }
</style>

@section('content')
<div class="container">
    <div class="error-container">
        <h1>419 ⏳</h1>
        <p>Oups, votre session a expiré ! Veuillez vous reconnecter.</p>
        <img src="https://media.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif" width="250" alt="Session expirée">

        <p class="countdown">Redirection en <span id="countdown">30</span> secondes...</p>
        <a href="{{ url('/login') }}" class="btn btn-warning">Se reconnecter</a>

        <div class="contact-section">
            <h3>Besoin d'assistance ? Contactez l'informaticien !</h3>
            {{-- <p>Email : support@exemple.com</p> --}}
            <p>Téléphone : +225 07 1800 7888</p>
        </div>
    </div>
</div>

<script>
    let count = 30;
    let countdownElement = document.getElementById('countdown');

    setInterval(() => {
        if (count > 0) {
            count--;
            countdownElement.textContent = count;
        } else {
            window.location.href = "{{ url('/login') }}";
        }
    }, 1000);
</script>
@endsection
