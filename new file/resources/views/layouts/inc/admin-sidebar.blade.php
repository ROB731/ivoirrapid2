<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading"></div>
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div>
                    Admin
                </a>
                <div class="sb-sidenav-menu-heading"></div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
                    Clients
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url('admin/add-user') }}">Ajouter un client</a>
                        <a class="nav-link" href="{{ url('admin/users') }}">Liste de client</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAdmins" aria-expanded="false" aria-controls="collapseAdmins">
                    <div class="sb-nav-link-icon"><i class="fas fa-shipping-fast"></i></div>
                    Plis
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseAdmins" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url('admin/destinataires') }}">Liste des destinataires</a>
                        <a class="nav-link" href="{{ url('admin/plis') }}">Liste des plis</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseReports" aria-expanded="false" aria-controls="collapseReports">
                    <div class="sb-nav-link-icon"><i class="fas fa-address-card"></i></div>
                    Coursiers
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseReports" aria-labelledby="headingThree" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url('admin/coursiers') }}">Liste des Coursiers</a>
                        <a class="nav-link" href="{{ url('admin/add-coursier') }}">Ajouter un Coursier</a>
                    </nav>
                </div>
                
               <!-- Nouvelle section pour les Zones -->
<a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseZones" aria-expanded="false" aria-controls="collapseZones">
    <div class="sb-nav-link-icon"><i class="fas fa-map-marker-alt"></i></div>
    Zones
    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
</a>
<div class="collapse" id="collapseZones" aria-labelledby="headingFour" data-bs-parent="#sidenavAccordion">
    <nav class="sb-sidenav-menu-nested nav">
        <a class="nav-link" href="{{ url('admin/zone') }}">Liste de zones</a>
        <a class="nav-link" href="{{ url('admin/add-zone') }}">Ajouter une zone</a>
        {{-- <a class="nav-link" href="{{ url('admin/zones-details') }}">Voir les détails des zones</a> <!-- Nouveau bouton --> --}}
    </nav>
</div>

            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Connecté en tant que:</div>
            {{ Auth::user()->name }}
        </div>
    </nav>
</div>
