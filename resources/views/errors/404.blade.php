@extends('layouts.app')

<style>
    body {
        background: url('https://ivoirrapid.ci/asset/logo.png') no-repeat center center fixed;
        background-size: cover;
        color: #d5d5d5;
        text-align: center;
        font-family: 'Arial', sans-serif;
    }

    .error-container {
        margin-top: 7%;
    }

    h1 {
        font-size: 90px;
        font-weight: bold;
        color: #ff4a4a;
        animation: glitch 0.5s infinite alternate;
    }

    @keyframes glitch {
        0% { transform: skew(-5deg); opacity: 0.8; }
        100% { transform: skew(5deg); opacity: 1; }
    }

    .fall-text {
        position: relative;
        display: inline-block;
        animation: fall 60s ease-in-out forwards;
        font-size: 90px;
        font-weight: bold;
    }

    @keyframes fall {
        0% { top: 0px; opacity: 1; }
        100% { top: 150px; opacity: 0; }
    }

    .countdown {
        font-size: 24px;
        font-weight: bold;
        color: #ffcc00;
    }

    .contact-section {
        margin-top: 20px;
        background: rgba(0, 0, 0, 0.7);
        padding: 10px;
        border-radius: 10px;
        display: inline-block;
        color: #fff;
    }
</style>

@section('content')
<div class="container">
    <div class="error-container">
        <h1>404 🚨</h1>
        <h2 class="fall-text" style="color:#ff4a4a">Oups, cette page s'est égarée...</h2>
        <img src="https://media.giphy.com/media/26xBIyg3YihtJzxCw/giphy.gif" width="250" alt="Page perdue">

        <p class="countdown">Redirection en <span id="countdown">60</span> secondes...</p>
      <div style="text-align: right">
        <a href="{{ url('/') }}" class="btn btn-warning ">Retour à l'accueil</a>
      </div>

        <div class="contact-section">
            <h3>Besoin d'aide ? Contactez l'informaticien !</h3>
            {{-- <p>Email : support@exemple.com</p> --}}
            <p>Téléphone : +225 07 1800 7800 </p>
        </div>
    </div>
</div>

<script>
    let count = 60;
    let countdownElement = document.getElementById('countdown');

    setInterval(() => {
        if (count > 0) {
            count--;
            countdownElement.textContent = count;
        } else {
            window.location.href = "{{ url('/') }}";
        }
    }, 1000);
</script>
@endsection
