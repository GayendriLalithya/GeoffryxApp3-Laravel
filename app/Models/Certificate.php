<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'certificate_id';

    // Specify the table name if it's not the plural form of the model name
    protected $table = 'certificate';

    // Define which fields are mass assignable
    protected $fillable = [
        'user_id',        // Foreign key to User
        'certificate_name', // Certificate Name (e.g., Chartered Engineer)
        'certificate',      // Certificate File Path
    ];

    // Relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');  // Define foreign key & local key
    }
}
