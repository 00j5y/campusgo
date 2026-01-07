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

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 lg:p-8 mb-10">
            <form action="{{ route('profile.setup.update') }}" method="POST" class="space-y-8">
                @csrf 
                @method('PATCH')

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

                {{-- Tolérance aux détours --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tolérance aux détours</label>
                    <div class="relative">
                        <select name="max_detour" class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-vert-principale focus:ring-1 focus:ring-vert-principale cursor-pointer">
                            @foreach([0, 5, 10, 15, 20, 30] as $min)
                                <option value="{{ $min }}" {{ ($user->preference?->max_detour ?? 5) == $min ? 'selected' : '' }}>
                                    {{ $min == 0 ? 'Aucun détour accepté' : $min . ' min de détour max' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                {{-- Temps d'attente max --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Attente retardataires</label>
                    <div class="relative">
                        <select name="max_attente" class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-vert-principale focus:ring-1 focus:ring-vert-principale cursor-pointer">
                            @foreach([0, 5, 10, 15, 20] as $min)
                                <option value="{{ $min }}" {{ ($user->preference?->max_attente ?? 5) == $min ? 'selected' : '' }}>
                                    {{ $min == 0 ? 'Pile à l\'heure (0 min)' : 'J\'attends max ' . $min . ' min' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex justify-end border-t border-gray-100 mt-6">
                    <button type="submit" class="bg-vert-principale hover:bg-vert-principal-h text-white px-6 py-2.5 rounded-lg font-medium shadow-sm transition-colors cursor-pointer">
                        Enregistrer ces préférences
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-red-50 rounded-xl border border-red-100 p-6 lg:p-8">
            <h2 class="text-xl font-bold text-red-700 mb-2">Supprimer le compte</h2>
            <p class="text-red-600 mb-6 text-sm">
                Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées.
            </p>
            
            <div class="flex justify-end">
                <button 
                    onclick="openModal('modal-delete-account')" 
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-sm transition-colors cursor-pointer">
                    Supprimer mon compte
                </button>
            </div>
        </div>
    </div>
</div>

<x-popup 
    id="modal-delete-account"
    title="Êtes-vous sûr ?"
    message="Voulez-vous vraiment supprimer votre compte ? Cette action est définitive. Veuillez entrer votre mot de passe pour confirmer."
    action="{{ route('profile.destroy') }}"
    method="DELETE"
    type="danger"
    confirmText="Oui, supprimer mon compte"
>
    <div class="mt-4 text-left">
        <label for="password" class="sr-only">Mot de passe</label>
        <input
            id="password"
            name="password"
            type="password"
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm p-3 border"
            placeholder="Votre mot de passe actuel"
            required
        />
        @if($errors->userDeletion->get('password'))
            <p class="text-red-500 text-xs mt-1">{{ $errors->userDeletion->get('password')[0] }}</p>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    openModal('modal-delete-account');
                });
            </script>
        @endif
    </div>
</x-popup>

@endsection