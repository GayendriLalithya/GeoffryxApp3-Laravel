<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'rating';
    protected $primaryKey = 'rating_id';
    
    protected $fillable = [
        'professional_id',
        'work_id',
        'user_id',
        'rate',
        'comment'
    ];

    // Add these to ensure proper type casting
    protected $casts = [
        'rate' => 'integer'
    ];
}