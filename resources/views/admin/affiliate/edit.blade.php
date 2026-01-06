@extends('layouts.admin.app')

@section('title', 'Edit Affiliator')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <!-- Judul dan Deskripsi -->
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-[#785576] flex items-center mb-2">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Data Affiliator
                        </h1>
                        <p class="text-gray-600">Ubah informasi data affiliator</p>
                    </div>

                    <!-- Tombol Kembali -->
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <a href="{{ route('admin.data-affiliator') }}"
                            class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span class="hidden sm:inline">Kembali</span>
                            <span class="sm:hidden">Back</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Validation Error Messages Only -->
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <strong>Terdapat beberapa kesalahan:</strong>
                    </div>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Edit Form -->
            <div class="bg-white shadow-xl rounded-xl overflow-hidden">
                <!-- Form Header -->
                <div class="bg-[#785576] px-4 py-4 sm:px-6">
                    <h2 class="text-lg sm:text-xl font-semibold text-white">Edit Data Affiliator</h2>
                </div>

                <!-- Form Content -->
                <form action="/admin/affiliate/{{ $affiliate->user_id }}/update" method="POST" class="p-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Informasi Dasar -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                                Informasi Dasar
                            </h3>

                            <!-- Nama Lengkap -->
                            <div class="space-y-2">
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name', $affiliate->name) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 text-sm bg-gray-50"
                                    placeholder="Masukkan nama lengkap"
                                    required>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', $affiliate->email) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 text-sm bg-gray-50"
                                    placeholder="email@example.com"
                                    required>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- WhatsApp -->
                            <div class="space-y-2">
                                <label for="phone" class="block text-sm font-medium text-gray-700">
                                    Kontak WhatsApp <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone', $affiliate->phone) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 text-sm bg-gray-50"
                                    placeholder="08123456789"
                                    required>
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Provinsi -->
                            <div class="space-y-2">
                                <label for="province" class="block text-sm font-medium text-gray-700">
                                    Provinsi <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                    id="province"
                                    name="province"
                                    value="{{ old('province', $affiliate->province) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 text-sm bg-gray-50"
                                    placeholder="Provinsi"
                                    required>
                                @error('province')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kabupaten/Kota -->
                            <div class="space-y-2">
                                <label for="city" class="block text-sm font-medium text-gray-700">
                                    Kabupaten/Kota <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                    id="city"
                                    name="city"
                                    value="{{ old('city', $affiliate->city) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 text-sm bg-gray-50"
                                    placeholder="Kabupaten/Kota"
                                    required>
                                @error('city')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Detail Alamat Lengkap -->
                            <div class="space-y-2">
                                <label for="address" class="block text-sm font-medium text-gray-700">
                                    Detail Alamat Lengkap <span class="text-red-500">*</span>
                                </label>
                                <textarea id="address"
                                        name="address"
                                        rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 text-sm bg-gray-50"
                                        placeholder="Jalan, RT/RW, Kelurahan, Kecamatan"
                                        required>{{ old('address', $affiliate->address) }}</textarea>
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Informasi Tanggal -->
                            <div class="space-y-4">
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

                            <!-- Status -->
                            <div class="space-y-2">
                                <label for="status" class="block text-sm font-medium text-gray-700">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select id="status" 
                                        name="status" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 text-sm bg-gray-50"
                                        required>
                                    <option value="">Pilih Status</option>
                                    <option value="Pending" {{ old('status', $affiliate->status ?? 'Pending') == 'Pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                    <option value="Aktif" {{ old('status', $affiliate->status ?? 'Pending') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Nonaktif" {{ old('status', $affiliate->status ?? 'Pending') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Instagram -->
                            <div class="space-y-2">
                                <label for="instagram_account" class="block text-sm font-medium text-gray-700">
                                    Akun Instagram <span class="text-gray-400">(Opsional)</span>
                                </label>
                                <input type="text" 
                                    id="instagram_account" 
                                    name="instagram_account" 
                                    value="{{ old('instagram_account', $affiliate->instagram_account) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 text-sm bg-gray-50"
                                    placeholder="username_instagram">
                                @error('instagram_account')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- TikTok -->
                            <div class="space-y-2">
                                <label for="tiktok_account" class="block text-sm font-medium text-gray-700">
                                    Akun TikTok <span class="text-gray-400">(Opsional)</span>
                                </label>
                                <input type="text" 
                                    id="tiktok_account" 
                                    name="tiktok_account" 
                                    value="{{ old('tiktok_account', $affiliate->tiktok_account) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 text-sm bg-gray-50"
                                    placeholder="username_tiktok">
                                @error('tiktok_account')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Shopee Affiliate -->
                            <div class="space-y-2">
                                <label for="shopee_account" class="block text-sm font-medium text-gray-700">
                                    Akun Shopee Affiliate <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                    id="shopee_account" 
                                    name="shopee_account" 
                                    value="{{ old('shopee_account', $affiliate->shopee_account) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 text-sm bg-gray-50"
                                    placeholder="username_shopee"
                                    required>
                                @error('shopee_account')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Source Info -->
                            <div class="space-y-2">
                                <label for="source_info" class="block text-sm font-medium text-gray-700">
                                    Sumber Informasi Tentang Kami <span class="text-gray-400">(Opsional)</span>
                                </label>
                                <input type="text" 
                                    id="source_info" 
                                    name="source_info" 
                                    value="{{ old('source_info', $affiliate->source_info) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 text-sm bg-gray-50"
                                    placeholder="Dari mana Anda mengetahui program ini?">
                                @error('source_info')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Profesi -->
                            <div class="space-y-2">
                                <label for="profession" class="block text-sm font-medium text-gray-700">
                                    Profesi <span class="text-red-500">*</span>
                                </label>
                                <textarea id="profession"
                                        name="profession"
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 text-sm bg-gray-50"
                                        placeholder="Profesi atau pekerjaan"
                                        required>{{ old('profession', $affiliate->profession) }}</textarea>
                                @error('profession')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Catatan Admin -->
                            <div class="space-y-2">
                                <label for="notes" class="block text-sm font-medium text-gray-700">
                                    Catatan Admin <span class="text-gray-400">(Opsional)</span>
                                </label>
                                <textarea id="notes" 
                                        name="notes" 
                                        rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 text-sm bg-gray-50"
                                        placeholder="Catatan tambahan untuk affiliator">{{ old('notes', $affiliate->notes) }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-6 flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                        <a href="/admin/data-affiliator" 
                           class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-[#785576] to-[#5d4359] text-white rounded-lg hover:from-[#6a4a67] hover:to-[#4a3a4f] transition-all duration-200 font-medium shadow-lg">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection