<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header>
        <a href="{{ route('home') }}" class="back-button">&lt;</a>
        @auth
            <span>{{ auth()->user()->name }}</span>
        @endauth
        <h1>Create</h1>
    </header>

    <main>
        <h2>Create a new item</h2>
        <form action="{{ route('store') }}" method="POST">
            @csrf
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>
            <br><br>
            <label for="post">Post</label>
            <textarea id="post" name="post" rows="4" required></textarea>
            <br><br>
            <button type="submit">Create</button>
        </form>

        {{-- @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif --}}

        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </main>
    
</body>
</html>