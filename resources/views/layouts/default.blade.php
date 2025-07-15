<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Simon Mckindley">
    <link rel="icon" href="{{ asset('images/bladeboard_icon.png') }}" type="image/x-icon">
    <title>@yield('title', "BLADE_board")</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('cdns')
</head>

<body>
    <header>
        <div class="header-inner">
            <nav>
                <div class="large-scrn-nav">
                    <a class="home-button" href="{{ route('home') }}">
                        <img src="{{ asset('images/bladeboard_icon.png') }}" alt="B" width="32">
                    </a>
                    @auth
                    <a class="link" href="{{ route('user.show') }}">{{ auth()->user()->name }}</a>
                    @endauth
                </div>
                <div class="small-menu-wrapper small-scrn-nav" onclick="this.classList.toggle('clicked')">
                    <img src="{{ asset('images/bladeboard_icon.png') }}" alt="B" width="32">
                    <a class="link" href="{{ route('home') }}">Home</a>
                    @auth
                    <a class="link" href="{{ route('user.show') }}">{{ auth()->user()->name }}</a>
                    @endauth
                </div>
            </nav>
            
            <h1>@yield('header', "BLADE_board")</h1>
            
            <div class="add-link">
                @yield('add-link')
            </div>
        </div>
    </header>
    
    <main>
        <div class="logo"></div>
        
        <div class="padding-box">

            <h2 class="page-title">@yield('pagetitle')</h2>
            
            @yield('maincontent')

        </div>
    </main>

    @php
        $alert = $alert ?? session('alert');
    @endphp

    @if (isset($alert))
        <x-alert 
            :type="$alert['type']" 
            :message="$alert['message']"/>
    @endif
</body>

@yield('scripts')
</html>