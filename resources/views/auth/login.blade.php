@extends('layouts.default')

@section('title', 'Login')

@section('pagetitle', 'Login')

@section('maincontent')
    <form class="auth-form" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input-cont">
            <input type="email" name="email" id="email" required>
            <label for="email">Email</label>
        </div>

        <div class="input-cont">    
            <input type="password" name="password" id="password" required>
            <label for="password">Password</label>
        </div>

        <button class="btn" type="submit">Blade me in</button>

        @error('email')
            <div style="color: crimson">{{ $message }}</div>
        @enderror
    </form>

    <div class="auth-link">
        <span>Don't have an account?</span>
        <a class="link" href="{{ route('register') }}">Register</a>        
    </div>
@endsection