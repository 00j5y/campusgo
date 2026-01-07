@extends('layouts.app')
@section('title', 'Sécurité - Campus\'GO')
@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-bold text-noir">Sécurité</h1>
                <p class="text-gris1 mt-2">Gérez votre mot de passe et vos accès</p>
            </div>
            <a href="{{ route('profile.show') }}" class="text-vert-principale hover:underline font-medium">
                &larr; Retour au profil
            </a>
        </div>

        <div class="max-w-2xl mx-auto">
            
            <form method="post" action="{{ route('password.update') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                @csrf
                @method('put')

                <h2 class="text-xl font-bold text-noir mb-6 flex items-center gap-2">
                    <span class="w-10 h-10 rounded-full bg-beige-principale flex items-center justify-center text-noir">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    Modifier le mot de passe
                </h2>

                <div class="space-y-6">       
                    <x-input-text 
                        name="current_password" 
                        label="Mot de passe actuel" 
                        type="password" 
                        autocomplete="current-password" 
                        bag="updatePassword"
                    />

                    <x-input-text 
                        name="password" 
                        label="Nouveau mot de passe" 
                        type="password" 
                        autocomplete="new-password" 
                        bag="updatePassword"
                    />

                    <x-input-text 
                        name="password_confirmation" 
                        label="Confirmer le nouveau mot de passe" 
                        type="password" 
                        autocomplete="new-password"
                        bag="updatePassword"
                    />
                </div>

                <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('profile.show') }}" class="text-gris1 hover:text-noir font-medium px-4 py-2">
                        Annuler
                    </a>
                    <button type="submit" class="bg-vert-principale hover:bg-vert-principal-h text-white px-6 py-2.5 rounded-lg font-medium shadow-sm transition-colors">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection