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
            <input type="text" name="name" id="name" value="{{ old('name') }}">
            <label for="name">Name</label>
        </div>
        
        <div class="input-cont">
            @error('email') <span class="input-error">{{ $message }}</span> @enderror
            <input type="email" name="email" id="email" value="{{ old('email') }}">
            <label for="email">Email</label>
        </div>
                
        <div class="input-cont">
            @error('password') <span class="input-error">{{ $message }}</span> @enderror
            <input type="password" name="password" id="password">
            <label for="password">Password</label>
        </div>
                
        <div class="input-cont">
            <input type="password" name="password_confirmation" id="password_confirmation">
            <label for="password_confirmation">Confirm Password</label>
        </div>

        <button class="btn" type="submit">Create New Admin</button>
    </form>
@endsection