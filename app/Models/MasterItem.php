<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MasterItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'item_id';

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'item_id';
    }

    protected $fillable = [
        'company_id',
        'code_item',
        'name_item',
        'description_item',
        'ingredient_item',
        'ingredients_item',
        'netweight_item',
        'contain_item',
        'costprice_item',
        'picture_item',
        'status_item',
        'is_reseller_babyspa',
        // Additional fields for sales management
        'price_item',
        'weight_item',
        'stock_item',
        'category_id',
        'image_item',
        'status'
    ];

    protected $casts = [
        'costprice_item' => 'float',
        'price_item' => 'float',
        'weight_item' => 'float',
        'stock_item' => 'integer',
        'category_id' => 'integer',
        'company_id' => 'integer',
        'is_bundle_configurable' => 'boolean',
        'bundle_config' => 'array'
    ];

    /**
     * Get the price attribute using costprice_item as fallback
     */
    public function getPriceAttribute()
    {
        return $this->price_item ?: $this->costprice_item;
    }

    /**
     * Get the id attribute using item_id
     */
    public function getIdAttribute()
    {
        return $this->item_id;
    }

    // Relationship dengan kategori melalui pivot table
    public function categories()
    {
        return $this->belongsToMany(
            MasterCategory::class,
            'master_items_categories',
            'item_id',
            'categories_id',
            'item_id',
            'category_id'
        );
    }

    // Single category relationship (for sales products)
    public function category()
    {
        return $this->belongsTo(MasterCategory::class, 'category_id', 'category_id');
    }

    // Relationship dengan item details (untuk harga berbeda per customer type)
    public function itemDetails()
    {
        return $this->hasMany(MasterItemDetail::class, 'item_id', 'item_id');
    }

    // Relationship dengan stock
    public function stocks()
    {
        return $this->hasMany(MasterItemStock::class, 'item_id', 'item_id');
    }

    // Alias for stock relationship (for consistency)
    public function itemStocks()
    {
        return $this->hasMany(MasterItemStock::class, 'item_id', 'item_id');
    }


    // Relationship dengan content (carousel, banner, dll)
    public function contents()
    {
        return $this->hasMany(MasterContent::class, 'item_id', 'item_id');
    }

    // Scope untuk produk yang tersedia (stock > 0)
    public function scopeAvailable($query)
    {
        return $query->whereHas('stocks', function ($q) {
            $q->where('stock', '>', 0);
        });
    }

    // Scope untuk produk aktif
    public function scopeActive($query)
    {
        return $query->where('status_item', 'active');
    }

    // Scope untuk produk berdasarkan kategori
    public function scopeByCategory($query, $categoryId)
    {
        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('category_id', $categoryId);
        });
    }

    // Scope untuk ordering
    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Scope untuk filter produk berdasarkan customer type dan is_reseller_babyspa
    public function scopeForCustomerType($query, $customerTypeId = 1)
    {
        // Customer type 4 adalah Reseller Baby Spa - bisa melihat SEMUA produk (termasuk produk 250ml dan produk biasa)
        if ($customerTypeId == 4) {
            return $query; // Tidak ada filter, tampilkan semua produk
        }
        
        // Customer type 3 adalah Reseller - bisa melihat produk is_reseller_only dan produk biasa, tapi tidak bisa melihat produk is_reseller_babyspa
        if ($customerTypeId == 3 || $customerTypeId == 2) {
            return $query->where(function($q) {
                $q->whereNull('is_reseller_babyspa')
                  ->orWhere('is_reseller_babyspa', 0);
            });
        }
        
        // Customer type 1 (Regular) tidak bisa melihat produk dengan is_reseller_babyspa = 1 
        return $query->where(function($q) {
            $q->whereNull('is_reseller_babyspa')
              ->orWhere('is_reseller_babyspa', 0);
        });
    }

    // Get sell price for specific customer type (default to type 1)
    public function getSellPrice($customerTypeId = 1)
    {
        $detail = $this->itemDetails()->where('customer_type_id', $customerTypeId)->first();
        
        // Return the sell price from master_items_details based on customer type
        // The price in master_items_details is already the final price for that customer type
        if ($detail) {
            return $detail->sell_price;
        }
        
        // Fallback to cost price if no detail found
        return $this->costprice_item;
    }
    
    // Get reseller price (with 30% discount)
    public function getResellerPrice()
    {
        return $this->getSellPrice(2);
    }
    
    // Get regular customer price
    public function getRegularPrice()
    {
        return $this->getSellPrice(1);
    }

    // Get stock quantity
    public function getStockQuantity($inventoryId = 1)
    {
        $stock = $this->stocks()->where('inventory_id', $inventoryId)->first();
        return $stock ? $stock->stock : 0;
    }

    // Accessor untuk format harga
    public function getFormattedPriceAttribute()
    {
        $price = $this->getSellPrice();
        return 'Rp ' . number_format($price, 0, ',', '.');
    }

    // Get related variants based on netweight_item and base name
    public function getRelatedVariants()
    {
        // Extract base name untuk mencari varian (hilangkan ukuran dari nama)
        $baseName = preg_replace('/\s+(10ml|30ml|100m)\s*$/i', '', $this->name_item);
        $baseName = trim($baseName);

        return self::where(function ($query) use ($baseName) {
            $query->where('name_item', 'LIKE', $baseName . '%');
        })
            ->whereNotNull('netweight_item')
            ->where('netweight_item', '!=', '')
            ->where('item_id', '!=', $this->item_id)
            ->orderByRaw("CASE 
            WHEN netweight_item LIKE '%10ml%' OR netweight_item LIKE '%10 ml%' THEN 1 
            WHEN netweight_item LIKE '%30ml%' OR netweight_item LIKE '%30 ml%' THEN 2 
            WHEN netweight_item LIKE '%100ml%' OR netweight_item LIKE '%100 ml%' THEN 3
            ELSE 4 END")
            ->get();
    }

    // Get size display text from netweight_item
    public function getVariantSizeAttribute()
    {
        return $this->netweight_item ?: 'Standard';
    }

    // Accessor untuk status stok
    public function getStockStatusAttribute()
    {
        $stock = $this->getStockQuantity();
        if ($stock <= 0) {
            return 'out_of_stock';
        } elseif ($stock <= 5) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    // Accessor untuk stock (menggunakan stock_item atau relationship stock)
    public function getStockAttribute()
    {
        // Cek apakah ada stock_item langsung di tabel
        if (isset($this->attributes['stock_item']) && $this->attributes['stock_item'] > 0) {
            return $this->attributes['stock_item'];
        }

        // Fallback ke relationship stock
        return $this->getStockQuantity();
    }

    // Accessor untuk unit item (from first stock record or default)
    public function getUnitItemAttribute()
    {
        // You might want to add unit field to master_items table
        // For now, return a default value
        return 'pcs';
    }

    // Accessor untuk image dari picture_item column (menggunakan storage)
    public function getImageAttribute()
    {
        // Cek apakah ada picture_item yang valid
        $pictureItem = $this->getRawOriginal('picture_item') ?? $this->attributes['picture_item'] ?? null;

        if ($pictureItem && !empty(trim($pictureItem))) {
            // 1. Jika picture_item sudah berupa path lengkap dengan http/https, gunakan langsung
            if (strpos($pictureItem, 'http') === 0) {
                return $pictureItem;
            }

            // 2. Jika picture_item adalah Base64 data URL, gunakan langsung
            if (strpos($pictureItem, 'data:image/') === 0) {
                return $pictureItem;
            }

            // 3. Coba dekripsi jika data terenkripsi atau Base64
            $decryptedImage = $this->tryDecryptImage($pictureItem);
            if ($decryptedImage) {
                return $decryptedImage;
            }

            // 4. Jika picture_item adalah Base64 string (tanpa prefix), tambahkan prefix
            if ($this->isBase64String($pictureItem)) {
                return 'data:image/jpeg;base64,' . $pictureItem;
            }

            // Add cache-busting timestamp untuk updated records
            $timestamp = '';
            if ($this->updated_at) {
                $timestamp = '?v=' . $this->updated_at->timestamp;
            }

            // PRIORITY: Check Storage disk first (storage/app/public/images/)
            $disk = Storage::disk('public');
            if ($disk->exists('images/' . $pictureItem)) {
                return Storage::url('images/' . $pictureItem) . $timestamp;
            }

            // Define possible image locations in order of priority
            $possiblePaths = [
                // 1. Direct path if already includes storage/
                $pictureItem,
                // 2. New images location (recommended for new uploads)
                'storage/images/' . $pictureItem,
                // 3. Legacy gentle-baby location for existing products
                'storage/gentle-baby/' . $pictureItem,
                // 4. Legacy public path (for older uploads)
                'images/' . $pictureItem,
                // 5. Legacy sales-products path (for backward compatibility)
                'storage/' . str_replace('sales-products/', '', $pictureItem),
                // 6. Direct path with storage prefix
                'storage/' . $pictureItem,
            ];

            // Check each path in order of priority and return the first existing one
            foreach ($possiblePaths as $relativePath) {
                $fullPath = public_path($relativePath);
                if (file_exists($fullPath)) {
                    // Ensure proper URL generation for Laravel
                    $url = url($relativePath) . $timestamp;

                    // Log successful image resolution for debugging
                    if (config('app.debug')) {
                        Log::info('Image resolved for product', [
                            'item_id' => $this->item_id,
                            'name' => $this->name_item,
                            'picture_item' => $pictureItem,
                            'resolved_path' => $relativePath,
                            'full_url' => $url
                        ]);
                    }

                    return $url;
                }
            }

            // If no file found but picture_item exists, try to construct expected URL
            if (config('app.debug')) {
                Log::warning('Image file not found for product', [
                    'item_id' => $this->item_id,
                    'name' => $this->name_item,
                    'picture_item' => $pictureItem,
                    'searched_paths' => $possiblePaths
                ]);
            }

            // Return expected new path with timestamp (for newly uploaded images from Storage)
            return Storage::url('images/' . $pictureItem) . $timestamp;
        }

        // Fallback to product-based image mapping if no picture_item
        if (config('app.debug')) {
            Log::info('Using fallback image mapping for product', [
                'item_id' => $this->item_id,
                'name' => $this->name_item,
                'picture_item_empty' => empty($pictureItem)
            ]);
        }

        return $this->getProductMainImage();
    }

    // Method untuk mendapatkan gambar utama produk berdasarkan nama
    public function getProductMainImage()
    {
        $productName = strtolower($this->name_item);

        // Mapping gambar berdasarkan nama produk
        $imageMapping = [
            // Gentle Baby Products
            'bye bugs' => 'storage/gentle-baby/bye-bugs.jpg',
            'cough' => 'storage/gentle-baby/cough-flu.jpg',
            'flu' => 'storage/gentle-baby/cough-flu.jpg',
            'deep sleep' => 'storage/gentle-baby/deep-sleep.jpg',
            'gimme food' => 'storage/gentle-baby/gimme-food.jpg',
            'tummy calmer' => 'storage/gentle-baby/tummy-calmer.jpg',
            'ldr booster' => 'storage/gentle-baby/ldr-booster.jpg',
            'joy' => 'storage/gentle-baby/joy.jpg',
            'massage' => 'storage/gentle-baby/massage-your-baby.jpg',
            'imm' => 'storage/gentle-baby/immboost.jpg',

        ];

        // Cari gambar berdasarkan nama produk
        foreach ($imageMapping as $keyword => $imagePath) {
            if (strpos($productName, $keyword) !== false) {
                // Cek apakah file gambar ada
                if (file_exists(public_path($imagePath))) {
                    return asset($imagePath);
                }
            }
        }

        // Default fallback dengan beberapa kemungkinan
        $fallbackImages = [
            'storage/images/placeholder.jpg',
            'storage/gentle-baby/placeholder.jpg',
            'storage/gentle-baby/bye-bugs.jpg', // Gunakan gambar yang sudah ada
        ];

        foreach ($fallbackImages as $fallback) {
            if (file_exists(public_path($fallback))) {
                return asset($fallback);
            }
        }

        // Final fallback - return URL even if file doesn't exist
        return asset('storage/images/placeholder.jpg');
    }

    // Accessor untuk thumbnail URL
    public function getThumbnailUrlAttribute()
    {
        return $this->image;
    }

    // Accessor untuk kategori tunggal (mengambil kategori pertama)
    public function getCategoryAttribute()
    {
        return $this->categories()->first();
    }

    // Method untuk mendapatkan nama kategori
    public function getCategoryName()
    {
        $category = $this->categories()->first();
        return $category ? $category->name_category : 'Umum';
    }

    // Method untuk mendapatkan thumbnail URL berdasarkan nama produk dan ukuran
    public function getThumbnail1Attribute()
    {
        $thumbnail = $this->getRawOriginal('thumbnail_1') ?? $this->attributes['thumbnail_1'] ?? null;

        // Cek apakah thumbnail ada dan valid (bukan no-image atau URL no-image)
        if ($thumbnail && !str_contains($thumbnail, 'no-image')) {
            // Coba dekripsi jika data terenkripsi
            $decryptedImage = $this->tryDecryptImage($thumbnail);
            if ($decryptedImage) {
                return $decryptedImage;
            }

            // PRIORITY: Check Storage disk first (storage/app/public/images/)
            $disk = Storage::disk('public');
            if ($disk->exists('images/' . $thumbnail)) {
                return Storage::url('images/' . $thumbnail);
            }

            $imageUrl = $this->getImageUrl($thumbnail);
            // Jika masih mengarah ke no-image, gunakan product thumbnail
            if (!str_contains($imageUrl, 'no-image')) {
                return $imageUrl;
            }
        }

        // Fallback: coba gunakan picture_item sebagai thumbnail jika ada
        $pictureItem = $this->getRawOriginal('picture_item');
        if ($pictureItem) {
            $decryptedPicture = $this->tryDecryptImage($pictureItem);
            if ($decryptedPicture) {
                return $decryptedPicture;
            }
        }

        return $this->getProductThumbnail(1);
    }

    public function getThumbnail2Attribute()
    {
        $thumbnail = $this->getRawOriginal('thumbnail_2') ?? $this->attributes['thumbnail_2'] ?? null;

        // Cek apakah thumbnail ada dan valid (bukan no-image atau URL no-image)
        if ($thumbnail && !str_contains($thumbnail, 'no-image')) {
            // Coba dekripsi jika data terenkripsi
            $decryptedImage = $this->tryDecryptImage($thumbnail);
            if ($decryptedImage) {
                return $decryptedImage;
            }

            // PRIORITY: Check Storage disk first (storage/app/public/images/)
            $disk = Storage::disk('public');
            if ($disk->exists('images/' . $thumbnail)) {
                return Storage::url('images/' . $thumbnail);
            }

            $imageUrl = $this->getImageUrl($thumbnail);
            // Jika masih mengarah ke no-image, gunakan product thumbnail
            if (!str_contains($imageUrl, 'no-image')) {
                return $imageUrl;
            }
        }

        // Fallback: coba gunakan picture_item sebagai thumbnail jika ada
        $pictureItem = $this->getRawOriginal('picture_item');
        if ($pictureItem) {
            $decryptedPicture = $this->tryDecryptImage($pictureItem);
            if ($decryptedPicture) {
                return $decryptedPicture;
            }
        }

        return $this->getProductThumbnail(2);
    }

    public function getThumbnail3Attribute()
    {
        $thumbnail = $this->getRawOriginal('thumbnail_3') ?? $this->attributes['thumbnail_3'] ?? null;

        // Cek apakah thumbnail ada dan valid (bukan no-image atau URL no-image)
        if ($thumbnail && !str_contains($thumbnail, 'no-image')) {
            // Coba dekripsi jika data terenkripsi
            $decryptedImage = $this->tryDecryptImage($thumbnail);
            if ($decryptedImage) {
                return $decryptedImage;
            }

            // PRIORITY: Check Storage disk first (storage/app/public/images/)
            $disk = Storage::disk('public');
            if ($disk->exists('images/' . $thumbnail)) {
                return Storage::url('images/' . $thumbnail);
            }

            $imageUrl = $this->getImageUrl($thumbnail);
            // Jika masih mengarah ke no-image, gunakan product thumbnail
            if (!str_contains($imageUrl, 'no-image')) {
                return $imageUrl;
            }
        }

        // Fallback: coba gunakan picture_item sebagai thumbnail jika ada
        $pictureItem = $this->getRawOriginal('picture_item');
        if ($pictureItem) {
            $decryptedPicture = $this->tryDecryptImage($pictureItem);
            if ($decryptedPicture) {
                return $decryptedPicture;
            }
        }

        return $this->getProductThumbnail(3);
    }

    // Method untuk mendapatkan thumbnail berdasarkan nama produk dan ukuran
    public function getProductThumbnail($thumbnailNumber = 1)
    {
        $productName = strtolower($this->name_item);

        // Mapping thumbnail berdasarkan nama produk dan ukuran
        $thumbnailMapping = [
            'bye bugs' => [
                'storage/gentle-baby/bye-bugs.jpg',
                'storage/gentle-baby/bye-bugs.jpg',
                'storage/gentle-baby/bye-bugs.jpg'
            ],
            'cough' => [
                'storage/gentle-baby/cough-flu-30-ml.jpg',
                'storage/gentle-baby/cough-flu-100-ml.jpg',
            ],
            'deep sleep' => [
                'storage/gentle-baby/deep-slepp-30ml.jpg',
                'storage/gentle-baby/deep-slepp-100ml.jpg',
            ],
            'gimme food' => [
                'storage/gentle-baby/gimme-food-30ml.jpg',
                'storage/gentle-baby/gimme-food-100ml.jpg',
            ],
            'tummy calmer' => [
                'storage/gentle-baby/tummy-calmer-30ml.jpg',
                'storage/gentle-baby/tummy-calmer-100ml.jpg',
            ],
            'ldr booster' => [
                'storage/gentle-baby/ldr-30ml.jpg',
                'storage/gentle-baby/ldr-100ml.jpg',
            ],
            'joy' => [
                'storage/gentle-baby/joy-30ml.jpg',
                'storage/gentle-baby/joy-100ml.jpg',
            ],
            'massage' => [
                'storage/gentle-baby/MYB-30-ml.jpg',
                'storage/gentle-baby/MYB-100-ml.jpg',
            ],
            'imm' => [
                'storage/gentle-baby/immboost-30ml.jpg',
                'storage/gentle-baby/immboost-100ml.jpg',
            ],
        ];

        // Cari thumbnail berdasarkan nama produk
        foreach ($thumbnailMapping as $keyword => $thumbnails) {
            if (strpos($productName, $keyword) !== false) {
                $index = $thumbnailNumber - 1;
                if (isset($thumbnails[$index])) {
                    return asset($thumbnails[$index]);
                }
            }
        }

        // Default fallback - gunakan gambar utama
        return $this->getProductMainImage();
    }

    // Method helper untuk URL gambar
    public function getImageUrl($imageName)
    {
        if (empty($imageName)) {
            return asset('storage/images/placeholder.jpg');
        }

        // 1. Jika imageName sudah berupa path lengkap dengan http/https, gunakan langsung
        if (strpos($imageName, 'http') === 0) {
            return $imageName;
        }

        // 2. Jika imageName adalah Base64 data URL, gunakan langsung
        if (strpos($imageName, 'data:image/') === 0) {
            return $imageName;
        }

        // 3. Jika imageName adalah Base64 string (tanpa prefix), tambahkan prefix
        if ($this->isBase64String($imageName)) {
            return 'data:image/jpeg;base64,' . $imageName;
        }

        // PRIORITY: Check Storage disk first (storage/app/public/images/)
        $disk = Storage::disk('public');
        if ($disk->exists('images/' . $imageName)) {
            return Storage::url('images/' . $imageName);
        }

        // Define possible image locations in order of priority for thumbnails
        $possiblePaths = [
            // 1. Direct path if already includes storage/
            $imageName,
            // 2. New images location (recommended for new uploads)
            'storage/images/' . $imageName,
            // 3. Legacy gentle-baby location for existing products
            'storage/gentle-baby/' . $imageName,
            // 4. Legacy public path (for older uploads)
            'images/' . $imageName,
        ];

        // Check each path in order of priority and return the first existing one
        foreach ($possiblePaths as $relativePath) {
            $fullPath = public_path($relativePath);
            if (file_exists($fullPath)) {
                $url = url($relativePath);

                // Log successful image resolution for debugging
                if (config('app.debug')) {
                    Log::info('Thumbnail image resolved', [
                        'item_id' => $this->item_id,
                        'original_name' => $imageName,
                        'resolved_path' => $relativePath,
                        'full_url' => $url
                    ]);
                }

                return $url;
            }
        }

        // If no file found but imageName exists, try to construct expected URL
        if (config('app.debug')) {
            Log::warning('Thumbnail image file not found', [
                'item_id' => $this->item_id,
                'image_name' => $imageName,
                'searched_paths' => $possiblePaths
            ]);
        }

        // Return expected new path from Storage (for newly uploaded images)
        return Storage::url('images/' . $imageName);
    }


    // Method untuk memeriksa apakah string adalah Base64
    protected function isBase64String($string)
    {
        // Cek panjang minimum dan karakter Base64
        if (strlen($string) < 50) return false;

        // Cek apakah string hanya mengandung karakter Base64 yang valid
        if (!preg_match('/^[a-zA-Z0-9\/\+=]+$/', $string)) return false;

        // Coba decode dan cek apakah hasilnya adalah image data
        $decoded = base64_decode($string, true);
        if ($decoded === false) return false;

        // Cek magic bytes untuk format gambar umum
        $imageTypes = [
            "\xFF\xD8\xFF" => 'jpeg', // JPEG
            "\x89\x50\x4E\x47" => 'png', // PNG
            "\x47\x49\x46\x38" => 'gif', // GIF
            "\x42\x4D" => 'bmp', // BMP
        ];

        foreach ($imageTypes as $magic => $type) {
            if (strpos($decoded, $magic) === 0) {
                return true;
            }
        }

        return false;
    }

    // Method untuk memeriksa apakah string mungkin terenkripsi
    protected function isPossiblyEncrypted($string)
    {
        // Cek karakteristik umum data terenkripsi:
        // - Panjang tertentu
        // - Mengandung karakter non-printable atau padding yang tidak biasa
        // - Tidak cocok dengan pola Base64 atau file path

        if (strlen($string) < 32) return false;

        // Jika mengandung karakter non-ASCII yang tidak biasa untuk Base64 atau path
        if (preg_match('/[^\x20-\x7E]/', $string)) return true;

        // Jika panjang dan pola menunjukkan kemungkinan enkripsi
        if (strlen($string) > 100 && !preg_match('/^[a-zA-Z0-9\/\+=\-_.]+$/', $string)) return true;

        return false;
    }


    // Method untuk mencoba dekripsi gambar dengan berbagai metode
    protected function tryDecryptImage($imageData)
    {
        if (empty($imageData)) {
            return null;
        }

        // 1. Jika sudah berupa data URL, gunakan langsung
        if (strpos($imageData, 'data:image/') === 0) {
            return $imageData;
        }

        // 2. Coba dekripsi Laravel encryption
        try {
            $decrypted = decrypt($imageData);
            if ($this->isValidImageData($decrypted)) {
                return $this->formatImageData($decrypted);
            }
        } catch (\Exception $e) {
            // Lanjut ke metode berikutnya
        }

        // 3. Coba Base64 decode
        if ($this->isBase64String($imageData)) {
            return 'data:image/jpeg;base64,' . $imageData;
        }

        // 4. Coba decode jika sudah di-encode
        try {
            $decoded = base64_decode($imageData, true);
            if ($decoded !== false && $this->isValidImageData($decoded)) {
                return 'data:image/jpeg;base64,' . base64_encode($decoded);
            }
        } catch (\Exception $e) {
            // Lanjut ke metode berikutnya
        }

        // 5. Coba URL-safe Base64
        try {
            $urlSafeDecoded = str_replace(['-', '_'], ['+', '/'], $imageData);
            if ($this->isBase64String($urlSafeDecoded)) {
                return 'data:image/jpeg;base64,' . $urlSafeDecoded;
            }
        } catch (\Exception $e) {
            // Lanjut
        }

        return null;
    }

    // Method untuk memvalidasi data gambar
    protected function isValidImageData($data)
    {
        if (empty($data)) {
            return false;
        }

        // Cek apakah sudah berupa Base64 string
        if ($this->isBase64String($data)) {
            return true;
        }

        // Cek magic bytes untuk format gambar
        $imageSignatures = [
            "\xFF\xD8\xFF" => 'jpeg',
            "\x89\x50\x4E\x47" => 'png',
            "\x47\x49\x46\x38" => 'gif',
            "\x42\x4D" => 'bmp',
            "\x52\x49\x46\x46" => 'webp'
        ];

        foreach ($imageSignatures as $signature => $format) {
            if (strpos($data, $signature) === 0) {
                return true;
            }
        }

        return false;
    }

    // Method untuk format data gambar menjadi data URL
    protected function formatImageData($imageData)
    {
        if (strpos($imageData, 'data:image/') === 0) {
            return $imageData;
        }

        if ($this->isBase64String($imageData)) {
            return 'data:image/jpeg;base64,' . $imageData;
        }

        // Jika binary data, encode ke Base64
        try {
            return 'data:image/jpeg;base64,' . base64_encode($imageData);
        } catch (\Exception $e) {
            return null;
        }
    }

    // Method untuk mendapatkan gambar produk berdasarkan database dan storage
    public function getProductImages()
    {
        $mainImage = $this->image;

        // Gunakan thumbnail yang tersedia dari accessor yang sudah diperbaiki
        $thumbnails = [];

        // Gunakan accessor yang sudah memperbaiki thumbnail berdasarkan nama produk
        $thumbnail1 = $this->getAttribute('thumbnail_1');
        $thumbnail2 = $this->getAttribute('thumbnail_2');
        $thumbnail3 = $this->getAttribute('thumbnail_3');

        $thumbnails[] = $mainImage;
        $thumbnails[] = $thumbnail1 ?: $mainImage;
        $thumbnails[] = $thumbnail2 ?: $mainImage;
        $thumbnails[] = $thumbnail3 ?: $mainImage;


        return [
            'main' => $mainImage,
            'thumbnails' => $thumbnails
        ];
    }

    /**
     * Get transaction sales details for this product
     */
    public function transactionSalesDetails()
    {
        return $this->hasMany(TransactionSalesDetails::class, 'item_id', 'item_id');
    }

    /**
     * Get order items for this product
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'master_item_id', 'item_id');
    }
}
