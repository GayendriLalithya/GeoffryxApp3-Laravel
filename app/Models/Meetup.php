<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meetup extends Model
{
    use HasFactory;

    protected $table = 'meetups';
    protected $primaryKey = 'meetup_id';
    protected $fillable = [
        'schedule_date',
        'schedule_time',
        'url',
        'user_id',
        'work_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'work_id');
    }
}
