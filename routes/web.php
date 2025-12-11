<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TrajetController;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', [HomeController::class, 'accueil'])->name('accueil');

//Proposer-un-Trajet
Route::get('/proposer-trajet', [TrajetController::class, 'create'])->name('trajets.create');
Route::post('/proposer-trajets', [TrajetController::class, 'store'])->name('trajets.store');
Route::get('/trajets-confirmation',[TrajetController::class, 'confirmation'])->name('trajets.confirmation');

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
