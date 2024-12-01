<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyRequest extends Model
{
    use HasFactory;

    // Important: Specify that this is a view, not a table
    protected $table = 'verify_requests';
    
    // Since it's a view, you typically don't want Laravel to manage timestamps
    public $timestamps = false;

    // Primary key might be different in a view
    protected $primaryKey = 'verify_id';

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
