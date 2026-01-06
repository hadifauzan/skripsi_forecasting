<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'name',
        'description',
        'image',
        'category',
        'price',
        'stock',
        'status',
        'order',
        'content'
    ];

    protected $casts = [
        'content' => 'array',
        'status' => 'boolean'
    ];

    /**
     * Get the id attribute (alias for product_id)
     */
    public function getIdAttribute()
    {
        return $this->product_id;
    }

    // Scope untuk produk aktif
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // Scope untuk urutan
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at');
    }
}
