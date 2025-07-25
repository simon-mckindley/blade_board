@extends('layouts.default')

@section('title', 'Login')

@section('pagetitle', 'Login')

@section('maincontent')
    <form class="auth-form" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input-cont">
            @error('email') <span class="input-error">{{ $message }}</span> @enderror
            <input type="email" name="email" id="email" required autocomplete="email">
            <label for="email">
                <img class="icon" src="{{ asset('images/email_icon.svg') }}" alt="">
                <span>Email</span>
            </label>
        </div>

        <div class="input-cont">
            @error('password') <span class="input-error">{{ $message }}</span> @enderror
            <input type="password" name="password" id="password" required autocomplete="current-password">
            <label for="password">
                <img class="icon" src="{{ asset('images/password_icon.svg') }}" alt="">
                <span>Password</span>
            </label>
        </div>

        <button class="btn submit-btn" type="submit">Blade me in</button>

        @error('invalid')
            <div class="input-error">{{ $message }}</div>
        @enderror

        @error('inactive')
            <div class="input-error">
                {{ $errors->first('inactive') }}
                @if ($errors->has('contact'))
                <br>
                Please contact support at -> 
                <a style="text-decoration: underline" href="mailto:{{ $errors->first('contact') }}">{{ $errors->first('contact') }}</a>
                for more information.
                @endif
            </div>
        @enderror
    </form>

    <div class="auth-link">
        <span>Don't have an account?</span>
        <a class="link" href="{{ route('register') }}">Register</a>        
    </div>
@endsection