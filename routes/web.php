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
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::fallback(function () {
    return view('errors.404');
});