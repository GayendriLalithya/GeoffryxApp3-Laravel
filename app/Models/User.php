<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'contact_no', 
        'address', 
        'email', 
        'password', 
        'user_type'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // public function profilePicture()
    // {
    //     return $this->hasOne(ProfilePicture::class, 'user_id', 'id');
    // }

    public function profilePicture()
    {
        return $this->hasOne(ProfilePicture::class, 'user_id');
    }


}

