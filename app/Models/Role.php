<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Set the table name if it differs from the default naming convention
    protected $table = 'role';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'role',
        'user_id',
        'project_id',
    ];

    // Disable default timestamps for this model
    public $timestamps = false;

    // Define composite primary key
    protected $primaryKey = ['user_id', 'project_id'];

    // Disable auto-incrementing primary key assumption
    public $incrementing = false;
    
    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function getProjectsForUser($user_id)
    {
        $projects = Role::where('user_id', $user_id)->select('project_id')->get();
        return $projects;
    }
    
    public function getUsersForProject($project_id){
        $users = Role::where('project_id', $project_id)->select('user_id')->get();
        return $users;
    }

    public function getArchivedProjectsForUser($user_id) {
        $user = User::findOrFail($user_id);
        $projectIds = Role::where('user_id', $user_id)->pluck('project_id')->toArray();
        $archivedProjects = Project::whereIn('project_id', $projectIds)
                            ->where('archived', true)
                            ->get();

        return $archivedProjects;
    }

    public function getRole($user_id, $project_id) {
        $userId = Auth::user()->user_id;

        $role = Role::where('user_id', $userId)
            ->where('project_id', $projectId)
            ->first();

        return $role->role;
    }
}
