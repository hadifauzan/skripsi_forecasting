@extends('layouts.admin.app')

@section('title', 'Kelola Carousel Varian')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Card hover effects */
        .variant-card {
            transition: all 0.3s ease;
        }
        
        .variant-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Status badge styling */
        .status-badge {
            transition: all 0.2s ease;
        }
    </style>
@endpush

@section('content')
<div class="p-6">
    <!-- Include shared navigation component -->
    @include('admin.content-products.navigation')

    <!-- LIST VIEW -->
    <div id="list-view" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Kelola Carousel Varian Produk</h2>
                    <p class="text-gray-600">Kelola varian produk yang ditampilkan di carousel halaman utama</p>
                </div>

                <!-- Filter Kategori + Button -->
                <div class="flex items-center space-x-3">
                    <!-- Filter Kategori -->
                                        <select id="category-filter" 
                            onchange="handleCategoryChange(this.value)"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                        <option value="gentle-baby">Gentle Baby</option>
                        <option value="mamina">Mamina</option>
                        <option value="nyam">Nyam! MPASI</option>
                        <option value="healo">Healo</option>
                    </select>

                    <!-- Tombol Tambah Varian -->
                    <button onclick="showCreateForm()" id="create-btn" 
                            class="h-10 px-4 bg-[#785576] text-white rounded-lg hover:bg-[#694966] transition-colors flex items-center">
                        <i class="fas fa-plus mr-2"></i>Tambah Varian
                    </button>
                </div>
            </div>
        </div>

        <!-- Error Message -->
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Gallery View -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-4">
            @forelse($contents as $index => $content)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-200 variant-card">
                <!-- Image Section -->
                <div class="aspect-video bg-gray-100 relative">
                    @if($content->image)
                        <img src="{{ $content->image_url }}" 
                             alt="{{ $content->title ?: ($content->masterItem ? $content->masterItem->name_item : 'Variant') }}" 
                             class="w-full h-full object-contain">
                    @elseif($content->masterItem && $content->masterItem->picture_item)
                        <img src="{{ $content->masterItem->image }}" 
                             alt="{{ $content->masterItem->name_item }}" 
                             class="w-full h-full object-contain">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                            <i class="fas fa-image text-gray-400 text-2xl"></i>
                        </div>
                    @endif

                    <!-- Status Badge - Kiri Atas -->
                    <div class="absolute top-2 left-2">
                        @if($content->status ?? true)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-500 text-white shadow-sm">
                                <i class="fas fa-check-circle mr-1"></i>Aktif
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-500 text-white shadow-sm">
                                <i class="fas fa-times-circle mr-1"></i>Nonaktif
                            </span>
                        @endif
                    </div>

                    <!-- Number Badge - Kanan Atas -->
                    <div class="absolute top-2 right-2">
                        <span class="bg-purple-500 text-white px-1.5 py-0.5 text-xs font-semibold rounded-full">
                            {{ $index + 1 }}
                        </span>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="p-3">
                    <!-- Title and Action Buttons Row -->
                    <div class="flex items-center justify-between mb-1">
                        <h3 class="font-medium text-gray-900 text-sm line-clamp-1 flex-1 mr-2">
                            @php
                                $displayTitle = $content->title ?: ($content->masterItem ? $content->masterItem->name_item : 'Tanpa judul');
                                // Remove category prefixes for display
                                $prefixesToRemove = [
                                    'Carousel Varian - Gentle baby - ',
                                    'Carousel Varian - Gentle Baby - ',
                                    'Carousel Varian - Mamina - ',
                                    'Carousel Varian - Nyam - ',
                                    'Carousel Varian - Healo - ',
                                    'Gentle baby - ',
                                    'Gentle Baby - ',
                                    'Mamina - ',
                                    'Nyam - ',
                                    'Healo - '
                                ];
                                foreach ($prefixesToRemove as $prefix) {
                                    if (Str::startsWith($displayTitle, $prefix)) {
                                        $displayTitle = Str::after($displayTitle, $prefix);
                                        break;
                                    }
                                }
                            @endphp
                            {{ $displayTitle }}
                        </h3>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-1">
                            <!-- Edit Button -->
                            <button onclick="showEditForm({{ $content->content_id }})" 
                                   class="group relative text-white p-1.5 rounded-md text-xs transition-all duration-200 hover:scale-110"
                                   title="Edit Varian"
                                   style="background-color: #785576;"
                                   onmouseover="this.style.backgroundColor='#6a4a68';"
                                   onmouseout="this.style.backgroundColor='#785576';">
                                <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </button>
                            
                            <!-- Delete Button -->
                            <form action="{{ route('admin.content-products.carousel-varian.destroy', $content->content_id) }}" 
                                  method="POST" 
                                  class="inline"
                                  id="delete-form-{{ $content->content_id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        onclick="confirmDeleteVariant({{ $content->content_id }}, '{{ addslashes($content->title ?: ($content->masterItem ? $content->masterItem->name_item : 'Tanpa judul')) }}')"
                                        class="group relative text-white p-1.5 rounded-md text-xs transition-all duration-200 hover:scale-110"
                                        title="Hapus Varian"
                                        style="background-color: #c94757;"
                                        onmouseover="this.style.backgroundColor='#b93e4f';"
                                        onmouseout="this.style.backgroundColor='#c94757';">
                                    <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-xs text-gray-500 line-clamp-1">
                        {{ Str::limit($content->body ?: ($content->masterItem ? $content->masterItem->description_item : 'Tanpa deskripsi'), 50) }}
                    </p>
                </div>
            </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-box-open text-gray-300 text-4xl mb-3"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada variant carousel</h3>
                            <p class="text-gray-600 mb-4 text-sm max-w-sm">Mulai dengan menambahkan variant produk pertama untuk ditampilkan di carousel</p>
                            <button onclick="showCreateForm()" 
                                   class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition-colors inline-flex items-center text-sm">
                                <i class="fas fa-plus mr-2"></i>Tambah Variant Pertama
                            </button>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(method_exists($contents, 'links'))
            <div class="mt-6">
                {{ $contents->links() }}
            </div>
        @endif
    </div>

    <!-- CREATE/EDIT FORM -->
    <div id="form-view" class="bg-white rounded-lg shadow p-6 hidden">
        <!-- Form Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 id="form-title" class="text-xl font-bold text-gray-900">
                        Tambah Konten ke Carousel
                    </h2>
                    <p id="form-subtitle" class="text-gray-600 mt-1">
                        Tambah konten baru untuk ditampilkan di carousel varian
                    </p>
                </div>
                <button onclick="showListView()" 
                    class="bg-[#785576] text-white px-4 py-2 rounded-lg hover:bg-[#694966] transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </button>
            </div>
        </div>

        <!-- Form -->
        <form id="content-form" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="form-method" name="_method" value="">
            <input type="hidden" id="product-category" name="product_category" value="">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div>
                    <!-- Pilih Produk -->
                    <div class="mb-4">
                        <label for="item_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Produk
                        </label>
                        <select name="item_id" id="item_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">-- Pilih Produk (Opsional) --</option>
                            @foreach(\App\Models\MasterItem::where('status_item', 'active')->orderBy('name_item')->get() as $item)
                                <option value="{{ $item->item_id }}">{{ $item->name_item }}</option>
                            @endforeach
                        </select>
                        <p class="text-sm text-gray-500 mt-1">Pilih produk untuk menghubungkan varian dengan produk di master items</p>
                        @error('item_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="grid grid-cols-1 gap-4 mb-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <select id="status" name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Varian Produk -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Varian <span class="text-red-500">*</span>
                        </label>
                        <div class="mb-2">
                            <input type="text" name="title" id="title" placeholder="Nama varian" required
                                class="w-full mb-2 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <!-- Section -->
                    <div class="mb-4">
                        <label for="section" class="block text-sm font-medium text-gray-700 mb-2">
                            Section <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="section" id="section" value="carousel-variant" readonly
                            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none">
                    </div>

                    <!-- Type of Page -->
                    <div class="mb-4">
                        <label for="type_of_page" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipe Halaman <span class="text-red-500">*</span>
                        </label>
                        <select name="type_of_page" id="type_of_page" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="homepage">Beranda</option>
                            <option value="gentle_baby_product">Gentle Baby Product</option>
                            <option value="mamina_product">Mamina Product</option>
                            <option value="nyam_product">Nyam Product</option>
                            <option value="healo_product">Healo Product</option>
                            <option value="article">Artikel</option>
                        </select>
                    </div>

                    <!-- Current Image (for edit mode) -->
                    <div id="current-image" class="mb-4 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-image text-purple-500 mr-1"></i>
                            Gambar Saat Ini
                        </label>
                        <div class="relative inline-block">
                            <img id="current-image-preview" src="" alt="" 
                                class="w-40 h-40 object-cover rounded-lg border-2 border-gray-200 shadow-sm">
                            <div class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                                <i class="fas fa-check mr-1"></i>Aktif
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload Section -->
                    <div class="mb-6">
                        <label for="image" id="image-label" class="block text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-cloud-upload-alt text-purple-500 mr-2"></i>
                            Upload Gambar Produk
                        </label>
                        
                        <!-- Upload Area -->
                        <div class="relative">
                            <!-- Hidden File Input -->
                            <input type="file" id="image" name="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                            
                            <!-- Custom Upload Button -->
                            <label for="image" class="cursor-pointer block">
                                <div id="upload-area" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 group">
                                    <!-- Upload Icon and Text -->
                                    <div id="upload-placeholder" class="space-y-3">
                                        <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                            <i class="fas fa-cloud-upload-alt text-3xl text-blue-600 group-hover:scale-110 transition-transform"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Klik untuk upload gambar</p>
                                            <p class="text-xs text-gray-500 mt-1">atau drag & drop gambar di sini</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Image Preview (Hidden by default) -->
                                    <div id="image-preview-container" class="hidden">
                                        <div class="relative inline-block">
                                            <img id="image-preview" src="" alt="Preview" 
                                                class="w-40 h-40 object-cover rounded-lg border-2 border-green-300 shadow-md">
                                            <!-- Remove button with clearer styling and tooltip -->
                                            <button type="button" onclick="removeImagePreview()" 
                                                title="Hapus gambar"
                                                class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 text-white rounded-full 
                                                    flex items-center justify-center text-xl font-bold 
                                                    hover:bg-red-600 transition-all duration-200 shadow-lg border-2 border-white 
                                                    leading-none">
                                                ×
                                            </button>   
                                        </div>
                                        <div class="mt-3 space-y-1">
                                            <p id="file-name" class="text-sm font-medium text-gray-700"></p>
                                            <p id="file-size" class="text-xs text-gray-500"></p>
                                            <div class="flex items-center justify-center space-x-2 text-xs text-green-600">
                                                <i class="fas fa-check-circle"></i>
                                                <span>Siap untuk disimpan</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        
                        <!-- File Info and Error Messages -->
                        <div class="mt-2 space-y-1">
                            <p class="text-sm text-gray-600 leading-relaxed">
                                <i class="fas fa-info-circle text-[#785576] mr-1"></i>
                                Format yang didukung: <strong>JPG, JPEG, PNG</strong> — ukuran maksimum <strong>2MB</strong>.
                            </p>
                            @error('image')
                                <p class="text-red-500 text-sm flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4 mt-6 pt-6 border-t">
                <button type="button" onclick="showListView()" 
                    class="px-6 py-2 border bg-[#c94757] rounded-md text-white hover:bg-[#b93e4f] transition-colors">
                    Batal
                </button>
                <button type="submit" id="submit-btn"
                    class="px-6 py-2 bg-[#785576] text-white rounded-md hover:bg-[#694966] transition-colors">
                    Simpan Varian Produk
                </button>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let isEditMode = false;
let currentProductId = null;
let currentCategory = '{{ $category ?? "gentle-baby" }}'; // Get current category from controller

// Contents data for edit functionality - organized by category
let contentsData = {};

// SweetAlert Delete Confirmation with Purple Theme for Variant
function confirmDeleteVariant(contentId, itemName) {
    Swal.fire({
        title: 'Hapus Varian?',
        text: `"${itemName}" akan dihapus secara permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#c94757',
        cancelButtonColor: '#785576',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            popup: 'swal-purple-theme',
            confirmButton: 'swal-confirm-btn',
            cancelButton: 'swal-cancel-btn'
        },
        didOpen: () => {
            // Add custom CSS for purple theme
            const style = document.createElement('style');
            style.textContent = `
                .swal-purple-theme {
                    border-radius: 12px !important;
                }
                .swal-confirm-btn {
                    background-color: #c94757 !important;
                    border: none !important;
                    border-radius: 8px !important;
                    font-weight: 600 !important;
                    padding: 10px 20px !important;
                    transition: all 0.3s ease !important;
                }
                .swal-confirm-btn:hover {
                    background-color: #b93e4f !important;
                    transform: translateY(-1px) !important;
                    box-shadow: 0 4px 12px rgba(201, 71, 87, 0.3) !important;
                }
                .swal-cancel-btn {
                    background-color: #785576 !important;
                    border: none !important;
                    border-radius: 8px !important;
                    font-weight: 600 !important;
                    padding: 10px 20px !important;
                    transition: all 0.3s ease !important;
                }
                .swal-cancel-btn:hover {
                    background-color: #694966 !important;
                    transform: translateY(-1px) !important;
                    box-shadow: 0 4px 12px rgba(120, 85, 118, 0.3) !important;
                }
                .swal2-icon.swal2-warning {
                    border-color: #785576 !important;
                    color: #785576 !important;
                }
            `;
            document.head.appendChild(style);
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit the form
            document.getElementById(`delete-form-${contentId}`).submit();
        }
    });
}

// Image Preview Functions
function previewImage(input) {
    const file = input.files[0];
    const uploadPlaceholder = document.getElementById('upload-placeholder');
    const previewContainer = document.getElementById('image-preview-container');
    const preview = document.getElementById('image-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    
    if (file) {
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Format File Tidak Valid',
                text: 'Harap pilih file gambar dengan format JPG, PNG, atau GIF.',
                confirmButtonColor: '#785576'
            });
            input.value = ''; // Clear the input
            return;
        }
        
        // Validate file size (2MB = 2 * 1024 * 1024 bytes)
        const maxSize = 2 * 1024 * 1024;
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'Ukuran File Terlalu Besar',
                text: 'Ukuran file maksimal adalah 2MB. Silakan pilih gambar yang lebih kecil.',
                confirmButtonColor: '#785576'
            });
            input.value = ''; // Clear the input
            return;
        }
        
        // Create FileReader to read the file
        const reader = new FileReader();
        reader.onload = function(e) {
            // Show preview, hide placeholder
            uploadPlaceholder.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            
            // Set preview image
            preview.src = e.target.result;
            
            // Set file info
            fileName.textContent = file.name;
            fileSize.textContent = `${(file.size / 1024 / 1024).toFixed(2)} MB`;
            
            // Add success animation
            previewContainer.style.opacity = '0';
            setTimeout(() => {
                previewContainer.style.transition = 'opacity 0.3s ease';
                previewContainer.style.opacity = '1';
            }, 50);
        };
        reader.readAsDataURL(file);
    }
}

function removeImagePreview() {
    const input = document.getElementById('image');
    const uploadPlaceholder = document.getElementById('upload-placeholder');
    const previewContainer = document.getElementById('image-preview-container');
    const preview = document.getElementById('image-preview');
    
    // Clear the file input
    input.value = '';
    
    // Reset preview
    preview.src = '';
    
    // Show placeholder, hide preview with animation
    previewContainer.style.transition = 'opacity 0.3s ease';
    previewContainer.style.opacity = '0';
    
    setTimeout(() => {
        previewContainer.classList.add('hidden');
        uploadPlaceholder.classList.remove('hidden');
        previewContainer.style.opacity = '1';
    }, 300);
}

// Drag and Drop functionality
function initializeDragAndDrop() {
    const uploadArea = document.getElementById('upload-area');
    const fileInput = document.getElementById('image');
    
    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });
    
    // Highlight drop area when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, unhighlight, false);
    });
    
    // Handle dropped files
    uploadArea.addEventListener('drop', handleDrop, false);
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    function highlight(e) {
        uploadArea.classList.add('border-blue-400', 'bg-blue-50');
    }
    
    function unhighlight(e) {
        uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
    }
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            fileInput.files = files;
            previewImage(fileInput);
        }
    }
}

// Function untuk mengorganisir konten berdasarkan kategori
function organizeContentsByCategory(contents) {
    const organized = {
        'gentle-baby': [],
        'mamina': [],
        'nyam': [],
        'healo': []
    };
    
    contents.forEach(content => {
        // Determine category based on title pattern
        if (content.title) {
            const title = content.title.toLowerCase();
            if (title.includes('carousel varian - gentle baby') || title.includes('gentle')) {
                organized['gentle-baby'].push(content);
            } else if (title.includes('carousel varian - mamina') || title.includes('mamina')) {
                organized['mamina'].push(content);
            } else if (title.includes('carousel varian - nyam') || title.includes('nyam')) {
                organized['nyam'].push(content);
            } else {
                // Default fallback - check type_of_page
                if (content.type_of_page === 'gentle_baby_product') {
                    organized['gentle-baby'].push(content);
                } else if (content.type_of_page === 'mamina_product') {
                    organized['mamina'].push(content);
                } else if (content.type_of_page === 'nyam_product') {
                    organized['nyam'].push(content);
                } else {
                    organized['gentle-baby'].push(content); // ultimate fallback
                }
            }
        } else {
            // No title, check type_of_page
            if (content.type_of_page === 'gentle_baby_product') {
                organized['gentle-baby'].push(content);
            } else if (content.type_of_page === 'mamina_product') {
                organized['mamina'].push(content);
            } else if (content.type_of_page === 'nyam_product') {
                organized['nyam'].push(content);
            } else {
                organized['gentle-baby'].push(content); // ultimate fallback
            }
        }
    });
    
    return organized;
}

// Initialize contents data
contentsData = organizeContentsByCategory(@json($contents->items() ?? []));

// Handle category filter change
function handleCategoryChange(selectedCategory) {
    currentCategory = selectedCategory;
    
    // Reload page with category filter (similar to carousel-produk)
    const url = new URL(window.location.href);
    url.searchParams.set('category', selectedCategory);
    window.location.href = url.toString();
}

// Function to update product dropdown based on category
async function updateProductDropdown(category) {
    const productDropdown = document.getElementById('item_id');
    if (!productDropdown) return Promise.resolve();
    
    try {
        // Show loading state
        productDropdown.innerHTML = '<option value="">Memuat produk...</option>';
        productDropdown.disabled = true;
        
        // Fetch products by category
        const response = await fetch(`{{ route('admin.api.products-by-category') }}?category=${category}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Clear dropdown
            productDropdown.innerHTML = '<option value="">-- Pilih Produk (Opsional) --</option>';
            
            // Add products to dropdown
            data.products.forEach(product => {
                const option = document.createElement('option');
                option.value = product.item_id;
                option.textContent = product.name_item;
                productDropdown.appendChild(option);
            });
            
            // Log for debugging
            console.log(`Loaded ${data.products.length} products for category: ${category}`);
            return Promise.resolve();
        } else {
            productDropdown.innerHTML = '<option value="">Gagal memuat produk</option>';
            console.error('Failed to load products:', data);
            return Promise.reject(new Error('Failed to load products'));
        }
    } catch (error) {
        productDropdown.innerHTML = '<option value="">Error memuat produk</option>';
        console.error('Error fetching products:', error);
        return Promise.reject(error);
    } finally {
        productDropdown.disabled = false;
    }
}

function showListView() {
    document.getElementById('list-view').classList.remove('hidden');
    document.getElementById('form-view').classList.add('hidden');
    document.getElementById('list-btn').classList.add('bg-purple-500', 'text-white');
    document.getElementById('list-btn').classList.remove('bg-gray-200', 'text-gray-700');
    document.getElementById('create-btn').classList.remove('bg-green-500', 'text-white');
    document.getElementById('create-btn').classList.add('bg-gray-200', 'text-gray-700');
    
    // Reset form
    resetForm();
}

function showCreateForm() {
    document.getElementById('list-view').classList.add('hidden');
    document.getElementById('form-view').classList.remove('hidden');
    document.getElementById('list-btn').classList.remove('bg-purple-500', 'text-white');
    document.getElementById('list-btn').classList.add('bg-gray-200', 'text-gray-700');
    document.getElementById('create-btn').classList.add('bg-green-500', 'text-white');
    document.getElementById('create-btn').classList.remove('bg-gray-200', 'text-gray-700');
    
    // Set form for create mode
    isEditMode = false;
    currentProductId = null;
    
    document.getElementById('form-title').textContent = 'Tambah Varian Produk Baru';
    document.getElementById('form-subtitle').textContent = 'Buat varian produk baru untuk carousel varian di halaman utama';
    document.getElementById('submit-btn').textContent = 'Simpan Varian Produk';
    document.getElementById('image-label').textContent = 'Gambar Produk';
    
    // Set form action and debug
    console.log('Setting form action to store route...');
    document.getElementById('content-form').action = '{{ route("admin.content-products.carousel-varian.store") }}';
    document.getElementById('form-method').value = '';
    
    // Always set product category based on current filter
    console.log('Auto-setting product category to:', currentCategory);
    document.getElementById('product-category').value = currentCategory;
    
    // Set appropriate type_of_page based on category
    const typeOfPageMap = {
        'gentle-baby': 'gentle_baby_product',
        'mamina': 'mamina_product', 
        'nyam': 'nyam_product',
        'healo': 'healo_product'
    };
    document.getElementById('type_of_page').value = typeOfPageMap[currentCategory];
    console.log('Auto-set type_of_page to:', typeOfPageMap[currentCategory]);
    
    // Hide current image
    document.getElementById('current-image').classList.add('hidden');
    
    // Update product dropdown for current category
    updateProductDropdown(currentCategory);
    
    // Reset form
    resetForm();
}

function showEditForm(productId) {
    // Find product across all categories
    let product = null;
    for (const category in contentsData) {
        product = contentsData[category].find(p => p.content_id === productId);
        if (product) break;
    }
    
    if (!product) return;
    
    document.getElementById('list-view').classList.add('hidden');
    document.getElementById('form-view').classList.remove('hidden');
    
    // Set form for edit mode
    isEditMode = true;
    currentProductId = productId;
    
    // Clean title by removing prefix for display
    let displayTitle = product.title || product.master_item?.name_item || 'Unknown';
    const prefixesToRemove = [
        'Carousel Varian - Gentle baby - ',
        'Carousel Varian - Gentle Baby - ',
        'Carousel Varian - Mamina - ',
        'Carousel Varian - Nyam - ',
        'Carousel Varian - Healo - ',
        'Gentle baby - ',
        'Gentle Baby - ',
        'Mamina - ',
        'Nyam - ',
        'Healo - '
    ];
    for (const prefix of prefixesToRemove) {
        if (displayTitle.startsWith(prefix)) {
            displayTitle = displayTitle.substring(prefix.length);
            break;
        }
    }
    
    document.getElementById('form-title').textContent = `Edit Varian Produk: ${displayTitle}`;
    document.getElementById('form-subtitle').textContent = 'Edit informasi varian produk untuk carousel di halaman utama';
    document.getElementById('submit-btn').textContent = 'Update Varian Produk';
    document.getElementById('image-label').textContent = product.image ? 'Ganti Gambar Produk' : 'Gambar Produk';
    
    // Set form action and method
    document.getElementById('content-form').action = `{{ route("admin.content-products.carousel-varian.update", ":id") }}`.replace(':id', productId);
    document.getElementById('form-method').value = 'PUT';
    
    // Set product category for edit
    let categoryFromTitle = 'gentle-baby'; // default
    if (product.title) {
        if (product.title.toLowerCase().includes('mamina')) {
            categoryFromTitle = 'mamina';
        } else if (product.title.toLowerCase().includes('nyam')) {
            categoryFromTitle = 'nyam';
        } else if (product.title.toLowerCase().includes('healo')) {
            categoryFromTitle = 'healo';
        }
    }
    document.getElementById('product-category').value = categoryFromTitle;
    
    // Update product dropdown for the category from title, then fill form data
    updateProductDropdown(categoryFromTitle).then(() => {
        // Fill form with product data after dropdown is populated
        fillFormWithProductData(product);
    });
    
    // Show current image if exists
    if (product.image) {
        document.getElementById('current-image').classList.remove('hidden');
        document.getElementById('current-image-preview').src = product.image_url || `/storage/${product.image}`;
        document.getElementById('current-image-preview').alt = product.title || product.master_item?.name_item || '';
    } else {
        document.getElementById('current-image').classList.add('hidden');
    }
}

function fillFormWithProductData(product) {
    // Strip category prefix from title for editing
    let displayTitle = product.title || '';
    const categoryPrefixes = [
        'Carousel Varian - Gentle baby - ',
        'Carousel Varian - Gentle Baby - ',
        'Carousel Varian - Mamina - ',
        'Carousel Varian - Nyam - ',
        'Carousel Varian - Healo - ',
        'Gentle baby - ',
        'Gentle Baby - ',
        'Mamina - ',
        'Nyam - ',
        'Healo - '
    ];
    
    for (const prefix of categoryPrefixes) {
        if (displayTitle.startsWith(prefix)) {
            displayTitle = displayTitle.substring(prefix.length);
            break;
        }
    }
    
    // Set item_id if exists - wait a bit to ensure dropdown is populated
    if (product.item_id) {
        setTimeout(() => {
            const itemDropdown = document.getElementById('item_id');
            if (itemDropdown) {
                itemDropdown.value = product.item_id;
                console.log('Set item_id to:', product.item_id);
            }
        }, 100);
    }
    
    document.getElementById('title').value = displayTitle;
    document.getElementById('status').value = product.status ? '1' : '0';
    document.getElementById('type_of_page').value = product.type_of_page || 'homepage';
}

function resetForm() {
    document.getElementById('content-form').reset();
    
    // Always set default values based on current category filter
    document.getElementById('section').value = 'carousel-variant';
    document.getElementById('status').value = '1';
    
    // Always auto-set product category from current filter
    document.getElementById('product-category').value = currentCategory;
    
    // Set type_of_page based on current category
    const typeOfPageMap = {
        'gentle-baby': 'gentle_baby_product',
        'mamina': 'mamina_product', 
        'nyam': 'nyam_product',
        'healo': 'healo_product'
    };
    document.getElementById('type_of_page').value = typeOfPageMap[currentCategory];
    
    // Reset image preview
    const uploadPlaceholder = document.getElementById('upload-placeholder');
    const previewContainer = document.getElementById('image-preview-container');
    if (uploadPlaceholder && previewContainer) {
        uploadPlaceholder.classList.remove('hidden');
        previewContainer.classList.add('hidden');
        document.getElementById('image-preview').src = '';
    }
    
    console.log('Form reset - auto-set category:', currentCategory, 'type_of_page:', typeOfPageMap[currentCategory]);
}

// Initialize page
function initializePage() {
    // Get current category from URL params, fallback to default
    const urlParams = new URLSearchParams(window.location.search);
    const categoryFromUrl = urlParams.get('category');
    
    if (categoryFromUrl) {
        currentCategory = categoryFromUrl;
    }
    
    // Set filter selector value to match current category
    const categoryFilter = document.getElementById('category-filter');
    if (categoryFilter) {
        categoryFilter.value = currentCategory;
        console.log('Setting category filter to:', currentCategory);
    }
    
    // Load products for current category
    updateProductDropdown(currentCategory);
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Initialize data and display
    initializePage();
    
    // Initialize drag and drop functionality
    initializeDragAndDrop();
    
    // Add event listener for category filter change
    const categoryFilter = document.getElementById('category-filter');
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function(e) {
            const selectedCategory = e.target.value;
            console.log('Category changed to:', selectedCategory);
            handleCategoryChange(selectedCategory);
        });
    }
    
    // Add form submit handler for debugging and auto-category setting
    document.getElementById('content-form').addEventListener('submit', function(e) {
        console.log('=== FORM SUBMIT DEBUG ===');
        
        // Ensure product category is always set based on current filter
        const categoryField = document.getElementById('product-category');
        categoryField.value = currentCategory;
        
        // Ensure type_of_page is set based on category
        const typeOfPageMap = {
            'gentle-baby': 'gentle_baby_product',
            'mamina': 'mamina_product', 
            'nyam': 'nyam_product',
            'healo': 'healo_product'
        };
        document.getElementById('type_of_page').value = typeOfPageMap[currentCategory];
        
        const formData = new FormData(this);
        console.log('Form Action:', this.action);
        console.log('Form Method:', this.method);
        
        // Log all form data
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }
        
        // Check required fields
        const title = document.getElementById('title').value;
        const productCategory = document.getElementById('product-category').value;
        const typeOfPage = document.getElementById('type_of_page').value;
        
        console.log('Auto-set values:');
        console.log('- product_category:', productCategory, '(auto-set from filter:', currentCategory, ')');
        console.log('- type_of_page:', typeOfPage, '(auto-mapped from category)');
        console.log('- title:', title);
        
        if (!title) {
            console.error('ERROR: Title is required!');
            alert('Nama varian harus diisi!');
            e.preventDefault();
            return false;
        }
        
        console.log('Form validation passed, submitting with auto-set category...');
    });
    
    // Check if there are validation errors, if so show the form
    @if($errors->any())
        @if(request('action') === 'edit' && request('id'))
            showEditForm({{ request('id') }});
        @else
            showCreateForm();
        @endif
    @else
        showListView();
    @endif
});
</script>
@endsection