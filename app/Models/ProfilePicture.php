<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilePicture extends Model
{
    use HasFactory;

    protected $table = 'profile_picture';

    protected $primaryKey = 'profile_picture_id';

    protected $fillable = [
        'user_id',
        'profile_pic',
    ];
}
