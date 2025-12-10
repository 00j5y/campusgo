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
                            {{-- On vérifie si created_at existe, sinon on met un message par défaut --}}
                            @if($user->created_at)
                                {{ $user->created_at->format('d/m/Y') }}
                            @else
                                <span class="text-gray-400 italic">Date inconnue</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center text-gray-700">
                            -
                        </td>

                        <td class="px-6 py-4 text-center text-gray-400">
                            -
                        </td>

                        <td class="px-6 py-4 text-center">
                            {{-- ICI il faudra lier ça à une colonne 'est_suspendu' dans ta BDD plus tard --}}
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                Actif
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-4">
                                
                                <button title="Empêcher la connexion" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-xs font-medium rounded hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 transition">
                                    Suspendre
                                </button>
                                
                                <form action="#" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement ce compte ?');">
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
    </div>

@endsection