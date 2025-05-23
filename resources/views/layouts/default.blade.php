<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/bladeboard_icon.png') }}" type="image/x-icon">
    <title>Display</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header>
        @yield('header')</h1>
    </header>
    
    <main>
        @yield('maincontent')
    </main>
</body>
</html>