<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TaskModal extends Component
{
    // Dados para colocar no modal da task
    public $task;
    public $project;
    public $team;


    /**
     * Create a new component instance.
     */
    public function __construct($task, $project, $team)
    {
        $this->task = $task;
        $this->project = $project;
        $this->team = $team;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.task-modal');
    }
}
