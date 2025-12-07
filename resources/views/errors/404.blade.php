@extends('layouts.app')

@section('title', '404 - Campus\'GO')

@section('content')
<section class="min-h-[60vh] flex items-center justify-center bg-beige-principale py-20">
    <div class="container mx-auto px-4 text-center">

        <h1 class="text-8xl font-bold text-vert-principale mb-4">404</h1>
        <h2 class="text-2xl font-semibold text-noir mb-6">Oups ! Mauvaise route.</h2>
        
        <p class="text-gris1 text-lg mb-10 max-w-lg mx-auto">
            On dirait que ce chemin n'existe pas sur Campus'Go !
        </p>

        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 bg-vert-principale hover:bg-vert-principal-h text-white px-8 py-3.5 rounded-lg font-medium transition-colors shadow-sm">
            <span>Retour à l'accueil</span>
        </a>

    </div>
</section>
@endsection