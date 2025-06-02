@extends('layouts.default')

@section('title', 'Edit Profile')

@section('pagetitle', 'Edit Profile')

@section('maincontent')
    @if ($user->id === auth()->id())
        <form method="POST" action="{{ route('user.update', $user->id) }}" style="display:inline;">
            @csrf
            @method('PUT')
            <label for="name">
            <h2 style="margin-block: 0 5px">{{ ucfirst($user->name) }}</h2>
            <input type="text" name="name" id="name" value="{{ $user->name }}" required>
            @error('name') <span style="color:crimson">{{ $message }}</span> @enderror
            <br>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ $user->email }}" required>
            @error('email') <span style="color:crimson">{{ $message }}</span> @enderror
            <br>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Leave blank to keep current password">
            @error('password') <span style="color:crimson">{{ $message }}</span> @enderror
            <br>
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation">
            <br>

            <button type="submit">Update</button>
        </form>                
    @else
        <p style="color: crimson">Unauthorised access</p>
    @endif
@endsection