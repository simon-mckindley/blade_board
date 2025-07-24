@extends('layouts.default')

@section('title', 'Dashboard')

@section('add-link')
    <button form="logout" class="btn warning-btn submit-btn" type="submit">Logout</button>
@endsection

@section('pagetitle', 'Admin Dashboard')

@section('maincontent')
    <p>Select an administration task</p>
    
    <div class="home-actions">
        <a class="btn" href="{{ route('admin.users.index') }}">Users</a>
        <a class="btn" href="{{ route('admin.reports.index') }}">Reports</a>
        <a class="btn" href="{{ route('posts.display') }}">Posts & Comments</a>
        <a class="btn" href="{{ route('admin.tags.index') }}">Tags</a>
    </div>
    
    <form id="logout" method="POST" action="{{ route('logout') }}">
        @csrf
    </form>
@endsection