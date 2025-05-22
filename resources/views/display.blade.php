<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header>
        <a href="{{ route('home') }}" class="back-button">&lt</a>
        @auth
            <span>{{ auth()->user()->name }}</span>
        @endauth
        <h1>Display</h1>
    </header>
    
    <main>
        <h2>Your post</h2>

        <h3>{{ $title }}</h3> 
        <p>{{ $post }}</p>

        <a href="{{ route('create') }}">Create another</a>
    
    </main>
</body>
</html>