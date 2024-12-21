<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkHistory extends Model
{
    use HasFactory;

    protected $table = 'work_history';

    protected $fillable = [
        'work_id',
        'user_id',
        'created_at',
        'updated_at',
    ];
}
