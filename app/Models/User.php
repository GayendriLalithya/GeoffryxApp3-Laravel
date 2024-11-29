<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;

    // Specify the primary key
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'contact_no', 
        'address',
        'password',
        'user_type',
        'deleted',  // Added deleted to the fillable attributes
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token'
    ];

    /**
     * Get the profile picture associated with the user.
     */
    public function profilePicture()
    {
        return $this->hasOne(ProfilePicture::class, 'user_id');
    }

    /**
     * Local scope to get non-deleted users.
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('deleted', false);
    }

    /**
     * Scope to get deleted users.
     */
    public function scopeOnlyDeleted($query)
    {
        return $query->where('deleted', true);
    }
}
