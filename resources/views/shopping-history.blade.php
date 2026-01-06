@extends('layouts.ecommerce')

@section('title', 'Riwayat Belanja - Gentle Living')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-800 font-nunito">Riwayat Belanja</h1>
            <p class="text-gray-600 mt-2 font-nunito">Kelola dan pantau status pesanan Anda</p>
        </div>

        <!-- Order List -->
        <div id="order-content" class="space-y-6">
            @forelse($allOrders as $order)
            <div class="order-item bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" 
                 data-status="{{ isset($order->shipping_status) && $order->shipping_status == 'cancelled' ? 'dibatalkan' : ($order->overall_status ?? $order->status) }}">
                
                <!-- Order Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                @php
                                    $status = $order->is_active_order ?? false 
                                        ? $order->status 
                                        : ($order->overall_status ?? 'unknown');
                                    if (isset($order->shipping_status) && $order->shipping_status == 'cancelled') {
                                        $status = 'dibatalkan';
                                    }
                                @endphp
                                
                                @if($status == 'delivered' || $status == 'selesai')
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @elseif($status == 'pending' || $status == 'belum-bayar')
                                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                @elseif($status == 'processing' || $status == 'confirmed' || $status == 'sedang-dikemas')
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                @elseif($status == 'shipped' || $status == 'dikirim')
                                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                                        </svg>
                                    </div>
                                @elseif($status == 'cancelled' || $status == 'dibatalkan')
                                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                @if($order->order_type == 'cancelled' || $order->status == 'cancelled')
                                    <p class="text-red-600 font-semibold font-nunito">Dibatalkan</p>
                                @elseif($order->order_type == 'active' || ($order->is_active_order ?? false))
                                    <p class="text-blue-600 font-semibold font-nunito">{{ ucfirst($order->status) }}</p>
                                @elseif($order->order_type == 'completed' || $order->status == 'delivered')
                                    <p class="text-green-600 font-semibold font-nunito">Selesai</p>
                                @else
                                    <p class="text-gray-600 font-semibold font-nunito">{{ ucfirst($order->status) }}</p>
                                @endif
                                <p class="text-sm text-gray-600 font-nunito">
                                    {{ $order->order_number ?? 'ORD-' . $order->id }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 font-nunito">Total Pesanan:</p>
                            <p class="text-xl font-bold text-blue-600 font-nunito">
                                @if($order->is_active_order ?? false)
                                    {{ $order->formatted_total ?? 'Rp' . number_format($order->total_amount, 0, ',', '.') }}
                                @else
                                    {{ $order->formatted_grand_total ?? 'Rp' . number_format($order->total_amount ?? 0, 0, ',', '.') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Order Products -->
                <div class="p-6">
                    {{-- All orders now use orderItems structure --}}
                    @foreach($order->orderItems as $item)
                    <div class="flex items-center space-x-4 {{ !$loop->last ? 'mb-4 pb-4 border-b border-gray-100' : '' }}">
                        <!-- Product Image -->
                        <div class="flex-shrink-0">
                            @if($item->masterItem && $item->masterItem->picture_item)
                                <img src="{{ asset('storage/' . $item->masterItem->picture_item) }}" 
                                     alt="{{ $item->masterItem->name_item }}"
                                     class="w-16 h-16 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-500 text-xs font-nunito">No Image</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Product Details -->
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-semibold text-gray-800 font-nunito">{{ $item->item_name }}</h3>
                            <p class="text-sm text-gray-600 font-nunito">Harga satuan: Rp{{ number_format($item->unit_price, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-600 font-nunito">x{{ $item->quantity }}</p>
                        </div>
                        
                        <!-- Product Price -->
                        <div class="text-right">
                            <p class="text-base font-semibold text-gray-800 font-nunito">Rp{{ number_format($item->total_price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Order Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex flex-wrap gap-3 justify-end">
                        @if($order->is_active_order ?? false)
                            <!-- Active order actions -->
                            @if($order->status == 'pending')
                                <button class="cancel-order-btn px-4 py-2 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 font-nunito"
                                        data-order-id="{{ $order->id }}" data-order-type="active">
                                    Batalkan Pesanan
                                </button>
                            @endif
                        @elseif($order->order_type == 'cancelled' || $order->status == 'cancelled')
                            <!-- Cancelled order - no actions available -->
                            <div class="text-center py-4">
                                <p class="text-gray-500 text-sm font-nunito">Pesanan telah dibatalkan</p>
                            </div>
                        @else
                            <!-- Completed order actions -->
                            @php
                                $completedStatus = $status == 'delivered' || $status == 'selesai';
                                $shippedStatus = $status == 'shipped' || $status == 'dikirim';
                                $cancelledStatus = $order->status == 'cancelled' || $order->order_type == 'cancelled';
                                $canCancel = !$completedStatus && !$shippedStatus && !$cancelledStatus;
                            @endphp
                            
                            @if($canCancel)
                                <button class="cancel-order-btn px-4 py-2 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 font-nunito"
                                        data-order-id="{{ $order->id }}" data-order-type="completed">
                                    Batalkan Pesanan
                                </button>
                            @endif

                            @if($shippedStatus && !$cancelledStatus)
                                <button class="confirm-order-btn px-4 py-2 text-sm font-medium text-green-600 bg-green-50 border border-green-200 rounded-md hover:bg-green-100 font-nunito"
                                        data-order-id="{{ $order->id }}">
                                    Pesanan Diterima
                                </button>
                            @endif
                            
                            @if($completedStatus && !$cancelledStatus)
                                @php
                                    $currentUserId = Auth::guard('customer')->check() 
                                        ? Auth::guard('customer')->user()->customer_id 
                                        : (Auth::guard('web')->check() ? (Auth::guard('web')->user()->user_id ?? Auth::guard('web')->id()) : null);
                                    $hasReview = $order->reviews && $order->reviews->where('user_id', $currentUserId)->count() > 0;
                                @endphp
                                
                                @if(!$hasReview)
                                    <button class="give-review-btn px-4 py-2 text-sm font-medium text-green-600 bg-green-50 border border-green-200 rounded-md hover:bg-green-100 font-nunito"
                                            data-order-id="{{ $order->id }}">
                                        Beri Ulasan
                                    </button>
                                @else
                                    <span class="px-4 py-2 text-sm font-medium text-gray-500 bg-gray-100 border border-gray-200 rounded-md font-nunito">
                                        ✓ Sudah Diulas
                                    </span>
                                @endif
                            @endif
                            
                            <button class="buy-again-btn px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 font-nunito"
                                    data-order-id="{{ $order->id }}">
                                Beli Lagi
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                <div class="w-24 h-24 mx-auto mb-4 text-gray-300">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2 font-nunito">Belum ada riwayat belanja</h3>
                <p class="text-gray-600 mb-4 font-nunito">Anda belum pernah melakukan pembelian. Yuk mulai berbelanja!</p>
                <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 font-nunito">
                    Mulai Belanja
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Button click handlers
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('buy-again-btn')) {
            const orderId = e.target.getAttribute('data-order-id');
            buyAgain(orderId);
        }
        
        if (e.target.classList.contains('cancel-order-btn')) {
            const orderId = e.target.getAttribute('data-order-id');
            const orderType = e.target.getAttribute('data-order-type');
            cancelOrder(orderId, orderType);
        }
        
        if (e.target.classList.contains('confirm-order-btn')) {
            const orderId = e.target.getAttribute('data-order-id');
            confirmOrder(orderId);
        }
        
        if (e.target.classList.contains('give-review-btn')) {
            const orderId = e.target.getAttribute('data-order-id');
            giveReview(orderId);
        }
    });
});

function buyAgain(orderId) {
    fetch(`/buy-again/${orderId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Produk berhasil ditambahkan ke keranjang!');
            window.location.href = data.redirect_url;
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses permintaan');
    });
}

function confirmOrder(orderId) {
    if (confirm('Apakah Anda yakin telah menerima pesanan ini?')) {
        fetch(`/confirm-order/${orderId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Pesanan berhasil dikonfirmasi diterima!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses permintaan');
        });
    }
}

function cancelOrder(orderId, orderType = 'completed') {
    if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
        const endpoint = orderType === 'active' ? `/cancel-active-order/${orderId}` : `/cancel-order/${orderId}`;
        
        fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Pesanan berhasil dibatalkan!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses permintaan');
        });
    }
}

function giveReview(orderId) {
    // Create review modal
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold mb-4 font-nunito">Beri Ulasan</h3>
            <form id="review-form">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2 font-nunito">Rating</label>
                    <div class="flex space-x-1" id="rating-stars">
                        ${[1,2,3,4,5].map(i => `
                            <button type="button" class="rating-star text-2xl text-gray-300 hover:text-yellow-400" data-rating="${i}">★</button>
                        `).join('')}
                    </div>
                    <input type="hidden" id="rating-value" name="rating" value="5">
                </div>
                <div class="mb-4">
                    <label for="review-comment" class="block text-sm font-medium text-gray-700 mb-2 font-nunito">Komentar</label>
                    <textarea id="review-comment" name="comment" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-nunito" placeholder="Bagikan pengalaman Anda..."></textarea>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" id="cancel-review" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200 font-nunito">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 font-nunito">
                        Kirim Ulasan
                    </button>
                </div>
            </form>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Rating stars interaction
    const stars = modal.querySelectorAll('.rating-star');
    const ratingInput = modal.querySelector('#rating-value');
    
    stars.forEach((star, index) => {
        star.addEventListener('click', function() {
            const rating = index + 1;
            ratingInput.value = rating;
            
            stars.forEach((s, i) => {
                s.className = i < rating 
                    ? 'rating-star text-2xl text-yellow-400' 
                    : 'rating-star text-2xl text-gray-300 hover:text-yellow-400';
            });
        });
    });
    
    // Set default 5 stars
    stars.forEach(star => star.className = 'rating-star text-2xl text-yellow-400');
    
    // Cancel button
    modal.querySelector('#cancel-review').addEventListener('click', function() {
        document.body.removeChild(modal);
    });
    
    // Submit review
    modal.querySelector('#review-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const reviewData = {
            rating: parseInt(formData.get('rating')),
            comment: formData.get('comment')
        };
        
        fetch(`/give-review/${orderId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(reviewData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Ulasan berhasil dikirim!');
                document.body.removeChild(modal);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim ulasan');
        });
    });
}
</script>
@endsection