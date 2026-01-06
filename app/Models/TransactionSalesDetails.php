<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionSalesDetails extends Model
{
    protected $table = 'transaction_sales_details';
    protected $primaryKey = 'transaction_sales_detail_id';

    protected $fillable = [
        'transaction_sales_id',
        'item_id',
        'qty',
        'costprice',
        'sell_price',
        'subtotal',
        'discount_amount',
        'discount_percentage',
        'total_amount'
    ];

    protected $casts = [
        'qty' => 'integer',
        'costprice' => 'float',
        'sell_price' => 'float',
        'subtotal' => 'float',
        'discount_amount' => 'float',
        'discount_percentage' => 'float',
        'total_amount' => 'float'
    ];

    public function transactionSales(): BelongsTo
    {
        return $this->belongsTo(TransactionSales::class, 'transaction_sales_id');
    }

    public function masterItem(): BelongsTo
    {
        return $this->belongsTo(MasterItem::class, 'item_id', 'item_id');
    }
}
