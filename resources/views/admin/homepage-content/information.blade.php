@extends('layouts.admin.app')

@section('title', 'Kelola Informasi')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="px-6 py-8">
        @include('admin.homepage-content.horizontal-navigation')

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 text-green-800 px-6 py-4 rounded-xl mb-6 shadow-sm"
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

        @if (session('error'))
            <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 text-red-800 px-6 py-4 rounded-xl mb-6 shadow-sm"
                role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{-- Preview Tampilan Publik --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-8">
            <!-- Header -->
            <div class="bg-[#785576] p-6">
                <div class="flex items-center text-white">
                    <div>
                        <h3 class="text-xl font-semibold">Preview Tampilan Publik</h3>
                        <p class="text-purple-100 text-sm">Tampilan informasi persis seperti yang akan dilihat pengunjung
                        </p>
                    </div>
                </div>
            </div>

            <!-- Preview Content -->
            <div class="p-8 bg-gray-50">
                <!-- Main Information Section Preview -->
                @if ($mainInformation)
                    <div class="text-center mb-12">
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl text-[#785576] mb-4 font-semibold">
                            {{ $mainInformation->title ?? 'Judul belum diatur' }}
                        </h2>
                        <p class="text-sm sm:text-base lg:text-lg text-[#785576] leading-relaxed max-w-4xl mx-auto">
                            {{ $mainInformation->body ?? 'Deskripsi belum diatur' }}
                        </p>
                        <div class="mt-4 flex justify-center">
                            <a href="{{ route('admin.homepage-content.information.edit', $mainInformation->section) }}"
                                class="bg-[#785576] text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                <span>Edit Informasi Utama</span>
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Grid Cards Preview -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8">

                    <!-- Card 1 -->
                    @if ($info1)
                        <div class="relative">
                            <div class="bg-white rounded-xl shadow-md p-5 flex items-center gap-4">
                                <div class="w-10 h-10 flex items-center justify-center rounded-full bg-[#F8F5F8] shadow-sm">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <p class="text-sm sm:text-base lg:text-lg text-[#785576] font-medium">
                                    {{ $info1->title }}
                                </p>
                            </div>
                            <!-- Edit Button -->
                            <a href="{{ route('admin.homepage-content.information.edit', $info1->section) }}"
                                class="absolute -top-2 -right-2 bg-[#785576] text-white rounded-full p-2 shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    @else
                        <div
                            class="bg-gray-100 rounded-xl shadow-md p-5 flex items-center gap-4 border-2 border-dashed border-gray-300">
                            <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-200">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500">Informasi 1 belum diisi</p>
                        </div>
                    @endif

                    <!-- Card 2 -->
                    @if ($info2)
                        <div class="relative">
                            <div class="bg-white rounded-xl shadow-md p-5 flex items-center gap-4">
                                <div class="w-10 h-10 flex items-center justify-center rounded-full bg-[#F8F5F8] shadow-sm">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <p class="text-sm sm:text-base lg:text-lg text-[#785576] font-medium">
                                    {{ $info2->title }}
                                </p>
                            </div>
                            <!-- Edit Button -->
                            <a href="{{ route('admin.homepage-content.information.edit', $info2->section) }}"
                                class="absolute -top-2 -right-2 bg-[#785576] text-white rounded-full p-2 shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    @else
                        <div
                            class="bg-gray-100 rounded-xl shadow-md p-5 flex items-center gap-4 border-2 border-dashed border-gray-300">
                            <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-200">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500">Informasi 2 belum diisi</p>
                        </div>
                    @endif

                    <!-- Card 3 -->
                    @if ($info3)
                        <div class="relative">
                            <div class="bg-white rounded-xl shadow-md p-5 flex items-center gap-4">
                                <div
                                    class="w-10 h-10 flex items-center justify-center rounded-full bg-[#F8F5F8] shadow-sm">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <p class="text-sm sm:text-base lg:text-lg text-[#785576] font-medium">
                                    {{ $info3->title }}
                                </p>
                            </div>
                            <!-- Edit Button -->
                            <a href="{{ route('admin.homepage-content.information.edit', $info3->section) }}"
                                class="absolute -top-2 -right-2 bg-[#785576] text-white rounded-full p-2 shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    @else
                        <div
                            class="bg-gray-100 rounded-xl shadow-md p-5 flex items-center gap-4 border-2 border-dashed border-gray-300">
                            <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-200">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500">Informasi 3 belum diisi</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

@endsection
