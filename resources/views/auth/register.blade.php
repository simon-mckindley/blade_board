@extends('layouts.default')

@section('title', 'Register')

@section('pagetitle', 'Register')

@section('maincontent')
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
@endsection