



            @php
                use Carbon\Carbon;
                    if(isset($_GET['searchZone'])) {
                                $searchZone = $_GET['searchZone'];
                                $destinataires = \App\Models\Destinataire::where('zone', $searchZone)
                                                ->select('id', 'name', 'adresse', 'contact', 'zone')
                                                ->get(); //  Ajout de paginate(50)
                            $msgZone = 'Recherche effectuer pour la zone : '. $searchZone . '/ Nombre de destonataires '.$n=\App\Models\Destinataire::where('zone', $searchZone)->count();
                            $AutremsgZone = " Désolé impossible de changer la zone ou l'adresse dire clique sur Aller à pour modifier";

                            } else {

                                $destinataires = \App\Models\Destinataire::where('zone', '000')
                                                ->select('id', 'name', 'adresse', 'contact', 'zone')
                                                ->get(); //  Ajout de paginate(50)
                                        // $msgZone = 'Recherche effectuer pour la zone : '. $searchZone;

                                    // $msgZone = 'Affichage des destinataires récemment créés dans ce mois de  ' . Carbon::now()->translatedFormat('F');
                                     $destCount =  \App\Models\Destinataire::where('zone', '000')->count();
                                    $msgZone = 'Affichage des destinataires récemment ou présent dans la zone "000"  '. $destCount .' destinataire(s)' ;
                                     $AutremsgZone="";
                            }

                            // -------------------------
                        @endphp

            <div class="modal fade " id="destinataires" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> <span style="color:red"> {{  $msgZone }} .</span> <br>  <br> <i>  {{  $AutremsgZone }} </i>  </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
            {{-- --------------------------------------------------------------------- --}}

            <div class="modal-body">
                <div>
                    <!-- Formulaire de recherche amélioré -->
                            <form method="get" class="form-control">
                                @csrf
                                <input type="text" name="searchZone" id="searchZone" class="form-control" placeholder="Recherchez une zone..." required>
                                <button type="submit" class="btn btn-primary mt-2">🔍 Rechercher</button>
                                <a href="{{ url()->current() }}" class="btn btn-text">
                                    🔄 Rafraichir
                                </a>
                            </form>
                        <hr>
                        <!--  Liste des destinataires avec format amélioré -->

                        {{-- @yield('destinataire') --}}
                        @livewire('destinataire-manager')
    </div>
</div>


    <style>
        svg{
            display:none;
        }
    </style>

{{-- --- Paginations-------------- --}}

{{-- <div class="mt-3" style="text-align: center">
    {{ $destinataires->links() }} <!-- Affiche les boutons de navigation -->
</div> --}}
{{-- Pagination ---------------------------------- --}}

     {{-- ------------------------------------- --}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>


        <form method="get">
                 <button type="submit" class="btn btn-primary" name="massModify">Modifier</button>
        </form>
      </div>


    </div>
  </div>
</div>




