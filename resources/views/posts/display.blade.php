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

    <a href="{{ route('posts.create') }}">Create a Post</a>
    <br>

    @foreach ($posts as $post)
        <div class="post">
            <a href="{{ route('posts.show', $post->id) }}">
                <h3>{{ $post->title }}</h3>
            </a>
            <p>{{ $post->content }}</p>
            <p>Tags: 
                @foreach ($post->tags as $tag)
                    <span>{{ $tag->name }}</span>
                @endforeach
            </p>
            <p>{{ $post->user->name }}</p>
        </div>
    @endforeach
@endsection