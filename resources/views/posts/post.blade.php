@php
    $createdAt = ($post->created_at->diffInDays(now()) < 1) ?
        $post->created_at->diffForHumans() : 
        $post->created_at->format('j F Y');

    $updatedAt = ($post->updated_at->diffInDays(now()) < 1) ?
        $post->updated_at->diffForHumans() :
        $post->updated_at->format('j F Y');
@endphp


@extends('layouts.default')

@section('title', 'Post - ' . ucfirst($post->title))

@section('maincontent')  
    <div class="post-navigation">
        <a class="link" href="{{ route('posts.display') }}">All Posts</a>
        <a class="link" href="{{ route('user.posts') }}">My Posts</a>
        <a class="link" href="{{ route('posts.create') }}">Create a Post</a>
    </div>

    <div class="post">
        @if ($post->user->id === auth()->id())
            <div class="post-actions">
                <a class="action" href="{{ route('posts.edit', $post) }}">
                    <img height="24" src="{{ asset('images/edit_document_icon.svg') }}" alt="Edit Post">
                </a>
                <form method="POST" action="{{ route('posts.destroy', $post->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action">
                        <img height="24" src="{{ asset('images/delete_icon.svg') }}" alt="Delete Post">
                    </button>
                </form>
            </div>
        @endif

        <div class="post-meta">
            <span class="post-date">Created - {{ $createdAt }}</span>
            <span class="post-date">Updated - {{ $updatedAt }}</span>
            <span>{{ ucwords($post->user->name) }}</span>
        </div>

        <div class="post-main">
            <div class="post-title">{{ ucwords($post->title) }}</div>

            <div class="post-content">{{ $post->content }}</div>

            <div class="post-tags">
                @foreach ($post->tags as $tag)
                    <span>{{ $tag->name }}</span>
                @endforeach
            </div>
        </div>
    </div>

    @if (session('success'))
        <div style="color: green; margin-top: 10px;">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <br>
    <form action="{{ route('comments.store', $post) }}" method="POST">
        @csrf
        <label for="comment">Add a comment:</label>
        <textarea name="comment" id="comment" rows="3" required></textarea>
        @error('comment') <span style="color:crimson">{{ $message }}</span> @enderror
        <br>
        <button type="submit">Submit Comment</button>
    </form>

    <h3>Comments ({{ $post->comments->count() }})</h3>
    @if ($post->comments->isEmpty())
        <p>No comments yet.</p>
    @else
        @foreach ($post->comments->sortByDesc('created_at') as $comment)
            <div class="comment @if ($comment->user->id === auth()->id()) highlighted @endif">
                <p><strong>{{ $comment->user->name }}:</strong> {{ $comment->content }}</p>
                <span style="font-size: 0.8em;">Posted: {{ $comment->created_at->diffForHumans() }}</span>
            </div>
        @endforeach
    @endif
@endsection