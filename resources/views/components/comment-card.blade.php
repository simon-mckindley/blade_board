@php
    $highlightClass = $highlightOwn && 
        (auth()->check() && 
            ($comment->user->id === auth()->id() || auth()->user()->isAdmin())
        ) ? 'highlighted' : '';

    $reportClass = (auth()->check() && auth()->user()->isAdmin() && $comment->hasReport()) ? 
        'reported' : '';
@endphp

<div class="comment-card {{ $highlightClass }} {{ $reportClass }}">
    <div class="comment-meta">
        <div>{{ $comment->user->name }}</div>
        <div>{{ display_time($comment->created_at) }}</div>
    </div>
    @if (!empty($highlightClass))
        <button type="button" class="comment-delete"
            title="Delete Comment"
            data-comment-id="{{ $comment->id }}"
        >
            <img height="20" src="{{ asset('images/cancel_icon.svg') }}" alt="Delete">
        </button>
    @else
        @if (auth()->check())
        <button type="button" class="report-btn"
            title="Report this comment" 
            data-id="{{ $comment->id }}" 
            data-type="comment"
        >
            <img height="20" src="{{ asset('images/report_icon.svg') }}" alt="">
        </button>
        @endif
    @endif
    <div class="comment-content">{{ $comment->content }}</div>
</div>
