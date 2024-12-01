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

    /**
     * Define the relationship to the Verify model.
     */
    public function verifies()
    {
        return $this->hasMany(Verify::class, 'user_id');
    }

    /**
     * Define the relationship to the Certificate model.
     * Since the certificates are now related to the verify table, we need to adjust the relationship.
     */
    public function certificates()
    {
        // Instead of user_id, it uses verify_id to get certificates related to the user through the verify table.
        return $this->hasManyThrough(Certificate::class, Verify::class, 'user_id', 'verify_id');
    }

    public function user() { 
        return $this->belongsTo(User::class);
    }

    /**
     * A user can have many verify requests (This is a new relationship).
     */
    public function verifyRequests()
    {
        return $this->hasMany(VerifyRequest::class);
    }
}
