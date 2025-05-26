<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="http://localhost:8097"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <link rel="icon" type="image/png" sizes="32x32" href="https://ivoirrapid.ci/asset/Logo IRN.png" class="logo" alt="Ivoirrapid Logo">


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
</head>
<body>
        @include('layout.inc.client-navbar')

    <div id="layoutSidenav">
        @include('layout.inc.client-sidebar')

        <div id="layoutSidenav_content">
            <main>
                @yield('content')
                <!--<script>
                    // Désactiver le clic droit
                    document.addEventListener('contextmenu', function(e) {
                        e.preventDefault();
                        alert('Le clic droit est désactivé !');
                    });
            
                    // Désactiver les raccourcis clavier (F12, Ctrl+Shift+I, Ctrl+U)
                    document.addEventListener('keydown', function(e) {
                        // Désactiver F12 (inspecteur de code)
                        if (e.keyCode === 123) { 
                            e.preventDefault();
                            alert('L\'inspecteur de code est désactivé !');
                        }
                        // Désactiver Ctrl+Shift+I (inspecteur de code)
                        if (e.ctrlKey && e.shiftKey && e.keyCode === 73) {
                            e.preventDefault();
                            alert('L\'inspecteur de code est désactivé !');
                        }
                        // Désactiver Ctrl+U (affichage du code source)
                        if (e.ctrlKey && e.keyCode === 85) {
                            e.preventDefault();
                            alert('L\'affichage du code source est désactivé !');
                        }
                    });
                </script>-->

            </main>
        @include('layout.inc.client-footer')
        </div>
    </div>
</body>
</html>
