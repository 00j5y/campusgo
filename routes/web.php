<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('accueil');
});

Route::fallback(function () {
    return view('errors.404');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/mon-profil', [ProfileController::class, 'show'])->name('profile.show');
});


    // Route temporaire pour tester sans page de connexion
    Route::get('/force-login', function () {
        // On prend le premier utilisateur qui traîne dans la base de données
        $user = \App\Models\User::first();

        if (!$user) {
            return "Erreur : Aucun utilisateur trouvé dans la base de données 'UTILISATEUR'. Ajoutez-en un via PHPMyAdmin !";
        }

        // On force la connexion de cet utilisateur
        \Illuminate\Support\Facades\Auth::login($user);

        // On redirige vers votre page profil
        return redirect()->route('profile.show');
    });


require __DIR__.'/auth.php';
