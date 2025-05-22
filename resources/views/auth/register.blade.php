<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header>
        <a href="{{ route('home') }}" class="back-button">&lt;</a>
        <h1>BLADE_board</h1>
    </header>

    <main>        
        <h3>Register</h3>

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf
        
            <label>Name:</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name') <span>{{ $message }}</span> @enderror
            <br>
            <label>Email:</label>
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email') <span>{{ $message }}</span> @enderror
            <br>
            <label>Password:</label>
            <input type="password" name="password">
            @error('password') <span>{{ $message }}</span> @enderror
            <br>
            <label>Confirm Password:</label>
            <input type="password" name="password_confirmation">
            <br>
            <button type="submit">Register</button>
        </form>

        <br>
        
        <div>
            <span>Have an account?</span>
            <a href="{{ route('login') }}">Login</a>        
        </div>
    </main>
</body>
</html>