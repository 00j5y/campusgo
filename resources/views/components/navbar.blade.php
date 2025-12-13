<header x-data="{ isOpen: false }" class="bg-white shadow-lg relative">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <div class="flex shrink-0 items-center">
                <a href="{{ url('/') }}" class="flex shrink-0 items-center">
                    <img class="h-12 w-auto" src="{{ asset('favicon.ico') }}" alt="Campus Go Logo">
                    <div class="ml-3 flex items-baseline text-2xl font-semibold font-sans">
                        <span class="text-vert-principale">Campus</span>
                        <span class="text-beige-second">'Go</span>
                    </div>
                </a>
            </div>

            <nav class="hidden lg:flex space-x-8 items-center">
                <a href="{{ url('/') }}" class="flex items-center text-gris1 font-semibold hover:text-vert-principale transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Accueil
                </a>
                <a href="{{ route('rechercher') }}" class="flex items-center text-gris1 hover:text-vert-principale transition font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Rechercher
                </a>
                <a href="{{ route('trajets.mes-trajets') }}" class="flex items-center text-gris1 hover:text-vert-principale transition font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Mes Trajets
                </a>
                @auth
                <a href="{{ route('profile.show') }}" class="flex items-center text-gris1 hover:text-vert-principale transition font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profil
                </a>
                @else
                    <a href="{{ route('profile.show') }}" class="flex items-center text-gris1 hover:text-vert-principale transition font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profil
                    </a>
                @endauth
            </nav>

            <div class="hidden lg:flex items-center space-x-6">
                <a href="{{ route('trajets.create') }}" class="bg-vert-principale text-white px-5 py-2 rounded-md font-medium hover:bg-vert-principal-h transition shadow-sm whitespace-nowrap">

                    Proposer un Trajet
                </a>
                @auth {{--  Si l'utilisateur est connecté --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="flex w-full items-center justify-center px-5 py-3 rounded-md font-medium text-gris1 hover:bg-gray-50 hover:text-rouge transition">
                        <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Déconnexion
                    </a>
                </form>

                @else
                    {{-- Si l'utilisateur n'est pas connecté --}}
                    <a href="{{ route('login') }}" class="flex w-full items-center justify-center px-5 py-3 rounded-md font-medium text-gris1 hover:bg-gray-50 hover:text-vert-principale transition">
                        <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Connexion
                    </a>
                @endauth
            </div>

            <div class="-mr-2 flex lg:hidden">
                <button @click="isOpen = !isOpen" type="button" class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gris1 hover:text-vert-principale hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-vert-principale" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Ouvrir le menu</span>
                    <svg :class="{'hidden': isOpen, 'block': !isOpen }" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg :class="{'block': isOpen, 'hidden': !isOpen }" class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="lg:hidden bg-white border-t border-gray-200" id="mobile-menu">
        
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ url('/') }}" class="text-gris1 hover:text-vert-principale hover:bg-gray-50 px-3 py-2 rounded-md text-base font-medium flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Accueil
            </a>
            <a href="{{ route('rechercher') }}" class="text-gris1 hover:text-vert-principale hover:bg-gray-50 px-3 py-2 rounded-md text-base font-medium flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Rechercher
            </a>
            <a href="{{ route('trajets.mes-trajets') }}" class="text-gris1 hover:text-vert-principale hover:bg-gray-50 px-3 py-2 rounded-md text-base font-medium flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Mes Trajets
            </a>
            <a href="#" class="text-gris1 hover:text-vert-principale hover:bg-gray-50 px-3 py-2 rounded-md text-base font-medium flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Profil
            </a>
        </div>

        <div class="pt-4 pb-4 border-t border-gray-200">
            <div class="px-2 space-y-3">
                <a href="{{route('trajets.create')}}" class="block w-full text-center bg-vert-principale text-white px-5 py-3 rounded-md font-medium hover:bg-vert-principal-h transition shadow-sm">
                    Proposer un Trajet
                </a>
                @auth {{--  Si l'utilisateur est connecté --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="flex w-full items-center justify-center px-5 py-3 rounded-md font-medium text-gris1 hover:bg-gray-50 hover:text-rouge transition">
                        <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Déconnexion
                    </a>
                </form>

                @else
                    {{-- Si l'utilisateur n'est pas connecté --}}
                    <a href="{{ route('login') }}" class="flex w-full items-center justify-center px-5 py-3 rounded-md font-medium text-gris1 hover:bg-gray-50 hover:text-vert-principale transition">
                        <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Connexion
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>