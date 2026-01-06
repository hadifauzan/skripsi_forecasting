<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateInfo extends Model
{
    protected $table = 'affiliate_info';
    
    protected $fillable = [
        'affiliate_registration_id',
        'akun_instagram',
        'akun_tiktok'
    ];

    public function affiliateRegistration()
    {
        return $this->belongsTo(AffiliateRegistration::class);
    }
}
