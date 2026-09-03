<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanDataPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'data_plan_id',
        'wholesale_price',
    ];

    protected $casts = [
        'wholesale_price' => 'decimal:2',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function dataPlan()
    {
        return $this->belongsTo(DataPlan::class);
    }
}
