<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterInventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_inventories';
    protected $primaryKey = 'inventory_id';
    
    protected $fillable = [
        'branch_id',
        'name_inventory'
    ];

    protected $casts = [
        'inventory_id' => 'integer',
        'branch_id' => 'integer',
        'name_inventory' => 'string'
    ];

    // Relationship dengan stock items
    public function itemStocks()
    {
        return $this->hasMany(MasterItemStock::class, 'inventory_id', 'inventory_id');
    }

    // Relationship dengan branch (jika ada model Branch)
    public function branch()
    {
        return $this->belongsTo(MasterBranch::class, 'branch_id', 'branch_id');
    }
}
