@extends('layouts.admin.app')

@section('title', 'Kelola FAQ')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="px-6 py-8">
        @include('admin.homepage-content.horizontal-navigation')

        {{-- Header Section --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-[#4D4C4C]">Frequently Asked Questions</h2>
                <p class="text-gray-600 mt-2">Kelola pertanyaan yang sering diajukan pelanggan</p>
            </div>
            <a href="{{ route('admin.homepage-content.faq.create') }}"
                class="bg-[#785576] hover:bg-[#614DAC] text-white px-6 py-3 rounded-xl font-medium flex items-center space-x-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah FAQ Baru</span>
            </a>
        </div>

        {{-- Daftar FAQ --}}
        @if ($faqs->count() > 0)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Header -->
                <div class="bg-[#785576] p-6">
                    <div class="flex items-center text-white">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <div>
                            <h3 class="text-xl font-semibold">FAQ Preview</h3>
                            <p class="text-purple-100 text-sm">Tampilan FAQ seperti yang akan dilihat pengunjung</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ List -->
                <div class="p-6">
                    <div class="space-y-4">

                        @foreach ($faqs as $index => $faq)
                            <div
                                class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                                <!-- FAQ Header -->
                                <div
                                    class="w-full px-6 py-5 flex justify-between items-center hover:bg-white/60 transition-all duration-200">
                                    <button type="button" onclick="toggleFaqPreview({{ $index }})"
                                        class="flex items-center space-x-4 flex-1 text-left">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 bg-[#785576] rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                            Q{{ $index + 1 }}
                                        </div>
                                        <h4
                                            class="font-bold text-[#4D4C4C] group-hover:text-[#785576] transition-colors duration-200 text-lg">
                                            {{ $faq->title }}
                                        </h4>
                                    </button>
                                    <div class="flex items-center space-x-2 ml-4">
                                        <!-- Edit Button -->
                                        <button type="button" onclick="editFaq('{{ $faq->section }}')"
                                            class="text-[#785576] hover:text-[#614DAC] transition-colors duration-200 p-3 rounded-full hover:bg-[#785576]/10 shadow-sm"
                                            title="Edit Data">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>

                                        <!-- Delete Button -->
                                        <button type="button" onclick="deleteFaq('{{ $faq->section }}')"
                                            class="text-red-500 hover:text-red-700 transition-colors duration-200 p-3 rounded-full hover:bg-red-50 shadow-sm"
                                            title="Hapus Data">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                        <!-- Expand Icon -->
                                        <button type="button" onclick="toggleFaqPreview({{ $index }})"
                                            class="p-2 hover:bg-gray-200 rounded-full transition-colors duration-200">
                                            <svg id="icon-{{ $index }}"
                                                class="w-5 h-5 text-gray-500 transform transition-transform duration-300"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- FAQ Answer -->
                                <div id="content-{{ $index }}" class="hidden">
                                    <div class="px-6 pb-6">
                                        <div class="bg-white rounded-xl p-6 border-l-4 border-[#785576] shadow-sm">
                                            <div class="flex items-start space-x-3">
                                                <div
                                                    class="flex-shrink-0 w-8 h-8 bg-[#785576]/10 rounded-full flex items-center justify-center">
                                                    <span class="text-[#785576] font-bold text-sm">A</span>
                                                </div>
                                                <p class="text-[#4D4C4C] leading-relaxed">{{ $faq->body }}</p>
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center mt-4 text-xs text-gray-500">
                                            <span class="bg-gray-100 px-3 py-1 rounded-full">Section:
                                                {{ $faq->section }}</span>
                                            <span>Dibuat: {{ $faq->created_at->format('d M Y H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-medium text-[#4D4C4C] mb-2">Belum Ada FAQ</h3>
                    <p class="text-gray-500 mb-6">Mulai dengan menambahkan pertanyaan yang sering diajukan pelanggan</p>
                    <a href="{{ route('admin.homepage-content.faq.create') }}"
                        class="bg-[#785576] hover:bg-[#614DAC] text-white px-6 py-3 rounded-xl font-medium inline-flex items-center space-x-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Tambah FAQ Pertama</span>
                    </a>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Toggle FAQ Preview
        function toggleFaqPreview(index) {
            const content = document.getElementById('content-' + index);
            const icon = document.getElementById('icon-' + index);

            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Edit FAQ - redirect to create page with data
        function editFaq(section) {
            // Redirect to edit page (which uses the same create form)
            window.location.href = `{{ route('admin.homepage-content.faq.edit', ':section') }}`.replace(':section',
                section);
        }

        // Delete FAQ
        function deleteFaq(section) {
            if (confirm('Apakah Anda yakin ingin menghapus FAQ ini? Tindakan ini tidak dapat dibatalkan.')) {
                fetch(`{{ route('admin.homepage-content.faq.delete', ':section') }}`.replace(':section', section), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('success', data.message);
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showNotification('error', data.message || 'Terjadi kesalahan');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('error', 'Terjadi kesalahan pada sistem');
                    });
            }
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
