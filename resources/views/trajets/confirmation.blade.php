@extends('layouts.app')

@section('title', 'Proposer un trajet - Campus\'GO')

@section('content')
    <main class="mx-auto max-w-xl p-6 text-center m-30">

    <div class="bg-white border border-vert-principale/50 rounded-xl p-8 shadow-xl">
        
        <h1 class="text-2xl font-bold text-vert-principale mb-4">
        Trajet Publié !
        </h1>
        
        <p class="text-gris1 mb-8">
            Merci d'utiliser Campus'GO ! Votre trajet est maintenant visible et prêt à être réservé.
        </p>

        <div class="flex flex-col items-center space-y-4 md:flex-row md:justify-center md:space-y-0 md:space-x-6">
            <a href="{{ route('accueil') }}" class="inline-block bg-vert-principale text-white px-8 py-2 rounded-md font-medium transition shadow-lg w-full sm:w-auto hover:bg-vert-principal-h">
            Accueil
            </a>
    
            <a href="#" class="inline-block border border-gray-300 text-gray-700 px-8 py-2 rounded-md font-medium transition w-full sm:w-auto hover:bg-gray-100">
            Voir Mes Trajets
            </a>
        </div>
    </div>
</main>
@endsection