@php
    $highlightClass = $highlightOwn && $post->user->id === auth()->id() ? 'highlighted' : '';

    $createdAt = ($post->created_at->diffInDays(now()) < 1) ?
        $post->created_at->diffForHumans() : 
        $post->created_at->format('j F Y');
@endphp

<div class="post-card {{ $highlightClass }}">
    <div class="post-date">{{ $createdAt }}</div>
    
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
        <div class="post-comments">
            <img src="{{ asset('images/comment_icon.svg') }}" alt=""> 
            {{ $post->comments_count }}
        </div>
    </div>
</div>
