@extends('layouts.ecommerce')

@section('title', 'Form Pengajuan Produk Affiliate')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 font-nunito mb-2">Form Pengajuan Produk</h1>
            <p class="text-gray-600 font-nunito">Lengkapi data alamat pengiriman untuk produk yang Anda ajukan</p>
        </div>

        <!-- Product Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-800 font-nunito mb-4">Produk yang Diajukan</h2>
            <div class="flex items-center space-x-4">
                <img src="{{ $product->image }}" 
                     alt="{{ $product->name_item }}" 
                     class="w-24 h-24 object-cover rounded-lg shadow-md">
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-900 font-nunito">{{ $product->name_item }}</h3>
                    <p class="text-gray-600 font-nunito">{{ $product->description_item }}</p>
                    <div class="mt-2">
                        <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $product->netweight_item }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form action="{{ route('affiliate.submission.store') }}" method="POST">
                @csrf
                <input type="hidden" name="item_id" value="{{ $product->item_id }}">
                
                <div class="p-6 space-y-6">
                    
                    <!-- Informasi Penerima -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 font-nunito mb-4 pb-2 border-b border-gray-200">
                            Informasi Penerima
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nama Penerima -->
                            <div>
                                <label for="recipient_name" class="block text-sm font-medium text-gray-700 font-nunito mb-2">
                                    Nama Penerima <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="recipient_name" 
                                       name="recipient_name" 
                                       value="{{ old('recipient_name', auth()->user()->name) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent font-nunito @error('recipient_name') border-red-500 @enderror"
                                       required>
                                @error('recipient_name')
                                    <p class="mt-1 text-sm text-red-500 font-nunito">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nomor HP -->
                            <div>
                                <label for="recipient_phone" class="block text-sm font-medium text-gray-700 font-nunito mb-2">
                                    Nomor HP <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" 
                                       id="recipient_phone" 
                                       name="recipient_phone" 
                                       value="{{ old('recipient_phone', auth()->user()->phone) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent font-nunito @error('recipient_phone') border-red-500 @enderror"
                                       placeholder="08xxxxxxxxxx"
                                       required>
                                @error('recipient_phone')
                                    <p class="mt-1 text-sm text-red-500 font-nunito">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Alamat Pengiriman -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 font-nunito mb-4 pb-2 border-b border-gray-200">
                            Alamat Pengiriman
                        </h3>
                        
                        <!-- Alamat Lengkap -->
                        <div class="mb-4">
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700 font-nunito mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea id="shipping_address" 
                                      name="shipping_address" 
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent font-nunito @error('shipping_address') border-red-500 @enderror"
                                      placeholder="Jl. Contoh No. 123, RT/RW, Kelurahan, Kecamatan"
                                      required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                            @error('shipping_address')
                                <p class="mt-1 text-sm text-red-500 font-nunito">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Provinsi -->
                            <div>
                                <label for="province" class="block text-sm font-medium text-gray-700 font-nunito mb-2">
                                    Provinsi <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="province" 
                                       name="province" 
                                       value="{{ old('province', auth()->user()->province) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent font-nunito @error('province') border-red-500 @enderror"
                                       placeholder="Contoh: Jawa Timur"
                                       required>
                                @error('province')
                                    <p class="mt-1 text-sm text-red-500 font-nunito">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kota/Kabupaten -->
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 font-nunito mb-2">
                                    Kota/Kabupaten <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="city" 
                                       name="city" 
                                       value="{{ old('city', auth()->user()->city) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent font-nunito @error('city') border-red-500 @enderror"
                                       placeholder="Contoh: Surabaya"
                                       required>
                                @error('city')
                                    <p class="mt-1 text-sm text-red-500 font-nunito">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Kode Pos -->
                        <div class="mt-4">
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 font-nunito mb-2">
                                Kode Pos
                            </label>
                            <input type="text" 
                                   id="postal_code" 
                                   name="postal_code" 
                                   value="{{ old('postal_code') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent font-nunito @error('postal_code') border-red-500 @enderror"
                                   placeholder="Contoh: 60123"
                                   maxlength="5">
                            @error('postal_code')
                                <p class="mt-1 text-sm text-red-500 font-nunito">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catatan Alamat -->
                        <div class="mt-4">
                            <label for="address_notes" class="block text-sm font-medium text-gray-700 font-nunito mb-2">
                                Catatan Alamat (Opsional)
                            </label>
                            <textarea id="address_notes" 
                                      name="address_notes" 
                                      rows="2"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent font-nunito @error('address_notes') border-red-500 @enderror"
                                      placeholder="Contoh: Dekat masjid, rumah pagar hijau">{{ old('address_notes') }}</textarea>
                            @error('address_notes')
                                <p class="mt-1 text-sm text-red-500 font-nunito">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Important Notice -->
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 text-yellow-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-yellow-800 font-nunito mb-1">Perhatian Penting!</p>
                                <ul class="text-sm text-yellow-700 font-nunito space-y-1 list-disc list-inside">
                                    <li>Pastikan alamat yang Anda isi sudah benar dan lengkap</li>
                                    <li>Setelah barang diterima, Anda wajib mengupload video promosi dalam 14 hari</li>
                                    <li>Keterlambatan upload video akan mengakibatkan akun diblacklist</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Form Actions -->
                <div class="bg-gray-50 px-6 py-4 flex space-x-3">
                    <a href="{{ route('shopping.products') }}" 
                       class="flex-1 bg-gray-200 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-200 font-nunito text-center">
                        Batal
                    </a>
                    <button type="submit" 
                            class="flex-1 bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors duration-200 font-nunito">
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
