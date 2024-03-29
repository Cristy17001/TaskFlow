<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    use HasFactory;

    // Set the table name if it differs from the default naming convention
    protected $table = 'FAQ';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'question',
        'answer',
    ];

    // Assuming 'faq_id' is the primary key for the 'FAQ' table
    protected $primaryKey = 'faq_id';
}
