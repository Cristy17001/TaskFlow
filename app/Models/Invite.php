<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    use HasFactory;

    // Set the table name if it differs from the default naming convention
    protected $table = 'invite';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'state',
        'date',
        'project_id',
        'id_user_sender',
        'id_user_receiver',
    ];

    // Assuming 'invite_id' is the primary key for the 'Invite' table
    protected $primaryKey = 'invite_id';

    // Disable timestamps for this model
    public $timestamps = false;

    // Relationships
    public function project()
    {
        // Assuming 'project_id' is the foreign key in the 'invite' table
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function sender()
    {
        // Assuming 'id_user_sender' is the foreign key in the 'invite' table
        return $this->belongsTo(User::class, 'id_user_sender', 'user_id');
    }

    public function receiver()
    {
        // Assuming 'id_user_receiver' is the foreign key in the 'invite' table
        return $this->belongsTo(User::class, 'id_user_receiver', 'user_id');
    }
    
    // Add any additional methods or customizations you may need
    public function sendInvite($id_sender, $id_receiver, $project_id) {
        Invite::create([
            'state' => 'Pending',
            'date' => now(),
            'project_id' => $project_id,
            'id_user_sender' => $id_sender,
            'id_user_receiver' => $id_receiver,
        ]);
    }



}
