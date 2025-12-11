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
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @foreach($trajets as $trajet)
                    <tr class="hover:bg-gray-50 transition-colors">
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mr-3">
                                    {{-- Initiale du User (Simulée avec U si pas de nom) --}}
                                    U
                                </div>
                                <div>
                                    {{-- On affiche l'ID si on a pas encore la relation User --}}
                                    <div class="font-bold text-gray-900">User #{{ $trajet->ID_Utilisateur }}</div>
                                    <div class="text-xs text-gray-400">Conducteur</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-col space-y-1">
                                <div class="flex items-center text-gray-700">
                                    <div class="w-2 h-2 rounded-full bg-green-500 mr-2"></div>
                                    <span class="font-medium">{{ $trajet->Lieu_Depart }}</span>
                                </div>
                                <div class="flex items-center text-gray-700">
                                    <div class="w-2 h-2 rounded-full bg-red-500 mr-2"></div>
                                    <span class="font-medium">{{ $trajet->Lieu_Arrivee }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-gray-600">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $trajet->Date_ }}
                            </div>
                            <div class="flex items-center mt-1 text-xs text-gray-500 pl-6">
                                {{ $trajet->Heure_Depart }}
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="bg-blue-50 text-blue-700 py-1 px-2 rounded text-xs font-bold border border-blue-100">
                                {{ $trajet->Place_Disponible }} disp.
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($trajet->Place_Disponible == 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Complet
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    En ligne
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                
                                <button title="Voir les détails" class="text-gray-400 hover:text-blue-600 transition p-1 rounded-full hover:bg-blue-50">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>

                                <form action="#" method="POST" onsubmit="return confirm('Supprimer ce trajet ?');">
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