<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header>
        <a href="{{ route('home') }}" class="back-button">&lt;</a>
        <h1>BLADE_board</h1>
    </header>

    <main>        
        <h3>Login</h3>

        <form method="POST" action="{{ route('login') }}">
            @csrf
        
            <label>Email:</label>
            <input type="email" name="email" required>
            
            <label>Password:</label>
            <input type="password" name="password" required>
        
            <button type="submit">Login</button>
        
            @error('email')
                <div>{{ $message }}</div>
            @enderror
        </form>
        
        <div>
            <p>Don't have an account?</p>
            <a href="{{ route('register') }}">Register</a>        
        </div>
    </main>
</body>
</html>