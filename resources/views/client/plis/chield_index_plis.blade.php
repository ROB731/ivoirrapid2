
{{-- Pour les modals ---------------------------------------------------- --}}
{{-- Modal pour afficher les messages des plis qui on été ramassés ou attribué 06-05-2025 ------------------------------------------------------- --}}
{{-- Modal pour afficher les messages des plis qui on été ramassés ou attribué 06-05-2025 ------------------------------------------------------- --}}
<div>
    {{-- <h4> Plis  </h4> --}}
            <!-- Bouton pour ouvrir le modal -->

            <!-- Modal Bootstrap  Pour afficher le modal des plis ramsaasé et qui doivent être deposé ------------------->
            <div class="modal fade" id="plisNonAttribuesModal" tabindex="-1" aria-labelledby="plisModalLabel" aria-hidden="true">

                <div class="modal-dialog modal-xl" >
                    <div class="modal-content">
                        <div class="modal-header bg-success text-dark">
                            <h5 class="modal-title" id="plisModalLabel" style="color:white">📦 Plis  ramassés ou attribués</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">

                            <h5>Plis ramassés ou Attribués ({{ $totalPlisRamassesOuAttribues }}) </h5>

                            <table class="table table-striped table-bordered table-hover mt-3" id="table-p">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="text-nowrap-pr" style="white-space: nowrap;">#</th>
                                        <th scope="col" class="text-nowrap-pr" style="white-space: nowrap;">Messages</th> <!-- Show to print-->
                                        {{-- <th scope="col" class="text-nowrap-pr" style="white-space: nowrap;">Etat(Final)</th>  <!-- Show to print--> --}}
                                        <th scope="col" class="text-nowrap hidden-column">Référence</th>
                                        <th scope="col" class="text-nowrap">Actions</th>

                                    </tr>
                                </thead>
                                <tbody>


                                    @foreach ($plisRamassesOuAttribues as $date => $plis)
                                    {{-- <h4>{{ \Carbon\Carbon::parse($date)->translatedFormat('l d F Y') }}</h4> --}}

                                    @foreach ($plis as $pli)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="alert alert">
                                                    @php $lastAttribution = $pli->attributions->last(); @endphp

                                                    @if ($lastAttribution)
                                                        @if ($lastAttribution->coursier_depot_id && $lastAttribution->coursier_ramassage_id)
                                                            <div class="alert alert-primary">
                                                                🚀 Votre pli <strong>({{ $pli->type }})</strong> N° : <strong>{{ $pli->code }}</strong> a été ramassé le

                                                                  <strong> {{ \Carbon\Carbon::parse($lastAttribution->date_attribution_ramassage)->format('d-m-Y H:i')  }} </strong>, il

                                                                    est en transit vers son destinataire <strong>{{ $pli->destinataire_name }}</strong> situé à <strong>{{ $pli->destinataire_adresse }}</strong> dépuis le <strong>  {{ \Carbon\Carbon::parse($lastAttribution->date_attribution_depot)->format('d-m-Y H:i')  }}  </strong>!

                                                                <div class="status-container">
                                                                    <strong>Statut Final :</strong>
                                                                    <span class="status-badge">
                                                                        {{ $pli->currentStatuer() ? $pli->currentStatuer()->statuer->name : 'Statut non défini' }}
                                                                    </span>
                                                                    <br>
                                                                    <strong>Date de mise à jour :</strong>
                                                                    <span class="date-badge">
                                                                        {{ $pli->currentStatuer() && $pli->currentStatuer()->created_at
                                                                            ? \Carbon\Carbon::parse($pli->currentStatuer()->created_at)->format('d-m-Y H:i')
                                                                            : 'Date inconnue' }}
                                                                    </span>


                                                                </div>

                                                            </div>

                                                        @elseif ($lastAttribution->coursier_ramassage_id)
                                                            <div class="alert alert-success">

                                                                ✅ Votre pli <strong>({{ $pli->type }})</strong> N° : <strong>{{ $pli->code }}</strong> a été ramassé le
                                                                <strong>
                                                                    {{ \Carbon\Carbon::parse($lastAttribution->date_attribution_ramassage ?? '')->translatedFormat('l d F à H\hi') ?? 'Date indisponible' }}
                                                                </strong>.
                                                                Il est en cours d'examen pour être transmis à votre destinataire : <strong> {{ $pli->destinataire_name }}</strong> situé à <strong> {{ $pli->destinataire_adresse }}</strong>
                                                                <div class="status-container">
                                                                    <strong>Statut Final :</strong>
                                                                    <span class="status-badge">

                                                                 @if($pli->currentStatuer() && $pli->currentStatuer()->statuer->name)
                                                                        {{ $pli->currentStatuer()->statuer->name }}
                                                                        <a href="#"> Demander l'accusé</a>
                                                                    @else
                                                                        Aucun statut pour le moment
                                                                    @endif

                                                                    </span>

                                                                    <strong>Date de mise à jour :</strong>
                                                                    <span class="date-badge">
                                                                        {{ $pli->currentStatuer() && $pli->currentStatuer()->created_at
                                                                            ? \Carbon\Carbon::parse($pli->currentStatuer()->created_at)->format('d-m-Y H:i')
                                                                            : 'Date inconnue' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endif

                                                    @else
                                                        <div class="alert alert-warning">
                                                            ⏳ Votre pli <strong>({{ $pli->type }})</strong> N° : <strong>{{ $pli->code }}</strong> est en attente de ramassage.
                                                        </div>
                                                        <div class="status-container">
                                                            <strong>Statut Final :</strong>
                                                            <span class="status-badge">
                                                                {{ $pli->currentStatuer() ? $pli->currentStatuer()->statuer->name : 'Statut non défini' }}
                                                            </span>
                                                            <strong>Date de mise à jour :</strong>
                                                            <span class="date-badge">
                                                                {{ $pli1->currentStatuer() && $pli->currentStatuer()->created_at
                                                                    ? \Carbon\Carbon::parse($pli->currentStatuer()->created_at)->format('d-m-Y H:i')
                                                                    : 'Date inconnue' }}
                                                            </span>
                                                        </div>
                                                    @endif

                                                </div>
                                            </td>
                                            {{-- <td>
                                                <span class="badge
                                                    @if ($pli->currentStatuer() && $pli->currentStatuer()->statuer->name == 'en attente') bg-warning
                                                    @elseif ($pli->currentStatuer() && $pli->currentStatuer()->statuer->name == 'ramassé') bg-info
                                                    @elseif ($pli->currentStatuer() && $pli->currentStatuer()->statuer->name == 'déposé') bg-success
                                                    @elseif ($pli->currentStatuer() && $pli->currentStatuer()->statuer->name == 'annulé') bg-danger
                                                    @elseif ($pli->currentStatuer() && $pli->currentStatuer()->statuer->name == 'retourné') bg-success
                                                    @endif">
                                                    {{ $pli->currentStatuer() ? $pli->currentStatuer()->statuer->name : 'Non défini' }}
                                                </span>
                                            </td> --}}

                                            <td class="text-nowrap">
                                                <button class="btn btn-info btn-sm view-reference-btn" data-reference="{{ $pli->reference }}" data-bs-toggle="modal" data-bs-target="#referenceModal">Voir</button>
                                            </td>
                                            <td class="text-nowrap">
                                                <a href="{{ route('client.plis.show', $pli->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>


                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach

                                </tbody>
                            </table>
                                  <!-- Affichage de la pagination -->
                        {{-- <div class="pagination-container">
                            {{ $plisRamassesOuAttribues->links() }}
                        </div> --}}

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                            .modal-header {
                                    position: sticky;
                                    top: 0;
                                    z-index: 1055;
                                    background-color: #198754; /* Couleur de fond verte (bg-success) */
                                }

            </style>
</div>

{{-- Fin du modal 06-05-2025----------------------------------------------------------------------------------- --}}
{{-- Fin du modal 06-05-2025----------------------------------------------------------------------------------- --}}




{{-- Pour le modal qui doit afficher les statuts finaux  --}}



{{-- Pour les modals ---------------------------------------------------- --}}

{{-- Modal pour afficher les messages des plis qui on été ramassés ou attribué et statut final 06-05-2025 ------------------------------------------------------- --}}
{{-- Modal pour afficher les messages des plis qui on été ramassés ou attribué 06-05-2025  et statut final ------------------------------------------------------- --}}

<div>
    {{-- <h4> Plis  </h4> --}}
            <!-- Bouton pour ouvrir le modal -->

        {{-- <button type="button" class="btn btn-dark custom-btn" data-bs-toggle="modal" data-bs-target="#plisNonAttribuesModal" data-bs-toggle="tooltip" title="Voir les plis non attribués">
            🚨
        </button> --}}

            <!-- Modal Bootstrap  Pour afficher le modal des plis ramsaasé et qui doivent être deposé ------------------->
            <div class="modal fade" id="plisStatutFinal" tabindex="-1" aria-labelledby="plisModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" >
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-dark">
                            <h5 class="modal-title" id="plisModalLabel" style="color:white">📦 Plis  ramassés ou attribués</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">


                            <table class="table table-striped table-bordered table-hover mt-3" id="table-p">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="text-nowrap-pr" style="white-space: nowrap;">#</th>
                                        <th scope="col" class="text-nowrap-pr" style="white-space: nowrap;">Messages</th> <!-- Show to print-->
                                        <th scope="col" class="text-nowrap-pr" style="white-space: nowrap;">Etat(Final)</th>  <!-- Show to print-->
                                        <th scope="col" class="text-nowrap hidden-column">Référence</th>
                                        <th scope="col" class="text-nowrap">Actions</th>
                                        <th scope="col" class="text-nowrap">Accusé</th>

                                    </tr>
                                </thead>
                                <tbody>


                                    {{-- @foreach ( $plisFinauxClient as $date => $plis) --}}
                                    {{-- <h4>{{ \Carbon\Carbon::parse($date)->translatedFormat('l d F Y') }}</h4> --}}


                                    @foreach ($plisFinauxClient as $pli)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="alert alert">
                                                @php
                                                    $lastAttribution = $pli->attributions->last();
                                                    $statut = $pli->currentStatuer() ? $pli->currentStatuer()->statuer->name : 'Non défini';
                                                    $dateStatut = $lastAttribution->date_attribution_ramassage ?? $lastAttribution->date_attribution_depot ?? null;
                                                @endphp

                                                @if ($lastAttribution)

                                {{-- Cas 1 Statut refusé-----------------------------------------------------------------  --}}
                                                                                {{-- Cas 1 Statut refusé-----------------------------------------------------------------  --}}

                                                    @if ($statut == 'refusé')
                                                        <div class="alert alert-warning">🚀 Pli <strong>{{ $pli->code }}</strong>
                                                             refusé le <strong>{{ \Carbon\Carbon::parse($dateStatut)->translatedFormat('d-m-Y') }}</strong>. <br>
                                                             Le destinataire <strong> {{ $pli->destinataire_name }} </strong>
                                                              situé à <strong> {{ $pli->destinataire_adresse }} </strong>
                                                              a réfusé la facture pour la - les raison(s) suivante(s) :
                                                                    <i>  <strong> {{ $pli->currentStatuer() && $pli->currentStatuer()->raison_annulation ? $pli->currentStatuer()->raison_annulation : 'Aucune raison spécifiée' }} </strong>
                                                                         </i>
                                               {{-- Pour le statut et l'accusé ---------------------- --}}
                                                            @if($pli->currentStatuer())
                                                            <!-- Affiche le lien seulement si le statut existe -->
                                                            <br> <br>
                                                            <a href="mailto:ivoirrapid@gmail.com?subject=Demande%20d'accusé%20pour%20le%20pli%20N°%3A%20{{$pli->code}}%20Client%20{{$pli->user_name}}&body=Bonjour%20IVOIRRAPID,%0D%0A%0D%0A
                                                            Je%20souhaite%20obtenir%20l'accusé%20de%20livraison%20pour%20le%20pli%20N°%3A%20{{$pli->code}}%20avec%20le%20statut%20{{$pli->currentStatuer()->statuer->name}}.%0D%0A%0D%0A
                                                            Merci%20de%20me%20contacter%20pour%20plus%20d'informations%20:%20{{$pli->user_Telephone}}"

                                                            class="btn-confirmation00">

                                                                📩 Demander l'accusé
                                                                {{-- {{ $pli1->currentStatuer() ? $pli1->currentStatuer()->statuer->name : 'Statut non défini' }} --}}
                                                            </a> Si  vous n'avez pas encore reçu

                                                            <style>
                                                                .btn-confirmation00 {
                                                                    display: inline-block;
                                                                    padding: 4px 8px;
                                                                    font-size: 14px;
                                                                    font-weight: bold;
                                                                    color: #383938;
                                                                    text-decoration: none;
                                                                    border: 2px solid #dce002;
                                                                    background-color: transparent;
                                                                    border-radius: 5px;
                                                                    transition: all 0.3s ease-in-out;
                                                                }

                                                                .btn-confirmation00:hover {
                                                                    background-color: #d0d011;
                                                                    color: white;
                                                                }
                                                            </style>

                                                        @else
                                                            <p style="color: red;">Statut non défini</p>
                                                        @endif

                                                        </div>
                                        {{-- Fin cas de statut --------------------------------------- --}}

                                                            </p>
                                                        </div>
        {{-- / Fin cas1 de refusé ------------------------------------------------------------------------------ --}}
                {{-- / Fin cas1 de refusé ------------------------------------------------------------------------------ --}}


                                                    @elseif ($statut == 'déposé')
                                                        <div class="alert alert-success">✅ Pli <strong>{{ $pli->code }}</strong>

                                                            déposé le <strong>{{ \Carbon\Carbon::parse($dateStatut)->translatedFormat('d-m-Y') }}</strong>
                                                             chez le destinataire <strong>{{ $pli->destinataire_name }}</strong> situé : <strong> {{ $pli->destinataire_adresse }}</strong> <br>
                                                            Date création du pli : <strong> {{ \carbon\Carbon::parse($pli->created_at)->format('d-m-Y') }} </strong>

                                                  {{-- Pour le statut et l'accusé ---------------------- --}}
                                                            @if($pli->currentStatuer())
                                                            <!-- Affiche le lien seulement si le statut existe -->
                                                            <br> <br>
                                                            <a href="mailto:ivoirrapid@gmail.com?subject=Demande%20d'accusé%20pour%20le%20pli%20N°%3A%20{{$pli->code}}&body=Bonjour%20IVOIRRAPID,%0D%0A%0D%0A
                                                            Je%20souhaite%20obtenir%20l'accusé%20de%20livraison%20pour%20le%20pli%20N°%3A%20{{$pli->code}}%20avec%20le%20statut%20{{$pli->currentStatuer()->statuer->name}}.%0D%0A%0D%0A
                                                            Merci%20de%20me%20contacter%20pour%20plus%20d'informations%20:%20{{$pli->user_Telephone}}" class="btn-confirmation">

                                                                📩 Demander l'accusé
                                                                {{-- {{ $pli1->currentStatuer() ? $pli1->currentStatuer()->statuer->name : 'Statut non défini' }} --}}
                                                            </a> Si  vous n'avez pas encore reçu

                                                            <style>
                                                                .btn-confirmation {
                                                                    display: inline-block;
                                                                    padding: 4px 8px;
                                                                    font-size: 14px;
                                                                    font-weight: bold;
                                                                    color: #383938;
                                                                    text-decoration: none;
                                                                    border: 2px solid #068d21;
                                                                    background-color: transparent;
                                                                    border-radius: 5px;
                                                                    transition: all 0.3s ease-in-out;
                                                                }

                                                                .btn-confirmation:hover {
                                                                    background-color: #06892d;
                                                                    color: white;
                                                                }
                                                            </style>

                                                        @else
                                                            <p style="color: red;">Statut non défini</p>
                                                        @endif

                                                        </div>
                                        {{-- Fin cas de statut --------------------------------------- --}}

     {{-- Cas 2 statut annulée-------------------------------------------------------------------------------------- --}}
                                      {{-- Cas 2 statut annulée-------------------------------------------------- --}}

                                                    @elseif ($statut == 'annulé')
                                                        <br>
                                                        <div class="alert alert-danger">❌ Pli <strong>{{ $pli->code }}</strong>
                                                            annulé le <strong>{{ \Carbon\Carbon::parse($dateStatut)->translatedFormat('d-m-Y') }}</strong>. <br>
                                                            La transmission de votre facture a été annulé pour la raison suivante :
                                                                <i> <strong> {{ $pli->currentStatuer() && $pli->currentStatuer()->raison_annulation ? $pli->currentStatuer()->raison_annulation : 'Aucune raison spécifiée' }} </strong>
                                                                </i> <br>
                                                               Date création du pli : <strong> {{ \carbon\Carbon::parse($pli->created_at)->format('d-m-Y') }} </strong>
                                                    {{-- Pour le statut et l'accusé ---------------- --}}
                                                              @if($pli->currentStatuer())
                                                              <!-- Affiche le lien seulement si le statut existe -->
                                                              <br> <br>
                                                              <a href="mailto:ivoirrapid@gmail.com?subject=Demande%20d'accusé%20pour%20le%20pli%20N°%3A%20{{$pli->code}}&body=Bonjour%20IVOIRRAPID,%0D%0A%0D%0A
                                                              Je%20souhaite%20obtenir%20l'accusé%20de%20livraison%20pour%20le%20pli%20N°%3A%20{{$pli->code}}%20avec%20le%20statut%20{{$pli->currentStatuer()->statuer->name}}.%0D%0A%0D%0A
                                                              Merci%20de%20me%20contacter%20pour%20plus%20d'informations%20:%20{{$pli->user_Telephone}}" class="btn-confirmation_annuler">

                                                                  📩 Demander l'accusé
                                                                  {{-- {{ $pli1->currentStatuer() ? $pli1->currentStatuer()->statuer->name : 'Statut non défini' }} --}}
                                                              </a> Si  vous n'avez pas encore reçu

                                                              <style>
                                                                  .btn-confirmation_annuler {
                                                                      display: inline-block;
                                                                      padding: 4px 8px;
                                                                      font-size: 14px;
                                                                      font-weight: bold;
                                                                      color: #383938;
                                                                      text-decoration: none;
                                                                      border: 2px solid #eb3824;
                                                                      background-color: transparent;
                                                                      border-radius: 5px;
                                                                      transition: all 0.3s ease-in-out;
                                                                  }

                                                                  .btn-confirmation_annuler:hover {
                                                                      background-color: #eb3824;
                                                                      color: white;
                                                                  }
                                                              </style>

                                                          @else
                                                              <p style="color: red;">Statut non défini</p>
                                                          @endif

                                             {{-- Fin pour le statut et l'accusé ----------------- --}}
          {{--  / Cas 2 statut annulée-------------------------------------------------- --}}

                                                        </div>

                                                    @else
                                                        <div class="alert alert-primary">🚀 Pli <strong>{{ $pli->code }}</strong> en transit.</div>

                                                              {{-- Pour le statut et l'accusé ---------------- --}}
                                                              @if($pli->currentStatuer())
                                                              <!-- Affiche le lien seulement si le statut existe -->
                                                              <br> <br>
                                                              <a href="mailto:ivoirrapid@gmail.com?subject=Demande%20d'accusé%20pour%20le%20pli%20N°%3A%20{{$pli->code}}&body=Bonjour%20IVOIRRAPID,%0D%0A%0D%0A
                                                              Je%20souhaite%20obtenir%20l'accusé%20de%20livraison%20pour%20le%20pli%20N°%3A%20{{$pli->code}}%20avec%20le%20statut%20{{$pli->currentStatuer()->statuer->name}}.%0D%0A%0D%0A
                                                              Merci%20de%20me%20contacter%20pour%20plus%20d'informations%20:%20{{$pli->user_Telephone}}" class="btn-confirmation_annuler">

                                                                  📩 Demander l'accusé
                                                                  {{-- {{ $pli1->currentStatuer() ? $pli1->currentStatuer()->statuer->name : 'Statut non défini' }} --}}
                                                              </a> Si  vous n'avez pas encore reçu

                                                              <style>
                                                                  .btn-confirmation_annuler {
                                                                      display: inline-block;
                                                                      padding: 4px 8px;
                                                                      font-size: 14px;
                                                                      font-weight: bold;
                                                                      color: #383938;
                                                                      text-decoration: none;
                                                                      border: 2px solid #eb3824;
                                                                      background-color: transparent;
                                                                      border-radius: 5px;
                                                                      transition: all 0.3s ease-in-out;
                                                                  }

                                                                  .btn-confirmation_annuler:hover {
                                                                      background-color: #eb3824;
                                                                      color: white;
                                                                  }
                                                              </style>

                                                          @else
                                                              <p style="color: red;">Statut non défini</p>
                                                          @endif

                                             {{-- Fin pour le statut et l'accusé ----------------- --}}

                                                    @endif

                                                    @if ($dateStatut)
                                                        <strong>Date du statut :</strong> {{ \Carbon\Carbon::parse($dateStatut)->translatedFormat('l d F Y à H\hi') }}
                                                    @else
                                                        <strong>Date :</strong> Indisponible
                                                    @endif
                                                @else
                                                    <div class="alert alert-warning">⏳ Pli <strong>{{ $pli->code }}</strong> en attente d’attribution ou cas particulier.</div>
                                                                {{-- Pour le statut et l'accusé ---------------- --}}
                                                                @if($pli->currentStatuer())
                                                                <!-- Affiche le lien seulement si le statut existe -->
                                                                <br> <br>
                                                                <a href="mailto:ivoirrapid@gmail.com?subject=Demande%20d'accusé%20pour%20le%20pli%20N°%3A%20{{$pli->code}}&body=Bonjour%20IVOIRRAPID,%0D%0A%0D%0A
                                                                Je%20souhaite%20obtenir%20l'accusé%20de%20livraison%20pour%20le%20pli%20N°%3A%20{{$pli->code}}%20avec%20le%20statut%20{{$pli->currentStatuer()->statuer->name}}.%0D%0A%0D%0A
                                                                Merci%20de%20me%20contacter%20pour%20plus%20d'informations%20:%20{{$pli->user_Telephone}}" class="btn-confirmation_annuler">

                                                                    📩 Demander l'accusé
                                                                    {{-- {{ $pli1->currentStatuer() ? $pli1->currentStatuer()->statuer->name : 'Statut non défini' }} --}}
                                                                </a> Si  vous n'avez pas encore reçu

                                                                <style>
                                                                    .btn-confirmation_annuler {
                                                                        display: inline-block;
                                                                        padding: 4px 8px;
                                                                        font-size: 14px;
                                                                        font-weight: bold;
                                                                        color: #383938;
                                                                        text-decoration: none;
                                                                        border: 2px solid #eb3824;
                                                                        background-color: transparent;
                                                                        border-radius: 5px;
                                                                        transition: all 0.3s ease-in-out;
                                                                    }

                                                                    .btn-confirmation_annuler:hover {
                                                                        background-color: #eb3824;
                                                                        color: white;
                                                                    }
                                                                </style>

                                                            @else
                                                                <p style="color: red;">Statut non défini</p>
                                                            @endif

                                               {{-- Fin pour le statut et l'accusé ----------------- --}}

                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            <span class="badge
                                                @if ($statut == 'refusé') bg-warning
                                                @elseif ($statut == 'ramassé') bg-info
                                                @elseif ($statut == 'déposé') bg-success
                                                @elseif ($statut == 'annulé') bg-danger
                                                @elseif ($statut == 'retourné') bg-dark
                                                @endif">
                                                {{ $statut }}
                                            </span>
                                        </td>

                                        <td class="text-nowrap">
                                            <button class="btn btn-info btn-sm view-reference-btn" data-reference="{{ $pli->reference }}" data-bs-toggle="modal" data-bs-target="#referenceModal">Voir</button>
                                        </td>

                                        <td class="text-nowrap">
                                            <a href="{{ route('client.plis.show', $pli->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                                            {{-- <a href="{{ route('client.edit-pli', $pli->id) }}" class="btn
                                                @if (in_array($statut, ['en attente', 'ramassé', 'déposé', 'annulé', 'retourné'])) btn-secondary disabled
                                                @else btn btn-warning btn-sm me-2
                                                @endif">
                                                <i class="fas fa-edit"></i>
                                            </a> --}}

                                            {{-- <a href="{{ route('plis.supprimer', $pli->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a> --}}
                                            {{-- <a href="{{ route('plis.restaurer', $pli->id) }}" class="btn btn-success btn-sm"><i class="fas fa-recycle"></i></a> --}}
                                        </td>

                                        <td>
                                                Reçu
                                        </td>
                                    </tr>
                                @endforeach

                                {{-- @endforeach --}}

                                </tbody>
                            </table>
                        </div>
                        {{-- Pargination --}}
                                <!-- Pagination -->
                                    <div class="pagination-wrapper" style="text-align: center">
                                        {{ $plisFinauxClient->links() }}
                                    </div>
                                    <style>
                                        svg{
                                            display:none;
                                        }
                                    </style>

                        {{-- Pagination --}}


                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                            .modal-header {
                                    position: sticky;
                                    top: 0;
                                    z-index: 1055;
                                    background-color: #198754; /* Couleur de fond verte (bg-success) */
                                }

            </style>
</div>

{{-- Fin du modal 06-05-2025----------------------------------------------------------------------------------- --}}
{{-- Fin du modal 06-05-2025----------------------------------------------------------------------------------- --}}

