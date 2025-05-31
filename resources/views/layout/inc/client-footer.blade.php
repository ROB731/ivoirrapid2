<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
{{-- Pour le message ou     annonce --}}

    <!-- Bouton pour ouvrir le modal -->
<!-- Bouton pour ouvrir le modal d'annonce -->
<button type="button" class="btn btn-warning floating-message-button" data-bs-toggle="modal" data-bs-target="#messageModal">
    📢 Annonces <sup style="color:red">+10</sup>
</button>
<!-- Modal Bootstrap -->
<div class="modal fade custom-message-modal" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content custom-message-content">
      <div class="modal-header" style="background:none !important" >
        <h5 class="modal-title" id="messageModalLabel"><i class="bi bi-megaphone-fill"></i> Annonce importante</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body custom-message-body">
        {{-- text-------------------------------- --}}
        <h4>🚀 Nouvelle Information !</h4>
                  <div> <!-- -->
                                 <div class="container">
                                        <h2>Bonjour chers clients,</h2>
                                        <p>
                                            Dans le souci de vous offrir un <span class="highlight">suivi plus efficace et un service plus fluide</span>, nous effectuons une
                                            <span class="highlight">mise à jour de notre base de données</span>. Cette amélioration vous permettra de
                                            <span class="highlight">visualiser et gérer vos plis et factures plus facilement</span>, en toute simplicité.
                                        </p>
                                        <p>
                                            <span class="highlight">Pas d’inquiétude !</span> 🔹 Tout est sous contrôle et notre équipe veille à ce que la transition se déroule
                                            <span class="highlight">sans interruption ni impact sur vos services</span>. Vous pourrez continuer à utiliser nos services
                                            <span class="highlight">sans le moindre souci</span>.
                                        </p>
                                        <p>
                                            Si vous avez <span class="highlight">la moindre question ou préoccupation</span>, nous restons <span class="highlight">à votre disposition</span>.
                                            <strong>N’hésitez pas à nous contacter !</strong>
                                        </p>
                                        <small style="color: red;"><i>Nous améliorons nos services</i></small>
                                  </div>

                  </div> <!-- -->
                  <div>
                       <p>
                        <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#contactModal">
                            📞 Besoin Aide ?
                        </button>
                    </p>
                  </div>

      {{-- Pour les medias --}}

              <div >

            <iframe style="width:100%" src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fweb.facebook.com%2FIvoirRapidCI%2Fposts%2Fpfbid02DCp9ZnPBgpGACXDdcoR12LmDoAoE9ZGAF15uArtSWPeHPjtkbRVH8XWw7DdHyVPZl&show_text=true&width=500" width="500" height="660" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Contact -------------------------------------------------------------------------------------------- --}}
<!-- Button trigger modal -->
<!-- Bouton pour ouvrir le modal -->
        <button type="button" class="btn btn-primary floating-button" data-bs-toggle="modal" data-bs-target="#contactModal">
            📞 Aide ?
        </button>

        <!-- Modal Bootstrap -->
        <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content" style="background:#eaf7fe">
            <div class="modal-header" style="background:none !important">
                <h4 class="modal-title" id="contactModalLabel" style="color:rgb(59, 59, 59) !important"><i class="bi bi-telephone-fill"></i> Contactez-nous</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body" id="body-contact">


                <h5><i class="bi bi-building" ></i> Sécrétariat Ivorrapid</h5>
                <hr>
                    <p> <i class="bi bi-telephone"></i> <a href="tel:+2250778704520" class="link-secondary" >+225 07 7870 4520</a></p>
                    <p><i class="bi bi-whatsapp"></i> <a href="https://wa.me/0778704520" target="_blank" class="link-secondary"> +225 07 7870 4520  </a></p>
{{--
                    <p> <i class="bi bi-telephone"></i> <a href="tel:+2250778704520" class="link-secondary" >+225 07 7870 4520</a></p>
                    <p><i class="bi bi-whatsapp"></i> <a href="https://wa.me/0778704520" target="_blank" class="link-secondary"> +225 07 7870 4520  </a></p> --}}

                    <h5><i class="bi bi-telephone-x"></i> Fixe Ivoirrapid</h5>
                    <hr>
                    <p> <i class="bi bi-telephone-x"></i> <a href="#" class="link-secondary" >+225 27 21 37 43 46</a></p>
                    {{-- <p><i class="bi bi-whatsapp"></i> <a href="https://wa.me/0778704520" target="_blank" class="link-secondary"> +225 27 21 37 43 46  </a></p> --}}


               <h5><i clas-s="bi bi-laptop"></i> Service Informatique</h5>
                <hr>
                <p> <i class="bi bi-telephone"></i> <a href="tel:+2250718007888" class="link-secondary">+225 07 1800 7800</a></p>
                <p> <i class="bi bi-whatsapp"></i> <a href="https://wa.me/0718007888" target="_blank" class="link-secondary"> +225 07 1800 7800  </a></p>
                <p> <i class="bi bi-whatsapp"></i> <a href="https://wa.me/0172527765" target="_blank" class="link-secondary"> +225 01 7252 7765  </a></p>


            </div>
            </div>
        </div>
        </div>


            <style>
                .link-secondary{
                    color:black !important;
                    text-decoration: none !important;
                }

                .floating-button {
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    background: #007bff;
                    color: white;
                    padding: 12px 18px;
                    border-radius: 50px;
                    border: none;
                    cursor: pointer;
                    font-size: 16px;
                    font-weight: bold;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
                    transition: all 0.3s ease;
                }

                .floating-button:hover {
                    background: #0056b3;
                    transform: scale(1.1);
                    box-shadow: 4px 4px 12px rgba(0, 0, 0, 0.4);
                }

                .modal-content h4 {
                    margin-top: 10px;
                    color: #007bff;
                }

                #contactModalLabel{
                    color:aliceblue !important;
                    text-align:center;
                }


                @media print {
            .floating-button,
            .floating-message-button {
                display: none !important;
            }
        }
    </style>


<style>
        .floating-message-button {
            position: fixed;
            bottom: 80px;
            right: 20px;
            background: #ffc107;
            color: black;
            padding: 12px 18px;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .floating-message-button:hover {
            background: #e0a800;
            transform: scale(1.1);
            box-shadow: 4px 4px 12px rgba(0, 0, 0, 0.4);
        }

        /* Modal spécifique aux annonces */
        .custom-message-modal .custom-message-content {
            background: #fffbe6;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .custom-message-body h4 {
            color: #ff6b6b;
        }

        .custom-message-image {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            border: 3px solid #ff6b6b;
            border-radius: 10px;
        }

</style>





    {{-- ------------------------------------------------------------------- --}}


<footer class="py-4 bg-light mt-auto">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; Ivoirrapid 2024</div>
            {{-- <div>
                <a href="#">Privacy Policy</a>
                &iddot;
                <a href="#">Terms &amp; Conditions</a>
            </div> --}}
        </div>
    </div>
</footer>
