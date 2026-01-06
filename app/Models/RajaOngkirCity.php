<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RajaOngkirCity extends Model
{
    use SoftDeletes;

    protected $table = 'rajaongkir_cities';

    protected $fillable = [
        'city_id',
        'province_id', 
        'type',
        'city_name',
        'postal_code'
    ];

    public function province()
    {
        return $this->belongsTo(RajaOngkirProvince::class, 'province_id', 'province_id');
    }

    /**
     * Get full city name with type
     */
    public function getFullNameAttribute()
    {
        return $this->type . ' ' . $this->city_name;
    }

    /**
     * Scope untuk search kota
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('city_name', 'like', "%{$term}%")
                    ->orWhere('postal_code', 'like', "%{$term}%");
    }
}
