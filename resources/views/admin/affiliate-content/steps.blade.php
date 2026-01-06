@extends('layouts.admin.app')

@section('title', 'Kelola Steps affiliate')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="p-6">
        @include('admin.affiliate-content.horizontal-navigation')

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

        {{-- Steps Content --}}
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-[#785576] p-6">
                <div class="flex items-center justify-between text-white">
                    <div>
                        <h3 class="text-xl font-semibold">Steps Preview</h3>
                        <p class="text-purple-100 text-sm">Kelola langkah-langkah untuk bergabung sebagai affiliate
                            affiliate
                        </p>
                    </div>
                    <!-- Add Step Button -->
                    <a href="{{ route('admin.affiliate-content.steps.create') }}"
                        class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 hover:scale-105 flex items-center space-x-2 backdrop-blur-sm border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Add Step</span>
                    </a>
                </div>
            </div>

            <!-- Steps Content List -->
            <div class="p-6 font-nunito">
                <div class="space-y-4">

                    {{-- How to Join Title Section --}}
                    @if ($howToJoinTitle)
                        <div
                            class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                            <div
                                class="w-full px-6 py-4 flex justify-between items-center group transition-all duration-300 hover:bg-white/50">
                                <div class="flex items-center space-x-4 flex-1">
                                    <div>
                                        <h4
                                            class="font-semibold text-[#4D4C4C] transition-all duration-300 group-hover:text-purple-600 group-hover:scale-105">
                                            Judul dan Deskripsi Section
                                        </h4>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <!-- Edit Button -->
                                    <a href="{{ url('admin/affiliate-content/steps/how-to-join-title/edit') }}"
                                        class="text-blue-600 hover:text-blue-800 transition-all duration-300 p-2 rounded-full hover:bg-blue-100 hover:rotate-6"
                                        title="Edit Section Title">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="px-6 pb-6">
                                <div class="bg-white rounded-lg p-4 border-l-4 border-purple-500">
                                    <h5 class="font-bold text-gray-800 mb-2">
                                        {{ $howToJoinTitle->title ?? 'Judul belum diatur' }}</h5>
                                    <p class="text-gray-700 leading-relaxed">
                                        {{ $howToJoinTitle->body ?? 'Deskripsi belum diatur' }}</p>
                                </div>
                                <div class="flex justify-between items-center mt-3 text-xs text-[#72C7B4]">
                                    <span>Diperbarui:
                                        {{ $howToJoinTitle && $howToJoinTitle->updated_at ? $howToJoinTitle->updated_at->format('d M Y H:i') : 'Belum tersedia' }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Steps Items Section --}}
                    @foreach ($steps as $step)
                        <div
                            class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                            <div
                                class="w-full px-6 py-4 flex justify-between items-center group transition-all duration-300 hover:bg-white/50">
                                <div class="flex items-center space-x-4 flex-1">
                                    <div>
                                        <h3
                                            class="font-semibold text-[#4D4C4C] transition-all duration-300 group-hover:text-purple-600 group-hover:scale-105">
                                            {{ ucfirst(str_replace('-', ' ', $step->section)) }}
                                        </h3>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <!-- Edit Button -->
                                    <a href="{{ url('admin/affiliate-content/steps/' . $step->section . '/edit') }}"
                                        class="text-blue-600 hover:text-blue-800 transition-all duration-300 p-2 rounded-full hover:bg-blue-100 hover:rotate-6"
                                        title="Edit {{ ucfirst(str_replace('-', ' ', $step->section)) }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ url('admin/affiliate-content/' . $step->section) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus step ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-800 transition-all duration-300 p-2 rounded-full hover:bg-red-100 hover:rotate-6"
                                            title="Delete {{ ucfirst(str_replace('-', ' ', $step->section)) }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="px-6 pb-6">
                                <div class="bg-white rounded-lg p-4 border-l-4 border-purple-500">
                                    <div class="flex items-center space-x-4">
                                        {{-- Step Number --}}
                                        <div class="w-12 h-12 flex-shrink-0">
                                            <div
                                                class="w-full h-full bg-purple-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                                {{ str_replace('step-', '', $step->section) }}
                                            </div>
                                        </div>
                                        {{-- Content --}}
                                        <div class="flex-1">
                                            <h4 class="text-gray-700 leading-relaxed font-bold">
                                                {{ $step->title ?? 'Judul step belum diatur' }}</h4>
                                            <p class="text-gray-700 leading-relaxed">
                                                {{ $step->body ?? 'Deskripsi belum diatur' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-3 text-xs text-[#72C7B4]">
                                    <span>Diperbarui:
                                        {{ $step && $step->updated_at ? $step->updated_at->format('d M Y H:i') : 'Belum tersedia' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Empty State --}}
                    @if ($steps->isEmpty())
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada steps</h3>
                            <p class="text-gray-500 mb-4">Mulai dengan menambahkan langkah pertama untuk bergabung sebagai
                                affiliate affiliate</p>
                            <a href="{{ route('admin.affiliate-content.steps.create') }}"
                                class="bg-[#785576] text-white px-6 py-3 rounded-lg hover:bg-[#665368] transition duration-300">
                                Add Step Pertama
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

@endsection
