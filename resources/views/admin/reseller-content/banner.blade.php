@extends('layouts.admin.app')

@section('title', 'Kelola Banner Reseller')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="p-6">
        @include('admin.reseller-content.horizontal-navigation')

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

        {{-- Reseller Banner Content --}}
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-[#6B7280] p-6">
                <div class="flex items-center text-white">
                    <div>
                        <h3 class="text-xl font-semibold">Banner Reseller</h3>
                        <p class="text-purple-100 text-sm">Kelola gambar banner utama halaman reseller</p>
                    </div>
                </div>
            </div>

            <!-- Banner Content -->
            <div class="p-6 font-nunito">
                {{-- Single Banner Image Section --}}
                <div
                    class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                    <div
                        class="w-full px-6 py-4 flex justify-between items-center group transition-all duration-300 hover:bg-white/50">
                        <div class="flex items-center space-x-4 flex-1">
                            {{-- Thumbnail Gambar --}}
                            <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                @if ($bannerImage && $bannerImage->image)
                                    <img src="{{ Storage::url($bannerImage->image) }}" alt="Banner Reseller"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            {{-- Info --}}
                            <div>
                                <h4
                                    class="font-semibold text-[#4D4C4C] transition-all duration-300 group-hover:text-purple-600">
                                    Banner Utama Reseller
                                </h4>
                                <p class="text-xs text-gray-400 mt-1">
                                    Ukuran disarankan: 1920x1080px (16:9) untuk tampilan optimal
                                </p>
                            </div>
                        </div>

                        {{-- Edit Button --}}
                        <div class="flex items-center space-x-2 ml-4">
                            <a href="{{ route('admin.reseller-content.edit-banner') }}"
                                class="text-blue-600 hover:text-blue-800 transition-all duration-300 p-2 rounded-full hover:bg-blue-100 hover:rotate-6"
                                title="Edit Banner Reseller">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    {{-- Title & Description --}}
                    <div class="px-6 pb-6">
                        <div class="bg-white rounded-lg p-4 border-l-4 border-purple-500">
                            <h5 class="font-bold text-gray-800 mb-2">
                                {{ $bannerImage->title ?? 'Judul belum diatur' }}</h5>
                            <p class="text-gray-700 leading-relaxed">
                                {{ $bannerImage->body ?? 'Deskripsi belum diatur' }}</p>
                        </div>
                        <div class="flex justify-between items-center mt-3 text-xs text-[#72C7B4]">
                            <span>Diperbarui:
                                {{ $bannerImage && $bannerImage->updated_at ? $bannerImage->updated_at->format('d M Y H:i') : 'Belum tersedia' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
