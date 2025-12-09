@extends('layouts.app')

@section('title', 'Mes avis - Campus\'GO')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-bold text-noir">Mes Avis</h1>
                <p class="text-gris1 mt-2">Découvrez ce que les covoitureurs pensent de vous</p>
            </div>
            <a href="{{ route('profile.show') }}" class="text-vert-principale hover:underline font-medium">
                &larr; Retour au profil
            </a>
        </div>

        <div class="max-w-3xl mx-auto space-y-8">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-vert-principale"></div>
                
                <p class="text-gris1 uppercase text-xs font-bold tracking-widest mb-2">Note Globale</p>
                
                <div class="flex justify-center items-baseline gap-2 mb-4">
                    <span class="text-6xl font-bold text-noir">{{ $average }}</span>
                    <span class="text-gray-400 text-2xl font-light">/5</span>
                </div>
                
                <div class="flex justify-center gap-1 mb-2 text-yellow-400">
                    @for($i = 1; $i <= 5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ $i <= round($average) ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>
                
                <p class="text-sm text-gris1 font-medium bg-gray-50 inline-block px-3 py-1 rounded-full border border-gray-100">
                    Basé sur {{ $total }} avis reçus
                </p>
            </div>

            <div class="space-y-4">
                @forelse($reviews as $review)
                    <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm transition hover:shadow-md">
                        <div class="flex gap-4">
                            <div class="shrink-0">
                                <div class="w-12 h-12 bg-vert-principale/10 rounded-full flex items-center justify-center text-vert-principale font-bold text-lg border border-vert-principale/20">
                                    {{ substr($review['author'], 0, 1) }}
                                </div>
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-1">
                                    <h3 class="font-bold text-noir text-lg">{{ $review['author'] }}</h3>
                                    <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-md border border-gray-100">{{ $review['date'] }}</span>
                                </div>
                                
                                <div class="flex text-yellow-400 text-sm mb-3">
                                    @for($i = 0; $i < 5; $i++)
                                        <span>{{ $i < $review['rating'] ? '★' : '☆' }}</span>
                                    @endfor
                                </div>
                                
                                <p class="text-gray-600 leading-relaxed">
                                    "{{ $review['comment'] }}"
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p class="text-gray-500 font-medium">Vous n'avez pas encore reçu d'avis.</p>
                        <p class="text-sm text-gray-400 mt-1">Les avis apparaîtront ici une fois vos premiers trajets effectués.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>
@endsection