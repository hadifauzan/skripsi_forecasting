@extends('layouts.admin.app')

@section('title', 'Edit Tentang Kami')

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

            <a href="{{ route('admin.about-us-content.tentang-kami') }}"
                class="bg-[#785576] hover:bg-[#614DAC] text-white px-6 py-3 rounded-lg font-medium flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-200 text-green-800 px-6 py-4 rounded-xl mb-6 shadow-sm"
                role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-200 text-red-800 px-6 py-4 rounded-xl mb-6 shadow-sm" role="alert">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <span class="font-medium">Terdapat kesalahan:</span>
                </div>
                <ul class="ml-8 space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Edit Form --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-[#785576] p-6 text-white">
                <h3 class="text-xl font-semibold">Form Edit Tentang Kami</h3>
                <p class="text-purple-100 text-sm">Edit konten informasi tentang perusahaan</p>
            </div>

            <form action="{{ route('admin.about-us-content.update-tentang-kami') }}" method="POST"
                enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                {{-- Judul --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title', $content->title) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#785576]">
                </div>

                {{-- Konten --}}
                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                        Konten
                    </label>
                    <textarea id="body" name="body" rows="6"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#785576]"
                        placeholder="Tulis konten tentang perusahaan...">{{ old('body', $content->body) }}</textarea>
                </div>

                {{-- Upload dan Preview Gambar --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Upload Area -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Gambar (Opsional)
                        </label>
                        <input type="file" id="image" name="image" accept="image/*"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#785576]"
                            onchange="previewImage(event)">
                        @error('image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Preview gambar yang dipilih -->
                        <div id="newImagePreview" class="mt-4 hidden">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Preview Gambar Baru</h4>
                            <img id="previewImg" src="" alt="Preview"
                                class="w-70 h-70 object-cover rounded-lg shadow-md border">
                        </div>
                    </div>

                    <!-- Preview Current Image -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini</h4>
                        @if ($content && $content->image)
                            <img src="{{ Storage::url($content->image) }}" alt="{{ $content->title }}"
                                class="w-70 h-70 object-cover rounded-lg shadow-md border">
                        @else
                            <div class="w-70 h-70 bg-gray-200 flex items-center justify-center rounded-lg border">
                                <p class="text-gray-500">Belum ada gambar</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.about-us-content.tentang-kami') }}"
                        class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-[#4D4C4C] rounded-xl font-medium transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-[#785576] hover:bg-[#614DAC] text-white rounded-xl font-medium flex items-center space-x-2 shadow-lg hover:shadow-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('newImagePreview');
            const previewImg = document.getElementById('previewImg');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('hidden');
            }
        }
    </script>

@endsection
