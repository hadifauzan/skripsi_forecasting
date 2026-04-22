<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterItemBillOfMaterials extends Model
{
    use SoftDeletes;

    protected $table = 'master_items_bill_of_materials';
    protected $primaryKey = 'bom_id';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'item_id',
        'item_raw_id',
        'quantity_required',
        'yield_percentage'
    ];

    protected $casts = [
        'item_id' => 'integer',
        'item_raw_id' => 'integer',
        'quantity_required' => 'decimal:1',
        'yield_percentage' => 'decimal:2'
    ];

    // Relationships
    public function item()
    {
        return $this->belongsTo(MasterItem::class, 'item_id', 'item_id');
    }

    public function rawMaterial()
    {
        return $this->belongsTo(MasterItemRawMaterial::class, 'item_raw_id', 'item_raw_id');
    }
}
