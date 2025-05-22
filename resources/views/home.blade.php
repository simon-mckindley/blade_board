<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header>
        <h1>BLADE_<span>board</span></h1>
    </header>

    <main>        
        <h3>Welcome</h3>

    @auth
        <p>Hello {{ auth()->user()->name }}</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>

        <br>

        <a href="{{ route('create') }}">Create</a>
        <br>
        <a href="{{ route('display') }}">Display</a>
    @endauth

    @guest
        <div>
            <a href="{{ route('login') }}">Login</a>
            <br>
            <a href="{{ route('register') }}">Register</a>
        </div>
    @endguest

    </main>
</body>
</html>