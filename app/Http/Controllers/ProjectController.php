<?php

namespace App\Http\Controllers;

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Role;
use App\Models\Task;
use App\Models\Label;
use App\Models\Comment;
use App\Models\TaskTeam;
use App\Models\Invite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ProjectController extends Controller
{
    private function getUserProjectRole($userId, $projectId) {
        $role = Role::where('user_id', $userId)
        ->where('project_id', $projectId)
        ->get();
        return $role;
    }

    public function show($projectId, Request $request)
    {
        $project = Project::findOrFail($projectId);
        $role = $this->getUserProjectRole(Auth::id(), $projectId);
        
        $team = Role::where('project_id', $projectId)
            ->join('users', 'role.user_id', '=', 'users.user_id')
            ->select('users.*', 'role.role')
            ->get();

        $taskController = app(TaskController::class);

        $searchTerm = $request->input('search');
        $tasks = $taskController->getTasksProject($projectId, $searchTerm);

        $isFavorite = Favorite::where(['user_id' => Auth::id(), 'project_id' => $projectId])->exists();

        $tasks->each(function ($task) {
            $task->comments = Comment::where('task_id', $task->task_id)
                ->get()
                ->map(function ($comment) {
                    $user = $comment->user;
                    return $comment;
                });
        });

        $teamMembers = Role::with('user')
            ->where('project_id', $projectId)
            ->get()
            ->map(function ($teamMember) {
                return ['user' => $teamMember->user, 'role' => $teamMember->role];
            });

        $labels = (new Label)->getAllLabels();

        return view('pages.opened-project-page', compact('project', 'role', 'team', 'tasks', 'labels', 'isFavorite', 'teamMembers'));
    }


    public function showUserProfile($projectId, $userId) {
        $isUserInProject = Role::where('project_id', $projectId)
                                ->where('user_id', $userId)
                                ->exists();
                        
        if (!$isUserInProject) {
            return redirect()->route('home')->with('error', 'User is not in the project.');
        }
        
        $projects = Role::where('user_id', $userId)
                        ->with('project') 
                        ->get()
                        ->pluck('project.name'); 

        $user = User::find($userId);
        
        return view('pages.team-member-page', compact('user', 'projects'));
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to view projects.');
        }
        $projectModel = new Project;
        $searchTerm = $request->input('search'); 
        $projects = $projectModel->getFullProjectForCurrentUser($searchTerm);

        return view('pages.projects-page', compact(['projects']));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);
        $validatedData['start_date'] = today();
        $validatedData['status'] = true;
        $validatedData['archived'] = false;
        $validatedData['creator_id'] = Auth::user()->user_id;
        Project::create($validatedData);

        return redirect()->route('list_projects')->with('success', 'Project created successfully!');
    }

    public function leave(Request $request, $projectId){
        $project = Project::findOrFail($projectId);
        $user = Auth::user();

        if ($project->members()->where('user_id', $user->id)->exists()) {
            // Detach the user from the project
            $project->members()->detach($user->id);

            return redirect()->route('projects.index')
                ->with('success', 'You have successfully left the project.');
        } else {
            return redirect()->route('projects.index')
                ->with('error', 'You are not a member of this project.');
        }
    }

    public function leaveProject($projectId) {
        $userId = Auth::user()->user_id;
        DB::transaction(function () use ($projectId, $userId) {
            Role::where('project_id', $projectId)
                ->where('user_id', $userId)
                ->delete();

            // Get tasks for the specified project and user
            $tasks = Task::where('project_id', $projectId)->get();
            
            // Iterate over tasks
            foreach ($tasks as $task) {
                // Remove occurrences in TaskTeam table
                TaskTeam::where('user_id', $userId)
                    ->where('task_id', $task->task_id)
                    ->delete();
            }
        });
        //This redirect is not working, but the js is redirecting to the correct page
        return redirect()->route('list_projects')->with('success', 'You have successfully left the project.');
    }

    public function sendInvite(Request $request) {
        $request->validate([
            'username' => 'required|string|max:255|exists:users,username',
            'project_id' => 'required',
        ]);
    
        // Get the username from the request
        $username = $request->input('username');
        $project_id = $request->input('project_id');

        $user = User::where("username", $username)->first();

        // Send a invite in case the user exists
        $invite = new Invite;
        $invite->sendInvite(Auth::id(), $user->user_id, $project_id);
        
        // Return a response, you can customize this based on your requirements
        return redirect()->back()->with('success', 'Invite sent successfully.');
    }
    
    public function deleteMember(Request $request) {
        $request->validate([
            'user_id' => 'required',
            'project_id' => 'required',
        ]);
        

        // Verify permissions
        $userId = Auth::user()->user_id;

        $role = Role::where('user_id', $userId)
            ->where('project_id', $request['project_id'])
            ->first();

        if (!$role || $role->role !== "Project Coordinator") {
            return with('error', 'You don\'t have permissions to delete a member from project.');
        }

        $user_id = $request->input('user_id');
        $project_id = $request->input('project_id');
        
        // Remove from the ROLE table the user with that user_id and project_id
        Role::where('user_id', $user_id)
            ->where('project_id', $project_id)
            ->delete();
        
        $tasks = Task::where('project_id', $project_id)->get();
    
        foreach ($tasks as $task) {
            TaskTeam::where('user_id', $user_id)
                    ->where('task_id', $task->task_id)
                    ->delete();
        }

    }

    public function promoteMember(Request $request) {
        $request->validate([
            'user_id' => 'required',
            'project_id' => 'required',
        ]);
    
        // Verify permissions
        $userId = Auth::user()->user_id;
    
        $role = Role::where('user_id', $userId)
            ->where('project_id', $request['project_id'])
            ->first();
    
        if (!$role || $role->role !== "Project Coordinator") {
            return with('error', 'You don\'t have permissions to promote the member.');
        }
    
        $user_id = $request->input('user_id');
        $project_id = $request->input('project_id');
    
        // Update the role to 'Project Coordinator'
        Role::where('user_id', $user_id)
            ->where('project_id', $project_id)
            ->update(['role' => 'Project Coordinator']);
    
        }
    
    public function archiveProject($projectId) {
        // Verify user permissions
        $userId = Auth::user()->user_id;

        $role = Role::where('user_id', $userId)
            ->where('project_id', $projectId)
            ->first();

        if (!$role || $role->role !== "Project Coordinator") {
            return redirect()->route('list_projects')->with('error', 'You don\'t have permissions to edit the project details.');
        }

        // Find the project by its ID
        $project = Project::find($projectId);
        // Check if the project is found
        if ($project) {
            // Update the 'archived' column to true
            $project->update(['archived' => true]);

            return redirect()->route('list_projects')->with('success', 'Project archived successfully.');
        } else {
            // Handle the case where the project with the given ID is not found
            return redirect()->route('list_projects')->with('error', 'Project not found.');
        }

    }

    public function unarchiveProject($projectId) {
        // Verify user permissions
        $userId = Auth::user()->user_id;

        $role = Role::where('user_id', $userId)
            ->where('project_id', $projectId)
            ->first();

        if (!$role || $role->role !== "Project Coordinator") {
            return redirect()->route('list_projects')->with('error', 'You don\'t have permissions to unarchive the project.');
        }

        // Find the project by its ID
        $project = Project::find($projectId);

        // Check if the project is found
        if ($project) {
            // Update the 'archived' column to false
            $project->update(['archived' => false]);

            return redirect()->route('list_projects')->with('success', 'Project unarchived successfully.');
        } else {
            // Handle the case where the project with the given ID is not found
            return redirect()->route('list_projects')->with('error', 'Project not found.');
        }
    }

    public function editProject($projectId, Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);
        
        // Verify user permissions
        $userId = Auth::user()->user_id;
        
        $role = Role::where('user_id', $userId)
            ->where('project_id', $projectId)
            ->first();

        if (!$role || $role->role !== "Project Coordinator") {
            return redirect()->route('list_projects')->with('error', 'You don\'t have permissions to edit the project details.');
        }

        $project = Project::find($projectId);

        if ($project) {
            $project->update([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
            ]);
    
            // Redirect to the project list page with a success message
            return redirect()->route('project_show', ['projectId' => $projectId])->with('success', 'Project details updated successfully.');
        } else {
            // Handle the case where the project with the given ID is not found
            return redirect()->back()->with('error', 'Project not found.');
        }
    }

    public function filterProjects($filter) {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to view projects.');
        }
        $projectModel = new Project;
        $projects = $projectModel->getFilteredProjectsForCurrentUser($filter);
        return view('pages.projects-page', compact('projects'));
    }

    public function createComment($project_id, Request $request) {
        $validatedData = $request->validate([
            'comment' => 'required|max:500',
            'task_id' => 'required',
        ]);
        
        $commentData = [
            'comment' => $validatedData['comment'],
            'date' => now(),
            'task_id' => $validatedData['task_id'],
            'user_id' => Auth::user()->user_id,
        ];

        Comment::create($commentData);

        $user = User::find($commentData['user_id']);
        
        $comment = [
            'comment' =>$commentData['comment'],
            'username' => $user->username,
            'name' => $user->name,
            'date' => $commentData['date'],
        ];

        // Return the comment to add it:
        $html = View::make('partials.comment', ['comment' => $comment, 'user' => $user])->render();
        return response()->json(['success' => true, 'html' => $html]);
    }

    public function saveTaskChanges($project_id, Request $request) {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'dueDate' => 'required|date',
            'priority' => 'required|string',
            'status' => 'required|string',
            'team' => 'array',
            'labels' => 'array',
            'taskId' => 'required',
        ]);
    
        // Find the task by ID
        $task = Task::findOrFail($validatedData['taskId']);
    
        // Update task title, description, dueDate, priority, and status
        $task->update([
            'name' => $validatedData['title'],
            'description' => $validatedData['description'],
            'due_date' => $validatedData['dueDate'],
            'priority' => $validatedData['priority'],
            'status' => $validatedData['status'],
        ]);
    
        // Find the task_team_before by TASK ID
        $team_before = TaskTeam::where('task_id', $validatedData['taskId'])->get();
        
        // Extract user IDs from the team_before and current team arrays
        $teamBeforeUserIds = $team_before->pluck('user_id')->toArray();
        $currentTeamUserIds = $validatedData['team'];
    
        // Find user IDs that were removed from the team
        $usersRemoved = array_diff($teamBeforeUserIds, $currentTeamUserIds);
    
        // Find user IDs that need to be added to the team
        $usersAdded = array_diff($currentTeamUserIds, $teamBeforeUserIds);
    
        // Handle the removal of users from the team
        TaskTeam::whereIn('user_id', $usersRemoved)
            ->where('task_id', $validatedData['taskId'])
            ->delete();
    
        // Handle the addition of users to the team
        foreach ($usersAdded as $userId) {
            TaskTeam::create([
                'task_id' => $validatedData['taskId'],
                'user_id' => $userId,
            ]);
        }
    
        // Find the task_labels_before by TASK ID
        $labels_before = Label::where('task_id', $validatedData['taskId'])->get();

        // Extract label names from the labels_before and current labels arrays
        $labelsBeforeNames = $labels_before->pluck('name')->toArray();
        $currentLabelsNames = $validatedData['labels'];

        // Find label names that were removed
        $labelsRemoved = array_diff($labelsBeforeNames, $currentLabelsNames);

        // Find label names that need to be added
        $labelsAdded = array_diff($currentLabelsNames, $labelsBeforeNames);

        // Handle the removal of labels
        Label::whereIn('name', $labelsRemoved)
            ->where('task_id', $validatedData['taskId'])
            ->delete();

        // Handle the addition of labels
        foreach ($labelsAdded as $labelName) {
            Label::firstOrCreate([
                'task_id' => $validatedData['taskId'],
                'name' => $labelName,
            ]);
        }

        return response()->json(['success' => true, 'test' => $request]);
    }


    public function markFavourite($project_id, Request $request)
    {
        $validatedData = $request->validate([
            'isFavorite' => 'required',
        ]);
    
        // Get the current user ID (assuming you are using the default Laravel authentication)
        $user_id = auth()->user()->id;
    
        if ($validatedData['isFavorite']) {
            // If isFavorite is true, add to favorites
            Favorite::firstOrNew([
                'user_id' => Auth::id(),
                'project_id' => $project_id,
            ])->save();
        } else {
            // If isFavorite is false, remove from favorites if it exists
            Favorite::where([
                'user_id' => Auth::id(),
                'project_id' => $project_id,
            ])->delete();
        }
    
        return response()->json(['success' => true]);
    }
    
}