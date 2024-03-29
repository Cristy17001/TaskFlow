<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\User;
use App\Models\Favorite;

class Project extends Model
{
    use HasFactory;

    // Set the table name if it differs from the default naming convention
    protected $table = 'project';

    public $timestamps = false;

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'status',
        'archived',
        'creator_id',
    ];
    
    protected $primaryKey = 'project_id';

    public function getProjectsForCurrentUser($filter)
    {
        $user = Auth::user();
        $user_model = new User();
        $user_id = Auth::id(); // Use Auth::id() to get the authenticated user's ID
    
        // Get projects where the user is a member
        $roleProjects = Role::where('user_id', $user_id)
            ->join('project', 'role.project_id', '=', 'project.project_id')
            ->select('project.*', 'role.role') // Select the columns you need from the project table
            ->get(); // Execute the query to retrieve results
    
        $projects = collect(); // Create a collection to store filtered projects
        // Combine with favorites if the filter is 'Favorite'
        if ($filter == 'Favorite') {
            foreach ($roleProjects as $project) {
                $isFavorite = Favorite::where(['user_id' => $user_id, 'project_id' => $project->project_id])->exists();
                if ($isFavorite) {
                    $projects->push($project); // Add the project to the collection
                }
            }
        } else if ($filter == 'Archived'){
            foreach ($roleProjects as $project) {
                if ($project->archived) {
                    $projects->push($project);
                }
            }
        } else if ($filter == 'Member'){
            foreach ($roleProjects as $project) {
                if ($project->role == 'Project Member') {
                    $projects->push($project);
                }
            }
        } else if ($filter == 'Coordinator'){
            foreach ($roleProjects as $project) {
                if ($project->role == 'Project Coordinator') {
                    $projects->push($project);
                }
            }
        }
    
        // Enhance each project in the collection
        foreach ($projects as $project) {
            $isFavorite = Favorite::where(['user_id' => $user_id, 'project_id' => $project->project_id])->exists();
            if($isFavorite) {
                $project->isFavorite = true;
            }
            $project->creator_username = $user_model->getUsername($project->creator_id);
        }
    
        return $projects;
    }
    

    public function getFullProjectForCurrentUser($searchTerm = null) {
        $user = Auth::user();
        $role = new Role();
        $user_model = new User();
        $projectList = $role->getProjectsForUser($user->user_id);
        $projects = [];
    
        foreach ($projectList as $project) {
            $projectDetails = Project::where('project_id', $project->project_id);
    
            // Apply search filter if $searchTerm is provided
            if ($searchTerm) {
                $searchTerms = explode(' ', $searchTerm);
    
                $projectDetails->where(function ($query) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $query->orWhereRaw("ARRAY_TO_STRING(ARRAY(SELECT UNNEST(string_to_array(name, ' '))), ' ') ILIKE ?", ['%' . $term . '%'])
                            ->orWhereRaw("ARRAY_TO_STRING(ARRAY(SELECT UNNEST(string_to_array(description, ' '))), ' ') ILIKE ?", ['%' . $term . '%']);
                    }
                });
            }
    
            $projectDetails = $projectDetails->first();
    
            if ($projectDetails) {
                // Set the project ID
                $projectDetails->id = $project->project_id;
    
                // Add the creator's username as a property
                $projectDetails->creator_username = $user_model->getUsername($projectDetails->creator_id);
    
                // Check if the project is a favorite
                $isFavorite = Favorite::where(['user_id' => Auth::id(), 'project_id' => $project->project_id])->exists();
                $projectDetails->isFavorite = $isFavorite;
    
                // Add the project object to the projects array
                $projects[] = $projectDetails;
            }
        }
    
        return $projects;
    }
    
    
    
    
    public function canBeAssignedToProject($project_id){
        $role = New Role();
        $assignableUsers = $role->getUsersForProject($project_id);
        return $assignableUsers;
    }

    public function getFilteredProjectsForCurrentUser($filter){
        $projectList = Project::getProjectsForCurrentUser($filter);
        return $projectList;
    }
}
