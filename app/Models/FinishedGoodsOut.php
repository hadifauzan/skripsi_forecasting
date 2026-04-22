<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinishedGoodsOut extends Model
{
    use SoftDeletes;

    protected $table = 'finished_goods_out';
    protected $primaryKey = 'fg_out_id';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'item_id',
        'inventory_id',
        'branch_id',
        'transaction_sales_detail_id',
        'issued_by',
        'document_number',
        'qty_out',
        'unit',
        'unit_cost',
        'total_cost',
        'stock_before',
        'stock_after',
        'type',
        'out_date',
        'notes'
    ];

    protected $casts = [
        'item_id' => 'integer',
        'inventory_id' => 'integer',
        'branch_id' => 'integer',
        'transaction_sales_detail_id' => 'integer',
        'issued_by' => 'integer',
        'qty_out' => 'decimal:2',
        'unit_cost' => 'decimal:1',
        'total_cost' => 'decimal:2',
        'stock_before' => 'decimal:2',
        'stock_after' => 'decimal:2',
        'out_date' => 'date'
    ];

    // Relationships
    public function item()
    {
        return $this->belongsTo(MasterItem::class, 'item_id', 'item_id');
    }

    public function inventory()
    {
        return $this->belongsTo(MasterInventory::class, 'inventory_id', 'inventory_id');
    }
}
