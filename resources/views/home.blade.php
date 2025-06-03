@extends('layouts.default')

@section('header')
    <div class="home-title">BLADE_<span>board</span></div>
@endsection

@auth
    @section('add-link')
        <a href="{{ route('posts.display') }}">All Posts</a>
    @endsection
@endauth

@section('pagetitle', 'Welcome')

@section('maincontent')
    @auth
        <p>Hello {{ ucwords(auth()->user()->name) }}</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
        
        <br>
        
        <a href="{{ route('posts.create') }}">Create</a>
        <br>
        <a href="{{ route('posts.display') }}">All Posts</a>
        <br>
        <a href="{{ route('user.show') }}">Profile</a>
    @endauth
    
    @guest
        <div>
            <a href="{{ route('login') }}">Login</a>
            <br>
            <a href="{{ route('register') }}">Register</a>
        </div>
    @endguest
    
@endsection