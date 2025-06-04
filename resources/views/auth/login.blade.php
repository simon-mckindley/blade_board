@extends('layouts.default')

@section('title', 'Login')

@section('pagetitle', 'Login')

@section('maincontent')
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label>Email:</label>
        <input type="email" name="email" required>
        <br>
        <label>Password:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Login</button>

        @error('email')
            <div style="color: crimson">{{ $message }}</div>
        @enderror
    </form>

    <br>

    <div>
        <span>Don't have an account?</span>
        <a class="link" href="{{ route('register') }}">Register</a>        
    </div>
@endsection