@extends('layouts.default')

@section('header')
    <h1>BLADE_<span>board</span></h1>
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
    
    <a href="{{ route('create') }}">Create</a>
    <br>
    <a href="{{ route('display') }}">Display</a>
    <br>
    <a href="{{ route('myaccount') }}">My Account</a>
    @endauth
    
    @guest
    <div>
        <a href="{{ route('login') }}">Login</a>
        <br>
        <a href="{{ route('register') }}">Register</a>
    </div>
    @endguest
    
@endsection