<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading"></div>
                <a class="nav-link" href="{{ route('client.dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div>
                    Client
                </a>

                <div class="sb-sidenav-menu-heading"></div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
                    Destinataires
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url('client/add-destinataire') }}">Ajouter un destinataire</a>
                        <a class="nav-link" href="{{ url('client/destinataires') }}">Liste des destinataires</a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseReports" aria-expanded="false" aria-controls="collapseReports">
                    <div class="sb-nav-link-icon"><i class="fas fa-shipping-fast"></i></div>
                    Plis
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseReports" aria-labelledby="headingThree" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url('client/add-pli') }}">Creer un pli</a>
                        <a class="nav-link" href="{{ url('client/plis') }}">Liste des plis</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseZones" aria-expanded="false" aria-controls="collapseZones">
                    <div class="sb-nav-link-icon"><i class="fas fa-map-marker-alt"></i></div>
                    Zones
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseZones" aria-labelledby="headingZones" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url('client/zones') }}">Voir les zones</a>
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
