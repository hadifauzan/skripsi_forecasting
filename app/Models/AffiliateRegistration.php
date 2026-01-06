<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateRegistration extends Model
{
    protected $fillable = [
        'email',
        'nama_lengkap',
        'kontak_whatsapp',
        'kota_domisili',
        'profesi_kesibukan',
        'info_darimana',
        'yang_lain_text',
        'status'
    ];
    
    protected $attributes = [
        'status' => 'Aktif'
    ];

    public function affiliateInfo()
    {
        return $this->hasOne(AffiliateInfo::class);
    }
}
