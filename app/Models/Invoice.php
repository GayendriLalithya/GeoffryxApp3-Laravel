<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'invoice';
    protected $primaryKey = 'invoice_id';
    protected $fillable = [
        'material_cost',
        'labor_charge',
        'service_charge',
        'work_id',
    ];

    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'work_id');
    }
}
