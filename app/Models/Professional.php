<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    protected $table = 'professionals'; // If you're using a custom table name

    protected $primaryKey = 'professional_id';

    protected $fillable = [
        'user_id', 'type', 'availability', 'work_location', 'payment_min', 'payment_max', 'preferred_project_size'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pendingProfessionals()
    {
        return $this->hasMany(PendingProfessional::class, 'professional_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'professional_id');
    }


}
