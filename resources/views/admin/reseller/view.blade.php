@extends('layouts.admin.app')

@section('title', 'Detail Reseller')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-[#785576] flex items-center mb-2">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Detail Reseller
                        </h1>
                        <p class="text-gray-600">Informasi lengkap data reseller</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <a href="{{ route('admin.reseller.edit', $reseller->customer_id) }}"
                            class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span class="hidden sm:inline">Edit Data</span>
                            <span class="sm:hidden">Edit</span>
                        </a>
                        <a href="{{ route('admin.data-reseller') }}"
                            class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Detail Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-[#785576] px-4 py-4 sm:px-6">
                    <h2 class="text-lg sm:text-xl font-semibold text-white">Informasi Reseller</h2>
                </div>

                <!-- Card Content -->
                <div class="p-4 sm:p-6 lg:p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Informasi Dasar -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                                Informasi Dasar
                            </h3>
                            
                            <div class="space-y-3">
                                <!-- Nama Lengkap -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2">
                                        <span class="text-gray-900 text-sm">{{ $reseller->clean_name ?? $reseller->name_customer ?? 'Tidak ada nama' }}</span>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 flex items-center">
                                        <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        @if($reseller->email_customer)
                                            <span class="text-blue-600 text-sm">{{ $reseller->email_customer }}</span>
                                        @else
                                            <span class="text-gray-500 text-sm italic">Tidak ada email</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Kontak WhatsApp -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kontak WhatsApp</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 flex items-center">
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.479 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                        </svg>
                                        <span class="text-green-600 text-sm">{{ $reseller->phone_customer ?? 'Tidak ada kontak' }}</span>
                                    </div>
                                </div>

                                <!-- Alamat Lengkap -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2">
                                        <span class="text-gray-900 text-sm">{{ $reseller->address_customer ?? 'Tidak ada alamat' }}</span>
                                    </div>
                                </div>

                                <!-- Tanggal Pendaftaran -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pendaftaran</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2">
                                        <span class="text-gray-900 text-sm">{{ $reseller->created_at->format('d F Y, H:i') }}</span>
                                    </div>
                                </div>

                                <!-- Tanggal Terakhir Diedit -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Terakhir Diedit</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 flex items-center">
                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-gray-900 text-sm">
                                            @if($reseller->updated_at && $reseller->updated_at != $reseller->created_at)
                                                {{ $reseller->updated_at->format('d F Y, H:i') }}
                                            @else
                                                Belum pernah diedit
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                                Media Sosial & Bisnis
                            </h3>
                            
                            <div class="space-y-3">
                                @php
                                    $socialMedia = $reseller->getSocialMediaFromLocationNotes();
                                    $salesInfo = $reseller->getSalesInfoFromLocationNotes();
                                @endphp

                                <!-- Instagram -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Akun Instagram</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 flex items-center">
                                        <svg class="w-4 h-4 text-pink-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"></path>
                                        </svg>
                                        <span class="text-pink-600 text-sm">
                                            {{ $socialMedia['akun_instagram'] ?? 'Tidak ada akun Instagram' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- TikTok -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Akun TikTok</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 flex items-center">
                                        <svg class="w-4 h-4 text-black mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"></path>
                                        </svg>
                                        <span class="text-gray-900 text-sm">
                                            @if($socialMedia && isset($socialMedia['akun_tiktok']) && $socialMedia['akun_tiktok'] && $socialMedia['akun_tiktok'] !== '-')
                                                {{ $socialMedia['akun_tiktok'] }}
                                            @else
                                                Tidak ada akun TikTok
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <!-- Sales Platform -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Berjualan Melalui</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 flex items-center">
                                        <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $salesInfo['berjualan_melalui'] ?? 'Tidak ada platform' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Status Reseller -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Reseller</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 flex items-center">
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
                                        <svg class="w-4 h-4 mr-2 text-current" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusIcon }}"></path>
                                        </svg>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Point/Poin Reseller -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Poin Reseller</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 flex items-center">
                                        <svg class="w-4 h-4 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                        <span class="text-yellow-600 text-sm font-medium">
                                            {{ $reseller->point ?? '0' }} Poin
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection