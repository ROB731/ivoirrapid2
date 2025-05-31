<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="{{ route('admin.dashboard') }}">Ivoirrapid</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">

        </div>
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user fa-fw"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                {{-- <!-- Formulaire de déconnexion --> /gestion-cheques --}}
                  <li><a class="nav-link text-bg-dark" href="{{ url('admin/gestion-cheques/') }}">Services Chèques</a></li>

                    <li>
                            <details>
                                <summary>Suivi de Pli</summary>
                                <ul>
                                    <li><a class="nav-link text-bg-dark" href="{{ url('/admin/Mon-profil') }}">Mon profil</a></li>
                                    <li><a class="nav-link text-bg-dark" href="{{ url('admin/destinataires') }}">Liste des destinataires</a></li>
                                    <li><a class="nav-link text-bg-dark" href="{{ url('admin/plis') }}">Liste des plis</a></li>
                                    <li><a class="nav-link text-bg-dark" href="{{ route('admin.attributions.index') }}" title="Attribuer"><i class="fas fa-user-plus"></i> Attribution des plis</a></li>
                                    <li><a class="nav-link text-bg-dark" href="{{ url('admin/plis/verification') }}" title="Vérifier">Vérification des statuts de plis</a></li>
                                </ul>
                            </details>
                    </li>


                   <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item" style="border: none; background: none; padding: 8px 16px;">
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>

</nav>

<style>
        .nav-link:hover{
                background-color:rgb(85, 85, 85) !important;
                /* font-size: 1px; */
                transition: all 0.9ms ease-in-out;

        }
</style>

 @include('some-modules')
    @yield('barre_info')
