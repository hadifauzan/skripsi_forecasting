@extends('layouts.admin.app')

@section('title', 'Tentang Kami')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="px-6 py-8">
        @include('admin.about-us-content.horizontal-navigation')

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-200 text-green-800 px-6 py-4 rounded-xl mb-6 shadow-sm"
                role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        {{-- Content Display --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="bg-[#785576] p-6">
                <div class="flex items-center text-white">
                    <div>
                        <h3 class="text-xl font-semibold">Section tentang kami</h3>
                        <p class="text-purple-100 text-sm">Modifikasi tentang kami sesuai kebutuhan terkini</p>
                    </div>
                </div>
            </div>

            {{-- About Us About Content --}}
            <div class="p-6 font-nunito">
                <div
                    class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1 mb-6">

                    {{-- Header Card --}}
                    <div
                        class="w-full px-6 py-4 flex justify-between items-center group transition-all duration-300 hover:bg-white/50">
                        <div class="flex items-center space-x-4 flex-1">
                            <div>
                                <h3
                                    class="font-semibold text-[#4D4C4C] transition-all duration-300 group-hover:text-purple-600 group-hover:scale-105">
                                    Section Tentang Kami
                                </h3>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.about-us-content.edit-tentang-kami') }}"
                                class="text-blue-600 hover:text-blue-800 transition-all duration-300 p-2 rounded-full hover:bg-blue-100 hover:rotate-6"
                                title="Edit {{ $content->title }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    {{-- Body Card --}}
                    <div class="px-6 pb-6">
                        <div class="bg-white rounded-lg p-4 border-l-4 border-purple-500">
                            <div class="flex items-center space-x-4">
                                {{-- Gambar --}}
                                @if ($content->image)
                                    <img src="{{ Storage::url($content->image) }}" alt="{{ $content->title }}"
                                        class="h-20 w-20 object-cover rounded-lg border border-gray-300 group-hover:brightness-95 transition duration-300"
                                        onerror="this.src='{{ asset('images/default-placeholder.jpg') }}'">
                                @else
                                    <div
                                        class="h-20 w-20 bg-gray-200 rounded-lg border border-gray-300 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif

                                {{-- Deskripsi --}}
                                <div class="flex-1">
                                    <h4 class="text-gray-700 leading-relaxed font-bold">{{ $content->title }}</h4>
                                    <p class="text-gray-700 leading-relaxed">{{ $content->body }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-3 text-xs text-[#72C7B4]">
                            <span>Diperbarui: {{ $content->updated_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
