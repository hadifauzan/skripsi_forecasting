@extends('layouts.admin.app')

@section('title', 'Edit Banner Reseller')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="px-6 py-8">

        {{-- Header Section --}}
        <div class="flex items-center justify-between py-5 font-nunito">
            <h2 class="font-nunito font-bold text-2xl text-[#614DAC]">
                Edit Banner Reseller
            </h2>

            <a href="{{ route('admin.reseller-content.banner') }}"
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

        {{-- Banner Image Upload Form --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <!-- Header Form -->
            <div class="bg-[#785576] p-6">
                <div class="flex items-center text-white">
                    <div>
                        <h3 class="text-xl font-semibold">Upload Banner Reseller</h3>
                        <p class="text-purple-100 text-sm">Kelola gambar banner utama halaman reseller</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form action="{{ route('admin.reseller-content.update-banner') }}" method="POST" enctype="multipart/form-data"
                class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column: Upload Area -->
                    <div class="space-y-6">
                        <!-- Image Upload -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                Gambar Banner (Opsional)
                            </label>

                            <!-- Upload Area -->
                            <div id="uploadArea"
                                class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-[#785576] transition-colors">
                                <input type="file" id="image" name="image" accept="image/*" class="hidden"
                                    onchange="previewNewImage(this)">
                                <label for="image" class="cursor-pointer">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="text-sm text-gray-600">
                                        <span class="font-medium text-[#785576] hover:text-[#614DAC]">Klik untuk
                                            upload</span>
                                        atau drag and drop
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG hingga 2MB</p>
                                    <p class="text-xs text-gray-400 mt-1">Ukuran disarankan: 1920x1080px (16:9)</p>
                                </label>
                            </div>

                            <!-- Preview New Image -->
                            <div id="newImagePreview" class="hidden mt-4 border border-gray-200 rounded-lg overflow-hidden">
                                <div class="relative">
                                    <img id="newPreviewImg" src="" alt="Preview" class="w-full h-48 object-cover">
                                    <button type="button" onclick="removeNewImage()"
                                        class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="p-3 bg-gray-50">
                                    <h4 id="newImageTitle" class="font-medium text-gray-900"></h4>
                                    <p id="newImageName" class="text-sm text-gray-500"></p>
                                </div>
                            </div>

                            @error('image')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul (Opsional)
                            </label>
                            <input type="text" id="title" name="title"
                                value="{{ old('title', $bannerImage->title ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 hover:border-[#785576]/50"
                                placeholder="Contoh: Bergabunglah menjadi Reseller kami">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Body -->
                        <div>
                            <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi (Opsional)
                            </label>
                            <textarea id="body" name="body" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent transition-all duration-200 resize-none hover:border-[#785576]/50"
                                placeholder="Masukkan teks deskripsi untuk banner...">{{ old('body', $bannerImage->body ?? '') }}</textarea>
                            @error('body')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column: Current Image Preview -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini</label>
                            @if ($bannerImage && $bannerImage->image)
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <img src="{{ Storage::url($bannerImage->image) }}" alt="Banner Saat Ini"
                                        class="w-full h-48 object-cover">
                                    <div class="p-3 bg-gray-50">
                                        <p class="text-sm text-gray-600">
                                            <span class="text-green-600">✓</span> Banner telah diupload
                                        </p>
                                        @if ($bannerImage->updated_at)
                                            <p class="text-xs text-gray-500 mt-1">
                                                Terakhir diperbarui: {{ $bannerImage->updated_at->format('d M Y H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="border border-gray-200 rounded-lg p-6 text-center bg-gray-50">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p class="text-sm text-gray-500">Belum ada gambar banner</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.reseller-content.banner') }}"
                        class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-[#4D4C4C] rounded-xl font-medium transition-all duration-200 border border-gray-300 hover:border-gray-400">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-[#785576] hover:bg-[#614DAC] text-white rounded-xl font-medium transition-all duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                        <span>Simpan Banner</span>
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
