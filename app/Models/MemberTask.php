<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberTask extends Model
{
    use HasFactory;

    protected $table = 'member_tasks';

    // Define the primary key
    protected $primaryKey = 'member_task_id';

    // Allow mass assignment for the necessary fields
    protected $fillable = ['description', 'status', 'team_member_id', 'team_id'];

    // If the primary key is not an auto-incrementing integer, set this to false
    public $incrementing = true;

    // If your primary key column is not an integer, specify the type
    protected $keyType = 'int';
}
