@php
    $highlightClass = $highlightOwn && $post->user->id === auth()->id() ? 
        'highlighted' : '';
@endphp

<div class="post-card {{ $highlightClass }}"
    data-user="{{ $post->user->name }}"
    data-title="{{ $post->title }}"
    data-tags="{{ $post->tags->pluck('id')->implode(',') }}"
    data-created="{{ $post->created_at->toDateString() }}"
    >
    <div class="post-date">{{ display_time($post->created_at) }}</div>
    
    <div class="post-main">
        <a class="link post-title" href="{{ route('posts.show', $post->id) }}">
            {{ ucwords($post->title) }}
        </a>
        
        <div class="post-tags"> 
            @foreach ($post->tags as $tag)
                <span>{{ $tag->name }}</span>
            @endforeach
        </div>
        
        <div class="post-name">{{ $post->user->name }}</div>

        <div class="post-stats">
            <div class="stats-inner" title="Comments">
                <img src="{{ asset('images/comment_icon.svg') }}" alt=""> 
                {{ $post->comments_count }}
            </div>
            <div class="stats-inner" title="Likes">
                <img src="{{ asset('images/mood_icon.svg') }}" alt=""> 
                {{ $post->likes_count }}
            </div>
            <div class="stats-inner" title="Views">
                <img src="{{ asset('images/view_icon.svg') }}" alt=""> 
                {{ $post->viewers()->count() }}
            </div>
        </div>
    </div>
</div>
