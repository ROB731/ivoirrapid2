<div class="modal fade" id="modalRamassage" tabindex="-1" aria-labelledby="modalLabelRamassage" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📋 Fiches de Mission - Ramassage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered ramassage-table">
                    <thead>
                        <tr>
                            <th>Coursier</th>
                            <th>Nombre de Plis</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($listeCoursiersRamassage)
                        @foreach ($listeCoursiersRamassage as $coursier)
                        <tr>
                            <td>{{ $coursier->coursierRamassage->nom ??'####' }} {{ $coursier->coursierRamassage->prenoms }}</td>
                            <td>{{ $coursier->nombrePlis }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm open-modal" data-bs-toggle="modal" data-bs-target="#modalMissionRamassage{{ $coursier->coursierRamassage->id }}">
                                    <a href="{{ route('fiche.ramassage', ['coursier_id1' => $coursier->coursierRamassage->id]) }}" class="btn ">
                                        📄 Voir la fiche mission dépôt
                                    </a>

                                </button>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <p>Aucune donnée pour l'instant</p>
                        @endisset
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Pour le ramassage  --}}

<div class="modal fade" id="modalDepot" tabindex="-1" aria-labelledby="modalLabelDepot" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📋 Fiches de Mission - Dépôt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered depot-table">
                    <thead>
                        <tr>
                            <th>Coursier</th>
                            <th>Nombre de Plis</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($listeCoursiersDepot as $coursier1)
                        <tr>
                            <td>{{ $coursier1->coursierDepot->nom }} {{ $coursier1->coursierDepot->prenoms }}</td>
                             {{-- <td>{{ $coursier1->coursierDepot->nom }} </td> --}}

                             <td>{{ $coursier1->nombrePlis }}</td>

                            <td>
                                <button class="btn btn-success btn-sm open-modal" data-bs-toggle="modal" data-bs-target="#modalMissionDepot{{ $coursier1->coursierDepot->id }}">

                                    <a href="{{ route('fiche.depot', ['coursier_id' => $coursier1->coursierDepot->id]) }}" class="btn btn-success">
                                        📄 Voir la fiche mission dépôt
                                    </a>

                                </button>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

