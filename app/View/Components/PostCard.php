<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Post;

class PostCard extends Component
{
    public Post $post;
    public bool $highlightOwn;

    public function __construct(Post $post, bool $highlightOwn = false)
    {
        $this->post = $post;
        $this->highlightOwn = $highlightOwn;
    }

    public function render()
    {
        return view('components.post-card');
    }
}
