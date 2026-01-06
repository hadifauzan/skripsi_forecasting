@extends('layouts.admin.app')

@section('title', isset($faq) ? 'Edit FAQ' : 'Tambah FAQ Baru')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="px-6 py-8">

        {{-- Header Section --}}
        <div class="flex items-center justify-between py-5 font-nunito">
            <div>
                <h2 class="font-nunito font-bold text-2xl text-[#614DAC]">
                    {{ isset($faq) ? 'Edit FAQ' : 'Tambah FAQ Baru' }}
                </h2>
            </div>
            <a href="{{ route('admin.homepage-content.faq') }}"
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
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                    <span class="font-medium">Terdapat kesalahan:</span>
                </div>
                <ul class="ml-8 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Tambah FAQ --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <!-- Header Form -->
            <div class="bg-[#785576] p-6">
                <div class="flex items-center text-white">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <div>
                        <h3 class="text-xl font-semibold">
                            {{ isset($faq) ? 'Form Edit FAQ' : 'Form Tambah FAQ' }}
                        </h3>
                        <p class="text-purple-100 text-sm">
                            {{ isset($faq) ? 'Perbarui informasi FAQ di bawah ini' : 'Isi form di bawah untuk menambahkan FAQ baru' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form
                action="{{ isset($faq) ? route('admin.homepage-content.faq.update', $faq->section) : route('admin.homepage-content.faq.store') }}"
                method="POST" class="p-6">
                @csrf
                @if (isset($faq))
                    @method('PUT')
                @endif
                <input type="hidden" name="type_of_page" value="homepage">

                <div class="grid grid-cols-1 gap-6">
                    <!-- Pertanyaan -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Pertanyaan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $faq->title ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 hover:border-[#785576]/50"
                            placeholder="Contoh: Apakah produk Gentle Baby aman untuk bayi yang baru lahir?" required>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jawaban -->
                    <div>
                        <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                            Jawaban <span class="text-red-500">*</span>
                        </label>
                        <textarea id="body" name="body" rows="6"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 resize-none hover:border-[#785576]/50"
                            placeholder="Contoh: Ya, produk Gentle Baby diformulasikan khusus untuk bayi dari usia 0 bulan. Menggunakan 100% bahan alami yang aman untuk kulit sensitif bayi dan telah teruji klinis oleh dermatologi."
                            required>{{ old('body', $faq->body ?? '') }}</textarea>
                        @error('body')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.homepage-content.faq') }}"
                        class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-[#4D4C4C] rounded-xl font-medium transition-all duration-200 border border-gray-300 hover:border-gray-400">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-[#785576] hover:bg-[#614DAC] text-white rounded-xl font-medium transition-all duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>{{ isset($faq) ? 'Update FAQ' : 'Simpan FAQ' }}</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
