@extends('layouts.app')

@section('title', 'Historique de connexion - Campus\'GO')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-noir">Historique de connexion</h1>
                <p class="text-gris1 mt-1">Vos 20 dernières connexions à la plateforme.</p>
            </div>
            <a href="{{ route('profile.show') }}" class="text-vert-principale hover:underline font-medium">&larr; Retour au profil</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-900 font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Appareil (Navigateur)</th>
                        <th class="px-6 py-4">Adresse IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($logins as $login)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $login->date_connexion->format('d/m/Y à H:i') }}
                            </td>
                            <td class="px-6 py-4 truncate max-w-xs" title="{{ $login->agent_utilisateur }}">
                                {{ Str::limit($login->agent_utilisateur, 60) }}
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-gray-500">
                                {{ $login->adresse_ip }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-400 italic">
                                Aucune donnée enregistrée pour le moment. Déconnectez-vous et revenez !
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection