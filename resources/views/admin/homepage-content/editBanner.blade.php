@extends('layouts.admin.app')

@section('title', 'Edit Banner')

@push('head')
    <meta
        name="csrf-tok                        <!-- Body Banner -->
                        <div>
                            <label for="body"
        class="block text-sm font-medium text-gray-700 mb-2">
    Deskripsi Banner
    </label>
    <textarea id="body" name="body" rows="4"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 resize-none"
        placeholder="Masukkan deskripsi banner (opsional)">{{ old('body', $banner->body ?? '') }}</textarea>ent="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="p-6 font-nunito">

        {{-- Header Section --}}
        <div class="flex items-center justify-between py-5 font-nunito">

            <h2 class="font-nunito font-bold text-2xl text-[#614DAC]">
                Edit Section Banner</h2>

            <a href="{{ route('admin.homepage-content.banner') }}"
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
            <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 text-green-700 px-6 py-4 rounded-xl mb-6 transition-all duration-300 hover:shadow-lg"
                role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 text-red-700 px-6 py-4 rounded-xl mb-6 transition-all duration-300 hover:shadow-lg"
                role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                    <span class="block sm:inline font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 text-red-700 px-6 py-4 rounded-xl mb-6 transition-all duration-300 hover:shadow-lg"
                role="alert">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="font-medium">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Form Edit Banner --}}
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <!-- Header Form -->
            <div class="bg-[#785576] p-6">
                <div class="flex items-center text-white">
                    <div>
                        <h3 class="text-xl font-semibold">Form Edit Banner</h3>
                        <p class="text-purple-100 text-sm">Perbarui judul & deskripsi banner terkini</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form action="{{ route('admin.homepage-content.banner.update', $banner->section) }}" method="POST"
                class="p-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="type_of_page" value="homepage">

                <div class="grid grid-cols-1 gap-6">
                    @if ($banner->section == 'banner')
                        {{-- Banner Utama Fields --}}
                        <!-- Judul Banner -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Banner
                            </label>
                            <input type="text" id="title" name="title"
                                value="{{ old('title', $banner->title ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Masukkan judul banner (opsional)">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi Banner -->
                        <div>
                            <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Banner
                            </label>
                            <textarea id="body" name="body" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 resize-none"
                                placeholder="Masukkan deskripsi banner (opsional)">{{ old('body', $banner->body ?? '') }}</textarea>
                            @error('body')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @elseif($banner->section == 'banner-product')
                        {{-- Product Banner Fields --}}
                        <!-- Nama Produk -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Produk
                            </label>
                            <input type="text" id="title" name="title"
                                value="{{ old('title', $banner->title ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Masukkan nama produk (opsional)">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gambar Produk -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-4">
                                Gambar Produk
                            </label>

                            {{-- Side by Side Layout --}}
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                                {{-- Current Image Column --}}
                                <div class="space-y-4">
                                    @if ($banner->image)
                                        <div>
                                            <h4 class="text-sm font-medium text-[#4D4C4C] mb-3 flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Gambar Saat Ini
                                            </h4>
                                            <div
                                                class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl border border-green-200 p-6 transition-all duration-300 hover:shadow-lg h-[280px] flex flex-col justify-center">
                                                <div class="text-center">
                                                    <div class="relative inline-block mb-3">
                                                        <img src="{{ asset('storage/images/homepage/' . $banner->image) }}"
                                                            alt="Current {{ $banner->title }}"
                                                            class="h-32 w-32 object-cover rounded-xl border-2 border-green-300 transition-all duration-300 hover:scale-105 shadow-lg"
                                                            onerror="this.src='{{ asset('images/default-placeholder.jpg') }}'">
                                                        <div
                                                            class="absolute -top-2 -right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full font-medium shadow-lg">
                                                            Aktif
                                                        </div>
                                                    </div>
                                                    <div class="space-y-1">
                                                        <p class="text-sm font-medium text-[#4D4C4C]">{{ $banner->title }}
                                                        </p>
                                                        <p class="text-xs text-green-600 font-medium">{{ $banner->image }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">Gambar ini sedang digunakan di
                                                            banner produk</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-400 mb-3 flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                Gambar Saat Ini
                                            </h4>
                                            <div
                                                class="bg-gray-50 rounded-xl border-2 border-dashed border-gray-200 p-6 h-[280px] flex flex-col justify-center">
                                                <div class="text-center">
                                                    <div
                                                        class="h-32 w-32 mx-auto bg-gray-200 rounded-xl flex items-center justify-center mb-3">
                                                        <svg class="w-12 h-12 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <p class="text-sm text-gray-500">Belum ada gambar</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Upload/Preview New Image Column --}}
                                <div class="space-y-4">
                                    <h4 class="text-sm font-medium text-[#4D4C4C] flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                        {{ $banner->image ? 'Ganti Gambar (Opsional)' : 'Upload Gambar Baru' }}
                                    </h4>

                                    {{-- Upload Area --}}
                                    <div id="uploadArea">
                                        <div class="flex items-center justify-center w-full">
                                            <label for="image"
                                                class="flex flex-col items-center justify-center w-full h-[280px] border-2 border-blue-300 border-dashed rounded-xl cursor-pointer bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 transition-all duration-300 hover:shadow-lg">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div
                                                        class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mb-3 transition-all duration-300 hover:scale-110">
                                                        <svg class="w-6 h-6 text-white" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <p class="mb-2 text-sm text-[#4D4C4C] font-medium text-center">
                                                        <span class="font-semibold">Klik untuk upload</span><br>
                                                        gambar produk {{ $banner->image ? 'baru' : '' }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">PNG, JPG, GIF</p>
                                                </div>
                                                <input id="image" name="image" type="file" class="hidden"
                                                    accept="image/*" onchange="previewNewImage(this)" />
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Preview New Image --}}
                                    <div id="newImagePreview" class="hidden">
                                        <div
                                            class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border border-blue-200 p-6 transition-all duration-300 hover:shadow-lg h-[280px] flex flex-col justify-center">
                                            <div class="text-center">
                                                <div class="relative inline-block mb-3">
                                                    <img id="newPreviewImg" src="#" alt="Preview gambar baru"
                                                        class="h-32 w-32 object-cover rounded-xl border-2 border-blue-300 shadow-lg">
                                                    <div
                                                        class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full font-medium shadow-lg">
                                                        Baru
                                                    </div>
                                                </div>
                                                <div class="space-y-1 mb-3">
                                                    <p class="text-sm font-medium text-[#4D4C4C]" id="newImageTitle">
                                                        Gambar Baru</p>
                                                    <p class="text-xs text-blue-600 font-medium" id="newImageName">
                                                        nama_file.jpg</p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $banner->image ? 'Gambar ini akan menggantikan gambar saat ini' : 'Gambar ini akan menjadi gambar produk' }}
                                                    </p>
                                                </div>
                                                <button type="button" onclick="removeNewImage()"
                                                    class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-600 hover:text-red-700 rounded-md text-xs font-medium transition-all duration-200 flex items-center justify-center mx-auto">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                    Hapus & Ganti
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @error('image')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keunggulan Produk (4 Point) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-4">
                                Keunggulan Produk (4 Point)
                            </label>

                            @php
                                $existingPoints = ['100% alami', 'BPOM Certified', 'Newborn Friendly', 'Teruji Klinis'];
                                if ($banner && $banner->body) {
                                    $bodyData = json_decode($banner->body, true);
                                    if (isset($bodyData['points']) && is_array($bodyData['points'])) {
                                        $existingPoints = array_pad($bodyData['points'], 4, '');
                                    }
                                }
                            @endphp

                            <div class="space-y-4">
                                @for ($i = 1; $i <= 4; $i++)
                                    <div
                                        class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200 p-4 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                                        <div class="flex items-center group">
                                            <div
                                                class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center mr-4 transition-all duration-300 group-hover:scale-110 shadow-lg">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <input type="text" name="point_{{ $i }}"
                                                value="{{ old('point_' . $i, $existingPoints[$i - 1] ?? '') }}"
                                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 bg-white/80 hover:bg-white"
                                                placeholder="Masukkan keunggulan produk {{ $i }} (opsional)...">
                                        </div>
                                        @error('point_' . $i)
                                            <p class="mt-2 text-sm text-red-600 ml-12">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endfor
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-purple-100">
                    <a href="{{ route('admin.homepage-content.banner') }}"
                        class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl font-medium transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border border-gray-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-8 py-3 bg-[#785576] hover:bg-[#614DAC] text-white rounded-xl font-medium transition-all duration-300 flex items-center space-x-3 shadow-lg hover:shadow-xl hover:-translate-y-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

    <script>
        // Function to preview new image when selected
        function previewNewImage(input) {
            const file = input.files[0];
            const previewContainer = document.getElementById('newImagePreview');
            const uploadArea = document.getElementById('uploadArea');
            const previewImg = document.getElementById('newPreviewImg');
            const imageNameElement = document.getElementById('newImageName');
            const imageTitleElement = document.getElementById('newImageTitle');

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imageNameElement.textContent = file.name;
                    imageTitleElement.textContent = 'Gambar Baru';

                    // Hide upload area with animation
                    uploadArea.style.transition = 'all 0.3s ease-in';
                    uploadArea.style.opacity = '0';
                    uploadArea.style.transform = 'translateY(-20px)';

                    setTimeout(() => {
                        uploadArea.classList.add('hidden');

                        // Show preview with animation
                        previewContainer.classList.remove('hidden');
                        previewContainer.style.opacity = '0';
                        previewContainer.style.transform = 'translateY(20px)';

                        setTimeout(() => {
                            previewContainer.style.transition = 'all 0.3s ease-out';
                            previewContainer.style.opacity = '1';
                            previewContainer.style.transform = 'translateY(0)';
                        }, 10);
                    }, 300);
                };

                reader.readAsDataURL(file);
            } else {
                // If no file, ensure upload area is visible and preview is hidden
                uploadArea.classList.remove('hidden');
                previewContainer.classList.add('hidden');
            }
        }

        // Function to remove/reset new image preview
        function removeNewImage() {
            const input = document.getElementById('image');
            const previewContainer = document.getElementById('newImagePreview');
            const uploadArea = document.getElementById('uploadArea');

            // Clear the file input
            input.value = '';

            // Hide preview with animation
            previewContainer.style.transition = 'all 0.3s ease-in';
            previewContainer.style.opacity = '0';
            previewContainer.style.transform = 'translateY(-20px)';

            setTimeout(() => {
                previewContainer.classList.add('hidden');

                // Show upload area again with animation
                uploadArea.classList.remove('hidden');
                uploadArea.style.opacity = '0';
                uploadArea.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    uploadArea.style.transition = 'all 0.3s ease-out';
                    uploadArea.style.opacity = '1';
                    uploadArea.style.transform = 'translateY(0)';
                }, 10);
            }, 300);
        }

        // Legacy function support for backward compatibility 
        function previewImage(input) {
            previewNewImage(input);
        }
    </script>
@endsection
