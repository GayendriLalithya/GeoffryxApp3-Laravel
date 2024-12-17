<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProject extends Model
{
    protected $table = 'view_user_projects'; // The name of the view

    public $timestamps = false; // Views do not have timestamps

    protected $fillable = [
        'work_id', 'name', 'location', 'start_date', 'end_date',
        'budget', 'description', 'client_name', 'client_contact'
    ];
}
