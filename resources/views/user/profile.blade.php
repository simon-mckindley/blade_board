@extends('layouts.default')

@section('title', 'User Profile')

@section('header')
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

    <a href="{{ route('user.edit', $user->id) }}">Edit</a>
    <br>
    <a href="{{ route('user.posts') }}">Your Posts</a>

    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif
    
@endsection