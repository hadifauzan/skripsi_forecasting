<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterItemStock extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_items_stock';
    protected $primaryKey = 'item_stock_id';
    
    protected $fillable = [
        'item_id',
        'inventory_id',
        'stock'
    ];

    protected $casts = [
        'item_id' => 'integer',
        'inventory_id' => 'integer',
        'stock' => 'integer'
    ];

    // Relationship dengan item
    public function item()
    {
        return $this->belongsTo(MasterItem::class, 'item_id', 'item_id');
    }

    // Relationship dengan inventory
    public function inventory()
    {
        return $this->belongsTo(MasterInventory::class, 'inventory_id', 'inventory_id');
    }
}
