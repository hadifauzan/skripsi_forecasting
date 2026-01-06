@extends('layouts.admin.app')

@section('title', 'Edit Banner affiliate')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="px-6 py-8">

        {{-- Header Section --}}
        <div class="flex items-center justify-between py-5 font-nunito">
            <h2 class="font-nunito font-bold text-2xl text-[#614DAC]">
                Edit Section Banner
            </h2>

            <a href="{{ route('admin.affiliate-content.banner') }}"
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

        {{-- Content Type Detection and Form --}}
        @if ($section === 'hero-title')
            {{-- Hero Title Form (Title + Body, No Image) --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
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
                <form action="{{ route('admin.affiliate-content.update', $section) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Hero <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title"
                                value="{{ old('title', $content->title ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 hover:border-[#785576]/50"
                                placeholder="Masukkan judul hero..." required>
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Body -->
                        <div>
                            <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Hero <span class="text-red-500">*</span>
                            </label>
                            <textarea id="body" name="body" rows="6"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 resize-none hover:border-[#785576]/50"
                                placeholder="Masukkan deskripsi hero..." required>{{ old('body', $content->body ?? '') }}</textarea>
                            @error('body')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.affiliate-content.banner') }}"
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
        @elseif (in_array($section, ['carousel-1', 'carousel-2', 'carousel-3']))
            {{-- Carousel Content Form (Image + Description) --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Header Form -->
                <div class="bg-[#785576] p-6">
                    <div class="flex items-center text-white">
                        <div>
                            <h3 class="text-xl font-semibold">Edit {{ str_replace('carousel-', 'Gambar ', $section) }}
                            </h3>
                            <p class="text-purple-100 text-sm">Gambar dan deskripsi banner
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <form action="{{ route('admin.affiliate-content.update', $section) }}" method="POST"
                    enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left Column: Form Fields -->
                        <div class="space-y-6">
                            <!-- Product Name -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Produk <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="title" name="title"
                                    value="{{ old('title', $content->title ?? '') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 hover:border-[#785576]/50"
                                    placeholder="Contoh: Mamina ASI Booster" required>
                                @error('title')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Product Description -->
                            <div>
                                <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                                    Deskripsi Produk <span class="text-red-500">*</span>
                                </label>
                                <textarea id="body" name="body" rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 resize-none hover:border-[#785576]/50"
                                    placeholder="Deskripsi ini akan muncul sebagai teks putih di atas gambar..." required>{{ old('body', $content->body ?? '') }}</textarea>
                                @error('body')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Image Upload -->
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                    Gambar Banner <span class="text-red-500">*</span>
                                </label>

                                {{-- Upload Area --}}
                                <div id="uploadArea" class="{{ $content && $content->image ? '' : '' }}">
                                    <label for="image" class="cursor-pointer">
                                        <div
                                            class="w-full px-4 py-8 border-2 border-dashed border-gray-300 rounded-lg hover:border-[#785576] transition-all duration-200 text-center bg-gray-50 hover:bg-gray-100">
                                            <div class="space-y-2">
                                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                <p class="text-sm text-gray-600 font-medium">
                                                    <span class="font-semibold">Klik untuk upload</span><br>
                                                    gambar carousel {{ $content && $content->image ? 'baru' : '' }}
                                                </p>
                                                <p class="text-xs text-gray-500">PNG, JPG, GIF</p>
                                            </div>
                                        </div>
                                        <input type="file" id="image" name="image" accept="image/*"
                                            class="hidden" onchange="previewNewImage(this)">
                                    </label>
                                </div>

                                {{-- Preview New Image --}}
                                <div id="newImagePreview" class="hidden mt-4">
                                    <div
                                        class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border border-blue-200 p-4 transition-all duration-300 hover:shadow-lg">
                                        <div class="flex items-center space-x-4">
                                            <div class="relative">
                                                <img id="newPreviewImg" src="#" alt="Preview gambar baru"
                                                    class="h-20 w-20 object-cover rounded-lg border-2 border-blue-300 shadow-lg">
                                                <div
                                                    class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full font-medium shadow-lg">
                                                    Baru
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-800" id="newImageTitle">Gambar
                                                    Baru</p>
                                                <p class="text-xs text-blue-600 font-medium" id="newImageName">
                                                    nama_file.jpg</p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ $content && $content->image ? 'Gambar ini akan menggantikan gambar saat ini' : 'Gambar ini akan menjadi gambar carousel' }}
                                                </p>
                                            </div>
                                            <button type="button" onclick="removeNewImage()"
                                                class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-600 hover:text-red-700 rounded-md text-xs font-medium transition-all duration-200 flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <p class="mt-2 text-sm text-gray-500">Format: JPG, PNG. Ukuran ideal:
                                    1200x600px</p>
                                @error('image')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column: Preview -->
                        <div class="space-y-6">
                            {{-- Current Image Preview --}}
                            @if ($content && $content->image)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini</h4>
                                    <div
                                        class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200 p-4 transition-all duration-300 hover:shadow-lg">
                                        <div class="relative rounded-lg overflow-hidden shadow-md mb-3">
                                            <img src="{{ Storage::url($content->image) }}" alt="{{ $content->title }}"
                                                class="w-full h-48 object-cover">
                                            <!-- Preview overlay text -->
                                            <div
                                                class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black/50 flex items-end">
                                                <div class="p-4 text-white w-full">
                                                    <h5 class="font-bold text-lg">{{ $content->title }}</h5>
                                                    <p class="text-sm text-gray-200">{{ $content->body }}</p>
                                                </div>
                                            </div>
                                            <div
                                                class="absolute top-2 right-2 bg-gray-800/70 text-white text-xs px-2 py-1 rounded-full font-medium">
                                                Aktif
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-gray-600">Gambar yang sedang digunakan</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Preview</h4>
                                    <div
                                        class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                                        <div class="text-center">
                                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <p class="text-gray-500 text-sm">Belum ada gambar</p>
                                            <p class="text-gray-400 text-xs">Upload gambar untuk melihat preview</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.affiliate-content.banner') }}"
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
                    <p class="text-gray-500 mb-4">Section "{{ $section }}" tidak tersedia untuk diedit atau sudah
                        dipindahkan ke halaman yang sesuai.</p>
                    <a href="{{ route('admin.affiliate-content.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-[#785576] text-white rounded-lg hover:bg-[#614DAC] transition-colors">
                        Kembali ke Kelola affiliate Content
                    </a>
                </div>
            </div>
        @endif

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
                    uploadArea.style.transform = 'translateY(-10px)';

                    setTimeout(() => {
                        uploadArea.classList.add('hidden');

                        // Show preview with animation
                        previewContainer.classList.remove('hidden');
                        previewContainer.style.opacity = '0';
                        previewContainer.style.transform = 'translateY(10px)';

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
            previewContainer.style.transform = 'translateY(-10px)';

            setTimeout(() => {
                previewContainer.classList.add('hidden');

                // Show upload area with animation
                uploadArea.classList.remove('hidden');
                uploadArea.style.opacity = '0';
                uploadArea.style.transform = 'translateY(10px)';

                setTimeout(() => {
                    uploadArea.style.transition = 'all 0.3s ease-out';
                    uploadArea.style.opacity = '1';
                    uploadArea.style.transform = 'translateY(0)';
                }, 10);
            }, 300);
        }
    </script>

@endsection
