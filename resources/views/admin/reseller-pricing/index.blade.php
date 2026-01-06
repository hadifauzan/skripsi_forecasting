@extends('layouts.admin.app')

@section('title', 'Pricing System Reseller')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="p-6">
        @include('admin.reseller-pricing.horizontal-navigation')

        {{-- Header Section --}}
        <div class="flex items-center justify-between py-5 font-nunito mb-6">
            <div>
                <h2 class="font-nunito font-bold text-2xl text-[#614DAC]">
                    Daftar Harga Produk
                </h2>
                <p class="text-gray-600 text-sm mt-1">
                    Sistem harga untuk reseller dengan margin keuntungan yang menarik
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Quick Actions can be added here if needed -->
            </div>
        </div>

        {{-- Tiered Discount Information --}}
        <div class="bg-[#785576] rounded-lg shadow-lg mb-6 overflow-hidden">
            <div class="px-6 py-4">
                <h3 class="text-lg font-bold text-white font-nunito flex items-center mb-4">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Diskon Bertingkat Berdasarkan Total Pembelian
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($tieredDiscounts as $tier)
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-4 border border-white border-opacity-30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-black text-sm font-medium mb-1">{{ $tier['description'] }}</div>
                                    <div class="text-black-300 text-xs">
                                        @if($tier['max_purchase'])
                                            Rp {{ number_format($tier['min_purchase'], 0, ',', '.') }} - Rp {{ number_format($tier['max_purchase'], 0, ',', '.') }}
                                        @else
                                            > Rp {{ number_format($tier['min_purchase'], 0, ',', '.') }}
                                        @endif
                                    </div>
                                </div>
                                <div class="bg-yellow-400 text-purple-900 px-4 py-2 rounded-full font-bold text-xl">
                                    {{ $tier['discount_percentage'] }}%
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-3 border border-white border-opacity-20">
                    <p class="text-black text-sm flex items-start">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Diskon berlaku otomatis sesuai total pembelian. Semakin besar pembelian, semakin besar diskon yang didapat!</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Pricing Table --}}
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-[#785576] px-6 py-4">
                <h3 class="text-lg font-bold text-white font-nunito flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    Daftar Harga Produk Reseller
                </h3>
                <p class="text-purple-100 text-sm mt-1">
                    Sistem harga untuk reseller dengan margin keuntungan yang menarik
                </p>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Produk
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga Eceran Terendah
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga Jual Normal
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga Setelah Diskon 30%
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Margin Keuntungan
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($pricingData as $index => $product)
                            @php
                                $marginNormal = $product['normal_price'] - $product['retail_price'];
                                $marginDiscount = $product['discount_price'] - $product['retail_price'];
                                $marginPercentageNormal = ($marginNormal / $product['retail_price']) * 100;
                                $marginPercentageDiscount = ($marginDiscount / $product['retail_price']) * 100;
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-[#614DAC] rounded-full flex items-center justify-center mr-3">
                                            <span class="text-white text-sm font-bold">{{ $index + 1 }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $product['name'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">
                                        Rp {{ number_format($product['retail_price'], 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500">Harga dari supplier</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-blue-600">
                                        Rp {{ number_format($product['normal_price'], 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-green-600 font-medium">
                                        +Rp {{ number_format($marginNormal, 0, ',', '.') }}
                                        ({{ number_format($marginPercentageNormal, 1) }}%)
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-red-600">
                                        Rp {{ number_format($product['discount_price'], 0, ',', '.') }}
                                    </div>
                                    @if($marginDiscount > 0)
                                        <div class="text-xs text-green-600 font-medium">
                                            +Rp {{ number_format($marginDiscount, 0, ',', '.') }}
                                            ({{ number_format($marginPercentageDiscount, 1) }}%)
                                        </div>
                                    @else
                                        <div class="text-xs text-red-600 font-medium">
                                            Rp {{ number_format($marginDiscount, 0, ',', '.') }}
                                            ({{ number_format($marginPercentageDiscount, 1) }}%)
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1">
                                        <!-- Normal margin badge -->
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Normal: {{ number_format($marginPercentageNormal, 1) }}%
                                        </span>
                                        <!-- Discount margin badge -->
                                        @if($marginDiscount > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Diskon: {{ number_format($marginPercentageDiscount, 1) }}%
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Diskon: {{ number_format($marginPercentageDiscount, 1) }}%
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Summary Cards -->
            <div class="bg-gray-50 px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Total Products Card -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total Produk</p>
                                <p class="text-xl font-bold text-gray-900">{{ count($pricingData) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Average Margin Card -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <div>
                                @php
                                    $totalMargin = 0;
                                    foreach($pricingData as $product) {
                                        $margin = (($product['normal_price'] - $product['retail_price']) / $product['retail_price']) * 100;
                                        $totalMargin += $margin;
                                    }
                                    $avgMargin = $totalMargin / count($pricingData);
                                @endphp
                                <p class="text-sm text-gray-600">Rata-rata Margin</p>
                                <p class="text-xl font-bold text-gray-900">{{ number_format($avgMargin, 1) }}%</p>
                            </div>
                        </div>
                    </div>

                    <!-- Discount Info Card -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Diskon Uniform</p>
                                <p class="text-xl font-bold text-gray-900">30%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Notes -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mx-6 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Informasi Penting</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li><strong>Harga Eceran Terendah:</strong> Harga dasar dari supplier yang menjadi modal reseller</li>
                                <li><strong>Harga Jual Normal:</strong> Harga yang direkomendasikan untuk dijual tanpa diskon</li>
                                <li><strong>Harga Setelah Diskon:</strong> Harga promosi dengan potongan 30% dari harga normal</li>
                                <li><strong>Margin Keuntungan:</strong> Selisih antara harga jual dengan harga modal (dalam persen)</li>
                                <li><strong>Diskon Bertingkat:</strong> Diskon tambahan berdasarkan total pembelian (5% untuk Rp 1jt-5jt, 10% untuk >Rp 5jt)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tiered Discount Calculator -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-300 rounded-lg p-6 mx-6 mb-6">
                <h3 class="text-lg font-bold text-purple-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Kalkulator Diskon Bertingkat
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Pembelian (Rp)</label>
                        <input type="text" id="purchaseAmount" 
                            class="w-full px-4 py-3 border-2 border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="Masukkan total pembelian..."
                            oninput="calculateDiscount()">
                    </div>
                    <div id="discountResult" class="hidden">
                        <div class="bg-white rounded-lg p-4 border-2 border-purple-300">
                            <div class="text-sm text-gray-600 mb-1">Diskon yang didapat:</div>
                            <div class="text-3xl font-bold text-purple-600 mb-2" id="discountPercentage">0%</div>
                            <div class="text-sm text-gray-600 mb-1">Total setelah diskon:</div>
                            <div class="text-xl font-bold text-green-600" id="finalAmount">Rp 0</div>
                            <div class="text-sm text-red-600 font-medium mt-2" id="savingsAmount">Hemat: Rp 0</div>
                        </div>
                    </div>
                </div>
                <div id="discountTierInfo" class="mt-4 p-4 bg-white bg-opacity-50 rounded-lg hidden">
                    <p class="text-sm text-gray-700"></p>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-6 flex justify-end space-x-3">
            <button onclick="window.print()" 
                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium flex items-center space-x-2 transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Print</span>
            </button>
            
            <a href="{{ route('admin.reseller-pricing.statistics') }}" 
                class="bg-[#614DAC] hover:bg-[#785576] text-white px-6 py-2 rounded-lg font-medium flex items-center space-x-2 transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span>Lihat Statistik</span>
            </a>
        </div>
    </div>

@endsection

@push('scripts')
<script>
function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function calculateDiscount() {
    const input = document.getElementById('purchaseAmount');
    const resultDiv = document.getElementById('discountResult');
    const discountPercentageEl = document.getElementById('discountPercentage');
    const finalAmountEl = document.getElementById('finalAmount');
    const savingsAmountEl = document.getElementById('savingsAmount');
    const tierInfoDiv = document.getElementById('discountTierInfo');
    
    // Remove non-numeric characters
    let value = input.value.replace(/[^0-9]/g, '');
    
    if (value === '') {
        resultDiv.classList.add('hidden');
        tierInfoDiv.classList.add('hidden');
        return;
    }
    
    // Format input with dots
    input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    
    const amount = parseInt(value);
    let discountPercentage = 0;
    let tierMessage = '';
    
    if (amount >= 5000001) {
        discountPercentage = 10;
        tierMessage = '🎉 Selamat! Anda mendapatkan diskon 10% karena pembelian di atas Rp 5.000.000';
    } else if (amount >= 1000000 && amount <= 5000000) {
        discountPercentage = 5;
        tierMessage = '✨ Anda mendapatkan diskon 5% untuk pembelian Rp 1.000.000 - Rp 5.000.000';
        if (amount > 4000000) {
            const toNextTier = 5000001 - amount;
            tierMessage += `. Tambah ${formatRupiah(toNextTier)} lagi untuk mendapat diskon 10%!`;
        }
    } else if (amount > 0) {
        discountPercentage = 0;
        const toFirstTier = 1000000 - amount;
        tierMessage = `💡 Belum ada diskon. Tambah ${formatRupiah(toFirstTier)} lagi untuk mendapat diskon 5%!`;
    }
    
    const discountAmount = amount * (discountPercentage / 100);
    const finalAmount = amount - discountAmount;
    
    // Show results
    resultDiv.classList.remove('hidden');
    tierInfoDiv.classList.remove('hidden');
    
    discountPercentageEl.textContent = discountPercentage + '%';
    finalAmountEl.textContent = formatRupiah(finalAmount);
    savingsAmountEl.textContent = 'Hemat: ' + formatRupiah(discountAmount);
    tierInfoDiv.querySelector('p').textContent = tierMessage;
}
</script>
@endpush