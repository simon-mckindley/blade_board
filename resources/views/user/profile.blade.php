@extends('layouts.default')

@section('header')
    <a href="{{ route('home') }}" class="back-button">&lt</a>
    <h1>{{ $user->name }}</h1>
@endsection

@section('maincontent')
        
    <dl>
        <dt>Email</dt>
        <dd>{{ $user->email }}</dd>

        <dt>Created At</dt>
        <dd>{{ $user->created_at }}</dd>

        <dt>Updated At</dt>
        <dd>{{ $user->updated_at }}</dd>
    </dl>

    <a href="">Edit</a>
    <br>
    <a href="{{ route('user.posts') }}">Your Posts</a>
    
@endsection