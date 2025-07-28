@php
    $pageTitle = 'Admin Dashboard';
@endphp

@extends('layouts.default')

@section('title', $pageTitle)

@section('add-link')
    <button form="logout" class="btn warning-btn submit-btn" type="submit">Logout</button>
@endsection

@section('pagetitle')
    <h2>{{ $pageTitle }}</h2>
@endsection

@section('maincontent')
    <p>Select an administration task</p>
    
    <ul class="home-actions" role="navigation">
        <li>
            <a class="btn" href="{{ route('admin.users.index') }}">Users</a>
        </li>
        <li>
            <a class="btn" href="{{ route('admin.reports.index') }}">Reports</a>
        </li>
        <li>
            <a class="btn" href="{{ route('posts.display') }}">Posts & Comments</a>
        </li>
        <li>
            <a class="btn" href="{{ route('admin.tags.index') }}">Tags</a>
        </li>
    </ul>
    
    <form id="logout" method="POST" action="{{ route('logout') }}">
        @csrf
    </form>
@endsection