@extends('layouts.app')

@section('title', 'Paramètres - Campus\'GO')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 max-w-3xl">
        
        <div class="mb-8 flex items-center justify-between">
             <div>
                <h1 class="text-2xl font-bold text-noir">Paramètres</h1>
                <p class="text-gris1 mt-1">Contrôlez qui peut voir vos informations.</p>
            </div>
            <a href="{{ route('profile.show') }}" class="text-vert-principale hover:underline font-medium">&larr; Retour au profil</a>
        </div>

        @if (session('status') === 'privacy-updated')
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <strong class="font-bold">Succès !</strong> Vos préférences ont été enregistrées.
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 lg:p-8">
            <form action="{{ route('profile.privacy.update') }}" method="POST" class="space-y-8">
                @csrf @method('PATCH')

                <div class="flex items-start gap-4">
                    <div class="flex h-6 items-center">
                        <input id="telephone_public" name="telephone_public" type="checkbox" 
                            {{ $user->preference?->telephone_public ? 'checked' : '' }}
                            class="h-5 w-5 rounded border-gray-300 text-vert-principale focus:ring-vert-principale">
                    </div>
                    <div class="text-sm leading-6">
                        <label for="telephone_public" class="font-medium text-gray-900 text-lg">Rendre mon numéro de téléphone public</label>
                        <p class="text-gray-500 mt-1">Si décoché, votre numéro ne sera visible qu'aux personnes dont vous avez accepté la réservation (recommandé).</p>
                    </div>
                </div>

                <hr class="border-gray-100">

                <div class="flex items-start gap-4">
                    <div class="flex h-6 items-center">
                        <input id="trajets_publics" name="trajets_publics" type="checkbox" 
                               {{ $user->preference?->trajets_publics ? 'checked' : '' }}
                               class="h-5 w-5 rounded border-gray-300 text-vert-principale focus:ring-vert-principale">
                    </div>
                    <div class="text-sm leading-6">
                        <label for="trajets_publics" class="font-medium text-gray-900 text-lg">Afficher mes anciens trajets</label>
                        <p class="text-gray-500 mt-1">Permet aux autres utilisateurs de voir votre historique de covoiturage sur votre profil public pour augmenter la confiance.</p>
                    </div>
                </div>

                <div class="pt-6 flex justify-end border-t border-gray-100 mt-6">
                    <button type="submit" class="bg-vert-principale hover:bg-vert-principal-h text-white px-6 py-2.5 rounded-lg font-medium shadow-sm transition-colors">
                        Enregistrer les préférences
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="max-w-4xl mx-auto mt-10 bg-white rounded-2xl shadow-sm border border-red-100 p-6 lg:p-8">
        
        <div class="flex items-start gap-4">
            <div class="p-3 bg-red-50 rounded-full shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            
            <div class="flex-1" 
                 x-data="{ 
                    open: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }}, 
                    password: '' 
                 }">
                 
                <h2 class="text-xl font-bold text-noir">Supprimer le compte</h2>
                <p class="text-sm text-gris1 mt-1 max-w-xl">
                    Une fois votre compte supprimé, toutes vos ressources et données seront définitivement effacées.
                </p>
                
                <button type="button" @click.prevent="open = true" class="mt-4 bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-lg font-medium transition-colors text-sm">
                    Je veux supprimer mon compte
                </button>

                <div x-show="open" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        
                        <div x-show="open" 
                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                             class="fixed inset-0 bg-beige-principale bg-opacity-90 transition-opacity" 
                             @click="open = false" 
                             aria-hidden="true"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <div x-show="open" @click.stop
                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full relative z-50">
                            
                            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                                @csrf
                                @method('delete')

                                <h2 class="text-lg font-medium text-gray-900">
                                    Êtes-vous sûr de vouloir supprimer votre compte ?
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    Veuillez entrer votre mot de passe pour confirmer. Cette action est irréversible.
                                </p>

                                <div class="mt-6">
                                    <label for="password" class="sr-only">Mot de passe</label>
                                    
                                    <input
                                        id="password"
                                        name="password"
                                        type="password"
                                        x-model="password" 
                                        class="mt-1 block w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm"
                                        placeholder="Votre mot de passe actuel"
                                    />
                                    
                                    @error('password', 'userDeletion')
                                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mt-6 flex justify-end gap-3">
                                    <button type="button" @click="open = false" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                        Annuler
                                    </button>

                                    <button type="submit" 
                                            :disabled="!password"
                                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                        Supprimer définitivement
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                </div>
        </div>
    </div>
</div>
@endsection