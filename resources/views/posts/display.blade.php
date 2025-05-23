@extends('layouts.default')

@section('header')
    <a href="{{ route('home') }}" class="back-button">&lt</a>
    @auth
        <span>{{ auth()->user()->name }}</span>
    @endauth
    <h1>Display</h1>
@endsection

@section('maincontent')   
    <h2>All Posts</h2>

    @foreach ($posts as $post)
        <div class="post">
            <h3>{{ $post->title }}</h3>
            <p>{{ $post->content }}</p>
            <p>Tags: 
                @foreach ($post->tags as $tag)
                    <span>{{ $tag->name }}</span>
                @endforeach
            </p>
            <p>{{ $post->user->name }}</p>
        </div> 
        <br>
    @endforeach

    <a href="{{ route('posts.create') }}">Create another</a>
@endsection