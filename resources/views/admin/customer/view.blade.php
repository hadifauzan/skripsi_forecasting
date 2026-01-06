@extends('layouts.admin.app')

@section('title', 'Detail Customer')

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
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Detail Customer
                        </h1>
                        <p class="text-gray-600">Informasi lengkap customer {{ $customer->name_customer }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.customer.edit', $customer->customer_id) }}"
                           class="inline-flex items-center justify-center px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Customer
                        </a>
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

            <!-- Customer Details Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-[#785576] to-[#5d4359]">
                    <h3 class="text-lg font-semibold text-white">Informasi Customer</h3>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Informasi Dasar -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                                Informasi Dasar
                            </h3>
                            
                            <div class="space-y-3">
                                <!-- Nama Customer -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Customer</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2">
                                        <span class="text-gray-900 text-sm">{{ $customer->clean_name ?? $customer->name_customer ?? '-' }}</span>
                                    </div>
                                </div>

                                <!-- Email Customer -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 flex items-center">
                                        <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        @if($customer->email_customer)
                                            <a href="mailto:{{ $customer->email_customer }}" class="text-blue-600 text-sm hover:text-blue-800">
                                                {{ $customer->email_customer }}
                                            </a>
                                        @else
                                            <span class="text-gray-500 text-sm italic">Tidak ada email</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- No. WhatsApp -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 flex items-center">
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.479 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                        </svg>
                                        @if($customer->phone_customer)
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customer->phone_customer) }}" 
                                               target="_blank" 
                                               class="text-green-600 text-sm hover:text-green-800">
                                                {{ $customer->phone_customer }}
                                            </a>
                                        @else
                                            <span class="text-gray-500 text-sm italic">Tidak ada kontak</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Alamat Customer -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Customer</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2">
                                        <span class="text-gray-900 text-sm">{{ $customer->address_customer ?? 'Tidak ada alamat' }}</span>
                                    </div>
                                </div>

                                <!-- Tanggal Registrasi -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Registrasi</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2">
                                        <span class="text-gray-900 text-sm">{{ $customer->created_at ? $customer->created_at->format('d F Y, H:i') : '-' }}</span>
                                    </div>
                                </div>

                                <!-- Terakhir Diupdate -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Terakhir Diupdate</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2">
                                        <span class="text-gray-900 text-sm">{{ $customer->updated_at ? $customer->updated_at->format('d F Y, H:i') : '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                                Informasi Tambahan
                            </h3>
                            
                            <div class="space-y-3">
                                <!-- Tipe Customer -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Customer</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 flex items-center">
                                        <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $customer->masterCustomerType->name_customer_type ?? 'Regular' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Poin Customer -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Poin Customer</label>
                                    <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 flex items-center">
                                        <svg class="w-4 h-4 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                        <span class="text-purple-600 text-sm font-medium">{{ $customer->point ?? 0 }} poin</span>
                                    </div>
                                </div>

                                <!-- Informasi Koordinat -->
                                @if($customer->latitude || $customer->longitude)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Koordinat Lokasi</label>
                                        <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2">
                                            <div class="text-gray-900 text-sm">
                                                <div>Latitude: {{ $customer->latitude ?? '-' }}</div>
                                                <div>Longitude: {{ $customer->longitude ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($customer->location_notes)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Lokasi</label>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-md px-3 py-2">
                                <span class="text-yellow-800 text-sm">{{ $customer->location_notes }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.data-customer') }}" 
                           class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200">
                            Kembali ke Daftar
                        </a>
                        <a href="{{ route('admin.customer.edit', $customer->customer_id) }}" 
                           class="px-6 py-2 bg-[#785576] text-white rounded-lg hover:bg-[#5d4359] transition-colors duration-200">
                            Edit Customer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection