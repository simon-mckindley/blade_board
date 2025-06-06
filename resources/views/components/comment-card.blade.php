@php
    $highlightClass = $highlightOwn && $comment->user->id === auth()->id() ? 
        'highlighted' : '';
@endphp

<div class="comment-card {{ $highlightClass }}">
    <div class="comment-meta">
        <div>{{ $comment->user->name }}</div>
        <div>{{ display_time($comment->created_at) }}</div>
    </div>
    <div class="comment-content">{{ $comment->content }}</div>
</div>