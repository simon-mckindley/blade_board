@extends('layouts.default')

@section('title', 'Register Admin')

@section('add-link')
    <a class="link" href="{{ route('admin.users.index') }}">Users</a>
@endsection

@section('pagetitle', 'Register New Admin')

@section('maincontent')
    <form class="auth-form" method="POST" action="{{ route('register.submit') }}">
        @csrf

        <div class="input-cont">
            <div class="admin-radio-inputs">
                <input type="radio" name="role" id="admin" value="admin" checked>
                <label for="admin" tabindex="0">Admin</label>
                <input type="radio" name="role" value="super" id="super">
                <label for="super" tabindex="0">Super</label>
            </div>
            <label for="">User Type</label>
        </div>

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

        <button class="btn submit-btn" type="submit">Create New Admin</button>
    </form>
@endsection