<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    // Set the table name if it differs from the default naming convention
    protected $table = 'labels';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'name',
        'task_id',
    ];

    // Assuming 'id' is the primary key for the 'Label' table
    protected $primaryKey = 'label_id';

    // Disable default timestamps for this model
    public $timestamps = false;
    
    // Relationships
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    public function getAllLabels(){
        //make sure every label is unique
        $labels = Label::all();
        $labels = $labels->unique('name');
        return $labels;
    }
    public function storeLabels($labels, $taskId){
        if (is_array($labels)) {
            foreach ($labels as $label) {
                $newLabel = new Label();
                $newLabel->name = $label;
                $newLabel->task_id = $taskId;
                $newLabel->save();
            }
        } else {
            $newLabel = new Label();
            $newLabel->name = $labels;
            $newLabel->task_id = $taskId;
            $newLabel->save();
        }
    }
}
