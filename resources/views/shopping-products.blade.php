@extends('layouts.ecommerce')

@section('title', 'Produk - Gentle Living')

@push('styles')
<style>
    .single-thumbnail {
        position: relative;
        transition: all 0.3s ease;
        cursor: pointer;
        opacity: 1;
    }
    
    .single-thumbnail:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }
    
    .single-thumbnail.border-blue-400 {
        opacity: 1;
        box-shadow: 0 4px 20px rgba(96, 165, 250, 0.3);
    }
    
    .border-blue-400 {
        border-color: #60a5fa !important;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.5);
    }
    
    .border-gray-300 {
        border-color: #d1d5db !important;
    }
    
    /* Full size thumbnail container styling */
    .thumbnail-container {
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.3s ease-in-out;
    }
    
    /* Full size thumbnail should not have padding when covering entire image */
    .thumbnail-container.w-full {
        padding: 0;
        border-radius: 0.75rem;
    }
    
    /* Always show main image thumbnail */
    .thumbnail-container .flex {
        min-height: 2.5rem;
    }
    
    /* Responsive thumbnail sizing */
    @media (max-width: 640px) {
        .single-thumbnail {
            width: 2rem !important;
            height: 2rem !important;
        }
    }
    
    /* Image hover effect for product cards */
    .group:hover .single-thumbnail {
        opacity: 1;
    }
    
    /* Thumbnail hover zoom effect */
    .single-thumbnail img {
        transition: transform 0.2s ease-in-out;
    }
    
    .single-thumbnail:hover img {
        transform: scale(1.1);
    }
    
    /* Loading state for images */
    .image-loading {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    /* Smooth transitions for main image changes */
    .main-product-image {
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    }
    
    .main-product-image.changing {
        opacity: 0.7;
        transform: scale(0.98);
    }
    
    /* Encrypted/Base64 image handling */
    .encrypted-image-indicator {
        position: absolute;
        top: 0.5rem;
        left: 0.5rem;
        background: rgba(34, 197, 94, 0.9);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    /* Base64 image optimization */
    img[src^="data:image/"] {
        image-rendering: optimizeQuality;
        -webkit-image-smoothing: true;
        -moz-image-smoothing: true;
        image-smoothing: true;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    
    
    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar Categories -->
            <div class="w-full lg:w-80 space-y-6">
                <!-- Categories Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 font-nunito">Kategori</h2>
                        
                        <!-- Category List -->
                        <div class="space-y-3">
                            <a href="{{ route('shopping.products') }}" 
                               class="flex items-center justify-between p-3 rounded-lg {{ !request('category') || request('category') == 'all' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }} font-medium transition-colors duration-200 group">
                                <span class="font-nunito">Semua produk</span>
                                <span class="{{ !request('category') || request('category') == 'all' ? 'bg-white text-blue-600' : 'bg-gray-200 text-gray-700 group-hover:bg-blue-100 group-hover:text-blue-600' }} px-2 py-1 rounded-full text-sm font-bold">{{ $categoryCounts['all'] }}</span>
                            </a>
                            
                            @php
                                // Gunakan data categories yang sudah dikirim dari controller
                                // $categories sudah tersedia dari LandingController@belanjaProduk
                            @endphp
                            
                            @foreach($categories as $category)
                                <a href="{{ route('shopping.products', ['category' => $category->slug]) }}" 
                                   class="flex items-center justify-between p-3 rounded-lg {{ request('category') == $category->slug ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }} transition-colors duration-200 group">
                                    <span class="font-nunito">{{ $category->name }}</span>
                                    <span class="{{ request('category') == $category->slug ? 'bg-white text-blue-600' : 'bg-gray-200 text-gray-700 group-hover:bg-blue-100 group-hover:text-blue-600' }} px-2 py-1 rounded-full text-sm font-semibold">{{ $categoryCounts[$category->slug] ?? 0 }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Filter Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 font-nunito">Urutkan</h3>
                        
                        <form method="GET" action="{{ route('shopping.products') }}">
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            
                            <div class="space-y-3">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="sort" value="name-asc" 
                                           class="text-blue-600 focus:ring-blue-500" 
                                           {{ request('sort') == 'name-asc' ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <span class="text-gray-700 font-nunito">Nama (A-Z)</span>
                                </label>
                                
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="sort" value="price-low" 
                                           class="text-blue-600 focus:ring-blue-500"
                                           {{ request('sort') == 'price-low' ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <span class="text-gray-700 font-nunito">Harga: Rendah ke Tinggi</span>
                                </label>
                                
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="sort" value="price-high" 
                                           class="text-blue-600 focus:ring-blue-500"
                                           {{ request('sort') == 'price-high' ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <span class="text-gray-700 font-nunito">Harga: Tinggi ke Rendah</span>
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1">
                <!-- Header Section -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2 font-nunito">
                        @if(request('category') && request('category') != 'all')
                            {{ $categories->firstWhere('slug', request('category'))->name ?? 'Semua Produk' }}
                        @else
                            Semua Produk
                        @endif
                    </h1>
                    <p class="text-gray-600 font-nunito">Menampilkan {{ $products->count() }} dari {{ $products->total() }} produk</p>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    
                    @forelse($products as $product)
                    <!-- Product Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                        <!-- Product Image with Thumbnails -->
                        <div class="relative bg-gray-100 h-64 flex items-center justify-center overflow-hidden">
                            <!-- Loading spinner -->
                            <div class="absolute inset-0 flex items-center justify-center bg-gray-200" id="loading-{{ $product->item_id }}">
                                <svg class="animate-spin h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            
                            @php
                                // Use the model's enhanced image accessor for consistent image display
                                $productImage = $product->image;
                                $fallbackImage = asset('storage/images/placeholder.jpg');
                                
                                // Debug info for main product image only
                                if (config('app.debug')) {
                                    \Log::info('Belanja Produk Image Debug', [
                                        'product_id' => $product->item_id,
                                        'product_name' => $product->name_item,
                                        'picture_item_raw' => $product->picture_item ?? 'NULL',
                                        'resolved_image' => $productImage,
                                        'resolved_image_type' => strpos($productImage, 'data:image/') === 0 ? 'base64' : 'url',
                                        'fallback_image' => $fallbackImage
                                    ]);
                                }
                            @endphp
                            
                            <div class="absolute top-3 right-3 z-10">
                                <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-md">
                                    {{ $product->netweight_item ?? 'Standard' }}
                                </span>
                            </div>
                            
                            <!-- Encrypted Image Indicator -->
                            @if(strpos($productImage, 'data:image/') === 0)
                                <div class="encrypted-image-indicator">
                                    <svg class="w-3 h-3 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 616 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Encrypted
                                </div>
                            @endif
                            
                            <!-- Main Product Image -->
                            <img id="mainImage-{{ $product->item_id }}" 
                                 src="{{ $productImage }}" 
                                 alt="{{ $product->name_item }}" 
                                 class="main-product-image w-full h-full object-cover transition-all duration-500 opacity-0"
                                 loading="lazy"
                                 data-product-id="{{ $product->item_id }}"
                                 data-original-src="{{ $productImage }}"
                                 data-is-base64="{{ strpos($productImage, 'data:image/') === 0 ? 'true' : 'false' }}"
                                 onerror="handleImageError(this, '{{ $fallbackImage }}')"
                                 onload="handleImageLoad(this)"
                                 style="min-height: 100%;"
                                 @if(strpos($productImage, 'data:image/') === 0)
                                 data-base64-size="{{ strlen($productImage) }}"
                                 @endif>
                                 
                            <!-- Full Size Product Thumbnail Display -->
                            <div class="absolute inset-0 z-10">
                                <div class="thumbnail-container w-full h-full">
                                    <!-- Single Main Product Thumbnail - Full Size -->
                                    <div class="single-thumbnail w-full h-full border-2 border-blue-400 rounded-lg overflow-hidden bg-white shadow-md">
                                        <img src="{{ $productImage }}" 
                                             alt="{{ $product->name_item }}" 
                                             class="w-full h-full object-cover"
                                             loading="lazy"
                                             onerror="this.style.display='none'">
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <!-- Product Info -->
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-2 font-nunito">{{ $product->name_item }}</h3>
                            <p class="text-sm text-gray-600 mb-3 font-nunito">{{ $product->description_item }}</p>
                            <p class="text-2xl font-bold text-blue-600 mb-1 font-nunito">{{ $product->formatted_price }}</p>
                            <p class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }} mb-4 font-nunito">
                                @if($product->stock > 0)
                                    Stok: {{ $product->stock }} {{ $product->unit_item }} tersedia
                                @else
                                    Stok habis
                                @endif
                            </p>
                            
                            @auth
                                @if(auth()->user()->role_id == 4)
                                    {{-- Button untuk Affiliate --}}
                                    <button onclick="showSubmissionModal({{ $product->item_id }}, '{{ $product->name_item }}', '{{ $product->image }}')"
                                       class="block w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors duration-200 font-nunito text-center {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}"
                                       {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        {{ $product->stock > 0 ? 'Ajukan Produk' : 'Stok Habis' }}
                                    </button>
                                @else
                                    {{-- Button untuk User Biasa --}}
                                    <a href="{{ route('product.detail', $product->item_id) }}" 
                                       class="block w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200 font-nunito text-center {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
                                        {{ $product->stock > 0 ? 'Lihat Detail' : 'Stok Habis' }}
                                    </a>
                                @endif
                            @else
                                {{-- Button untuk Guest --}}
                                <a href="{{ route('product.detail', $product->item_id) }}" 
                                   class="block w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200 font-nunito text-center {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
                                    {{ $product->stock > 0 ? 'Lihat Detail' : 'Stok Habis' }}
                                </a>
                            @endauth
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-12">
                        <div class="text-gray-400 mb-4">
                            <svg class="w-24 h-24 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2 font-nunito">Tidak ada produk</h3>
                        <p class="text-gray-500 font-nunito">Tidak ada produk yang ditemukan dalam kategori ini.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="mt-12 flex justify-center">
                        <nav class="flex items-center space-x-2">
                            {{-- Previous Page Link --}}
                            @if ($products->onFirstPage())
                                <span class="px-3 py-2 text-gray-300 cursor-not-allowed">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $products->previousPageUrl() }}" class="px-3 py-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </a>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                @if ($page == $products->currentPage())
                                    <span class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="px-4 py-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">{{ $page }}</a>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($products->hasMorePages())
                                <a href="{{ $products->nextPageUrl() }}" class="px-3 py-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            @else
                                <span class="px-3 py-2 text-gray-300 cursor-not-allowed">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </span>
                            @endif
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// Handle image loading and errors for better UX
function handleImageLoad(img) {
    const productId = img.getAttribute('data-product-id');
    const loadingElement = document.getElementById('loading-' + productId);
    const isBase64 = img.getAttribute('data-is-base64') === 'true';
    
    // Hide loading spinner
    if (loadingElement) {
        loadingElement.style.display = 'none';
    }
    
    // Remove loading animation class
    img.classList.remove('image-loading');
    
    // Show image with fade-in effect
    img.classList.remove('opacity-0');
    img.classList.add('opacity-100');
    
    // Show thumbnails container if it exists
    const productCard = img.closest('.bg-white');
    if (productCard) {
        const thumbnailContainer = productCard.querySelector('.thumbnail-container');
        if (thumbnailContainer) {
            thumbnailContainer.style.opacity = '1';
            thumbnailContainer.style.transform = 'translateY(0)';
        }
    }
    
    // Log successful load for Base64/encrypted images
    if (isBase64 && console) {
        const base64Size = img.getAttribute('data-base64-size');
        console.log('Base64/Encrypted image loaded for product ' + productId + ' (size: ' + base64Size + ' chars)');
    }
    
    // Update encrypted indicator if exists
    const encryptedIndicator = img.closest('.relative').querySelector('.encrypted-image-indicator');
    if (encryptedIndicator && isBase64) {
        encryptedIndicator.style.display = 'block';
    }
}

function handleImageError(img, fallbackUrl) {
    const productId = img.getAttribute('data-product-id');
    const loadingElement = document.getElementById('loading-' + productId);
    const isBase64 = img.getAttribute('data-is-base64') === 'true';
    
    // Hide loading spinner
    if (loadingElement) {
        loadingElement.style.display = 'none';
    }
    
    // Log error type
    if (isBase64) {
        console.warn('Base64 image failed to load for product ' + productId + ', possibly corrupted data');
    } else {
        console.log('File image failed to load for product ' + productId + ', using fallback');
    }
    
    // Set fallback image
    img.src = fallbackUrl;
    img.classList.remove('opacity-0');
    img.classList.add('opacity-75'); // Slightly faded for fallback
    
    // Remove base64 indicator since we're using fallback
    img.setAttribute('data-is-base64', 'false');
}

// Simplified script - no thumbnail switching needed

// Preload images and initialize thumbnails for better performance
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[data-product-id]');
    
    images.forEach(function(img) {
        // Set loading state initially
        img.style.opacity = '0';
        img.classList.add('image-loading');
        
        // If image is already cached, trigger onload immediately
        if (img.complete) {
            handleImageLoad(img);
        }
    });
    
    // Initialize thumbnail containers
    const thumbnailContainers = document.querySelectorAll('.thumbnail-container');
    thumbnailContainers.forEach(function(container) {
        // Delay showing thumbnail container slightly
        setTimeout(function() {
            container.style.opacity = '0.9';
            container.style.transform = 'translateY(0)';
        }, 500);
    });
    
    // Preload thumbnail images
    const thumbnailImages = document.querySelectorAll('.single-thumbnail img');
    let loadedThumbnails = 0;
    
    thumbnailImages.forEach(function(thumbImg) {
        if (thumbImg.complete) {
            loadedThumbnails++;
        } else {
            thumbImg.onload = function() {
                loadedThumbnails++;
            };
        }
    });
    
    console.log('Belanja Produk page loaded with', images.length, 'product images and', thumbnailImages.length, 'thumbnails');
});

// Affiliate Submission Modal Functions
function showSubmissionModal(itemId, itemName, itemImage) {
    // Set data ke modal
    document.getElementById('modalItemId').value = itemId;
    document.getElementById('modalItemName').textContent = itemName;
    document.getElementById('modalItemImage').src = itemImage;
    
    // Tampilkan modal
    document.getElementById('submissionModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeSubmissionModal() {
    document.getElementById('submissionModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

async function confirmSubmission() {
    const itemId = document.getElementById('modalItemId').value;
    
    // Show loading state
    const confirmBtn = event.target;
    const originalText = confirmBtn.innerHTML;
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    
    try {
        // Check if user has active submission
        const response = await fetch('{{ route("affiliate.submission.check") }}', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const data = await response.json();
        
        if (data.has_active) {
            // User has active submission, show warning
            closeSubmissionModal();
            showActiveSubmissionWarning(data.submission);
        } else {
            // No active submission, proceed to form
            window.location.href = '{{ route("affiliate.submission.create") }}?item_id=' + itemId;
        }
    } catch (error) {
        console.error('Error checking active submission:', error);
        // On error, still allow to proceed (backend will handle validation)
        window.location.href = '{{ route("affiliate.submission.create") }}?item_id=' + itemId;
    } finally {
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = originalText;
    }
}

function showActiveSubmissionWarning(submission) {
    document.getElementById('activeSubmissionModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Set submission details
    if (submission) {
        document.getElementById('activeItemName').textContent = submission.item_name;
        document.getElementById('activeStatus').textContent = submission.status_label;
        document.getElementById('activeCreatedAt').textContent = submission.created_at;
        
        // Set status badge color
        const statusBadge = document.getElementById('activeStatus');
        statusBadge.className = 'px-3 py-1.5 rounded-full text-xs font-semibold inline-block';
        
        switch(submission.status) {
            case 'pending':
                statusBadge.classList.add('bg-yellow-100', 'text-yellow-800');
                break;
            case 'approved':
                statusBadge.classList.add('bg-blue-100', 'text-blue-800');
                break;
            case 'shipped':
                statusBadge.classList.add('bg-purple-100', 'text-purple-800');
                break;
            case 'received':
                statusBadge.classList.add('bg-orange-100', 'text-orange-800');
                break;
            default:
                statusBadge.classList.add('bg-gray-100', 'text-gray-800');
        }
    }
}

function closeActiveSubmissionWarning() {
    document.getElementById('activeSubmissionModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function goToSubmissions() {
    window.location.href = '{{ route("affiliate.submissions.list") }}';
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('submissionModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeSubmissionModal();
            }
        });
    }
});
</script>

<!-- Modal Konfirmasi Pengajuan -->
@auth
@if(auth()->user()->role_id == 4)
<div id="submissionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl transform transition-all">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white font-nunito">Konfirmasi Pengajuan Produk</h3>
                <button onclick="closeSubmissionModal()" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <!-- Product Info -->
            <div class="flex items-center space-x-4 mb-6 bg-gray-50 p-4 rounded-lg">
                <img id="modalItemImage" src="" alt="" class="w-20 h-20 object-cover rounded-lg shadow-md">
                <div class="flex-1">
                    <p class="text-sm text-gray-600 font-nunito mb-1">Produk yang akan diajukan:</p>
                    <p id="modalItemName" class="text-lg font-bold text-gray-800 font-nunito"></p>
                </div>
            </div>
            
            <!-- Warning Info -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <svg class="w-5 h-5 text-yellow-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-yellow-800 font-nunito mb-1">Perhatian!</p>
                        <ul class="text-sm text-yellow-700 font-nunito space-y-1 list-disc list-inside">
                            <li>Anda hanya dapat mengajukan 1 produk</li>
                            <li>Setelah barang diterima, Anda wajib upload video promosi dalam 14 hari</li>
                            <li>Jika tidak upload video, akun akan diblacklist</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <p class="text-gray-700 font-nunito mb-6">
                Apakah Anda yakin ingin mengajukan produk ini? Anda akan diarahkan ke halaman pengisian alamat pengiriman.
            </p>
            
            <input type="hidden" id="modalItemId" value="">
        </div>
        
        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 rounded-b-2xl flex space-x-3">
            <button onclick="closeSubmissionModal()" 
                    class="flex-1 bg-gray-200 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-200 font-nunito">
                Batal
            </button>
            <button onclick="confirmSubmission()" 
                    class="flex-1 bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors duration-200 font-nunito">
                Ya, Lanjutkan
            </button>
        </div>
    </div>
</div>
@endif

<!-- Modal Peringatan Pengajuan Aktif -->
@if(auth()->user()->role_id == 4)
<div id="activeSubmissionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl transform transition-all">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white font-nunito">Pengajuan Masih Aktif</h3>
                <button onclick="closeActiveSubmissionWarning()" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <!-- Warning Icon -->
            <div class="flex justify-center mb-6">
                <div class="bg-red-100 rounded-full p-4">
                    <svg class="w-16 h-16 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Message -->
            <div class="text-center mb-6">
                <h4 class="text-lg font-bold text-gray-900 font-nunito mb-2">Tidak Dapat Mengajukan Produk Baru</h4>
                <p class="text-gray-600 font-nunito mb-4">Anda masih memiliki pengajuan yang belum selesai. Harap selesaikan pengajuan yang ada terlebih dahulu.</p>
            </div>
            
            <!-- Active Submission Details -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 font-nunito mb-1">Produk yang Diajukan:</p>
                        <p id="activeItemName" class="text-sm font-bold text-gray-800 font-nunito">-</p>
                    </div>
                    <span id="activeStatus" class="px-3 py-1.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">-</span>
                </div>
                <div class="border-t border-gray-200 pt-3">
                    <p class="text-xs text-gray-500 font-nunito">Tanggal Pengajuan:</p>
                    <p id="activeCreatedAt" class="text-sm font-semibold text-gray-700 font-nunito">-</p>
                </div>
            </div>
            
            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-blue-800 font-nunito mb-1">Informasi</p>
                        <p class="text-xs text-blue-700 font-nunito">Anda hanya dapat mengajukan 1 produk dalam satu waktu. Setelah pengajuan selesai, Anda dapat mengajukan produk baru.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 rounded-b-2xl flex space-x-3">
            <button onclick="closeActiveSubmissionWarning()" 
                    class="flex-1 bg-gray-200 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-200 font-nunito">
                Tutup
            </button>
            <button onclick="goToSubmissions()" 
                    class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200 font-nunito">
                Lihat Pengajuan Saya
            </button>
        </div>
    </div>
</div>
@endif
@endauth

@endsection
