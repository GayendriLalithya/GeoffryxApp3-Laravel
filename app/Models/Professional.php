<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    use HasFactory;
    protected $table = 'professionals';
    protected $primaryKey = 'professional_id';
    protected $fillable = [
        'user_id',
        'payment_range',
        'type',
        'availability',
        'work_location',
        'account_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function work()
    {
        return $this->hasMany(Work::class, 'user_id', 'user_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'professional_id', 'professional_id');
    }

    public function references()
    {
        return $this->hasMany(Reference::class, 'professional_id', 'professional_id');
    }

    public function meetups()
    {
        return $this->hasMany(Meetup::class, 'user_id', 'user_id');
    }
}
