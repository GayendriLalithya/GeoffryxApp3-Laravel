<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referal extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'referrals';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'referral_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'work_id',
        'professional_id',
        'reference_id',
        'status',
    ];

    /**
     * The relationships.
     */
    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'id');
    }

    public function professional()
    {
        return $this->belongsTo(Professional::class, 'professional_id', 'id');
    }

    public function reference()
    {
        return $this->belongsTo(Reference::class, 'reference_id', 'id');
    }
}
