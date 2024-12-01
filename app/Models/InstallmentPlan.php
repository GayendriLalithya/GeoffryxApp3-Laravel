<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentPlan extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'installment_plan';
    protected $primaryKey = 'installment_plan_id';
    protected $fillable = [
        'work_id',
    ];

    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'work_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'installment_plan_id', 'installment_plan_id');
    }
}
