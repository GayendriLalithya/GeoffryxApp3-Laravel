<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingRequest extends Model
{
    protected $table = 'pending_requests'; // This is the name of the view, not a table
    public $timestamps = false;  // Views usually don't have timestamps

    // You can add any fillable properties here if you plan to insert data into this view.
    protected $fillable = [
        'verify_id', 'nic_no', 'nic_front', 'nic_back', 'status', 'user_id',
        'user_name', 'contact_no', 'address', 'email', 'user_type',
        'certificate_id', 'certificate_name', 'certificate'
    ];
}
