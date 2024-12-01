<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'rating';
    protected $primaryKey = 'rating_id';
    protected $fillable = [
        'professional_id',
        'work_id',
        'user_id',
        'rate',
        'comment',
    ];

    public function professional()
    {
        return $this->belongsTo(Professional::class, 'professional_id', 'professional_id');
    }

    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'work_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
