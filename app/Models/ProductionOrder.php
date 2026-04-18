<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionOrder extends Model
{
    use SoftDeletes;

    protected $table = 'production_orders';
    protected $primaryKey = 'production_order_id';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'item_id',
        'branch_id',
        'created_by',
        'approved_by',
        'order_number',
        'qty_planned',
        'qty_produced',
        'unit',
        'status',
        'planned_date',
        'started_at',
        'completed_at',
        'total_material_cost',
        'overhead_cost',
        'hpp_per_unit',
        'notes'
    ];

    protected $casts = [
        'item_id' => 'integer',
        'branch_id' => 'integer',
        'created_by' => 'integer',
        'approved_by' => 'integer',
        'qty_planned' => 'decimal:2',
        'qty_produced' => 'decimal:2',
        'status' => 'string',
        'planned_date' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_material_cost' => 'decimal:2',
        'overhead_cost' => 'decimal:2',
        'hpp_per_unit' => 'decimal:2'
    ];

    // Relationships
    public function item()
    {
        return $this->belongsTo(MasterItem::class, 'item_id', 'item_id');
    }

    public function rawMaterialOut()
    {
        return $this->hasMany(RawMaterialOut::class, 'production_order_id', 'production_order_id');
    }

    public function finishedGoodsIn()
    {
        return $this->hasMany(FinishedGoodsIn::class, 'production_order_id', 'production_order_id');
    }
}
