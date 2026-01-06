@extends('layouts.admin.app')

@section('title', isset($editingPhoto) ? 'Edit Foto Individual' : 'Edit Foto Keluarga Gentle Living')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="px-6 py-8">

        {{-- Header Section --}}
        <div class="flex items-center justify-between py-5 font-nunito">
            <h2 class="font-nunito font-bold text-2xl text-[#614DAC]">
                {{ isset($editingPhoto) ? 'Edit Foto Individual' : 'Edit Foto Keluarga Gentle Living' }}
            </h2>

            <a href="{{ route('admin.about-us-content.family') }}"
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

        @if ($errors->any())
            <div class="bg-red-100 border border-red-200 text-red-800 px-6 py-4 rounded-xl mb-6 shadow-sm" role="alert">
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

        <div class="p-6">
            {{-- Individual Photo Edit Form (when photoId is present) --}}
            @if (isset($editingPhoto))
                <div
                    class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200 overflow-hidden">
                    <div class="bg-[#785576] p-6 text-white">
                        <h4 class="text-xl font-semibold">Edit Foto Individual</h4>
                        <p class="text-purple-100 text-sm">Perbarui foto keluarga yang dipilih</p>
                    </div>

                    <form
                        action="{{ route('admin.about-us-content.update-single-family-photo', $editingPhoto->content_id ?? $slotNumber ?? $photoId) }}"
                        method="POST" enctype="multipart/form-data" class="p-6">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            {{-- Current Photo Preview --}}
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    {{ $editingPhoto->image ? 'Foto Saat Ini' : 'Tambah Foto Baru' }}
                                    @if($slotNumber)
                                        <span class="text-blue-600">(Slot {{ $slotNumber }})</span>
                                    @endif
                                </label>
                                <div class="flex justify-center">
                                    <div
                                        class="w-64 h-64 bg-gradient-to-br from-gray-100 to-gray-200 border-2 border-dashed border-gray-300 rounded-xl overflow-hidden">
                                        @if ($editingPhoto->image)
                                            <img src="{{ Storage::url($editingPhoto->image) }}"
                                                alt="{{ $editingPhoto->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div
                                                class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                                <svg class="w-20 h-20 mb-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                                </svg>
                                                <p class="text-sm">Belum ada foto</p>
                                                @if($slotNumber)
                                                    <p class="text-xs mt-1">Slot {{ $slotNumber }}</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Upload New Photo --}}
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <label for="photo" class="block text-sm font-medium text-gray-700 mb-3">
                                    Pilih Foto Baru <span class="text-red-500">*</span>
                                </label>
                                <input type="file" id="photo" name="photo" accept="image/*" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-[#785576]"
                                    onchange="previewNewPhoto(event)">
                                <p class="mt-2 text-sm text-gray-500">Format yang didukung: JPG, PNG, GIF, WebP. Tidak ada
                                    batasan ukuran file.</p>

                                {{-- Preview New Photo --}}
                                <div id="newPhotoPreview" class="mt-4 hidden">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Preview Foto Baru:</label>
                                    <div class="flex justify-center">
                                        <div
                                            class="w-64 h-64 bg-gray-100 border-2 border-dashed border-green-300 rounded-xl overflow-hidden">
                                            <img id="newPhotoPreviewImg" src="" alt="Preview"
                                                class="w-full h-full object-cover">
                                        </div>
                                    </div>
                                    <p class="text-center text-sm text-green-600 mt-2">✓ Foto siap untuk diupload</p>
                                </div>
                            </div>

                            {{-- Photo Title --}}
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-3">
                                    Judul Foto (Opsional)
                                </label>
                                <input type="text" id="title" name="title"
                                    value="{{ old('title', $editingPhoto->title ?? ($slotNumber ? 'Keluarga ' . $slotNumber : '')) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-[#785576]"
                                    placeholder="Masukkan judul foto...">
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.about-us-content.edit-family-photos') }}"
                                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-[#4D4C4C] rounded-xl font-medium transition">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-3 bg-[#785576] hover:bg-[#614DAC] text-white rounded-xl font-medium flex items-center space-x-2 shadow-lg hover:shadow-xl transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ $editingPhoto->image ? 'Update Foto' : 'Tambah Foto' }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- All 10 Slots Display (only show when not editing individual photo) --}}
            @if (!isset($editingPhoto))
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-xl font-semibold text-gray-800">Galeri Foto Keluarga (10 Slot)</h4>
                        <div class="flex items-center space-x-3">
                            <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                                @php
                                    $filledCount = $familyImages->filter(function($img) { return !empty($img->image); })->count();
                                @endphp
                                {{ $filledCount }} dari 10 foto
                            </span>
                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                    style="width: {{ ($filledCount / 10) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                        @php
                            // Convert collection to array with slot numbers
                            $imagesBySlot = [];
                            foreach ($familyImages as $image) {
                                preg_match('/family-image-(\d+)/', $image->section, $matches);
                                if (isset($matches[1])) {
                                    $imagesBySlot[$matches[1]] = $image;
                                }
                            }
                        @endphp

                        @for($i = 1; $i <= 10; $i++)
                            @php
                                $currentImage = $imagesBySlot[$i] ?? null;
                            @endphp
                            <div class="relative group bg-white border-2 border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-all duration-300 hover:shadow-lg">
                                <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 relative">
                                    @if ($currentImage && $currentImage->image)
                                        <img src="{{ Storage::url($currentImage->image) }}" alt="{{ $currentImage->title }}"
                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                    @else
                                        <!-- Default User Icon -->
                                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                            <svg class="w-16 h-16 mb-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                            </svg>
                                            <p class="text-xs">Foto {{ $i }}</p>
                                        </div>
                                    @endif

                                    <!-- Edit Button - Top Right Corner -->
                                    <div class="absolute top-2 right-2 flex space-x-1">
                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.about-us-content.edit-family-photos', $currentImage ? $currentImage->content_id : $i) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-full shadow-lg transition-all duration-200 hover:scale-110 opacity-90 hover:opacity-100"
                                            title="{{ $currentImage && $currentImage->image ? 'Edit Foto' : 'Tambah Foto' }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($currentImage && $currentImage->image)
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4v16m8-8H4">
                                                    </path>
                                                @endif
                                            </svg>
                                        </a>

                                        <!-- Delete Button (only if image exists) -->
                                        @if ($currentImage && $currentImage->image)
                                            <form action="{{ route('admin.about-us-content.delete-family-image', $currentImage->content_id) }}"
                                                method="POST"
                                                onsubmit="return confirm('🗑️ Apakah Anda yakin ingin menghapus foto ini?')"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-lg transition-all duration-200 hover:scale-110 opacity-90 hover:opacity-100"
                                                    title="Hapus Foto">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <!-- Image Title -->
                                <div class="p-3 bg-white">
                                    <p class="text-sm font-medium text-gray-700 truncate text-center">
                                        {{ $currentImage && $currentImage->title ? $currentImage->title : 'Foto ' . $i }}
                                    </p>
                                    <p class="text-xs text-gray-500 text-center mt-1">
                                        {{ $currentImage && $currentImage->updated_at ? $currentImage->updated_at->format('d M Y') : '-' }}
                                    </p>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            @endif
        </div>
    </div>
    </div>

    <script>
        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Add escape key handler for add photo modal
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    // Close add photo modal if open
                    const addModal = document.getElementById('addPhotoModal');
                    if (addModal && !addModal.classList.contains('hidden')) {
                        closeAddPhotoModal();
                    }
                }
            });
        });

        // Preview function for individual photo edit
        function previewNewPhoto(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('newPhotoPreview');
            const img = document.getElementById('newPhotoPreviewImg');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        }
    </script>

@endsection
