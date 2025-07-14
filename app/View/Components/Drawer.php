<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class Drawer extends Component
{
    public Collection $tags;
    public LengthAwarePaginator $posts;
    public bool $needsUser;
    public string $action;

    public function __construct(
        Collection $tags, 
        LengthAwarePaginator $posts, 
        bool $needsUser = true, 
        string $action = 'posts.display')
    {
        $this->tags = $tags;
        $this->posts = $posts;
        $this->needsUser = $needsUser;
        $this->action = $action;
    }

    public function render()
    {
        return view('components.drawer');
    }
}
