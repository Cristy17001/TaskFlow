<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    // Set the table name if it differs from the default naming convention
    protected $table = 'notification';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'type',
        'task_id',
        'invite_id',
        'user_id',
        'project_id',
        'date',
    ];

    // Assuming 'notification_id' is the primary key for the 'Notification' table
    protected $primaryKey = 'notification_id';
    
    // Relationships
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'task_id');
    }

    public function invite()
    {
        return $this->belongsTo(Invite::class, 'invite_id', 'invite_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }
}
