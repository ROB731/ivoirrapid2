<?php

use App\Http\Controllers\Admin\CoursierController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Client\DestinataireController;
use App\Http\Controllers\Client\PliController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Routes pour l'administrateur
Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/add-user', [UserController::class, 'addUser'])->name('admin.add-user');
    Route::post('/add-user', [UserController::class, 'store'])->name('admin.store-user');

    // Routes pour la gestion des destinataires par l'administrateur
    Route::get('/destinataires', [DestinataireController::class, 'index'])->name('admin.destinataires.index');

    // Routes pour la gestion des plis par l'administrateur
    Route::get('plis', [PliController::class, 'index'])->name('client.plis.index');
    Route::get('coursiers', [CoursierController::class, 'index']);
    Route::get('add-coursier', [CoursierController::class, 'create']);
    Route::
});

// Routes pour les clients
Route::prefix('client')->middleware(['auth', 'isClient'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Client\DashboardController::class, 'index'])->name('client.dashboard');

    // Routes pour la gestion des destinataires par le client (limité aux destinataires propres à chaque client)
    Route::get('destinataires', [DestinataireController::class, 'index'])->name('client.destinataires.index');
    Route::get('add-destinataire', [DestinataireController::class, 'create'])->name('client.add-destinataire');
    Route::post('add-destinataire', [DestinataireController::class, 'store'])->name('client.store-destinataire');

    // Routes pour la gestion des plies par le client (limité aux Pli propres à chaque client)
    Route::get('plis', [App\Http\Controllers\Client\PliController::class, 'index'])->name('client.plis.index');
    Route::get('add-pli', [App\Http\Controllers\Client\PliController::class, 'create'])->name('client.add-pli');
    Route::post('add-pli', [App\Http\Controllers\Client\PliController::class, 'store'])->name('client.store-pli');
});
