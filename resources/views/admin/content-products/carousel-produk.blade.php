@extends('layouts.admin.app')

@section('title', 'Kelola Carousel Produk')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
@endpush

@section('content')
<div class="p-6">
    <!-- Include shared navigation component -->
    @include('admin.content-products.navigation')

    <!-- LIST VIEW -->
    <div id="list-view" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-start">
                <!-- Judul dan Deskripsi -->
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Kelola Banner Produk</h2>
                    <p class="text-gray-600">
                        Kelola gambar produk yang ditampilkan di banner utama.
                    </p>
                </div>

                <!-- Filter + Tombol Tambah Konten -->
                <div class="flex items-center space-x-3">
                    <!-- Filter Kategori Produk -->
                    <select id="product-filter" 
                            onchange="handleProductFilterChange(this.value)"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                        <option value="gentle-baby">Gentle Baby</option>
                        <option value="healo">Healo</option>
                        <option value="mamina">Mamina</option>
                        <option value="nyam">Nyam! MPASI</option>
                    </select>
                    
                    <!-- Tombol Tambah Konten -->
                    <button onclick="showCreateForm()" id="create-btn" 
                            class="px-4 py-2 bg-[#785576] text-white rounded-lg hover:bg-[#694966] transition-colors shadow-sm">
                        <i class="fas fa-camera mr-2"></i>Tambah Gambar
                    </button>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Gallery View -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-4">
            @forelse($contents as $index => $content)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-200">
                <!-- Image Section -->
                <div class="aspect-video bg-gray-100 relative">
                    @if($content->image)
                        <img src="{{ asset('storage/' . $content->image) }}" 
                             alt="{{ $content->title }}" 
                             class="w-full h-full object-contain">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                            <i class="fas fa-image text-gray-400 text-2xl"></i>
                        </div>
                    @endif

                    <!-- Number Badge -->
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
                                $displayTitle = $content->title ?: 'Tidak ada judul';
                                // Remove category prefixes for display
                                $prefixesToRemove = [
                                    'Carousel Produk - Gentle baby - ',
                                    'Carousel Produk - Gentle Baby - ',
                                    'Carousel Produk - Mamina - ',
                                    'Carousel Produk - Nyam - ',
                                    'Carousel Produk - Healo - ',
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
                                   title="Edit Gambar"
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
                            <form action="{{ route('admin.content-products.carousel-produk.destroy', $content->content_id) }}" 
                                  method="POST" 
                                  class="inline"
                                  id="delete-form-{{ $content->content_id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        onclick="confirmDelete({{ $content->content_id }}, '{{ addslashes($content->title ?: 'Tanpa judul') }}')"
                                        class="group relative text-white p-1.5 rounded-md text-xs transition-all duration-200 hover:scale-110"
                                        title="Hapus Gambar"
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

                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-box-open text-gray-300 text-4xl mb-3"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada konten carousel</h3>
                        <p class="text-gray-600 mb-4 text-sm max-w-sm">Mulai dengan menambahkan konten carousel pertama untuk menampilkan gambar produk</p>
                        <button onclick="showCreateForm()" 
                               class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition-colors inline-flex items-center text-sm">
                            <i class="fas fa-plus mr-2"></i>Tambah Konten Pertama
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
                    <p id="form-subtitle" class="text-gray-600 mt-1">Tambah konten baru untuk ditampilkan di carousel produk utama</p>
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
            <input type="hidden" name="section" id="section" value="carousel-produk">
            <input type="hidden" name="type_of_page" id="type_of_page" value="homepage">
            <input type="hidden" name="body" id="body" value="Tanpa deskripsi">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div>
                    <!-- Nama Gambar -->
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Gambar <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" required placeholder="Contoh: Banner Produk Gentle Baby"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>
                
                <!-- Right Column -->
                <div>
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

                    <!-- Image Upload -->
                    <div class="mb-4">
                        <label for="image" id="image-label" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-cloud-upload-alt mr-2 text-blue-600"></i>Gambar Konten <span class="text-red-500">*</span>
                        </label>

                        <!-- Custom Upload Area with Drag & Drop -->
                        <div class="relative">
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
                        
                        <!-- Hidden File Input -->
                        <input type="file" name="image" id="image" accept="image/*" required
                               onchange="previewImage(this)"
                               class="hidden">
                        
                        <!-- File Info and Error Messages -->
                        <div class="mt-2 space-y-1">
                            <p class="text-sm text-gray-600 leading-relaxed">
                                <i class="fas fa-info-circle text-blue-600 mr-1"></i>
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
                       class="px-4 py-2 bg-[#c94757] text-white rounded-lg hover:bg-[#b93e4f] transition-colors">
                    Batal
                </button>
                <button type="submit" id="submit-btn"
                        class="px-4 py-2 bg-[#785576] text-white rounded-lg hover:bg-[#694966] transition-colors">
                    Simpan Konten
                </button>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert2 JavaScript -->
<script src="{{ asset('js/sweetalert2@11.js') }}"></script>

<script>
let isEditMode = false;
let currentContentId = null;
let currentProduct = 'gentle-baby'; // Default product category

// Contents data for edit functionality - organized by category
let contentsData = @json($contentsByCategory ?? []);
let allContents = @json($contents ?? []);

// SweetAlert Delete Confirmation with Purple Theme
function confirmDelete(contentId, itemName) {
    Swal.fire({
        title: 'Hapus Gambar?',
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

// Handle product filter change
function handleProductFilterChange(selectedProduct) {
    currentProduct = selectedProduct;
    
    // Reload page with category filter
    const url = new URL(window.location.href);
    url.searchParams.set('category', selectedProduct);
    window.location.href = url.toString();
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
    
    if (!uploadArea || !fileInput) return;
    
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



function showListView() {
    document.getElementById('list-view').classList.remove('hidden');
    document.getElementById('form-view').classList.add('hidden');
    
    // Reset form
    resetForm();
}

    function showCreateForm() {
        document.getElementById('list-view').classList.add('hidden');
        document.getElementById('form-view').classList.remove('hidden');
        
        // Set form for create mode
        isEditMode = false;
        currentContentId = null;
        
        document.getElementById('form-title').textContent = 'Tambah Gambar Carousel';
        document.getElementById('form-subtitle').textContent = 'Upload gambar baru untuk carousel produk';
        document.getElementById('submit-btn').textContent = 'Upload Gambar';
        document.getElementById('image-label').innerHTML = '<i class="fas fa-cloud-upload-alt mr-2 text-green-500"></i>Upload Gambar <span class="text-red-500">*</span>';
        
        // Set form action
        document.getElementById('content-form').action = '{{ route("admin.content-products.carousel-produk.store") }}';
        document.getElementById('form-method').value = '';
        
        // Set product category
        document.getElementById('product-category').value = currentProduct;
        
        // Set type_of_page based on current product category
        const typeOfPageMap = {
            'gentle-baby': 'gentle_baby_product',
            'mamina': 'mamina_product',
            'nyam': 'nyam_product',
            'healo': 'healo_product'
        };
        document.getElementById('type_of_page').value = typeOfPageMap[currentProduct] || 'homepage';
        
        // Set image field as required for create mode
        document.getElementById('image').setAttribute('required', 'required');
        
        // Hide current image
        document.getElementById('current-image').classList.add('hidden');
        
        // Reset form
        resetForm();
    }

    function showEditForm(contentId) {
        // Find content across all categories
        let content = null;
        for (const category in contentsData) {
            content = contentsData[category].find(c => c.content_id === contentId);
            if (content) break;
        }
        
        if (!content) return;
        
        document.getElementById('list-view').classList.add('hidden');
        document.getElementById('form-view').classList.remove('hidden');
        
        // Set form for edit mode
        isEditMode = true;
        currentContentId = contentId;
        
        // Clean title by removing prefix for display
        let displayTitle = content.title || 'Tanpa Judul';
        const prefixesToRemove = [
            'Carousel Produk - Gentle baby - ',
            'Carousel Produk - Gentle Baby - ',
            'Carousel Produk - Mamina - ',
            'Carousel Produk - Nyam - ',
            'Carousel Produk - Healo - ',
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
        
        document.getElementById('form-title').textContent = `Edit Gambar: ${displayTitle}`;
        document.getElementById('form-subtitle').textContent = 'Edit gambar carousel produk';
        document.getElementById('submit-btn').textContent = 'Update Gambar Banner';
        document.getElementById('image-label').innerHTML = content.image ? '<i class="fas fa-cloud-upload-alt mr-2 text-green-500"></i>Ganti Gambar' : '<i class="fas fa-cloud-upload-alt mr-2 text-green-500"></i>Upload Gambar <span class="text-red-500">*</span>';
        
        // Set form action and method
        document.getElementById('content-form').action = `{{ route("admin.content-products.carousel-produk.update", ":id") }}`.replace(':id', contentId);
        document.getElementById('form-method').value = 'PUT';
        
        // Set product category for edit
        let categoryFromTitle = 'gentle-baby'; // default
        if (content.title) {
            if (content.title.toLowerCase().includes('mamina')) {
                categoryFromTitle = 'mamina';
            } else if (content.title.toLowerCase().includes('nyam')) {
                categoryFromTitle = 'nyam';
            } else if (content.title.toLowerCase().includes('healo')) {
                categoryFromTitle = 'healo';
            }
        }
        document.getElementById('product-category').value = categoryFromTitle;
        
        // Remove required attribute for image field in edit mode
        document.getElementById('image').removeAttribute('required');
        
        // Show current image if exists
        if (content.image) {
            document.getElementById('current-image').classList.remove('hidden');
            document.getElementById('current-image-preview').src = content.image_url || `/storage/${content.image}`;
            document.getElementById('current-image-preview').alt = content.title || 'Content image';
        } else {
            document.getElementById('current-image').classList.add('hidden');
        }
        
        // Fill form with content data
        fillFormWithContentData(content);
    }

function fillFormWithContentData(content) {
    // Strip category prefix from title for editing
    let displayTitle = content.title || '';
    const categoryPrefixes = [
        'Carousel Produk - Gentle baby - ',
        'Carousel Produk - Gentle Baby - ',
        'Carousel Produk - Mamina - ',
        'Carousel Produk - Nyam - ',
        'Carousel Produk - Healo - ',
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
    
    document.getElementById('title').value = displayTitle;
    // Hidden fields - no need to update UI elements for these
    document.getElementById('body').value = content.body || 'Tanpa deskripsi';
    document.getElementById('section').value = content.section || 'carousel-produk';
    document.getElementById('type_of_page').value = content.type_of_page || 'homepage';
}function resetForm() {
    document.getElementById('content-form').reset();
    document.getElementById('section').value = 'carousel-produk';
    
    // Set type_of_page based on current product category
    const typeOfPageMap = {
        'gentle-baby': 'gentle_baby_product',
        'mamina': 'mamina_product',
        'nyam': 'nyam_product',
        'healo': 'healo_product'
    };
    document.getElementById('type_of_page').value = typeOfPageMap[currentProduct] || 'homepage';
    
    document.getElementById('body').value = 'Tanpa deskripsi';
    document.getElementById('product-category').value = currentProduct;
    
    // Reset image preview
    const uploadPlaceholder = document.getElementById('upload-placeholder');
    const previewContainer = document.getElementById('image-preview-container');
    if (uploadPlaceholder && previewContainer) {
        uploadPlaceholder.classList.remove('hidden');
        previewContainer.classList.add('hidden');
        document.getElementById('image-preview').src = '';
    }
}

    // Initialize page
    function initializePage() {
        // Get current category from URL params
        const urlParams = new URLSearchParams(window.location.search);
        const categoryFromUrl = urlParams.get('category');
        
        if (categoryFromUrl) {
            currentProduct = categoryFromUrl;
        }
        
        // Set filter selector value
        const productFilter = document.getElementById('product-filter');
        if (productFilter) {
            productFilter.value = currentProduct;
        }
    }

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize data and display
        initializePage();
        
        // Initialize drag and drop functionality
        initializeDragAndDrop();

        
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
