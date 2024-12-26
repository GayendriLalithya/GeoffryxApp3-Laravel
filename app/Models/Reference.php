<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reference';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'reference_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'professional_id',
    ];

    /**
     * The relationships.
     */

    public function professional()
    {
        return $this->belongsTo(Professional::class, 'professional_id', 'professional_id');
    }
}