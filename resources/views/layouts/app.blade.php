<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
         @livewireStyles

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Authentification Ir-App') }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="https://ivoirrapid.ci/asset/Logo IRN.png" class="logo" alt="Ivoirrapid Logo">
    <!-- Styles -->
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
    <script src="http://localhost:8097"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

</head>
<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" defer></script>
    
     @livewireScripts
</body>
</html>
