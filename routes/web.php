<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController; // Le contrôleur du collègue
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


// Page d'accueil
Route::get('/', [HomeController::class, 'accueil'])->name('accueil');


// Page de connexion (GET) -> Appelle la méthode index()
Route::get('/login', [AuthController::class, 'index'])->name('login');

// Action de connexion (POST) -> Appelle la méthode connexion()
Route::post('/login', [AuthController::class, 'connexion']);

// 1. Afficher le formulaire (GET) -> Appelle la méthode 'create' qu'on vient d'ajouter
Route::get('/register', [AuthController::class, 'create'])->name('register');

// 2. Traiter le formulaire (POST) -> Appelle la méthode 'register' existante
Route::post('/register', [AuthController::class, 'register']);

// Déconnexion (POST) -> Appelle la méthode logout()
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


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

Route::fallback(function () {
    return view('errors.404');
});