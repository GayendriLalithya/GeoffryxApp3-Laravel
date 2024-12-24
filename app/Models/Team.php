<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $table = 'team';
    protected $primaryKey = 'team_id';
    public $timestamps = true;

    // Define relationship with the Work model
    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id');
    }

    // Define relationship with TeamMember
    public function members()
    {
        return $this->hasMany(TeamMember::class, 'team_id');
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class, 'team_id');
    }

}

