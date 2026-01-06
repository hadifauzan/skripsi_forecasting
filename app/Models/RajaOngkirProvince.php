<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RajaOngkirProvince extends Model
{
    use SoftDeletes;

    protected $table = 'rajaongkir_provinces';
    protected $primaryKey = 'province_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'province_id',
        'province_name'
    ];

    public function cities()
    {
        return $this->hasMany(RajaOngkirCity::class, 'province_id', 'province_id');
    }
}
