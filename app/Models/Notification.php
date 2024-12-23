<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $primaryKey = 'notification_id';  // Optional: To specify custom primary key if not 'id'

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'status',
    ];

    // Relationship with the User model (one-to-many)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
