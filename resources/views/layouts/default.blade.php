<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Simon Mckindley">
    <link rel="icon" href="{{ asset('images/bladeboard_icon.png') }}" type="image/x-icon">
    <title>@yield('title', "BLADE_board")</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header>
        <nav>
            <a href="{{ route('home') }}" class="back-button">
                <img src="{{ asset('images/bladeboard_icon.png') }}" alt="B" width="32">
            </a>
            @auth
                <a class="link" href="{{ route('user.show') }}">{{ auth()->user()->name }}</a>
            @endauth
        </nav>
        
        <h1>@yield('header', "BLADE_board")</h1>

        <div class="add-link">
            @yield('add-link')
        </div>
    </header>
    
    <main>
        <div class="logo"></div>
        <h2 class="page-title">@yield('pagetitle')</h2>
        
        @yield('maincontent')
    </main>
</body>

@yield('scripts')
</html>