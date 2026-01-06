<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterRole extends Model
{
    use SoftDeletes;

    protected $table = 'master_roles';
    protected $primaryKey = 'role_id';

    protected $fillable = [
        'name_role',
    ];

    // Relationship to Users
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }
}
