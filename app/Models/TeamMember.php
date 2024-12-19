<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $table = 'team_members';
    protected $primaryKey = 'team_member_id';
    public $timestamps = true;

    // Define relationship with the Team model
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    // Define relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}