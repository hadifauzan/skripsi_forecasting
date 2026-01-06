<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class MasterCustomers extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $table = 'master_customers';
    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'company_id',
        'customer_type_id',
        'email_customer',
        'name_customer',
        'phone_customer',
        'address_customer',
        'social_media',
        'sales_platform',
        'latitude',
        'longitude',
        'location_notes',
        'point',
        'password',
        'status',
        'remember_token',
        'jwt_token'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'jwt_token'
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'point' => 'integer',
        'password' => 'hashed'
    ];

    // Override the default username field for authentication
    public function getAuthIdentifierName()
    {
        return 'customer_id'; // Use customer_id for session storage (integer)
    }

    // Override to return the actual customer_id value
    public function getAuthIdentifier()
    {
        return $this->customer_id;
    }

    // Method to find user by email or phone
    public static function findByEmailOrPhone($identifier)
    {
        return static::where('email_customer', $identifier)
                    ->orWhere('phone_customer', $identifier)
                    ->first();
    }

    // Method to get the password field
    public function getAuthPassword()
    {
        return $this->password;
    }

    // Relationships
    public function masterCompany()
    {
        return $this->belongsTo(MasterCompany::class, 'company_id', 'company_id');
    }

    public function masterCustomerType()
    {
        return $this->belongsTo(MasterCustomerType::class, 'customer_type_id', 'customer_type_id');
    }

    public function company()
    {
        return $this->belongsTo(MasterCompany::class, 'company_id', 'company_id');
    }

    public function customerType()
    {
        return $this->belongsTo(MasterCustomerType::class, 'customer_type_id', 'customer_type_id');
    }

    public function transactionSales()
    {
        return $this->hasMany(TransactionSales::class, 'customer_id', 'customer_id');
    }

    // Relationship to User model (for customers with corresponding user accounts)
    public function user()
    {
        return $this->belongsTo(User::class, 'email_customer', 'email');
    }

    /**
     * Get clean customer name without prefix and timestamp
     * Format: "nama_reseller_timestamp" or "nama_customer_timestamp" -> "nama"
     */
    public function getCleanNameAttribute()
    {
        if (!$this->name_customer) {
            return null;
        }

        $name = $this->name_customer;
        
        // Remove common suffixes like _reseller_timestamp or _customer_timestamp
        $patterns = [
            '/_reseller_\d+$/',  // Remove _reseller_timestamp
            '/_customer_\d+$/',  // Remove _customer_timestamp  
            '/_\d+$/'           // Remove any _timestamp at the end
        ];

        foreach ($patterns as $pattern) {
            $name = preg_replace($pattern, '', $name);
        }

        // Capitalize first letter of each word
        return ucwords(str_replace('_', ' ', $name));
    }

    /**
     * Get customer name for display (alias for clean_name)
     */
    public function getDisplayNameAttribute()
    {
        return $this->clean_name;
    }

    /**
     * Extract social media information from location_notes or social_media field
     */
    public function getSocialMediaAttribute()
    {
        // Prioritaskan field social_media yang ada di database
        if ($this->attributes['social_media']) {
            // Parse format: "instagram, tiktok" atau hanya "instagram"
            $platforms = explode(',', $this->attributes['social_media']);
            $socialMedia = [];
            
            foreach ($platforms as $platform) {
                $platform = trim($platform);
                if (strpos($platform, '@') === 0) {
                    // Jika format @username, tentukan platform berdasarkan posisi
                    if (!isset($socialMedia['instagram'])) {
                        $socialMedia['instagram'] = $platform;
                    } else {
                        $socialMedia['tiktok'] = $platform;
                    }
                }
            }
            return $socialMedia;
        }

        // Fallback ke location_notes jika social_media kosong (untuk kompatibilitas data lama)
        if (!$this->location_notes) {
            return null;
        }

        $notes = $this->location_notes;
        $socialMedia = [];

        // Try to decode as JSON first (new format)
        $decoded = json_decode($notes, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            // JSON format - extract directly
            $socialMedia['instagram'] = $decoded['akun_instagram'] ?? null;
            $socialMedia['tiktok'] = ($decoded['akun_tiktok'] && $decoded['akun_tiktok'] !== '-') ? $decoded['akun_tiktok'] : null;
            return $socialMedia;
        }

        // Fall back to old text format parsing
        // Extract Instagram
        if (preg_match('/Instagram:\s*([^|]+)/', $notes, $matches)) {
            $socialMedia['instagram'] = trim($matches[1]);
        }

        // Extract TikTok
        if (preg_match('/TikTok:\s*([^|]+)/', $notes, $matches)) {
            $tiktok = trim($matches[1]);
            $socialMedia['tiktok'] = $tiktok !== '-' ? $tiktok : null;
        }

        return $socialMedia;
    }

    /**
     * Extract social media information dari field social_media atau location_notes (untuk kompatibilitas view)
     */
    public function getSocialMediaFromLocationNotes()
    {
        // Prioritaskan field social_media yang ada di database
        if ($this->attributes['social_media']) {
            // Parse format: "instagram, tiktok" atau hanya "instagram"  
            $platforms = explode(',', $this->attributes['social_media']);
            $socialMedia = [];
            
            foreach ($platforms as $index => $platform) {
                $platform = trim($platform);
                if ($platform) {
                    if ($index === 0) {
                        $socialMedia['akun_instagram'] = $platform;
                    } else {
                        $socialMedia['akun_tiktok'] = $platform;
                    }
                }
            }
            return $socialMedia;
        }

        // Fallback ke location_notes jika social_media kosong (untuk kompatibilitas data lama)
        if (!$this->location_notes) {
            return null;
        }

        $notes = $this->location_notes;
        $socialMedia = [];

        // Try to decode as JSON first (new format)
        $decoded = json_decode($notes, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            // JSON format - extract directly
            $socialMedia['akun_instagram'] = $decoded['akun_instagram'] ?? null;
            $socialMedia['akun_tiktok'] = ($decoded['akun_tiktok'] && $decoded['akun_tiktok'] !== '-') ? $decoded['akun_tiktok'] : null;
            return $socialMedia;
        }

        // Fall back to old text format parsing
        // Extract Instagram
        if (preg_match('/Instagram:\s*([^|]+)/', $notes, $matches)) {
            $socialMedia['akun_instagram'] = trim($matches[1]);
        }

        // Extract TikTok
        if (preg_match('/TikTok:\s*([^|]+)/', $notes, $matches)) {
            $tiktok = trim($matches[1]);
            $socialMedia['akun_tiktok'] = $tiktok !== '-' ? $tiktok : null;
        }

        return $socialMedia;
    }

    /**
     * Extract sales information dari field sales_platform atau location_notes (untuk kompatibilitas)
     */
    public function getSalesInfoFromLocationNotes()
    {
        // Prioritaskan field sales_platform yang ada di database
        if ($this->attributes['sales_platform']) {
            return ['berjualan_melalui' => $this->attributes['sales_platform']];
        }

        // Fallback ke location_notes jika sales_platform kosong (untuk kompatibilitas data lama)
        if (!$this->location_notes) {
            return null;
        }

        $notes = $this->location_notes;
        $salesInfo = [];

        // Try to decode as JSON first (new format)
        $decoded = json_decode($notes, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            // JSON format - extract directly
            $salesInfo['berjualan_melalui'] = $decoded['berjualan_melalui'] ?? null;
            return $salesInfo;
        }

        // Fall back to old text format parsing
        // Extract platform berjualan
        if (preg_match('/Berjualan melalui:\s*([^|]+)/', $notes, $matches)) {
            $salesInfo['berjualan_melalui'] = trim($matches[1]);
        }

        return $salesInfo;
    }

    /**
     * Extract sales information dari field sales_platform atau location_notes (attribute accessor)
     */
    public function getSalesInfoAttribute()
    {
        // Prioritaskan field sales_platform yang ada di database
        if ($this->attributes['sales_platform']) {
            return $this->attributes['sales_platform'];
        }

        // Fallback ke location_notes jika sales_platform kosong (untuk kompatibilitas data lama)
        if (!$this->location_notes) {
            return null;
        }

        $notes = $this->location_notes;
        
        // Try to decode as JSON first (new format)
        $decoded = json_decode($notes, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            // JSON format - extract directly
            return $decoded['berjualan_melalui'] ?? null;
        }

        // Fall back to old text format parsing
        // Extract sales method
        if (preg_match('/Berjualan melalui:\s*(.+)$/', $notes, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }
}
