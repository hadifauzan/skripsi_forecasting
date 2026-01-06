@extends('layouts.admin.app')

@section('title', 'Pembelian Customer Reseller')

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
                    Pembelian Customer Reseller
                </h2>
                <p class="text-gray-600 text-sm mt-1">
                    Data customer reseller yang mengambil produk dengan harga diskon yang dapat diatur dan poin yang didapat
                </p>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Reseller</p>
                        <p class="text-3xl font-bold text-purple-600">{{ count($purchasesData) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Transaksi</p>
                        <p class="text-3xl font-bold text-blue-600">{{ array_sum(array_column($purchasesData, 'transaction_count')) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Diskon</p>
                        <p class="text-3xl font-bold text-green-600">Rp {{ number_format(array_sum(array_column($purchasesData, 'discount_amount')), 0, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Poin</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ number_format(array_sum(array_column($purchasesData, 'calculated_points')), 0, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Purchases Table --}}
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-[#785576] px-6 py-4">
                <h3 class="text-lg font-bold text-white font-nunito flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Daftar Pembelian Reseller
                </h3>
                <p class="text-purple-100 text-sm mt-1">
                    Detail pembelian produk dengan harga diskon yang dapat diatur dan poin yang didapat
                </p>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Reseller
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Transaksi
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Pembelian
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Diskon
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Setelah Diskon
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Poin Terkumpul
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($purchasesData as $index => $purchase)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-white text-sm font-bold">{{ substr($purchase['name'], 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $purchase['name'] }}</div>
                                            <div class="text-xs text-gray-500">{{ $purchase['email'] }}</div>
                                            <div class="text-xs text-gray-500">{{ $purchase['phone'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $purchase['transaction_count'] }} transaksi
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-bold text-gray-900">
                                        Rp {{ number_format($purchase['total_purchase'], 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-bold text-red-600">
                                        - Rp {{ number_format($purchase['discount_amount'], 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $purchase['total_purchase'] > 0 ? number_format(($purchase['discount_amount'] / $purchase['total_purchase']) * 100, 1) : 0 }}% diskon
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-bold text-green-600">
                                        Rp {{ number_format($purchase['total_after_discount'], 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="text-lg font-bold text-yellow-600">
                                            {{ number_format($purchase['calculated_points'], 0, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-gray-500">poin</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <button onclick="showPurchaseDetails({{ $index }})" 
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-[#614DAC] hover:bg-[#785576] transition-colors duration-200 mr-2">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Detail
                                    </button>
                                    <button onclick="editDiscounts({{ $purchase['customer_id'] }}, {{ $index }})" 
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Atur Diskon
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="mt-4 text-lg font-medium">Belum ada data pembelian reseller</p>
                                    <p class="mt-2 text-sm">Data akan muncul setelah reseller melakukan pembelian</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
        </div>
    </div>

    {{-- Purchase Details Modal --}}
    <div id="purchaseDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white mb-10">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900" id="modalTitle">Detail Pembelian Reseller</h3>
                    <button onclick="closePurchaseDetails()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div id="modalContent" class="space-y-6">
                    <!-- Content will be loaded dynamically -->
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button onclick="closePurchaseDetails()" 
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded font-medium">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Discount Edit Modal --}}
    <div id="discountEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white mb-10">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900" id="discountModalTitle">Atur Diskon Produk per Transaksi</h3>
                    <button onclick="closeDiscountModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div id="discountModalContent" class="space-y-6">
                    <!-- Content will be loaded dynamically -->
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button onclick="closeDiscountModal()" 
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded font-medium">
                        Batal
                    </button>
                    <button onclick="saveAllDiscounts()" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-medium flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Semua Diskon
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const purchasesData = @json($purchasesData);
        let currentTransactions = [];
        let currentCustomerId = null;

        function showPurchaseDetails(index) {
            const purchase = purchasesData[index];
            const modal = document.getElementById('purchaseDetailsModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');

            modalTitle.textContent = `Detail Pembelian - ${purchase.name}`;

            let productsHtml = '';
            if (purchase.products_purchased && Object.keys(purchase.products_purchased).length > 0) {
                productsHtml = `
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h4 class="font-semibold text-gray-800">Produk yang Dibeli</h4>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Poin/Unit</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Total Poin</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                `;

                for (const [productName, productData] of Object.entries(purchase.products_purchased)) {
                    productsHtml += `
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">${productName}</td>
                            <td class="px-4 py-3 text-sm text-center text-gray-700">${productData.quantity}</td>
                            <td class="px-4 py-3 text-sm text-center text-blue-600 font-medium">${productData.points}</td>
                            <td class="px-4 py-3 text-sm text-center text-green-600 font-bold">${productData.total_points.toLocaleString()}</td>
                        </tr>
                    `;
                }

                productsHtml += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            } else {
                productsHtml = `
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-sm text-yellow-800">Belum ada detail produk yang dibeli</p>
                    </div>
                `;
            }

            modalContent.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="text-sm text-blue-600 mb-1">Email</div>
                        <div class="text-base font-semibold text-blue-900">${purchase.email || '-'}</div>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="text-sm text-green-600 mb-1">Telepon</div>
                        <div class="text-base font-semibold text-green-900">${purchase.phone || '-'}</div>
                    </div>
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <div class="text-sm text-purple-600 mb-1">Total Transaksi</div>
                        <div class="text-2xl font-bold text-purple-900">${purchase.transaction_count}</div>
                    </div>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="text-sm text-yellow-600 mb-1">Poin Terkumpul</div>
                        <div class="text-2xl font-bold text-yellow-900">${purchase.calculated_points.toLocaleString()}</div>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="text-sm text-red-600 mb-1">Total Pembelian</div>
                        <div class="text-xl font-bold text-red-900">Rp ${purchase.total_purchase.toLocaleString()}</div>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="text-sm text-green-600 mb-1">Total Diskon</div>
                        <div class="text-xl font-bold text-green-900">Rp ${purchase.discount_amount.toLocaleString()}</div>
                    </div>
                    <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                        <div class="text-sm text-teal-600 mb-1">Setelah Diskon</div>
                        <div class="text-xl font-bold text-teal-900">Rp ${purchase.total_after_discount.toLocaleString()}</div>
                    </div>
                </div>
                
                ${productsHtml}
                
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="text-sm text-gray-600 mb-1">Transaksi Terakhir</div>
                    <div class="text-base font-semibold text-gray-900">${purchase.last_transaction || 'Belum ada transaksi'}</div>
                </div>
            `;

            modal.classList.remove('hidden');
        }

        function closePurchaseDetails() {
            document.getElementById('purchaseDetailsModal').classList.add('hidden');
        }

        async function editDiscounts(customerId, purchaseIndex) {
            currentCustomerId = customerId;
            const purchase = purchasesData[purchaseIndex];
            const modal = document.getElementById('discountEditModal');
            const modalTitle = document.getElementById('discountModalTitle');
            const modalContent = document.getElementById('discountModalContent');

            modalTitle.textContent = `Atur Diskon - ${purchase.name}`;

            // Show loading
            modalContent.innerHTML = `
                <div class="flex justify-center items-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
                </div>
            `;
            modal.classList.remove('hidden');

            try {
                // Fetch transaction details from server
                const response = await fetch(`/admin/reseller-pricing/customer/${customerId}/transactions`);
                const data = await response.json();
                currentTransactions = data.transactions || [];

                if (currentTransactions.length === 0) {
                    modalContent.innerHTML = `
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <p class="mt-4 text-lg font-medium text-yellow-800">Belum ada transaksi</p>
                        </div>
                    `;
                    return;
                }

                let contentHtml = '<div class="space-y-6">';
                
                currentTransactions.forEach((transaction, transIndex) => {
                    // Calculate transaction totals
                    let transactionSubtotal = 0;
                    let transactionDiscountFromProducts = 0;
                    
                    transaction.details.forEach(detail => {
                        const subtotal = detail.sell_price * detail.qty;
                        const discountAmount = detail.discount_amount || 0;
                        transactionSubtotal += subtotal;
                        transactionDiscountFromProducts += discountAmount;
                    });
                    
                    const transactionDiscountPercentage = transaction.discount_percentage || 0;
                    const transactionDiscountAmount = transaction.discount_amount || 0;
                    
                    contentHtml += `
                        <div class="bg-white border border-gray-300 rounded-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-600 to-purple-500 px-4 py-3">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-semibold text-white">Transaksi #${transaction.number || transaction.id}</h4>
                                    <span class="text-white text-sm">${transaction.date || '-'}</span>
                                </div>
                            </div>
                            
                            <!-- Transaction Level Discount -->
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b-2 border-blue-200 px-4 py-4">
                                <h5 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Diskon Level Transaksi (Berlaku untuk seluruh transaksi)
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Subtotal Transaksi</label>
                                        <div class="text-lg font-bold text-gray-900" id="transaction_subtotal_${transIndex}">
                                            Rp ${transactionSubtotal.toLocaleString()}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Diskon Transaksi (%)</label>
                                        <input type="number" 
                                            id="transaction_discount_${transIndex}"
                                            data-trans-index="${transIndex}"
                                            data-transaction-id="${transaction.id}"
                                            data-transaction-subtotal="${transactionSubtotal}"
                                            value="${transactionDiscountPercentage}"
                                            min="0" 
                                            max="100" 
                                            step="0.01"
                                            onchange="calculateTransactionDiscount(${transIndex})"
                                            class="w-full px-3 py-2 border-2 border-blue-300 rounded-lg text-center focus:ring-2 focus:ring-blue-500 focus:border-transparent font-semibold">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Diskon Transaksi (Rp)</label>
                                        <div class="text-lg font-bold text-red-600" id="transaction_discount_amount_${transIndex}">
                                            Rp ${transactionDiscountAmount.toLocaleString()}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Product Level Discounts -->
                            <div class="overflow-x-auto">
                                <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                                    <h5 class="text-xs font-semibold text-gray-600 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        Diskon Per Produk
                                    </h5>
                                </div>
                                <table class="w-full">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Produk</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-600 uppercase">Qty</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-600 uppercase">Harga Satuan</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-600 uppercase">Subtotal</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-600 uppercase w-32">Diskon (%)</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-600 uppercase">Diskon (Rp)</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-600 uppercase">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                    `;

                    transaction.details.forEach((detail, detailIndex) => {
                        const subtotal = detail.sell_price * detail.qty;
                        const discountPercentage = detail.discount_percentage || 0;
                        const discountAmount = detail.discount_amount || 0;
                        const total = subtotal - discountAmount;

                        contentHtml += `
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900">${detail.product_name}</td>
                                <td class="px-4 py-3 text-sm text-center text-gray-700">${detail.qty}</td>
                                <td class="px-4 py-3 text-sm text-right text-gray-700">Rp ${detail.sell_price.toLocaleString()}</td>
                                <td class="px-4 py-3 text-sm text-right font-medium text-gray-900">Rp ${subtotal.toLocaleString()}</td>
                                <td class="px-4 py-3">
                                    <input type="number" 
                                        id="discount_${transIndex}_${detailIndex}"
                                        data-trans-index="${transIndex}"
                                        data-detail-index="${detailIndex}"
                                        data-detail-id="${detail.id}"
                                        data-subtotal="${subtotal}"
                                        value="${discountPercentage}"
                                        min="0" 
                                        max="100" 
                                        step="0.01"
                                        onchange="calculateDiscount(${transIndex}, ${detailIndex})"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-center focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                </td>
                                <td class="px-4 py-3 text-sm text-right font-medium text-red-600">
                                    <span id="discount_amount_${transIndex}_${detailIndex}">Rp ${discountAmount.toLocaleString()}</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-right font-bold text-green-600">
                                    <span id="total_${transIndex}_${detailIndex}">Rp ${total.toLocaleString()}</span>
                                </td>
                            </tr>
                        `;
                    });

                    contentHtml += `
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Transaction Summary -->
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-4 border-t-2 border-gray-300">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div class="text-right md:col-start-2">
                                        <div class="text-xs text-gray-600">Diskon Produk</div>
                                        <div class="text-sm font-bold text-red-600" id="transaction_product_discount_total_${transIndex}">
                                            Rp ${transactionDiscountFromProducts.toLocaleString()}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-gray-600">Diskon Transaksi</div>
                                        <div class="text-sm font-bold text-red-600" id="transaction_discount_display_${transIndex}">
                                            Rp ${transactionDiscountAmount.toLocaleString()}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-gray-600">Total Akhir</div>
                                        <div class="text-lg font-bold text-green-600" id="transaction_final_total_${transIndex}">
                                            Rp ${(transactionSubtotal - transactionDiscountFromProducts - transactionDiscountAmount).toLocaleString()}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                contentHtml += '</div>';
                modalContent.innerHTML = contentHtml;

            } catch (error) {
                console.error('Error loading transactions:', error);
                modalContent.innerHTML = `
                    <div class="bg-red-50 border border-red-200 rounded-lg p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="mt-4 text-lg font-medium text-red-800">Gagal memuat data transaksi</p>
                        <p class="mt-2 text-sm text-red-600">${error.message}</p>
                    </div>
                `;
            }
        }

        function calculateDiscount(transIndex, detailIndex) {
            const input = document.getElementById(`discount_${transIndex}_${detailIndex}`);
            const discountPercentage = parseFloat(input.value) || 0;
            const subtotal = parseFloat(input.dataset.subtotal);
            
            // Calculate discount amount
            const discountAmount = (subtotal * discountPercentage) / 100;
            const total = subtotal - discountAmount;
            
            // Update display
            document.getElementById(`discount_amount_${transIndex}_${detailIndex}`).textContent = 
                `Rp ${discountAmount.toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0})}`;
            document.getElementById(`total_${transIndex}_${detailIndex}`).textContent = 
                `Rp ${total.toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0})}`;
            
            // Recalculate transaction totals
            updateTransactionTotals(transIndex);
        }

        function calculateTransactionDiscount(transIndex) {
            const input = document.getElementById(`transaction_discount_${transIndex}`);
            const discountPercentage = parseFloat(input.value) || 0;
            const transactionSubtotal = parseFloat(input.dataset.transactionSubtotal);
            
            // Calculate transaction discount amount
            const discountAmount = (transactionSubtotal * discountPercentage) / 100;
            
            // Update display
            document.getElementById(`transaction_discount_amount_${transIndex}`).textContent = 
                `Rp ${discountAmount.toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0})}`;
            
            // Recalculate transaction totals
            updateTransactionTotals(transIndex);
        }

        function updateTransactionTotals(transIndex) {
            const transaction = currentTransactions[transIndex];
            let totalProductDiscount = 0;
            
            // Calculate total product discounts
            transaction.details.forEach((detail, detailIndex) => {
                const input = document.getElementById(`discount_${transIndex}_${detailIndex}`);
                if (input) {
                    const discountPercentage = parseFloat(input.value) || 0;
                    const subtotal = parseFloat(input.dataset.subtotal);
                    const discountAmount = (subtotal * discountPercentage) / 100;
                    totalProductDiscount += discountAmount;
                }
            });
            
            // Get transaction discount
            const transactionDiscountInput = document.getElementById(`transaction_discount_${transIndex}`);
            const transactionDiscountPercentage = parseFloat(transactionDiscountInput.value) || 0;
            const transactionSubtotal = parseFloat(transactionDiscountInput.dataset.transactionSubtotal);
            const transactionDiscountAmount = (transactionSubtotal * transactionDiscountPercentage) / 100;
            
            // Calculate final total
            const finalTotal = transactionSubtotal - totalProductDiscount - transactionDiscountAmount;
            
            // Update displays
            document.getElementById(`transaction_product_discount_total_${transIndex}`).textContent = 
                `Rp ${totalProductDiscount.toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0})}`;
            document.getElementById(`transaction_discount_display_${transIndex}`).textContent = 
                `Rp ${transactionDiscountAmount.toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0})}`;
            document.getElementById(`transaction_final_total_${transIndex}`).textContent = 
                `Rp ${finalTotal.toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0})}`;
        }

        async function saveAllDiscounts() {
            const discountData = [];
            const transactionDiscounts = [];
            
            // Collect all discount inputs
            currentTransactions.forEach((transaction, transIndex) => {
                // Collect transaction-level discount
                const transactionDiscountInput = document.getElementById(`transaction_discount_${transIndex}`);
                if (transactionDiscountInput) {
                    const transactionDiscountPercentage = parseFloat(transactionDiscountInput.value) || 0;
                    const transactionSubtotal = parseFloat(transactionDiscountInput.dataset.transactionSubtotal);
                    const transactionDiscountAmount = (transactionSubtotal * transactionDiscountPercentage) / 100;
                    
                    transactionDiscounts.push({
                        transaction_id: transactionDiscountInput.dataset.transactionId,
                        discount_percentage: transactionDiscountPercentage,
                        discount_amount: transactionDiscountAmount
                    });
                }
                
                // Collect product-level discounts
                transaction.details.forEach((detail, detailIndex) => {
                    const input = document.getElementById(`discount_${transIndex}_${detailIndex}`);
                    if (input) {
                        const discountPercentage = parseFloat(input.value) || 0;
                        const subtotal = parseFloat(input.dataset.subtotal);
                        const discountAmount = (subtotal * discountPercentage) / 100;
                        const total = subtotal - discountAmount;
                        
                        discountData.push({
                            detail_id: input.dataset.detailId,
                            discount_percentage: discountPercentage,
                            discount_amount: discountAmount,
                            total_amount: total
                        });
                    }
                });
            });

            try {
                const response = await fetch('/admin/reseller-pricing/update-discounts', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        customer_id: currentCustomerId,
                        discounts: discountData,
                        transaction_discounts: transactionDiscounts
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    // Show success message
                    alert('Diskon berhasil disimpan!');
                    closeDiscountModal();
                    // Reload page to refresh data
                    window.location.reload();
                } else {
                    alert('Gagal menyimpan diskon: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error saving discounts:', error);
                alert('Terjadi kesalahan saat menyimpan diskon');
            }
        }

        function closeDiscountModal() {
            document.getElementById('discountEditModal').classList.add('hidden');
            currentTransactions = [];
            currentCustomerId = null;
        }

        // Close modal when clicking outside
        document.getElementById('purchaseDetailsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePurchaseDetails();
            }
        });

        document.getElementById('discountEditModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDiscountModal();
            }
        });
    </script>

@endsection
