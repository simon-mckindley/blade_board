@extends('layouts.default')

@section('title', 'Register')

@section('pagetitle', 'Register')

@section('maincontent')
    <form class="auth-form" method="POST" action="{{ route('register.submit') }}">
        @csrf

        <div class="input-cont">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name') <span>{{ $message }}</span> @enderror
        </div>
        
        <div class="input-cont">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email') <span>{{ $message }}</span> @enderror
        </div>
                
        <div class="input-cont">
            <label>Password</label>
            <input type="password" name="password">
            @error('password') <span>{{ $message }}</span> @enderror
        </div>
                
        <div class="input-cont">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation">
        </div>

        <button class="btn" type="submit">Set me up</button>
    </form>

    <div class="auth-link">
        <span>Have an account?</span>
        <a class="link" href="{{ route('login') }}">Login</a>        
    </div>
@endsection