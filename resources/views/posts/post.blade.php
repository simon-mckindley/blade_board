@extends('layouts.default')

@section('header')
    <h1>Post</h1>
@endsection

@section('maincontent')   
    <div class="post">
        @if ($post->user->id === auth()->id())
            <div class="post-actions" style="background-color: blue; color: white;">
                <a href="{{ route('posts.edit', $post) }}" style="color: inherit">Edit</a>
                <form method="POST" action="{{ route('posts.destroy', $post->id) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="color: inherit; background-color: transparent;">Delete</button>
                </form>
            </div>
        @endif

        <h2 style="margin-block: 0 5px">{{ ucfirst($post->title) }}</h2>
        <div style="font-size: 0.8em; display: flex; flex-direction: column;">
            <span>{{ ucwords($post->user->name) }}</span>
            <span>Created: {{ $post->created_at->diffForHumans() }}</span>
            <span>Updated: {{ $post->updated_at->format('F j, Y') }}</span>
        </div>
        <p>{{ $post->content }}</p>
        <p>Tags: 
            @foreach ($post->tags as $tag)
                <span>{{ $tag->name }}</span>
            @endforeach
        </p>
    </div>

    @if (session('success'))
        <div style="color: green; margin-top: 10px;">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <a href="{{ route('posts.display') }}">All Posts</a>
    <br>
    <a href="{{ route('user.posts') }}">Your Posts</a>
@endsection