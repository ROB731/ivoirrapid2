

<form action="{{ route('admin.attributions.massAttribuer') }}" method="POST">
    @csrf
    <table class="table table-striped">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th> <!-- Sélecteur global -->
                <th>#</th>
                <th>Destinataire</th>
                <th>Expéditeur</th>
                <th>Zone Destinataire</th>
                <th>Zone Expéditeur</th>
                <th>Type d'Attribution</th>
            </tr>
        </thead>
        <tbody>
            @foreach($plies as $pli)
                <tr>
                    <td><input type="checkbox" name="pli_ids[]" value="{{ $pli->id }}"></td> <!-- Cases à cocher -->
                    <td>{{ $pli->id }}</td>
                    <td>{{ $pli->destinataire->name }}</td>
                    <td>{{ $pli->user->name }}</td>
                    <td>{{ $pli->destinataire->zone ?? 'N/A' }}</td>
                    <td>{{ $pli->user->Zone ?? 'N/A' }}</td>
                    <td>
                        <select name="attributions[{{ $pli->id }}]" class="form-control" required>
                            <option value="ramassage"
                                @if(optional($pli->attribution)->coursier_ramassage_id) disabled @endif>
                                Ramassage
                            </option>
                            <option value="depot"
                                @if(optional($pli->attribution)->coursier_depot_id) disabled @endif>
                                Dépôt
                            </option>
                        </select>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Bouton unique de validation -->
    <button type="submit" class="btn btn-success mt-2">Attribuer en masse</button>
</form>
