<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // Set the table name if it differs from the default naming convention
    protected $table = 'comment';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'comment',
        'date',
        'task_id',
        'user_id',
    ];

    // Assuming 'comment_id' is the primary key for the 'Comment' table
    protected $primaryKey = 'comment_id';

    // Disable timestamps for this model
    public $timestamps = false;
    
    // Relationships
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }    
}
