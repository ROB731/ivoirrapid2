<?php

use App\Http\Controllers\Admin\CoursierController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ZoneController;
use App\Http\Controllers\AttributionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Client\DestinataireController;
use App\Http\Controllers\Client\PliController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;






Route::get('/', function () {
    return view('auth.login');
});



Auth::routes();
// Routes pour la réinitialisation du mot de passe
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');


// Routes pour l'administrateur
Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/plis/{pliId}/accuse-retour', [PliController::class, 'showAccuseDeRetour'])->name('plis.accuse_retour');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/add-user', [UserController::class, 'addUser'])->name('admin.add-user');
    Route::post('/add-user', [UserController::class, 'store'])->name('admin.store-user');
    Route::get('edit-user/{user_id}', [UserController::class, 'edit'])->name('admin.edit-user');
    Route::put('update-user/{user_id}', [UserController::class, 'update']);
    Route::get('delete-user/{user_id}', [UserController::class, 'destroy'])->name('admin.delete-user');

    // Routes pour la gestion des destinataires par l'administrateur
    Route::get('/destinataires', [DestinataireController::class, 'index'])->name('admin.destinataires.index');

    // Routes pour la gestion des plis par l'administrateur
    Route::get('plis', [PliController::class, 'index'])->name('admin.plis.index');




    // Afficher les plis à attribuer
    Route::get('/attributions', [AttributionController::class, 'index'])->name('admin.attributions.index');
    Route::post('/plis/{pli}/changeStatuer', [PliController::class, 'changeStatuer'])->name('plis.changeStatuer');

    // Attribuer un pli à un coursier
    Route::post('/attribuer-pli/{pliId}', [AttributionController::class, 'attribuerPli'])->name('admin.attributions.attribuer');
    Route::post('/attributions/{pli}/confirmer/{type}', [AttributionController::class, 'pliDejaAttribue'])
    ->name('admin.attributions.confirmer');
    Route::post('/attributions/{pli}/statut', [AttributionController::class, 'updateStatut'])->name('attributions.updateStatut');
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

    Route::get('zone', [ZoneController::class, 'index'])->name('admin.zone.index');
    Route::get('add-zone', [ZoneController::class, 'create']);
    Route::post('add-zone', [ZoneController::class, 'store']);
    Route::get('/zones/{id}', [ZoneController::class, 'show'])->name('admin.zone.show');

    Route::get('edit-zone/{zone_id}', [ZoneController::class, 'edit'])->name('edit');
    Route::put('/zone/{zone}', [ZoneController::class, 'update'])->name('admin.zone.update');
    // Route::get('zones-details', [ZoneController::class, 'showZonesWithDetails'])->name('zones.details');
    Route::get('/delete-zone/{zone_id}', [ZoneController::class, 'destroy'])->name('zone.delete');

});


// Routes pour les clients
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


    // Route pour afficher un pli spécifique
    Route::get('/plis/{id}', [PliController::class, 'show'])->name('client.plis.show');


    Route::get('/zones', [ZoneController::class, 'index'])->name('client.zones');






});
