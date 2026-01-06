@extends('layouts.admin.app')

@section('title', 'Visi & Misi')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="px-6 py-8">
        @include('admin.about-us-content.horizontal-navigation')

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

        {{-- Content Display --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="bg-[#785576] p-6">
                <div class=" text-white">
                    <h3 class="text-xl font-semibold">Section Visi & Misi</h3>
                    <p class="text-purple-100 text-sm">Modifikasi visi & misi sesuai kebutuhan terkini</p>
                </div>
            </div>

            {{-- Visi & Misi Content --}}
            <div class="p-6 font-nunito">
                <div class="grid md:grid-cols-2 gap-6">

                    {{-- Visi Section --}}
                    <div
                        class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div
                            class="w-full px-6 py-4 flex justify-between items-center group transition-all duration-300 hover:bg-white/50">
                            <div class="flex items-center space-x-4 flex-1">
                                <div>
                                    <h4
                                        class="font-semibold text-[#4D4C4C] transition-all duration-300 group-hover:text-purple-600 group-hover:scale-105">
                                        {{ $visionContent->title ?? 'Visi' }}
                                    </h4>
                                    <p class="text-sm text-gray-600 mt-1">Visi Perusahaan</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 ml-4">
                                <!-- Edit Vision Button -->
                                <a href="{{ route('admin.about-us-content.edit-vision') }}"
                                    class="text-purple-600 hover:text-purple-800 transition-all duration-300 p-2 rounded-full hover:bg-purple-100 hover:rotate-6"
                                    title="Edit Visi">
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
                                <div class="space-y-3">
                                    @if (!empty($visionData) && is_array($visionData))
                                        @foreach ($visionData as $vision)
                                            @if (!empty(trim($vision)))
                                                <div class="flex items-start group/item">
                                                    <div
                                                        class="w-2 h-2 bg-purple-500 rounded-full mt-2 mr-3 flex-shrink-0 transition-all duration-300 group-hover/item:scale-125">
                                                    </div>
                                                    <span
                                                        class="text-[#4D4C4C] leading-relaxed transition-all duration-300 group-hover/item:text-purple-700">{{ $vision }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="text-gray-500 italic text-center py-4">
                                            Belum ada visi yang ditambahkan
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex justify-between items-center mt-3 text-xs text-[#72C7B4]">
                                <span>Diperbarui:
                                    {{ $visionContent && $visionContent->updated_at ? $visionContent->updated_at->format('d M Y H:i') : 'Belum tersedia' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Misi Section --}}
                    <div
                        class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div
                            class="w-full px-6 py-4 flex justify-between items-center group transition-all duration-300 hover:bg-white/50">
                            <div class="flex items-center space-x-4 flex-1">
                                <div>
                                    <h4
                                        class="font-semibold text-[#4D4C4C] transition-all duration-300 group-hover:text-purple-600 group-hover:scale-105">
                                        {{ $missionContent->title ?? 'Misi' }}
                                    </h4>
                                    <p class="text-sm text-gray-600 mt-1">Misi Perusahaan</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 ml-4">
                                <!-- Edit Mission Button -->
                                <a href="{{ route('admin.about-us-content.edit-mission') }}"
                                    class="text-purple-600 hover:text-purple-800 transition-all duration-300 p-2 rounded-full hover:bg-purple-100 hover:rotate-6"
                                    title="Edit Visi">
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
                                <div class="space-y-3">
                                    @if (!empty($missionData) && is_array($missionData))
                                        @foreach ($missionData as $mission)
                                            @if (!empty(trim($mission)))
                                                <div class="flex items-start group/item">
                                                    <div
                                                        class="w-2 h-2 bg-purple-500 rounded-full mt-2 mr-3 flex-shrink-0 transition-all duration-300 group-hover/item:scale-125">
                                                    </div>
                                                    <span
                                                        class="text-[#4D4C4C] leading-relaxed transition-all duration-300 group-hover/item:text-purple-700">{{ $mission }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="text-gray-500 italic text-center py-4">
                                            Belum ada misi yang ditambahkan
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex justify-between items-center mt-3 text-xs text-[#72C7B4]">
                                <span>Diperbarui:
                                    {{ $missionContent && $missionContent->updated_at ? $missionContent->updated_at->format('d M Y H:i') : 'Belum tersedia' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript for Dropdown --}}
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown-menu');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('dropdown-menu');
            const button = event.target.closest('button');

            if (!button || !button.getAttribute('onclick')?.includes('toggleDropdown')) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
@endsection
