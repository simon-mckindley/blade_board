@extends('layouts.default')

@section('header')
    <a href="{{ route('home') }}" class="back-button">&lt;</a>
    <h1>BLADE_board</h1>
@endsection

@section('maincontent')   
    <h3>Login</h3>

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
        <a href="{{ route('register') }}">Register</a>        
    </div>
@endsection