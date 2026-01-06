<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterCustomerType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_customers_types';
    protected $primaryKey = 'customer_type_id';
    
    protected $fillable = [
        'name_customer_type',
        'reseller'
    ];

    // Relationship dengan item details
    public function itemDetails()
    {
        return $this->hasMany(MasterItemDetail::class, 'customer_type_id', 'customer_type_id');
    }
}
