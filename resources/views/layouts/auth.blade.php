<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <title>@yield('title', "Campus'GO")</title>
    
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="min-h-screen bg-white lg:flex">
        <div class="hidden lg:flex lg:w-1/2 bg-cover relative" style="background-image: url('{{ asset('images/auth/iut.jpg') }}');">
            <div class="relative z-10 w-full p-12 flex flex-col justify-between text-white h-full">
                <a href="{{ url('/') }}" class="flex items-center">
                    <div class="bg-white text-vert-principale p-2 rounded-full font-bold flex items-center shadow-lg">
                        <img class="size-12 mx-3" src="{{ asset('favicon.ico') }}"> <span class="text-vert-principale">Campus</span>
                        <span class="text-beige-second">'Go</span>
                    </div>
                </a>

                <div class="mb-10 bg-black/30 rounded-2xl p-4">
                    <h1 class="text-4xl font-bold mb-6">Bienvenue sur Campus'Go</h1>
                    <p class="text-lg mb-8">La plateforme de covoiturage dédiée à la communauté de l'IUT d'Amiens.</p>
                    <ul class="font-medium">
                        <li class="flex items-center">
                            <img class="size-5 mr-2" src="{{ asset('images/accueil/icones/valider.png') }}">
                            Réservé aux membres de l'IUT d'Amiens
                        </li>
                        <li class="flex items-center">
                            <img class="size-5 mr-2" src="{{ asset('images/accueil/icones/valider.png') }}">
                            Profils vérifiés et sécurisés
                        </li>
                        <li class="flex items-center">
                            <img class="size-5 mr-2" src="{{ asset('images/accueil/icones/valider.png') }}">
                            Économisez jusqu'à 50% sur vos trajets
                        </li>
                    </ul>
                </div>

                <div class="flex items-center space-x-12 font-bold border-t border-white/20 pt-8">
                    <div><span class="block text-3xl">250+</span><span class="text-sm">Membres actifs</span></div>
                    <div><span class="block text-3xl">500+</span><span class="text-sm">Trajets partagés</span></div>
                    <div><span class="block text-3xl">2.5T</span><span class="text-sm">CO₂ économisé</span></div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-12">
            <div class="w-full max-w-md">
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-vert-principale">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>

    </div>
</body>
</html>