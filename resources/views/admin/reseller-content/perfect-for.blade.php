@extends('layouts.admin.app')

@section('title', 'Kelola Perfect For - Reseller')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="p-6">
        @include('admin.reseller-content.horizontal-navigation')

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

        {{-- Perfect For Content --}}
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-[#785576] p-6">
                <div class="flex items-center text-white">
                    <div>
                        <h3 class="text-xl font-semibold">Perfect For Preview</h3>
                        <p class="text-purple-100 text-sm">Kelola target audience yang cocok untuk program reseller</p>
                    </div>
                </div>
            </div>

            <!-- Perfect For Content List -->
            <div class="p-6 font-nunito">
                <div class="space-y-4">

                    {{-- Perfect For Title Section --}}
                    @if ($title)
                        <div
                            class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                            <div
                                class="w-full px-6 py-4 flex justify-between items-center group transition-all duration-300 hover:bg-white/50">
                                <div class="flex items-center space-x-4 flex-1">
                                    <div>
                                        <h4
                                            class="font-semibold text-[#4D4C4C] transition-all duration-300 group-hover:text-purple-600 group-hover:scale-105">
                                            Judul dan Subtitle Section
                                        </h4>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <!-- Edit Button -->
                                    <button type="button" onclick="editPerfectFor('reseller-perfect-for-title')"
                                        class="text-blue-600 hover:text-blue-800 transition-all duration-300 p-2 rounded-full hover:bg-blue-100 hover:rotate-6"
                                        title="Edit Section Title">
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
                                    <h5 class="font-bold text-gray-800 mb-2">
                                        {{ $title->title ?? 'Judul belum diatur' }}</h5>
                                    <p class="text-gray-700 leading-relaxed">
                                        {{ $title->body ?? 'Deskripsi belum diatur' }}</p>
                                </div>
                                <div class="flex justify-between items-center mt-3 text-xs text-[#72C7B4]">
                                    <span>Diperbarui:
                                        {{ $title && $title->updated_at ? $title->updated_at->format('d M Y H:i') : 'Belum tersedia' }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Perfect For Items Section --}}
                    @for ($i = 1; $i <= 5; $i++)
                        @php
                            $target = $items->firstWhere('section', 'reseller-perfect-' . $i);
                        @endphp
                        <div
                            class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                            <div
                                class="w-full px-6 py-4 flex justify-between items-center group transition-all duration-300 hover:bg-white/50">
                                <div class="flex items-center space-x-4 flex-1">
                                    <div>
                                        <h3
                                            class="font-semibold text-[#4D4C4C] transition-all duration-300 group-hover:text-purple-600 group-hover:scale-105">
                                            Target {{ $i }}
                                        </h3>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <!-- Edit Button -->
                                    <button type="button" onclick="editPerfectFor('reseller-perfect-{{ $i }}')"
                                        class="text-blue-600 hover:text-blue-800 transition-all duration-300 p-2 rounded-full hover:bg-blue-100 hover:rotate-6"
                                        title="Edit Target {{ $i }}">
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
                                    {{-- Content --}}
                                    <div>
                                        <h4 class="text-gray-700 leading-relaxed font-bold mb-2">
                                            {{ $target->title ?? 'Target belum diatur' }}</h4>
                                        <p class="text-gray-700 leading-relaxed">
                                            {{ $target->body ?? 'Deskripsi belum diatur' }}</p>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-3 text-xs text-[#72C7B4]">
                                    <span>Diperbarui:
                                        {{ $target && $target->updated_at ? $target->updated_at->format('d M Y H:i') : 'Belum tersedia' }}</span>
                                </div>
                            </div>
                        </div>
                    @endfor

                </div>
            </div>
        </div>

    </div>

    <script>
        // Edit Perfect For - redirect to edit page with data
        function editPerfectFor(section) {
            // Redirect to edit page
            window.location.href =
                `{{ route('admin.reseller-content.section.edit', ['sectionKey' => 'perfect-for', 'section' => ':section']) }}`
                .replace(':section',
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
