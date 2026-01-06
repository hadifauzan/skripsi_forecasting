@extends('layouts.admin.app')

@section('title', 'Kelola Banner')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="p-6">
        @include('admin.homepage-content.horizontal-navigation')

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Daftar Banner --}}
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-[#785576] p-6">
                <div class="flex items-center text-white">
                    <div>
                        <h3 class="text-xl font-semibold">Banner Preview</h3>
                        <p class="text-purple-100 text-sm">Tampilan banner seperti yang akan dilihat pengunjung</p>
                    </div>
                </div>
            </div>

            <!-- Banner List -->
            <div class="p-6 font-nunito">
                <div class="space-y-4">

                    {{-- Banner Utama --}}
                    @if ($bannerContent)
                        <div
                            class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                            <div
                                class="w-full px-6 py-4 flex justify-between items-center group transition-all duration-300 hover:bg-white/50">
                                <div class="flex items-center space-x-4 flex-1">
                                    <div>
                                        <h4
                                            class="font-semibold text-[#4D4C4C] transition-all duration-300 group-hover:text-purple-600 group-hover:scale-105">
                                            {{ $bannerContent->title ?? 'Judul belum diatur' }}
                                        </h4>
                                        <p class="text-sm text-gray-600 mt-1">Banner Utama</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <!-- Edit Button -->
                                    <button type="button" onclick="editBanner('{{ $bannerContent->section }}')"
                                        class="text-blue-600 hover:text-blue-800 transition-all duration-300 p-2 rounded-full hover:bg-blue-100 hover:rotate-6"
                                        title="Edit Banner Utama">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="px-6 pb-6">
                                <div class="bg-white rounded-lg p-4 border-l-4 border-purple-500">
                                    <p class="text-gray-700 leading-relaxed">
                                        {{ $bannerContent->body ?? 'Deskripsi belum diatur' }}</p>
                                </div>
                                <div class="flex justify-between items-center mt-3 text-xs text-[#72C7B4]">
                                    <span>Diperbarui:
                                        {{ $bannerContent && $bannerContent->updated_at ? $bannerContent->updated_at->format('d M Y H:i') : 'Belum tersedia' }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Product Banner --}}
                    @if ($productBanner)
                        <div
                            class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                            <div
                                class="w-full px-6 py-4 flex justify-between items-center group transition-all duration-300 hover:bg-white/50">
                                <div class="flex items-center space-x-4 flex-1">
                                    <div>
                                        <h4
                                            class="font-semibold text-[#4D4C4C] transition-all duration-300 group-hover:text-purple-600 group-hover:scale-105">
                                            {{ $productBanner->title ?? 'Judul belum diatur' }}
                                        </h4>
                                        <p class="text-sm text-gray-600 mt-1">Produk Pada Banner</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <!-- Edit Button -->
                                    <button type="button" onclick="editBanner('{{ $productBanner->section }}')"
                                        class="text-blue-600 hover:text-blue-800 transition-all duration-300 p-2 rounded-full hover:bg-blue-100 hover:rotate-6"
                                        title="Edit Product Banner">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="px-6 pb-6">
                                <div class="bg-white rounded-lg p-4 border-l-4 border-purple-500">
                                    <div class="flex items-start space-x-4">
                                        {{-- Gambar --}}
                                        @if ($productBanner->image)
                                            <img src="{{ asset('storage/images/homepage/' . $productBanner->image) }}"
                                                alt="{{ $productBanner->title }}"
                                                class="h-20 w-20 object-cover rounded-lg border border-gray-300 group-hover:brightness-95 transition duration-300"
                                                onerror="this.src='{{ asset('images/default-placeholder.jpg') }}'">
                                        @else
                                            <div
                                                class="h-20 w-20 bg-gray-200 rounded-lg border border-gray-300 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif

                                        {{-- Poin --}}
                                        <div class="flex-1">
                                            @php
                                                $points = [
                                                    '100% alami',
                                                    'BPOM Certified',
                                                    'Newborn Friendly',
                                                    'Teruji Klinis',
                                                ];
                                                if ($productBanner && $productBanner->body) {
                                                    $bodyData = json_decode($productBanner->body, true);
                                                    if (isset($bodyData['points']) && is_array($bodyData['points'])) {
                                                        $points = $bodyData['points'];
                                                    }
                                                }
                                            @endphp
                                            <div class="space-y-2">
                                                @foreach ($points as $point)
                                                    @if ($point)
                                                        <div class="flex items-center group/item">
                                                            <div
                                                                class="w-4 h-4 bg-purple-500 rounded-full flex items-center justify-center mr-2 transition-all duration-300 group-hover/item:scale-110">
                                                                <svg class="w-2 h-2 text-white" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                            </div>
                                                            <span
                                                                class="text-sm text-gray-700 transition-colors duration-300 group-hover/item:text-purple-600">{{ $point }}</span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-3 text-xs text-[#72C7B4]">
                                    <span>Diperbarui:
                                        {{ $productBanner && $productBanner->updated_at ? $productBanner->updated_at->format('d M Y H:i') : 'Belum tersedia' }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <script>
        // Edit Banner - redirect to edit page with data
        function editBanner(section) {
            // Redirect to edit page
            window.location.href = `{{ route('admin.homepage-content.banner.edit', ':section') }}`.replace(':section',
                section);
        }

        // Show notification function
        function showNotification(type, message) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } text-white`;
            notification.textContent = message;

            // Add to page
            document.body.appendChild(notification);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>

@endsection
