<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    protected $table = 'professionals'; // If you're using a custom table name

    protected $fillable = [
        'user_id', 'type', 'availability', 'work_location', 'payment_min', 'payment_max', 'preferred_project_size'
    ];
}
