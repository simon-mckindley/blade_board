@extends('layouts.default')

@section('header')
    <a href="{{ route('home') }}" class="back-button">&lt</a>
    @auth
        <span>{{ auth()->user()->name }}</span>
    @endauth
    <h1>Display</h1>
@endsection

@section('maincontent')   
    <h2>Your post</h2>

    <h3>{{ $title }}</h3> 
    <p>{{ $post }}</p>

    <a href="{{ route('create') }}">Create another</a>
@endsection