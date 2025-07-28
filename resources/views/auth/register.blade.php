@php
    $pageTitle = 'Register';
@endphp

@extends('layouts.default')

@section('title', $pageTitle)

@section('pagetitle')
    <h2>{{ $pageTitle }}</h2>
@endsection

@section('maincontent')
    <form class="auth-form" method="POST" action="{{ route('register.submit') }}">
        @csrf

        <div class="input-cont">
            @error('name') <span class="input-error">{{ $message }}</span> @enderror
            <input type="text" name="name" id="name" value="{{ old('name') }}" autocomplete="username">
            <label for="name">
                <img class="icon" src="{{ asset('images/edit_note_icon.svg') }}" alt="">
                <span>Name</span>
            </label>
        </div>
        
        <div class="input-cont">
            @error('email') <span class="input-error">{{ $message }}</span> @enderror
            <input type="email" name="email" id="email" value="{{ old('email') }}" autocomplete="email">
            <label for="email">
                <img class="icon" src="{{ asset('images/email_icon.svg') }}" alt="">
                <span>Email</span>
            </label>
        </div>
                
        <div class="input-cont">
            @error('password') <span class="input-error">{{ $message }}</span> @enderror
            <input type="password" name="password" id="password" autocomplete="new-password">
            <label for="password">
                <img class="icon" src="{{ asset('images/password_icon.svg') }}" alt="">
                <span>Password</span>
            </label>
        </div>
                
        <div class="input-cont">
            <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password">
            <label for="password_confirmation"><span>Confirm Password</span></label>
        </div>

        <button class="btn submit-btn" type="submit">Set me up</button>
    </form>

    <div class="auth-link">
        <span>Have an account?</span>
        <a class="link" href="{{ route('login') }}">Login</a>        
    </div>
@endsection