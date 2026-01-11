@extends('layouts.admin')

@section('title', 'Admin Trajets - Campus\'GO')

@section('admin-content')

    <div class="mb-8 mt-6 px-2">
        <h2 class="text-2xl font-bold text-gray-800">Modération des trajets</h2>
        <p class="text-sm text-gray-500 mt-1">Consultez et supprimez les covoiturages inappropriés</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                
                <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">Conducteur</th>
                        <th class="px-6 py-4 font-medium">Itinéraire</th>
                        <th class="px-6 py-4 font-medium">Date & Heure</th>
                        <th class="px-6 py-4 font-medium text-center">Places</th>
                        <th class="px-6 py-4 font-medium text-center">État</th>
                        <th class="px-6 py-4 font-medium text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @foreach($trajets as $trajet)
                    <tr class="hover:bg-gray-50 transition-colors">
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mr-3">
                                    {{-- Affichage de la 1ère lettre du prénom en majuscule, ou '?' si pas d'utilisateur --}}
                                    {{ strtoupper(substr($trajet->user?->prenom ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    {{-- Affichage du prénom et du nom --}}
                                    <div class="font-bold text-gray-900">
                                        {{ $trajet->user?->prenom }} {{ $trajet->user?->nom }}
                                    </div>
                                    <div class="text-xs text-gray-400">Conducteur</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-col space-y-1">
                                {{-- Lieu de départ --}}
                                <div class="flex items-center text-gray-700">
                                    <div class="w-2 h-2 rounded-full bg-green-500 mr-2"></div>
                                    <span class="font-medium">
                                        {{-- On utilise ?? 'N/A' pour éviter d'afficher une ligne vide si la donnée manque --}}
                                        {{ $trajet->lieu_depart ?? 'Départ inconnu' }}
                                    </span>
                                </div>

                                {{-- Lieu d'arrivée --}}
                                <div class="flex items-center text-gray-700">
                                    <div class="w-2 h-2 rounded-full bg-red-500 mr-2"></div>
                                    <span class="font-medium">
                                        {{ $trajet->lieu_arrivee ?? 'Arrivée inconnue' }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-gray-600">
                            {{-- Ligne pour la Date --}}
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium">
                                    {{-- Formatage en JJ/MM/AAAA --}}
                                    {{ \Carbon\Carbon::parse($trajet->date_depart)->format('d/m/Y') }}
                                </span>
                            </div>
                            {{-- Ligne pour l'Heure --}}
                            <div class="flex items-center mt-1 text-xs text-gray-500 pl-6">
                                {{-- Formatage en HH:MM --}}
                                {{ \Carbon\Carbon::parse($trajet->heure_depart)->format('H:i') }}
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="text-gray-600 font-medium">
                                {{ $trajet->place_disponible }} 
                                @if($trajet->place_disponible > 1)
                                    places disponibles
                                @else
                                    place disponible
                                @endif
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($trajet->place_disponible == 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-gray-800">
                                    Complet
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    En ligne
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center">
                                <form action="{{route('admin.trajets.delete', $trajet->id) }}" method="POST" onsubmit="return confirm('Supprimer ce trajet ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Supprimer le trajet" class="text-gray-400 hover:text-red-600 transition p-1 rounded-full hover:bg-red-50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($trajets->isEmpty())
                <div class="p-8 text-center text-gray-500">Aucun trajet en ligne pour le moment.</div>
            @endif
        </div>
    </div>

@endsection