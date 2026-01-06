<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterCompany extends Model
{
    use SoftDeletes;

    protected $table = 'master_companies';
    protected $primaryKey = 'company_id';

    protected $fillable = [
        'name_company',
        'phone_company',
        'address_company',
    ];

    // Relationship to Users
    public function users()
    {
        return $this->hasMany(User::class, 'company_id', 'company_id');
    }
}
