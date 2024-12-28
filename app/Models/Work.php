<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;
    
    protected $table = 'work';
    protected $primaryKey = 'work_id';
    protected $fillable = [
        'description',
        'name',
        'user_id',
        'location',
        'budget',
        'start_date',
        'end_date',
        'status',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'work_id', 'work_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'work_id', 'work_id');
    }

    public function meetups()
    {
        return $this->hasMany(Meetup::class, 'work_id', 'work_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'work_id', 'work_id');
    }

    public function team()
    {
        return $this->hasOne(Team::class, 'work_id');
    }

    public function installmentPlans()
    {
        return $this->hasMany(InstallmentPlan::class, 'work_id', 'work_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pendingProfessionals()
    {
        return $this->hasMany(PendingProfessional::class, 'work_id');
    }
}
