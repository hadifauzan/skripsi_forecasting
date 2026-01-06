<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterItemDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_items_details';
    protected $primaryKey = 'item_detail_id';
    
    protected $fillable = [
        'item_id',
        'customer_type_id',
        'cost_price',
        'sell_price'
    ];

    protected $casts = [
        'cost_price' => 'float',
        'sell_price' => 'float',
        'item_id' => 'integer',
        'customer_type_id' => 'integer'
    ];

    // Relationship dengan item
    public function item()
    {
        return $this->belongsTo(MasterItem::class, 'item_id', 'item_id');
    }

    // Relationship dengan customer type
    public function customerType()
    {
        return $this->belongsTo(MasterCustomerType::class, 'customer_type_id', 'customer_type_id');
    }
}
