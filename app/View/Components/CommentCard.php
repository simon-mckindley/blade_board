<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Comment;

class CommentCard extends Component
{
    public Comment $comment;
    public bool $highlightOwn;

    public function __construct(Comment $comment, bool $highlightOwn = false)
    {
        $this->comment = $comment;
        $this->highlightOwn = $highlightOwn;
    }

    public function render()
    {
        return view('components.comment-card');
    }
}
