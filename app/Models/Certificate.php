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
        'verify_id',          // Foreign key to Verify
        'certificate_name',    // Certificate Name (e.g., Chartered Engineer)
        'certificate',         // Certificate File Path
    ];

    // Relationship with the Verify model (since verify_id now references verify table)
    public function verify()
    {
        return $this->belongsTo(Verify::class, 'verify_id', 'verify_id');  // Define foreign key & local key
    }

    // If you still need a relationship with the User model, you can define it like this:
    public function user()
    {
        return $this->belongsTo(User::class, 'verify_id', 'user_id');
    }
}
