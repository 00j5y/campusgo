<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <title>@yield('title', "Campus'GO")</title>
    
    <link rel="shortcut icon" href="{{ asset('images/logo/logo.png') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    
    <main>
        @yield('content')

        @stack('scripts')
    </main>

</body>
</html>
