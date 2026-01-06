<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterBranch extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_branches';
    protected $primaryKey = 'branch_id';
    
    protected $fillable = [
        'company_id',
        'name_branch',
        'phone_branch',
        'address_branch'
    ];

    protected $casts = [
        'branch_id' => 'integer',
        'company_id' => 'integer',
        'name_branch' => 'string',
        'phone_branch' => 'string',
        'address_branch' => 'string'
    ];

    // Relationship dengan inventories
    public function inventories()
    {
        return $this->hasMany(MasterInventory::class, 'branch_id', 'branch_id');
    }

    // Relationship dengan company (jika ada model Company)
    public function company()
    {
        return $this->belongsTo(MasterCompany::class, 'company_id', 'company_id');
    }
}
