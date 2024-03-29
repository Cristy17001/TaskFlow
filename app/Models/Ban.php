<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    use HasFactory;

    // Set the table name if it differs from the default naming convention
    protected $table = 'Ban';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'motive',
        'date',
        'banned_id',
        'admin_id',
    ];

    // Assuming 'ban_id' is the primary key for the 'Ban' table
    protected $primaryKey = 'ban_id';
    
    // Relationships
    public function bannedUser()
    {
        return $this->belongsTo(User::class, 'banned_id', 'user_id');
    }

    public function adminUser()
    {
        return $this->belongsTo(User::class, 'admin_id', 'user_id');
    }
}
