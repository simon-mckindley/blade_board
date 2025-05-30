<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/bladeboard_icon.png') }}" type="image/x-icon">
    <title>@yield('title', "Blade_board")</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <nav>
        <a href="{{ route('home') }}" class="back-button">&lt</a>
        @auth
            <a href="{{ route('user.show') }}">{{ auth()->user()->name }}</a>
        @endauth
    </nav>

    <header>
        @yield('header')</h1>
    </header>
    
    <main>
        @yield('maincontent')
    </main>
</body>
</html>