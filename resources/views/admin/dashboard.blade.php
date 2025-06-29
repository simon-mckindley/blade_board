@extends('layouts.default')

@section('title', 'Dashboard')

@auth
    @section('add-link')
        <button form="logout" class="btn warning-btn" type="submit">Logout</button>
    @endsection
@endauth

@section('pagetitle', 'Admin Dashboard')

@section('maincontent')
    @if (auth()->user()->isAdmin())
        <p>Select an administration task</p>
        
        <div class="home-actions">
            <a class="btn" href="">Users</a>
            <a class="btn" href="">Posts</a>
            <a class="btn" href="">Comments</a>
            <a class="btn" href="{{ route('user.show') }}">Tags</a>
        </div>
    @else
        <p>Unauthorised access</p>
    @endif
        
        <form id="logout" method="POST" action="{{ route('logout') }}">
            @csrf
        </form>
@endsection