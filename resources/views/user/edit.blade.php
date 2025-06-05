@extends('layouts.default')

@section('title', 'Edit Profile')

@section('add-link')
    <a class="btn warning-btn" href="{{ route('user.show') }}">Cancel</a>
@endsection

@section('pagetitle', 'Edit Profile')

@section('maincontent')
    @if ($user->id === auth()->id())
        <form class="auth-form" method="POST" action="{{ route('user.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="input-cont">
                @error('name') <span style="color:crimson">{{ $message }}</span> @enderror
                <div class="edit-input">
                    <input type="text" name="name" id="name" value="{{ $user->name }}" required disabled>
                    <button type="submit" class="edit-btn" id="edit-name-btn">
                        <img height="24" src="{{ asset('images/edit_square.svg') }}" alt="Edit Info">
                    </button>
                </div>
                <label for="name">Name</label>    
            </div>

            <div class="input-cont">
                @error('email') <span style="color:crimson">{{ $message }}</span> @enderror
                <div class="edit-input">
                    <input type="email" name="email" id="email" value="{{ $user->email }}" required disabled>
                    <button type="submit" class="edit-btn" id="edit-email-btn">
                        <img height="24" src="{{ asset('images/edit_square.svg') }}" alt="Edit Info">
                    </button>
                </div>
                <label for="email">Email</label>
            </div>

            <div class="input-cont">
                @error('password') <span style="color:crimson">{{ $message }}</span> @enderror
                <div class="edit-input">
                    <input type="password" name="password" id="password" placeholder="Leave blank to keep password" disabled>
                    <button type="submit" class="edit-btn" id="edit-password-btn">
                        <img height="24" src="{{ asset('images/edit_square.svg') }}" alt="Edit Info">
                    </button>
                </div>
                <label for="password">Password</label>
            </div>

            <div class="input-cont">
                <input type="password" name="password_confirmation" id="password_confirmation" disabled>
                <label for="password_confirmation">Confirm Password</label>
            </div>

            <button class="btn" type="submit" id="submit-btn" disabled>Change me</button>
        </form>

    @else
        <p style="color: crimson">Unauthorised access</p>
    @endif
@endsection