<div>
    @if(session()->has('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    <!-- Formulaire d'ajout -->
    <h3>Ajouter un destinataire</h3>
    <div>
        <input type="text" wire:model="name" placeholder="Nom">
        <input type="text" wire:model="adresse" placeholder="Adresse">
        <input type="text" wire:model="zone" placeholder="Zone">
        <button wire:click="addDest" class="btn btn-success">➕ Ajouter</button>
    </div>

    <hr>

    <!-- Tableau des destinataires -->
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Adresse</th>
                <th>Zone</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($destinataires as $index => $dest)
                <tr>
                    <td>{{ $index + 1 }}</td>

                    @if($editedDestId === $dest->id)
                        <td><input type="text" wire:model="name"></td>
                        <td><input type="text" wire:model="adresse"></td>
                        <td><input type="text" wire:model="zone"></td>
                        <td>
                            <button wire:click="updateDest" class="btn btn-success">✅ Valider</button>
                            <button wire:click="$set('editedDestId', null)" class="btn btn-secondary">❌ Annuler</button>
                        </td>
                    @else
                        <td>{{ $dest->name }}</td>
                        <td>{{ $dest->adresse }}</td>
                        <td><span class="badge bg-info">{{ $dest->zone }}</span></td>
                        <td>
                            <button wire:click="editDest({{ $dest->id }})" class="btn btn-primary">✏️ Modifier</button>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
