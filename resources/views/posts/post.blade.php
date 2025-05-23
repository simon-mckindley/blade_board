@extends('layouts.default')

@section('header')
    <a href="{{ route('home') }}" class="back-button">&lt</a>
    @auth
        <span>{{ auth()->user()->name }}</span>
    @endauth
    <h1>Post</h1>
@endsection

@section('maincontent')   
    <div class="post">
        <h2 style="margin-block: 0 5px">{{ $post->title }}</h2>
        <div style="font-size: 0.8em; display: flex; flex-direction: column;">
            <span>Author: {{ $post->user->name }}</span>
            <span>Created: {{ $post->created_at }}</span>
            <span>Updated: {{ $post->updated_at }}</span>
        </div>
        <p>{{ $post->content }}</p>
        <p>Tags: 
            @foreach ($post->tags as $tag)
                <span>{{ $tag->name }}</span>
            @endforeach
        </p>
    </div>

    <a href="{{ route('posts.display') }}">Posts</a>
@endsection