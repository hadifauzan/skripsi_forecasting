`@extends('layouts.admin.app')

@section('title', 'Data Reseller')

@section('content')
    <div class="min-h-screen bg-white">
        <div class="px-4 sm:px-6 lg:px-8 py-8">
            @php
                // Untuk summary cards, ambil semua data reseller
                $allResellersForSummary = \App\Models\MasterCustomers::whereHas('masterCustomerType', function (
                    $query,
                ) {
                    $query->where('name_customer_type', 'reseller');
                })->get();

                // Hitung statistik berdasarkan status
                $totalResellers = $allResellersForSummary->count();
                $activeResellers = $allResellersForSummary->where('status', 'Aktif')->count();
                $inactiveResellers = $allResellersForSummary->where('status', 'Nonaktif')->count();
                $pendingResellers = $allResellersForSummary->where('status', 'Pending')->count();

                // Debug info
                \Log::info('Reseller Summary Cards Data', [
                    'total' => $totalResellers,
                    'active' => $activeResellers,
                    'inactive' => $inactiveResellers,
                    'pending' => $pendingResellers,
                ]);

                // Debug table data
                \Log::info('Reseller Table Data', [
                    'resellers_count' => $resellers->count(),
                    'resellers_total' => $resellers->total(),
                    'resellers_items' => $resellers->items() ? count($resellers->items()) : 0,
                ]);
            @endphp

            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-[#785576] flex items-center mb-2">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 mr-2 sm:mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            Data Reseller
                        </h1>
                        <p class="text-gray-600">Kelola data reseller yang telah mendaftar</p>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Reseller Card -->
                <div
                    class="bg-[#446b6a] rounded-xl p-8 text-white shadow-lg transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80 mb-2">Total Reseller</p>
                            <p class="text-4xl font-bold">{{ $totalResellers }}</p>
                        </div>
                        <div class="bg-white/20 rounded-full p-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pending Reseller Card -->
                <div
                    class="bg-gradient-to-r from-orange-500 to-amber-500 rounded-xl p-8 text-white shadow-lg transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/90 mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Menunggu Konfirmasi
                            </p>
                            <p class="text-4xl font-bold">{{ $pendingResellers }}</p>
                            @if ($pendingResellers > 0)
                                <p class="text-xs text-white/80 mt-2 font-medium">Segera hubungi via WhatsApp!</p>
                            @endif
                        </div>
                        <div class="bg-white/20 rounded-full p-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L4.316 16.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Active Reseller Card -->
                <div
                    class="bg-green-600 rounded-xl p-8 text-white shadow-lg transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80 mb-2">Reseller Aktif</p>
                            <p class="text-4xl font-bold">{{ $activeResellers }}</p>
                        </div>
                        <div class="bg-white/20 rounded-full p-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Inactive Reseller Card -->
                <div
                    class="bg-red-600 rounded-xl p-8 text-white shadow-lg transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80 mb-2">Reseller Nonaktif</p>
                            <p class="text-4xl font-bold">{{ $inactiveResellers }}</p>
                        </div>
                        <div class="bg-white/20 rounded-full p-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms and Conditions Management Section -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-[#785576] to-[#6a4a68] rounded-2xl shadow-xl overflow-hidden">
                    <div class="px-6 py-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="bg-white/20 rounded-full p-3 mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">Syarat dan Ketentuan Reseller</h3>
                                    <p class="text-sm text-white/80">Kelola syarat dan ketentuan yang ditampilkan pada form pendaftaran</p>
                                </div>
                            </div>
                            <button onclick="openTermsModal()" 
                                class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg transition-all duration-200 font-medium flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Syarat & Ketentuan
                            </button>
                        </div>
                        
                        <div class="mt-4 bg-white/10 rounded-lg p-4">
                            <h4 class="font-medium text-white mb-3">Preview Syarat dan Ketentuan Saat Ini:</h4>
                            <div class="bg-white/20 border border-white/30 rounded-lg p-4 max-h-32 overflow-y-auto">
                                <div class="text-sm text-white whitespace-pre-line">{{ $termsContent }}</div>
                            </div>
                            <div class="mt-3 text-xs text-white/70">
                                <strong>Info:</strong> Teks ini akan ditampilkan pada bagian persetujuan di form pendaftaran reseller.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter and Search Section -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <!-- Search Bar -->
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="searchInput" placeholder="Cari berdasarkan nama atau email..."
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg 
                            text-gray-900 placeholder-gray-500 
                            focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent 
                            transition-all duration-200">
                    </div>
                    
                    <div class="flex gap-3">
                        <!-- Status Filter -->
                        <div class="w-full sm:w-48">
                            <select 
                                id="statusFilter"
                                class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700
                                    focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent
                                    transition-all duration-200"
                            >
                                <option value="">Semua Status</option>
                                <option value="Pending">Menunggu Konfirmasi</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        
                        <!-- Export Button -->
                        <button onclick="exportToExcel()"
                            class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors duration-200 whitespace-nowrap">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <span class="hidden sm:inline">Export Excel</span>
                            <span class="sm:hidden">Export</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Data Table -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                @if ($resellers->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gradient-to-r from-[#785576] to-[#5d4359]">
                                <tr>
                                    <th
                                        class="px-4 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-4 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Nama</th>
                                    <th
                                        class="px-4 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-4 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Kontak</th>
                                    <th
                                        class="px-4 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Alamat</th>
                                    <th
                                        class="px-4 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-4 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Platform</th>
                                    <th
                                        class="px-4 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Social Media</th>
                                    <th
                                        class="px-4 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-4 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-50">
                                @forelse($resellers as $index => $reseller)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200 reseller-row border-b border-gray-50"
                                        data-name="{{ strtolower($reseller->clean_name ?? ($reseller->name_customer ?? '')) }}"
                                        data-phone="{{ $reseller->phone_customer ?? '' }}"
                                        data-email="{{ $reseller->email_customer ?? '' }}"
                                        data-status="{{ $reseller->status ?? 'Pending' }}">

                                        <!-- Row Number -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span
                                                class="text-sm font-medium text-gray-900">{{ ($resellers->currentPage() - 1) * $resellers->perPage() + $index + 1 }}</span>
                                        </td>

                                        <!-- Name -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $reseller->clean_name ?? ($reseller->name_customer ?? 'Tidak ada nama') }}
                                            </div>
                                        </td>

                                        <!-- Email -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-700">
                                                @if ($reseller->email_customer)
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                        <span class="text-blue-600">{{ $reseller->email_customer }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 italic">Tidak ada email</span>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Contact -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-700">
                                                @if ($reseller->phone_customer)
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path
                                                                d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.130-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                                        </svg>
                                                        <span class="text-green-600">{{ $reseller->phone_customer }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 italic">Tidak ada kontak</span>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Address -->
                                        <td class="px-4 py-4">
                                            <div class="text-sm text-gray-700 max-w-xs">
                                                <div class="line-clamp-2">
                                                    {{ $reseller->address_customer ?? 'Tidak ada alamat' }}
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Status -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @php
                                                $status = $reseller->status ?? 'Pending';
                                                $statusClass = match($status) {
                                                    'Aktif' => 'bg-green-100 text-green-800 border border-green-200',
                                                    'Nonaktif' => 'bg-red-100 text-red-800 border border-red-200',
                                                    'Pending' => 'bg-orange-100 text-orange-800 border border-orange-200',
                                                    default => 'bg-gray-100 text-gray-800 border border-gray-200'
                                                };
                                                $statusIcon = match($status) {
                                                    'Aktif' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                                    'Nonaktif' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                                                    'Pending' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                                    default => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                                                };
                                                $statusText = match($status) {
                                                    'Pending' => 'Menunggu Konfirmasi',
                                                    default => $status
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusIcon }}"></path>
                                                </svg>
                                                {{ $statusText }}
                                            </span>
                                        </td>

                                        <!-- Sales Platform -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @php
                                                $salesInfo = $reseller->getSalesInfoFromLocationNotes();
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                    </path>
                                                </svg>
                                                {{ $salesInfo['berjualan_melalui'] ?? 'Social Media' }}
                                            </span>
                                        </td>

                                        <!-- Social Media -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @php
                                                $socialMedia = $reseller->getSocialMediaFromLocationNotes();
                                            @endphp
                                            <div class="space-y-1">
                                                <!-- Instagram -->
                                                @if ($socialMedia && isset($socialMedia['akun_instagram']) && $socialMedia['akun_instagram'])
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-2 text-pink-500" fill="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path
                                                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z">
                                                            </path>
                                                        </svg>
                                                        <span
                                                            class="text-xs text-pink-600">{{ $socialMedia['akun_instagram'] }}</span>
                                                    </div>
                                                @endif

                                                <!-- TikTok -->
                                                @if (
                                                    $socialMedia &&
                                                        isset($socialMedia['akun_tiktok']) &&
                                                        $socialMedia['akun_tiktok'] &&
                                                        $socialMedia['akun_tiktok'] !== '-')
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-2 text-black" fill="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path
                                                                d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z">
                                                            </path>
                                                        </svg>
                                                        <span
                                                            class="text-xs text-gray-900">{{ $socialMedia['akun_tiktok'] }}</span>
                                                    </div>
                                                @endif

                                                <!-- Show message if no social media -->
                                                @if (
                                                    (!$socialMedia || !isset($socialMedia['akun_instagram']) || !$socialMedia['akun_instagram']) &&
                                                        (!$socialMedia ||
                                                            !isset($socialMedia['akun_tiktok']) ||
                                                            !$socialMedia['akun_tiktok'] ||
                                                            $socialMedia['akun_tiktok'] === '-'))
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                                        Tidak ada
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Registration Date -->
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                <span
                                                    class="text-sm text-gray-600 font-medium">{{ $reseller->created_at->format('d/m/Y') }}</span>
                                            </div>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <div class="flex justify-center space-x-2">
                                                <!-- View Button -->
                                                <button onclick="viewDetails({{ $reseller->customer_id }})"
                                                    class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all duration-200"
                                                    title="Lihat Detail">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                </button>

                                                <!-- Edit Button -->
                                                <button onclick="editReseller({{ $reseller->customer_id }})"
                                                    class="p-2 text-yellow-600 hover:text-yellow-800 hover:bg-yellow-50 rounded-lg transition-all duration-200"
                                                    title="Edit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </button>

                                                <!-- Delete Button -->
                                                <button
                                                    onclick="deleteReseller({{ $reseller->customer_id }}, '{{ $reseller->clean_name ?? ($reseller->name_customer ?? 'Reseller') }}')"
                                                    class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200"
                                                    title="Hapus">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-4 py-12 text-center">
                                            <div class="text-gray-500">
                                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                    </path>
                                                </svg>
                                                <p class="text-lg font-medium text-gray-400 mb-2">Tidak ada data reseller
                                                </p>
                                                <p class="text-sm text-gray-400">Data akan muncul setelah ada reseller yang
                                                    mendaftar</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-4 py-4 bg-gray-50 border-t border-gray-100" id="paginationSection">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <!-- Info Section -->
                            <div class="text-sm text-gray-600">
                                @php
                                    $from = ($resellers->currentPage() - 1) * $resellers->perPage() + 1;
                                    $to = min($from + $resellers->perPage() - 1, $resellers->total());
                                @endphp
                                <span>
                                    Menampilkan <span class="font-medium text-gray-900">{{ $from }}</span> sampai
                                    <span class="font-medium text-gray-900">{{ $to }}</span> dari <span
                                        class="font-medium text-gray-900">{{ $resellers->total() }}</span> data reseller
                                </span>
                            </div>

                            <!-- Navigation Links -->
                            @if ($resellers->hasPages())
                                <div class="flex items-center space-x-1">
                                    {{-- Previous Page Link --}}
                                    @if ($resellers->onFirstPage())
                                        <span
                                            class="px-3 py-2 text-sm text-gray-400 bg-white border border-gray-200 rounded-lg cursor-not-allowed">
                                            Sebelumnya
                                        </span>
                                    @else
                                        <a href="{{ $resellers->previousPageUrl() }}"
                                            class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-[#785576] transition-all duration-200">
                                            Sebelumnya
                                        </a>
                                    @endif

                                    {{-- Page Numbers --}}
                                    @foreach ($resellers->getUrlRange(1, $resellers->lastPage()) as $page => $url)
                                        @if ($page == $resellers->currentPage())
                                            <span
                                                class="px-3 py-2 text-sm font-medium text-white bg-[#785576] border border-[#785576] rounded-lg">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $url }}"
                                                class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-[#785576] transition-all duration-200">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($resellers->hasMorePages())
                                        <a href="{{ $resellers->nextPageUrl() }}"
                                            class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-[#785576] transition-all duration-200">
                                            Selanjutnya
                                        </a>
                                    @else
                                        <span
                                            class="px-3 py-2 text-sm text-gray-400 bg-white border border-gray-200 rounded-lg cursor-not-allowed">
                                            Selanjutnya
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="max-w-md mx-auto">
                            <svg class="w-20 h-20 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <h3 class="text-xl font-medium text-gray-400 mb-3">Belum ada data reseller</h3>
                            <p class="text-gray-400 mb-6">Data reseller akan muncul di sini setelah ada yang mendaftar
                                melalui form pendaftaran.</p>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-sm text-blue-600">
                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Reseller dapat mendaftar melalui form pendaftaran yang tersedia di website.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Terms and Conditions Modal -->
        <div id="termsModal" 
            class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 transition-all duration-300">
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-[#785576] to-[#6a4a68] px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-white">Edit Syarat dan Ketentuan Reseller</h3>
                            <p class="text-sm text-white/80 mt-1">Kelola konten yang akan ditampilkan pada form pendaftaran</p>
                        </div>
                        <button onclick="closeTermsModal()" 
                            class="text-white/80 hover:text-white transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="p-8 overflow-y-auto max-h-[calc(90vh-120px)]">
                    <form id="termsForm" onsubmit="saveTerms(event)">
                        @csrf
                        
                        <!-- Guidelines Section -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="text-sm text-blue-800">
                                    <p class="font-medium mb-2">Panduan Penulisan:</p>
                                    <ul class="space-y-1 text-xs">
                                        <li>• Syarat dan ketentuan yang Anda masukkan akan ditampilkan pada bagian "Persetujuan" 
                                           di form pendaftaran reseller</li>
                                        <li>• Gunakan bahasa yang jelas dan mudah dipahami</li>
                                        <li>• Tekan Enter untuk membuat baris baru</li>
                                        <li>• Maksimal 2000 karakter</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Terms Content Input -->
                        <div class="mb-6">
                            <label for="termsContent" class="block text-sm font-medium text-gray-700 mb-3">
                                Syarat dan Ketentuan Reseller <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="termsContent" 
                                name="terms_content" 
                                rows="12" 
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#785576] focus:border-transparent resize-none"
                                placeholder="Masukkan syarat dan ketentuan untuk reseller..."
                                required>{{ $termsContent !== 'Belum ada syarat dan ketentuan yang ditetapkan.' ? $termsContent : '' }}</textarea>
                            
                            <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                                <span>Gunakan format plain text. Enter untuk baris baru.</span>
                                <span id="charCount">0 / 2000 karakter</span>
                            </div>
                        </div>

                        <!-- Guidelines -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="text-sm text-yellow-800">
                                    <p class="font-medium mb-1">Penting untuk Diperhatikan:</p>
                                    <p class="text-xs">Perubahan yang Anda buat akan langsung mempengaruhi form pendaftaran reseller yang dapat diakses oleh calon reseller. Pastikan konten sudah sesuai sebelum menyimpan.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <button type="button" onclick="closeTermsModal()"
                                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-8 py-3 bg-[#785576] text-white rounded-lg hover:bg-[#6a4a68] transition-colors duration-200 font-medium shadow-lg">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
        <script>
            // Search and Filter functionality
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchInput');
                const statusFilter = document.getElementById('statusFilter');

                // Add event listeners
                searchInput.addEventListener('input', filterAndRenumber);
                statusFilter.addEventListener('change', filterAndRenumber);

                function filterAndRenumber() {
                    const searchTerm = searchInput.value.toLowerCase();
                    const statusValue = statusFilter.value;
                    const rows = document.querySelectorAll('.reseller-row');
                    let visibleRowCount = 0;

                    rows.forEach((row, index) => {
                        const name = row.dataset.name || '';
                        const email = row.dataset.email || '';
                        const status = row.dataset.status || '';

                        const matchesSearch = name.includes(searchTerm) ||
                            email.includes(searchTerm);
                        const matchesStatus = !statusValue || status === statusValue;

                        if (matchesSearch && matchesStatus) {
                            row.style.display = '';
                            visibleRowCount++;
                            // Update numbering for visible rows starting from 1
                            const numberCell = row.querySelector('td:first-child span');
                            if (numberCell) {
                                numberCell.textContent = visibleRowCount;
                            }
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Update pagination info text
                    const paginationSection = document.getElementById('paginationSection');
                    if (searchTerm || statusValue) {
                        // When filtering, show filtered results info
                        const infoSpan = paginationSection.querySelector('.text-sm.text-gray-600 span');
                        if (infoSpan) {
                            infoSpan.innerHTML =
                                `Menampilkan <span class="font-medium text-gray-900">1</span> sampai <span class="font-medium text-gray-900">${visibleRowCount}</span> dari <span class="font-medium text-gray-900">${visibleRowCount}</span> data reseller (difilter)`;
                        }
                        // Hide navigation buttons when filtering
                        const navButtons = paginationSection.querySelector('.flex.items-center.space-x-1');
                        if (navButtons) navButtons.style.display = 'none';
                    } else {
                        // When not filtering, restore original pagination info
                        const infoSpan = paginationSection.querySelector('.text-sm.text-gray-600 span');
                        if (infoSpan) {
                            const currentPage = {{ $resellers->currentPage() }};
                            const perPage = {{ $resellers->perPage() }};
                            const total = {{ $resellers->total() }};
                            const from = (currentPage - 1) * perPage + 1;
                            const to = Math.min(from + perPage - 1, total);
                            infoSpan.innerHTML =
                                `Menampilkan <span class="font-medium text-gray-900">${from}</span> sampai <span class="font-medium text-gray-900">${to}</span> dari <span class="font-medium text-gray-900">${total}</span> data reseller`;
                        }
                        // Show navigation buttons when not filtering
                        const navButtons = paginationSection.querySelector('.flex.items-center.space-x-1');
                        if (navButtons) navButtons.style.display = 'flex';
                    }
                }
            });

            // Export Excel function
            function exportToExcel() {
                const button = document.querySelector('button[onclick="exportToExcel()"]');
                const originalText = button.innerHTML;

                // Show loading state
                button.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses Excel...
            `;
                button.disabled = true;

                // Check if there's data to export first
                const visibleRows = document.querySelectorAll('.reseller-row:not([style*="display: none"])');
                if (visibleRows.length === 0) {
                    Swal.fire({
                        title: 'Tidak Ada Data',
                        text: 'Tidak ada data yang dapat diekspor. Pastikan ada data reseller di tabel.',
                        icon: 'warning',
                        confirmButtonColor: '#f59e0b'
                    });

                    // Reset button
                    button.innerHTML = originalText;
                    button.disabled = false;
                    return;
                }

                // Redirect to export URL
                window.location.href = '/admin/export-reseller-excel';

                // Reset button and show success message after a delay
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;

                    // Show success notification
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'File Excel berhasil diunduh',
                        icon: 'success',
                        confirmButtonColor: '#785576',
                        timer: 2000,
                        timerProgressBar: true
                    });
                }, 3000);
            }

            // CRUD functions
            function viewDetails(id) {
                window.location.href = `/admin/reseller/${id}/view`;
            }

            function editReseller(id) {
                window.location.href = `/admin/reseller/${id}/edit`;
            }

            function deleteReseller(id, name) {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: `Apakah Anda yakin ingin menghapus data ${name}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Menghapus...',
                            text: 'Sedang memproses permintaan',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Send delete request
                        fetch(`/admin/reseller/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: data.message,
                                        icon: 'success',
                                        confirmButtonColor: '#785576'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: data.message || 'Terjadi kesalahan saat menghapus data',
                                        icon: 'error',
                                        confirmButtonColor: '#785576'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan pada server',
                                    icon: 'error',
                                    confirmButtonColor: '#785576'
                                });
                            });
                    }
                });
            }

            // Terms and Conditions Modal Functions
            function openTermsModal() {
                const modal = document.getElementById('termsModal');
                const termsContent = document.getElementById('termsContent');
                
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                
                // Update character count
                updateCharCount();
                
                // Focus on textarea
                setTimeout(() => termsContent.focus(), 100);
            }

            function closeTermsModal() {
                const modal = document.getElementById('termsModal');
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            function updateCharCount() {
                const termsContent = document.getElementById('termsContent');
                const charCount = document.getElementById('charCount');
                const currentLength = termsContent.value.length;
                
                charCount.textContent = `${currentLength} / 2000 karakter`;
                
                if (currentLength > 2000) {
                    charCount.classList.add('text-red-500');
                    charCount.classList.remove('text-gray-500');
                } else {
                    charCount.classList.remove('text-red-500');
                    charCount.classList.add('text-gray-500');
                }
            }

            function saveTerms(event) {
                event.preventDefault();
                
                const termsContent = document.getElementById('termsContent').value.trim();
                
                if (!termsContent) {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Syarat dan ketentuan tidak boleh kosong',
                        icon: 'warning',
                        confirmButtonColor: '#785576'
                    });
                    return;
                }

                if (termsContent.length > 2000) {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Syarat dan ketentuan tidak boleh lebih dari 2000 karakter',
                        icon: 'warning',
                        confirmButtonColor: '#785576'
                    });
                    return;
                }

                // Show loading
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Sedang memproses perubahan syarat dan ketentuan',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send AJAX request
                fetch('/admin/reseller/terms-conditions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        terms_content: termsContent
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Syarat dan ketentuan berhasil diperbarui',
                            icon: 'success',
                            confirmButtonColor: '#785576'
                        }).then(() => {
                            closeTermsModal();
                            // Reload page to show updated terms in preview
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan saat menyimpan',
                            icon: 'error',
                            confirmButtonColor: '#785576'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan pada server',
                        icon: 'error',
                        confirmButtonColor: '#785576'
                    });
                });
            }

            // Character count listener
            document.addEventListener('DOMContentLoaded', function() {
                const termsContent = document.getElementById('termsContent');
                if (termsContent) {
                    termsContent.addEventListener('input', updateCharCount);
                }
                
                // Close modal when clicking outside
                document.getElementById('termsModal').addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeTermsModal();
                    }
                });
                
                // Close modal with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        const modal = document.getElementById('termsModal');
                        if (!modal.classList.contains('hidden')) {
                            closeTermsModal();
                        }
                    }
                });
            });
        </script>
    @endsection
