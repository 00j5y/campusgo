@extends('layouts.auth')

@section('title', 'Inscription - Campus\'GO')

@section('content')
    <div class="bg-gray-100 p-1 rounded-lg flex mb-8">
        <a href="{{ route('login') }}" class="w-1/2 text-center py-2 rounded-md text-sm font-medium transition-all text-gray-500 hover:text-gray-700 hover:bg-gray-200">
            Connexion
        </a>

        <button class="w-1/2 text-center py-2 rounded-md text-sm font-medium transition-all bg-white shadow-sm text-noir cursor-default">
            Inscription
        </button>
    </div>

    <div>
        <h2 class="text-2xl font-bold text-noir mb-2">Créer un compte</h2>
        <p class="text-gray-500 mb-8">Rejoignez la communauté Campus'Go</p>

        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf

            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-noir mb-1">Prénom</label>
                    <input type="text" name="firstname" value="{{ old('firstname') }}" required autofocus
                           class="w-full px-4 py-3 rounded-md bg-gray-50 border border-gray-200 focus:border-vert-principale focus:ring-vert-principale outline-none text-sm">
                    @error('firstname') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-noir mb-1">Nom</label>
                    <input type="text" name="lastname" value="{{ old('lastname') }}" required
                           class="w-full px-4 py-3 rounded-md bg-gray-50 border border-gray-200 focus:border-vert-principale focus:ring-vert-principale outline-none text-sm">
                    @error('lastname') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-noir mb-1">Email IUT</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-3 rounded-md bg-gray-50 border border-gray-200 focus:border-vert-principale focus:ring-vert-principale outline-none text-sm">
                <p class="text-xs text-gray-500 mt-1">Email universitaire obligatoire.</p>
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-noir mb-1">Mot de passe</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-3 rounded-md bg-gray-50 border border-gray-200 focus:border-vert-principale focus:ring-vert-principale outline-none text-sm">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-noir mb-1">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" required
                       class="w-full px-4 py-3 rounded-md bg-gray-50 border border-gray-200 focus:border-vert-principale focus:ring-vert-principale outline-none text-sm">
            </div>

            <button type="submit" class="w-full bg-vert-principale hover:bg-green-800 text-white font-bold py-3 px-4 rounded-md transition duration-300 flex items-center justify-center">
                Créer mon compte
            </button>
        </form>
    </div>
@endsection