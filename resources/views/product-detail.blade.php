@extends('layouts.ecommerce')

@section('title', $product->name_item . ' - Gentle Living')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">
<style>
.variant-thumbnail {
    position: relative;
    transition: all 0.3s ease;
}

.variant-thumbnail:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.variant-thumbnail img {
    transition: all 0.3s ease;
}

.variant-thumbnail:hover img {
    transform: scale(1.05);
}

.thumbnail-container .text-center:hover .variant-thumbnail {
    border-color: #14B8A6 !important;
}
</style>
@endpush

@section('content')
@php
// Get current product images from controller data
$currentImages = $product->product_images;
@endphp

<div class="min-h-screen bg-gray-50">
    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex items-center space-x-2 text-sm text-gray-500 font-nunito">
                <a href="{{ route('home') }}" class="hover:text-teal-600 transition-colors duration-200">Home</a>
                <span>/</span>
                @php
                    $categorySlug = strtolower(str_replace(' ', '-', $product->category));
                @endphp
                <a id="breadcrumb-category" href="{{ route('shopping.products', ['category' => $categorySlug]) }}" class="hover:text-teal-600 transition-colors duration-200">{{ $product->category }}</a>
                <span>/</span>
                <span id="breadcrumb-product" class="text-gray-800 font-medium">{{ $product->name_item }}</span>
            </nav>
        </div>
    </div>

    <!-- Main Product Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_2fr] gap-8 mb-12">
            
            {{-- Product Images --}}
            <div class="space-y-4">
                {{-- Main Image --}}
                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                    <img id="main-image" 
                         src="{{ asset($currentImages['main']) }}" 
                         alt="{{ $product->name_item }}" 
                         class="w-full h-full object-cover"
                         onerror="this.onerror=null; this.src='{{ asset('storage/gentle-baby/placeholder.jpg') }}'">
                </div>

                {{-- Thumbnail Images - All Variants --}}
                <div class="flex space-x-2 product-gallery overflow-x-auto">
                    @if(isset($productVariants) && $productVariants->count() > 0)
                        @foreach($productVariants as $index => $variant)
                            @php
                                $variantMainImage = $variant->product_images['main'] ?? 'storage/gentle-baby/placeholder.jpg';
                                $isCurrentVariant = $variant->item_id == $product->item_id;
                            @endphp
                            <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 cursor-pointer border-2 transition-all duration-200 {{ $isCurrentVariant ? 'border-teal-500' : 'border-transparent' }} hover:border-teal-500 variant-thumbnail" 
                                 onclick="selectVariantFromThumbnail({{ $variant->item_id }}, this)"
                                 data-variant-id="{{ $variant->item_id }}"
                                 data-variant-name="{{ $variant->name_item }}"
                                 data-variant-size="{{ $variant->netweight_item }}"
                                 title="Klik untuk memilih ukuran {{ $variant->netweight_item }}">
                                <img src="{{ asset($variantMainImage) }}" 
                                     alt="{{ $variant->name_item }} - {{ $variant->netweight_item }}" 
                                     class="w-full h-full object-cover"
                                     onerror="this.onerror=null; this.src='{{ asset('storage/gentle-baby/placeholder.jpg') }}'">
                            </div>
                        @endforeach
                    @else
                        {{-- Fallback to current product images if no variants --}}
                        @if(count($currentImages['thumbnails']) > 0)
                            @foreach($currentImages['thumbnails'] as $index => $thumbnail)
                                <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 cursor-pointer border-2 transition-all duration-200 {{ $index === 0 ? 'border-teal-500' : 'border-transparent' }} hover:border-teal-500 product-thumbnail" 
                                     onclick="changeMainImage('{{ asset($thumbnail) }}', this)">
                                    <img src="{{ asset($thumbnail) }}" 
                                         alt="{{ $product->name_item }} - Image {{ $index + 1 }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.onerror=null; this.src='{{ asset('storage/gentle-baby/placeholder.jpg') }}'">
                                </div>
                            @endforeach
                        @else
                            {{-- Single main image as thumbnail if no other images --}}
                            <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 border-2 border-teal-500">
                                <img src="{{ asset($currentImages['main']) }}" 
                                     alt="{{ $product->name_item }}" 
                                     class="w-full h-full object-cover"
                                     onclick="changeMainImage(this.src)"
                                     onerror="this.onerror=null; this.src='{{ asset('storage/gentle-baby/placeholder.jpg') }}'">
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Product Info --}}
            <div class="space-y-6">
                {{-- Product Name --}}
                <div>
                    <h1 id="product-name" class="text-3xl font-bold text-gray-900 mb-4 font-nunito">{{ $product->name_item }}</h1>
                </div>

                {{-- Description --}}
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-900 mb-2 font-nunito">Deskripsi</h3>
                    @if($product->description_item)
                        <p id="product-description" class="text-gray-600 leading-relaxed font-nunito">{{ $product->description_item }}</p>
                    @else
                        <p id="product-description" class="text-gray-500 italic font-nunito">Deskripsi produk belum tersedia.</p>
                    @endif
                </div>

                {{-- Variants --}}
                @if(isset($productVariants) && $productVariants->count() > 1)
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-900 mb-3 font-nunito">Varian</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($productVariants as $variant)
                            @php
                                // Extract display label untuk varian
                                $displayLabel = $variant->netweight_item;
                                if (stripos($variant->name_item, 'Mamina') !== false) {
                                    if (preg_match('/(\d+)\s*kantong/i', $variant->netweight_item, $matches)) {
                                        $displayLabel = $matches[1] . ' Kantong';
                                    }
                                }
                            @endphp
                            <button class="variant-btn {{ $variant->item_id == $product->item_id ? 'active border-teal-500 bg-teal-50' : '' }} px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:border-teal-500 hover:bg-teal-50 transition-colors duration-200 font-nunito" 
                                    onclick="selectVariant(this, {{ $variant->item_id }})"
                                    data-variant-id="{{ $variant->item_id }}"
                                    data-variant-name="{{ $variant->name_item }}"
                                    data-variant-price="{{ $variant->sell_price }}"
                                    data-variant-price-formatted="{{ $variant->formatted_price }}"
                                    data-variant-stock="{{ $variant->stock }}"
                                    data-variant-size="{{ $variant->netweight_item }}"
                                    data-variant-description="{{ addslashes($variant->description_item ?? 'Deskripsi produk belum tersedia.') }}"
                                    data-variant-image="{{ asset($variant->product_images['main']) }}"
                                    data-variant-thumbnails="{{ json_encode(array_map(fn($img) => asset($img), $variant->product_images['thumbnails'])) }}">
                                {{ $displayLabel }}
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Quantity --}}
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-900 mb-3 font-nunito">Kuantitas</h3>
                    <div class="flex items-center space-x-2">
                        <button class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors duration-200" onclick="decreaseQuantity()">
                            <i class="fas fa-minus text-sm"></i>
                        </button>
                        <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}" 
                               class="w-16 h-10 text-center border border-gray-300 rounded-lg focus:border-teal-500 focus:ring-1 focus:ring-teal-500 font-nunito">
                        <button class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors duration-200" onclick="increaseQuantity()">
                            <i class="fas fa-plus text-sm"></i>
                        </button>
                        <span class="text-sm text-gray-500 ml-4 font-nunito">Stok <span id="current-stock">{{ $product->stock }}</span></span>
                    </div>
                    
                    @php
                        $customerTypeId = session('customer_type_id', 1);
                        $mamina5KantongIds = [45, 48, 51];
                        $isMamina5Kantong = in_array($product->item_id, $mamina5KantongIds);
                        $isReseller = $customerTypeId == 3;
                    @endphp
                    
                    @if($isReseller && $isMamina5Kantong)
                    <div class="mt-2">
                        <p class="text-xs text-gray-600 font-nunito">
                            <span class="text-amber-600 font-semibold">⚠️ Info:</span> Anda bisa menambahkan jumlah berapa saja, namun total dari 3 rasa harus minimal 10 box dan kelipatan 10 saat checkout.
                        </p>
                    </div>
                    @endif
                </div>

                {{-- Price --}}
                <div class="mb-8">
                    <p id="product-price" class="text-3xl font-bold text-teal-600 font-nunito">
                        <span id="total-price">{{ $product->formatted_price }}</span>
                    </p>
                    <p class="text-sm text-gray-600 font-nunito mt-1">Harga Satuan: <span id="unit-price">{{ $product->formatted_price }}</span></p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex space-x-4">
                    {{-- Add to Cart Button --}}
                    <button class="flex-1 bg-white border-2 border-teal-600 text-teal-600 px-6 py-3 rounded-lg font-semibold hover:bg-teal-50 transition-colors duration-200 font-nunito flex items-center justify-center add-to-cart-btn" onclick="addToCart()">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0L17 18m0 0v3a1 1 0 01-1 1H8a1 1 0 01-1-1v-3m10 0a1 1 0 01-1 1H8a1 1 0 01-1-1"></path>
                        </svg>
                        Masukkan Keranjang
                    </button>
                    
                    @php
                        $customerTypeId = session('customer_type_id', 1);
                        $mamina5KantongIds = [45, 48, 51];
                        $isMamina5Kantong = in_array($product->item_id, $mamina5KantongIds);
                        $isReseller = $customerTypeId == 3;
                        $disableBuyNow = $isReseller && $isMamina5Kantong;
                    @endphp
                    
                    {{-- Buy Now Button --}}
                    @if($disableBuyNow)
                        <button class="flex-1 bg-gray-300 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed font-nunito" disabled title="Untuk reseller, silakan tambahkan ke keranjang terlebih dahulu. Minimal pembelian 10 box dari kombinasi 3 rasa">
                            Beli Sekarang (Tidak Tersedia)
                        </button>
                    @else
                        <button class="flex-1 bg-teal-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-teal-700 transition-colors duration-200 font-nunito" onclick="buyNow()">
                            Beli Sekarang
                        </button>
                    @endif
                </div>
                
                @if($disableBuyNow)
                <!-- Notifikasi khusus untuk Mamina 5 kantong - Reseller -->
                <div class="mt-4 p-4 bg-amber-50 border-l-4 border-amber-400 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-amber-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-amber-800 font-nunito">Pembelian Reseller - Mamina 5 Kantong</h3>
                            <ul class="text-xs text-amber-700 font-nunito mt-2 space-y-1">
                                <li>• Total pembelian dari 3 rasa (Original, Jeruk Nipis, Belimbing Wuluh) minimal 10 box</li>
                                <li>• Pembelian harus dalam kelipatan 10 box</li>
                                <li>• Kombinasi rasa bebas (contoh: 4 Original + 3 Jeruk Nipis + 3 Belimbing Wuluh = 10 box)</li>
                                <li>• Silakan tambahkan produk ke keranjang terlebih dahulu</li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Penilaian Produk Section --}}
        <div class="mt-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 font-nunito">
                        Penilaian Produk 
                        @if($productReviews->count() > 0)
                            <span class="text-sm text-gray-500 font-normal">({{ $productReviews->count() }} ulasan)</span>
                        @endif
                    </h2>
                    
                    @if($productReviews->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($productReviews as $review)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-semibold text-gray-800 font-nunito">{{ $review->user->name ?? 'User' }}</span>
                                    <div class="flex items-center">
                                        @for($star = 1; $star <= 5; $star++)
                                            <svg class="w-4 h-4 {{ $star <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="text-gray-600 text-sm font-nunito leading-relaxed mb-2">
                                        {{ $review->comment }}
                                    </p>
                                @endif
                                <div class="text-xs text-gray-400 font-nunito">
                                    {{ $review->created_at->format('d M Y') }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto mb-4 text-gray-300">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2 font-nunito">Belum ada ulasan</h3>
                            <p class="text-gray-600 font-nunito">Jadilah yang pertama memberikan ulasan untuk produk ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kategori Serupa Section -->
        <div class="mt-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 font-nunito text-center">Kategori Serupa</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @forelse($similarProducts as $similarProduct)
                        <div class="border border-gray-200 rounded-lg overflow-hidden bg-white hover:shadow-md transition-shadow duration-200 product-card">
                            <div class="h-48 overflow-hidden">
                                <img src="{{ asset($similarProduct->image) }}" 
                                     alt="{{ $similarProduct->name_item }}" 
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                     onerror="this.onerror=null; this.src='{{ asset('storage/gentle-baby/placeholder.jpg') }}'">
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-2 font-nunito text-sm line-clamp-2">{{ $similarProduct->name_item }}</h3>
                                <p class="text-teal-600 font-bold mb-3 font-nunito">{{ $similarProduct->formatted_price }}</p>
                                <a href="{{ route('product.detail', $similarProduct->item_id) }}" class="block w-full bg-teal-600 text-white text-center py-2 rounded-lg hover:bg-teal-700 transition-colors duration-200 font-nunito text-sm">
                                    Lihat Produk
                                </a>
                            </div>
                        </div>
                        @empty
                        @for($i = 1; $i <= 4; $i++)
                        <div class="border border-gray-200 rounded-lg overflow-hidden bg-white">
                            <div class="bg-gray-200 h-48 flex items-center justify-center">
                                <div class="w-16 h-16 bg-gradient-to-br from-teal-100 to-teal-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M9 9l6 6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-2 font-nunito text-sm">Produk {{ $i }}</h3>
                                <p class="text-teal-600 font-bold mb-3 font-nunito">Rp100.000</p>
                                <button class="block w-full bg-teal-600 text-white text-center py-2 rounded-lg hover:bg-teal-700 transition-colors duration-200 font-nunito text-sm">
                                    Lihat Produk
                                </button>
                            </div>
                        </div>
                        @endfor
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Produk Lainnya Section -->
        <div class="mt-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 font-nunito text-center">Produk Lainnya</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @forelse($otherProducts as $otherProduct)
                        <div class="border border-gray-200 rounded-lg overflow-hidden bg-white hover:shadow-md transition-shadow duration-200 product-card">
                            <div class="h-48 overflow-hidden">
                                <img src="{{ asset($otherProduct->image) }}" 
                                     alt="{{ $otherProduct->name_item }}" 
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                     onerror="this.onerror=null; this.src='{{ asset('storage/gentle-baby/placeholder.jpg') }}'">
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-2 font-nunito text-sm line-clamp-2">{{ $otherProduct->name_item }}</h3>
                                <p class="text-teal-600 font-bold mb-3 font-nunito">{{ $otherProduct->formatted_price }}</p>
                                <a href="{{ route('product.detail', $otherProduct->item_id) }}" class="block w-full bg-teal-600 text-white text-center py-2 rounded-lg hover:bg-teal-700 transition-colors duration-200 font-nunito text-sm">
                                    Lihat Produk
                                </a>
                            </div>
                        </div>
                        @empty
                        @php
                            // Kategori placeholder berdasarkan kategori produk saat ini
                            $currentCategory = $product->category ?? 'Gentle Baby';
                            $placeholderCategories = [];
                            
                            switch($currentCategory) {
                                case 'Gentle Baby':
                                    $placeholderCategories = ['Mamina ASI Booster', 'Nyam MPASI', 'Healo Roll On', 'Produk Lainnya'];
                                    break;
                                case 'Mamina':
                                    $placeholderCategories = ['Gentle Baby Oil', 'Nyam MPASI', 'Healo Roll On', 'Produk Lainnya'];
                                    break;
                                case 'Nyam':
                                    $placeholderCategories = ['Gentle Baby Oil', 'Mamina ASI Booster', 'Healo Roll On', 'Produk Lainnya'];
                                    break;
                                case 'Healo':
                                    $placeholderCategories = ['Gentle Baby Oil', 'Mamina ASI Booster', 'Nyam MPASI', 'Produk Lainnya'];
                                    break;
                                default:
                                    $placeholderCategories = ['Gentle Baby Oil', 'Mamina ASI Booster', 'Nyam MPASI', 'Healo Roll On'];
                            }
                        @endphp
                        @for($i = 1; $i <= 4; $i++)
                        <div class="border border-gray-200 rounded-lg overflow-hidden bg-white opacity-75">
                            <div class="bg-gray-100 h-48 flex items-center justify-center">
                                <div class="w-16 h-16 bg-gradient-to-br from-teal-50 to-teal-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M9 9l6 6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-600 mb-2 font-nunito text-sm">{{ $placeholderCategories[$i-1] ?? 'Produk Segera Hadir' }}</h3>
                                <p class="text-gray-400 font-medium mb-3 font-nunito text-xs">Segera Hadir</p>
                                <button disabled class="block w-full bg-gray-300 text-gray-500 text-center py-2 rounded-lg font-nunito text-sm cursor-not-allowed">
                                    Segera Hadir
                                </button>
                            </div>
                        </div>
                        @endfor
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

   
    </div>
</div>

<script>
// Store current product price (numeric value)
let currentUnitPrice = {{ $product->sell_price }};

// Format number to Indonesian Rupiah
function formatRupiah(number) {
    return 'Rp' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Update total price based on quantity
function updateTotalPrice() {
    const quantityInput = document.getElementById('quantity');
    const quantity = parseInt(quantityInput.value) || 1;
    const totalPrice = currentUnitPrice * quantity;
    
    // Update total price display
    document.getElementById('total-price').textContent = formatRupiah(totalPrice);
}

// Image gallery functionality
function changeMainImage(imageSrc, thumbnailElement) {
    const mainImage = document.getElementById('main-image');
    if (mainImage) {
        mainImage.src = imageSrc;
    }
    
    // Remove active state from all thumbnails
    const thumbnails = document.querySelectorAll('.product-thumbnail, .variant-thumbnail');
    thumbnails.forEach(thumb => {
        thumb.classList.remove('border-teal-500');
        thumb.classList.add('border-transparent');
    });
    
    // Add active state to clicked thumbnail
    if (thumbnailElement) {
        thumbnailElement.classList.remove('border-transparent');
        thumbnailElement.classList.add('border-teal-500');
    }
}

// Select variant from thumbnail click
function selectVariantFromThumbnail(variantId, thumbnailElement) {
    // Find the corresponding variant button
    const variantButton = document.querySelector(`.variant-btn[data-variant-id="${variantId}"]`);
    if (variantButton) {
        // Trigger the variant selection
        selectVariant(variantButton, variantId);
    }
    
    // Update thumbnail active state will be handled by selectVariant function
}

// Quantity controls
function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const maxValue = parseInt(quantityInput.max);
    
    if (currentValue < maxValue) {
        quantityInput.value = currentValue + 1;
        updateTotalPrice();
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const minValue = parseInt(quantityInput.min);
    
    if (currentValue > minValue) {
        quantityInput.value = currentValue - 1;
        updateTotalPrice();
    }
}

// Variant selection with dynamic updates
function selectVariant(button, variantId) {
    // Remove active class from all variant buttons
    const variantButtons = button.parentNode.querySelectorAll('button');
    variantButtons.forEach(btn => {
        btn.classList.remove('border-teal-500', 'bg-teal-50', 'active');
        btn.classList.add('border-gray-300');
    });
    
    // Add active class to clicked button
    button.classList.remove('border-gray-300');
    button.classList.add('border-teal-500', 'bg-teal-50', 'active');

    // Update product name
    const variantName = button.getAttribute('data-variant-name');
    const productNameElement = document.getElementById('product-name');
    if (productNameElement && variantName) {
        productNameElement.textContent = variantName;
    }

    // Update breadcrumb product name
    const breadcrumbProductElement = document.getElementById('breadcrumb-product');
    if (breadcrumbProductElement && variantName) {
        breadcrumbProductElement.textContent = variantName;
    }

    // Update product description
    const variantDescription = button.getAttribute('data-variant-description');
    const productDescriptionElement = document.getElementById('product-description');
    if (productDescriptionElement && variantDescription) {
        if (variantDescription === 'Deskripsi produk belum tersedia.') {
            productDescriptionElement.className = 'text-gray-500 italic font-nunito';
        } else {
            productDescriptionElement.className = 'text-gray-600 leading-relaxed font-nunito';
        }
        productDescriptionElement.textContent = variantDescription;
    }

    // Update URL without reloading page
    const newUrl = '{{ url("shopping/product") }}/' + variantId;
    window.history.pushState({variantId: variantId}, '', newUrl);

    // Update page title
    document.title = variantName + ' - Gentle Living';

    // Update unit price (numeric value)
    const variantPrice = parseFloat(button.getAttribute('data-variant-price'));
    currentUnitPrice = variantPrice;

    // Update unit price display
    const priceFormatted = button.getAttribute('data-variant-price-formatted');
    document.getElementById('unit-price').textContent = priceFormatted;
    
    // Update total price based on current quantity
    updateTotalPrice();

    // Update stock
    const stock = button.getAttribute('data-variant-stock');
    document.getElementById('current-stock').textContent = stock;
    
    // Update quantity max value
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.max = stock;
        // Reset quantity to 1 if current quantity exceeds new stock
        if (parseInt(quantityInput.value) > parseInt(stock)) {
            quantityInput.value = Math.min(parseInt(quantityInput.value), parseInt(stock));
            updateTotalPrice();
        }
    }

    // Update main image
    const mainImage = button.getAttribute('data-variant-image');
    const mainImageElement = document.getElementById('main-image');
    if (mainImageElement && mainImage) {
        mainImageElement.src = mainImage;
        mainImageElement.alt = variantName;
    }

    // Update variant thumbnails active state
    const variantThumbnails = document.querySelectorAll('.variant-thumbnail');
    variantThumbnails.forEach(thumb => {
        // Reset all thumbnails
        thumb.classList.remove('border-teal-500');
        thumb.classList.add('border-transparent');
        
        // Check if this thumbnail corresponds to the selected variant
        if (thumb.getAttribute('data-variant-id') == variantId) {
            thumb.classList.remove('border-transparent');
            thumb.classList.add('border-teal-500');
        }
    });

    // Update global product ID for cart functions
    window.currentProductId = variantId;
    
    // Reset quantity to 1
    document.getElementById('quantity').value = 1;
    updateTotalPrice();
}

// Add to cart functionality
function addToCart() {
    @if(!Auth::guard('customer')->check() && !Auth::guard('web')->check())
        alert('Silakan login terlebih dahulu untuk menambahkan produk ke keranjang.');
        return;
    @endif

    const quantity = document.getElementById('quantity').value;
    
    // Get selected variant ID or use current product ID
    const selectedVariantBtn = document.querySelector('.variant-btn.border-teal-500');
    const productId = selectedVariantBtn ? selectedVariantBtn.getAttribute('data-variant-id') : {{ $product->item_id }};
    const variantName = selectedVariantBtn ? selectedVariantBtn.getAttribute('data-variant-size') : '{{ $product->netweight_item }}';
    
    // Disable button temporarily
    const button = event.target;
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = 'Menambahkan...';
    
    fetch('{{ route("shopping.cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity,
            variant: variantName
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Show success notification
            showNotification('Produk berhasil ditambahkan ke keranjang!', 'success');
            
            // Optional: Update cart counter if exists
            updateCartCounter();
        } else {
            console.log('Error response:', data);
            showNotification(data.message || 'Gagal menambahkan produk ke keranjang.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
    })
    .finally(() => {
        // Re-enable button
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

// Show notification function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg font-nunito text-white transform transition-all duration-300 ease-in-out translate-x-full ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    }`;
    notification.textContent = message;
    
    // Add to DOM
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(full)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Update cart counter function
function updateCartCounter() {
    // Call the global cart badge update function
    if (typeof updateCartBadge === 'function') {
        updateCartBadge();
    }
}

// Buy now functionality
function buyNow() {
    @if(!Auth::guard('customer')->check() && !Auth::guard('web')->check())
        alert('Silakan login terlebih dahulu untuk melakukan pembelian.');
        return;
    @endif

    const quantity = document.getElementById('quantity').value;
    
    // Get selected variant ID or use current product ID
    const selectedVariantBtn = document.querySelector('.variant-btn.border-teal-500');
    const productId = selectedVariantBtn ? selectedVariantBtn.getAttribute('data-variant-id') : {{ $product->item_id }};
    const variantName = selectedVariantBtn ? selectedVariantBtn.getAttribute('data-variant-size') : '{{ $product->netweight_item }}';
    
    // Disable button temporarily
    const button = event.target;
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = 'Memproses...';
    
    // Add to cart first, then redirect to checkout
    fetch('{{ route("shopping.cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity,
            variant: variantName
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirect to checkout page
            window.location.href = '{{ route("shopping.checkout") }}';
        } else {
            showNotification(data.message || 'Gagal menambahkan produk ke keranjang.', 'error');
            button.disabled = false;
            button.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

// Global variables
window.currentProductId = {{ $product->item_id }};

// Handle browser back/forward button
window.addEventListener('popstate', function(event) {
    if (event.state && event.state.variantId) {
        // Find the variant button with matching ID
        const variantButton = document.querySelector(`.variant-btn[data-variant-id="${event.state.variantId}"]`);
        if (variantButton) {
            // Trigger variant selection
            selectVariant(variantButton, event.state.variantId);
        }
    }
});

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Ensure the correct variant thumbnail is highlighted on page load
    const currentProductId = {{ $product->item_id }};
    const currentVariantThumbnail = document.querySelector(`.variant-thumbnail[data-variant-id="${currentProductId}"]`);
    
    if (currentVariantThumbnail) {
        // Remove active from all thumbnails
        const allThumbnails = document.querySelectorAll('.variant-thumbnail, .product-thumbnail');
        allThumbnails.forEach(thumb => {
            thumb.classList.remove('border-teal-500');
            thumb.classList.add('border-transparent');
        });
        
        // Add active to current variant thumbnail
        currentVariantThumbnail.classList.remove('border-transparent');
        currentVariantThumbnail.classList.add('border-teal-500');
    }
    
    // Set initial state for browser history
    window.history.replaceState({variantId: currentProductId}, '', window.location.href);
});
</script>

@endsection
