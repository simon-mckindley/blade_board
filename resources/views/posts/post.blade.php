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
            {{-- <div class="comment-form-container">
                <form class="post-form comment-form" action="{{ route('comments.store', $post) }}" method="POST">
                    @csrf
                    <div class="input-cont">
                        @error('comment') <span class="input-error">{{ $message }}</span> @enderror
                        <textarea name="comment" id="comment" rows="3" required></textarea>
                        <label for="comment">Add a comment</label>
                    </div>
                    
                    <button class="btn" type="submit">Submit Comment</button>
                </form>
            </div> --}}
            
            <div class="comments-section">
                <div class="comments-head">
                    <h3>Comments ({{ $post->comments->count() }})</h3>
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
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const commentForm = document.querySelector('.comment-form-container');
            
            document.querySelector('.comment-action').addEventListener('click', function() {
                commentForm.classList.toggle('open');
            });
        });
    </script>
@endsection