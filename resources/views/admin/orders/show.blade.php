@extends('layouts.admin.app')

@section('title', 'Detail Pesanan - ' . $order->order_number)

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium mb-2 inline-flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Daftar Pesanan
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Detail Pesanan {{ $order->order_number }}</h1>
            <p class="text-sm text-gray-600">{{ $order->created_at->format('d F Y, H:i') }}</p>
        </div>
        <div>
            <button onclick="window.print()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm font-medium">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
        <div class="flex">
            <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="ml-3 text-sm text-green-700">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
        <div class="flex">
            <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="ml-3 text-sm text-red-700">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Item Pesanan</h2>
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                    <div class="flex gap-4 pb-4 border-b border-gray-200 last:border-0">
                        @if($item->masterItem && $item->masterItem->item_pict)
                        <img src="{{ asset('storage/' . $item->masterItem->item_pict) }}" 
                             alt="{{ $item->masterItem->item_name }}"
                             class="w-20 h-20 object-cover rounded-lg">
                        @else
                        <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $item->masterItem->item_name ?? 'Produk Tidak Tersedia' }}</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                            </p>
                            @if($item->masterItem && $item->masterItem->item_code)
                            <p class="text-xs text-gray-500 mt-1">SKU: {{ $item->masterItem->item_code }}</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="mt-6 pt-4 border-t border-gray-200 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="text-gray-900">Rp {{ number_format($order->total_amount - $order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Ongkos Kirim ({{ strtoupper($order->shipping_courier ?? '-') }})</span>
                        <span class="text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-semibold pt-2 border-t border-gray-200">
                        <span class="text-gray-900">Total</span>
                        <span class="text-blue-600">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pengiriman</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kurir</label>
                        <p class="text-sm text-gray-900">{{ strtoupper($order->shipping_courier ?? '-') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Layanan</label>
                        <p class="text-sm text-gray-900">{{ $order->shipping_service ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estimasi Pengiriman</label>
                        <p class="text-sm text-gray-900">{{ $order->shipping_etd ?? '-' }} hari</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Resi</label>
                        <p class="text-sm font-mono text-blue-600">{{ $order->tracking_number ?? 'Belum tersedia' }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Pengiriman</label>
                    <p class="text-sm text-gray-900">{{ $order->shipping_address }}</p>
                    @if($order->shippingCity)
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $order->shippingCity->city_name }}, {{ $order->shippingProvince->province ?? '' }}
                    </p>
                    @endif
                </div>

                @if($order->shipping_notes)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Pengiriman</label>
                    <p class="text-sm text-gray-900">{{ $order->shipping_notes }}</p>
                </div>
                @endif
            </div>

            <!-- Customer Notes -->
            @if($order->notes)
            <div class="bg-yellow-50 rounded-lg shadow-md p-6 border-l-4 border-yellow-400">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Catatan Pelanggan</h2>
                <p class="text-sm text-gray-700">{{ $order->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Order Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Status Pesanan</h2>
                <div class="mb-4">
                    <span class="px-3 py-2 inline-flex text-sm leading-5 font-semibold rounded-full {{ $order->status_badge }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                <!-- Update Status Form -->
                <form method="POST" action="{{ route('admin.orders.update-status', $order->id) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                        <select name="status" id="statusSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div id="shippingFields" class="space-y-4 {{ $order->status == 'shipped' ? '' : 'hidden' }}">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No. Resi</label>
                            <input type="text" name="tracking_number" value="{{ $order->tracking_number }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" 
                                   placeholder="Masukkan nomor resi">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Pengiriman</label>
                            <textarea name="shipping_notes" rows="2" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" 
                                      placeholder="Catatan opsional">{{ $order->shipping_notes }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                        Update Status
                    </button>
                </form>

                <!-- Status Timeline -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Timeline</h3>
                    <div class="space-y-3">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Pesanan Dibuat</p>
                                <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        @if($order->confirmed_at)
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Dikonfirmasi</p>
                                <p class="text-xs text-gray-500">{{ $order->confirmed_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($order->shipped_at)
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                        <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Dikirim</p>
                                <p class="text-xs text-gray-500">{{ $order->shipped_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($order->delivered_at)
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Selesai</p>
                                <p class="text-xs text-gray-500">{{ $order->delivered_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pelanggan</h2>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <p class="text-sm text-gray-900">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <p class="text-sm text-gray-900">{{ $order->customer_email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                        <p class="text-sm text-gray-900">{{ $order->customer_phone }}</p>
                    </div>
                    @if($order->user)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Akun</label>
                        <p class="text-sm text-gray-900">
                            @if($order->user->role_id == 4)
                                <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">Affiliate</span>
                            @elseif($order->user->role_id == 5)
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Reseller</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">Customer</span>
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Information -->
            @if($order->payments->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pembayaran</h2>
                @foreach($order->payments as $payment)
                <div class="space-y-2 {{ !$loop->last ? 'pb-4 mb-4 border-b border-gray-200' : '' }}">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Metode</span>
                        <span class="text-sm font-medium text-gray-900">{{ $payment->payment_method }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Status</span>
                        <span class="text-sm">
                            @if($payment->status == 'paid')
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Lunas</span>
                            @elseif($payment->status == 'pending')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Pending</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">{{ $payment->status }}</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Jumlah</span>
                        <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

<script>
// Show/hide shipping fields based on status
document.getElementById('statusSelect').addEventListener('change', function() {
    const shippingFields = document.getElementById('shippingFields');
    if (this.value === 'shipped') {
        shippingFields.classList.remove('hidden');
    } else {
        shippingFields.classList.add('hidden');
    }
});

// Print styling
window.addEventListener('beforeprint', function() {
    document.querySelectorAll('.no-print').forEach(el => el.style.display = 'none');
});

window.addEventListener('afterprint', function() {
    document.querySelectorAll('.no-print').forEach(el => el.style.display = '');
});
</script>

<style>
@media print {
    .no-print,
    button,
    form,
    nav {
        display: none !important;
    }
    
    .container-fluid {
        padding: 0 !important;
    }
    
    .shadow-md {
        box-shadow: none !important;
    }
}
</style>
@endsection
