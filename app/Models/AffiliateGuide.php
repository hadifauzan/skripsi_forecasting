<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateGuide extends Model
{
    // Use master_contents table
    protected $table = 'master_contents';
    
    // Primary key
    protected $primaryKey = 'content_id';

    protected $fillable = [
        'title',
        'body',
        'section',
        'sub_items',
        'status',
        'type_of_page'
    ];

    protected $casts = [
        'status' => 'boolean',
        'sub_items' => 'array'
    ];

    protected $attributes = [
        'type_of_page' => 'affiliate_guide'
    ];

    /**
     * Boot method to auto-set type_of_page
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->type_of_page = 'affiliate_guide';
        });

        static::addGlobalScope('affiliate_guide', function ($builder) {
            $builder->where('type_of_page', 'affiliate_guide');
        });
    }

    /**
     * Scope untuk hanya mendapatkan guide yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope untuk mengurutkan berdasarkan ID
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('content_id', 'asc');
    }

    /**
     * Scope untuk filter berdasarkan section
     */
    public function scopeSection($query, $section)
    {
        return $query->where('section', $section);
    }

    // Accessor for compatibility
    public function getContentAttribute()
    {
        return $this->body;
    }

    // Mutator for compatibility
    public function setContentAttribute($value)
    {
        $this->attributes['body'] = $value;
    }

    // Accessor for compatibility
    public function getSectionTypeAttribute()
    {
        return $this->section;
    }

    // Mutator for compatibility
    public function setSectionTypeAttribute($value)
    {
        $this->attributes['section'] = $value;
    }

    // Accessor for compatibility
    public function getIsActiveAttribute()
    {
        return $this->status;
    }

    // Mutator for compatibility
    public function setIsActiveAttribute($value)
    {
        $this->attributes['status'] = $value;
    }

    // Accessor for id to return content_id
    public function getIdAttribute()
    {
        return $this->content_id;
    }
}
