@extends('layouts.admin.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="mb-4 sm:mb-0">
                <h1 class="page-title text-3xl text-[#6C63FF] flex items-center mb-2">
                    <svg class="w-8 h-8 mr-3 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Produk
                </h1>
                <p class="page-subtitle text-[#6C63FF] text-lg ml-11">Tambahkan produk baru ke dalam sistem penjualan</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.sales.products.index') }}" 
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
                    <p class="text-sm text-gray-600 mt-1">Masukkan detail informasi produk baru</p>
                </div>
                
                <form id="productForm" action="{{ route('admin.sales.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    
                    <!-- Nama Produk -->
                    <div class="space-y-2">
                        <label for="name_item" class="block text-sm font-medium text-gray-700">
                            Nama Produk <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name_item" id="name_item" 
                               value="{{ old('name_item') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name_item') border-red-500 @enderror"
                               placeholder="Masukkan nama produk yang menarik dan mudah dicari" 
                               maxlength="255" required>
                        <small class="text-gray-500">Gunakan nama yang jelas dan mudah diingat pelanggan</small>
                        @error('name_item')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Kode Produk -->
                    <div class="space-y-2">
                        <label for="code_item" class="block text-sm font-medium text-gray-700">
                            Kode Produk <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="code_item" id="code_item" 
                               value="{{ old('code_item') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('code_item') border-red-500 @enderror"
                               placeholder="Contoh: PRD-001, GB-COUGH-10ML, dll..." 
                               maxlength="50" required>
                        <small class="text-gray-500">Kode unik untuk identifikasi produk. Jika kosong, sistem akan generate otomatis.</small>
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
                                  placeholder="Jelaskan manfaat produk, cara penggunaan, bahan utama, dan informasi penting lainnya..."
                                  maxlength="2000">{{ old('description_item') }}</textarea>
                        <div class="flex justify-between items-center">
                            <small class="text-gray-500">Deskripsi yang detail membantu pelanggan memahami produk</small>
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
                                  placeholder="Contoh: Aqua, Glycerin, Dimethicone, Niacinamide, Hyaluronic Acid, Vitamin E, dll..."
                                  maxlength="1500">{{ old('ingredient_item') }}</textarea>
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
                                  maxlength="1000">{{ old('contain_item') }}</textarea>
                        <div class="flex justify-between items-center">
                            <small class="text-gray-500">Sebutkan apa saja yang ada di dalam kemasan produk</small>
                            <small class="text-gray-400" id="containCount">0/1000 karakter</small>
                        </div>
                        @error('contain_item')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga Pokok dan Berat -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="space-y-2">
                            <label for="netweight_item" class="block text-sm font-medium text-gray-700">
                                Berat Produk
                            </label>
                            <div class="relative">
                                <input type="text" name="netweight_item" id="netweight_item" 
                                       value="{{ old('netweight_item') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('netweight_item') border-red-500 @enderror"
                                       placeholder="Contoh: 10ml">
                                <span class="absolute right-3 top-3 text-gray-500">gram/ml</span>
                            </div>
                            @error('netweight_item')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Stok Produk -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-2">
                            <h3 class="text-lg font-medium text-gray-900">Stok Produk</h3>
                            <span class="text-red-500">*</span>
                        </div>
                        <p class="text-sm text-gray-600">Tentukan jumlah stok produk yang tersedia</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                            <!-- Stock Quantity -->
                            <div class="space-y-2">
                                <label for="stock_quantity" class="block text-sm font-medium text-gray-700">
                                    Jumlah Stok <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="stock_quantity" id="stock_quantity" 
                                           value="{{ old('stock_quantity', 0) }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stock_quantity') border-red-500 @enderror"
                                           placeholder="0" min="0" step="1" required>
                                    <span class="absolute right-3 top-3 text-gray-500">pcs</span>
                                </div>
                                <small class="text-gray-500">Jumlah stok yang tersedia di inventori utama</small>
                                @error('stock_quantity')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Struktur Harga Produk -->
                    <!-- Struktur Harga Produk -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-2">
                            <h3 class="text-lg font-medium text-gray-900">Struktur Harga Produk</h3>
                            <span class="text-red-500">*</span>
                        </div>
                        <p class="text-sm text-gray-600">Tentukan harga jual dan harga pokok untuk setiap tipe customer</p>
                        
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
                                               value="{{ old('cost_price_1') }}"
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
                                               value="{{ old('sell_price_1') }}"
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
                                               value="{{ old('cost_price_3') }}"
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
                                               value="{{ old('sell_price_3') }}"
                                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sell_price_3') border-red-500 @enderror"
                                               placeholder="0" min="0" step="1" required>
                                    </div>
                                    @error('sell_price_3')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>                    <!-- Status -->
                    <div class="space-y-2">
                        <label for="status_item" class="block text-sm font-medium text-gray-700">
                            Status Produk <span class="text-red-500">*</span>
                        </label>
                        <select name="status_item" id="status_item" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status_item') border-red-500 @enderror" required>
                            <option value="active" {{ old('status_item', 'active') == 'active' ? 'selected' : '' }}>
                                Aktif (Tampil di toko)
                            </option>
                            <option value="inactive" {{ old('status_item') == 'inactive' ? 'selected' : '' }}>
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
                                       {{ old('is_reseller_babyspa') == 1 ? 'checked' : '' }}
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

                    

                    <!-- Harga Produk -->
                    <div class="space-y-2">
                        <label for="costprice_item" class="block text-sm font-medium text-gray-700">
                            Harga Produk <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="costprice_item" id="costprice_item" 
                                   value="{{ old('costprice_item') }}"
                                   class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('costprice_item') border-red-500 @enderror"
                                   placeholder="50000" 
                                   min="0" step="0.01" required>
                        </div>
                        <small class="text-gray-500">Harga dasar produk (untuk perhitungan internal)</small>
                        @error('costprice_item')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori Produk -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Kategori Produk <span class="text-red-500">*</span>
                        </label>
                        <div class="border border-gray-300 rounded-lg p-4 max-h-60 overflow-y-auto @error('category_ids') border-red-500 @enderror">
                            @forelse(App\Models\MasterCategory::whereIn('category_id', [1, 2, 3, 4, 5, 6, 7, 8])->orderBy('name_category')->get() as $category)
                                <div class="flex items-center mb-3">
                                    <input type="checkbox" 
                                           name="category_ids[]" 
                                           id="category_{{ $category->category_id }}" 
                                           value="{{ $category->category_id }}"
                                           {{ is_array(old('category_ids')) && in_array($category->category_id, old('category_ids')) ? 'checked' : '' }}
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

                    <!-- Gambar Produk -->
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700">Gambar Produk Utama</label>
                        
                        <!-- Upload Area -->
                        <div class="space-y-4">
                            <!-- Upload Button -->
                            <div class="space-y-2">
                                <p class="text-sm text-gray-600">Pilih gambar produk utama:</p>
                                <button type="button" 
                                        onclick="document.getElementById('image').click()"
                                        class="w-full p-8 border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0118.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <p class="text-base font-medium text-gray-900 mb-2">Pilih Gambar Utama Produk</p>
                                        <p class="text-sm text-gray-500">Klik untuk memilih file</p>
                                        <p class="text-xs text-gray-400 mt-2">
                                            Format: JPEG, PNG, JPG, GIF, WebP • Max: 2MB
                                        </p>
                                    </div>
                                </button>
                            </div>
                        </div>
                        
                        <!-- File Input (Hidden) -->
                        <input type="file" name="image" id="image" accept="image/*" class="hidden">
                        
                        <!-- Image Preview -->
                        <div id="imagePreview" class="hidden space-y-4">
                            <div class="space-y-2">
                                <p class="text-sm text-gray-600 font-medium">Preview Gambar Utama:</p>
                                <div class="relative inline-block group">
                                    <img id="preview" 
                                         src="" 
                                         alt="Preview gambar produk" 
                                         class="w-40 h-40 object-cover rounded-lg border-2 border-green-300 shadow-sm transition-transform duration-200 group-hover:scale-105">
                                    <div class="absolute -top-2 -right-2">
                                        <button type="button" 
                                                onclick="cancelImageChange()" 
                                                class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors shadow-lg">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs text-green-600 font-medium">✓ Gambar utama siap diupload</p>
                        </div>
                        
                        <!-- Error Display -->
                        @error('image')
                            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            </div>
                        @enderror
                    </div>

                

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Produk
                        </button>
                        <a href="{{ route('admin.sales.products.index') }}" 
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
        <div class="lg:col-span-1 space-y-6">
            <!-- Tips Card -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg">
                <div class="px-6 py-4 border-b border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tips Produk Berkualitas
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-900">Nama Menarik</p>
                                <p class="text-xs text-blue-700">Gunakan nama yang jelas dan mudah dicari pelanggan</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-900">Deskripsi Lengkap</p>
                                <p class="text-xs text-blue-700">Jelaskan manfaat dan cara penggunaan produk</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-900">Harga Kompetitif</p>
                                <p class="text-xs text-blue-700">Sesuaikan dengan kualitas dan pasar target</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-900">Gambar Berkualitas</p>
                                <p class="text-xs text-blue-700">Foto terang, jelas, dan menampilkan detail produk</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Info -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg">
                <div class="px-6 py-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Status Produk</h4>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Aktif</p>
                                <p class="text-xs text-gray-600">Produk akan tampil di halaman belanja</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Nonaktif</p>
                                <p class="text-xs text-gray-600">Produk disembunyikan dari pelanggan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview functionality - simplified like edit.blade.php
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    
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
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
                
                console.log('Image preview loaded successfully');
            } catch (error) {
                console.error('Error loading image preview:', error);
                alert('Error memuat preview gambar. File mungkin corrupt.');
                e.target.value = '';
                previewContainer.classList.add('hidden');
            }
        };
        
        reader.onerror = function() {
            console.error('FileReader error');
            alert('Error membaca file. Silakan coba file lain.');
            e.target.value = '';
            previewContainer.classList.add('hidden');
        };
        
        reader.readAsDataURL(file);
    } else {
        previewContainer.classList.add('hidden');
    }
});

// Cancel image change function - consistent with edit.blade.php
function cancelImageChange() {
    const imageInput = document.getElementById('image');
    const previewContainer = document.getElementById('imagePreview');
    const preview = document.getElementById('preview');
    
    // Clear the input
    imageInput.value = '';
    
    // Hide preview
    previewContainer.classList.add('hidden');
    
    // Clear preview src
    if (preview) {
        preview.src = '';
    }
    
    console.log('Image upload cancelled');
}

// Drag and drop functionality for upload button
const uploadButton = document.querySelector('button[onclick*="image.click"]');
if (uploadButton) {
    uploadButton.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-blue-400', 'bg-blue-50');
    });
    
    uploadButton.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-400', 'bg-blue-50');
    });
    
    uploadButton.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-400', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0 && files[0].type.startsWith('image/')) {
            const imageInput = document.getElementById('image');
            imageInput.files = files;
            imageInput.dispatchEvent(new Event('change'));
        } else if (files.length > 0) {
            alert('Harap pilih file gambar yang valid (JPEG, PNG, JPG, GIF, WebP)');
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

// Image upload functions
function handleImageSelect(input) {
    const file = input.files[0];
    if (file) {
        // Validate image size
        if (!validateImageSize(file)) {
            input.value = '';
            return false;
        }
        
        // Validate image type
        if (!validateImageType(file)) {
            input.value = '';
            return false;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewContainer = document.getElementById('img-preview-container');
            const uploadArea = document.getElementById('upload-area');
            
            previewContainer.innerHTML = `
                <div class="relative">
                    <img id="img-preview" src="${e.target.result}" class="w-full h-48 object-cover rounded-lg" alt="Preview">
                    <button type="button" onclick="cancelImageChange()" 
                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="mt-2 text-sm text-gray-600">
                    <p><strong>File:</strong> ${file.name}</p>
                    <p><strong>Ukuran:</strong> ${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                </div>
            `;
            previewContainer.style.display = 'block';
            uploadArea.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
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
            const previewContainer = document.getElementById(`thumbnail${thumbnailNumber}Preview`);
            const previewImg = document.getElementById(`thumbnail${thumbnailNumber}PreviewImg`);
            
            previewImg.src = e.target.result;
            previewContainer.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function cancelThumbnailChange(thumbnailNumber) {
    const input = document.getElementById(`thumbnail_${thumbnailNumber}`);
    const previewContainer = document.getElementById(`thumbnail${thumbnailNumber}Preview`);
    const previewImg = document.getElementById(`thumbnail${thumbnailNumber}PreviewImg`);
    
    // Clear input and preview
    input.value = '';
    previewImg.src = '';
    previewContainer.classList.add('hidden');
}

// Initialize counters and event handlers
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
    
    // Setup image input handler
    const imageInput = document.querySelector('input[name="image"]');
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            handleImageSelect(this);
        });
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
document.querySelector('#productForm').addEventListener('submit', function(e) {
    const nameField = document.getElementById('name_item');
    const priceField = document.getElementById('costprice_item');
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
        alert('Harga produk wajib diisi dan harus lebih dari 0!');
        priceField.focus();
        e.preventDefault();
        return false;
    }
    
    // Validate Regular Customer pricing
    const costPrice1 = document.getElementById('cost_price_1');
    const sellPrice1 = document.getElementById('sell_price_1');
    if (!costPrice1.value || parseFloat(costPrice1.value) <= 0) {
        alert('Cost price untuk Regular Customer wajib diisi dan harus lebih dari 0!');
        costPrice1.focus();
        e.preventDefault();
        return false;
    }
    if (!sellPrice1.value || parseFloat(sellPrice1.value) <= 0) {
        alert('Sell price untuk Regular Customer wajib diisi dan harus lebih dari 0!');
        sellPrice1.focus();
        e.preventDefault();
        return false;
    }
    
    // Validate Agent pricing
    const costPrice2 = document.getElementById('cost_price_2');
    const sellPrice2 = document.getElementById('sell_price_2');
    if (!costPrice2.value || parseFloat(costPrice2.value) <= 0) {
        alert('Cost price untuk Agent wajib diisi dan harus lebih dari 0!');
        costPrice2.focus();
        e.preventDefault();
        return false;
    }
    if (!sellPrice2.value || parseFloat(sellPrice2.value) <= 0) {
        alert('Sell price untuk Agent wajib diisi dan harus lebih dari 0!');
        sellPrice2.focus();
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
    
    if (!statusField.value) {
        alert('Status produk wajib dipilih!');
        statusField.focus();
        e.preventDefault();
        return false;
    }
    
    if (categoryCheckboxes.length === 0) {
        alert('Minimal pilih satu kategori produk!');
        e.preventDefault();
        return false;
    }
    
    // Additional image validation before submit
    const hasImage = imageField.files.length > 0;
    if (hasImage) {
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
        console.log('Image file details:', {
            name: file.name,
            size: file.size,
            type: file.type
        });
    }
    
    // Show loading state
    const submitButton = document.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.disabled = true;
        if (hasImage) {
            submitButton.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Mengunggah gambar...';
        } else {
            submitButton.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Menyimpan produk...';
        }
        
        console.log('Starting form submission process');
    }
    
    return true;
});
</script>

@endsection