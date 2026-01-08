@extends('layouts.admin')

@section('title', 'Admin Utilisateurs - Campus\'GO')

@section('admin-content')

    <div class="mb-8 mt-6 px-2">
        <h2 class="text-2xl font-bold text-gray-800">Gestion des utilisateurs</h2>
        <p class="text-sm text-gray-500 mt-1">Gérez les comptes utilisateurs et leurs statuts</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                
                <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">Utilisateur</th>
                        <th class="px-6 py-4 font-medium">Email</th>
                        <th class="px-6 py-4 font-medium">Membre depuis</th>
                        <th class="px-6 py-4 font-medium text-center">Trajets</th>
                        <th class="px-6 py-4 font-medium text-center">Note</th>
                        <th class="px-6 py-4 font-medium text-center">Statut</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $user->prenom }} {{ $user->nom }}</div>
                            @if($user->est_admin)
                                <span class="text-[10px] bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full font-bold">ADMIN</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-gray-500">
                            {{ $user->email }}
                        </td>

                        <td class="px-6 py-4 text-gray-500">
                            @if($user->date_creation)
                                {{ \Carbon\Carbon::parse($user->date_creation)->format('d/m/Y') }}
                            @else
                                <span class="text-gray-500 italic">Date inconnue</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center text-gray-500">
                            {{ $user->trajets_count }} 
                            @if($user->trajets_count > 1)
                                trajets
                            @else
                                trajet
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center text-gray-400">
                            -
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($user->est_suspendu)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                    Suspendu
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    Actif
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-4">
                                
                                <form action="{{ route('admin.utilisateurs.suspend', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    
                                    @if($user->est_suspendu)
                                        <button type="submit" class="px-3 py-1.5 bg-green-50 border border-green-200 text-green-700 text-xs font-medium rounded hover:bg-green-100 transition shadow-sm">
                                            Réactiver
                                        </button>
                                    @else
                                        <button type="submit" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-xs font-medium rounded hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 transition shadow-sm">
                                            Suspendre
                                        </button>
                                    @endif
                                </form>
                                
                                <form action="{{ route('admin.utilisateurs.delete', $user->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement ce compte ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Supprimer définitivement" class="text-gray-400 hover:text-red-600 transition pt-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($users->isEmpty())
                <div class="p-8 text-center text-gray-500">Aucun utilisateur trouvé.</div>
            @endif
        </div>
        
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $users->links() }}
        </div>
        
        
    </div>

@endsection