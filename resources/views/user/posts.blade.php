@extends('layouts.default')

@section('title', 'My Posts')

@section('pagetitle', 'My Posts')

@section('maincontent')
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
            <span>Created: {{ $post->created_at->diffForHumans() }}</span>
            <br>
            <span>Updated: {{ $post->updated_at->format('F j, Y') }}</span>

            <p>Comments: {{ $post->comments_count }}</p>`
        </div>
    @endforeach

    @if ($posts->isEmpty())
        <p>No posts found.</p>
    @endif

@endsection