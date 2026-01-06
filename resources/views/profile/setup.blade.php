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

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 lg:p-8">
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


                <div class="pt-6 flex justify-end border-t border-gray-100 mt-6">
                    <button type="submit" class="bg-vert-principale hover:bg-vert-principal-h text-white px-6 py-2.5 rounded-lg font-medium shadow-sm transition-colors">
                        Enregistrer cette préférence
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-10" x-data="{ open: false, password: '' }">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection