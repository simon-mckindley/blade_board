<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    public string $type;
    public string $message;

    public function __construct(string $type, string $message)
    {
        $this->type = $type; // 'success', 'error', 'warning', 'info'
        $this->message = $message;
    }

    public function render()
    {
        return view('components.alert');
    }
}
