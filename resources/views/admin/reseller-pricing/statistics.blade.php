@extends('layouts.admin.app')

@section('title', 'Statistik Pricing Reseller')

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
                    Statistik & Analisis Margin
                </h2>
                <p class="text-gray-600 text-sm mt-1">
                    Analisis mendalam margin keuntungan dan rekomendasi pricing strategy
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Quick Actions can be added here if needed -->
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Products -->
            <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Produk</p>
                        <p class="text-3xl font-bold text-gray-900">9</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Average Normal Margin -->
            <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Rata-rata Margin Normal</p>
                        <p class="text-3xl font-bold text-green-600">12.3%</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Products with Positive Discount Margin -->
            <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Produk Untung saat Diskon</p>
                        <p class="text-3xl font-bold text-green-600">7</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Products with Negative Discount Margin -->
            <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Produk Rugi saat Diskon</p>
                        <p class="text-3xl font-bold text-red-600">2</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detailed Analysis --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Margin Analysis Chart -->
            <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-[#614DAC]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Analisis Margin per Produk
                </h3>
                
                <div class="space-y-4">
                    <!-- Gentle Baby 10ml -->
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-900">Gentle Baby 10ml</span>
                        <div class="flex space-x-2">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">Normal: 5.5%</span>
                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded">Diskon: -26.2%</span>
                        </div>
                    </div>

                    <!-- Gentle Baby 30ml -->
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-900">Gentle Baby 30ml</span>
                        <div class="flex space-x-2">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">Normal: 3.4%</span>
                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded">Diskon: -27.6%</span>
                        </div>
                    </div>

                    <!-- Gentle Baby 100ml -->
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-900">Gentle Baby 100ml</span>
                        <div class="flex space-x-2">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">Normal: 3.8%</span>
                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded">Diskon: -27.4%</span>
                        </div>
                    </div>

                    <!-- Show More Button -->
                    <button class="w-full py-2 text-sm text-[#614DAC] hover:text-[#785576] font-medium transition-colors duration-200">
                        Lihat Semua Produk
                    </button>
                </div>
            </div>

            <!-- Recommendations -->
            <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-[#614DAC]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                    Rekomendasi
                </h3>
                
                <div class="space-y-4">
                    <!-- High Margin Products -->
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <h4 class="font-medium text-green-800 mb-2">Produk Margin Tertinggi</h4>
                        <ul class="text-sm text-green-700 space-y-1">
                            <li>• Breastfeeding Oil (15.7% margin normal)</li>
                            <li>• Healo (8.7% margin normal)</li>
                            <li>• Mamina ASI Booster 20 Teabag (4.8% margin normal)</li>
                        </ul>
                    </div>

                    <!-- Products to Watch -->
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <h4 class="font-medium text-yellow-800 mb-2">Perhatian Khusus</h4>
                        <ul class="text-sm text-yellow-700 space-y-1">
                            <li>• Gentle Baby Twinpack: Harga normal di bawah retail</li>
                            <li>• Semua produk Gentle Baby: Margin diskon negatif</li>
                        </ul>
                    </div>

                    <!-- Action Items -->
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h4 class="font-medium text-blue-800 mb-2">Saran Tindakan</h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Review harga retail untuk produk Gentle Baby</li>
                            <li>• Pertimbangkan diskon berbeda untuk tiap kategori</li>
                            <li>• Fokus promosi pada produk margin tinggi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pricing Strategy Alert --}}
        <div class="mt-8 bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-yellow-800">
                        Strategi Pricing Memerlukan Review
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>
                            Berdasarkan analisis, ada beberapa produk yang mengalami kerugian saat diberikan diskon 30%. 
                            Pertimbangkan untuk menyesuaikan strategi pricing atau memberikan diskon yang berbeda untuk setiap kategori produk.
                        </p>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.reseller-pricing.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            Lihat Detail Pricing
                            <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection