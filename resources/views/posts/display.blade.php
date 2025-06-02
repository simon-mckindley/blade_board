@extends('layouts.default')

@section('title', 'Posts')

@section('pagetitle', 'Posts')

@section('maincontent')
    @if (session('success'))
        <div style="color: green">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('posts.create') }}">Create a Post</a>
    <br>

    @foreach ($posts as $post)
        <div class="post @if ($post->user->id === auth()->id()) highlighted @endif">
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
@endsection