<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUtilisateursController;
use App\Http\Controllers\AdminTrajetsController;

// Page d'accueil
Route::get('/', [HomeController::class, 'accueil'])->name('accueil');

// Erreur 404
Route::fallback(function () {
    return view('errors.404');
});

/*
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
*/

require __DIR__.'/auth.php';


// Route pour la gestion des utilisateurs par l'admin
Route::middleware(['auth'])->get('/admin-utilisateurs', [AdminUtilisateursController::class, 'index'])->name('admin.utilisateurs');

// Route pour la gestion des trajets par l'admin
Route::middleware(['auth'])->get('/admin-trajets', [AdminTrajetsController::class, 'index'])->name('admin.trajets');

// Route pour SUPPRIMER un utilisateur
Route::delete('/admin/utilisateurs/{id}', [AdminUtilisateursController::class, 'destroy'])->name('admin.utilisateurs.delete');