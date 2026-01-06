<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_categories';
    protected $primaryKey = 'category_id';
    
    protected $fillable = [
        'name_category'
    ];

    // Relationship dengan items melalui pivot table
    public function items()
    {
        return $this->belongsToMany(
            MasterItem::class,
            'master_items_categories',
            'categories_id',
            'item_id',
            'category_id',
            'item_id'
        );
    }

    // Scope untuk kategori aktif (semua kategori dianggap aktif)
    public function scopeActive($query)
    {
        return $query; // Return all categories since no status column exists
    }

    // Accessor untuk name (alias dari name_category)
    public function getNameAttribute()
    {
        return $this->name_category;
    }
    
    // Accessor untuk slug (dibuat dari name_category)
    public function getSlugAttribute()
    {
        return strtolower(str_replace(' ', '-', $this->name_category));
    }
}
