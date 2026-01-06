@extends('layouts.admin.app')

@section('title', 'Edit Visi')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="px-6 py-8">

        {{-- Header Section --}}
        <div class="flex items-center justify-between py-5 font-nunito">
            <h2 class="font-nunito font-bold text-2xl text-[#614DAC]">
                Edit Section Visi
            </h2>

            <a href="{{ route('admin.about-us-content.vision-mission') }}"
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
                <h3 class="text-xl font-semibold">Form Edit Visi</h3>
                <p class="text-purple-100 text-sm">Kelola visi perusahaan</p>
            </div>

            <form action="{{ route('admin.about-us-content.update-vision') }}" method="POST" class="p-6 space-y-6">
                @csrf

                {{-- Judul Visi --}}
                <div>
                    <label for="vision_title" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Visi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="vision_title" name="vision_title"
                        value="{{ old('vision_title', $visionContent->title ?? 'Visi') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300"
                        placeholder="Masukkan judul visi...">
                </div>

                {{-- Dynamic Vision Points --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Poin-poin Visi <span class="text-red-500">*</span>
                    </label>

                    <div id="vision-container" class="space-y-3">
                        @if (!empty($visionData) && is_array($visionData))
                            @foreach ($visionData as $index => $vision)
                                <div class="vision-item bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-start space-x-3">
                                        <div
                                            class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mt-1">
                                            <span class="text-purple-600 font-semibold text-sm">{{ $index + 1 }}</span>
                                        </div>
                                        <textarea name="vision[]" rows="2"
                                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none transition-all duration-300"
                                            placeholder="Masukkan poin visi...">{{ old('vision.' . $index, $vision) }}</textarea>
                                        <button type="button" onclick="removeVisionItem(this)"
                                            class="flex-shrink-0 text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition-all duration-300"
                                            title="Hapus poin">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="vision-item bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-start space-x-3">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mt-1">
                                        <span class="text-purple-600 font-semibold text-sm">1</span>
                                    </div>
                                    <textarea name="vision[]" rows="2"
                                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none transition-all duration-300"
                                        placeholder="Masukkan poin visi...">{{ old('vision.0') }}</textarea>
                                    <button type="button" onclick="removeVisionItem(this)"
                                        class="flex-shrink-0 text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition-all duration-300"
                                        title="Hapus poin">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <button type="button" onclick="addVisionItem()"
                        class="mt-4 w-full border-2 border-dashed border-purple-300 text-purple-600 hover:text-purple-700 hover:border-purple-400 flex items-center justify-center space-x-2 py-4 rounded-lg transition-all duration-300 hover:bg-purple-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="font-medium">Tambah Poin Visi</span>
                    </button>
                </div>

                {{-- Submit Button --}}
                <div class="flex justify-end pt-6 border-t border-gray-200 space-x-3">
                    <a href="{{ route('admin.about-us-content.vision-mission') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium flex items-center space-x-2 transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span>Batal</span>
                    </a>
                    <button type="submit"
                        class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-8 py-3 rounded-lg font-medium flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span>Simpan Visi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- JavaScript for Dynamic Fields --}}
    <script>
        let visionCounter = {{ count($visionData ?? []) > 0 ? count($visionData) : 1 }};

        function addVisionItem() {
            visionCounter++;
            const container = document.getElementById('vision-container');
            const newItem = document.createElement('div');
            newItem.className = 'vision-item bg-gray-50 rounded-lg p-4 border border-gray-200';
            newItem.innerHTML = `
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mt-1">
                        <span class="text-purple-600 font-semibold text-sm">${visionCounter}</span>
                    </div>
                    <textarea name="vision[]" rows="2"
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none transition-all duration-300"
                        placeholder="Masukkan poin visi..."></textarea>
                    <button type="button" onclick="removeVisionItem(this)"
                        class="flex-shrink-0 text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition-all duration-300"
                        title="Hapus poin">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            `;

            container.appendChild(newItem);
            updateNumbering();
        }

        function removeVisionItem(button) {
            const visionItems = document.querySelectorAll('.vision-item');
            if (visionItems.length > 1) {
                button.closest('.vision-item').remove();
                updateNumbering();
            } else {
                alert('Minimal harus ada satu poin visi');
            }
        }

        function updateNumbering() {
            const visionItems = document.querySelectorAll('.vision-item');
            visionItems.forEach((item, index) => {
                const numberSpan = item.querySelector('.w-8.h-8 span');
                if (numberSpan) {
                    numberSpan.textContent = index + 1;
                }
            });
            visionCounter = visionItems.length;
        }
    </script>
@endsection
