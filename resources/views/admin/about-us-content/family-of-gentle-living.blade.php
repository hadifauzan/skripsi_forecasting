@extends('layouts.admin.app')

@section('title', 'About Us - Family Management')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="p-6">
        @include('admin.about-us-content.horizontal-navigation')

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Family Section Content --}}
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-[#6B7280] p-6">
                <div class="flex items-center justify-between text-white">
                    <div>
                        <h3 class="text-xl font-semibold">Section Keluarga Gentle Living</h3>
                        <p class="text-gray-100 text-sm">Kelola judul, deskripsi, dan maksimal 10 foto keluarga</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 font-nunito">
                <div class="space-y-6">

                    {{-- Header Section (Title + Body) --}}
                    <div
                        class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div
                            class="w-full px-6 py-4 flex justify-between items-center group transition-all duration-300 hover:bg-white/50">
                            <div class="flex items-center space-x-4 flex-1">
                                <div>
                                    <h4
                                        class="font-semibold text-[#4D4C4C] transition-all duration-300 group-hover:text-purple-600 group-hover:scale-105">
                                        Judul dan Deskripsi
                                    </h4>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 ml-4">
                                <!-- Edit Button -->
                                <button type="button"
                                    onclick="window.location.href='{{ route('admin.about-us-content.edit-family-header') }}'"
                                    class="text-blue-600 hover:text-blue-800 transition-all duration-300 p-2 rounded-full hover:bg-blue-100 hover:rotate-6">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="px-6 pb-6">
                            <div class="bg-white rounded-lg p-4 border-l-4 border-purple-500">
                                <h5 class="font-bold text-gray-800 mb-2">
                                    {{ $familyHeader->title ?? 'Keluarga Gentle Living' }}</h5>
                                <p class="text-gray-700 leading-relaxed">
                                    {{ $familyHeader->body ?? 'Bergabunglah dengan ribuan keluarga Indonesia yang telah mempercayakan nutrisi terbaik untuk si kecil kepada Gentle Living.' }}
                                </p>
                            </div>
                            <div class="flex justify-between items-center mt-3 text-xs text-[#72C7B4]">
                                <span>Diperbarui:
                                    {{ $familyHeader->updated_at ? $familyHeader->updated_at->format('d M Y H:i') : 'Belum diperbarui' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Family Images Grid --}}
                    <div
                        class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div
                            class="w-full px-6 py-4 flex justify-between items-center group transition-all duration-300 hover:bg-white/50">
                            <div class="flex items-center space-x-4 flex-1">
                                <div>
                                    <h4
                                        class="font-semibold text-[#4D4C4C] transition-all duration-300 group-hover:text-purple-600 group-hover:scale-105">
                                        Galeri Foto Keluarga
                                    </h4>
                                    <p class="text-xs text-gray-600 mt-1">
                                        @php
                                            $filledCount = $familyImages->filter(function($img) { return !empty($img->image); })->count();
                                        @endphp
                                        {{ $filledCount }} dari 10 foto
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 ml-4">
                                <!-- Edit Button -->
                                <button type="button"
                                    onclick="window.location.href='{{ route('admin.about-us-content.edit-family-photos') }}'"
                                    class="text-blue-600 hover:text-blue-800 transition-all duration-300 p-2 rounded-full hover:bg-blue-100 hover:rotate-6">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="px-6 pb-6">
                            @if($familyImages->filter(function($img) { return !empty($img->image); })->count() > 0)
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                                    @foreach ($familyImages->filter(function($img) { return !empty($img->image); }) as $image)
                                        <div class="relative group">
                                            <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg overflow-hidden border-2 border-gray-300">
                                                <img src="{{ Storage::url($image->image) }}" alt="{{ $image->title }}"
                                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                            </div>
                                            <div class="mt-2 text-center">
                                                <p class="text-xs text-gray-600 truncate">
                                                    {{ $image->title ?? 'Foto Keluarga' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <!-- Empty State -->
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    <p class="text-gray-500 mb-4">Belum ada foto keluarga</p>
                                    <p class="text-sm text-gray-400">Klik "Kelola Foto" untuk menambah foto</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
