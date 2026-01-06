@extends('layouts.ecommerce')

@section('title', 'Shopping Cart - Gentle Living')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-800 font-nunito">Shopping Cart</h1>
            <p class="text-gray-600 mt-2 font-nunito">Kelola produk yang akan Anda beli</p>
        </div>

        <!-- Alert Messages -->
        @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="text-sm font-semibold text-red-800 font-nunito">Error!</h3>
                    <p class="text-sm text-red-700 font-nunito mt-1">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="text-sm font-semibold text-green-800 font-nunito">Success!</h3>
                    <p class="text-sm text-green-700 font-nunito mt-1">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if($cartItems->count() > 0)
        
        @php
            $customerTypeId = session('customer_type_id', 1);
            $mamina5KantongIds = [45, 48, 51];
            $totalMamina5Kantong = 0;
            
            if ($customerTypeId == 3) {
                foreach($cartItems as $item) {
                    if (in_array($item->master_item_id, $mamina5KantongIds)) {
                        $totalMamina5Kantong += $item->quantity;
                    }
                }
            }
        @endphp
        
        @if($customerTypeId == 3 && $totalMamina5Kantong > 0)
        <!-- Mamina 5 Kantong Summary for Reseller -->
        <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-blue-800 font-nunito">Total Mamina 5 Kantong di Keranjang</h3>
                    <p class="text-xs text-blue-700 font-nunito mt-1">
                        Saat ini Anda memiliki <span class="font-bold">{{ $totalMamina5Kantong }} box</span> dari kombinasi 3 rasa.
                        @if($totalMamina5Kantong < 10)
                            <span class="text-red-600 font-semibold">⚠️ Kurang dari minimal 10 box!</span>
                        @elseif($totalMamina5Kantong % 10 !== 0)
                            <span class="text-red-600 font-semibold">⚠️ Bukan kelipatan 10!</span>
                        @else
                            <span class="text-green-600 font-semibold">✅ Memenuhi syarat</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endif
        
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Cart Items -->
            <div class="flex-1">
                <!-- Select All Header -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" id="select-all" 
                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="select-all" class="text-base font-semibold text-gray-800 font-nunito">
                                    Pilih Semua ({{ $cartItems->count() }} produk)
                                </label>
                            </div>
                            <button class="text-sm text-red-600 hover:text-red-700 font-nunito font-medium">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Cart Items List -->
                <div class="space-y-4">
                    @foreach($cartItems as $item)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 cart-item" data-item-id="{{ $item->id }}">
                        <div class="p-6">
                            <div class="flex items-start space-x-4">
                                <!-- Checkbox -->
                                <div class="pt-2">
                                    <input type="checkbox" 
                                           class="item-checkbox w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                           data-price="{{ $item->price }}"
                                           data-quantity="{{ $item->quantity }}"
                                           data-master-item-id="{{ $item->master_item_id }}">
                                </div>

                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    <div class="w-24 h-24 bg-gray-200 rounded-lg overflow-hidden">
                                        @if($item->masterItem && $item->masterItem->image)
                                            <img src="{{ $item->masterItem->image }}" 
                                                 alt="{{ $item->masterItem->name_item }}" 
                                                 class="w-full h-full object-cover"
                                                 onerror="console.error('Image failed to load:', this.src); this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                 onload="console.log('Image loaded successfully:', this.src);">
                                            <!-- Fallback if image fails to load -->
                                            <div class="w-full h-full bg-gray-200 flex items-center justify-center" style="display: none;">
                                                <span class="text-gray-500 text-xs font-nunito">{{ $item->masterItem->getCategoryName() }}</span>
                                            </div>
                                        @else
                                            <!-- Fallback if no image available -->
                                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-500 text-xs font-nunito text-center">{{ $item->masterItem->getCategoryName() ?? 'No Image' }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Product Details -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-800 font-nunito">{{ $item->masterItem->name_item }}</h3>
                                    <p class="text-sm text-gray-600 font-nunito mt-1">{{ $item->masterItem->netweight_item }}</p>
                                    <p class="text-sm text-gray-500 font-nunito mt-1">Stok: {{ $item->masterItem->getStockQuantity() }}</p>
                                    
                                    @if(session('customer_type_id') == 3 && $item->master_item_id == 54)
                                    <!-- Notifikasi khusus untuk Mamina 3 kantong - Reseller -->
                                    <div class="mt-2 p-2 bg-amber-50 border border-amber-200 rounded-lg">
                                        <p class="text-xs text-amber-800 font-nunito">
                                            <span class="font-semibold">⚠️ Pembelian Reseller:</span><br>
                                            Minimal 10 box, kelipatan 10
                                        </p>
                                    </div>
                                    @endif
                                    
                                    @if(session('customer_type_id') == 3 && in_array($item->master_item_id, [45, 48, 51]))
                                    <!-- Notifikasi khusus untuk Mamina 5 kantong - Reseller -->
                                    <div class="mt-2 p-2 bg-amber-50 border border-amber-200 rounded-lg">
                                        <p class="text-xs text-amber-800 font-nunito">
                                            <span class="font-semibold">⚠️ Pembelian Reseller:</span><br>
                                            Total 3 rasa minimal 10 box, kelipatan 10
                                        </p>
                                    </div>
                                    @endif
                                    
                                    <!-- Price -->
                                    <div class="mt-3">
                                        <span class="text-xl font-bold text-blue-600 font-nunito">{{ $item->masterItem->formatted_price }}</span>
                                    </div>
                                </div>

                                <!-- Quantity Controls & Actions -->
                                <div class="flex flex-col items-end space-y-4">
                                    <!-- Remove Button -->
                                    <button class="remove-item text-gray-400 hover:text-red-500 transition-colors duration-200" 
                                            data-item-id="{{ $item->id }}" 
                                            title="Hapus dari keranjang">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>

                                    <!-- Quantity Controls -->
                                    <div class="flex items-center border border-gray-300 rounded-lg">
                                        <button class="quantity-btn px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-l-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                                data-action="decrease" 
                                                data-item-id="{{ $item->id }}"
                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <input type="number" 
                                               value="{{ $item->quantity }}" 
                                               min="1" 
                                               max="{{ $item->masterItem->stock }}"
                                               class="quantity-input w-16 px-3 py-2 text-center border-0 focus:ring-0 font-nunito"
                                               data-item-id="{{ $item->id }}"
                                               data-stock="{{ $item->masterItem->stock }}">
                                        <button class="quantity-btn px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-r-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                                data-action="increase" 
                                                data-item-id="{{ $item->id }}"
                                                {{ $item->quantity >= $item->masterItem->stock ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Loading Indicator -->
                                    <div class="loading-indicator hidden">
                                        <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600"></div>
                                    </div>

                                    <!-- Item Total -->
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500 font-nunito">Total</p>
                                        <p class="text-lg font-bold text-gray-800 font-nunito item-total">
                                            Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="w-full lg:w-96">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 sticky top-24">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 font-nunito">Ringkasan Belanja</h2>
                        
                        <!-- Selected Items Info -->
                        <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-800 font-nunito">
                                <span id="selected-count">0</span> produk dipilih
                            </p>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-nunito">Subtotal Produk</span>
                                <span class="font-semibold font-nunito" id="selected-subtotal">Rp0</span>
                            </div>
                            <hr class="border-gray-200">
                            <div class="flex justify-between items-center text-lg">
                                <span class="font-bold text-gray-800 font-nunito">Total Pembayaran</span>
                                <span class="font-bold text-blue-600 font-nunito" id="selected-total">Rp0</span>
                            </div>
                        </div>

                        <!-- Voucher Section -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2 font-nunito">Kode Voucher</label>
                            <div class="flex space-x-2">
                                <input type="text" 
                                       placeholder="Masukkan kode voucher"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-nunito">
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-nunito font-medium">
                                    Pakai
                                </button>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <a href="{{ route('shopping.checkout') }}" id="checkout-btn" 
                                class="block w-full py-3 bg-gray-300 text-gray-500 rounded-lg font-nunito font-medium cursor-not-allowed text-center"
                                onclick="return false;">
                            Beli Sekarang
                        </a>

                        <!-- Security Badge -->
                        <div class="mt-4 flex items-center justify-center space-x-2 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span class="font-nunito">Pembayaran 100% Aman</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Empty Cart -->
        <div class="text-center py-16">
            <div class="max-w-md mx-auto">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0L17 18m0 0v3a1 1 0 01-1 1H8a1 1 0 01-1-1v-3m10 0a1 1 0 01-1 1H8a1 1 0 01-1-1"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2 font-nunito">Keranjang Kosong</h3>
                <p class="text-gray-600 font-nunito mb-6">Belum ada produk dalam keranjang Anda.</p>
                <a href="{{ route('shopping.products') }}" 
                   class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-nunito font-medium">
                    Mulai Belanja
                </a>
            </div>
        </div>
        @endif

        <!-- Recommended Products -->
        @if($cartItems->count() > 0 && isset($recommendedProducts) && $recommendedProducts->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 font-nunito">Rekomendasi Untuk Anda</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($recommendedProducts as $product)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden group hover:shadow-md transition-shadow duration-300">
                    <div class="aspect-square overflow-hidden">
                        <img src="{{ $product->image }}" 
                             alt="{{ $product->name_item }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                             onerror="console.error('Recommended product image failed:', this.src); this.onerror=null; this.src='{{ asset('storage/gentle-baby/placeholder.jpg') }}'"
                             onload="console.log('Recommended product image loaded:', this.src)">
                    </div>
                    <div class="p-4">
                        <div class="mb-2">
                            @php
                                // Category color mapping
                                $categoryColors = [
                                    'Gentle Baby' => 'bg-green-100 text-green-700',
                                    'Mamina' => 'bg-pink-100 text-pink-700',
                                    'Nyam' => 'bg-orange-100 text-orange-700',
                                    'Healo' => 'bg-blue-100 text-blue-700',
                                    'default' => 'bg-teal-100 text-teal-700'
                                ];
                                $colorClass = $categoryColors[$product->category] ?? $categoryColors['default'];
                            @endphp
                            <span class="inline-block px-2 py-1 text-xs font-medium rounded-full font-nunito {{ $colorClass }}">
                                {{ $product->category }}
                            </span>
                        </div>
                        <h3 class="font-semibold text-gray-800 font-nunito mb-2 text-sm line-clamp-2">{{ $product->name_item }}</h3>
                        <p class="text-teal-600 font-bold font-nunito mb-3">{{ $product->formatted_price }}</p>
                        <div class="space-y-2">
                            <a href="{{ route('product.detail', $product->item_id) }}" 
                               class="block w-full py-2 bg-gray-100 text-gray-700 text-center rounded-lg hover:bg-gray-200 transition-colors duration-200 font-nunito font-medium text-sm">
                                Lihat Produk
                            </a>
                            <button class="add-to-cart-btn w-full py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors duration-200 font-nunito font-medium text-sm"
                                    data-product-id="{{ $product->item_id }}"
                                    data-product-name="{{ $product->name_item }}">
                                + Keranjang
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                @for($i = 1; $i <= 4; $i++)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden opacity-75">
                    <div class="aspect-square bg-gray-100 flex items-center justify-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-teal-50 to-teal-100 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M9 9l6 6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-600 font-nunito mb-2 text-sm">Produk Segera Hadir</h3>
                        <p class="text-gray-400 font-medium font-nunito mb-3 text-xs">Segera Hadir</p>
                        <button disabled class="w-full py-2 bg-gray-300 text-gray-500 rounded-lg font-nunito font-medium text-sm cursor-not-allowed">
                            Segera Hadir
                        </button>
                    </div>
                </div>
                @endfor
                @endforelse
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const quantityBtns = document.querySelectorAll('.quantity-btn');
    const removeItemBtns = document.querySelectorAll('.remove-item');
    const checkoutBtn = document.getElementById('checkout-btn');

    // CSRF Token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        window.csrfToken = csrfToken.getAttribute('content');
    }

    // Show toast notification
    function showToast(message, type = 'success') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-nunito transform transition-all duration-300 translate-x-full`;
        toast.className += type === 'success' ? ' bg-green-500' : ' bg-red-500';
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => toast.classList.remove('translate-x-full'), 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => document.body.removeChild(toast), 300);
        }, 3000);
    }

    // Update cart item quantity via AJAX
    function updateCartQuantity(itemId, quantity) {
        const cartItem = document.querySelector(`[data-item-id="${itemId}"]`).closest('.cart-item');
        const loadingIndicator = cartItem.querySelector('.loading-indicator');
        const quantityInput = cartItem.querySelector('.quantity-input');
        const quantityButtons = cartItem.querySelectorAll('.quantity-btn');
        
        // Validasi khusus untuk Mamina 3 kantong (item_id = 54) untuk reseller (customer_type_id = 3)
        const masterItemId = cartItem.querySelector('.item-checkbox')?.dataset.masterItemId;
        const customerTypeId = {{ session('customer_type_id', 1) }};
        
        if (customerTypeId == 3 && masterItemId == '54') {
            // Minimal 10 box
            if (quantity < 10) {
                showToast('Pembelian Mamina 3 Kantong untuk reseller minimal 10 box', 'error');
                quantityInput.value = 10;
                return;
            }
            
            // Harus kelipatan 10
            if (quantity % 10 !== 0) {
                const roundedQty = Math.ceil(quantity / 10) * 10;
                showToast('Pembelian Mamina 3 Kantong untuk reseller harus kelipatan 10 box. Disesuaikan menjadi ' + roundedQty + ' box', 'error');
                quantityInput.value = roundedQty;
                quantity = roundedQty;
            }
        }
        
        // Validasi khusus untuk Mamina 5 kantong (IDs: 45, 48, 51) untuk reseller
        const mamina5KantongIds = ['45', '48', '51'];
        // TIDAK ADA validasi di frontend untuk Mamina 5 kantong - biarkan update
        // Warning akan ditampilkan dari response server jika ada
        
        // Show loading
        loadingIndicator.classList.remove('hidden');
        quantityInput.disabled = true;
        quantityButtons.forEach(btn => btn.disabled = true);
        
        fetch('/shopping/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                item_id: itemId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update checkbox data
                const checkbox = cartItem.querySelector('.item-checkbox');
                checkbox.dataset.quantity = quantity;
                
                // Update item total display
                const price = parseInt(checkbox.dataset.price);
                const itemTotal = cartItem.querySelector('.item-total');
                itemTotal.textContent = 'Rp' + (price * quantity).toLocaleString('id-ID');
                
                // Update button states
                updateQuantityButtonStates(cartItem, quantity);
                
                // Update summary
                updateSummary();
                
                // Show warning if present (untuk Mamina 5 kantong)
                if (data.warning) {
                    showToast(data.warning, 'error');
                } else {
                    showToast('Keranjang berhasil diperbarui');
                }
            } else {
                showToast(data.message || 'Gagal memperbarui keranjang', 'error');
                // Revert quantity
                quantityInput.value = quantityInput.dataset.oldValue || 1;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan', 'error');
            // Revert quantity
            quantityInput.value = quantityInput.dataset.oldValue || 1;
        })
        .finally(() => {
            // Hide loading
            loadingIndicator.classList.add('hidden');
            quantityInput.disabled = false;
            quantityButtons.forEach(btn => btn.disabled = false);
            updateQuantityButtonStates(cartItem, quantity);
        });
    }

    // Update quantity button states
    function updateQuantityButtonStates(cartItem, quantity) {
        const decreaseBtn = cartItem.querySelector('[data-action="decrease"]');
        const increaseBtn = cartItem.querySelector('[data-action="increase"]');
        const quantityInput = cartItem.querySelector('.quantity-input');
        const stock = parseInt(quantityInput.dataset.stock);
        
        decreaseBtn.disabled = quantity <= 1;
        increaseBtn.disabled = quantity >= stock;
        
        if (decreaseBtn.disabled) {
            decreaseBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            decreaseBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
        
        if (increaseBtn.disabled) {
            increaseBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            increaseBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    // Remove item from cart
    function removeFromCart(itemId) {
        if (!confirm('Yakin ingin menghapus produk ini dari keranjang?')) {
            return;
        }
        
        const cartItem = document.querySelector(`[data-item-id="${itemId}"]`).closest('.cart-item');
        
        fetch(`/shopping/cart/remove/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Animate out and remove
                cartItem.style.transform = 'translateX(100%)';
                cartItem.style.opacity = '0';
                setTimeout(() => {
                    cartItem.remove();
                    updateSummary();
                    updateCartCount();
                }, 300);
                
                showToast('Produk berhasil dihapus dari keranjang');
            } else {
                showToast('Gagal menghapus produk', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan', 'error');
        });
    }

    // Update cart count in header
    function updateCartCount() {
        const remainingItems = document.querySelectorAll('.cart-item').length;
        const cartBadges = document.querySelectorAll('.cart-badge, [class*="cart"] span');
        cartBadges.forEach(badge => {
            if (badge.textContent !== undefined) {
                badge.textContent = remainingItems;
            }
        });
    }

    // Update summary when checkboxes change
    function updateSummary() {
        let selectedCount = 0;
        let selectedSubtotal = 0;
        
        itemCheckboxes.forEach(checkbox => {
            if (checkbox.checked && checkbox.closest('.cart-item')) {
                selectedCount++;
                const price = parseInt(checkbox.dataset.price);
                const quantity = parseInt(checkbox.dataset.quantity);
                selectedSubtotal += price * quantity;
            }
        });
        
        document.getElementById('selected-count').textContent = selectedCount;
        document.getElementById('selected-subtotal').textContent = 'Rp' + selectedSubtotal.toLocaleString('id-ID');
        
        const selectedTotal = selectedSubtotal;
        document.getElementById('selected-total').textContent = 'Rp' + selectedTotal.toLocaleString('id-ID');
        
        // Validasi khusus untuk Mamina 5 Kantong - Reseller
        const customerTypeId = {{ session('customer_type_id', 1) }};
        const mamina5KantongIds = ['45', '48', '51'];
        let totalMamina5Kantong = 0;
        let hasMamina5Kantong = false;
        
        if (customerTypeId == 3) {
            itemCheckboxes.forEach(checkbox => {
                if (checkbox.checked && checkbox.closest('.cart-item')) {
                    const masterItemId = checkbox.dataset.masterItemId;
                    if (mamina5KantongIds.includes(masterItemId)) {
                        hasMamina5Kantong = true;
                        totalMamina5Kantong += parseInt(checkbox.dataset.quantity);
                    }
                }
            });
        }
        
        // Enable/disable checkout button
        let canCheckout = selectedCount > 0;
        let checkoutMessage = '';
        
        // Validasi Mamina 5 Kantong untuk reseller
        if (customerTypeId == 3 && hasMamina5Kantong) {
            if (totalMamina5Kantong < 10) {
                canCheckout = false;
                checkoutMessage = 'Total Mamina 5 Kantong minimal 10 box!';
            } else if (totalMamina5Kantong % 10 !== 0) {
                canCheckout = false;
                checkoutMessage = 'Total Mamina 5 Kantong harus kelipatan 10 box!';
            }
        }
        
        if (canCheckout) {
            checkoutBtn.onclick = null;
            checkoutBtn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed', 'bg-red-500');
            checkoutBtn.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700');
            checkoutBtn.textContent = `Beli Sekarang (${selectedCount})`;
        } else {
            checkoutBtn.onclick = function(e) { 
                e.preventDefault(); 
                if (checkoutMessage) {
                    showToast(checkoutMessage, 'error');
                }
                return false; 
            };
            checkoutBtn.classList.add('cursor-not-allowed');
            checkoutBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            
            if (checkoutMessage) {
                checkoutBtn.classList.add('bg-red-500', 'text-white');
                checkoutBtn.classList.remove('bg-gray-300', 'text-gray-500');
                checkoutBtn.textContent = checkoutMessage;
            } else {
                checkoutBtn.classList.add('bg-gray-300', 'text-gray-500');
                checkoutBtn.classList.remove('bg-red-500', 'text-white');
                checkoutBtn.textContent = 'Beli Sekarang';
            }
        }
        
        // Update select all checkbox
        const currentItemCheckboxes = document.querySelectorAll('.item-checkbox');
        const checkedCount = Array.from(currentItemCheckboxes).filter(cb => cb.checked).length;
        selectAllCheckbox.checked = checkedCount === currentItemCheckboxes.length && currentItemCheckboxes.length > 0;
        selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < currentItemCheckboxes.length;
    }

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        const currentItemCheckboxes = document.querySelectorAll('.item-checkbox');
        currentItemCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSummary();
    });

    // Individual checkbox change
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSummary);
    });

    // Quantity controls
    quantityBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.disabled) return;
            
            const action = this.dataset.action;
            const itemId = this.dataset.itemId;
            const input = document.querySelector(`input[data-item-id="${itemId}"]`);
            const stock = parseInt(input.dataset.stock);
            
            let currentValue = parseInt(input.value);
            let newValue = currentValue;
            
            if (action === 'increase' && currentValue < stock) {
                newValue = currentValue + 1;
            } else if (action === 'decrease' && currentValue > 1) {
                newValue = currentValue - 1;
            }
            
            if (newValue !== currentValue) {
                input.dataset.oldValue = currentValue;
                input.value = newValue;
                updateCartQuantity(itemId, newValue);
            }
        });
    });

    // Direct input change with debouncing
    let quantityUpdateTimeout;
    quantityInputs.forEach(input => {
        input.addEventListener('input', function() {
            const itemId = this.dataset.itemId;
            const stock = parseInt(this.dataset.stock);
            let value = parseInt(this.value);
            
            // Validate input
            if (isNaN(value) || value < 1) {
                value = 1;
                this.value = 1;
            } else if (value > stock) {
                value = stock;
                this.value = stock;
                showToast(`Stok tersedia hanya ${stock} item`, 'error');
            }
            
            // Debounce the update
            clearTimeout(quantityUpdateTimeout);
            quantityUpdateTimeout = setTimeout(() => {
                updateCartQuantity(itemId, value);
            }, 500);
        });
        
        input.addEventListener('blur', function() {
            const value = parseInt(this.value);
            if (isNaN(value) || value < 1) {
                this.value = 1;
                updateCartQuantity(this.dataset.itemId, 1);
            }
        });
    });

    // Remove item buttons
    removeItemBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const itemId = this.dataset.itemId;
            removeFromCart(itemId);
        });
    });

    // Add to cart buttons (for recommended products)
    const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            
            // Disable button during request
            const originalText = this.textContent;
            this.disabled = true;
            this.textContent = 'Menambahkan...';
            
            fetch('/shopping/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(`${productName} berhasil ditambahkan ke keranjang`);
                    updateCartCount();
                    // Refresh the page to update cart items and recommendations
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showToast(data.message || 'Gagal menambahkan produk', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan', 'error');
            })
            .finally(() => {
                // Re-enable button if no success (success will reload page)
                if (!this.disabled) return;
                this.disabled = false;
                this.textContent = originalText;
            });
        });
    });

    // Initialize summary and button states
    updateSummary();
    document.querySelectorAll('.cart-item').forEach(cartItem => {
        const quantityInput = cartItem.querySelector('.quantity-input');
        const quantity = parseInt(quantityInput.value);
        updateQuantityButtonStates(cartItem, quantity);
    });

    // Debug: Check product images in cart
    console.log('=== Shopping Cart Image Debug ===');
    document.querySelectorAll('.cart-item img').forEach((img, index) => {
        console.log(`Cart Item ${index + 1}:`, {
            src: img.src,
            alt: img.alt,
            loaded: img.complete && img.naturalHeight > 0
        });
    });
});
</script>
@endsection
