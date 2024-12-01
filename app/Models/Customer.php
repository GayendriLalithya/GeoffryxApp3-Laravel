<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    
    protected $table = 'customer';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function work()
    {
        return $this->hasMany(Work::class, 'user_id', 'user_id');
    }

    public function meetups()
    {
        return $this->hasMany(Meetup::class, 'user_id', 'user_id');
    }
}
