@extends('layouts.default')

@section('title', 'Post - ' . ucfirst($post->title))

@section('maincontent')   
    <a href="{{ route('posts.display') }}">All Posts</a>
    <br>
    <a href="{{ route('user.posts') }}">My Posts</a>
    <br>

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