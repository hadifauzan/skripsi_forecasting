<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BufferStockConfig extends Model
{
    use SoftDeletes;

    protected $table = 'buffer_stock_config';
    protected $primaryKey = 'config_id';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'item_type',
        'item_id',
        'branch_id',
        'avg_daily_usage',
        'max_daily_usage',
        'lead_time_days',
        'max_lead_time_days',
        'safety_days',
        'demand_variability_factor',
        'service_level_percentage',
        'min_reorder_quantity',
        'is_active',
        'config_notes'
    ];

    protected $casts = [
        'item_id' => 'integer',
        'branch_id' => 'integer',
        'avg_daily_usage' => 'decimal:1',
        'max_daily_usage' => 'decimal:4',
        'lead_time_days' => 'integer',
        'max_lead_time_days' => 'integer',
        'safety_days' => 'integer',
        'demand_variability_factor' => 'decimal:2',
        'service_level_percentage' => 'decimal:2',
        'min_reorder_quantity' => 'integer',
        'is_active' => 'boolean'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByItemType($query, $type)
    {
        return $query->where('item_type', $type);
    }
}
