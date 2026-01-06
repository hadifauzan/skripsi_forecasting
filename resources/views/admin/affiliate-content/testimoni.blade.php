@extends('layouts.admin.app')

@section('title', 'Kelola Testimoni affiliate')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="px-6 py-8">
        @include('admin.affiliate-content.horizontal-navigation')

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl mb-6" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        {{-- Testimonial Content --}}
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-[#785576] p-6">
                <div class="flex items-center justify-between text-white">
                    <div>
                        <h3 class="text-xl font-semibold">Testimonial Preview</h3>
                        <p class="text-purple-100 text-sm">Kelola testimonial dari affiliate affiliate</p>
                    </div>
                    <!-- Add Testimonial Button -->
                    <button onclick="addTestimonial()"
                        class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 hover:scale-105 flex items-center space-x-2 backdrop-blur-sm border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Add Testimonial</span>
                    </button>
                </div>
            </div>

            <!-- Testimonial Content List -->
            <div class="p-6 font-nunito">
                <div class="space-y-4">

                    {{-- Testimonial Title Section --}}
                    @if ($testimonialTitle)
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
                                    <button type="button" onclick="editTestimoni('testimonial-title')"
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
                                        {{ $testimonialTitle->title ?? 'Judul belum diatur' }}</h5>
                                    <p class="text-gray-700 leading-relaxed">
                                        {{ $testimonialTitle->body ?? 'Deskripsi belum diatur' }}</p>
                                </div>
                                <div class="flex justify-between items-center mt-3 text-xs text-[#72C7B4]">
                                    <span>Diperbarui:
                                        {{ $testimonialTitle && $testimonialTitle->updated_at ? $testimonialTitle->updated_at->format('d M Y H:i') : 'Belum tersedia' }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Testimonial Items Section --}}
                    @foreach ($testimonials as $testimonial)
                        <div
                            class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                            <div
                                class="w-full px-6 py-4 flex justify-between items-center group transition-all duration-300 hover:bg-white/50">
                                <div class="flex items-center space-x-4 flex-1">
                                    <div>
                                        <h3
                                            class="font-semibold text-[#4D4C4C] transition-all duration-300 group-hover:text-purple-600 group-hover:scale-105">
                                            {{ ucfirst(str_replace('-', ' ', $testimonial->section)) }}
                                        </h3>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <!-- Edit Button -->
                                    <button type="button" onclick="editTestimoni('{{ $testimonial->section }}')"
                                        class="text-blue-600 hover:text-blue-800 transition-all duration-300 p-2 rounded-full hover:bg-blue-100 hover:rotate-6"
                                        title="Edit {{ ucfirst(str_replace('-', ' ', $testimonial->section)) }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <!-- Delete Button -->
                                    <button type="button" onclick="deleteTestimonial('{{ $testimonial->section }}')"
                                        class="text-red-600 hover:text-red-800 transition-all duration-300 p-2 rounded-full hover:bg-red-100 hover:rotate-6"
                                        title="Delete {{ ucfirst(str_replace('-', ' ', $testimonial->section)) }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="px-6 pb-6">
                                <div class="bg-white rounded-lg p-4 border-l-4 border-purple-500">
                                    <div class="flex items-start space-x-4">
                                        {{-- Avatar --}}
                                        <div class="w-12 h-12 flex-shrink-0">
                                            <div
                                                class="w-full h-full bg-purple-500 rounded-full flex items-center justify-center text-white">
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        {{-- Content --}}
                                        <div class="flex-1">
                                            <p class="text-gray-700 leading-relaxed italic mb-2">
                                                "{{ $testimonial->body ?? 'Testimoni belum diatur' }}"</p>
                                            <h4 class="text-gray-700 leading-relaxed font-bold">
                                                {{ $testimonial->title ?? 'Nama testimonial belum diatur' }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-3 text-xs text-[#72C7B4]">
                                    <span>Diperbarui:
                                        {{ $testimonial && $testimonial->updated_at ? $testimonial->updated_at->format('d M Y H:i') : 'Belum tersedia' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Empty State --}}
                    @if ($testimonials->isEmpty())
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada testimonial</h3>
                            <p class="text-gray-500 mb-4">Mulai dengan menambahkan testimonial pertama dari affiliate
                                affiliate</p>
                            <button onclick="addTestimonial()"
                                class="bg-[#785576] text-white px-6 py-3 rounded-lg hover:bg-[#665368] transition duration-300">
                                Add Testimonial Pertama
                            </button>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- Info Section --}}
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mt-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="text-lg font-semibold text-blue-800 mb-2">Tips untuk Testimonial</h4>
                    <ul class="text-blue-700 space-y-1 text-sm">
                        <li>• Gunakan testimonial yang autentik dan nyata</li>
                        <li>• Sertakan nama dan posisi untuk meningkatkan kredibilitas</li>
                        <li>• Testimonial yang spesifik lebih meyakinkan</li>
                        <li>• Foto affiliate membuat testimonial lebih personal</li>
                        <li>• Gunakan tombol "Add Testimonial" untuk menambah testimonial baru</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

@endsection

<script>
    // Edit Testimoni Function
    function editTestimoni(section) {
        window.location.href = '{{ url('admin/affiliate-content') }}/' + section + '/edit';
    }

    // Add Testimonial Function
    function addTestimonial() {
        // Create a form to add new testimonial
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('admin.affiliate-content.store') }}';
        form.style.display = 'none';

        // CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // Section type
        const sectionInput = document.createElement('input');
        sectionInput.type = 'hidden';
        sectionInput.name = 'section_type';
        sectionInput.value = 'testimonial';
        form.appendChild(sectionInput);

        // Type of page
        const typeInput = document.createElement('input');
        typeInput.type = 'hidden';
        typeInput.name = 'type_of_page';
        typeInput.value = 'affiliate';
        form.appendChild(typeInput);

        document.body.appendChild(form);
        form.submit();
    }

    // Delete Testimonial Function
    function deleteTestimonial(section) {
        if (confirm('Apakah Anda yakin ingin menghapus testimonial ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ url('admin/affiliate-content') }}/' + section;
            form.style.display = 'none';

            // CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Method DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();
        }
    }

    // Handle success message
    @if (session('success'))
        alert('{{ session('success') }}');
    @endif
</script>
