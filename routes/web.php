<?php

use App\Models\Coursier;
use App\Models\Attribution;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Symfony\Component\HttpFoundation\Request;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ZoneController;
// use App\Http\Controllers\Client\PliController;
use App\Http\Controllers\Client\PliController;
use App\Http\Controllers\AttributionController;
use App\Http\Controllers\Admin\CoursierController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Client\DestinataireController;

//  Première route : Redirection automatique

//-------------------------------------------------------------------------------------------------------------

// Route::get('/test', [PliController::class, 'suiviDepotPli'])->name('admin.plis.verification');

// -------------------------------------------------------------------------------------------

   // Gestin de chèques----------------------
        Route::get('admin/gestion-cheques', function(){
            return view('admin.services-cheques.index');
        });

         Route::post('admin/gestion-cheques', function(){
            return view('admin.services-cheques.index');
        });


        // coté client------
        Route::get('client/cheques', function(){
            return view('client.cheques.index');
        });

          Route::post('client/cheques', function(){
            return view('client.cheques.index');
        });

        //     Route::post('/client/ajouter-cheque', function(){
        //     return view('client.cheques.create');
        // });

    // Fin gestion de chèque-----------------------

// --------------------------------------------------------------------------------------------------------------------------

        // Route::get('/admin/plis/verification', function () {
        //     return view('admin.plis.verification');
        // })->name('admin.plis.verification');

        //  Route::post('/admin/plis/verification', function () {
        //     return view('admin.plis.verification');
        // })->name('admin.plis.verification');


// -----------12-05-2025---------------------------------
            Route::get('/admin/plis/verification', function () {
                if (Auth::check() && Auth::user()->role_as == 1) {
                    return view('admin.plis.verification');
                }
                abort(403, 'Accès refusé'); // Retourne une erreur si l'utilisateur n'est pas admin
            })->name('admin.plis.verification');

            Route::post('/admin/plis/verification', function () {
                if (Auth::check() && Auth::user()->role_as == 1) {
                    return view('admin.plis.verification');
                }
                abort(403, 'Accès refusé'); // Retourne une erreur si l'utilisateur n'est pas admin
            })->name('admin.plis.verification');

            // ----------------12-05-2025--------------------------------------------------------



        Route::get('/admin/Mon-profil',function(){

                if(Auth::check())
                    {
                        if(Auth::user()->role_as=='1')
                            {
                                return view('admin.profil_dest');
                            }

                    }

                });

          Route::post('/admin/Mon-profil',function(){

           if(Auth::check())
            {
                if(Auth::user()->role_as=='1')
                    {
                        return view('admin.profil_dest');
                    }
            }

        });




        // Route::post('/admin/Mon-profil',function(){
        //     return view('admin.profil_dest');
        // });


        //  Route::get('/client/Mon-profil',function(){
        //     return view('client.profil');
        // });

        // Route::post('/client/Mon-profil',function(){
        //     return view('client.profil');
        // });



            Route::post('/client/Mon-profil-user',function(){

                if(Auth::check())
                    {
                        if(Auth::user()->role_as=='0')
                            {
                                return view('client.profil');
                            }
                    }

                });


                  Route::get('/client/Mon-profil-user',function(){

                if(Auth::check())
                    {
                        if(Auth::user()->role_as=='0')
                            {
                                return view('client.profil');
                            }
                    }

                });




        Route::get('/', function () {     //Fonction ajouter le 05-05-2025 pour gerer la movaise section
            if (Auth::check()) {
                $user = Auth::user();

                // Déconnecter et rediriger selon le rôle
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();

                return redirect('/')->with('status', 'Session Expired. Please Login Again.');
            }

            // Si l'utilisateur n'est pas connecté, afficher la page de connexion
            return view('auth.login');
        });



        // -----------------------------test 12-05-2025----------------------



        // ----------------------------------------------


         // Vérification des plis -----------10-05-2025--------------------------------------------------------------


     // Vérification des éléments ou--------------------------------------------------

//------------------------------------------------------------------------------------------------------




Route::post('/admin/attributions/massAttribuer', [AttributionController::class, 'massAttribuer'])->name('admin.attributions.massAttribuer');
Route::get('/get-users', [DestinataireController::class, 'getUsers'])->name('get.users');
Route::get('/get-destinataires', [DestinataireController::class, 'getDestinataires'])->name('get.destinataires');



//Route::get('/admin/coursiers/top10/{periode}', [CoursierController::class, 'top10Coursiers'])->name('admin.coursiers.top10');

Auth::routes();
// Routes pour la réinitialisation du mot de passe
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/get-destinataires', [DestinataireController::class, 'getDestinataires'])->name('get.destinataires');


// Routes pour l'administrateur---------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {


    // Lien pour la vérification des depots des plis

    // Fin test pour les autres liens







    Route::get('/plis/{pliId}/accuse-retour', [PliController::class, 'showAccuseDeRetour'])->name('plis.accuse_retour');
    Route::get('/get-destinataires', [DestinataireController::class, 'getDestinataires'])->name('get.destinataires');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/add-user', [UserController::class, 'addUser'])->name('admin.add-user');
    Route::post('/add-user', [UserController::class, 'store'])->name('admin.store-user');
    Route::get('edit-user/{user_id}', [UserController::class, 'edit'])->name('admin.edit-user');
    Route::put('update-user/{user_id}', [UserController::class, 'update']);
    Route::get('delete-user/{user_id}', [UserController::class, 'destroy'])->name('admin.delete-user');
    Route::get('/admin/coursiers/top10/{periode}', [CoursierController::class, 'top10Coursiers'])->name('admin.coursiers.top10');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Route en  bas pour voir les plis supprimés
    Route::get('/admin/plis/plis_trashed', [PliController::class, 'pliesSupprimesParClient'])->name('admin.plis.plis_trashed');


//------------------------------------------------------------------------------------------------------








    Route::get('/admin/restaurer_pli/{historique_id}', [PliController::class, 'restaurerPli'])->name('admin.plis.restaurer');

    // Routes pour la gestion des destinataires par l'administrateur
    Route::get('/destinataires', [DestinataireController::class, 'index'])->name('admin.destinataires.index');

    Route::get('add-destinataire', [DestinataireController::class, 'create'])->name('client.add-destinataire');
    Route::post('add-destinataire', [DestinataireController::class, 'store'])->name('client.store-destinataire');
    Route::get('edit-destinataire/{destinataire_id}', [DestinataireController::class, 'edit'])->name('client.edit-destinataire');
    Route::put('update-destinataire/{destinataire_id}', [DestinataireController::class, 'update'])->name('client.update-destinataire');
    Route::get('delete-destinataire/{destinataire_id}', [DestinataireController::class, 'destroy'])->name('client.delete-destinataire');

    // Pour les fiche de mission ci bas---------------------------------------02-05-2025-----------------------------------------

        Route::get('/admin/attributions/fiche-mission-ramassage/{coursier_id1}', [AttributionController::class, 'ficheRamassage'])->name('fiche.ramassage');
        // Route::get('/admin/attributions/fiche-mission-depot', [AttributionController::class, 'ficheDepot'])->name('fiche.depot');

        Route::get('/admin/attributions/fiche-mission-depot/{coursier_id}', [AttributionController::class, 'ficheDepot'])->name('fiche.depot');

// ----------- Fin pour les fiches de mission ---02-05-2025----------------------------------------------------------------------------------------------------

    // Route pour l'attribution automatique

    Route::post('/attribution/process', [AttributionController::class, 'processAttribution'])->name('attribution.process');
        // fin  Test attribution automatique

    Route::get('plis', [PliController::class, 'index'])->name('admin.plis.index');

    // Pour le nouvelle affichage des plis---------------------
// Vérification des plius-----------------------------------
    // Route::get('/verification-depot-pli', [PliController::class, 'verificationDepotPli'])->name('verification.depot.pli');
// Vérification des éléments ou--------------------------------------------------

    Route::get('/historique-plis', [PliController::class, 'historiquePlis'])->name('plis.historique');
    Route::get('/plis-finalises', [PliController::class, 'historiquePlis'])->name('plis.historique');
    Route::get('/rechercher-plis-historique-compact', [PliController::class, 'rechercherPlisHistoriqueCompact']);
    Route::get('/historique-plis', [PliController::class, 'historiquePlis'])->name('historiquePlis');


    // Afficher les plis à attribuer
    Route::get('/attributions', [AttributionController::class, 'index'])->name('admin.attributions.index');

    Route::post('/plis/{pli}/changeStatuer', [PliController::class, 'changeStatuer'])->name('plis.changeStatuer');

    // Attribuer un pli à un coursier
    Route::post('/attribuer-pli/{pliId}', [AttributionController::class, 'attribuerPli'])->name('admin.attributions.attribuer');

    // Route::post('/attributions/{pli}/confirmer/{type}', [AttributionController::class, 'pliDejaAttribue'])
    // ->name('admin.attributions.confirmer');

    Route::post('/attributions/{pli}/statut', [AttributionController::class, 'updateStatut'])->name('attributions.updateStatut');

    Route::post('/admin/attributions/attribuer-groupe', [AttributionController::class, 'attribuerEnGroupe'])
    ->name('admin.attributions.attribuer.groupe'); //Test attribution de mass la fonction utilisée -------------------------------------------------------------------------------------------------

        // Amélioration pour l'erreur du pli
        Route::post('/attributions/{pliId}/confirmer/{type}', [AttributionController::class, 'pliDejaAttribue'])
    ->name('admin.attributions.confirmer');

    // Le  probleme était la methode, la fonction mal appelé au lieu d'appele la fonction qui gère la confirmation des attributs, c'était plustot une autre fonction
    Route::post('/admin/attributions/{pliId}/confirmer/{type}', [AttributionController::class, 'confirmerAttribution'])
    ->name('admin.attributions.confirmer');
    // En haut une amelioration
        // Ajouter la route pour la vue d'impression
        Route::get('/attributions/impression', [AttributionController::class, 'impression'])->name('admin.attributions.impression');
        Route::get('/admin/attributions/depot', [AttributionController::class, 'filtrerParCoursierDepot'])
        ->name('admin.attributions.depot');

// Route pour afficher un pli spécifique
    Route::get('/plis/{id}', [PliController::class, 'show'])->name('admin.plis.show');

    // Routes pour la gestion des coursiers
    Route::get('coursiers', [CoursierController::class, 'index'])->name('admin.coursiers.index');
    Route::get('add-coursier', [CoursierController::class, 'create']);
    Route::post('add-coursier', [CoursierController::class, 'store'])->name('admin.store-coursier');
    Route::get('edit-coursier/{coursier_id}', [CoursierController::class, 'edit'])->name('editCoursier');
    Route::put('update-coursier/{coursier_id}', [CoursierController::class, 'update'])->name('updateCoursier');
    Route::get('delete-coursier/{coursier_id}', [CoursierController::class, 'destroy'])->name('deleteCours');

    Route::get('/admin/coursiers/top10/{periode}', [CoursierController::class, 'top10Coursiers'])->name('admin.coursiers.top10');

    Route::get('zone', [ZoneController::class, 'index'])->name('admin.zone.index');
    Route::get('add-zone', [ZoneController::class, 'create']);
    Route::post('add-zone', [ZoneController::class, 'store']);
    Route::get('/zones/{id}', [ZoneController::class, 'show'])->name('admin.zone.show');

    Route::get('edit-zone/{zone_id}', [ZoneController::class, 'edit'])->name('edit');
    Route::put('/zone/{zone}', [ZoneController::class, 'update'])->name('admin.zone.update');
    // Route::get('zones-details', [ZoneController::class, 'showZonesWithDetails'])->name('zones.details');
    Route::get('/delete-zone/{zone_id}', [ZoneController::class, 'destroy'])->name('zone.delete');

});

//----------------------------------------------------------------------------------------- Fin de route pour le middle ware admin


// Routes pour les clients------------------------------------------------------------------------------------------------------------------------

Route::prefix('client')->middleware(['auth', 'isClient'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Client\DashboardController::class, 'index'])->name('client.dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Routes pour la gestion des destinataires par le client (limité aux destinataires propres à chaque client)
    Route::get('destinataires', [DestinataireController::class, 'index'])->name('client.destinataires.index');

    Route::get('add-destinataire', [DestinataireController::class, 'create'])->name('client.add-destinataire');
    Route::post('add-destinataire', [DestinataireController::class, 'store'])->name('client.store-destinataire');
    Route::get('edit-destinataire/{destinataire_id}', [DestinataireController::class, 'edit'])->name('client.edit-destinataire');
    Route::put('update-destinataire/{destinataire_id}', [DestinataireController::class, 'update'])->name('client.update-destinataire');
    Route::get('delete-destinataire/{destinataire_id}', [DestinataireController::class, 'destroy'])->name('client.delete-destinataire');


    // Routes pour la gestion des plies par le client (limité aux Pli propres à chaque client)
    Route::get('plis', [App\Http\Controllers\Client\PliController::class, 'index'])->name('client.plis.index');
    Route::get('add-pli', [App\Http\Controllers\Client\PliController::class, 'create'])->name('client.add-pli');
    Route::post('add-pli', [App\Http\Controllers\Client\PliController::class, 'store'])->name('client.store-pli');
    Route::get('edit-pli/{pli_id}', [App\Http\Controllers\Client\PliController::class, 'edit'])->name('client.edit-pli');
    Route::put('update-pli/{pli_id}', [App\Http\Controllers\Client\PliController::class, 'update'])->name('client.update-pli');
    Route::get('delete-pli/{pli_id}', [App\Http\Controllers\Client\PliController::class, 'destroy']);
    Route::post('/plis/{pli}/action', [PliController::class, 'gererAction'])->name('client.plis.action');


    // Nouvelle ajout --------------------------------------------------
    Route::get('/plis/{pli}/supprimer', [PliController::class, 'supprimerPli'])->name('plis.supprimer');
    Route::get('/plis/{pli}/restaurer', [PliController::class, 'restaurerPli'])->name('plis.restaurer');
    Route::get('/plis/historique', [PliController::class, 'historique'])->name('plis.historique');


    // Route pour afficher un pli spécifique
    Route::get('/plis/{id}', [PliController::class, 'show'])->name('client.plis.show');


    Route::get('/zones', [ZoneController::class, 'index'])->name('client.zones');

});
