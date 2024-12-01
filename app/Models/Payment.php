<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment';
    protected $primaryKey = 'payment_id';
    protected $fillable = [
        'installment_plan_id',
        'amount',
        'date',
        'time',
    ];

    public function installmentPlan()
    {
        return $this->belongsTo(InstallmentPlan::class, 'installment_plan_id', 'installment_plan_id');
    }
}
