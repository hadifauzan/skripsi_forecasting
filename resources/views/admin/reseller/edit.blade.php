@extends('layouts.admin.app')

@section('title', 'Edit Reseller')

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
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Reseller
                        </h1>
                        <p class="text-gray-600">Ubah informasi data reseller</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
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

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

            <!-- Edit Form -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Form Header -->
                <div class="bg-[#785576] px-4 py-4 sm:px-6">
                    <h2 class="text-lg sm:text-xl font-semibold text-white">Edit Data Reseller</h2>
                </div>

                <!-- Form Content -->
                <div class="p-4 sm:p-6 lg:p-8">
                    <form action="{{ route('admin.reseller.update', $reseller->customer_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @php
                            $socialMedia = $reseller->getSocialMediaFromLocationNotes();
                            $salesInfo = $reseller->getSalesInfoFromLocationNotes();
                        @endphp

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Left Column - Basic Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                                    Informasi Dasar
                                </h3>
                                
                                <div class="space-y-3">
                                    <!-- Name -->
                                    <div>
                                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">
                                            Nama Lengkap <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               id="nama_lengkap" 
                                               name="nama_lengkap" 
                                               value="{{ old('nama_lengkap', $reseller->clean_name ?? $reseller->name_customer) }}"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                               required>
                                        @error('nama_lengkap')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $reseller->email_customer) }}"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                               required>
                                        @error('email')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Phone -->
                                    <div>
                                        <label for="kontak_whatsapp" class="block text-sm font-medium text-gray-700 mb-1">
                                            Kontak WhatsApp <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               id="kontak_whatsapp" 
                                               name="kontak_whatsapp" 
                                               value="{{ old('kontak_whatsapp', $reseller->phone_customer) }}"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                               required>
                                        @error('kontak_whatsapp')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Address -->
                                    <div>
                                        <label for="alamat_lengkap" class="block text-sm font-medium text-gray-700 mb-1">
                                            Alamat Lengkap <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="alamat_lengkap" 
                                                  name="alamat_lengkap" 
                                                  rows="3"
                                                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                                  required>{{ old('alamat_lengkap', $reseller->address_customer) }}</textarea>
                                        @error('alamat_lengkap')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column - Social Media & Business -->
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
                                        <label for="akun_instagram" class="block text-sm font-medium text-gray-700 mb-1">
                                            Akun Instagram <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               id="akun_instagram" 
                                               name="akun_instagram" 
                                               value="{{ old('akun_instagram', $socialMedia['akun_instagram'] ?? '') }}"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                               required>
                                        @error('akun_instagram')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- TikTok -->
                                    <div>
                                        <label for="akun_tiktok" class="block text-sm font-medium text-gray-700 mb-1">
                                            Akun TikTok <span class="text-gray-400">(Opsional)</span>
                                        </label>
                                        <input type="text" 
                                               id="akun_tiktok" 
                                               name="akun_tiktok" 
                                               value="{{ old('akun_tiktok', ($socialMedia['akun_tiktok'] ?? '') !== '-' ? ($socialMedia['akun_tiktok'] ?? '') : '') }}"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent">
                                        @error('akun_tiktok')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Sales Platform -->
                                    <div>
                                        <label for="berjualan_melalui" class="block text-sm font-medium text-gray-700 mb-1">
                                            Berjualan Melalui <span class="text-red-500">*</span>
                                        </label>
                                        <select id="berjualan_melalui" 
                                                name="berjualan_melalui" 
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                                required>
                                            <option value="">Pilih platform</option>
                                            <option value="Tokopedia" {{ old('berjualan_melalui', $salesInfo['berjualan_melalui'] ?? '') == 'Tokopedia' ? 'selected' : '' }}>Tokopedia</option>
                                            <option value="Shopee" {{ old('berjualan_melalui', $salesInfo['berjualan_melalui'] ?? '') == 'Shopee' ? 'selected' : '' }}>Shopee</option>
                                            <option value="Lazada" {{ old('berjualan_melalui', $salesInfo['berjualan_melalui'] ?? '') == 'Lazada' ? 'selected' : '' }}>Lazada</option>
                                            <option value="WhatsApp" {{ old('berjualan_melalui', $salesInfo['berjualan_melalui'] ?? '') == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                                            <option value="Offline Store (Babyspa)" {{ old('berjualan_melalui', $salesInfo['berjualan_melalui'] ?? '') == 'Offline Store (Babyspa)' ? 'selected' : '' }}>Offline Store (Babyspa)</option>
                                        </select>
                                        @error('berjualan_melalui')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Status Reseller -->
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                            Status Reseller <span class="text-red-500">*</span>
                                        </label>
                                        <select id="status" 
                                                name="status" 
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                                required>
                                            <option value="">Pilih Status</option>
                                            <option value="Pending" {{ old('status', $reseller->status ?? 'Pending') == 'Pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                            <option value="Aktif" {{ old('status', $reseller->status ?? 'Pending') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="Nonaktif" {{ old('status', $reseller->status ?? 'Pending') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                        </select>
                                        @error('status')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        <p class="text-xs text-gray-500 mt-1">
                                            <span class="text-orange-600">Pending:</span> Menunggu konfirmasi admin |
                                            <span class="text-green-600">Aktif:</span> Reseller sudah dikonfirmasi |
                                            <span class="text-red-600">Nonaktif:</span> Reseller dinonaktifkan
                                        </p>
                                    </div>

                                    <!-- Poin Reseller -->
                                    <div>
                                        <label for="point" class="block text-sm font-medium text-gray-700 mb-1">
                                            Poin Reseller
                                        </label>
                                        <input type="number" 
                                               id="point" 
                                               name="point" 
                                               value="{{ old('point', $reseller->point ?? 0) }}"
                                               min="0"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                               placeholder="0">
                                        @error('point')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        <p class="text-xs text-gray-500 mt-1">Poin yang dimiliki reseller untuk reward/insentif</p>
                                    </div>

                                    <!-- Registration Date (Read Only) -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pendaftaran</label>
                                        <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2">
                                            <span class="text-gray-900 text-sm">{{ $reseller->created_at->format('d F Y, H:i') }}</span>
                                        </div>
                                    </div>

                                    <!-- Last Updated Date (Read Only) -->
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
                        
                        <!-- Form Actions -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('admin.reseller.view', $reseller->customer_id) }}"
                                   class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200">
                                    Batal
                                </a>
                                <button type="submit"
                                        class="px-6 py-2 bg-[#785576] text-white rounded-lg hover:bg-[#5d4359] transition-colors duration-200">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection