@extends('layouts.default')

@section('title', 'Login')

@section('pagetitle', 'Login')

@section('maincontent')
    <form class="auth-form" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input-cont">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="input-cont">    
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button class="btn" type="submit">Blade me</button>

        @error('email')
            <div style="color: crimson">{{ $message }}</div>
        @enderror
    </form>

    <div class="auth-link">
        <span>Don't have an account?</span>
        <a class="link" href="{{ route('register') }}">Register</a>        
    </div>
@endsection