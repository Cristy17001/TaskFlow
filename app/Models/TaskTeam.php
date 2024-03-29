<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\User;

class TaskTeam extends Model
{
    use HasFactory;

    protected $table = 'task_team';

    public $timestamps = false;

    protected $fillable = [
        'user_id', 
        'task_id'
    ];

    protected $primaryKey = ['user_id', 'task_id'];

    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'task_id');
    }


    public function addUserToTask($userId, $taskId)
    {
        $userId = (int)$userId;
        $taskTeam = new TaskTeam();
        $taskTeam->user_id = $userId;
        $taskTeam->task_id = $taskId;
        $taskTeam->save();
    }
}
