@php
    $highlightClass = $highlightOwn && 
        (auth()->check() && 
            ($comment->user->id === auth()->id() || auth()->user()->isAdmin())
        ) ? 'highlighted' : '';
@endphp

<div class="comment-card {{ $highlightClass }}">
    <div class="comment-meta">
        <div>{{ $comment->user->name }}</div>
        <div>{{ display_time($comment->created_at) }}</div>
    </div>
    @if (!empty($highlightClass))
        <button
            type="button" class="comment-delete"
            data-comment-id="{{ $comment->id }}"
            title="Delete Comment"
        >
            <img height="20" src="{{ asset('images/cancel_icon.svg') }}" alt="Delete">
        </button>
    @else
        <button class="report-btn" type="button" title="Report comment" data-comment-id="{{ $comment->id }}">
            <img class="icon" id="report-btn" height="24" src="{{ asset('images/report_icon.svg') }}" alt="">
        </button>
    @endif
    <div class="comment-content">{{ $comment->content }}</div>
</div>
