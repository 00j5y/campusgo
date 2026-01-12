@extends('layouts.auth')

@section('title', 'Connexion - Campus\'GO')

@section('content')
    <div class="bg-gray-100 p-1 rounded-lg flex mb-8">
        <button class="w-1/2 text-center py-2 rounded-md text-sm font-medium transition-all bg-white shadow-sm text-noir cursor-default">
            Connexion
        </button>

        <a href="{{ route('register') }}" class="w-1/2 text-center py-2 rounded-md text-sm font-medium transition-all text-gray-500 hover:text-gray-700 hover:bg-gray-200">
            Inscription
        </a>
    </div>

    <div>
        <h2 class="text-2xl font-bold text-noir mb-2">Connectez-vous</h2>
        <p class="text-noir mb-8">Accédez à votre compte Campus'Go</p>

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-noir mb-1">Email IUT</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       placeholder="prenom.nom@u-picardie.fr"
                       class="w-full px-4 py-3 rounded-md bg-gray-50 border border-gray-200 focus:border-vert-principale focus:bg-white focus:ring-1 focus:ring-vert-principale outline-none transition text-sm">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-noir mb-1">Mot de passe</label>
                <input type="password" name="password" required
                       placeholder="••••••••"
                       class="w-full px-4 py-3 rounded-md bg-gray-50 border border-gray-200 focus:border-vert-principale focus:bg-white focus:ring-1 focus:ring-vert-principale outline-none transition text-sm">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full bg-vert-principale hover:bg-green-800 text-white font-bold py-3 px-4 rounded-md transition duration-300 flex items-center justify-center">
                Se connecter
            </button>
        </form>
    </div>
@endsection
