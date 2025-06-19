@php
    $likeClass = $post->likedByUsers->contains(auth()->id()) ? 
        'unlike' : 'like';
@endphp


@extends('layouts.default')

@section('title', 'Post -> ' . ucfirst($post->title))

@section('maincontent')  
    <div class="post-navigation">
        <a class="link" href="{{ route('posts.display') }}">All Posts</a>
        <a class="link" href="{{ route('user.posts') }}">My Posts</a>
        <a class="link" href="{{ route('posts.create') }}">Create a Post</a>
    </div>

    <div class="post-page-grid">

        <div class="post">
            <div class="post-actions">
                @if ($post->user->id === auth()->id())
                    <a class="action" href="{{ route('posts.edit', $post) }}" title="Edit Post">
                        <img height="24" src="{{ asset('images/edit_document_icon.svg') }}" alt="Edit Post">
                    </a>
                    <button type="button" class="action delete" onclick="document.getElementById('delete-post-dialog').showModal()" title="Delete Post">
                        <img height="24" src="{{ asset('images/delete_icon.svg') }}" alt="Delete Post">
                    </button>
                @else
                    <form method="POST" action="{{ route('posts.like', $post->id) }}">
                        @csrf
                        <button type="submit" class="action {{ $likeClass }}" title="{{ ucfirst($likeClass) }} this Post">
                            <img height="24" src="{{ asset('images/mood_icon.svg') }}" alt="Liked Post">
                        </button>
                    </form>
                    <div>
                       &lpar;{{ $post->likes()->count() }}&rpar;
                    </div>
                @endif
            </div>
            
            <div class="post-meta">
                <span class="post-date">Created -> {{ display_time($post->created_at) }}</span>
                <span class="post-date">Updated -> {{ display_time($post->updated_at) }}</span>
                <span>{{ ucwords($post->user->name) }}</span>
            </div>
            
            <div class="post-main">
                <div class="post-title">{{ ucwords($post->title) }}</div>
                
                <div class="post-content">{!! ($post->content) !!}</div>
                
                <div class="post-tags">
                    @foreach ($post->tags as $tag)
                    <span>{{ $tag->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="comments-grid">            
            <div class="comments-section">
                <div class="comments-head">
                    <h3>Comments &lpar;{{ $post->comments->count() }}&rpar;</h3>
                    <button type="button" class="comment-action" title="Add Comment">
                        <img height="24" src="{{ asset('images/comment_icon.svg') }}" alt="Add Comment">
                    </button>
                </div>

                <div id="container" class="comment-form-container">
                    <div>
                        <form class="post-form comment-form" action="{{ route('comments.store', $post) }}" method="POST">
                            @csrf
                            <div class="input-cont">
                                @error('comment') <span class="input-error">{{ $message }}</span> @enderror
                                <textarea name="comment" id="comment" rows="3" required></textarea>
                                <label for="comment">Add a comment</label>
                            </div>
                            
                            <button class="btn" type="submit">Submit Comment</button>
                        </form>
                    </div>
                </div>

                @if ($post->comments->isEmpty())
                <p>No comments yet</p>
                @else
                    @foreach ($post->comments->sortByDesc('created_at') as $comment)
                        <x-comment-card :comment="$comment" :highlightOwn="true" />
                    @endforeach
                @endif
            </div>
        </div>

    </div>

    <dialog id="delete-post-dialog" class="delete-confirm-dialog">
        <h3>Are you sure you want to delete this post?</h3>
        <p>This action cannot be undone!</p>
        <form method="POST" action="{{ route('posts.destroy', $post->id) }}">
            @csrf
            @method('DELETE')
            <div class="dialog-actions">
                <button type="button" class="btn" onclick="this.closest('dialog').close()">Cancel</button>
                <button type="submit" class="btn delete-btn">Delete</button>
            </div>
        </form>
    </dialog>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const commentForm = document.getElementById('container');
            
            document.querySelector('.comment-action').addEventListener('click', function() {
                commentForm.classList.toggle('open');
            });
        });
    </script>
@endsection