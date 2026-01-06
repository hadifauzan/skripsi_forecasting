<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterContent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_contents';
    protected $primaryKey = 'content_id';

    protected $fillable = [
        'type_of_page',
        'section',
        'title',
        'body',
        'image',
        'item_id',
        'status',
        'views',
        'video_url',
        'username'
    ];

    /**
     * Relationship: MasterContent belongs to MasterItem
     */
    public function masterItem()
    {
        return $this->belongsTo(MasterItem::class, 'item_id', 'item_id');
    }

    /**
     * Scope for filtering by section
     */
    public function scopeForSection($query, $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Scope for filtering by page type
     */
    public function scopeForPageType($query, $type)
    {
        return $query->where('type_of_page', $type);
    }

    /**
     * Get image URL with fallback
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/placeholder.jpg');
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views');
    }
}