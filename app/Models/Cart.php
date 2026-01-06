<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'master_item_id',
        'quantity',
        'price',
        'variant'
    ];

    protected $casts = [
        'price' => 'float',
        'quantity' => 'integer'
    ];

    /**
     * Relationship dengan User (supports both admin users and customer user_id mapping)
     */
    public function user(): BelongsTo
    {
        // This can reference either master_users (for admin) or customer_id (for customers)
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relationship with Customer (for customer orders)
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(\App\Models\MasterCustomers::class, 'user_id', 'customer_id');
    }

    /**
     * Relationship dengan MasterItem
     */
    public function masterItem(): BelongsTo
    {
        return $this->belongsTo(MasterItem::class, 'master_item_id', 'item_id');
    }

    /**
     * Accessor untuk subtotal
     */
    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->price;
    }

    /**
     * Get sell price for the item (supports admin and customer pricing)
     */
    public function getSellPrice(): float
    {
        // Return the price stored in cart, or get from masterItem if available
        if ($this->price > 0) {
            return $this->price;
        }

        // Fallback to masterItem price if available
        if ($this->masterItem) {
            // If admin is shopping, use regular customer price (customer_type_id = 1)
            $customerTypeId = $this->determineCustomerTypeId();
            
            // Get pricing based on customer type
            $pricing = $this->masterItem->itemDetails()
                ->where('customer_type_id', $customerTypeId)
                ->first();
                
            if ($pricing && $pricing->sell_price > 0) {
                return $pricing->sell_price;
            }
            
            // Fallback to cost price if no specific pricing
            if ($this->masterItem->costprice_item > 0) {
                return $this->masterItem->costprice_item;
            }
        }

        return 0;
    }

    /**
     * Determine customer type ID for pricing (1 = regular customer, 2 = agent, 3 = reseller)
     */
    private function determineCustomerTypeId(): int
    {
        // Check if this cart belongs to a customer (customer_id range)
        if ($this->user_id > 1000) {
            // Try to get customer data
            $customer = \App\Models\MasterCustomers::where('customer_id', $this->user_id)->first();
            if ($customer) {
                return $customer->customer_type_id;
            }
        }
        
        // Default to regular customer pricing for admin or unknown users
        return 1;
    }

    /**
     * Get item name
     */
    public function getItemName(): string
    {
        return $this->masterItem->name_item ?? 'Unknown Item';
    }

    /**
     * Get total price for this cart item
     */
    public function getTotalPrice(): float
    {
        return $this->quantity * $this->getSellPrice();
    }
}
