@extends('layouts.default')

@section('header')
    <h1>
        <div class="home-title">BLADE_<span>board</span></div>
    </h1>
@endsection

@section('maincontent')
        
    <h3>Welcome</h3>
    
    @auth
    <p>Hello {{ auth()->user()->name }}</p>
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