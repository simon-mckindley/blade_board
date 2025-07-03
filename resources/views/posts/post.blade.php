@php
    if (auth()->check()) {
        $likeClass = $post->likedByUsers->contains(auth()->id()) ? 
        'unlike' : 'like';
    }

    $commentActionVisibility = !auth()->check() || auth()->user()->isAdmin() ? 
        'hidden' : 'visible';
@endphp


@extends('layouts.default')

@section('title', 'Post -> ' . ucfirst($post->title))

 @if (!auth()->check() || auth()->user()->isAdmin())
    @section('add-link')
        <a class="link" href="{{ route('posts.display') }}">All Posts</a>
    @endsection
@endif

@section('maincontent')  
    @auth
        @if (!auth()->user()->isAdmin())
            <div class="post-navigation">
                <a class="link" href="{{ route('posts.display') }}">All Posts</a>
                <a class="link" href="{{ route('user.posts') }}">My Posts</a>
                <a class="link" href="{{ route('posts.create') }}">Create a Post</a>
            </div>
        @endif
    @endauth

    <div class="post-page-grid">

        <div class="post">
            <div class="post-actions">
                @auth
                    @if ($post->user->id === auth()->id() || auth()->user()->isAdmin())
                        {{-- Display if is users own post --}}
                        @if (!auth()->user()->isAdmin())
                        <a class="action" href="{{ route('posts.edit', $post) }}" title="Edit Post">
                            <img height="24" src="{{ asset('images/edit_document_icon.svg') }}" alt="Edit Post">
                        </a>
                        @endif
                        {{-- Display if admin or users own post --}}
                        <button type="button" class="action delete" onclick="document.getElementById('delete-post-dialog').showModal()" title="Delete Post">
                            <img height="24" src="{{ asset('images/delete_icon.svg') }}" alt="Delete Post">
                        </button>
                    @else
                        <form method="POST" action="{{ route('posts.like', $post->id) }}">
                            @csrf
                            <button type="submit" class="action {{ $likeClass }}" title="{{ ucfirst($likeClass) }} this Post">
                                <img height="24" src="{{ asset('images/mood_icon.svg') }}" alt="Like Post">
                            </button>
                        </form>
                        <div>
                            &lpar;{{ $post->likes()->count() }}&rpar;
                        </div>
                    @endif
                @endauth
                @guest
                    <img height="24" src="{{ asset('images/mood_icon.svg') }}" alt="Likes">
                    <div>
                        &lpar;{{ $post->likes()->count() }}&rpar;
                    </div>
                @endguest
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

                <div class="post-views" title="Views">
                    <img src="{{ asset('images/view_icon.svg') }}" alt=""> 
                    &lpar;{{ $post->viewers()->count() }}&rpar;
                </div>
            </div>
        </div>
                   
        <div class="comments-section">
            <div class="comments-head">
                <h3>Comments &lpar;{{ $post->comments->count() }}&rpar;</h3>
                <button type="button" class="comment-action" 
                    style="visibility: {{ $commentActionVisibility }}" title="Add Comment">
                    <img height="24" src="{{ asset('images/comment_icon.svg') }}" alt="Add Comment">
                </button>
            </div>

            <div id="container" class="container comment-form-container">
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
                <div class="comments-cont">
                    @foreach ($post->comments->sortByDesc('created_at') as $comment)
                    <x-comment-card :comment="$comment" :highlightOwn="true" />
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    <dialog id="delete-post-dialog" class="delete-confirm-dialog">
        <h3>Are you sure you want to delete this post?</h3>
        <p>This action cannot be undone!</p>
        <form method="POST" action="{{ route('posts.destroy', $post->id) }}">
            @csrf
            @method('DELETE')
            <div class="dialog-actions">
                <button type="button" class="btn" onclick="this.closest('dialog').close()">No, don't</button>
                <button type="submit" class="btn delete-btn">Definitely</button>
            </div>
        </form>
    </dialog>

    @if (!$post->comments->isEmpty())
        <dialog id="delete-comment-dialog" class="delete-confirm-dialog">
            <h3>Are you sure you want to delete this comment?</h3>
            <p>This action cannot be undone!</p>
            <form id="delete-comment-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="dialog-actions">
                    <button type="button" class="btn" onclick="this.closest('dialog').close()">Oops, No</button>
                    <button type="submit" class="btn delete-btn">For Sure</button>
                </div>
            </form>
        </dialog>
    @endif
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const commentForm = document.getElementById('container');
            
            document.querySelector('.comment-action').addEventListener('click', function() {
                commentForm.classList.toggle('open');
            });
            
            document.querySelectorAll('.comment-delete').forEach(button => {
                button.addEventListener('click', () => {
                    const commentId = button.getAttribute('data-comment-id');
                    const form = document.getElementById('delete-comment-form');
                
                    form.action = `comments/${commentId}`;
                    document.getElementById('delete-comment-dialog').showModal();
                });
            });

            fetch('{{ route('posts.logView', $post->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            }
            ).then(res => res.json())
             .then(data => console.log(data))
             .catch(err => console.error(err));
        });
    </script>
@endsection