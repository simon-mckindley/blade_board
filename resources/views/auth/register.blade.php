@extends('layouts.default')

@section('title', 'Register')

@section('pagetitle', 'Register')

@section('maincontent')
    <form class="auth-form" method="POST" action="{{ route('register.submit') }}">
        @csrf

        <div class="input-cont">
            @error('name') <span>{{ $message }}</span> @enderror
            <input type="text" name="name" id="name" value="{{ old('name') }}">
            <label for="name">Name</label>
        </div>
        
        <div class="input-cont">
            @error('email') <span>{{ $message }}</span> @enderror
            <input type="email" name="email" id="email" value="{{ old('email') }}">
            <label for="email">Email</label>
        </div>
                
        <div class="input-cont">
            @error('password') <span>{{ $message }}</span> @enderror
            <input type="password" name="password" id="password">
            <label for="password">Password</label>
        </div>
                
        <div class="input-cont">
            <input type="password" name="password_confirmation" id="password_confirmation">
            <label for="password_confirmation">Confirm Password</label>
        </div>

        <button class="btn" type="submit">Set me up</button>
    </form>

    <div class="auth-link">
        <span>Have an account?</span>
        <a class="link" href="{{ route('login') }}">Login</a>        
    </div>
@endsection