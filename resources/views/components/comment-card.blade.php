@php
    $highlightClass = $highlightOwn && $comment->user->id === auth()->id() ? 
        'highlighted' : '';
@endphp

<div class="comment-card {{ $highlightClass }}">
    <div class="comment-meta">
        <div>{{ $comment->user->name }}</div>
        <div>{{ display_time($comment->created_at) }}</div>
    </div>
    @if (!empty($highlightClass))
        <button type="submit" class="comment-delete" onclick="document.getElementById('delete-comment-dialog').showModal()" title="Delete Comment">
            <img height="20" src="{{ asset('images/cancel_icon.svg') }}" alt="Delete">
        </button>
    @endif
    <div class="comment-content">{{ $comment->content }}</div>
</div>
