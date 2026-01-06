@extends('layouts.ecommerce')

@section('title', 'Checkout - Gentle Living')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
.disabled-select {
    opacity: 0.6;
    cursor: not-allowed !important;
    background-color: #f9fafb !important;
}
select:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    background-color: #f9fafb;
}
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen py-8 pt-8">
    <div class="container mx-auto px-4">
        
        <!-- Page Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2 font-baloo">Checkout</h1>
            <p class="text-gray-600 font-nunito">Lengkapi informasi untuk menyelesaikan pesanan Anda</p>
        </div>

        <form method="POST" action="/test/checkout/process" id="checkout-form">
            @csrf
            
            <!-- Hidden inputs for storing selected city data -->
            <input type="hidden" name="selected_city_name" id="selected_city_name" value="">
            <input type="hidden" name="selected_province_name" id="selected_province_name" value="">
            
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 font-nunito">
                                Terjadi kesalahan pada form checkout:
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li class="font-nunito">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800 font-nunito">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Left Column - Forms -->
                <div class="space-y-6">
                    
                    <!-- Shipping Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-gray-800 font-nunito flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Informasi Pengiriman
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2 font-nunito">
                                        Nama Penerima *
                                    </label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-nunito"
                                           placeholder="Nama lengkap penerima">
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2 font-nunito">
                                        Nomor Telepon *
                                    </label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-nunito"
                                           placeholder="08123456789">
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2 font-nunito">
                                    Email *
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-nunito"
                                       placeholder="email@example.com">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2 font-nunito">
                                    Alamat Lengkap *
                                </label>
                                <textarea id="address" name="address" rows="3" required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-nunito"
                                          placeholder="Jalan, nomor rumah, RT/RW, kelurahan">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2 font-nunito">
                                        Provinsi *
                                    </label>
                                    <select id="province" name="province" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-nunito">
                                        <option value="">Pilih Provinsi</option>
                                        @foreach($provinces as $province)
                                            @if(isset($province['province_id']) && isset($province['province']))
                                            <option value="{{ $province['province_id'] }}" {{ old('province') == $province['province_id'] ? 'selected' : '' }}>
                                                {{ $province['province'] }}
                                            </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('province')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2 font-nunito">
                                        Kota/Kabupaten *
                                    </label>
                                    <select id="city" name="city" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-nunito"
                                           disabled>
                                        <option value="">Pilih Kota/Kabupaten</option>
                                    </select>
                                    @error('city')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2 font-nunito">
                                        Kode Pos *
                                    </label>
                                    <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-nunito"
                                           placeholder="12345">
                                    @error('postal_code')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <!-- Placeholder for symmetry -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Options -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-gray-800 font-nunito flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-8v2m0 6v2"></path>
                                </svg>
                                Pilih Pengiriman
                                <span class="ml-auto text-sm font-normal text-gray-500" id="shipping-counter"></span>
                            </h2>
                            <!-- Filter options (hidden by default, shown when options available) -->
                            <div class="mt-4 hidden" id="shipping-filters">
                                <div class="flex flex-wrap gap-2">
                                    <select id="courier-filter" class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Semua Kurir</option>
                                    </select>
                                    <select id="speed-filter" class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Semua Kecepatan</option>
                                        <option value="1">Same Day (1 hari)</option>
                                        <option value="2">Express (1-2 hari)</option>
                                        <option value="3">Regular (2-3 hari)</option>
                                        <option value="4">Economy (4+ hari)</option>
                                    </select>
                                    <select id="price-filter" class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Semua Harga</option>
                                        <option value="low">< Rp 15,000</option>
                                        <option value="mid">Rp 15,000 - 50,000</option>
                                        <option value="high"> Rp 50,000</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="p-6" id="shipping-options-container">
                            <div class="text-center py-6">
                                <div class="text-gray-500 font-nunito">📦 Pilih kota tujuan untuk melihat pilihan pengiriman</div>
                                <div class="text-sm text-gray-400 mt-1 font-nunito">Kami akan menghitung ongkos kirim terbaik untuk Anda</div>
                            </div>
                        </div>
                    </div>



                    <!-- Order Notes -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-gray-800 font-nunito flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Catatan Pesanan (Opsional)
                            </h2>
                        </div>
                        <div class="p-6">
                            <textarea id="notes" name="notes" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-nunito"
                                      placeholder="Catatan khusus untuk pesanan Anda...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Order Summary -->
                <div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 sticky top-24">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-gray-800 font-nunito">Ringkasan Pesanan</h2>
                        </div>
                        
                        <div class="p-6">
                            <!-- Notifikasi khusus untuk Mamina 3 kantong - Reseller -->
                            @php
                                $hasMamina3Kantong = false;
                                $hasMamina5Kantong = false;
                                $customerTypeId = session('customer_type_id', 1);
                                $mamina5KantongIds = [45, 48, 51];
                                
                                foreach($cartItems as $item) {
                                    if($customerTypeId == 3 && $item->master_item_id == 54) {
                                        $hasMamina3Kantong = true;
                                    }
                                    if($customerTypeId == 3 && in_array($item->master_item_id, $mamina5KantongIds)) {
                                        $hasMamina5Kantong = true;
                                    }
                                }
                            @endphp
                            
                            @if($hasMamina3Kantong)
                            <div class="mb-4 p-4 bg-amber-50 border-l-4 border-amber-400 rounded-lg">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-amber-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <h3 class="text-sm font-semibold text-amber-800 font-nunito">Pembelian Reseller</h3>
                                        <p class="text-xs text-amber-700 font-nunito mt-1">
                                            Mamina 3 Kantong harus minimal 10 box dan kelipatan 10
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            @if($hasMamina5Kantong)
                            <div class="mb-4 p-4 bg-amber-50 border-l-4 border-amber-400 rounded-lg">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-amber-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <h3 class="text-sm font-semibold text-amber-800 font-nunito">Pembelian Reseller</h3>
                                        <p class="text-xs text-amber-700 font-nunito mt-1">
                                            Total Mamina 5 Kantong (Original, Jeruk Nipis, Belimbing Wuluh) harus minimal 10 box dan kelipatan 10
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Order Items -->
                            <div class="space-y-4 mb-6">
                                @foreach($cartItems as $item)
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                            @if($item->masterItem && $item->masterItem->image)
                                                <img src="{{ $item->masterItem->image }}" 
                                                     alt="{{ $item->masterItem->name_item }}"
                                                     class="w-full h-full object-cover"
                                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-gray-200\'><span class=\'text-gray-400 text-xs\'>No Image</span></div>';">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                    <span class="text-gray-400 text-xs font-nunito">No Image</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-800 font-nunito">{{ $item->masterItem->name_item }}</h4>
                                        <p class="text-xs text-gray-600 font-nunito">{{ $item->variant ?? 'Varian 1' }}</p>
                                        <p class="text-xs text-gray-600 font-nunito">Qty: {{ $item->quantity }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-800 font-nunito">
                                            Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Price Breakdown -->
                            <div class="space-y-3 mb-6 border-t border-gray-200 pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 font-nunito">Subtotal Produk</span>
                                    <span class="font-semibold font-nunito">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 font-nunito">Total Berat</span>
                                    <span class="font-semibold font-nunito">{{ ceil($totalWeight) }}g</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 font-nunito">Ongkos Kirim</span>
                                    <span class="font-semibold font-nunito" id="shipping-cost">Rp0</span>
                                </div>
                                <div class="flex justify-between items-center" id="payment-fee-row" style="display: none;">
                                    <span class="text-gray-600 font-nunito">Biaya Admin</span>
                                    <span class="font-semibold font-nunito" id="payment-fee">Rp0</span>
                                </div>
                                <hr class="border-gray-200">
                                <div class="flex justify-between items-center text-lg">
                                    <span class="font-bold text-gray-800 font-nunito">Total Pembayaran</span>
                                    <span class="font-bold text-blue-600 font-nunito" id="total-payment">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Payment Method Section -->
                            <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-4 rounded-lg border border-blue-200 mb-6">
                                <div class="flex items-center justify-center space-x-3">
                                    <div class="w-16 h-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M12 12h-4.01m4.01 2v2.5m0-.5h-4m4 0v1a1 1 0 01-1 1h-2m-6-2v1a1 1 0 001 1h2m-2-2v2.5m0-.5h4M8 7V5a1 1 0 011-1h2a1 1 0 011 1v2"></path>
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <h3 class="text-lg font-bold text-gray-800 font-nunito">Pembayaran QRIS</h3>
                                        <p class="text-sm text-gray-600 font-nunito">Bayar mudah dengan scan QR Code</p>
                                        <p class="text-xs text-green-600 font-semibold font-nunito mt-1">✨ Gratis Biaya Admin</p>
                                    </div>
                                </div>
                                <input type="hidden" name="payment_method" value="qris">
                            </div>

                            <!-- Submit Button untuk QRIS -->
                            <button type="submit" name="payment_action" value="qris"
                                    class="w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-nunito font-medium">
                                <i class="fas fa-qrcode mr-2"></i>Bayar dengan QRIS
                            </button>

                            <!-- Debug Section (Remove in production) -->
                            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <h4 class="text-sm font-bold text-yellow-800 mb-2">🐛 Debug Tools (Development Only)</h4>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" onclick="quickDebug()"
                                            class="py-2 px-3 bg-yellow-600 text-white rounded text-xs hover:bg-yellow-700 transition-colors">
                                        <i class="fas fa-search mr-1"></i>Full Debug Test
                                    </button>
                                    <button type="button" onclick="testMiddlewareIssue()"
                                            class="py-2 px-3 bg-red-600 text-white rounded text-xs hover:bg-red-700 transition-colors">
                                        <i class="fas fa-shield-alt mr-1"></i>Test Middleware
                                    </button>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mt-2">
                                    <button type="button" onclick="window.open('/debug/checkout-access', '_blank')"
                                            class="py-2 px-3 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                                        <i class="fas fa-user-check mr-1"></i>Check Access
                                    </button>
                                    <button type="button" onclick="window.open('/debug/qris-logs', '_blank')"
                                            class="py-2 px-3 bg-purple-500 text-white rounded text-xs hover:bg-purple-600 transition-colors">
                                        <i class="fas fa-file-alt mr-1"></i>View Logs
                                    </button>
                                </div>
                                <div class="mt-2">
                                    <button type="button" onclick="testBypassCheckout()"
                                            class="w-full py-2 px-3 bg-orange-600 text-white rounded text-xs hover:bg-orange-700 transition-colors">
                                        <i class="fas fa-rocket mr-1"></i>Test Bypass (Fixes redirect issue)
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Quick Debug Button -->
                            <button type="button" onclick="quickDebug()"
                                    class="w-full mt-2 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors font-nunito text-sm">
                                <i class="fas fa-search mr-2"></i>Quick Debug Test
                            </button>

                            <!-- Security Badge -->
                            <div class="mt-4 flex items-center justify-center space-x-2 text-sm text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span class="font-nunito">Transaksi 100% Aman</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    const shippingOptionsContainer = document.getElementById('shipping-options-container');
    const shippingCostElement = document.getElementById('shipping-cost');
    const paymentFeeElement = document.getElementById('payment-fee');
    const paymentFeeRow = document.getElementById('payment-fee-row');
    const totalPaymentElement = document.getElementById('total-payment');
    const submitButton = document.querySelector('button[type="button"][onclick="processQrisPayment()"]');
    
    const subtotal = {{ $subtotal ?? 0 }};
    const totalWeight = {{ ceil($totalWeight ?? 0) }};
    let currentShippingCost = 0;
    let currentPaymentFee = 0;
    
    // Initialize city select as disabled
    if (citySelect) {
        citySelect.disabled = true;
        citySelect.classList.add('disabled-select');
    }
    
    // Disable submit button initially
    if (submitButton) {
        submitButton.disabled = true;
        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
        submitButton.innerHTML = 'Pilih Kota dan Pengiriman';
    }

    // Handle province change
    provinceSelect.addEventListener('change', function() {
        const provinceId = this.value;
        
        // Reset city dropdown
        citySelect.innerHTML = '<option value="">Memuat kota...</option>';
        citySelect.disabled = true;
        citySelect.classList.add('disabled-select');
        
        // Reset shipping options
        resetShippingOptions();
        
        if (provinceId) {
            // Fetch cities for selected province
            fetch(`/api/cities-by-province?province_id=${provinceId}`)
                .then(response => response.json())
                .then(data => {
                    citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                    
                    if (data.cities && data.cities.length > 0) {
                        data.cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.city_id;
                            option.textContent = `${city.city_name} (${city.type})`;
                            option.dataset.cityName = city.city_name;
                            option.dataset.postalCode = city.postal_code;
                            citySelect.appendChild(option);
                        });
                        
                        // Enable city select
                        citySelect.disabled = false;
                        citySelect.classList.remove('disabled-select');
                    } else {
                        citySelect.innerHTML = '<option value="">Tidak ada kota tersedia</option>';
                        citySelect.disabled = true;
                        citySelect.classList.add('disabled-select');
                    }
                })
                .catch(error => {
                    console.error('Error fetching cities:', error);
                    citySelect.innerHTML = '<option value="">Error memuat kota</option>';
                    citySelect.disabled = true;
                    citySelect.classList.add('disabled-select');
                });
        } else {
            citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
            citySelect.disabled = true;
            citySelect.classList.add('disabled-select');
        }
    });

    // Handle city change
    citySelect.addEventListener('change', function() {
        const cityId = this.value;
        const selectedOption = this.options[this.selectedIndex];
        const cityName = selectedOption.dataset.cityName || selectedOption.text;
        const provinceName = provinceSelect.options[provinceSelect.selectedIndex].text;
        const postalCode = selectedOption.dataset.postalCode;
        
        // Store selected city and province names
        document.getElementById('selected_city_name').value = cityName;
        document.getElementById('selected_province_name').value = provinceName;
        
        // Auto-fill postal code if available
        if (postalCode && postalCode !== 'undefined') {
            const postalCodeInput = document.getElementById('postal_code');
            postalCodeInput.value = postalCode;
            postalCodeInput.classList.add('border-green-500', 'bg-green-50');
            setTimeout(() => {
                postalCodeInput.classList.remove('border-green-500', 'bg-green-50');
            }, 2000);
        }
        
        if (cityId) {
            // Show enhanced loading with progress indicator
            shippingOptionsContainer.innerHTML = `
                <div class="text-center py-6">
                    <div class="inline-flex items-center space-x-2">
                        <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-blue-600 font-nunito">Menghitung ongkos kirim ke ${cityName}...</span>
                    </div>
                    <div class="mt-2 text-sm text-gray-500 font-nunito">Mohon tunggu sebentar</div>
                </div>
            `;
            
            // Calculate shipping cost
            fetch('/api/calculate-shipping', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    city_id: cityId,
                    weight: totalWeight
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.shippingOptions && data.shippingOptions.length > 0) {
                    // Show success message briefly before displaying options
                    shippingOptionsContainer.innerHTML = `
                        <div class="text-center py-4">
                            <div class="text-green-600 font-nunito">✅ ${data.shippingOptions.length} layanan pengiriman tersedia</div>
                        </div>
                    `;
                    
                    setTimeout(() => {
                        displayShippingOptions(data.shippingOptions);
                        enableSubmitButton();
                    }, 800);
                } else {
                    shippingOptionsContainer.innerHTML = `
                        <div class="text-center py-6">
                            <div class="text-red-500 font-nunito">❌ Tidak ada layanan pengiriman tersedia</div>
                            <div class="text-sm text-gray-500 mt-1 font-nunito">untuk ${cityName}</div>
                        </div>
                    `;
                    disableSubmitButton();
                }
            })
            .catch(error => {
                console.error('Error calculating shipping:', error);
                shippingOptionsContainer.innerHTML = `
                    <div class="text-center py-6">
                        <div class="text-red-500 font-nunito">❌ Error menghitung ongkos kirim</div>
                        <div class="text-sm text-gray-500 mt-1 font-nunito">Silakan coba lagi</div>
                    </div>
                `;
                disableSubmitButton();
            });
        } else {
            resetShippingOptions();
            // Clear postal code if city is deselected
            document.getElementById('postal_code').value = '';
        }
    });

    function displayShippingOptions(options) {
        // Show filters if we have many options
        const filtersDiv = document.getElementById('shipping-filters');
        const counter = document.getElementById('shipping-counter');
        
        if (options.length > 5) {
            filtersDiv.classList.remove('hidden');
            populateFilters(options);
        } else {
            filtersDiv.classList.add('hidden');
        }
        
        counter.textContent = `${options.length} layanan tersedia`;
        
        let html = '<div class="space-y-2 animate-fade-in" id="shipping-list">';
        
        // Sort options by price (cheapest first)
        options.sort((a, b) => a.price - b.price);
        
        options.forEach((option, index) => {
            const isRecommended = index === 0; // Mark cheapest as recommended
            const isFast = option.estimated_days <= 1;
            const isEconomy = option.price < 15000;
            
            // Create badges
            let badges = '';
            if (isRecommended) badges += '<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-nunito mr-1">Termurah</span>';
            if (isFast) badges += '<span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-nunito mr-1">Cepat</span>';
            if (isEconomy) badges += '<span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full font-nunito">Ekonomis</span>';
            
            html += `
                <label class="shipping-option-item flex items-center justify-between p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 ${isRecommended ? 'ring-2 ring-blue-200 bg-blue-50' : ''}" 
                       style="animation-delay: ${Math.min(index * 50, 1000)}ms"
                       data-courier="${option.courier.toLowerCase()}"
                       data-price="${option.price}"
                       data-days="${option.estimated_days}">
                    <div class="flex items-center flex-1">
                        <input type="radio" name="shipping_method" value="${option.price}" 
                               class="text-blue-600 focus:ring-blue-500 mr-3"
                               data-price="${option.price}"
                               data-service="${option.name}"
                               ${index === 0 ? 'checked' : ''}>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <div class="font-medium text-gray-800 font-nunito">${option.name}</div>
                                ${badges}
                            </div>
                            <div class="text-sm text-gray-600 font-nunito">${option.description}</div>
                        </div>
                    </div>
                    <div class="text-right ml-4">
                        <div class="font-bold text-gray-800 font-nunito">Rp${option.price.toLocaleString('id-ID')}</div>
                        <div class="text-sm text-gray-600 font-nunito">${option.estimated_days} hari</div>
                    </div>
                </label>
            `;
        });
        
        html += '</div>';
        
        // Add compact view toggle for many options
        if (options.length > 10) {
            html += `
                <div class="mt-4 text-center">
                    <button type="button" id="toggle-compact" class="text-blue-600 hover:text-blue-800 text-sm font-nunito">
                        Tampilkan dalam mode compact
                    </button>
                </div>
            `;
        }
        
        // Add fade-in CSS animation style if not exists
        if (!document.getElementById('checkout-animations')) {
            const style = document.createElement('style');
            style.id = 'checkout-animations';
            style.textContent = `
                .animate-fade-in {
                    animation: fadeIn 0.5s ease-in-out;
                }
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                .loading-pulse {
                    animation: pulse 1.5s infinite;
                }
                @keyframes pulse {
                    0%, 100% { opacity: 1; }
                    50% { opacity: 0.5; }
                }
                .compact-mode .shipping-option-item {
                    padding: 8px 12px;
                }
                .compact-mode .font-medium {
                    font-size: 0.875rem;
                }
                .compact-mode .text-sm {
                    font-size: 0.75rem;
                }
            `;
            document.head.appendChild(style);
        }
        
        shippingOptionsContainer.innerHTML = html;
        
        // Add event listeners to new shipping options
        const shippingOptions = shippingOptionsContainer.querySelectorAll('input[name="shipping_method"]');
        shippingOptions.forEach(option => {
            option.addEventListener('change', updateTotal);
        });
        
        // Add compact mode toggle
        const toggleCompact = document.getElementById('toggle-compact');
        if (toggleCompact) {
            toggleCompact.addEventListener('click', function() {
                const list = document.getElementById('shipping-list');
                list.classList.toggle('compact-mode');
                this.textContent = list.classList.contains('compact-mode') ? 
                    'Tampilkan dalam mode normal' : 'Tampilkan dalam mode compact';
            });
        }
        
        // Update total with first option (cheapest)
        if (options.length > 0) {
            currentShippingCost = options[0].price;
            updateTotal();
        }
        
        // Setup filters
        setupFilters(options);
    }

    function resetShippingOptions() {
        const filtersDiv = document.getElementById('shipping-filters');
        const counter = document.getElementById('shipping-counter');
        
        if (filtersDiv) filtersDiv.classList.add('hidden');
        if (counter) counter.textContent = '';
        
        shippingOptionsContainer.innerHTML = `
            <div class="text-center py-6">
                <div class="text-gray-500 font-nunito">📦 Pilih kota tujuan untuk melihat pilihan pengiriman</div>
                <div class="text-sm text-gray-400 mt-1 font-nunito">Kami akan menghitung ongkos kirim terbaik untuk Anda</div>
            </div>
        `;
        currentShippingCost = 0;
        updateTotal();
        disableSubmitButton();
    }
    
    function populateFilters(options) {
        const courierFilter = document.getElementById('courier-filter');
        const couriers = [...new Set(options.map(opt => opt.courier))].sort();
        
        // Clear existing options except first
        courierFilter.innerHTML = '<option value="">Semua Kurir</option>';
        couriers.forEach(courier => {
            courierFilter.innerHTML += `<option value="${courier.toLowerCase()}">${courier}</option>`;
        });
    }
    
    function setupFilters(allOptions) {
        const courierFilter = document.getElementById('courier-filter');
        const speedFilter = document.getElementById('speed-filter');  
        const priceFilter = document.getElementById('price-filter');
        
        function applyFilters() {
            const courierValue = courierFilter.value;
            const speedValue = speedFilter.value;
            const priceValue = priceFilter.value;
            
            let filtered = allOptions.filter(option => {
                // Courier filter
                if (courierValue && !option.courier.toLowerCase().includes(courierValue)) {
                    return false;
                }
                
                // Speed filter
                if (speedValue) {
                    const days = option.estimated_days;
                    switch(speedValue) {
                        case '1': if (days > 1) return false; break;
                        case '2': if (days < 1 || days > 2) return false; break;
                        case '3': if (days < 2 || days > 3) return false; break;
                        case '4': if (days <= 3) return false; break;
                    }
                }
                
                // Price filter
                if (priceValue) {
                    const price = option.price;
                    switch(priceValue) {
                        case 'low': if (price >= 15000) return false; break;
                        case 'mid': if (price < 15000 || price > 50000) return false; break;
                        case 'high': if (price <= 50000) return false; break;
                    }
                }
                
                return true;
            });
            
            renderFilteredOptions(filtered);
        }
        
        courierFilter.addEventListener('change', applyFilters);
        speedFilter.addEventListener('change', applyFilters);
        priceFilter.addEventListener('change', applyFilters);
    }
    
    function renderFilteredOptions(options) {
        const counter = document.getElementById('shipping-counter');
        counter.textContent = `${options.length} layanan tersedia`;
        
        if (options.length === 0) {
            shippingOptionsContainer.innerHTML = `
                <div class="text-center py-6">
                    <div class="text-gray-500 font-nunito">😔 Tidak ada layanan pengiriman yang sesuai filter</div>
                    <div class="text-sm text-gray-400 mt-1 font-nunito">Coba ubah filter untuk melihat lebih banyak pilihan</div>
                </div>
            `;
            currentShippingCost = 0;
            updateTotal();
            disableSubmitButton();
            return;
        }
        
        // Re-render options
        let html = '<div class="space-y-2 animate-fade-in" id="shipping-list">';
        
        options.sort((a, b) => a.price - b.price);
        
        options.forEach((option, index) => {
            const isRecommended = index === 0;
            const isFast = option.estimated_days <= 1;
            const isEconomy = option.price < 15000;
            
            let badges = '';
            if (isRecommended) badges += '<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-nunito mr-1">Termurah</span>';
            if (isFast) badges += '<span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-nunito mr-1">Cepat</span>';
            if (isEconomy) badges += '<span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full font-nunito">Ekonomis</span>';
            
            html += `
                <label class="shipping-option-item flex items-center justify-between p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 ${isRecommended ? 'ring-2 ring-blue-200 bg-blue-50' : ''}"
                       data-courier="${option.courier.toLowerCase()}"
                       data-price="${option.price}"
                       data-days="${option.estimated_days}">
                    <div class="flex items-center flex-1">
                        <input type="radio" name="shipping_method" value="${option.price}" 
                               class="text-blue-600 focus:ring-blue-500 mr-3"
                               data-price="${option.price}"
                               data-service="${option.name}"
                               ${index === 0 ? 'checked' : ''}>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <div class="font-medium text-gray-800 font-nunito">${option.name}</div>
                                ${badges}
                            </div>
                            <div class="text-sm text-gray-600 font-nunito">${option.description}</div>
                        </div>
                    </div>
                    <div class="text-right ml-4">
                        <div class="font-bold text-gray-800 font-nunito">Rp${option.price.toLocaleString('id-ID')}</div>
                        <div class="text-sm text-gray-600 font-nunito">${option.estimated_days} hari</div>
                    </div>
                </label>
            `;
        });
        
        html += '</div>';
        shippingOptionsContainer.innerHTML = html;
        
        // Add event listeners
        const shippingOptions = shippingOptionsContainer.querySelectorAll('input[name="shipping_method"]');
        shippingOptions.forEach(option => {
            option.addEventListener('change', updateTotal);
        });
        
        // Update total with first option
        if (options.length > 0) {
            currentShippingCost = options[0].price;
            updateTotal();
            enableSubmitButton();
        }
    }

    function enableSubmitButton() {
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
            submitButton.innerHTML = '<i class="fas fa-qrcode mr-2"></i>Bayar dengan QRIS';
        }
    }

    function disableSubmitButton() {
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            submitButton.innerHTML = 'Pilih Kota dan Pengiriman';
        }
    }
    
    function updateTotal() {
        const selectedShipping = document.querySelector('input[name="shipping_method"]:checked');
        
        let shippingCost = 0;
        if (selectedShipping) {
            const price = selectedShipping.dataset.price || selectedShipping.value || '0';
            shippingCost = parseInt(price.replace(/[^\d]/g, ''));
        }
        
        currentShippingCost = isNaN(shippingCost) ? 0 : shippingCost;
        currentPaymentFee = 0; // QRIS tidak ada biaya admin
        
        // Update display
        if (shippingCostElement) {
            shippingCostElement.textContent = 'Rp' + currentShippingCost.toLocaleString('id-ID');
        }
        if (paymentFeeElement) {
            paymentFeeElement.textContent = 'Rp0';
        }
        
        // Hide payment fee row for QRIS
        if (paymentFeeRow) {
            paymentFeeRow.style.display = 'none';
        }
        
        const total = subtotal + currentShippingCost + currentPaymentFee;
        const finalTotal = isNaN(total) ? subtotal : total;
        
        if (totalPaymentElement) {
            totalPaymentElement.textContent = 'Rp' + finalTotal.toLocaleString('id-ID');
        }
    }
    
    // Handle shipping option selection styling
    document.addEventListener('change', function(e) {
        if (e.target.name === 'shipping_method') {
            document.querySelectorAll('.shipping-option-item').forEach(label => {
                label.classList.remove('border-blue-500', 'bg-blue-50');
            });
            if (e.target.checked) {
                e.target.closest('.shipping-option-item').classList.add('border-blue-500', 'bg-blue-50');
            }
            updateTotal();
        }
    });
});

// Process QRIS payment
function processQrisPayment() {
    console.log('=== QRIS Payment Process Started ===');
    console.log('Timestamp:', new Date().toISOString());
    
    const form = document.getElementById('checkout-form');
    if (!form) {
        console.error('Checkout form not found!');
        alert('Form tidak ditemukan!');
        return;
    }
    
    const formData = new FormData(form);
    
    // Add QRIS payment identifiers
    formData.set('payment_method', 'qris');
    formData.set('payment_action', 'qris');
    
    // Debug: Log form data
    console.log('Form data values:');
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }
    
    // Validasi form fields yang diperlukan
    const requiredFields = ['name', 'email', 'phone', 'address', 'province', 'city'];
    for (let field of requiredFields) {
        const fieldValue = formData.get(field);
        if (!fieldValue || fieldValue.trim() === '') {
            alert(`Field ${field} harus diisi!`);
            const fieldElement = document.querySelector(`[name="${field}"]`);
            if (fieldElement) {
                fieldElement.focus();
            }
            return;
        }
    }    // Validasi shipping method
    const shippingMethod = document.querySelector('input[name="shipping_method"]:checked');
    if (!shippingMethod) {
        alert('Silakan pilih metode pengiriman!');
        return;
    }
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
    button.disabled = true;
    
    console.log('Form validation passed, processing checkout...');
    
    
    // Step 1: Process checkout (using test route for debugging)
    fetch('/test/checkout/process', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Checkout response status:', response.status);
        console.log('Checkout response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            // If not JSON, get text to see what was returned
            return response.text().then(text => {
                console.error('Non-JSON response received:', text);
                throw new Error('Server returned non-JSON response: ' + text.substring(0, 200));
            });
        }
        
        return response.json();
    })
    .then(checkoutData => {
        console.log('Checkout response:', checkoutData);
        
        if (checkoutData.success) {
            // Step 2: Create QRIS payment
            const subtotal = {{ $subtotal }};
            const shippingCost = getSelectedShippingCost();
            const totalAmount = subtotal + shippingCost;
            
            const paymentData = {
                order_id: checkoutData.order_number,
                amount: totalAmount,
                customer_name: formData.get('name'),
                customer_email: formData.get('email'),
                customer_phone: formData.get('phone'),
                items: [
                    @foreach($cartItems as $item)
                    {
                        id: '{{ $item->id }}',
                        price: {{ $item->getSellPrice() }},
                        quantity: {{ $item->quantity }},
                        name: '{{ addslashes($item->getItemName()) }}'
                    },
                    @endforeach
                ]
            };
            
            console.log('Creating QRIS payment with data:', paymentData);
            
            return fetch('/payment/qris', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(paymentData)
            });
        } else {
            // Handle authentication or other errors
            if (checkoutData.redirect) {
                alert(checkoutData.message + ' Redirecting to login...');
                window.location.href = checkoutData.redirect;
                return;
            }
            throw new Error(checkoutData.message || 'Checkout failed');
        }
    })
    .then(response => {
        console.log('Payment response status:', response.status);
        console.log('Payment response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`Payment HTTP error! status: ${response.status}`);
        }
        
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                console.error('Payment non-JSON response:', text);
                throw new Error('Payment server returned non-JSON response: ' + text.substring(0, 200));
            });
        }
        
        return response.json();
    })
    .then(paymentData => {
        console.log('Payment response:', paymentData);
        
        if (paymentData.success) {
            alert('QR Code berhasil dibuat! Mengarahkan ke halaman pembayaran...');
            // Redirect to payment page with QR code using order ID
            window.location.href = '/payment/qris/order/' + paymentData.order_id;
        } else {
            throw new Error(paymentData.error || 'Payment creation failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: ' + error.message);
    })
    .finally(() => {
        // Restore button state
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Get selected shipping cost
function getSelectedShippingCost() {
    const selectedShipping = document.querySelector('input[name="shipping_method"]:checked');
    return selectedShipping ? parseInt(selectedShipping.dataset.price || selectedShipping.value) : 0;
}

// Test direct QRIS function
function testDirectQris() {
    console.log('=== Testing Direct QRIS ===');
    
    // Get form data
    const form = document.getElementById('checkout-form');
    const formData = new FormData(form);
    
    // Set QRIS payment method explicitly
    formData.set('payment_method', 'qris');
    formData.set('payment_action', 'qris');
    formData.set('debug_mode', '1'); // Add debug mode
    
    // Show what we're sending
    console.log('Form data being sent:');
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }
    
    // Submit to test route with explicit QRIS data
    fetch('/test/checkout/process', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        
        if (response.redirected) {
            console.log('Redirected to:', response.url);
            window.location.href = response.url;
        } else {
            return response.text();
        }
    })
    .then(data => {
        if (data) {
            console.log('Response:', data);
            alert('Response received: ' + data.substring(0, 200));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: ' + error.message);
    });
}

// Enhanced comprehensive debug function
function quickDebug() {
    console.log('=== COMPREHENSIVE QRIS DEBUG ===');
    
    // Test configuration first
    fetch('/debug/midtrans-config')
        .then(response => response.json())
        .then(configData => {
            console.log('Midtrans Configuration:', configData);
            
            // Now test the checkout process
            const form = document.getElementById('checkout-form');
            if (!form) {
                console.error('Checkout form not found');
                alert('Error: Checkout form not found');
                return;
            }

            const formData = new FormData(form);
            
            // Add debug flags
            formData.set('payment_method', 'qris');
            formData.set('payment_action', 'qris');
            formData.set('debug_mode', '1');
            
            // Ensure required fields have values for testing
            if (!formData.get('name')) formData.set('name', 'Debug Test User');
            if (!formData.get('email')) formData.set('email', 'debug@test.com');
            if (!formData.get('phone')) formData.set('phone', '08123456789');
            if (!formData.get('address')) formData.set('address', 'Debug Test Address');
            if (!formData.get('province')) formData.set('province', '1');
            if (!formData.get('city')) formData.set('city', '1');
            if (!formData.get('shipping_method')) formData.set('shipping_method', '15000');
            
            console.log('Form data being sent:');
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }
            
            // Send test request
            return fetch('/test/checkout/process', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
        })
        .then(response => {
            console.log('Checkout Response Status:', response.status);
            console.log('Checkout Response Headers:', [...response.headers]);
            
            if (response.redirected) {
                console.log('Redirected to:', response.url);
                alert('SUCCESS: Redirect detected to: ' + response.url + '\n\nQRIS payment created successfully!');
                
                // Show logs
                return fetch('/debug/qris-logs');
            } else {
                return response.text().then(text => {
                    console.log('Checkout Response Data:', text);
                    
                    try {
                        const jsonData = JSON.parse(text);
                        console.log('Parsed JSON Response:', jsonData);
                        
                        if (jsonData.success === false) {
                            alert('CHECKOUT FAILED:\n' + 
                                'Message: ' + (jsonData.message || 'Unknown error') + '\n' +
                                'Debug Info: ' + JSON.stringify(jsonData.debug_info || {}, null, 2));
                        } else {
                            alert('Unexpected response format. Check console for details.');
                        }
                    } catch (e) {
                        console.log('Response is not JSON:', text);
                        alert('Response is not JSON. Check console for full response.');
                    }
                    
                    // Fetch recent logs regardless
                    return fetch('/debug/qris-logs');
                });
            }
        })
        .then(response => response ? response.json() : null)
        .then(logData => {
            if (logData) {
                console.log('Recent QRIS Logs:', logData);
                console.log('Log Lines Found:', logData.total_lines);
                
                if (logData.log_lines && logData.log_lines.length > 0) {
                    console.log('Recent Log Entries:');
                    logData.log_lines.slice(-10).forEach((line, index) => {
                        console.log(`${index + 1}. ${line}`);
                    });
                }
                
                const logSummary = `Debug completed!\n\nRecent logs: ${logData.total_lines} lines found\nLog file: ${logData.file_size} bytes\nLast modified: ${logData.last_modified}`;
                
                if (!document.querySelector('.redirect-detected')) {
                    alert(logSummary + '\n\nCheck console for full details.');
                }
            }
        })
        .catch(error => {
            console.error('Debug Error:', error);
            alert('Debug error: ' + error.message + '\n\nCheck console for full details.');
        });
}

// Test middleware issue specifically
function testMiddlewareIssue() {
    console.log('=== TESTING MIDDLEWARE ISSUE ===');
    
    fetch('/debug/checkout-access')
        .then(response => response.json())
        .then(data => {
            console.log('Checkout Access Test:', data);
            
            let message = 'Middleware Debug Results:\n\n';
            
            // Authentication status
            message += `Authentication Status:\n`;
            message += `- Web Auth: ${data.authentication.web_auth}\n`;
            message += `- Customer Auth: ${data.authentication.customer_auth}\n`;
            
            if (data.authentication.customer_user) {
                message += `- Customer: ${data.authentication.customer_user.name_customer}\n`;
                message += `- Email: ${data.authentication.customer_user.email_customer}\n`;
            }
            
            // Customer status check
            if (data.customer_status_check) {
                message += `\nCustomer Status Check:\n`;
                message += `- Current Status: ${data.customer_status_check.current_status}\n`;
                message += `- Is Active: ${data.customer_status_check.is_active}\n`;
                message += `- Will Pass Middleware: ${data.customer_status_check.will_pass_middleware}\n`;
                
                if (!data.customer_status_check.will_pass_middleware) {
                    message += `\n🚨 PROBLEM FOUND: Customer status is "${data.customer_status_check.current_status}" but needs to be "Aktif"\n`;
                    message += `This is why checkout redirects to cart!`;
                }
            } else {
                message += `\n🚨 PROBLEM FOUND: No customer authentication!\n`;
                message += `This is why checkout redirects to cart!`;
            }
            
            message += `\nSession Info:\n`;
            message += `- Session ID: ${data.session.session_id}\n`;
            message += `- Has Customer Session: ${data.session.has_customer_session}\n`;
            
            alert(message);
        })
        .catch(error => {
            console.error('Middleware test error:', error);
            alert('Middleware test failed: ' + error.message);
        });
}

// Test bypass checkout (uses testCheckoutProcess route)
function testBypassCheckout() {
    console.log('=== TESTING BYPASS CHECKOUT ===');
    
    const form = document.getElementById('checkout-form');
    if (!form) {
        alert('Form not found!');
        return;
    }
    
    const formData = new FormData(form);
    
    // Add required data
    formData.set('payment_method', 'qris');
    formData.set('payment_action', 'qris');
    
    // Ensure required fields
    if (!formData.get('name')) formData.set('name', 'Debug Test User');
    if (!formData.get('email')) formData.set('email', 'debug@test.com');
    if (!formData.get('phone')) formData.set('phone', '08123456789');
    if (!formData.get('address')) formData.set('address', 'Debug Test Address');
    if (!formData.get('province')) formData.set('province', '1');
    if (!formData.get('city')) formData.set('city', '1');
    if (!formData.get('shipping_method')) formData.set('shipping_method', '15000');
    
    console.log('Sending to bypass route: /test/checkout/process');
    
    fetch('/test/checkout/process', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Bypass Response Status:', response.status);
        
        if (response.redirected) {
            console.log('SUCCESS: Redirected to payment page:', response.url);
            alert('✅ BYPASS SUCCESSFUL!\n\nRedirected to: ' + response.url + '\n\nThis confirms the issue is with middleware, not QRIS functionality.');
            window.location.href = response.url;
        } else {
            return response.text();
        }
    })
    .then(text => {
        if (text) {
            console.log('Bypass Response:', text);
            try {
                const data = JSON.parse(text);
                let message = 'Bypass Test Results:\n\n';
                message += `Success: ${data.success}\n`;
                message += `Message: ${data.message}\n`;
                
                if (data.debug_info) {
                    message += `\nDebug Info:\n`;
                    message += JSON.stringify(data.debug_info, null, 2);
                }
                
                alert(message);
            } catch (e) {
                alert('Bypass response (not JSON):\n' + text);
            }
        }
    })
    .catch(error => {
        console.error('Bypass test error:', error);
        alert('Bypass test failed: ' + error.message);
    });
}


</script>
@endsection
