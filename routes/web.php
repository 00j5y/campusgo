<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TrajetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RechercheController;

// Page d'accueil
Route::get('/', [HomeController::class, 'accueil'])->name('accueil');

Route::get('/rechercher', [RechercheController::class, 'index'])->name('rechercher');

Route::post('/reserver/{id}', [RechercheController::class, 'reserver'])->name('reserver');

Route::post('/annuler/{id}', [RechercheController::class, 'annuler'])->name('annuler');

Route::get('/mes-trajets', [TrajetController::class, 'historique'])
    ->middleware('auth') 
    ->name('historique-trajet');

Route::get('/proposer-trajet', [TrajetController::class, 'create'])
    ->middleware('auth')
    ->name('trajets.create');

Route::post('/proposer-trajets', [TrajetController::class, 'store'])
    ->middleware('auth')
    ->name('trajets.store');

Route::get('/trajets-confirmation',[TrajetController::class, 'confirmation'])
    ->middleware('auth')
    ->name('trajets.confirmation');

// Erreur 404
Route::fallback(function () {
    return view('errors.404');
});

Route::middleware('auth')->group(function () {
    Route::get('/mon-profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/mon-profil/modifier', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/mon-profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/mon-profil', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/photo', [ProfileController::class, 'destroyPhoto'])->name('profile.photo.destroy');

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

    Route::get('/mes-avis', [App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/laisser-un-avis/{id_trajet}', [App\Http\Controllers\ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/avis', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/avis/{id}', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::delete('/trajets/{id}', [TrajetController::class, 'destroy'])->name('trajets.destroy');

});

require __DIR__.'/auth.php';