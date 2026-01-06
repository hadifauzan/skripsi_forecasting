@extends('layouts.admin.app')

@section('title', 'Data Affiliator')

@section('content')
    <div class="min-h-screen bg-white">
        <div class="px-4 sm:px-6 lg:px-8 py-8">
        @php
            // Untuk summary cards, ambil semua data affiliator (role_id = 4)
            $allAffiliatesForSummary = \App\Models\User::where('role_id', 4)->get();
            $totalAffiliates = $allAffiliatesForSummary->count();
            $activeAffiliates = $allAffiliatesForSummary->where('status', 'Aktif')->count();
            $inactiveAffiliates = $allAffiliatesForSummary->where('status', 'Nonaktif')->count();
            $pendingAffiliates = $allAffiliatesForSummary->where('status', 'Pending')->count();

            // Debug info
            \Log::info('Summary Cards Data', [
                'total' => $totalAffiliates,
                'active' => $activeAffiliates,
                'inactive' => $inactiveAffiliates,
                'pending' => $pendingAffiliates,
            ]);

            // Debug table data
            \Log::info('Table Data', [
                'affiliates_count' => $affiliates->count(),
                'affiliates_total' => $affiliates->total(),
                'affiliates_items' => $affiliates->items() ? count($affiliates->items()) : 0,
            ]);
        @endphp
        <!-- Page Header -->
        <div class="mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-[#785576] flex items-center mb-2">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    Data Affiliator
                </h1>
                <p class="text-gray-600">Kelola data affiliator yang telah mendaftar di sistem Gentle Living</p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Affiliator Card -->
            <div
                class="bg-[#446b6a] rounded-xl p-8 text-white shadow-lg transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-white/80 mb-2">Total Affiliator</p>
                        <p class="text-4xl font-bold">{{ $totalAffiliates }}</p>
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

            <!-- Pending Affiliator Card -->
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
                        <p class="text-4xl font-bold">{{ $pendingAffiliates }}</p>
                        @if ($pendingAffiliates > 0)
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

            <!-- Active Affiliator Card -->
            <div
                class="bg-green-600 rounded-xl p-8 text-white shadow-lg transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-white/80 mb-2">Affiliator Aktif</p>
                        <p class="text-4xl font-bold">{{ $activeAffiliates }}</p>
                    </div>
                    <div class="bg-white/20 rounded-full p-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Inactive Affiliator Card -->
            <div
                class="bg-red-600 rounded-xl p-8 text-white shadow-lg transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-white/80 mb-2">Affiliator Nonaktif</p>
                        <p class="text-4xl font-bold">{{ $inactiveAffiliates }}</p>
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

        <!-- Filter and Search Section -->
        <div class="flex flex-col md:flex-row items-center gap-3 mb-6">
                
            <!-- Search Input -->
            <div class="relative flex-1 w-full">
                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input 
                    type="text" 
                    id="searchInput" 
                    placeholder="Cari nama atau email..." 
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg 
                        text-sm text-gray-700 placeholder-gray-400 
                        focus:outline-none focus:ring-1 focus:ring-[#528B89] focus:border-[#528B89]
                        transition-all duration-200"
                >
            </div>

            <!-- Status Filter -->
            <div class="w-full md:w-48">
                <select 
                    id="statusFilter"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700
                        focus:outline-none focus:ring-1 focus:ring-[#528B89] focus:border-[#528B89]
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
                class="inline-flex items-center px-6 py-3 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors duration-200 shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Export Excel
            </button>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">


            <!-- Tampilkan tabel dalam semua kondisi untuk debugging -->
            @if (true)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-[#785576] to-[#5d4359]">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">WhatsApp</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Provinsi</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Kota</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Akun Shopee</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Instagram</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">

                            @forelse($affiliates as $index => $affiliate)
                                <tr class="hover:bg-purple-50 transition-colors duration-200 affiliate-row"
                                    data-name="{{ strtolower($affiliate->name ?? 'no-name') }}"
                                    data-email="{{ strtolower($affiliate->email ?? 'no-email') }}"
                                    data-status="{{ $affiliate->status ?? 'Pending' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium row-number">
                                        {{ ($affiliates->currentPage() - 1) * $affiliates->perPage() + $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $affiliate->name ?? 'No Name' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">
                                            {{ $affiliate->email ?? 'No Email' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center text-sm text-gray-900">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                            </svg>
                                            {{ $affiliate->phone ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $affiliate->province ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $affiliate->city ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $status = $affiliate->status;
                                            $statusClass = match ($status) {
                                                'Aktif' => 'bg-green-100 text-green-800',
                                                'Nonaktif' => 'bg-red-100 text-red-800',
                                                'Pending' => 'bg-orange-100 text-orange-800',
                                                default => 'bg-gray-100 text-gray-800',
                                            };
                                            $statusText = match ($status) {
                                                'Pending' => 'Menunggu Konfirmasi',
                                                default => $status,
                                            };
                                        @endphp
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                            @if ($status === 'Pending')
                                                <svg class="w-3 h-3 mr-1 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @endif
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($affiliate->shopee_account)
                                            <div class="text-sm text-gray-900 flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M18.94 4.94A1.5 1.5 0 0017.5 4h-11a1.5 1.5 0 00-1.44.94l-2 6A1.5 1.5 0 004.5 13h15a1.5 1.5 0 001.44-1.94l-2-6zM9 15H7v5h2v-5zm4 0h-2v5h2v-5zm4 0h-2v5h2v-5z"/>
                                                </svg>
                                                {{ $affiliate->shopee_account }}
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($affiliate->instagram_account)
                                            <div class="text-sm text-gray-900 flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                                </svg>
                                                {{ $affiliate->instagram_account }}
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        {{ $affiliate->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <!-- View Details Button -->
                                            <a href="/admin/affiliate/{{ $affiliate->user_id }}/view"
                                                class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all duration-200"
                                                title="Lihat Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>

                                            <!-- Edit Button -->
                                            <a href="/admin/affiliate/{{ $affiliate->user_id }}/edit"
                                                class="p-2 text-yellow-600 hover:text-yellow-800 hover:bg-yellow-50 rounded-lg transition-all duration-200"
                                                title="Edit Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>

                                            <!-- Delete Button -->
                                            <button
                                                onclick="deleteAffiliate({{ $affiliate->user_id }}, '{{ $affiliate->name }}')"
                                                class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200"
                                                title="Hapus Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="px-6 py-12 text-center text-gray-500">
                                        <strong>No Data Found in Loop</strong><br>
                                        Variable $affiliates tidak mengandung data atau error dalam loop
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-8 py-6 border-t border-gray-200" id="paginationSection">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <!-- Info Section -->
                        <div class="text-sm text-gray-700">
                            @php
                                $from = ($affiliates->currentPage() - 1) * $affiliates->perPage() + 1;
                                $to = min($from + $affiliates->perPage() - 1, $affiliates->total());
                            @endphp
                            <span>Menampilkan <span class="font-semibold">{{ $from }}</span> - <span
                                    class="font-semibold">{{ $to }}</span> dari <span
                                    class="font-semibold">{{ $affiliates->total() }}</span> data</span>
                        </div>

                        <!-- Navigation Links -->
                        @if ($affiliates->hasPages())
                            <div class="flex items-center space-x-2">
                                {{-- Previous Page Link --}}
                                @if ($affiliates->onFirstPage())
                                    <span
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 cursor-default leading-5 rounded-md">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Sebelumnya
                                    </span>
                                @else
                                    <a href="{{ $affiliates->previousPageUrl() }}"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#528B89] focus:border-brand-500 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Sebelumnya
                                    </a>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($affiliates->hasMorePages())
                                    <a href="{{ $affiliates->nextPageUrl() }}"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#528B89] focus:border-brand-500 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                        Selanjutnya
                                        <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @else
                                    <span
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 cursor-default leading-5 rounded-md">
                                        Selanjutnya
                                        <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data affiliator</h3>
                    <p class="text-gray-500">Data affiliator akan muncul di sini setelah ada yang mendaftar.</p>
                </div>
            @endif
        </div>
    </div>



    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Search and Filter functionality with automatic renumbering
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');

            // Add event listeners
            searchInput.addEventListener('input', filterAndRenumber);
            statusFilter.addEventListener('change', filterAndRenumber);

            function filterAndRenumber() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;
                const rows = document.querySelectorAll('.affiliate-row');
                let visibleRowCount = 0;

                // Update pagination info based on filtering
                const paginationSection = document.getElementById('paginationSection');

                rows.forEach((row, index) => {
                    const name = row.dataset.name || '';
                    const email = row.dataset.email || '';
                    const status = row.dataset.status || '';

                    const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                    const matchesStatus = !statusValue || status === statusValue;

                    if (matchesSearch && matchesStatus) {
                        row.style.display = '';
                        visibleRowCount++;
                        // Update numbering for visible rows starting from 1
                        const numberCell = row.querySelector('.row-number');
                        if (numberCell) {
                            numberCell.textContent = visibleRowCount;
                        }
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Update pagination info text
                if (searchTerm || statusValue) {
                    // When filtering, show filtered results info
                    const infoSpan = paginationSection.querySelector('.text-sm.text-gray-700 span');
                    if (infoSpan) {
                        infoSpan.innerHTML =
                            `Menampilkan <span class="font-semibold">1</span> - <span class="font-semibold">${visibleRowCount}</span> dari <span class="font-semibold">${visibleRowCount}</span> data (difilter)`;
                    }
                    // Hide navigation buttons when filtering
                    const navButtons = paginationSection.querySelector('.flex.items-center.space-x-2');
                    if (navButtons) navButtons.style.display = 'none';
                } else {
                    // When not filtering, restore original pagination info
                    const infoSpan = paginationSection.querySelector('.text-sm.text-gray-700 span');
                    if (infoSpan) {
                        const currentPage = {{ $affiliates->currentPage() }};
                        const perPage = {{ $affiliates->perPage() }};
                        const total = {{ $affiliates->total() }};
                        const from = (currentPage - 1) * perPage + 1;
                        const to = Math.min(from + perPage - 1, total);
                        infoSpan.innerHTML =
                            `Menampilkan <span class="font-semibold">${from}</span> - <span class="font-semibold">${to}</span> dari <span class="font-semibold">${total}</span> data`;
                    }
                    // Show navigation buttons when not filtering
                    const navButtons = paginationSection.querySelector('.flex.items-center.space-x-2');
                    if (navButtons) navButtons.style.display = 'flex';
                }

                // Show/hide empty state message
                showEmptyStateIfNeeded(visibleRowCount);
            }

            // Reset filter function
            function resetFilters() {
                searchInput.value = '';
                statusFilter.value = '';

                // Show all rows with original numbering
                const rows = document.querySelectorAll('.affiliate-row');
                rows.forEach((row, index) => {
                    row.style.display = '';
                    const numberCell = row.querySelector('.row-number');
                    if (numberCell) {
                        // Calculate original pagination number
                        const currentPage = {{ $affiliates->currentPage() }};
                        const perPage = {{ $affiliates->perPage() }};
                        const originalNumber = (currentPage - 1) * perPage + index + 1;
                        numberCell.textContent = originalNumber;
                    }
                });

                // Restore original pagination info
                const paginationSection = document.getElementById('paginationSection');
                const infoSpan = paginationSection.querySelector('.text-sm.text-gray-700 span');
                if (infoSpan) {
                    const currentPage = {{ $affiliates->currentPage() }};
                    const perPage = {{ $affiliates->perPage() }};
                    const total = {{ $affiliates->total() }};
                    const from = (currentPage - 1) * perPage + 1;
                    const to = Math.min(from + perPage - 1, total);
                    infoSpan.innerHTML =
                        `Menampilkan <span class="font-semibold">${from}</span> - <span class="font-semibold">${to}</span> dari <span class="font-semibold">${total}</span> data`;
                }

                // Show navigation buttons
                const navButtons = paginationSection.querySelector('.flex.items-center.space-x-2');
                if (navButtons) navButtons.style.display = 'flex';

                // Remove empty message
                const emptyMessage = document.getElementById('emptyMessage');
                if (emptyMessage) emptyMessage.remove();
            }

            // Add reset button functionality if needed
            window.resetFilters = resetFilters;

                            function showEmptyStateIfNeeded(visibleRowCount) {
                const tableBody = document.querySelector('tbody');
                let emptyMessage = document.getElementById('emptyMessage');

                if (visibleRowCount === 0) {
                    if (!emptyMessage) {
                        emptyMessage = document.createElement('tr');
                        emptyMessage.id = 'emptyMessage';
                        emptyMessage.innerHTML = `
                            <td colspan="11" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data yang ditemukan</h3>
                                    <p class="text-gray-500 mb-4">Coba ubah kata kunci pencarian atau filter status.</p>
                                    <button onclick="resetFilters()" class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors duration-200">
                                        Reset Filter
                                    </button>
                                </div>
                            </td>
                        `;
                        tableBody.appendChild(emptyMessage);
                    }
                } else {
                    if (emptyMessage) {
                        emptyMessage.remove();
                    }
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
            const visibleRows = document.querySelectorAll('.affiliate-row:not([style*="display: none"])');
            if (visibleRows.length === 0) {
                Swal.fire({
                    title: 'Tidak Ada Data',
                    text: 'Tidak ada data yang dapat diekspor. Pastikan ada data affiliator di tabel.',
                    icon: 'warning',
                    confirmButtonColor: '#f59e0b'
                });
                
                // Reset button
                button.innerHTML = originalText;
                button.disabled = false;
                return;
            }

            // Show notification about export
            Swal.fire({
                title: 'Memproses Export',
                text: 'File Excel sedang dipersiapkan dengan data lengkap dari detail view...',
                icon: 'info',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false
            });

            // Redirect to export URL
            window.location.href = '/admin/export-excel';

            // Reset button after a delay
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 3000);
        }

        // Delete Affiliate Function
        function deleteAffiliate(id, name) {
            console.log('deleteAffiliate called with ID:', id, 'Name:', name);

            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus data ${name}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'swal2-popup-custom'
                }
            }).then((result) => {
                console.log('SweetAlert result:', result);

                if (result.isConfirmed) {
                    console.log('User confirmed delete, proceeding...');

                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    console.log('CSRF token element:', csrfToken);
                    console.log('CSRF token value:', csrfToken ? csrfToken.getAttribute('content') : 'NOT FOUND');

                    fetch(`/admin/affiliate/${id}/delete`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : '',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            console.log('Delete response status:', response.status);
                            return response.text().then(text => {
                                console.log('Raw delete response:', text);
                                try {
                                    return JSON.parse(text);
                                } catch (e) {
                                    console.error('Delete response is not JSON:', text);
                                    throw new Error('Server returned invalid JSON: ' + text);
                                }
                            });
                        })
                        .then(data => {
                            console.log('Delete response data:', data);
                            if (data.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: 'Data berhasil dihapus',
                                    icon: 'success',
                                    confirmButtonColor: '#528B89',
                                    timer: 1500,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                throw new Error(data.message || 'Gagal menghapus data');
                            }
                        })
                        .catch(error => {
                            console.error('Delete error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: error.message || 'Terjadi kesalahan saat menghapus data',
                                icon: 'error',
                                confirmButtonColor: '#dc2626'
                            });
                        });
                } else {
                    console.log('User cancelled delete');
                }
            });
        }
    </script>
    <script src="{{ asset('js/view-data.js') }}"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
@endsection
