@extends('layouts.default')

@section('header')
    <div class="home-title">BLADE_<span>board</span></div>
@endsection

@auth
    @section('add-link')
        <a class="link" href="{{ route('posts.display') }}">All Posts</a>
    @endsection
@endauth

@section('pagetitle', 'Welcome')

@section('maincontent')
    @auth
        <p>Hello <strong style="font-size: 1.1em">{{ ucwords(auth()->user()->name) }}</strong></p>
        
        <div class="home-actions">
            <a class="btn" href="{{ route('posts.create') }}">Create</a>
            <a class="btn" href="{{ route('posts.display') }}">All Posts</a>
            <a class="btn" href="{{ route('user.show') }}">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn" type="submit">Logout</button>
            </form>
        </div>
    @endauth
    
    @guest
        <div class="home-actions">
            <a class="btn" href="{{ route('login') }}">Login</a>
            <a class="btn" href="{{ route('register') }}">Register</a>
        </div>
    @endguest
    
@endsection