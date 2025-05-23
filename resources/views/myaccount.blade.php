@extends('layouts.default')

@section('header')
    <a href="{{ route('home') }}" class="back-button">&lt</a>
    <h1>Your Account</h1>
@endsection

@section('maincontent')
        
    <dl>
        <dt>Name</dt>
        <dd>{{ auth()->user()->name }}</dd>

        <dt>Email</dt>
        <dd>{{ auth()->user()->email }}</dd>

        <dt>Created At</dt>
        <dd>{{ auth()->user()->created_at }}</dd>

        <dt>Updated At</dt>
        <dd>{{ auth()->user()->updated_at }}</dd>
    </dl>
    
@endsection