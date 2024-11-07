<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    protected $fillable = [
        'name', 'contact_no', 'address', 'email', 'password', 'user_type'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->user_type = $user->user_type ?? 'customer'; // Set default if not provided
        });
    }
}
