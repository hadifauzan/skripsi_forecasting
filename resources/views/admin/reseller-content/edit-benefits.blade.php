@extends('layouts.admin.app')

@section('title', 'Edit Keuntungan Reseller')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="px-6 py-8">

        {{-- Header Section --}}
        <div class="flex items-center justify-between py-5 font-nunito">
            <h2 class="font-nunito font-bold text-2xl text-[#614DAC]">
                Edit
                {{ $section === 'reseller-what-you-get-title' ? 'Section Keuntungan' : 'Keuntungan ' . str_replace('reseller-get-', '', $section) }}
            </h2>

            <a href="{{ route('admin.reseller-content.section', 'benefits') }}"
                class="bg-[#785576] hover:bg-[#614DAC] text-white px-6 py-3 rounded-lg font-medium flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                <span>Kembali</span>
            </a>
        </div>

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

        @if ($errors->any())
            <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 text-red-800 px-6 py-4 rounded-xl mb-6 shadow-sm"
                role="alert">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                    <span class="font-medium">Terdapat beberapa kesalahan:</span>
                </div>
                <ul class="list-disc pl-8">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Content Type Detection and Form --}}
        @if ($section === 'reseller-what-you-get-title')
            {{-- What You Get Title Form --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">

                <!-- Form Content -->
                <form
                    action="{{ route('admin.reseller-content.section.update', ['sectionKey' => 'benefits', 'section' => $section]) }}"
                    method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Section
                            </label>
                            <input type="text" id="title" name="title"
                                value="{{ old('title', $content->title ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                placeholder="Contoh: What You Get" required>
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Body -->
                        <div>
                            <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Section (Opsional)
                            </label>
                            <textarea id="body" name="body" rows="6"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                placeholder="Masukkan deskripsi mengapa user harus bergabung...">{{ old('body', $content->body ?? '') }}</textarea>
                            @error('body')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.reseller-content.section', 'benefits') }}"
                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-[#4D4C4C] rounded-xl font-medium transition-all duration-200 border border-gray-300 hover:border-gray-400">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-3 bg-[#785576] hover:bg-[#614DAC] text-white rounded-xl font-medium transition-all duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        @elseif (str_contains($section, 'reseller-get-'))
            {{-- Individual Benefits Edit Form --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Header Form -->
                <div class="bg-[#785576] p-6">
                    <div class="flex items-center text-white">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="text-xl font-semibold">Edit Keuntungan
                                {{ str_replace('reseller-get-', '', $section) }}
                            </h3>
                            <p class="text-purple-100 text-sm">Keuntungan bergabung sebagai reseller</p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <form
                    action="{{ route('admin.reseller-content.section.update', ['sectionKey' => 'benefits', 'section' => $section]) }}"
                    method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Keuntungan
                            </label>
                            <input type="text" id="title" name="title"
                                value="{{ old('title', $content->title ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                placeholder="Contoh: Marketing Tools" required>
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Body -->
                        <div>
                            <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Keuntungan (Opsional)
                            </label>
                            <textarea id="body" name="body" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                placeholder="Masukkan deskripsi keuntungan ini...">{{ old('body', $content->body ?? '') }}</textarea>
                            @error('body')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Icon Picker -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Pilih Icon
                            </label>
                            <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-8 gap-3">
                                @php
                                    $icons = [
                                        'dollar' =>
                                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.598 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                                        'gift' =>
                                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>',
                                        'star' =>
                                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>',
                                        'chart' =>
                                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>',
                                        'users' =>
                                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a4 4 0 11-8 0 4 4 0 018 0z"></path>',
                                        'support' =>
                                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>',
                                        'trophy' =>
                                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>',
                                        'lightning' =>
                                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>',
                                    ];
                                    $currentIcon = old('selected_icon', $content->image ?? 'star');
                                @endphp

                                @foreach ($icons as $iconName => $iconPath)
                                    <button type="button" onclick="selectIcon('{{ $iconName }}')"
                                        class="icon-option p-3 border-2 rounded-lg hover:border-purple-500 transition-all duration-200 flex items-center justify-center {{ $currentIcon === $iconName ? 'border-purple-500 bg-purple-50' : 'border-gray-200' }}"
                                        data-icon="{{ $iconName }}">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            {!! $iconPath !!}
                                        </svg>
                                    </button>
                                @endforeach
                            </div>

                            <!-- Hidden input to store selected icon -->
                            <input type="hidden" id="selected_icon" name="selected_icon" value="{{ $currentIcon }}">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.reseller-content.section', 'benefits') }}"
                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-[#4D4C4C] rounded-xl font-medium transition-all duration-200 border border-gray-300 hover:border-gray-400">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-3 bg-[#785576] hover:bg-[#614DAC] text-white rounded-xl font-medium transition-all duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        @else
            {{-- Fallback for other sections --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-6 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Section tidak ditemukan</h3>
                    <p class="text-gray-500 mb-4">Section "{{ $section }}" tidak tersedia untuk diedit di halaman
                        benefits.</p>
                    <a href="{{ route('admin.reseller-content.section', 'benefits') }}"
                        class="inline-flex items-center px-4 py-2 bg-[#785576] text-white rounded-lg hover:bg-[#614DAC] transition-colors">
                        Kembali ke Benefits
                    </a>
                </div>
            </div>
        @endif

    </div>

    <script>
        function selectIcon(iconName) {
            // Remove selection from all icons
            document.querySelectorAll('.icon-option').forEach(option => {
                option.classList.remove('border-purple-500', 'bg-purple-50');
                option.classList.add('border-gray-200');
            });

            // Add selection to clicked icon
            const selectedOption = document.querySelector(`[data-icon="${iconName}"]`);
            selectedOption.classList.remove('border-gray-200');
            selectedOption.classList.add('border-purple-500', 'bg-purple-50');

            // Update hidden input
            document.getElementById('selected_icon').value = iconName;
        }
    </script>

@endsection
