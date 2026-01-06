@extends('layouts.admin.app')

@section('title', 'Sistem Poin Reseller')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .points-card {
            transition: all 0.3s ease;
        }
        .points-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        .reward-card {
            transition: all 0.3s ease;
        }
        .reward-card:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .gradient-text {
            background: linear-gradient(45deg, #614DAC, #785576);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
@endpush

@section('content')

    <div class="p-6">
        @include('admin.reseller-pricing.horizontal-navigation')

        {{-- Header Section --}}
        <div class="flex items-center justify-between py-5 font-nunito mb-6">
            <div>
                <h2 class="font-nunito font-bold text-2xl text-[#614DAC]">
                    Sistem Poin Reseller
                </h2>
                <p class="text-gray-600 text-sm mt-1">
                    Kelola sistem poin dan bonus untuk reseller berdasarkan penjualan produk
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Quick Actions can be added here if needed -->
            </div>
        </div>

        {{-- Points Rules Section --}}
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden mb-6">
            <!-- Header -->
            <div class="bg-[#785576] px-6 py-4">
                <h3 class="text-lg font-bold text-white font-nunito flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                    </svg>
                    Perolehan Poin Per Produk
                </h3>
                <p class="text-purple-100 text-sm mt-1">
                    Poin yang diperoleh reseller untuk setiap penjualan produk
                </p>
            </div>

            <!-- Points Rules Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Produk
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Poin per Unit
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Keterangan
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-pink-400 to-purple-400 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Gentle Baby 10ml</div>
                                        <div class="text-xs text-gray-500">Ukuran mini</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                    Gentle Baby
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="text-lg font-bold text-green-600">1</div>
                                <div class="text-xs text-gray-500">poin</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                Produk entry level dengan poin dasar
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-pink-400 to-purple-400 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Gentle Baby 30ml</div>
                                        <div class="text-xs text-gray-500">Ukuran reguler</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                    Gentle Baby
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="text-lg font-bold text-green-600">4</div>
                                <div class="text-xs text-gray-500">poin</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                Ukuran populer dengan poin lebih tinggi
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-pink-400 to-purple-400 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Gentle Baby Twinpack</div>
                                        <div class="text-xs text-gray-500">Paket bundling</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                    Gentle Baby
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="text-lg font-bold text-green-600">5</div>
                                <div class="text-xs text-gray-500">poin</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                Paket hemat dengan poin bonus
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-pink-400 to-purple-400 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Gentle Baby 100ml</div>
                                        <div class="text-xs text-gray-500">Ukuran jumbo</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                    Gentle Baby
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="text-lg font-bold text-green-600">12</div>
                                <div class="text-xs text-gray-500">poin</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                Ukuran terbesar dengan poin maksimal
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-teal-400 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Healo 10ml</div>
                                        <div class="text-xs text-gray-500">Produk kesehatan</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Healo
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="text-lg font-bold text-green-600">1</div>
                                <div class="text-xs text-gray-500">poin</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                Produk kesehatan dengan poin standar
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-teal-400 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Healo Bundling</div>
                                        <div class="text-xs text-gray-500">Paket bundling</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Healo
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="text-lg font-bold text-green-600">4</div>
                                <div class="text-xs text-gray-500">poin</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                Paket bundling dengan nilai lebih tinggi
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">ASI Booster 20 Teabag</div>
                                        <div class="text-xs text-gray-500">Paket lengkap</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ASI Booster
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="text-lg font-bold text-green-600">7</div>
                                <div class="text-xs text-gray-500">poin</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                Paket lengkap dengan poin premium
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">ASI Booster 10 Teabag</div>
                                        <div class="text-xs text-gray-500">Paket trial</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ASI Booster
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="text-lg font-bold text-green-600">4</div>
                                <div class="text-xs text-gray-500">poin</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                Paket trial dengan poin moderate
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Bonus Rewards Section --}}
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
                <h3 class="text-lg font-bold text-white font-nunito flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    Bonus Reward Berdasarkan Akumulasi Poin
                </h3>
                <p class="text-yellow-100 text-sm mt-1">
                    Dapatkan bonus uang tunai ketika mencapai target poin tertentu
                </p>
            </div>

            <!-- Rewards Grid -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <!-- Reward Level 1 -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-6 text-center transform hover:scale-105 transition-all duration-300">
                        <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-green-700 mb-2">1,000 Poin</h4>
                        <div class="text-2xl font-bold text-green-600 mb-2">Rp 500.000</div>
                        <p class="text-sm text-green-600">Bonus pertama untuk reseller aktif</p>
                    </div>

                    <!-- Reward Level 2 -->
                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 border-2 border-blue-200 rounded-xl p-6 text-center transform hover:scale-105 transition-all duration-300">
                        <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-blue-700 mb-2">2,500 Poin</h4>
                        <div class="text-2xl font-bold text-blue-600 mb-2">Rp 1.500.000</div>
                        <p class="text-sm text-blue-600">Level silver dengan bonus menarik</p>
                    </div>

                    <!-- Reward Level 3 -->
                    <div class="bg-gradient-to-br from-purple-50 to-indigo-50 border-2 border-purple-200 rounded-xl p-6 text-center transform hover:scale-105 transition-all duration-300">
                        <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-purple-700 mb-2">5,000 Poin</h4>
                        <div class="text-2xl font-bold text-purple-600 mb-2">Rp 3.500.000</div>
                        <p class="text-sm text-purple-600">Level gold dengan bonus premium</p>
                    </div>

                    <!-- Reward Level 4 -->
                    <div class="bg-gradient-to-br from-orange-50 to-red-50 border-2 border-orange-200 rounded-xl p-6 text-center transform hover:scale-105 transition-all duration-300">
                        <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-orange-700 mb-2">7,500 Poin</h4>
                        <div class="text-2xl font-bold text-orange-600 mb-2">Rp 5.000.000</div>
                        <p class="text-sm text-orange-600">Level platinum dengan bonus besar</p>
                    </div>

                    <!-- Reward Level 5 -->
                    <div class="bg-gradient-to-br from-pink-50 to-rose-50 border-2 border-pink-200 rounded-xl p-6 text-center transform hover:scale-105 transition-all duration-300">
                        <div class="w-16 h-16 bg-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-pink-700 mb-2">10,000 Poin</h4>
                        <div class="text-2xl font-bold text-pink-600 mb-2">Rp 7.500.000</div>
                        <p class="text-sm text-pink-600">Level diamond dengan bonus fantastis</p>
                    </div>

                    <!-- Reward Level 6 -->
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 border-2 border-indigo-200 rounded-xl p-6 text-center transform hover:scale-105 transition-all duration-300">
                        <div class="w-16 h-16 bg-indigo-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-indigo-700 mb-2">12,500 Poin</h4>
                        <div class="text-2xl font-bold text-indigo-600 mb-2">Rp 10.000.000</div>
                        <p class="text-sm text-indigo-600">Level master dengan bonus luar biasa</p>
                    </div>

                    <!-- Reward Level 7 -->
                    <div class="bg-gradient-to-br from-yellow-50 to-amber-50 border-2 border-yellow-300 rounded-xl p-6 text-center transform hover:scale-105 transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-yellow-700 mb-2">15,000 Poin</h4>
                        <div class="text-2xl font-bold text-yellow-600 mb-2">Rp 12.500.000</div>
                        <p class="text-sm text-yellow-600">Level ultimate dengan bonus maksimal</p>
                    </div>
                </div>
            </div>

            <!-- Info Section -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-t border-gray-200 p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-blue-800 mb-2">Ketentuan Sistem Poin</h3>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Poin akan otomatis terakumulasi setiap kali reseller melakukan penjualan produk</li>
                            <li>• Bonus akan diberikan secara otomatis ketika mencapai target poin tertentu</li>
                            <li>• Poin tidak dapat ditransfer antar reseller</li>
                            <li>• Bonus akan ditransfer ke rekening reseller dalam 3-5 hari kerja</li>
                            <li>• Sistem poin berlaku untuk semua reseller aktif</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-6 flex justify-between items-center">
            <div class="flex space-x-3">
                <button onclick="openPointsCalculator()" 
                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium flex items-center space-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span>Kalkulator Poin</span>
                </button>
            </div>
            
            <div class="flex space-x-3">
                <button onclick="window.print()" 
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium flex items-center space-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Print</span>
                </button>
                
                <a href="{{ route('admin.reseller-pricing.index') }}" 
                    class="bg-[#614DAC] hover:bg-[#785576] text-white px-6 py-2 rounded-lg font-medium flex items-center space-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Kembali ke Pricing</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Points Calculator Modal --}}
    <div id="pointsCalculatorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Kalkulator Poin Reseller</h3>
                    <button onclick="closePointsCalculator()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <p class="text-sm text-blue-700">
                        Masukkan jumlah produk yang terjual untuk menghitung total poin dan estimasi bonus yang akan diperoleh.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <!-- Product inputs -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-800">Jumlah Produk Terjual</h4>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="text-sm text-gray-600">Gentle Baby 10ml:</label>
                                <input type="number" id="calc_gentle_10" min="0" value="0" 
                                    class="w-20 px-2 py-1 border border-gray-300 rounded text-center" 
                                    onchange="calculatePoints()">
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <label class="text-sm text-gray-600">Gentle Baby 30ml:</label>
                                <input type="number" id="calc_gentle_30" min="0" value="0" 
                                    class="w-20 px-2 py-1 border border-gray-300 rounded text-center" 
                                    onchange="calculatePoints()">
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <label class="text-sm text-gray-600">Gentle Baby Twinpack:</label>
                                <input type="number" id="calc_gentle_twin" min="0" value="0" 
                                    class="w-20 px-2 py-1 border border-gray-300 rounded text-center" 
                                    onchange="calculatePoints()">
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <label class="text-sm text-gray-600">Gentle Baby 100ml:</label>
                                <input type="number" id="calc_gentle_100" min="0" value="0" 
                                    class="w-20 px-2 py-1 border border-gray-300 rounded text-center" 
                                    onchange="calculatePoints()">
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <label class="text-sm text-gray-600">Healo 10ml:</label>
                                <input type="number" id="calc_healo_10" min="0" value="0" 
                                    class="w-20 px-2 py-1 border border-gray-300 rounded text-center" 
                                    onchange="calculatePoints()">
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <label class="text-sm text-gray-600">Healo Bundling:</label>
                                <input type="number" id="calc_healo_bundle" min="0" value="0" 
                                    class="w-20 px-2 py-1 border border-gray-300 rounded text-center" 
                                    onchange="calculatePoints()">
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <label class="text-sm text-gray-600">ASI Booster 20 Teabag:</label>
                                <input type="number" id="calc_asi_20" min="0" value="0" 
                                    class="w-20 px-2 py-1 border border-gray-300 rounded text-center" 
                                    onchange="calculatePoints()">
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <label class="text-sm text-gray-600">ASI Booster 10 Teabag:</label>
                                <input type="number" id="calc_asi_10" min="0" value="0" 
                                    class="w-20 px-2 py-1 border border-gray-300 rounded text-center" 
                                    onchange="calculatePoints()">
                            </div>
                        </div>
                    </div>

                    <!-- Results -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-800">Hasil Perhitungan</h4>
                        
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-green-600" id="totalPoints">0</div>
                                <div class="text-sm text-green-700">Total Poin</div>
                            </div>
                        </div>
                        
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-yellow-600" id="estimatedBonus">Rp 0</div>
                                <div class="text-sm text-yellow-700">Estimasi Bonus Tercapai</div>
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="text-center">
                                <div class="text-lg font-bold text-blue-600" id="nextTarget">-</div>
                                <div class="text-sm text-blue-700">Target Bonus Selanjutnya</div>
                            </div>
                        </div>
                        
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <div class="text-center">
                                <div class="text-lg font-bold text-purple-600" id="pointsNeeded">-</div>
                                <div class="text-sm text-purple-700">Poin yang Dibutuhkan</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button onclick="resetCalculator()" 
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded font-medium">
                        Reset
                    </button>
                    <button onclick="closePointsCalculator()" 
                        class="bg-[#614DAC] hover:bg-[#785576] text-white px-4 py-2 rounded font-medium">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openPointsCalculator() {
            document.getElementById('pointsCalculatorModal').classList.remove('hidden');
            calculatePoints(); // Initial calculation
        }

        function closePointsCalculator() {
            document.getElementById('pointsCalculatorModal').classList.add('hidden');
        }

        function resetCalculator() {
            const inputs = document.querySelectorAll('#pointsCalculatorModal input[type="number"]');
            inputs.forEach(input => input.value = 0);
            calculatePoints();
        }

        function calculatePoints() {
            const pointsPerProduct = {
                'calc_gentle_10': 1,
                'calc_gentle_30': 4,
                'calc_gentle_twin': 5,
                'calc_gentle_100': 12,
                'calc_healo_10': 1,
                'calc_healo_bundle': 4,
                'calc_asi_20': 7,
                'calc_asi_10': 4
            };

            const bonusLevels = [
                { points: 1000, bonus: 500000 },
                { points: 2500, bonus: 1500000 },
                { points: 5000, bonus: 3500000 },
                { points: 7500, bonus: 5000000 },
                { points: 10000, bonus: 7500000 },
                { points: 12500, bonus: 10000000 },
                { points: 15000, bonus: 12500000 }
            ];

            let totalPoints = 0;

            // Calculate total points
            Object.keys(pointsPerProduct).forEach(id => {
                const quantity = parseInt(document.getElementById(id).value) || 0;
                totalPoints += quantity * pointsPerProduct[id];
            });

            // Find highest achieved bonus
            let achievedBonus = 0;
            let nextTarget = null;
            let pointsNeeded = 0;

            for (let i = 0; i < bonusLevels.length; i++) {
                if (totalPoints >= bonusLevels[i].points) {
                    achievedBonus = bonusLevels[i].bonus;
                } else {
                    nextTarget = bonusLevels[i];
                    pointsNeeded = bonusLevels[i].points - totalPoints;
                    break;
                }
            }

            // Update display
            document.getElementById('totalPoints').textContent = totalPoints.toLocaleString();
            document.getElementById('estimatedBonus').textContent = 'Rp ' + achievedBonus.toLocaleString();
            
            if (nextTarget) {
                document.getElementById('nextTarget').textContent = 'Rp ' + nextTarget.bonus.toLocaleString() + ' (' + nextTarget.points.toLocaleString() + ' poin)';
                document.getElementById('pointsNeeded').textContent = pointsNeeded.toLocaleString() + ' poin lagi';
            } else {
                document.getElementById('nextTarget').textContent = 'Target Maksimal Tercapai!';
                document.getElementById('pointsNeeded').textContent = '0 poin (Sempurna!)';
            }
        }

        // Close modal when clicking outside
        document.getElementById('pointsCalculatorModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePointsCalculator();
            }
        });
    </script>

@endsection