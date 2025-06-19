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

<dialog id="delete-comment-dialog" class="delete-confirm-dialog">
    <h3>Are you sure you want to delete this comment?</h3>
    <p>This action cannot be undone!</p>
    <form method="POST" action="{{ route('comments.destroy', $comment->id) }}">
        @csrf
        @method('DELETE')
        <div class="dialog-actions">
            <button type="button" class="btn" onclick="this.closest('dialog').close()">Cancel</button>
            <button type="submit" class="btn delete-btn">Delete</button>
        </div>
    </form>
</dialog>