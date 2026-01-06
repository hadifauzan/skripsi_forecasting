@extends('layouts.admin.app')

@section('title', 'Edit Customer')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-[#785576] flex items-center mb-2">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit Customer
                        </h1>
                        <p class="text-gray-600">Edit data customer {{ $customer->name_customer }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.data-customer') }}"
                           class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-[#785576] to-[#5d4359]">
                <h3 class="text-lg font-semibold text-white">Informasi Customer</h3>
            </div>

            <form action="{{ route('admin.customer.update', $customer->customer_id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Informasi Dasar -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                            Informasi Dasar
                        </h3>
                        
                        <div class="space-y-4">
                            <!-- Nama Customer -->
                            <div>
                                <label for="name_customer" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nama Customer <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="name_customer" 
                                       name="name_customer" 
                                       value="{{ old('name_customer', $customer->name_customer) }}"
                                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                       required>
                            </div>

                            <!-- Email Customer -->
                            <div>
                                <label for="email_customer" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email Customer
                                </label>
                                <input type="email" 
                                       id="email_customer" 
                                       name="email_customer" 
                                       value="{{ old('email_customer', $customer->email_customer) }}"
                                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                       placeholder="email@example.com">
                            </div>

                            <!-- No. WhatsApp -->
                            <div>
                                <label for="phone_customer" class="block text-sm font-medium text-gray-700 mb-1">
                                    No. WhatsApp
                                </label>
                                <input type="text" 
                                       id="phone_customer" 
                                       name="phone_customer" 
                                       value="{{ old('phone_customer', $customer->phone_customer) }}"
                                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                       placeholder="08123456789">
                            </div>

                            <!-- Alamat Customer -->
                            <div>
                                <label for="address_customer" class="block text-sm font-medium text-gray-700 mb-1">
                                    Alamat Customer
                                </label>
                                <textarea id="address_customer" 
                                          name="address_customer" 
                                          rows="3"
                                          class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                          placeholder="Masukkan alamat lengkap customer">{{ old('address_customer', $customer->address_customer) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                            Informasi Tambahan
                        </h3>
                        
                        <div class="space-y-4">
                            <!-- Tipe Customer -->
                            <div>
                                <label for="customer_type_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tipe Customer <span class="text-red-500">*</span>
                                </label>
                                <select id="customer_type_id" 
                                        name="customer_type_id" 
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                        required>
                                    <option value="">Pilih Tipe Customer</option>
                                    @foreach($customerTypes as $type)
                                        <option value="{{ $type->customer_type_id }}" 
                                                {{ old('customer_type_id', $customer->customer_type_id) == $type->customer_type_id ? 'selected' : '' }}>
                                            {{ $type->name_customer_type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Poin Customer -->
                            <div>
                                <label for="point" class="block text-sm font-medium text-gray-700 mb-1">
                                    Poin Customer
                                </label>
                                <input type="number" 
                                       id="point" 
                                       name="point" 
                                       value="{{ old('point', $customer->point ?? 0) }}"
                                       min="0"
                                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                       placeholder="0">
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                    Status Customer
                                </label>
                                <select id="status" 
                                        name="status" 
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent">
                                    <option value="Aktif" {{ old('status', $customer->status ?? 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Nonaktif" {{ old('status', $customer->status) == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    <option value="Pending" {{ old('status', $customer->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                            </div>

                            <!-- Informasi Koordinat -->
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">
                                    Latitude
                                </label>
                                <input type="text" 
                                       id="latitude" 
                                       name="latitude" 
                                       value="{{ old('latitude', $customer->latitude) }}"
                                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                       placeholder="-6.200000">
                            </div>

                            <div>
                                <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">
                                    Longitude
                                </label>
                                <input type="text" 
                                       id="longitude" 
                                       name="longitude" 
                                       value="{{ old('longitude', $customer->longitude) }}"
                                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                       placeholder="106.816666">
                            </div>

                            <div>
                                <label for="location_notes" class="block text-sm font-medium text-gray-700 mb-1">
                                    Catatan Lokasi
                                </label>
                                <textarea id="location_notes" 
                                          name="location_notes" 
                                          rows="2"
                                          class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                          placeholder="Catatan khusus tentang lokasi customer">{{ old('location_notes', $customer->location_notes) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.data-customer') }}" 
                       class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-[#785576] text-white rounded-lg hover:bg-[#5d4359] transition-colors duration-200">
                        Update Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection