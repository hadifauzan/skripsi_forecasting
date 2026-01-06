<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterCategoryArticle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_category_articles';
    protected $primaryKey = 'category_article_id';
    
    protected $fillable = [
        'name_category_article',
        'description',
        'slug'
    ];

    // Relationship dengan articles (jika ada tabel articles)
    public function articles()
    {
        return $this->hasMany(Content::class, 'category_article_id', 'category_article_id');
    }

    // Scope untuk kategori aktif
    public function scopeActive($query)
    {
        return $query;
    }

    // Accessor untuk name (alias dari name_category_article)
    public function getNameAttribute()
    {
        return $this->name_category_article;
    }
    
    // Mutator untuk auto-generate slug dari name_category_article
    public function setNameCategoryArticleAttribute($value)
    {
        $this->attributes['name_category_article'] = $value;
        $this->attributes['slug'] = strtolower(str_replace(' ', '-', $value));
    }
}
