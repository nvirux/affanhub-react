<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'network_id',
        'data_type_id',
        'name',
        'size_mb',
        'validity',
        'cost_price',
        'selling_price',
        'default_retail_price',
        'plan_code',
        'is_active',
        'is_best_offer',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'default_retail_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_best_offer' => 'boolean',
        'size_mb' => 'integer',
    ];

    public function network()
    {
        return $this->belongsTo(Network::class);
    }

    public function dataType()
    {
        return $this->belongsTo(DataType::class);
    }

    public function planPrices()
    {
        return $this->hasMany(PlanDataPrice::class);
    }

    public function storeDataPlans()
    {
        return $this->hasMany(StoreDataPlan::class);
    }

    /**
     * Categorize the plan validity into fixed standard tabs: Daily, Weekly, Monthly, Yearly.
     */
    public function validityGroup(): string
    {
        $raw = strtolower(trim((string) $this->validity));

        if ($raw === '' || $raw === 'not specified') {
            return 'Monthly';
        }

        // Check for hours (e.g. "24 hours", "24hrs", "48 hr")
        if (preg_match('/(\d+)\s*(?:hours?|hrs?)\b/i', $raw, $matches)) {
            $hours = (int) $matches[1];
            $days = $hours / 24;
            return $days <= 6 ? 'Daily' : ($days <= 14 ? 'Weekly' : 'Monthly');
        }

        // Check for days (e.g. "1 day", "1 days", "2 days", "7 days", "14 days", "30 days", "60 days")
        if (preg_match('/(\d+)\s*(?:days?|d)\b/i', $raw, $matches)) {
            $days = (int) $matches[1];
            if ($days <= 6) {
                return 'Daily';
            }
            if ($days <= 14) {
                return 'Weekly';
            }
            if ($days <= 364) {
                return 'Monthly';
            }
            return 'Yearly';
        }

        // Check for weeks (e.g. "1 week", "2 weeks", "2 wks")
        if (preg_match('/(\d+)\s*(?:weeks?|wks?)\b/i', $raw, $matches)) {
            $weeks = (int) $matches[1];
            return $weeks <= 2 ? 'Weekly' : 'Monthly';
        }

        // Check for months (e.g. "1 month", "2 months", "3 months")
        if (preg_match('/(\d+)\s*(?:months?|mos?)\b/i', $raw, $matches)) {
            $months = (int) $matches[1];
            return $months < 12 ? 'Monthly' : 'Yearly';
        }

        // Check for years (e.g. "1 year", "2 years", "1 yr")
        if (preg_match('/(\d+)\s*(?:years?|yrs?)\b/i', $raw, $matches)) {
            return 'Yearly';
        }

        // Keyword fallbacks
        if (str_contains($raw, 'hour') || str_contains($raw, 'daily')) {
            return 'Daily';
        }
        if (str_contains($raw, 'week')) {
            return 'Weekly';
        }
        if (str_contains($raw, 'year') || str_contains($raw, 'annual')) {
            return 'Yearly';
        }

        return 'Monthly';
    }
}
