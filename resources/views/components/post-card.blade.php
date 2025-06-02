@php
    $highlightClass = $highlightOwn && $post->user->id === auth()->id() ? 'highlighted' : '';
@endphp

<div class="post-card {{ $highlightClass }}">
    <div class="post-created">{{ $post->created_at->format('j F Y') }}</div>
    
    <a class="post-title" href="{{ route('posts.show', $post->id) }}">
        {{ $post->title }}
    </a>
    
    <div class="post-tags"> 
        @foreach ($post->tags as $tag)
            <span>{{ $tag->name }}</span>
        @endforeach
    </div>
    
    <div class="post-name">{{ $post->user->name }}</div>
    <div class="post-comments">Comments {{ $post->comments_count }}</div>
</div>
