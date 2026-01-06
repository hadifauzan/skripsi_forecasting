<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterItem;
use App\Models\MasterCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SalesProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MasterItem::with(['categories', 'itemDetails']); // Eager load categories and pricing
        
        // Search functionality - enhanced search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name_item', 'LIKE', "%{$search}%")
                  ->orWhere('description_item', 'LIKE', "%{$search}%")
                  ->orWhere('code_item', 'LIKE', "%{$search}%")
                  ->orWhere('ingredient_item', 'LIKE', "%{$search}%");
            });
        }
        
        // Category filter - using relationship
        if ($request->filled('category')) {
            $categoryId = $request->category;
            $query->whereHas('categories', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status_item', $request->status);
        }
        
        // Price range filter
        if ($request->filled('price_min')) {
            $query->where('costprice_item', '>=', $request->price_min);
        }
        
        if ($request->filled('price_max')) {
            $query->where('costprice_item', '<=', $request->price_max);
        }
        
        // Sorting options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['name_item', 'costprice_item', 'created_at', 'updated_at'];
        $allowedOrders = ['asc', 'desc'];
        
        if (in_array($sortBy, $allowedSorts) && in_array($sortOrder, $allowedOrders)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $perPage = $request->get('per_page', 10);
        $allowedPerPage = [5, 10, 15, 25, 50];
        
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }
        
        $products = $query->paginate($perPage)->appends($request->query());
        
        // Get only specific categories for filter dropdown
        $allowedCategories = ['Gentle Baby', 'Mamina', 'Nyam', 'Healo'];
        $categories = MasterCategory::whereIn('name_category', $allowedCategories)
                                  ->orderBy('name_category')
                                  ->get();
        
        // Get filter statistics
        $stats = [
            'total' => MasterItem::count(),
            'active' => MasterItem::where('status_item', 'active')->count(),
            'inactive' => MasterItem::where('status_item', 'inactive')->count(),
        ];
        
        return view('admin.sales.products.index', compact('products', 'categories', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $allowedCategories = ['Gentle Baby', 'Mamina', 'Nyam', 'Healo'];
        $categories = MasterCategory::whereIn('name_category', $allowedCategories)
                                  ->orderBy('name_category')
                                  ->get();
        return view('admin.sales.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_item' => 'required|string|max:255|unique:master_items,name_item',
            'code_item' => 'nullable|string|max:50|unique:master_items,code_item',
            'description_item' => 'nullable|string|max:2000',
            'ingredient_item' => 'nullable|string|max:1500',
            'contain_item' => 'nullable|string|max:1000',
            'costprice_item' => 'required|numeric|min:0|max:999999999.99',
            'netweight_item' => 'nullable|string|max:50',
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'required|exists:master_categories,category_id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'thumbnail_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'thumbnail_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'thumbnail_3' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status_item' => 'required|in:active,inactive',
            // Pricing for regular customer only
            'cost_price_1' => 'required|numeric|min:0|max:999999999.99',
            'sell_price_1' => 'required|numeric|min:0|max:999999999.99',
            // Stock quantity
            'stock_quantity' => 'required|integer|min:0|max:999999'
        ], [
            'name_item.required' => 'Nama produk wajib diisi.',
            'name_item.unique' => 'Nama produk sudah ada, gunakan nama lain.',
            'name_item.max' => 'Nama produk maksimal 255 karakter.',
            'code_item.unique' => 'Kode produk sudah digunakan, gunakan kode lain.',
            'code_item.max' => 'Kode produk maksimal 50 karakter.',
            'description_item.max' => 'Deskripsi maksimal 2000 karakter.',
            'ingredient_item.max' => 'Komposisi/ingredients maksimal 1500 karakter.',
            'contain_item.max' => 'Isi kemasan maksimal 1000 karakter.',
            'costprice_item.required' => 'Harga produk wajib diisi.',
            'costprice_item.numeric' => 'Harga harus berupa angka.',
            'costprice_item.min' => 'Harga tidak boleh kurang dari 0.',
            'category_ids.required' => 'Minimal pilih satu kategori produk.',
            'category_ids.array' => 'Format kategori tidak valid.',
            'category_ids.min' => 'Minimal pilih satu kategori produk.',
            'category_ids.*.exists' => 'Salah satu kategori yang dipilih tidak valid.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPEG, PNG, JPG, GIF, atau WebP.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            'thumbnail_1.image' => 'Thumbnail 1 harus berupa gambar.',
            'thumbnail_1.mimes' => 'Format thumbnail 1 harus JPEG, PNG, JPG, GIF, atau WebP.',
            'thumbnail_1.max' => 'Ukuran thumbnail 1 maksimal 2MB.',
            'thumbnail_2.image' => 'Thumbnail 2 harus berupa gambar.',
            'thumbnail_2.mimes' => 'Format thumbnail 2 harus JPEG, PNG, JPG, GIF, atau WebP.',
            'thumbnail_2.max' => 'Ukuran thumbnail 2 maksimal 2MB.',
            'thumbnail_3.image' => 'Thumbnail 3 harus berupa gambar.',
            'thumbnail_3.mimes' => 'Format thumbnail 3 harus JPEG, PNG, JPG, GIF, atau WebP.',
            'thumbnail_3.max' => 'Ukuran thumbnail 3 maksimal 2MB.',
            'status_item.required' => 'Status produk wajib dipilih.',
            'status_item.in' => 'Status produk tidak valid.',
            // Pricing validation messages
            'cost_price_1.required' => 'Cost price wajib diisi.',
            'cost_price_1.numeric' => 'Cost price harus berupa angka.',
            'cost_price_1.min' => 'Cost price tidak boleh kurang dari 0.',
            'sell_price_1.required' => 'Sell price wajib diisi.',
            'sell_price_1.numeric' => 'Sell price harus berupa angka.',
            'sell_price_1.min' => 'Sell price tidak boleh kurang dari 0.',
            // Stock validation messages
            'stock_quantity.required' => 'Jumlah stok wajib diisi.',
            'stock_quantity.integer' => 'Jumlah stok harus berupa angka bulat.',
            'stock_quantity.min' => 'Jumlah stok tidak boleh kurang dari 0.',
            'stock_quantity.max' => 'Jumlah stok maksimal 999999 unit.',
        ]);

        try {
            $data = [
                'name_item' => $request->name_item,
                'code_item' => $request->code_item ?: 'PRD-' . strtoupper(Str::random(8)),
                'description_item' => $request->description_item,
                'ingredient_item' => $request->ingredient_item,
                'contain_item' => $request->contain_item,
                'costprice_item' => $request->costprice_item,
                'netweight_item' => $request->netweight_item,
                'status_item' => $request->status_item,
                'company_id' => 1, // Default company ID
            ];
            
            // Handle image upload
            if ($request->hasFile('image')) {
                Log::info('Processing image upload for new product');
                
                $image = $request->file('image');
                
                // Validate uploaded file
                if (!$image->isValid()) {
                    Log::error('Invalid uploaded file');
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'File gambar tidak valid. Silakan coba lagi.');
                }
                
                // Create images directory if it doesn't exist
                $uploadPath = storage_path('app/public/images');
                if (!is_dir($uploadPath)) {
                    if (!mkdir($uploadPath, 0755, true)) {
                        Log::error('Failed to create images directory: ' . $uploadPath);
                        return redirect()->back()
                            ->withInput()
                            ->with('error', 'Gagal membuat direktori gambar. Periksa permission folder.');
                    }
                    Log::info('Created images directory: ' . $uploadPath);
                }
                
                // Check if directory is writable
                if (!is_writable($uploadPath)) {
                    Log::error('Images directory not writable: ' . $uploadPath);
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Direktori gambar tidak dapat ditulis. Periksa permission folder.');
                }
                
                // Generate unique filename to avoid conflicts
                $timestamp = time();
                $randomString = Str::random(6);
                $sanitizedName = Str::slug($request->name_item);
                $extension = $image->getClientOriginalExtension();
                $imageName = $timestamp . '_' . $randomString . '_' . $sanitizedName . '.' . $extension;
                
                Log::info('Generated filename for new product: ' . $imageName);
                
                try {
                    // Use Laravel's Storage facade for more reliable file handling
                    $disk = Storage::disk('public');
                    
                    // Store the file using Storage disk
                    $storedPath = $disk->putFileAs('images', $image, $imageName);
                    
                    if (!$storedPath) {
                        throw new \Exception('Storage disk putFileAs returned false');
                    }
                    
                    Log::info('File stored using disk, returned path: ' . $storedPath);
                    
                    // Verify file exists using Storage disk
                    if (!$disk->exists($storedPath)) {
                        throw new \Exception('File not found in storage disk after upload: ' . $storedPath);
                    }
                    
                    // Get the full file system path for additional verification
                    $fullPath = $disk->path($storedPath);
                    Log::info('Full file system path: ' . $fullPath);
                    
                    // Double check with file_exists on the full path
                    if (!file_exists($fullPath)) {
                        throw new \Exception('File not found on filesystem: ' . $fullPath);
                    }
                    
                    Log::info('File successfully verified at: ' . $fullPath);
                    
                    // Set image name in data
                    $data['picture_item'] = $imageName;
                    
                } catch (\Exception $e) {
                    Log::error('Image upload failed: ' . $e->getMessage());
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Gagal mengupload gambar: ' . $e->getMessage());
                }
            }

            // Handle thumbnail uploads
            $thumbnailFields = ['thumbnail_1', 'thumbnail_2', 'thumbnail_3'];
            foreach ($thumbnailFields as $field) {
                if ($request->hasFile($field)) {
                    Log::info('Processing ' . $field . ' upload for new product');
                    
                    $thumbnailImage = $request->file($field);
                    
                    if (!$thumbnailImage->isValid()) {
                        Log::error('Invalid ' . $field . ' file');
                        continue; // Skip invalid thumbnails
                    }
                    
                    try {
                        // Generate unique filename for thumbnail
                        $timestamp = time();
                        $randomString = Str::random(6);
                        $sanitizedName = Str::slug($request->name_item);
                        $extension = $thumbnailImage->getClientOriginalExtension();
                        $thumbnailName = $timestamp . '_' . $randomString . '_' . $sanitizedName . '_' . $field . '.' . $extension;
                        
                        Log::info('Generated ' . $field . ' filename: ' . $thumbnailName);
                        
                        // Store thumbnail using Laravel Storage
                        $disk = Storage::disk('public');
                        $storedPath = $disk->putFileAs('images', $thumbnailImage, $thumbnailName);
                        
                        if ($storedPath && $disk->exists($storedPath)) {
                            $data[$field] = $thumbnailName;
                            Log::info('Successfully stored ' . $field . ': ' . $thumbnailName);
                        } else {
                            Log::warning('Failed to store ' . $field . ', skipping');
                        }
                        
                    } catch (\Exception $e) {
                        Log::error($field . ' upload failed: ' . $e->getMessage());
                        // Continue with other thumbnails even if one fails
                    }
                }
            }

            $product = MasterItem::create($data);

            // Create pricing details for regular customer only
            \App\Models\MasterItemDetail::create([
                'item_id' => $product->item_id,
                'customer_type_id' => 1, // Regular Customer
                'cost_price' => $request->cost_price_1,
                'sell_price' => $request->sell_price_1
            ]);

            // Attach categories via pivot table
            if ($request->has('category_ids') && is_array($request->category_ids) && count($request->category_ids) > 0) {
                // Insert multiple categories into pivot table
                $categoryData = [];
                foreach ($request->category_ids as $categoryId) {
                    $categoryData[] = [
                        'item_id' => (string) $product->item_id,
                        'categories_id' => $categoryId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                DB::table('master_items_categories')->insert($categoryData);
                Log::info('Attached ' . count($categoryData) . ' categories to product: ' . $product->item_id);
            }

            // Create stock entry for default inventory
            $defaultInventoryId = 1; // Use default inventory ID
            
            // Ensure we have an inventory record (create default if none exists)
            $inventory = DB::table('master_inventories')->where('inventory_id', $defaultInventoryId)->first();
            if (!$inventory) {
                // Create default inventory if none exists
                DB::table('master_inventories')->insert([
                    'inventory_id' => $defaultInventoryId,
                    'branch_id' => 1, // Default branch
                    'name_inventory' => 'Default Inventory',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                Log::info('Created default inventory');
            }

            // Create stock entry
            \App\Models\MasterItemStock::create([
                'item_id' => $product->item_id,
                'inventory_id' => $defaultInventoryId,
                'stock' => $request->stock_quantity,
            ]);

            Log::info('Product created successfully', ['product_id' => $product->item_id, 'name' => $product->name_item]);

            return redirect()->route('admin.sales.products.index')
                ->with('success', 'Produk "' . $product->name_item . '" berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('Failed to create product', ['error' => $e->getMessage(), 'request' => $request->all()]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan produk. Silakan coba lagi.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterItem $product)
    {
        $allowedCategories = ['Gentle Baby', 'Mamina', 'Nyam', 'Healo'];
        $categories = MasterCategory::whereIn('name_category', $allowedCategories)
                                  ->orderBy('name_category')
                                  ->get();
        
        // Load pricing details with customer types and stock data
        $product->load('itemDetails.customerType', 'itemStocks');
        
        // Store the previous URL in session (the page that led to edit page)
        if (!session()->has('previous_url')) {
            session(['previous_url' => url()->previous()]);
        }
        
        return view('admin.sales.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\MasterItem $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, MasterItem $product)
    {
        /** @var \Illuminate\Http\Request $request */
        /** @var \App\Models\MasterItem $product */
        
        // Debug: Log the incoming request
        Log::info('=== UPDATE REQUEST DEBUG ===');
        Log::info('Product ID: ' . $product->item_id);
        Log::info('Request method: ' . $request->method());
        Log::info('Has file image: ' . ($request->hasFile('image') ? 'YES' : 'NO'));
        Log::info('All request data: ', $request->all());
        Log::info('Files: ', $request->allFiles());
        
        $request->validate([
            'name_item' => 'required|string|max:255|unique:master_items,name_item,' . $product->item_id . ',item_id',
            'code_item' => 'nullable|string|max:50|unique:master_items,code_item,' . $product->item_id . ',item_id',
            'description_item' => 'nullable|string|max:2000',
            'ingredient_item' => 'nullable|string|max:1500',
            'contain_item' => 'nullable|string|max:1000',
            'costprice_item' => 'required|numeric|min:0|max:999999999.99',
            'netweight_item' => 'nullable|string|max:50',
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'required|exists:master_categories,category_id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'thumbnail_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'thumbnail_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'thumbnail_3' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status_item' => 'required|in:active,inactive',
            // Pricing for regular customer only
            'cost_price_1' => 'required|numeric|min:0|max:999999999.99',
            'sell_price_1' => 'required|numeric|min:0|max:999999999.99',
            // Stock quantity
            'stock_quantity' => 'required|integer|min:0|max:999999'
        ], [
            'name_item.required' => 'Nama produk wajib diisi.',
            'name_item.unique' => 'Nama produk sudah ada, gunakan nama lain.',
            'name_item.max' => 'Nama produk maksimal 255 karakter.',
            'code_item.unique' => 'Kode produk sudah digunakan, gunakan kode lain.',
            'code_item.max' => 'Kode produk maksimal 50 karakter.',
            'description_item.max' => 'Deskripsi maksimal 2000 karakter.',
            'ingredient_item.max' => 'Komposisi/ingredients maksimal 1500 karakter.',
            'contain_item.max' => 'Isi kemasan maksimal 1000 karakter.',
            'costprice_item.required' => 'Harga produk wajib diisi.',
            'costprice_item.numeric' => 'Harga harus berupa angka.',
            'costprice_item.min' => 'Harga tidak boleh kurang dari 0.',
            'costprice_item.max' => 'Harga terlalu besar.',
            'netweight_item.max' => 'Berat produk maksimal 50 karakter.',
            'category_ids.required' => 'Minimal pilih satu kategori produk.',
            'category_ids.array' => 'Format kategori tidak valid.',
            'category_ids.min' => 'Minimal pilih satu kategori produk.',
            'category_ids.*.exists' => 'Salah satu kategori yang dipilih tidak valid.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPEG, PNG, JPG, GIF, atau WebP.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            'status_item.required' => 'Status produk wajib dipilih.',
            'status_item.in' => 'Status produk harus aktif atau nonaktif.',
            // Pricing validation messages
            'cost_price_1.required' => 'Cost price wajib diisi.',
            'cost_price_1.numeric' => 'Cost price harus berupa angka.',
            'cost_price_1.min' => 'Cost price tidak boleh kurang dari 0.',
            'sell_price_1.required' => 'Sell price wajib diisi.',
            'sell_price_1.numeric' => 'Sell price harus berupa angka.',
            'sell_price_1.min' => 'Sell price tidak boleh kurang dari 0.',
            // Stock validation messages
            'stock_quantity.required' => 'Jumlah stok wajib diisi.',
            'stock_quantity.integer' => 'Jumlah stok harus berupa angka bulat.',
            'stock_quantity.min' => 'Jumlah stok tidak boleh kurang dari 0.',
            'stock_quantity.max' => 'Jumlah stok maksimal 999999 unit.',
        ]);

        // Use database transaction to ensure data consistency
        DB::beginTransaction();
        
        try {
            // Prepare data for update - ensure all fields are properly handled
            $data = [
                'name_item' => trim($request->name_item),
                'code_item' => $request->filled('code_item') ? trim($request->code_item) : $product->code_item,
                'description_item' => $request->filled('description_item') ? trim($request->description_item) : $product->description_item,
                'ingredient_item' => $request->filled('ingredient_item') ? trim($request->ingredient_item) : $product->ingredient_item,
                'contain_item' => $request->filled('contain_item') ? trim($request->contain_item) : $product->contain_item,
                'costprice_item' => $request->costprice_item,
                'netweight_item' => $request->filled('netweight_item') ? trim($request->netweight_item) : $product->netweight_item,
                'status_item' => $request->status_item,
                'updated_at' => now(), // Force update timestamp
            ];
            
            Log::info('Starting product update for: ' . $product->item_id);
            Log::info('Current picture_item: ' . $product->picture_item);
            
            // Handle image upload
            if ($request->hasFile('image')) {
                Log::info('Processing image upload for product: ' . $product->item_id);
                
                $image = $request->file('image');
                
                // Validate uploaded file
                if (!$image->isValid()) {
                    Log::error('Invalid uploaded file');
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'File gambar tidak valid. Silakan coba lagi.');
                }
                
                // Store old image name for reference
                $oldImageName = $product->picture_item;
                
                // Create images directory if it doesn't exist
                $uploadPath = storage_path('app/public/images');
                if (!is_dir($uploadPath)) {
                    if (!mkdir($uploadPath, 0755, true)) {
                        Log::error('Failed to create images directory: ' . $uploadPath);
                        return redirect()->back()
                            ->withInput()
                            ->with('error', 'Gagal membuat direktori gambar. Periksa permission folder.');
                    }
                    Log::info('Created images directory: ' . $uploadPath);
                }
                
                // Check if directory is writable
                if (!is_writable($uploadPath)) {
                    Log::error('Images directory not writable: ' . $uploadPath);
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Direktori gambar tidak dapat ditulis. Periksa permission folder.');
                }
                
                // Generate unique filename to avoid conflicts
                $timestamp = time();
                $randomString = Str::random(6);
                $sanitizedName = Str::slug($request->name_item);
                $extension = $image->getClientOriginalExtension();
                $imageName = $timestamp . '_' . $randomString . '_' . $sanitizedName . '.' . $extension;
                
                Log::info('Generated filename: ' . $imageName);
                
                // Log current working directory and paths for debugging
                Log::info('Current working directory: ' . getcwd());
                Log::info('Storage path: ' . storage_path());
                Log::info('App path: ' . app_path());
                Log::info('Expected image directory: ' . storage_path('app/public/images'));
                
                try {
                    // Use multiple approaches for more reliable file handling
                    $destinationPath = storage_path('app/public/images/' . $imageName);
                    
                    Log::info('=== FILE UPLOAD DEBUG ===');
                    Log::info('Destination path: ' . $destinationPath);
                    Log::info('Temporary file: ' . $image->getPathname());
                    Log::info('Temporary file exists: ' . (file_exists($image->getPathname()) ? 'YES' : 'NO'));
                    Log::info('Temporary file size: ' . filesize($image->getPathname()) . ' bytes');
                    Log::info('Original name: ' . $image->getClientOriginalName());
                    Log::info('MIME type: ' . $image->getMimeType());
                    
                    $uploadSuccess = false;
                    
                    // Method 1: Try Laravel Storage facade first (recommended approach)
                    Log::info('Trying Storage facade method...');
                    $disk = Storage::disk('public');
                    $storedPath = $disk->putFileAs('images', $image, $imageName);
                    
                    if ($storedPath && $disk->exists($storedPath)) {
                        Log::info('✅ SUCCESS: File stored using Storage facade: ' . $storedPath);
                        $finalPath = $disk->path($storedPath);
                        Log::info('Final file path: ' . $finalPath);
                        Log::info('Final file size: ' . filesize($finalPath) . ' bytes');
                        $uploadSuccess = true;
                        
                    } else {
                        // Method 2: Try direct move as fallback
                        Log::warning('Storage facade failed, trying direct move...');
                        
                        try {
                            $moved = $image->move(storage_path('app/public/images'), $imageName);
                            
                            if ($moved && file_exists($destinationPath)) {
                                Log::info('✅ SUCCESS: File moved using direct move(): ' . $destinationPath);
                                Log::info('Final file size: ' . filesize($destinationPath) . ' bytes');
                                $uploadSuccess = true;
                            } else {
                                Log::error('❌ Direct move failed - file not found at destination');
                            }
                        } catch (\Exception $moveEx) {
                            Log::error('❌ Direct move threw exception: ' . $moveEx->getMessage());
                        }
                    }
                    
                    if ($uploadSuccess) {
                        // Update database with new image name
                        $data['picture_item'] = $imageName;
                        Log::info('✅ Image name added to data for database update: ' . $imageName);
                    } else {
                        throw new \Exception('All upload methods failed');
                    }
                    
                    // Now delete old image if exists (after new image is confirmed)
                    if ($oldImageName) {
                        Log::info('Starting cleanup of old image: ' . $oldImageName);
                        $deletedCount = 0;
                        
                        // Use Storage disk for reliable cleanup
                        $cleanupDisk = Storage::disk('public');
                        $possibleStoragePaths = [
                            'images/' . $oldImageName,
                            'images/' . basename($oldImageName),
                            'sales-products/' . $oldImageName,
                            'sales-products/' . basename($oldImageName),
                            'gentle-baby/' . $oldImageName,
                            'gentle-baby/' . basename($oldImageName),
                            $oldImageName // Direct filename
                        ];
                        
                        foreach ($possibleStoragePaths as $path) {
                            if ($cleanupDisk->exists($path)) {
                                try {
                                    $cleanupDisk->delete($path);
                                    Log::info('Successfully deleted old image via storage disk: ' . $path);
                                    $deletedCount++;
                                } catch (\Exception $e) {
                                    Log::warning('Failed to delete old image via storage disk: ' . $path . ' - ' . $e->getMessage());
                                }
                            }
                        }
                        
                        // Also try direct file system paths as fallback
                        $possibleFilePaths = [
                            storage_path('app/public/images/' . $oldImageName),
                            storage_path('app/public/images/' . basename($oldImageName)),
                            storage_path('app/public/sales-products/' . $oldImageName),
                            storage_path('app/public/gentle-baby/' . $oldImageName),
                            public_path('storage/images/' . $oldImageName),
                            public_path('storage/sales-products/' . $oldImageName),
                            public_path('storage/gentle-baby/' . $oldImageName)
                        ];
                        
                        foreach ($possibleFilePaths as $path) {
                            if (file_exists($path) && is_file($path)) {
                                try {
                                    unlink($path);
                                    Log::info('Successfully deleted old image via filesystem: ' . $path);
                                    $deletedCount++;
                                } catch (\Exception $e) {
                                    Log::warning('Failed to delete old image via filesystem: ' . $path . ' - ' . $e->getMessage());
                                }
                            }
                        }
                        
                        if ($deletedCount > 0) {
                            Log::info('Total old image files deleted: ' . $deletedCount);
                        } else {
                            Log::warning('No old image files found for deletion: ' . $oldImageName);
                        }
                    }
                    
                } catch (\Exception $e) {
                    Log::error('Error storing image: ' . $e->getMessage());
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Gagal menyimpan gambar: ' . $e->getMessage());
                }
            }

            // Handle thumbnail uploads
            $thumbnailFields = ['thumbnail_1', 'thumbnail_2', 'thumbnail_3'];
            foreach ($thumbnailFields as $field) {
                if ($request->hasFile($field)) {
                    Log::info('Processing ' . $field . ' upload for product: ' . $product->item_id);
                    
                    $thumbnailImage = $request->file($field);
                    
                    if (!$thumbnailImage->isValid()) {
                        Log::error('Invalid ' . $field . ' file for product: ' . $product->item_id);
                        continue; // Skip invalid thumbnails
                    }
                    
                    // Store old thumbnail name for cleanup
                    $oldThumbnailName = $product->$field;
                    
                    try {
                        // Generate unique filename for thumbnail
                        $timestamp = time();
                        $randomString = Str::random(6);
                        $sanitizedName = Str::slug($request->name_item);
                        $extension = $thumbnailImage->getClientOriginalExtension();
                        $thumbnailName = $timestamp . '_' . $randomString . '_' . $sanitizedName . '_' . $field . '.' . $extension;
                        
                        Log::info('Generated ' . $field . ' filename: ' . $thumbnailName);
                        
                        // Store thumbnail using Laravel Storage
                        $disk = Storage::disk('public');
                        $storedPath = $disk->putFileAs('images', $thumbnailImage, $thumbnailName);
                        
                        if ($storedPath && $disk->exists($storedPath)) {
                            $data[$field] = $thumbnailName;
                            Log::info('Successfully stored ' . $field . ': ' . $thumbnailName);
                            
                            // Cleanup old thumbnail if exists
                            if ($oldThumbnailName) {
                                Log::info('Starting cleanup of old ' . $field . ': ' . $oldThumbnailName);
                                
                                $possiblePaths = [
                                    'images/' . $oldThumbnailName,
                                    'images/' . basename($oldThumbnailName),
                                    $oldThumbnailName
                                ];
                                
                                foreach ($possiblePaths as $path) {
                                    if ($disk->exists($path)) {
                                        try {
                                            $disk->delete($path);
                                            Log::info('Successfully deleted old ' . $field . ': ' . $path);
                                            break;
                                        } catch (\Exception $e) {
                                            Log::warning('Failed to delete old ' . $field . ': ' . $path . ' - ' . $e->getMessage());
                                        }
                                    }
                                }
                            }
                            
                        } else {
                            Log::warning('Failed to store ' . $field . ', skipping');
                        }
                        
                    } catch (\Exception $e) {
                        Log::error($field . ' upload failed: ' . $e->getMessage());
                        // Continue with other thumbnails even if one fails
                    }
                }
            }

            // Update the product using Eloquent ORM
            Log::info('Updating product with data:', $data);
            Log::info('Product before update:', $product->toArray());
            
            // Update the product
            $updated = $product->update($data);
            
            if (!$updated) {
                throw new \Exception('Failed to update product data');
            }
            
            // Refresh to get updated data
            $product->refresh();
            Log::info('Product after update:', $product->toArray());
            Log::info('Product updated successfully: ' . $product->item_id);
            
            // Update pricing details for regular customer only
            Log::info('Updating pricing for product: ' . $product->item_id, [
                'cost_price' => $request->cost_price_1,
                'sell_price' => $request->sell_price_1
            ]);
            
            $pricingUpdated = \App\Models\MasterItemDetail::updateOrCreate(
                [
                    'item_id' => $product->item_id,
                    'customer_type_id' => 1 // Regular Customer only
                ],
                [
                    'cost_price' => $request->cost_price_1,
                    'sell_price' => $request->sell_price_1
                ]
            );
            
            Log::info('Pricing updated for product: ' . $product->item_id, $pricingUpdated->toArray());                // Update categories via pivot table
                if ($request->has('category_ids') && is_array($request->category_ids) && count($request->category_ids) > 0) {
                    // Delete existing category relationships
                    DB::table('master_items_categories')
                      ->where('item_id', (string) $product->item_id)
                      ->delete();
                    
                    // Insert new category relationships
                    $categoryData = [];
                    foreach ($request->category_ids as $categoryId) {
                        $categoryData[] = [
                            'item_id' => (string) $product->item_id,
                            'categories_id' => $categoryId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    DB::table('master_items_categories')->insert($categoryData);
                    Log::info('Updated ' . count($categoryData) . ' categories for product: ' . $product->item_id);
                }

                // Update stock for default inventory
                $defaultInventoryId = 1;
                
                // Ensure we have an inventory record (create default if none exists)
                $inventory = DB::table('master_inventories')->where('inventory_id', $defaultInventoryId)->first();
                if (!$inventory) {
                    // Create default inventory if none exists
                    DB::table('master_inventories')->insert([
                        'inventory_id' => $defaultInventoryId,
                        'branch_id' => 1, // Default branch
                        'name_inventory' => 'Default Inventory',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    Log::info('Created default inventory for update');
                }
                
                // Update or create stock entry
                Log::info('Updating stock for product: ' . $product->item_id, [
                    'inventory_id' => $defaultInventoryId,
                    'stock' => $request->stock_quantity
                ]);
                
                $stockUpdated = \App\Models\MasterItemStock::updateOrCreate(
                    [
                        'item_id' => $product->item_id,
                        'inventory_id' => $defaultInventoryId
                    ],
                    [
                        'stock' => $request->stock_quantity
                    ]
                );
                
                Log::info('Stock updated for product: ' . $product->item_id, $stockUpdated->toArray());
                
                Log::info('Product updated successfully: ' . $product->item_id);
                
                // Commit the transaction
                DB::commit();
                
                // Commit the transaction
            DB::commit();
            
            Log::info('Product update completed successfully: ' . $product->item_id);
            
            // Get the previous URL from session and remove it
            $previousUrl = session()->pull('previous_url', route('admin.sales.products.index'));
            
            // Redirect back to previous page with success message
            return redirect($previousUrl)
                ->with('success', 'Produk "' . $product->name_item . '" berhasil diperbarui!' . 
                       ($request->hasFile('image') ? ' Gambar telah diganti.' : ''));
            
        } catch (\Exception $e) {
            // Rollback transaction on exception
            DB::rollBack();
            
            // Clean up uploaded file if any
            if (isset($data['picture_item']) && $data['picture_item']) {
                $uploadedFile = storage_path('app/public/images/' . $data['picture_item']);
                if (file_exists($uploadedFile)) {
                    unlink($uploadedFile);
                    Log::info('Deleted uploaded file due to exception: ' . $uploadedFile);
                }
            }
            
            Log::error('Error updating product: ' . $e->getMessage(), [
                'product_id' => $product->item_id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param \App\Models\MasterItem $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(MasterItem $product)
    {
        /** @var \App\Models\MasterItem $product */
        
        // Delete image if exists
        if ($product->picture_item) {
            // Handle different path formats
            if (strpos($product->picture_item, 'sales-products/') === 0) {
                // Legacy sales-products path
                $imagePath = storage_path('app/public/' . $product->picture_item);
            } else {
                // Current images path (filename only)
                $imagePath = storage_path('app/public/images/' . $product->picture_item);
            }
            
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $product->delete();

        return redirect()->route('admin.sales.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * Toggle product status
     * 
     * @param \App\Models\MasterItem $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(MasterItem $product)
    {
        /** @var \App\Models\MasterItem $product */
        
        $product->status = $product->status === 'active' ? 'inactive' : 'active';
        $product->status_item = $product->status; // Sync both status fields
        $product->save();

        $status = $product->status === 'active' ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('admin.sales.products.index')
            ->with('success', "Produk \"{$product->name_item}\" berhasil {$status}!");
    }

    /**
     * Handle bulk actions
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function bulk(Request $request)
    {
        /** @var \Illuminate\Http\Request $request */
        
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'selected_products' => 'required|array|min:1',
            'selected_products.*' => 'exists:master_items,item_id'
        ]);

        try {
            $productIds = $request->selected_products;
            $action = $request->action;
            $count = count($productIds);

            switch ($action) {
                case 'activate':
                    MasterItem::whereIn('item_id', $productIds)
                        ->update(['status' => 'active', 'status_item' => 'active']);
                    $message = "{$count} produk berhasil diaktifkan";
                    break;

                case 'deactivate':
                    MasterItem::whereIn('item_id', $productIds)
                        ->update(['status' => 'inactive', 'status_item' => 'inactive']);
                    $message = "{$count} produk berhasil dinonaktifkan";
                    break;

                case 'delete':
                    // Delete associated images first
                    $products = MasterItem::whereIn('item_id', $productIds)->get();
                    foreach ($products as $product) {
                        if ($product->picture_item && Storage::exists(str_replace('storage/', 'public/', $product->picture_item))) {
                            Storage::delete(str_replace('storage/', 'public/', $product->picture_item));
                        }
                    }
                    
                    MasterItem::whereIn('item_id', $productIds)->delete();
                    $message = "{$count} produk berhasil dihapus";
                    break;
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'count' => $count
                ]);
            }

            return redirect()->route('admin.sales.products.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Bulk action error: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memproses aksi bulk'
                ], 500);
            }

            return redirect()->route('admin.sales.products.index')
                ->with('error', 'Terjadi kesalahan saat memproses aksi bulk');
        }
    }

    /**
     * Duplicate a product
     * 
     * @param \App\Models\MasterItem $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicate(MasterItem $product)
    {
        /** @var \App\Models\MasterItem $product */
        
        $newProduct = $product->replicate();
        $newProduct->name_item = $product->name_item . ' (Copy)';
        $newProduct->code_item = 'PRD-' . strtoupper(Str::random(8));
        $newProduct->status = 'inactive';
        $newProduct->status_item = 'inactive';
        $newProduct->save();

        return redirect()->route('admin.sales.products.edit', $newProduct->item_id)
            ->with('success', 'Produk berhasil diduplikasi! Silakan edit informasi produk baru.');
    }
}
