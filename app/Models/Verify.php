<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verify extends Model
{
    use HasFactory;

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'verify_id';

    // Specify the table name if it's not the plural form of the model name
    protected $table = 'verify';

    // Define which fields are mass assignable
    protected $fillable = [
        'user_id',           // Foreign key to User
        'nic_no',            // NIC Number
        'nic_front',         // NIC Front Image
        'nic_back',          // NIC Back Image
        'professional_type', // Professional Type (e.g., Chartered Architect)
    ];

    // Relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');  // Define foreign key & local key
    }
}
