<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function dataPlans()
    {
        return $this->hasMany(DataPlan::class);
    }

    public function airtimeDiscount()
    {
        return $this->hasOne(AirtimeDiscount::class);
    }

    public function planAirtimeDiscounts()
    {
        return $this->hasMany(PlanAirtimeDiscount::class);
    }

    public function storeAirtimeDiscounts()
    {
        return $this->hasMany(StoreAirtimeDiscount::class);
    }
}
