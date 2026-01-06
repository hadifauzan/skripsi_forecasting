@extends('layouts.app')

@section('title', 'Formulir Pendaftaran Reseller - Gentle Living')

@section('content')
<!-- Clean White Background -->
<div class="min-h-screen bg-gray-50">
    <div class="relative z-10 py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Simple but Attractive Header -->
            <div class="mb-12">
            </div>
            
            <!-- Simple Form Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <!-- Clean Form Header -->
                <div class="bg-[#785576] border-b border-gray-200 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-[#694966] rounded-lg p-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">Formulir Pendaftaran Reseller</h2>
                                <p class="text-sm text-white">Isi data diri Anda dengan lengkap dan benar</p>
                            </div>
                        </div>
                        <a href="{{ route('reseller') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#785576] transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal
                        </a>
                    </div>
                </div>
                
                <!-- Form Content -->
                <div class="px-6 py-6">
                    <form action="{{ route('reseller.store') }}" method="POST">
                        @csrf

                        <!-- Personal Information Section -->
                        <div class="mb-8 p-6 bg-purple-50 rounded-xl border border-gray-200">
                            <div class="flex items-center mb-6">
                                <div class="bg-[#785576] rounded-lg p-2 mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Informasi Personal</h3>
                                    <p class="text-sm text-gray-600">Data pribadi untuk identifikasi akun Anda</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nama Lengkap -->
                                <div class="md:col-span-2">
                                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="nama_lengkap" 
                                           name="nama_lengkap" 
                                           value="{{ old('nama_lengkap') }}"
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#528B89] focus:border-[#528B89] sm:text-sm @error('nama_lengkap') border-red-300 @enderror"
                                           placeholder="Masukkan nama lengkap Anda"
                                           required>
                                    @error('nama_lengkap')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="md:col-span-2">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="email" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}"
                                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#528B89] focus:border-[#528B89] sm:text-sm @error('email') border-red-300 @enderror"
                                               placeholder="contoh@email.com"
                                               required>
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Alamat Lengkap -->
                                <div class="md:col-span-2">
                                    <label for="alamat_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                                        Alamat Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="alamat_lengkap" 
                                              name="alamat_lengkap" 
                                              rows="4"
                                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#528B89] focus:border-[#528B89] sm:text-sm @error('kontak_whatsapp') border-red-300 @enderror"
                                              placeholder="(Jalan, No, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi)"
                                              required>{{ old('alamat_lengkap') }}</textarea>
                                    @error('alamat_lengkap')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Kontak WhatsApp -->
                                <div class="md:col-span-2">
                                    <label for="kontak_whatsapp" class="block text-sm font-medium text-gray-700 mb-2">
                                        Kontak WhatsApp <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="tel" 
                                               id="kontak_whatsapp" 
                                               name="kontak_whatsapp" 
                                               value="{{ old('kontak_whatsapp') }}"
                                               class="block w-full pl-12 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#528B89] focus:border-[#528B89] sm:text-sm @error('kontak_whatsapp') border-red-300 @enderror"
                                               placeholder="Contoh: 08123456789"
                                               pattern="[0-9]+"
                                               inputmode="numeric"
                                               oninput="validateWhatsAppNumber(this)"
                                               onkeypress="return isNumber(event)"
                                               required>
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.787"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div id="whatsapp-error" class="mt-1 text-sm text-red-600 hidden">
                                        Nomor WhatsApp hanya boleh berisi angka
                                    </div>
                                    @error('kontak_whatsapp')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Password <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="password" 
                                               name="password" 
                                               class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#528B89] focus:border-[#528B89] sm:text-sm @error('password') border-red-300 @enderror"
                                               placeholder="Minimal 8 karakter"
                                               required>
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        </div>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <button type="button" onclick="togglePassword('password')" class="text-gray-400 hover:text-gray-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Konfirmasi Password -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                        Konfirmasi Password <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#528B89] focus:border-[#528B89] sm:text-sm @error('password_confirmation') border-red-300 @enderror"
                                               placeholder="Ulangi password"
                                               required>
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        </div>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <button type="button" onclick="togglePassword('password_confirmation')" class="text-gray-400 hover:text-gray-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    @error('password_confirmation')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Social Media Section -->
                        <div class="mb-8 p-6 bg-purple-50 rounded-xl border border-gray-200">
                            <div class="flex items-center mb-4">
                                <div class="bg-[#785576] rounded-lg p-2 mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V3a1 1 0 011 1v6.5l1.34 1.34a1 1 0 01.16 1.16L18 15H6l-1.5-1.5a1 1 0 01.16-1.16L6 10.5V4a1 1 0 011-1v0"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Media Sosial</h3>
                                    <p class="text-sm text-gray-600">Akun sosial media untuk promosi produk</p>
                                </div>
                            </div>
                            <div class="bg-blue-100 rounded-lg p-3 mb-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-sm text-blue-800">Akun Instagram wajib diisi, Akun TikTok bersifat opsional.</p>
                                </div>
                            </div>
                            
                            <!-- Grid Layout for Social Media -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Akun Instagram -->
                                <div>
                                    <label for="akun_instagram" class="block text-sm font-medium text-gray-700 mb-2">
                                        Akun Instagram <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" 
                                               id="akun_instagram" 
                                               name="akun_instagram" 
                                               value="{{ old('akun_instagram') }}"
                                                class="block w-full pl-12 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#528B89] focus:border-[#528B89] sm:text-sm @error('akun_tiktok') border-red-300 @enderror"
                                               placeholder="Contoh: @username_anda"
                                               required>
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-4">
                                            <svg class="w-5 h-5 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.40s-.644-1.44-1.439-1.40z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('akun_instagram')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Akun TikTok -->
                                <div>
                                    <label for="akun_tiktok" class="block text-sm font-medium text-gray-700 mb-2">
                                        Akun TikTok
                                    </label>
                                    <div class="relative">
                                        <input type="text" 
                                               id="akun_tiktok" 
                                               name="akun_tiktok" 
                                               value="{{ old('akun_tiktok') }}"
                                                class="block w-full pl-12 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#528B89] focus:border-[#528B89] sm:text-sm @error('akun_tiktok') border-red-300 @enderror"
                                               placeholder="Contoh: @username_anda">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-4">
                                            <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M19.321 5.562a5.124 5.124 0 01-.443-.258 6.228 6.228 0 01-1.137-.966c-.849-.891-1.319-1.973-1.319-3.042V1h-2.539v14.21c-.054 1.615-1.399 2.904-3.037 2.904a3.02 3.02 0 01-1.787-.596c-.55-.398-.94-.948-1.123-1.584a2.99 2.99 0 01-.084-.808c0-1.676 1.371-3.037 3.061-3.037.311 0 .611.049.892.138V9.804c-.285-.042-.58-.063-.884-.063-3.31 0-6.002 2.677-6.002 5.963 0 1.625.647 3.099 1.699 4.176a5.987 5.987 0 004.253 1.764c3.31 0 6.002-2.677 6.002-5.964V8.787a8.72 8.72 0 005.119 1.674V7.926c-1.18 0-2.255-.498-3.011-1.299a4.33 4.33 0 01-1.131-1.065z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('akun_tiktok')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Business Information Section -->
                        <div class="mb-8 p-6 bg-purple-50 rounded-xl border border-gray-200">
                            <div class="flex items-center mb-4">
                                <div class="bg-[#785576] rounded-lg p-2 mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0H8m8 0v2a2 2 0 002 2v8a2 2 0 01-2 2H8a2 2 0 01-2-2v-8a2 2 0 012-2V8"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Informasi Bisnis</h3>
                                    <p class="text-sm text-gray-600">Metode penjualan yang akan Anda gunakan</p>
                                </div>
                            </div>
                            
                            <!-- Berjualan Melalui -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Metode Berjualan <span class="text-red-500">*</span>
                                </label>
                                
                                <!-- Dropdown Toggle Button -->
                                <button type="button" 
                                        id="dropdownToggle"
                                        onclick="toggleDropdown()"
                                        class="w-full flex items-center justify-between px-4 py-3 border border-gray-300 rounded-md bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-[#785576] transition-colors duration-200 @error('berjualan_melalui') border-red-300 @enderror">
                                    <span id="selectedCount" class="text-sm text-gray-500">Pilih metode berjualan (dapat memilih lebih dari satu)</span>
                                    <svg id="dropdownIcon" class="w-5 h-5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <!-- Dropdown Content (Hidden by default) -->
                                <div id="dropdownContent" class="hidden mt-2 border border-gray-300 rounded-md p-4 bg-white shadow-lg max-h-96 overflow-y-auto">
                                    
                                    <!-- Marketplace & E-Commerce -->
                                    <div class="mb-4">
                                        <h3 class="font-semibold text-gray-700 mb-2 text-sm">Marketplace & E-Commerce</h3>
                                        <div class="space-y-2 ml-2">
                                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                                <input type="checkbox" 
                                                    name="berjualan_melalui[]" 
                                                    value="TikTok/Tokopedia"
                                                    class="w-4 h-4 text-[#528B89] border-gray-300 rounded focus:ring-[#528B89]"
                                                    {{ in_array('TikTok/Tokopedia', old('berjualan_melalui', [])) ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">TikTok/Tokopedia</span>
                                            </label>
                                            
                                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                                <input type="checkbox" 
                                                    name="berjualan_melalui[]" 
                                                    value="Shopee"
                                                    class="w-4 h-4 text-[#528B89] border-gray-300 rounded focus:ring-[#528B89]"
                                                    {{ in_array('Shopee', old('berjualan_melalui', [])) ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">Shopee</span>
                                            </label>
                                            
                                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                                <input type="checkbox" 
                                                    name="berjualan_melalui[]" 
                                                    value="Lazada"
                                                    class="w-4 h-4 text-[#528B89] border-gray-300 rounded focus:ring-[#528B89]"
                                                    {{ in_array('Lazada', old('berjualan_melalui', [])) ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">Lazada</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Social Media -->
                                    <div class="mb-4">
                                        <h3 class="font-semibold text-gray-700 mb-2 text-sm">Social Media</h3>
                                        <div class="space-y-2 ml-2">
                                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                                <input type="checkbox" 
                                                    name="berjualan_melalui[]" 
                                                    value="WhatsApp"
                                                    class="w-4 h-4 text-[#528B89] border-gray-300 rounded focus:ring-[#528B89]"
                                                    {{ in_array('WhatsApp', old('berjualan_melalui', [])) ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">WhatsApp</span>
                                            </label>
                                            
                                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                                <input type="checkbox" 
                                                    name="berjualan_melalui[]" 
                                                    value="Facebook"
                                                    class="w-4 h-4 text-[#528B89] border-gray-300 rounded focus:ring-[#528B89]"
                                                    {{ in_array('Facebook', old('berjualan_melalui', [])) ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">Facebook</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Offline Store -->
                                    <div class="mb-4">
                                        <h3 class="font-semibold text-gray-700 mb-2 text-sm">Offline Store</h3>
                                        <div class="space-y-2 ml-2">
                                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                                <input type="checkbox" 
                                                    name="berjualan_melalui[]" 
                                                    value="Offline Store/Homecare (Babyspa)"
                                                    class="w-4 h-4 text-[#528B89] border-gray-300 rounded focus:ring-[#528B89]"
                                                    {{ in_array('Offline Store/Homecare (Babyspa)', old('berjualan_melalui', [])) ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">Offline Store/Homecare (Babyspa)</span>
                                            </label>
                                            
                                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                                <input type="checkbox" 
                                                    name="berjualan_melalui[]" 
                                                    value="Offline Store (Babyshop)"
                                                    class="w-4 h-4 text-[#528B89] border-gray-300 rounded focus:ring-[#528B89]"
                                                    {{ in_array('Offline Store (Babyshop)', old('berjualan_melalui', [])) ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">Offline Store (Babyshop)</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Lainnya -->
                                    <div>
                                        <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                            <input type="checkbox" 
                                                name="berjualan_melalui[]" 
                                                value="Lainnya"
                                                class="w-4 h-4 text-[#528B89] border-gray-300 rounded focus:ring-[#528B89]"
                                                {{ in_array('Lainnya', old('berjualan_melalui', [])) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-700 font-medium">Lainnya</span>
                                        </label>
                                    </div>
                                    
                                </div>
                                <!-- End Dropdown Content -->

                                <!-- Hidden input for validation -->
                                <input type="hidden" id="berjualan_validation" name="berjualan_validation" required>
                                
                                @error('berjualan_melalui')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Custom Sales Platform Input (Hidden by default) -->
                            <div id="customSalesPlatformDiv" class="hidden">
                                <label for="custom_sales_platform" class="block text-sm font-medium text-gray-700 mb-2">
                                    Sebutkan Metode Berjualan Lainnya <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                           id="custom_sales_platform" 
                                           name="custom_sales_platform" 
                                           value="{{ old('custom_sales_platform') }}"
                                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#528B89] focus:border-[#528B89] sm:text-sm @error('custom_sales_platform') border-red-300 @enderror"
                                           placeholder="Contoh: Facebook Marketplace, Instagram Shop, dll"
                                           maxlength="100">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Maksimal 100 karakter</p>
                                @error('custom_sales_platform')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Agreement Section -->
                        <div class="mb-8 p-6 bg-purple-50 rounded-xl border border-gray-200">
                            <div class="flex items-center mb-4">
                                <div class="bg-[#785576] rounded-lg p-2 mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Persetujuan</h3>
                                    <p class="text-sm text-gray-600">Syarat dan ketentuan menjadi reseller</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-start">
                                        <input type="checkbox" 
                                               id="persetujuan" 
                                               name="persetujuan"
                                               value="1"
                                               class="mt-1 h-4 w-4 text-[#785576] focus:ring-[#785576] border-gray-300 rounded"
                                               required>
                                        <label for="persetujuan" class="ml-3 text-sm text-gray-700">
                                            Saya menyetujui <button type="button" onclick="showTermsModal()" class="font-bold text-[#785576] hover:text-[#6a4a68] underline cursor-pointer">syarat dan ketentuan</button> menjadi reseller Gentle Living dan bersedia mengikuti semua aturan yang berlaku.
                                        </label>
                                    </div>
                                </div>
                                @error('persetujuan')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('reseller') }}" 
                               class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors duration-200 font-medium">
                                Batal
                            </a>
                            <button type="submit" 
                                    id="submitBtn"
                                    class="px-8 py-3 bg-[#785576] text-white rounded-xl hover:bg-[#694966] transition-colors duration-200 font-medium shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled>
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Daftar Sebagai Reseller
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div id="termsModal" 
     class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden">        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-[#785576] to-[#6a4a68] px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white">Syarat dan Ketentuan Reseller</h3>
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
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
            <div class="prose prose-sm max-w-none">
                <div class="bg-gray-50 rounded-lg p-4 whitespace-pre-line text-gray-700">{{ $termsContent }}</div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex justify-end">
                <button type="button" onclick="closeTermsModal()"
                    class="px-6 py-2 bg-[#785576] text-white rounded-lg hover:bg-[#6a4a68] transition-colors duration-200 font-medium">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('persetujuan');
    const submitBtn = document.getElementById('submitBtn');
    
    function toggleSubmitButton() {
        if (checkbox.checked) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
    
    checkbox.addEventListener('change', toggleSubmitButton);
    toggleSubmitButton(); // Initial check
    
    // Initialize selection on page load
    updateSelection();
    
    // Add event listeners to all checkboxes
    const salesCheckboxes = document.querySelectorAll('input[name="berjualan_melalui[]"]');
    salesCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelection);
    });
});

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
    field.setAttribute('type', type);
}

// Toggle dropdown visibility
function toggleDropdown() {
    const dropdown = document.getElementById('dropdownContent');
    const icon = document.getElementById('dropdownIcon');
    
    dropdown.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('dropdownContent');
    const toggleBtn = document.getElementById('dropdownToggle');
    
    if (dropdown && toggleBtn && !dropdown.contains(event.target) && !toggleBtn.contains(event.target)) {
        dropdown.classList.add('hidden');
        document.getElementById('dropdownIcon').classList.remove('rotate-180');
    }
});

// Update selected count and toggle custom input
function updateSelection() {
    const checkboxes = document.querySelectorAll('input[name="berjualan_melalui[]"]');
    const selectedCount = document.getElementById('selectedCount');
    const validationInput = document.getElementById('berjualan_validation');
    const customDiv = document.getElementById('customSalesPlatformDiv');
    const customInput = document.getElementById('custom_sales_platform');
    const lainnyaCheckbox = document.querySelector('input[name="berjualan_melalui[]"][value="Lainnya"]');
    
    let selected = [];
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            selected.push(checkbox.value);
        }
    });
    
    // Update display text
    if (selected.length === 0) {
        selectedCount.textContent = 'Pilih metode berjualan (dapat memilih lebih dari satu)';
        selectedCount.classList.add('text-gray-500');
        selectedCount.classList.remove('text-gray-900');
        validationInput.value = '';
    } else if (selected.length === 1) {
        selectedCount.textContent = selected[0];
        selectedCount.classList.remove('text-gray-500');
        selectedCount.classList.add('text-gray-900');
        validationInput.value = 'valid';
    } else {
        selectedCount.textContent = `${selected.length} metode dipilih`;
        selectedCount.classList.remove('text-gray-500');
        selectedCount.classList.add('text-gray-900');
        validationInput.value = 'valid';
    }
    
    // Toggle custom input for "Lainnya"
    if (lainnyaCheckbox && lainnyaCheckbox.checked) {
        customDiv.classList.remove('hidden');
        customInput.setAttribute('required', 'required');
    } else {
        customDiv.classList.add('hidden');
        customInput.removeAttribute('required');
        customInput.value = '';
    }
}

// Toggle custom sales platform input field (legacy support)
function toggleCustomSalesPlatform() {
    updateSelection();
}

// WhatsApp number validation functions
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    // Allow backspace, delete, tab, escape, enter
    if (charCode == 46 || charCode == 8 || charCode == 9 || charCode == 27 || charCode == 13 ||
        // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
        (charCode == 65 && evt.ctrlKey === true) ||
        (charCode == 67 && evt.ctrlKey === true) ||
        (charCode == 86 && evt.ctrlKey === true) ||
        (charCode == 88 && evt.ctrlKey === true)) {
        return true;
    }
    // Ensure that it is a number and stop the keypress
    if (charCode < 48 || charCode > 57) {
        return false;
    }
    return true;
}

function validateWhatsAppNumber(input) {
    const value = input.value;
    const errorDiv = document.getElementById('whatsapp-error');
    
    // Remove non-numeric characters
    const numericValue = value.replace(/[^0-9]/g, '');
    
    // Update input value with only numbers
    if (value !== numericValue) {
        input.value = numericValue;
        errorDiv.classList.remove('hidden');
        input.classList.add('border-red-300');
        input.classList.remove('border-gray-300');
        
        // Hide error after 3 seconds
        setTimeout(() => {
            errorDiv.classList.add('hidden');
            input.classList.remove('border-red-300');
            input.classList.add('border-gray-300');
        }, 3000);
    }
    
    // Additional validation for length (Indonesian phone numbers typically 10-13 digits)
    if (numericValue.length > 0 && (numericValue.length < 10 || numericValue.length > 13)) {
        input.setCustomValidity('Nomor WhatsApp harus antara 10-13 digit');
    } else {
        input.setCustomValidity('');
    }
}

// Form submission validation
document.getElementById('kontak_whatsapp').addEventListener('blur', function() {
    const value = this.value;
    const errorDiv = document.getElementById('whatsapp-error');
    
    if (value && !/^[0-9]+$/.test(value)) {
        errorDiv.textContent = 'Nomor WhatsApp hanya boleh berisi angka';
        errorDiv.classList.remove('hidden');
        this.classList.add('border-red-300');
    } else if (value && (value.length < 10 || value.length > 13)) {
        errorDiv.textContent = 'Nomor WhatsApp harus antara 10-13 digit';
        errorDiv.classList.remove('hidden');
        this.classList.add('border-red-300');
    } else {
        errorDiv.classList.add('hidden');
        this.classList.remove('border-red-300');
        this.classList.add('border-gray-300');
    }
});

// Terms and Conditions Modal Functions
function showTermsModal() {
    const modal = document.getElementById('termsModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeTermsModal() {
    const modal = document.getElementById('termsModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('termsModal');
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeTermsModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!modal.classList.contains('hidden')) {
                closeTermsModal();
            }
        }
    });
});
</script>

@if (session('success'))
<script>
    alert('{{ session('success') }}');
</script>
@endif

@if ($errors->any())
<script>
    let errorMessage = 'Terdapat kesalahan dalam pengisian form:\n\n';
    @foreach ($errors->all() as $error)
        errorMessage += '• {{ $error }}\n';
    @endforeach
    alert(errorMessage);
</script>
@endif
@endsection