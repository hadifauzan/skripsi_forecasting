@extends('layouts.admin.app')

@section('title', 'Edit Produk')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="mb-4 sm:mb-0">
                <h1 class="page-title text-3xl text-[#6C63FF] flex items-center mb-2">
                    <svg class="w-8 h-8 mr-3 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Produk
                </h1>
                <p class="page-subtitle text-[#6C63FF] text-lg ml-11">Edit informasi produk: {{ $product->name_item }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ session('previous_url', route('admin.sales.products.index')) }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors duration-200 shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div> 
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Produk</h3>
                    <p class="text-sm text-gray-600 mt-1">Edit detail informasi produk</p>
                </div>
                
                <form action="{{ route('admin.sales.products.update', $product->item_id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Nama Produk -->
                    <div class="space-y-2">
                        <label for="name_item" class="block text-sm font-medium text-gray-700">
                            Nama Produk <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name_item" id="name_item" 
                               value="{{ old('name_item', $product->name_item) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name_item') border-red-500 @enderror"
                               placeholder="Masukkan nama produk" required>
                        @error('name_item')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kode Produk -->
                    <div class="space-y-2">
                        <label for="code_item" class="block text-sm font-medium text-gray-700">
                            Kode Produk
                        </label>
                        <input type="text" name="code_item" id="code_item" 
                               value="{{ old('code_item', $product->code_item) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('code_item') border-red-500 @enderror"
                               placeholder="Contoh: PRD-001, GB-COUGH-10ML, dll..." 
                               maxlength="50">
                        <small class="text-gray-500">Kode unik untuk identifikasi produk. Kosongkan jika ingin menggunakan kode yang sudah ada.</small>
                        @error('code_item')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="space-y-2">
                        <label for="description_item" class="block text-sm font-medium text-gray-700">
                            Deskripsi Produk
                        </label>
                        <textarea name="description_item" id="description_item" rows="5"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description_item') border-red-500 @enderror"
                                  placeholder="Masukkan deskripsi produk secara detail...">{{ old('description_item', $product->description_item) }}</textarea>
                        <div class="flex justify-between items-center">
                            <small class="text-gray-500">Jelaskan manfaat, cara pakai, dan informasi penting lainnya</small>
                            <small class="text-gray-400" id="descriptionCount">0/2000 karakter</small>
                        </div>
                        @error('description_item')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ingredients (Komposisi) -->
                    <div class="space-y-2">
                        <label for="ingredient_item" class="block text-sm font-medium text-gray-700">
                            Komposisi/Ingredients
                        </label>
                        <textarea name="ingredient_item" id="ingredient_item" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('ingredient_item') border-red-500 @enderror"
                                  placeholder="Contoh: Aqua, Glycerin, Dimethicone, Niacinamide, Hyaluronic Acid, Vitamin E, dll...">{{ old('ingredient_item', $product->ingredient_item ?? '') }}</textarea>
                        <div class="flex justify-between items-center">
                            <small class="text-gray-500">Cantumkan komposisi bahan-bahan produk untuk transparansi kepada pelanggan</small>
                            <small class="text-gray-400" id="ingredientsCount">0/1500 karakter</small>
                        </div>
                        @error('ingredient_item')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contain Item -->
                    <div class="space-y-2">
                        <label for="contain_item" class="block text-sm font-medium text-gray-700">
                            Isi Kemasan/Contain Item
                        </label>
                        <textarea name="contain_item" id="contain_item" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contain_item') border-red-500 @enderror"
                                  placeholder="Contoh: 1 botol 30ml, 1 pipet dropper, 1 panduan penggunaan, kemasan box premium, dll..."
                                  maxlength="1000">{{ old('contain_item', $product->contain_item ?? '') }}</textarea>
                        <div class="flex justify-between items-center">
                            <small class="text-gray-500">Sebutkan apa saja yang ada di dalam kemasan produk</small>
                            <small class="text-gray-400" id="containCount">0/1000 karakter</small>
                        </div>
                        @error('contain_item')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Berat -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="space-y-2">
                            <label for="netweight_item" class="block text-sm font-medium text-gray-700">
                                Berat Produk
                            </label>
                            <div class="relative">
                                <input type="text" name="netweight_item" id="netweight_item" 
                                       value="{{ old('netweight_item', $product->netweight_item) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('netweight_item') border-red-500 @enderror"
                                       placeholder="Contoh: 250g, 10ml">
                                <span class="absolute right-3 top-3 text-gray-500">gram/ml</span>
                            </div>
                            @error('netweight_item')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Harga Produk (untuk Database) -->
                    <div class="space-y-2">
                        <label for="costprice_item" class="block text-sm font-medium text-gray-700">
                            Harga Produk (Rp) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" 
                                   name="costprice_item" 
                                   id="costprice_item" 
                                   value="{{ old('costprice_item', $product->costprice_item) }}" 
                                   min="0" 
                                   max="999999999.99" 
                                   step="0.01"
                                   placeholder="0"
                                   class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('costprice_item') border-red-500 @enderror"
                                   required>
                        </div>
                        @error('costprice_item')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <small class="text-gray-500">Harga dasar produk yang disimpan dalam database</small>
                    </div>
                    
                    <!-- Stok Produk -->
                    <div class="space-y-2">
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700">
                            Stok Produk (Unit) <span class="text-red-500">*</span>
                        </label>
                        @php
                            $currentStock = $product->itemStocks->first();
                        @endphp
                        <input type="number" 
                               name="stock_quantity" 
                               id="stock_quantity" 
                               value="{{ old('stock_quantity', $currentStock ? $currentStock->stock : 0) }}" 
                               min="0" 
                               max="999999" 
                               placeholder="Masukkan jumlah stok produk"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stock_quantity') border-red-500 @enderror" 
                               required>
                        @error('stock_quantity')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <small class="text-gray-500">Jumlah stok produk yang tersedia untuk dijual</small>
                    </div>


                    <!-- Struktur Harga untuk Customer -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-2">
                            <h3 class="text-lg font-medium text-gray-900">Struktur Harga Produk</h3>
                            <span class="text-red-500">*</span>
                        </div>
                        <p class="text-sm text-gray-600">Tentukan harga jual untuk setiap tipe customer</p>
                        
                        @php
                            $regularPricing = $product->itemDetails->where('customer_type_id', 1)->first();
                            $agentPricing = $product->itemDetails->where('customer_type_id', 2)->first();
                            $resellerPricing = $product->itemDetails->where('customer_type_id', 3)->first();
                        @endphp
                        
                        <!-- Regular Customer Pricing -->
                        <div class="border border-gray-200 rounded-lg p-4 space-y-4">
                            <div class="flex items-center space-x-2">
                                <h4 class="text-md font-medium text-gray-900">Harga untuk Regular Customer</h4>
                                <span class="text-red-500">*</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Cost Price Regular -->
                                <div class="space-y-2">
                                    <label for="cost_price_1" class="block text-sm font-medium text-gray-700">
                                        Cost Price (Harga Pokok) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500">Rp</span>
                                        <input type="number" name="cost_price_1" id="cost_price_1" 
                                               value="{{ old('cost_price_1', $regularPricing->cost_price ?? '') }}"
                                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cost_price_1') border-red-500 @enderror"
                                               placeholder="0" min="0" step="1" required>
                                    </div>
                                    @error('cost_price_1')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Sell Price Regular -->
                                <div class="space-y-2">
                                    <label for="sell_price_1" class="block text-sm font-medium text-gray-700">
                                        Sell Price (Harga Jual) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500">Rp</span>
                                        <input type="number" name="sell_price_1" id="sell_price_1" 
                                               value="{{ old('sell_price_1', $regularPricing->sell_price ?? '') }}"
                                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sell_price_1') border-red-500 @enderror"
                                               placeholder="0" min="0" step="1" required>
                                    </div>
                                    @error('sell_price_1')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Reseller Pricing -->
                        <div class="border border-gray-200 rounded-lg p-4 space-y-4">
                            <div class="flex items-center space-x-2">
                                <h4 class="text-md font-medium text-gray-900">Harga untuk Reseller</h4>
                                <span class="text-red-500">*</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Cost Price Reseller -->
                                <div class="space-y-2">
                                    <label for="cost_price_3" class="block text-sm font-medium text-gray-700">
                                        Cost Price (Harga Pokok) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500">Rp</span>
                                        <input type="number" name="cost_price_3" id="cost_price_3" 
                                               value="{{ old('cost_price_3', $resellerPricing->cost_price ?? '') }}"
                                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cost_price_3') border-red-500 @enderror"
                                               placeholder="0" min="0" step="1" required>
                                    </div>
                                    @error('cost_price_3')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Sell Price Reseller -->
                                <div class="space-y-2">
                                    <label for="sell_price_3" class="block text-sm font-medium text-gray-700">
                                        Sell Price (Harga Jual) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500">Rp</span>
                                        <input type="number" name="sell_price_3" id="sell_price_3" 
                                               value="{{ old('sell_price_3', $resellerPricing->sell_price ?? '') }}"
                                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sell_price_3') border-red-500 @enderror"
                                               placeholder="0" min="0" step="1" required>
                                    </div>
                                    @error('sell_price_3')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <!-- Kategori Produk -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Kategori Produk <span class="text-red-500">*</span>
                        </label>
                        @php
                            $currentCategoryIds = $product->categories()->pluck('category_id')->toArray();
                        @endphp
                        <div class="border border-gray-300 rounded-lg p-4 max-h-60 overflow-y-auto @error('category_ids') border-red-500 @enderror">
                            @forelse(App\Models\MasterCategory::whereIn('category_id', [1, 2, 3, 4, 5, 6, 7, 8])->orderBy('name_category')->get() as $category)
                                <div class="flex items-center mb-3">
                                    <input type="checkbox" 
                                           name="category_ids[]" 
                                           id="category_{{ $category->category_id }}" 
                                           value="{{ $category->category_id }}"
                                           {{ (is_array(old('category_ids')) && in_array($category->category_id, old('category_ids'))) || (!old('category_ids') && in_array($category->category_id, $currentCategoryIds)) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="category_{{ $category->category_id }}" class="ml-2 text-sm text-gray-700 cursor-pointer">
                                        {{ $category->name_category }}
                                    </label>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada kategori produk tersedia</p>
                            @endforelse
                        </div>
                        @error('category_ids')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <small class="text-gray-500">Pilih satu atau lebih kategori yang sesuai dengan produk</small>
                    </div>

                    <!-- Status -->
                    <div class="space-y-2">
                        <label for="status_item" class="block text-sm font-medium text-gray-700">
                            Status Produk <span class="text-red-500">*</span>
                        </label>
                        <select name="status_item" id="status_item" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status_item') border-red-500 @enderror" required>
                            <option value="active" {{ old('status_item', $product->status_item) == 'active' ? 'selected' : '' }}>
                                Aktif (Tampil di toko)
                            </option>
                            <option value="inactive" {{ old('status_item', $product->status_item) == 'inactive' ? 'selected' : '' }}>
                                Nonaktif (Tersembunyi)
                            </option>
                        </select>
                        @error('status_item')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reseller Baby Spa Flag -->
                    <div class="space-y-2">
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" 
                                       name="is_reseller_babyspa" 
                                       id="is_reseller_babyspa" 
                                       value="1"
                                       {{ old('is_reseller_babyspa', $product->is_reseller_babyspa) == 1 ? 'checked' : '' }}
                                       class="w-5 h-5 text-purple-600 bg-white border-purple-300 rounded focus:ring-purple-500 focus:ring-2 mt-1">
                                <div class="flex-1">
                                    <label for="is_reseller_babyspa" class="block text-sm font-semibold text-purple-900 cursor-pointer">
                                        Produk Khusus Reseller Baby Spa
                                    </label>
                                    <p class="text-xs text-purple-700 mt-1">
                                        Centang jika produk ini hanya dapat dijual oleh Reseller Baby Spa. Produk akan tersembunyi dari customer biasa dan reseller reguler.
                                    </p>
                                    <p class="text-xs text-purple-600 mt-1 font-medium">
                                        Contoh: Gentle Baby 250ml
                                    </p>
                                </div>
                            </div>
                        </div>
                        @error('is_reseller_babyspa')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gambar Produk -->
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700">Gambar Produk</label>
                        
                        <!-- Current Image Display -->
                        <div class="space-y-4">
                            @if($product->picture_item)
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-600">Gambar saat ini:</p>
                                    <div class="relative inline-block group">
                                        <img id="currentImage" 
                                             src="{{ $product->image }}" 
                                             alt="{{ $product->name_item }}" 
                                             class="w-40 h-40 object-cover rounded-lg border-2 border-gray-200 shadow-sm"
                                             onerror="this.src='{{ asset('storage/gentle-baby/placeholder.jpg') }}'">
                                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                            <button type="button" 
                                                    onclick="document.getElementById('image').click()" 
                                                    class="bg-white text-gray-800 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors">
                                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                Ganti Gambar
                                            </button>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">Nama file: {{ $product->picture_item }}</p>
                                </div>
                            @else
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-600">Belum ada gambar</p>
                                    <div class="w-40 h-40 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center cursor-pointer hover:border-gray-400 transition-colors"
                                         onclick="document.getElementById('image').click()">
                                        <div class="text-center">
                                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            <p class="text-sm text-gray-500">Klik untuk menambah gambar</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- New Image Preview (for when user selects a new image) -->
                            <div id="newImagePreview" class="hidden space-y-2">
                                <p class="text-sm text-green-600 font-medium">Gambar baru yang akan diupload:</p>
                                <div class="relative inline-block group">
                                    <img id="previewImage" 
                                         src="" 
                                         alt="Preview" 
                                         class="w-40 h-40 object-cover rounded-lg border-2 border-green-300 shadow-sm">
                                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                        <button type="button" 
                                                onclick="cancelImageUpload()" 
                                                class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition-colors">
                                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Batal
                                        </button>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500" id="newFileName"></p>
                            </div>
                        </div>
                        
                        <!-- File Input (Hidden) -->
                        <input type="file" name="image" id="image" accept="image/*" class="hidden">
                        
                        <!-- Image Info -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-blue-800 font-medium">Tips Upload Gambar:</p>
                                    <ul class="text-xs text-blue-700 mt-1 space-y-1">
                                        <li>• Format yang didukung: JPEG, PNG, JPG, GIF, WebP</li>
                                        <li>• Ukuran maksimal: 2MB</li>
                                        <li>• Resolusi optimal: 800x800 pixels atau lebih</li>
                                        <li>• Gambar akan disimpan di: storage/public/images/</li>
                                        <li>• Kosongkan jika tidak ingin mengubah gambar</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Error Display -->
                        @error('image')
                            <p class="text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        
                        <!-- New Image Preview -->
                        <div id="imagePreview" class="hidden space-y-2">
                            <p class="text-sm text-gray-600">Preview gambar baru:</p>
                            <div class="relative inline-block">
                                <img id="preview" src="" alt="Preview" class="w-40 h-40 object-cover rounded-lg border-2 border-green-300 shadow-sm">
                                <div class="absolute -top-2 -right-2">
                                    <button type="button" 
                                            onclick="cancelImageChange()" 
                                            class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <p class="text-xs text-green-600 font-medium">✓ Gambar siap diupload</p>
                        </div>
                    </div>

                

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Perubahan
                        </button>
                        <a href="{{ session('previous_url', route('admin.sales.products.index')) }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="lg:col-span-1">
            <div class="space-y-6">
                <!-- Product Info Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Produk</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm font-medium text-gray-600">ID Produk:</span>
                            <span class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ $product->item_id }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm font-medium text-gray-600">Kode Produk:</span>
                            <span class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">
                                {{ $product->code_item ?? 'Belum diset' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm font-medium text-gray-600">Kategori:</span>
                            <span class="text-sm text-gray-900">
                                {{ $product->category->name_category ?? 'Tanpa kategori' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm font-medium text-gray-600">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $product->status_item == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <div class="w-1.5 h-1.5 rounded-full mr-1.5 
                                    {{ $product->status_item == 'active' ? 'bg-green-400' : 'bg-red-400' }}"></div>
                                {{ $product->status_item == 'active' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm font-medium text-gray-600">Dibuat:</span>
                            <span class="text-sm text-gray-900">
                                {{ $product->created_at ? $product->created_at->format('d M Y') : 'N/A' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm font-medium text-gray-600">Diperbarui:</span>
                            <span class="text-sm text-gray-900">
                                {{ $product->updated_at ? $product->updated_at->format('d M Y H:i') : 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="bg-blue-50 rounded-lg border border-blue-200">
                    <div class="px-6 py-4 border-b border-blue-200">
                        <h3 class="text-lg font-semibold text-blue-900 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Tips
                        </h3>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-3 text-sm text-blue-800">
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Gunakan nama produk yang jelas dan mudah dicari
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Gambar produk dengan kualitas baik meningkatkan penjualan
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Deskripsi detail membantu pelanggan memahami produk
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Status "Aktif" akan menampilkan produk di halaman belanja
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <form action="{{ route('admin.sales.products.destroy', $product->item_id) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-100 text-red-700 text-sm font-medium rounded-lg hover:bg-red-200 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Hapus Produk
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview functionality
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const newImagePreview = document.getElementById('newImagePreview');
    const previewImage = document.getElementById('previewImage');
    const newFileName = document.getElementById('newFileName');
    
    if (file) {
        // Comprehensive file validation
        
        // Check file size (2MB = 2,097,152 bytes)
        const maxSize = 2 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('Ukuran file terlalu besar. Maksimal 2MB.\nUkuran file Anda: ' + Math.round(file.size / 1024 / 1024 * 100) / 100 + ' MB');
            e.target.value = '';
            return;
        }
        
        // Check file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung.\nGunakan: JPEG, PNG, JPG, GIF, atau WebP.\nFile Anda: ' + file.type);
            e.target.value = '';
            return;
        }
        
        // Check file name
        if (file.name.length > 255) {
            alert('Nama file terlalu panjang. Maksimal 255 karakter.');
            e.target.value = '';
            return;
        }
        
        // Additional checks for corrupted files
        if (file.size === 0) {
            alert('File kosong atau corrupt. Silakan pilih file yang valid.');
            e.target.value = '';
            return;
        }
        
        console.log('File validation passed:', {
            name: file.name,
            size: file.size,
            type: file.type,
            lastModified: new Date(file.lastModified)
        });
        
        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                // Show new image preview
                previewImage.src = e.target.result;
                newImagePreview.classList.remove('hidden');
                
                // Update file name display
                newFileName.textContent = 'File: ' + file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
                
                console.log('Image preview loaded successfully');
            } catch (error) {
                console.error('Error loading image preview:', error);
                alert('Error memuat preview gambar. File mungkin corrupt.');
                e.target.value = '';
                newImagePreview.classList.add('hidden');
            }
        };
        
        reader.onerror = function() {
            console.error('FileReader error');
            alert('Error membaca file. Silakan coba file lain.');
            e.target.value = '';
            newImagePreview.classList.add('hidden');
        };
        
        reader.readAsDataURL(file);
    } else {
        // Hide preview if no file selected
        newImagePreview.classList.add('hidden');
    }
});

// Cancel image upload function
function cancelImageUpload() {
    const imageInput = document.getElementById('image');
    const newImagePreview = document.getElementById('newImagePreview');
    
    imageInput.value = '';
    newImagePreview.classList.add('hidden');
    
    console.log('Image upload cancelled by user');
}

// Drag and drop functionality
const dropZone = document.querySelector('.cursor-pointer');
if (dropZone) {
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-blue-400', 'bg-blue-50');
    });
    
    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-400', 'bg-blue-50');
    });
    
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-400', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('image').files = files;
            document.getElementById('image').dispatchEvent(new Event('change'));
        }
    });
}

// Description character counter
document.getElementById('description_item').addEventListener('input', function(e) {
    const maxLength = 2000;
    const currentLength = e.target.value.length;
    const counter = document.getElementById('descriptionCount');
    
    counter.textContent = currentLength + '/' + maxLength + ' karakter';
    
    if (currentLength > maxLength * 0.9) {
        counter.classList.add('text-red-500');
        counter.classList.remove('text-gray-400');
    } else {
        counter.classList.remove('text-red-500');
        counter.classList.add('text-gray-400');
    }
});

// Ingredients character counter
document.getElementById('ingredient_item').addEventListener('input', function(e) {
    const maxLength = 1500;
    const currentLength = e.target.value.length;
    const counter = document.getElementById('ingredientsCount');
    
    counter.textContent = currentLength + '/' + maxLength + ' karakter';
    
    if (currentLength > maxLength * 0.9) {
        counter.classList.add('text-red-500');
        counter.classList.remove('text-gray-400');
    } else {
        counter.classList.remove('text-red-500');
        counter.classList.add('text-gray-400');
    }
});

// Contain Item character counter
document.getElementById('contain_item').addEventListener('input', function(e) {
    const maxLength = 1000;
    const currentLength = e.target.value.length;
    const counter = document.getElementById('containCount');
    
    counter.textContent = currentLength + '/' + maxLength + ' karakter';
    
    if (currentLength > maxLength * 0.9) {
        counter.classList.add('text-red-500');
        counter.classList.remove('text-gray-400');
    } else {
        counter.classList.remove('text-red-500');
        counter.classList.add('text-gray-400');
    }
});

// Initialize counters
document.addEventListener('DOMContentLoaded', function() {
    const descriptionField = document.getElementById('description_item');
    if (descriptionField) {
        descriptionField.dispatchEvent(new Event('input'));
    }
    
    const ingredientsField = document.getElementById('ingredient_item');
    if (ingredientsField) {
        ingredientsField.dispatchEvent(new Event('input'));
    }
    
    const containField = document.getElementById('contain_item');
    if (containField) {
        containField.dispatchEvent(new Event('input'));
    }
});

// Price formatting and validation
['costprice_item', 'cost_price_1', 'sell_price_1', 'cost_price_2', 'sell_price_2', 'cost_price_3', 'sell_price_3'].forEach(function(fieldId) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.addEventListener('input', function(e) {
            let value = e.target.value;
            
            // Remove any non-numeric characters except decimal point
            value = value.replace(/[^\d]/g, '');
            
            // Ensure it's a valid number
            if (value && parseInt(value) < 0) {
                value = '0';
            }
            
            e.target.value = value;
        });
    }
});

// Form submission validation
document.querySelector('form').addEventListener('submit', function(e) {
    const nameField = document.getElementById('name_item');
    const priceField = document.getElementById('costprice_item');
    const costPriceField = document.getElementById('cost_price_1');
    const sellPriceField = document.getElementById('sell_price_1');
    const statusField = document.getElementById('status_item');
    const categoryCheckboxes = document.querySelectorAll('input[name="category_ids[]"]:checked');
    const imageField = document.getElementById('image');
    
    // Check required fields
    if (!nameField.value.trim()) {
        alert('Nama produk wajib diisi!');
        nameField.focus();
        e.preventDefault();
        return false;
    }
    
    if (!priceField.value || parseFloat(priceField.value) <= 0) {
        alert('Harga pokok produk wajib diisi dan harus lebih dari 0!');
        priceField.focus();
        e.preventDefault();
        return false;
    }
    
    // Validate Regular Customer pricing
    if (!costPriceField.value || parseFloat(costPriceField.value) <= 0) {
        alert('Cost price untuk Regular Customer wajib diisi dan harus lebih dari 0!');
        costPriceField.focus();
        e.preventDefault();
        return false;
    }
    
    if (!sellPriceField.value || parseFloat(sellPriceField.value) <= 0) {
        alert('Sell price untuk Regular Customer wajib diisi dan harus lebih dari 0!');
        sellPriceField.focus();
        e.preventDefault();
        return false;
    }
    

    // Validate Reseller pricing
    const costPrice3 = document.getElementById('cost_price_3');
    const sellPrice3 = document.getElementById('sell_price_3');
    if (!costPrice3.value || parseFloat(costPrice3.value) <= 0) {
        alert('Cost price untuk Reseller wajib diisi dan harus lebih dari 0!');
        costPrice3.focus();
        e.preventDefault();
        return false;
    }
    if (!sellPrice3.value || parseFloat(sellPrice3.value) <= 0) {
        alert('Sell price untuk Reseller wajib diisi dan harus lebih dari 0!');
        sellPrice3.focus();
        e.preventDefault();
        return false;
    }
    
    if (categoryCheckboxes.length === 0) {
        alert('Minimal pilih satu kategori produk!');
        e.preventDefault();
        return false;
    }
    
    if (!statusField.value) {
        alert('Status produk wajib dipilih!');
        statusField.focus();
        e.preventDefault();
        return false;
    }
    
    // Additional image validation before submit
    const hasNewImage = imageField.files.length > 0;
    if (hasNewImage) {
        const file = imageField.files[0];
        
        // Final validation before submit
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file gambar terlalu besar (max 2MB)');
            imageField.focus();
            e.preventDefault();
            return false;
        }
        
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file gambar tidak didukung');
            imageField.focus();
            e.preventDefault();
            return false;
        }
        
        console.log('Final image validation passed, proceeding with upload');
    }
    
    // Confirm submission
    let confirmMessage = 'Simpan perubahan produk?';
    
    if (hasNewImage) {
        confirmMessage = 'Simpan perubahan produk dan ganti gambar?\n\nFile: ' + imageField.files[0].name + '\nUkuran: ' + Math.round(imageField.files[0].size / 1024) + ' KB';
    }
    
    if (!confirm(confirmMessage)) {
        e.preventDefault();
        return false;
    }
    
    // Add loading state for image upload
    if (hasNewImage) {
        const submitButton = document.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Mengunggah gambar...';
        
        // Store flag for page reload after submission
        sessionStorage.setItem('imageUploadInProgress', 'true');
        sessionStorage.setItem('uploadTimestamp', new Date().getTime());
        
        console.log('Starting image upload process');
    }
    
    return true;
});

// Refresh image after successful update (to avoid browser cache)
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced image refresh function
    function refreshProductImage() {
        const currentImage = document.getElementById('currentImage');
        if (currentImage && currentImage.src) {
            // Force browser to reload image by adding unique timestamp
            const timestamp = new Date().getTime();
            const baseUrl = currentImage.src.split('?')[0];
            currentImage.src = baseUrl + '?v=' + timestamp;
            
            console.log('Image refreshed with cache-busting: ' + currentImage.src);
        }
    }
    
    // Check if we just updated (success message exists)
    const successMessage = document.querySelector('.bg-green-50');
    if (successMessage) {
        // Wait a moment for the page to fully load, then refresh image
        setTimeout(function() {
            refreshProductImage();
        }, 500);
        
        // Also check if the success message mentions image update
        const messageText = successMessage.textContent;
        if (messageText.includes('Gambar telah diganti')) {
            // Force a more aggressive cache clear for image updates
            setTimeout(function() {
                refreshProductImage();
                
                // Clear browser cache if supported
                if ('caches' in window) {
                    caches.keys().then(function(names) {
                        names.forEach(function(name) {
                            caches.delete(name);
                        });
                    });
                }
            }, 1000);
        }
    }
    
    // Store flag if image is being uploaded
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const hasNewImage = document.getElementById('image').files.length > 0;
            if (hasNewImage) {
                // Store flag that image is being uploaded
                sessionStorage.setItem('imageUploadInProgress', 'true');
                sessionStorage.setItem('uploadTimestamp', new Date().getTime());
            }
        });
    }
    
    // Check if we just came back from an image upload
    const uploadInProgress = sessionStorage.getItem('imageUploadInProgress');
    const uploadTimestamp = sessionStorage.getItem('uploadTimestamp');
    
    if (uploadInProgress === 'true') {
        // Clear the flags
        sessionStorage.removeItem('imageUploadInProgress');
        sessionStorage.removeItem('uploadTimestamp');
        
        // Force image refresh with aggressive cache busting
        setTimeout(function() {
            const currentImage = document.getElementById('currentImage');
            if (currentImage) {
                const timestamp = uploadTimestamp || new Date().getTime();
                const baseUrl = currentImage.src.split('?')[0];
                currentImage.src = baseUrl + '?v=' + timestamp + '&refresh=1';
                
                // Force reload if image doesn't change
                currentImage.onload = function() {
                    console.log('Image successfully refreshed after upload');
                };
                
                currentImage.onerror = function() {
                    console.log('Error loading new image, using fallback');
                    this.src = '{{ asset("storage/gentle-baby/placeholder.jpg") }}';
                };
            }
        }, 1000);
    }
    
    // Setup thumbnail input handlers
    for (let i = 1; i <= 3; i++) {
        const thumbnailInput = document.getElementById(`thumbnail_${i}`);
        if (thumbnailInput) {
            thumbnailInput.addEventListener('change', function() {
                handleThumbnailUpload(i);
            });
        }
    }
});

// Thumbnail upload handlers
function handleThumbnailUpload(thumbnailNumber) {
    const input = document.getElementById(`thumbnail_${thumbnailNumber}`);
    const file = input.files[0];
    
    if (file) {
        // Validate file
        if (!validateImageSize(file) || !validateImageType(file)) {
            input.value = '';
            return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewContainer = document.getElementById(`thumbnail_${thumbnailNumber}Preview`);
            const previewImg = document.getElementById(`thumbnail_${thumbnailNumber}PreviewImg`);
            
            previewImg.src = e.target.result;
            previewContainer.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function cancelThumbnailChange(thumbnailNumber) {
    const input = document.getElementById(`thumbnail_${thumbnailNumber}`);
    const previewContainer = document.getElementById(`thumbnail_${thumbnailNumber}Preview`);
    const previewImg = document.getElementById(`thumbnail_${thumbnailNumber}PreviewImg`);
    
    // Clear input and preview
    input.value = '';
    previewImg.src = '';
    previewContainer.classList.add('hidden');
}

function validateImageSize(file) {
    const maxSize = 2 * 1024 * 1024; // 2MB
    if (file.size > maxSize) {
        alert('Ukuran file gambar terlalu besar. Maksimal 2MB.');
        return false;
    }
    return true;
}

function validateImageType(file) {
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    if (!allowedTypes.includes(file.type)) {
        alert('Format file gambar tidak didukung. Gunakan format: JPEG, PNG, GIF, atau WebP.');
        return false;
    }
    return true;
}
</script>

@endsection