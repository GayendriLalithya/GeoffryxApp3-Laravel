<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilePicture extends Model
{
    use HasFactory;

    protected $table = 'profile_picture'; // Ensure this matches the actual table name
    protected $primaryKey = 'profile_picture_id';

    protected $fillable = ['profile_pic', 'user_id'];

    /**
     * Relationship to the User model
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
