<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Comment;
use App\Models\TaskTeam;
use App\Models\User;
use App\Models\Label;
use Illuminate\Support\Facades\Auth;



class TaskController extends Controller
{
    // Display a listing of the tasks
    public function index()
    {
        $tasks = Task::all();
        return view('pages.tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'priority' => 'required',
            'due_date' => 'required',
            'project_id' => 'required',
            'user_assigned_id' => 'required',
            'actual_labels' => 'array',
        ]);
    
        // Remove user_assigned_id from validatedData
        $user_assigned_id = $validatedData['user_assigned_id'];
        unset($validatedData['user_assigned_id']);
    
        $validatedData['status'] = 'Open';
        $validatedData['user_creator_id'] = auth()->id();
        $validatedData['created_at'] = now();
    
        $task = Task::create($validatedData);
    
        $taskTeam = new TaskTeam;
        $taskTeam->addUserToTask($user_assigned_id, $task->task_id);
    
        $labelModel = new Label;
    
        // Check if actual_labels array is not empty before processing
        if (!empty($validatedData['actual_labels'])) {
            // Filter out null values from the array
            $labelsArray = array_filter($validatedData['actual_labels'], function ($label) {
                return $label !== null;
            });
            // If the filtered array is not empty, store the labels
            if (!empty($labelsArray)) {
                $labelsArray = explode(",", $labelsArray[0]);    
                $labelModel->storeLabels($labelsArray, $task->task_id);
            }
        }
    
        return $task;
    }
    


    // Display the specified task
    public function show($id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.show', compact('task'));
    }

    // Show the form for editing the specified task
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

    // Update the specified task in the database
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'project_id' => 'required',
            'user_creator_id' => 'required',
            'user_assigned_id' => 'required',
        ]);

        $task = Task::findOrFail($id);
        $task->update($validatedData);

        return redirect()->route('pages.tasks.index')->with('success', 'Task updated successfully!');
    }

    // Remove the specified task from the database
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect()->route('home')->with('success', 'Task deleted successfully!');
    }
    
    public function viewTasks($userId)
    {
        $user = User::findOrFail($userId);
        $tasks = Task::where('user_assigned_id', $userId)->get();

        return view('pages.tasks.index', compact('tasks', 'user'));
    }

    public function getTasksProject($projectId, $searchTerm = null)
    {
        $tasks = Task::where('project_id', $projectId);
    
        // Apply search filter if $searchTerm is provided
        if ($searchTerm) {
            $searchTerms = explode(' ', $searchTerm);
    
            $tasks->where(function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->orWhereRaw("ARRAY_TO_STRING(ARRAY(SELECT UNNEST(string_to_array(name, ' '))), ' ') ILIKE ?", ['%' . $term . '%'])
                        ->orWhere('status', 'LIKE', '%' . $term . '%')
                        ->orWhere('priority', 'LIKE', '%' . $term . '%');
                }
            });
        }
    
        $tasks = $tasks->get();
    
        foreach ($tasks as $task) {    
            $task->labels = Label::where('task_id', $task->task_id)->get();
            $task->assignees = TaskTeam::where('task_id', $task->task_id)->with('user')->get()->pluck('user');  
        }
    
        //dd($task->assignees);
        return $tasks;
    }
    
}
