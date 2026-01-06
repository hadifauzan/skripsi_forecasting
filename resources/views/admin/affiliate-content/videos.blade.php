@extends('layouts.admin.app')

@section('title', 'Kelola Video Affiliate')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="p-6">
        @include('admin.affiliate-content.horizontal-navigation')

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

        {{-- Video Affiliate Content --}}
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-[#785576] p-6">
                <div class="flex items-center justify-between text-white">
                    <div>
                        <h3 class="text-xl font-semibold">Video Affiliate TikTok</h3>
                        <p class="text-purple-100 text-sm">Kelola video TikTok affiliate (Maksimal 4 video)</p>
                    </div>
                    @if($videos->count() < 4)
                        <a href="{{ route('admin.affiliate-content.videos.create') }}"
                            class="flex items-center px-4 py-2 bg-white text-[#785576] rounded-lg hover:bg-purple-50 transition-all duration-200 font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Video
                        </a>
                    @endif
                </div>
            </div>

            <!-- Video Content List -->
            <div class="p-6 font-nunito">
                @if($videos->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($videos as $index => $video)
                            <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                                <div class="p-6">
                                    <!-- Video Header -->
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="bg-[#785576] text-white rounded-full w-10 h-10 flex items-center justify-center font-bold">
                                                {{ $index + 1 }}
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-800">Video {{ $index + 1 }}</h4>
                                                <p class="text-sm text-gray-600">{{ '@' . $video->username }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <!-- Edit Button -->
                                            <a href="{{ route('admin.affiliate-content.videos.edit', $video->section) }}"
                                                class="text-blue-600 hover:text-blue-800 transition-all duration-300 p-2 rounded-full hover:bg-blue-100"
                                                title="Edit Video">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <!-- Delete Button -->
                                            <button type="button" onclick="deleteVideo('{{ $video->section }}')"
                                                class="text-red-600 hover:text-red-800 transition-all duration-300 p-2 rounded-full hover:bg-red-100"
                                                title="Hapus Video">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Video Info -->
                                    <div class="bg-white rounded-lg p-4 border-l-4 border-purple-500">
                                        @if($video->title)
                                            <h5 class="font-bold text-gray-800 mb-2">{{ $video->title }}</h5>
                                        @endif
                                        
                                        <div class="space-y-2">
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                <span class="font-medium">Username:</span>
                                                <span class="ml-1">{{ '@' . $video->username }}</span>
                                            </div>
                                            
                                            <div class="flex items-start text-sm text-gray-600">
                                                <svg class="w-4 h-4 mr-2 mt-0.5 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                                </svg>
                                                <div class="flex-1">
                                                    <span class="font-medium">URL:</span>
                                                    <a href="{{ $video->video_url }}" target="_blank" class="ml-1 text-blue-600 hover:text-blue-800 break-all">
                                                        {{ Str::limit($video->video_url, 50) }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Preview Link -->
                                        <div class="mt-4">
                                            <a href="{{ $video->video_url }}" target="_blank"
                                                class="inline-flex items-center px-4 py-2 bg-[#785576] text-white text-sm font-medium rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Lihat Video
                                            </a>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center mt-3 text-xs text-[#72C7B4]">
                                        <span>Diperbarui: {{ $video->updated_at->format('d M Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-600 text-lg mb-4">Belum ada video yang ditambahkan</p>
                        <a href="{{ route('admin.affiliate-content.videos.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-[#785576] text-white rounded-lg hover:bg-[#614DAC] transition-all duration-200 font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Video Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-5">Hapus Video</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Apakah Anda yakin ingin menghapus video ini? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                            Hapus
                        </button>
                    </form>
                    <button onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-24 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteVideo(section) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            form.action = `{{ route('admin.affiliate-content.videos.destroy', ':section') }}`.replace(':section', section);
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>

@endsection
