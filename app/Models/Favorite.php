<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $table = 'favorite';

    protected $fillable = [
        'user_id',
        'project_id',
    ];
    public $timestamps = false;

    protected $primaryKey = ['user_id', 'project_id'];

    public $incrementing = false;
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function getUsersFavoriteProjects($userId)
    {
        $favorites = Favorite::where('user_id', $userId)
            ->select('project_id')
            ->get();
        return $favorites;
    }
}
