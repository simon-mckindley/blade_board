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
        <form method="POST" action="{{ route('comments.destroy', $comment->id) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="comment-delete" title="Delete Comment">
                <img height="20" src="{{ asset('images/cancel_icon.svg') }}" alt="Delete">
            </button>
        </form>
    @endif
    <div class="comment-content">{{ $comment->content }}</div>
</div>