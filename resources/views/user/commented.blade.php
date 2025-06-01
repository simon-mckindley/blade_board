@extends('layouts.default')

@section('title', 'Commented Posts')

@section('header')
    <h1>My Commented Posts</h1>
@endsection

@section('maincontent')
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
            <p>Comments: {{ $post->comments_count }}</p>
        </div>
    @endforeach

    @if ($posts->isEmpty())
        <p>No posts found.</p>
    @endif

@endsection