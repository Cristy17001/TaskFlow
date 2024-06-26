<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Navigation extends Component
{
    public $activePage;

    /**
     * Create a new component instance.
     */
    public function __construct($activePage = null)
    {
        $this->activePage = $activePage;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        //get auth user
        $user = auth()->user();
        return view('components.navigation', compact('user'));
    }
}
