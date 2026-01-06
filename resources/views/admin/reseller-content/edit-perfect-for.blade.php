@extends('layouts.admin.app')

@section('title', 'Edit ' . ucfirst(str_replace('-', ' ', $section)))

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="px-6 py-8">

        {{-- Header Section --}}
        <div class="flex items-center justify-between py-5 font-nunito">
            <h2 class="font-nunito font-bold text-2xl text-[#614DAC]">
                Edit
                {{ $section === 'reseller-perfect-for-title' ? 'Perfect For Title' : ucfirst(str_replace('-', ' ', $section)) }}
            </h2>

            <a href="{{ route('admin.reseller-content.section', 'perfect-for') }}"
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

        {{-- Main Form --}}
        <form method="POST"
            action="{{ route('admin.reseller-content.section.update', ['sectionKey' => 'perfect-for', 'section' => $section]) }}"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">

                <div class="p-6">
                    @if ($section === 'reseller-perfect-for-title')
                        {{-- Title Section --}}
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Section
                            </label>
                            <input type="text" id="title" name="title"
                                value="{{ old('title', $content->title ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                placeholder="Masukkan judul section (opsional)">
                        </div>

                        <div class="mb-6">
                            <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi (Opsional)
                            </label>
                            <input type="text" id="body" name="body"
                                value="{{ old('body', $content->body ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                placeholder="Masukkan deskripsi">
                        </div>
                    @else
                        {{-- Individual Perfect For Item --}}
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Target Reseller
                            </label>
                            <input type="text" id="title" name="title"
                                value="{{ old('title', $content->title ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                placeholder="Contoh: Ibu Rumah Tangga (opsional)">
                        </div>

                        <div class="mb-6">
                            <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Target Reseller
                            </label>
                            <textarea id="body" name="body" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                                placeholder="Jelaskan mengapa cocok untuk jenis reseller ini (opsional)">{{ old('body', $content->body ?? '') }}</textarea>
                        </div>
                    @endif
                </div>

                {{-- Form Actions --}}
                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <a href="{{ route('admin.reseller-content.section', 'perfect-for') }}"
                        class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition duration-300">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-[#785576] text-white px-6 py-3 rounded-lg hover:bg-[#665368] transition duration-300">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>

    </div>



@endsection
