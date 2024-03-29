<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskTeam;
use App\Models\Project;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
        $tasks = $this->getTasksForCurrentUser($searchTerm);

        return view('pages.home', compact('tasks'));
    }

    public function getTasksForCurrentUser($searchTerm = null)
    {
        $query = TaskTeam::where('user_id', auth()->id())
            ->join('task', 'task_team.task_id', '=', 'task.task_id')
            ->select('task.*');

        if ($searchTerm) {
            $searchTerms = explode(' ', $searchTerm);

            $query->where(function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->orWhereRaw("ARRAY_TO_STRING(ARRAY(SELECT UNNEST(string_to_array(name, ' '))), ' ') ILIKE ?", ['%' . $term . '%'])
                        ->orWhereRaw("ARRAY_TO_STRING(ARRAY(SELECT UNNEST(string_to_array(description, ' '))), ' ') ILIKE ?", ['%' . $term . '%']);
                }
            });
        }

        $tasks = $query->paginate(2);

        foreach ($tasks as $task) {
            $task->project_name = Project::find($task->project_id)->name;
            $task->assignees = TaskTeam::where('task_id', $task->task_id)
                ->join('users', 'task_team.user_id', '=', 'users.user_id')
                ->select('users.user_id', 'users.name', 'users.username', 'users.email')
                ->get();
        }

        return $tasks;
    }
}
