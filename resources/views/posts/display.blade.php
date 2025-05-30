@extends('layouts.default')

@section('title', 'Posts Page')

@section('header')
    <h1>Posts Page</h1>
@endsection

@section('maincontent')
    @if (session('success'))
        <div style="color: green">
            {{ session('success') }}
        </div>
    @endif

    <h2>All Posts</h2>

    <a href="{{ route('posts.create') }}">Create a Post</a>
    <br>

    @foreach ($posts as $post)
        <div class="post">
            @if ($post->user->id === auth()->id())
                <div class="post-actions" style="background-color: blue; color: white;">
                    Mine
                </div>
            @endif

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