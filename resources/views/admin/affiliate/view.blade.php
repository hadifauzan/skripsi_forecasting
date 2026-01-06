@extends('layouts.admin.app')

@section('title', 'Detail Affiliator')

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
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Detail Affiliator
                        </h1>
                        <p class="text-gray-600">Informasi lengkap data affiliator</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <!-- Tombol Edit -->
                        <a href="/admin/affiliate/{{ $affiliate->user_id }}/edit"
                            class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                                    a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span class="hidden sm:inline">Edit Data</span>
                            <span class="sm:hidden">Edit</span>
                        </a>

                        <!-- Tombol Kembali -->
                        <a href="/admin/data-affiliator"
                            class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Detail Card -->
            <div class="bg-white shadow-xl rounded-xl overflow-hidden">
                <!-- Card Header -->
                <div class="bg-[#785576] px-4 py-4 sm:px-6">
                    <h2 class="text-lg sm:text-xl font-semibold text-white">Informasi Affiliator</h2>
                </div>

                <!-- Content -->
                <div class="p-6">
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
                                        <span class="text-gray-900 text-sm">{{ $affiliate->name ?? 'Tidak ada nama' }}</span>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 flex items-center">
                                        <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-blue-600 text-sm">{{ $affiliate->email ?? 'Tidak ada email' }}</span>
                                    </div>
                                </div>

                                <!-- Kontak WhatsApp -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kontak WhatsApp</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 flex items-center">
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                        </svg>
                                        <span class="text-green-600 text-sm">{{ $affiliate->phone ?? 'Tidak ada kontak' }}</span>
                                    </div>
                                </div>

                                <!-- Provinsi -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2">
                                        <span class="text-gray-900 text-sm">{{ $affiliate->province ?? 'Tidak ada provinsi' }}</span>
                                    </div>
                                </div>

                                <!-- Kabupaten/Kota -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten/Kota</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2">
                                        <span class="text-gray-900 text-sm">{{ $affiliate->city ?? 'Tidak ada kota' }}</span>
                                    </div>
                                </div>

                                <!-- Detail Alamat -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Detail Alamat Lengkap</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2">
                                        <span class="text-gray-900 text-sm">{{ $affiliate->address ?? 'Tidak ada alamat' }}</span>
                                    </div>
                                </div>

                                <!-- Tanggal Pendaftaran -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pendaftaran</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2">
                                        <span class="text-gray-900 text-sm">{{ $affiliate->created_at->format('d F Y, H:i') }}</span>
                                    </div>
                                </div>

                                <!-- Terakhir Diedit -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Terakhir Diedit</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 flex items-center">
                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-gray-900 text-sm">
                                            @if($affiliate->updated_at && $affiliate->updated_at != $affiliate->created_at)
                                                {{ $affiliate->updated_at->format('d F Y, H:i') }}
                                            @else
                                                Belum pernah diedit
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Media Sosial & Bisnis -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                                Media Sosial & Bisnis
                            </h3>
                            
                            <div class="space-y-3">
                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 flex items-center">
                                        @php
                                            $statusConfig = [
                                                'Aktif' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'text-green-500'],
                                                'Pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'text-yellow-500'],
                                                'Nonaktif' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'text-red-500']
                                            ];
                                            $currentStatus = $affiliate->status ?? 'Pending';
                                            $config = $statusConfig[$currentStatus] ?? $statusConfig['Pending'];
                                        @endphp
                                        <div class="w-2 h-2 rounded-full mr-2 {{ str_replace('text-', 'bg-', $config['icon']) }}"></div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                            {{ $currentStatus }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Akun Instagram -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Akun Instagram</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 flex items-center">
                                        <svg class="w-4 h-4 text-pink-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                        </svg>
                                        @if($affiliate->instagram_account)
                                            <span class="text-pink-600 text-sm">{{ $affiliate->instagram_account }}</span>
                                        @else
                                            <span class="text-gray-500 text-sm italic">Tidak ada akun Instagram</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Akun TikTok -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Akun TikTok</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 flex items-center">
                                        <svg class="w-4 h-4 text-black mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                                        </svg>
                                        @if($affiliate->tiktok_account)
                                            <span class="text-black text-sm">{{ $affiliate->tiktok_account }}</span>
                                        @else
                                            <span class="text-gray-500 text-sm italic">Tidak ada akun TikTok</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Akun Shopee Affiliate -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Akun Shopee Affiliate</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 flex items-center">
                                        <svg class="w-4 h-4 text-orange-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M18.94 4.94A1.5 1.5 0 0017.5 4h-11a1.5 1.5 0 00-1.44.94l-2 6A1.5 1.5 0 004.5 13h15a1.5 1.5 0 001.44-1.94l-2-6zM9 15H7v5h2v-5zm4 0h-2v5h2v-5zm4 0h-2v5h2v-5z"/>
                                        </svg>
                                        @if($affiliate->shopee_account)
                                            <span class="text-orange-600 text-sm">{{ $affiliate->shopee_account }}</span>
                                        @else
                                            <span class="text-gray-500 text-sm italic">Tidak ada akun Shopee</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Source Info -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Sumber Informasi Tentang Kami</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 flex items-center">
                                        <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        @if(!empty($affiliate->source_info))
                                            <span class="text-blue-600 text-sm">{{ $affiliate->source_info }}</span>
                                        @else
                                            <span class="text-gray-500 text-sm italic">Tidak ada informasi sumber</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Profesi -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Profesi</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2">
                                        <span class="text-gray-900 text-sm">{{ $affiliate->profession ?? 'Tidak ada profesi' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($affiliate->notes)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-md px-3 py-2">
                                <span class="text-yellow-800 text-sm">{{ $affiliate->notes }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


@endsection