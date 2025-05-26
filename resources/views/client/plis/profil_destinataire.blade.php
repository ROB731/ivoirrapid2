

<!-- Bouton pour ouvrir le modal -->
<button type="button" class="btn-modal" data-bs-toggle="modal" data-bs-target="#modalDestinataire">
    📍 Voir profil et position
</button>

<!-- Modal -->
<div class="modal fade" id="modalDestinataire" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Profil du Destinataire</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Informations du destinataire -->
                <p><strong>Nom :</strong> <span id="nomDestinataire"></span></p>
                <p><strong>Téléphone :</strong> <span id="telDestinataire"></span></p>
                <p><strong>Adresse :</strong> <span id="adresseDestinataire"></span></p>

                <!-- Carte Google Maps -->
                <div id="map" style="width: 100%; height: 300px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-close-modal" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>


<style>
        .modal-content {
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .btn-modal {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-close-modal {
            background-color: #dc3545;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

</style>


<script>
    function initMap(adresse) {
        var geocoder = new google.maps.Geocoder();
        var mapOptions = {
            zoom: 15,
            center: { lat: 5.359, lng: -4.008 }, // Coordonnées par défaut (Abidjan)
        };

        var map = new google.maps.Map(document.getElementById('map'), mapOptions);

        geocoder.geocode({ 'address': adresse }, function(results, status) {
            if (status === 'OK') {
                map.setCenter(results[0].geometry.location);
                new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                });
            } else {
                alert("L'adresse n'a pas été trouvée !");
            }
        });
    }

    // Fonction pour ouvrir le modal et afficher les infos
    function afficherProfil(nom, telephone, adresse) {
        document.getElementById("nomDestinataire").textContent = nom;
        document.getElementById("telDestinataire").textContent = telephone;
        document.getElementById("adresseDestinataire").textContent = adresse;

        initMap(adresse);
    }
</script>

<!-- API Google Maps -->
<script async src="https://maps.googleapis.com/maps/api/js?key=TON_CLE_API&callback=initMap"></script>

