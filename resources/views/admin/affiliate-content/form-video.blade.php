@extends('layouts.admin.app')

@section('title', isset($video) ? 'Edit Video Affiliate' : 'Tambah Video Affiliate')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="p-6">
        @include('admin.affiliate-content.horizontal-navigation')

        {{-- Alert Messages --}}
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Form Card --}}
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-[#785576] p-6">
                <div class="flex items-center text-white">
                    <a href="{{ route('admin.affiliate-content.videos') }}"
                        class="mr-4 hover:bg-white/20 p-2 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h3 class="text-xl font-semibold">
                            {{ isset($video) ? 'Edit Video Affiliate' : 'Tambah Video Affiliate' }}
                        </h3>
                        <p class="text-purple-100 text-sm">
                            {{ isset($video) ? 'Perbarui informasi video TikTok' : 'Tambahkan video TikTok baru untuk affiliate' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Content - 2 Column Layout -->
            <div class="p-6 font-nunito">
                <form
                    action="{{ isset($video) ? route('admin.affiliate-content.videos.update', $video->section) : route('admin.affiliate-content.videos.store') }}"
                    method="POST">
                    @csrf
                    @if(isset($video))
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
                        <!-- Left Column - Form Fields -->
                        <div class="space-y-6 h-full">
                            <!-- Video URL -->
                            <div>
                                <label for="video_url" class="block text-sm font-medium text-gray-700 mb-2">
                                    URL Video TikTok <span class="text-red-500">*</span>
                                </label>
                                <input type="url" name="video_url" id="video_url"
                                    value="{{ old('video_url', $video->video_url ?? '') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('video_url') border-red-500 @enderror"
                                    placeholder="https://www.tiktok.com/@username/video/..." required>
                                @error('video_url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-500">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Salin URL lengkap video TikTok dari browser atau aplikasi
                                </p>
                            </div>

                            <!-- Username -->
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                    Username TikTok <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">@</span>
                                    <input type="text" name="username" id="username"
                                        value="{{ old('username', $video->username ?? '') }}"
                                        class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('username') border-red-500 @enderror"
                                        placeholder="username" required>
                                </div>
                                @error('username')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-500">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Username tanpa simbol @ (contoh: johndoe)
                                </p>
                            </div>

                            <!-- Title (Optional) -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Judul Video <span class="text-gray-400 text-xs">(Opsional)</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title', $video->title ?? '') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('title') border-red-500 @enderror"
                                    placeholder="Judul deskriptif untuk video (opsional)">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons - Mobile -->
                            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200 lg:hidden">
                                <a href="{{ route('admin.affiliate-content.videos') }}"
                                    class="px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-all duration-200 font-medium">
                                    Batal
                                </a>
                                <button type="submit"
                                    class="px-6 py-3 bg-[#785576] text-white rounded-lg hover:bg-[#614DAC] transition-all duration-200 font-medium flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ isset($video) ? 'Update Video' : 'Simpan Video' }}
                                </button>
                            </div>
                        </div>

                        <!-- Right Column - Preview -->
                        <div class="lg:sticky lg:top-6 lg:self-start h-full flex flex-col">
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-6 flex-grow">
                                <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Preview Video TikTok
                                </h4>

                                <div class="bg-white rounded-lg p-4 ">
                                    <div id="preview-content">
                                        @if(isset($video) && $video->video_url)
                                            <!-- TikTok Video Preview -->
                                            <div class="w-full max-w-[280px] mx-auto">
                                                <div
                                                    class="relative aspect-[9/16] w-full bg-gray-100 overflow-hidden rounded-2xl shadow-xl">
                                                    <blockquote class="tiktok-embed h-full w-full"
                                                        cite="{{ $video->video_url }}"
                                                        data-video-id="{{ basename(parse_url($video->video_url, PHP_URL_PATH)) }}"
                                                        style="max-width: 100% !important; min-width: 100% !important; height: 100% !important; margin: 0 !important;">
                                                        <section></section>
                                                    </blockquote>

                                                    <div
                                                        class="absolute bottom-0 left-0 right-0 h-28 bg-gradient-to-t from-black/70 to-transparent pointer-events-none z-10">
                                                    </div>

                                                    <div class="absolute bottom-4 left-0 right-0 px-4 pointer-events-none z-20">
                                                        <p class="text-white text-sm font-bold drop-shadow-lg font-nunito">
                                                            {{ '@' . $video->username }}
                                                        </p>
                                                        @if($video->title)
                                                            <p class="text-white text-xs drop-shadow-lg font-nunito mt-1">
                                                                {{ $video->title }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center py-12">
                                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                                <p class="text-gray-500 text-sm italic">Masukkan URL video TikTok untuk melihat
                                                    preview</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons - Desktop -->
                            <div class="hidden lg:flex items-center justify-end space-x-4 mt-6">
                                <a href="{{ route('admin.affiliate-content.videos') }}"
                                    class="px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-all duration-200 font-medium">
                                    Batal
                                </a>
                                <button type="submit"
                                    class="px-6 py-3 bg-[#785576] text-white rounded-lg hover:bg-[#614DAC] transition-all duration-200 font-medium flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ isset($video) ? 'Update Video' : 'Simpan Video' }}
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <!-- Tips Card -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
                Tips Menambahkan Video TikTok
            </h4>
            <ul class="space-y-2 text-sm text-blue-800">
                <li class="flex items-start">
                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Pastikan video TikTok bersifat publik (tidak private)</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Salin URL lengkap dari browser (contoh: https://www.tiktok.com/@username/video/1234567890)</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Username harus sesuai dengan akun TikTok yang mengupload video</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Maksimal 4 video dapat ditambahkan</span>
                </li>
            </ul>
        </div>
    </div>

    {{-- TikTok Embed Script --}}
    <script async src="https://www.tiktok.com/embed.js"></script>

    <script>
        // Live preview update
        const videoUrlInput = document.getElementById('video_url');
        const usernameInput = document.getElementById('username');
        const titleInput = document.getElementById('title');
        const previewContent = document.getElementById('preview-content');
        let tiktokScriptLoaded = false;

        function updatePreview() {
            const videoUrl = videoUrlInput.value.trim();
            const username = usernameInput.value.trim();
            const title = titleInput.value.trim();

            // Validasi URL TikTok
            const isTikTokUrl = videoUrl && (videoUrl.includes('tiktok.com') || videoUrl.includes('vt.tiktok.com'));

            if (!isTikTokUrl) {
                previewContent.innerHTML = `
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-500 text-sm italic">Masukkan URL video TikTok untuk melihat preview</p>
                        </div>
                    `;
                return;
            }

            // Tampilkan TikTok Embed
            let html = `
                    <div class="w-full max-w-[280px] mx-auto">
                        <div class="relative aspect-[9/16] w-full bg-gray-100 overflow-hidden rounded-2xl shadow-xl">
                            <blockquote class="tiktok-embed h-full w-full"
                                cite="${videoUrl}"
                                data-video-id="${videoUrl.split('/').pop().split('?')[0]}"
                                style="max-width: 100% !important; min-width: 100% !important; height: 100% !important; margin: 0 !important;">
                                <section></section>
                            </blockquote>

                            <!-- Bottom Gradient -->
                            <div class="absolute bottom-0 left-0 right-0 h-28 bg-gradient-to-t from-black/70 to-transparent pointer-events-none z-10"></div>

                            <!-- Username Overlay -->
                            <div class="absolute bottom-4 left-0 right-0 px-4 pointer-events-none z-20">
                `;

            if (username) {
                html += `
                        <p class="text-white text-sm font-bold drop-shadow-lg font-nunito">
                            @${username}
                        </p>
                    `;
            }

            if (title) {
                html += `
                        <p class="text-white text-xs drop-shadow-lg font-nunito mt-1">
                            ${title}
                        </p>
                    `;
            }

            html += `
                            </div>
                        </div>
                    </div>
                `;

            previewContent.innerHTML = html;

            // Reload TikTok embed script
            if (window.tiktokEmbed) {
                window.tiktokEmbed.load();
            } else {
                // Load script jika belum ada
                const script = document.createElement('script');
                script.src = 'https://www.tiktok.com/embed.js';
                script.async = true;
                document.body.appendChild(script);
            }
        }

        // Debounce function untuk menghindari terlalu banyak update
        let debounceTimer;
        function debounceUpdate() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(updatePreview, 800);
        }

        videoUrlInput.addEventListener('input', debounceUpdate);
        usernameInput.addEventListener('input', updatePreview);
        titleInput.addEventListener('input', updatePreview);
    </script>

@endsection