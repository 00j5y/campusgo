<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController; // Le contrôleur du collègue
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TrajetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RechercheController;


// Page d'accueil
Route::get('/', [HomeController::class, 'accueil'])->name('accueil');

// Page de recherche de trajets
Route::get('/rechercher', [RechercheController::class, 'index'])->name('rechercher');

// Route pour RÉSERVER un trajet 
Route::post('/reserver/{id}', [RechercheController::class, 'reserver'])->name('reserver');

// Route pour ANNULER un trajet 
Route::post('/annuler/{id}', [RechercheController::class, 'annuler'])->name('annuler');

// Routes pour la proposition de trajets
Route::get('proposer-trajet', [HomeController::class, 'create'])->name('trajets.create');

//Proposer-un-Trajet
Route::get('/proposer-trajet', [TrajetController::class, 'create'])->name('trajets.create');
Route::post('/proposer-trajets', [TrajetController::class, 'store'])->name('trajets.store');
Route::get('/trajets-confirmation',[TrajetController::class, 'confirmation'])->name('trajets.confirmation');

// Erreur 404
Route::fallback(function () {
    return view('errors.404');
});

Route::middleware('auth')->group(function () {
    Route::get('/mon-profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/mon-profil/modifier', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/mon-profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/mon-profil', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/vehicule/ajouter', [App\Http\Controllers\VehiculeController::class, 'create'])->name('vehicule.create');
    Route::patch('/profil/preference/toggle', [ProfileController::class, 'togglePreference'])->name('preference.toggle');

    Route::post('/vehicule', [App\Http\Controllers\VehiculeController::class, 'store'])->name('vehicule.store');
    Route::delete('/vehicule/{id}', [App\Http\Controllers\VehiculeController::class, 'destroy'])->name('vehicule.destroy');

    Route::get('/parametres/securite', [ProfileController::class, 'editSecurity'])->name('profile.security');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    Route::get('/membre/{id}', [App\Http\Controllers\ProfileController::class, 'showPublic'])->name('profile.public');

    Route::get('/profile/history', [ProfileController::class, 'history'])->name('profile.history');
    Route::get('/profile/setup', [ProfileController::class, 'setup'])->name('profile.setup');
    Route::patch('/profile/setup', [ProfileController::class, 'updateSetup'])->name('profile.setup.update');

    Route::patch('/profile/preference/discussion', [ProfileController::class, 'updateDiscussion'])->name('preference.discussion');
});

require __DIR__.'/auth.php';