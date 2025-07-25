@php
    if (auth()->check()) {
        $likeClass = $post->likedByUsers->contains(auth()->id()) ? 
        'liked' : '';
    }

    $commentActionVisibility = !auth()->check() || auth()->user()->isAdmin() ? 
        'hidden' : 'visible';

    $reportClass = (auth()->check() && auth()->user()->isAdmin() && $post->hasOutstandingReport()) 
        ? 'reported' : '';
@endphp


@extends('layouts.default')

@section('title', 'Post -> ' . ucfirst($post->title))

 @if (!auth()->check() || auth()->user()->isAdmin())
    @section('add-link')
        <a class="link" href="{{ route('posts.display') }}">Posts</a>
    @endsection
@endif

@section('maincontent')  
    @auth
        @if (!auth()->user()->isAdmin())
            <div class="post-navigation">
                <a class="link" href="{{ route('posts.display') }}">Posts</a>
                <a class="link" href="{{ route('user.posts') }}">My Posts</a>
                <a class="link" href="{{ route('posts.create') }}">Create a Post</a>
            </div>
        @endif
    @endauth

    <div class="post-page-grid">

        <div class="post {{ $reportClass }}">
            <div class="post-head">
                <div class="post-actions-row">
                    <div class="post-tags">
                        @foreach ($post->tags as $tag)
                        <span>{{ $tag->name }}</span>
                        @endforeach
                    </div>
                    
                    <div class="post-actions">
                        @auth
                            @if ($post->user->id === auth()->id() || auth()->user()->isAdmin())
                                {{-- Display if is users own post --}}
                                @if (!auth()->user()->isAdmin())
                                <a class="action" href="{{ route('posts.edit', $post) }}" title="Edit Post">
                                    <img class="icon" height="24" src="{{ asset('images/edit_document_icon.svg') }}" alt="Edit Post">
                                </a>
                                @endif
                                {{-- Display if admin or users own post --}}
                                <button type="button" class="action delete" onclick="document.getElementById('delete-post-dialog').showModal()" title="Delete Post">
                                    <img class="icon" height="24" src="{{ asset('images/delete_icon.svg') }}" alt="Delete Post">
                                </button>
                            @else
                                <div class="likes-cont">
                                    <div style="position: relative">
                                        <button 
                                            type="button"
                                            class="action {{ $likeClass }} like-btn" 
                                            data-post-id="{{ $post->id }}"
                                        >
                                            <img class="icon" height="24" src="{{ asset('images/mood_icon.svg') }}" alt="Like Post">
                                        </button>
                                        <img class="icon" id="like-conf" height="24" src="{{ asset('images/mood_icon.svg') }}" alt="">
                                    </div>
                                    <div>
                                        &lpar;<span class="likes-count">{{ $post->likes()->count() }}</span>&rpar;
                                    </div>
                                </div>
                            @endif
                        @endauth
                        @guest
                            <div class="likes-cont">
                                <img class="icon" height="24" src="{{ asset('images/mood_icon.svg') }}" alt="Likes">
                                <div>
                                    &lpar;{{ $post->likes()->count() }}&rpar;
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
                
                <div class="post-meta-row">
                    <div class="post-meta">
                        <span class="post-date">Created -> {{ display_time($post->created_at) }}</span>
                        @if ($post->created_at != $post->updated_at)
                        <span class="post-date">Edited -> {{ display_time($post->updated_at) }}</span>
                        @endif
                        <span>{{ ucwords($post->user->name) }}</span>
                    </div>
                    <div class="post-views" title="Views">
                        <div style="position: relative">
                            <img class="icon" height="24" src="{{ asset('images/view_icon.svg') }}" alt=""> 
                            <img class="icon" id="view-conf" height="24" src="{{ asset('images/view_icon.svg') }}" alt="">
                        </div>
                        &lpar;{{ $post->viewers()->count() }}&rpar;
                    </div>
                </div>
                
                <div class="post-title">
                    {{ ucwords($post->title) }}
                </div>
            </div>
                
            <div class="post-content">
                {!! ($post->content) !!}
                @if (auth()->check() && $post->user->id !== auth()->id())
                <button type="button" class="report-btn" 
                    title="Report this post" 
                    data-id="{{ $post->id }}" 
                    data-type="post"
                >
                    <img height="24" src="{{ asset('images/report_icon.svg') }}" alt="">
                </button>
                @endif
            </div>

        </div>
                   
        <div class="comments-section">
            <div class="comments-head">
                <h3>Comments &lpar;{{ $post->comments->count() }}&rpar;</h3>
                <button type="button" class="comment-action" 
                    style="visibility: {{ $commentActionVisibility }}" title="Add Comment">
                    <img class="icon" height="24" src="{{ asset('images/comment_icon.svg') }}" alt="Add Comment">
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
                        
                        <button class="btn submit-btn" type="submit">Submit Comment</button>
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


    <dialog id="report-dialog" class="report-dialog">
        <h3>Report this <span id="report-head-text"></span></h3>
        <p>This report will be submitted to the BLADE_board admin for review.</p>
        <form action="{{ route('reports.store') }}" method="POST" class="report-form auth-form">
            @csrf
            <input type="hidden" name="reportable_type">  {{-- 'post' or 'comment' --}}
            <input type="hidden" name="reportable_id">

            <div class="input-cont">
                <select name="reason" id="reason" required>
                    <option value="">Select reason</option>
                    @foreach (\App\Enums\ReportReason::cases() as $reason)
                    <option value="{{ $reason->value }}">{{ $reason->label() }}</option>
                    @endforeach
                </select>
                <label for="reason">Reason</label>
            </div>

            <div class="input-cont">
                <textarea name="description" id="description" rows="4" placeholder="Describe why you're reporting this content..."></textarea>
                <label for="description">Additional details (optional)</label>
            </div>

            <div class="dialog-actions">
                <button type="button" class="btn" onclick="this.closest('dialog').close()">Forget It</button>
                <button type="submit" class="btn submit-btn">Submit Report</button>
            </div>
        </form>
    </dialog>


    <dialog id="delete-post-dialog" class="delete-confirm-dialog">
        <h3>Are you sure you want to delete this post?</h3>
        <p>This action cannot be undone!</p>
        <form method="POST" action="{{ route('posts.destroy', $post->id) }}">
            @csrf
            @method('DELETE')
            <div class="dialog-actions">
                <button type="button" class="btn" onclick="this.closest('dialog').close()">No, don't</button>
                <button type="submit" class="btn delete-btn submit-btn">Definitely</button>
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
                    <button type="submit" class="btn delete-btn submit-btn">For Sure</button>
                </div>
            </form>
        </dialog>
    @endif
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Open report dialog and assign data
            document.querySelectorAll('.report-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelector('[name="reportable_type"]').value = btn.dataset.type;
                    document.getElementById('report-head-text').innerText = btn.dataset.type;
                    document.querySelector('[name="reportable_id"]').value = btn.dataset.id;

                    document.getElementById('report-dialog').showModal();
                });
            });

            // Open comment form
            const commentForm = document.getElementById('container');
            
            document.querySelector('.comment-action').addEventListener('click', function() {
                commentForm.classList.toggle('open');
            });
            
            // Open comment delete dialog and assign data
            document.querySelectorAll('.comment-delete').forEach(button => {
                button.addEventListener('click', () => {
                    const commentId = button.getAttribute('data-comment-id');
                    const form = document.getElementById('delete-comment-form');
                
                    form.action = `comments/${commentId}`;
                    document.getElementById('delete-comment-dialog').showModal();
                });
            });

            const viewConf = document.getElementById('view-conf');
            viewConf.addEventListener('animationend', () => viewConf.removeAttribute('style'));

            // Log-View Ajax call
            fetch('{{ route('posts.logView', $post->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error('Failed to log view');
                }
                return res.json();
            })
            .then(data => {
                console.log(data);
                viewConf.style.display = 'block';
            })
            .catch(err => console.error('View log failed:', err));

            // Like toggle Ajax call
            const likeButton = document.querySelector('.like-btn');

            if (likeButton) {
                const likeConf = document.getElementById('like-conf');
                likeConf.addEventListener('animationend', () => likeConf.removeAttribute('style'));
                
                likeButton.title = likeButton.classList.contains('liked') ?
                     'Unlike this post' : 'Like this post';

                likeButton.addEventListener('click', async () => {
                    console.log('Liked button clicked');
                    likeButton.querySelector('img').style = 'animation: rotate 1000ms linear infinite;';
                    const likesCount = document.querySelector('.likes-count');
                    let likesNum = parseInt(likesCount.innerText);
                    const postId = likeButton.dataset.postId;
                    
                    try {
                        const response = await fetch(`{{ route('posts.like', $post->id) }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                        });
                        
                        if (!response.ok) throw new Error('Network response was not ok');
                        
                        const result = await response.json();
                        
                        likeButton.classList.toggle('liked');
                        likeButton.blur();
                        likeButton.querySelector('img').removeAttribute('style');

                        if (likeButton.classList.contains('liked')) {
                            likeConf.style.display = 'block';
                            likeButton.title = 'Unlike this post';
                            likesNum++;
                        } else {
                            likeButton.title = 'Like this post';
                            likesNum--;
                        }

                        likesCount.innerText = likesNum;
                        console.log(result);
                    } catch (error) {
                        console.error('Error toggling like:', error);
                        likeButton.querySelector('img').removeAttribute('style');
                    }
                });
            }

        });
    </script>
@endsection