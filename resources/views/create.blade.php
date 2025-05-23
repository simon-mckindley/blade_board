@extends('layouts.default')

@section('header')
    <a href="{{ route('home') }}" class="back-button">&lt</a>
    @auth
        <span>{{ auth()->user()->name }}</span>
    @endauth
    <h1>Create</h1>
@endsection

@section('maincontent')   
    <h2>Create a new item</h2>
    <form action="{{ route('store') }}" method="POST">
        @csrf
        <label for="title">Title</label>
        <input type="text" id="title" name="title" required>
        <br><br>
        <label for="post">Post</label>
        <textarea id="post" name="post" rows="4" required></textarea>
        <br><br>
        <button type="submit">Create</button>
    </form>

    {{-- @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif --}}

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
@endsection