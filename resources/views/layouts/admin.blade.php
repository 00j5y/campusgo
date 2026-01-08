@extends('layouts.app')

@section('content')

    <div class="bg-gray-50 min-h-screen py-8">
        <div class="container mx-auto px-4 max-w-7xl">

            <div class="mb-8">
                <div class="flex items-center gap-3 mb-1">
                    <div class="bg-green-100 p-2 rounded-lg text-green-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold text-gray-800">Tableau de bord administrateur</h1>
                </div>
                <p class="text-gray-500 text-sm ml-12">
                    Gérez les utilisateurs et modérez les trajets de la plateforme Campus'Go
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <h3 class="text-gray-500 text-sm font-medium">Utilisateurs</h3>
                    <p class="text-3xl font-bold text-gray-800 mt-2 mb-4">{{ $stats['users'] ?? 0 }}</p>
                    
                    <div class="flex items-center text-xs text-gray-400">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Total des utilisateurs inscrits</span>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <h3 class="text-gray-500 text-sm font-medium">Trajets</h3>
                    <p class="text-3xl font-bold text-gray-800 mt-2 mb-4">{{ $stats['trajets'] ?? 0 }}</p>
                    
                    <div class="flex items-center text-xs text-gray-400">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        <span>Total des trajets en cours</span>
                    </div>
                </div>

            </div>
            
            <div class="flex justify-center mb-6">
                <div class="bg-gray-200 p-1 rounded-full inline-flex items-center">
                    
                    <a href="{{ route('admin.utilisateurs') }}" 
                    class="{{ request()->routeIs('admin.utilisateurs') ? 'bg-white shadow-sm text-gray-800' : 'text-gray-500 hover:text-gray-700' }} 
                            px-6 py-2 rounded-full text-sm font-medium transition-all duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Utilisateurs
                    </a>

                    <a href="{{ route('admin.trajets') }}" 
                    class="{{ request()->routeIs('admin.trajets') ? 'bg-white shadow-sm text-gray-800' : 'text-gray-500 hover:text-gray-700' }} 
                            px-6 py-2 rounded-full text-sm font-medium transition-all duration-200 flex items-center ml-1">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        Trajets
                        <span class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $stats['trajets'] ?? 0 }}
                        </span>
                    </a>

                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                @yield('admin-content')
            </div>

        </div>
    </div>

@endsection


