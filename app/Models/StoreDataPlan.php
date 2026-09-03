<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreDataPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'data_plan_id',
        'selling_price',
        'is_enabled',
        'is_best_offer',
    ];

    protected $casts = [
        'selling_price' => 'decimal:2',
        'is_enabled' => 'boolean',
        'is_best_offer' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function dataPlan()
    {
        return $this->belongsTo(DataPlan::class);
    }
}
