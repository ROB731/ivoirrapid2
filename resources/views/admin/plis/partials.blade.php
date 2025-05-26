<div class="table-responsive">
    <table class="table table-bordered table-striped mt-3">
        <thead class="bg-light text-center">
            <tr>
                <th class="text-nowrap">No de Suivie</th>
                <th class="text-nowrap">Client</th>
                <th class="text-nowrap">Destinataire</th>
                <th class="text-nowrap">Coursier Ramassage</th>
                <th class="text-nowrap">Coursier Dépôt</th>
                <th class="text-nowrap">Date Ramassage</th>
                <th class="text-nowrap">Date Dépôt</th>
                <th class="text-nowrap">Statut</th>
                <th class="text-nowrap">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($plis as $pli)
                <tr>
                    <!-- No de Suivie -->
                    <td class="text-center text-nowrap">{{ $pli->code }}</td>

                    <!-- Client -->
                    <td class="text-nowrap">
                        <div><strong>{{ $pli->user_name }}</strong></div>
                        <div class="text-muted small">Tél: {{ $pli->user_Telephone ?? 'N/A' }}</div>
                    </td>

                    <!-- Destinataire -->
                    <td class="text-nowrap">
                        <div><strong>{{ $pli->destinataire_name }}</strong></div>
                        <div class="text-muted small">Tél: {{ $pli->destinataire_telephone ?? 'N/A' }}</div>
                    </td>

                    <!-- Coursiers -->
                    <td class="text-center text-nowrap">
                        {{ isset($pli->attributions[0]) ? $pli->attributions[0]['coursier_ramassage_id'] ?? 'Non défini' : 'Non défini' }}
                    </td>
                    <td class="text-center text-nowrap">
                        {{ isset($pli->attributions[0]) ? $pli->attributions[0]['coursier_depot_id'] ?? 'Non défini' : 'Non défini' }}
                    </td>

                    <!-- Dates -->
                    <td class="text-center text-nowrap">
                        {{ isset($pli->attributions[0]) && $pli->attributions[0]['date_attribution_ramassage'] 
                            ? \Carbon\Carbon::parse($pli->attributions[0]['date_attribution_ramassage'])->format('d/m/Y') 
                            : 'Non défini' }}
                    </td>
                    <td class="text-center text-nowrap">
                        {{ isset($pli->attributions[0]) && $pli->attributions[0]['date_attribution_depot'] 
                            ? \Carbon\Carbon::parse($pli->attributions[0]['date_attribution_depot'])->format('d/m/Y') 
                            : 'Non défini' }}
                    </td>

                    <!-- Statut -->
                    <td class="text-nowrap">
                        <form action="{{ route('plis.changeStatuer', $pli->id) }}" method="POST">
                            @csrf
                            <select name="statuer" class="form-select form-select-sm statut-select" required>
                                @foreach(['en attente', 'ramassé', 'déposé', 'annulé', 'retourné'] as $statut)
                                    <option value="{{ $statut }}" 
                                        {{ $pli->currentStatuer()?->statuer->name == $statut ? 'selected' : '' }}>
                                        {{ ucfirst($statut) }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="text" name="raison" class="form-control form-control-sm mt-2 raison-input" 
                                placeholder="Raison annulation" 
                                value="{{ $pli->currentStatuer()?->raison_annulation ?? '' }}" 
                                {{ $pli->currentStatuer()?->statuer->name == 'annulé' ? '' : 'disabled' }} />
                            <button type="submit" class="btn btn-primary btn-sm mt-2">Mettre à jour</button>
                        </form>
                    </td>

                    <!-- Actions -->
                    <td class="text-center text-nowrap">
                        <!-- Attribuer coursiers -->
                        <a href="{{ route('admin.attributions.index') }}" class="btn btn-sm btn-outline-primary">Attribuer</a>
                        <a href="{{ route('admin.plis.show', $pli->id) }}" class="btn btn-sm btn-outline-primary">Voir</a>

                        <!-- Modal pour détails -->
                        <button class="btn btn-sm btn-outline-info mt-1" data-bs-toggle="modal" data-bs-target="#modalDates-{{ $pli->id }}">
                            Dates
                        </button>
                    </td>
                </tr>

                <!-- Modal pour les détails -->
                <div class="modal fade" id="modalDates-{{ $pli->id }}" tabindex="-1" aria-labelledby="modalDatesLabel-{{ $pli->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalDatesLabel-{{ $pli->id }}">Détails des Dates</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                            </div>
                            <div class="modal-body">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <strong>Date En Attente :</strong> 
                                        {{ $pli->statuerHistory->where('statuer_id', '1')->last()?->date_changement 
                                            ? \Carbon\Carbon::parse($pli->statuerHistory->where('statuer_id', '1')->last()->date_changement)->format('d/m/Y H:i') 
                                            : 'Non défini' }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Date Ramassé :</strong> 
                                        {{ $pli->statuerHistory->where('statuer_id', '2')->last()?->date_changement 
                                            ? \Carbon\Carbon::parse($pli->statuerHistory->where('statuer_id', '2')->last()->date_changement)->format('d/m/Y H:i') 
                                            : 'Non défini' }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Date Déposé :</strong> 
                                        {{ $pli->statuerHistory->where('statuer_id', '3')->last()?->date_changement 
                                            ? \Carbon\Carbon::parse($pli->statuerHistory->where('statuer_id', '3')->last()->date_changement)->format('d/m/Y H:i') 
                                            : 'Non défini' }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Date Annulé :</strong> 
                                        {{ $pli->statuerHistory->where('statuer_id', '4')->last()?->date_changement 
                                            ? \Carbon\Carbon::parse($pli->statuerHistory->where('statuer_id', '4')->last()->date_changement)->format('d/m/Y H:i') 
                                            : 'Non défini' }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Date Retourné :</strong> 
                                        {{ $pli->statuerHistory->where('statuer_id', '5')->last()?->date_changement 
                                            ? \Carbon\Carbon::parse($pli->statuerHistory->where('statuer_id', '5')->last()->date_changement)->format('d/m/Y H:i') 
                                            : 'Non défini' }}
                                    </li>
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>
    
</div>