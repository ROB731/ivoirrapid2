

                        <!-- Button trigger modal -->
  @foreach($destinataires as $destinataire)
                        <!-- Modal -->
                        <div class="modal fade modal-lg" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Editer le destinataire - {{ $destinataire->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                {{-- Debut formulaire --}}

                        {{-- <div class="container mt-4">
                            <h3 class="text-center">📋 Gestion des Destinataires</h3> --}}

                                            <!-- Formulaire d'ajout -->
                                            <form action="" method="POST" class="p-3 border rounded bg-light">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Nom :</label>
                                                        <input type="text" name="name" class="form-control" required placeholder="Nom du destinataire">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Adresse :</label>
                                                        <input type="text" name="adresse" class="form-control" required placeholder="Adresse">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Téléphone :</label>
                                                        <input type="text" name="telephone" class="form-control" placeholder="Téléphone">
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-md-4">
                                                        <label>Contact :</label>
                                                        <input type="text" name="contact" class="form-control" placeholder="Contact">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Email :</label>
                                                        <input type="email" name="email" class="form-control" placeholder="Email">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Zone :</label>
                                                        <input type="text" name="zone" class="form-control" required placeholder="Zone">
                                                    </div>
                                                </div>
                                                <div class="text-center mt-3">
                                                    <button type="submit" class="btn btn-success">➕ Ajouter</button>
                                                </div>
                                            </form>

                                {{-- fin formulaire  --}}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                            </div>
                            </div>
                        </div>
                        </div>
        @endforeach
