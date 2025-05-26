<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="index.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Admin
                </a>
                <div class="sb-sidenav-menu-heading">Interface</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Clients
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url('admin/add-user') }}">Ajouter un client</a>
                        <a class="nav-link" href="{{ url('admin/users') }}">Liste de client</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                    <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                    Destinataires
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                            Authentication
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ url('admin/destinataires') }}">Liste des destinataires</a>
                                <a class="nav-link" href="{{ url('admin/plis') }}">Liste des plis</a>
                            </nav>
                        </div>
                    </nav>
                </div>


                <div class="sb-sidenav-menu-heading">Rapports</div>
    <a class="nav-link" href="{{ url('admin/coursiers') }}">
        <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
        Coursiers
    </a>
    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseReports" aria-expanded="false" aria-controls="collapseReports">
        <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
        Rapports
        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
    </a>
    <div class="collapse" id="collapseReports" aria-labelledby="headingThree" data-bs-parent="#sidenavAccordion">
        <nav class="sb-sidenav-menu-nested nav">
            <a class="nav-link" href="{{ url('admin/coursiers') }}">Coursiers</a>
            <a class="nav-link" href="{{ url('admin/rapport-mensuel') }}">Rapport Mensuel</a>
            <a class="nav-link" href="{{ url('admin/rapport-annuel') }}">Rapport Annuel</a>
        </nav>
    </div>
                <a class="nav-link" href="{{ url('admin/coursiers') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Coursiers
                </a>
                <a class="nav-link" href="tables.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Tables
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Connecté en tant que:</div>
            {{ Auth::user()->name }}
        </div>
    </nav>
</div>
