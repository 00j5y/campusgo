<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <title>@yield('title', "Campus'GO")</title>
    
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 flex flex-col min-h-screen font-sans antialiased">


    @include('components.navbar')

    <main class="relative flex-grow">
        
        @php
            $estAccueil = request()->routeIs('accueil');
        @endphp

        <div class="{{ $estAccueil ? 'absolute top-6 left-0 right-0 z-50' : 'container mx-auto px-4 mt-6' }} flex justify-center pointer-events-none">
            <div class="w-full max-w-4xl px-4 pointer-events-auto">

                @include('components.flash-message')
            </div>
        </div>

        @yield('content')
    </main>

    @include('components.footer')

    @yield('scripts')

</body>
</html>