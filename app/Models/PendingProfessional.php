<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingProfessional extends Model
{
    use HasFactory;

    protected $table = 'pending_professional';
    protected $primaryKey = 'pending_prof_id';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function professional()
{
    return $this->belongsTo(Professional::class, 'professional_id', 'professional_id');
}
}
