@extends('layouts.default')

@section('title', 'Login')

@section('pagetitle', 'Login')

@section('maincontent')
    <form class="auth-form" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input-cont">
            @error('email') <span class="input-error">{{ $message }}</span> @enderror
            <input type="email" name="email" id="email" required>
            <label for="email">Email</label>
        </div>

        <div class="input-cont">    
            @error('password') <span class="input-error">{{ $message }}</span> @enderror
            <input type="password" name="password" id="password" required>
            <label for="password">Password</label>
        </div>

        <button class="btn submit-btn" type="submit">Blade me in</button>

        @error('invalid')
            <div class="input-error">{{ $message }}</div>
        @enderror
    </form>

    <div class="auth-link">
        <span>Don't have an account?</span>
        <a class="link" href="{{ route('register') }}">Register</a>        
    </div>
@endsection