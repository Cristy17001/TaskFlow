<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Label;
use App\Models\Project;


class Task extends Model
{
    use HasFactory;

    // Set the table name if it differs from the default naming convention
    protected $table = 'task'; 
    public $timestamps = false;

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'priority',
        'description',
        'status',
        'name',
        'due_date',
        'created_at',
        'project_id',
        'user_creator_id',
        'user_assigned_id',
    ];

    // Assuming 'task_id' is the primary key for the 'Task' table
    protected $primaryKey = 'task_id';
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_creator_id', 'user_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'user_assigned_id', 'user_id');
    }

    public function labels()
    {
        return $this->hasMany(Label::class, 'task_id', 'id');
    }
    
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'task_id', 'id');
    }
}
