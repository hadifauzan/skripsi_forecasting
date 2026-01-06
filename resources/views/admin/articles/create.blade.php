@extends('layouts.admin.app')

@section('title', 'Tambah Artikel')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="p-6">
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

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Form Header --}}
            <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-[#785576] p-6">
                    <div class="flex items-center text-white">
                        <div>
                            <h3 class="text-xl font-semibold">Tambah Artikel Baru</h3>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Basic Information --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Title --}}
                        <div class="lg:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Artikel <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="Masukkan judul artikel...">
                        </div>

                        {{-- Category --}}
                        <div>
                            <label for="section" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select id="section" name="section" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" {{ old('section') == $category->slug ? 'selected' : '' }}>
                                        {{ $category->name_category_article }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status Publikasi
                            </label>
                            <div class="flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="status" value="1" {{ old('status') ? 'checked' : '' }}
                                        class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                                    <span class="ml-2 text-sm text-gray-700">Aktif (Ditampilkan di Website)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Thumbnail Upload --}}
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            Thumbnail Artikel
                        </label>
                        
                        {{-- Image Preview Box --}}
                        <div id="new-image-preview-container" class="bg-gradient-to-br from-gray-50 to-slate-50 p-6 rounded-xl border-2 border-dashed border-gray-300 mb-4">
                            {{-- Empty State (shown when no image) --}}
                            <div id="preview-empty-state" class="text-center">
                                <div class="flex items-center justify-center mb-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-2xl">🖼️</span>
                                        <div>
                                            <p class="text-sm font-bold text-gray-700">Preview Thumbnail</p>
                                            <p class="text-xs text-gray-500">Belum ada gambar dipilih</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white p-2 rounded-lg shadow-sm border-2 border-dashed border-gray-200">
                                    <div class="w-full max-w-2xl mx-auto h-80 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <div class="text-center">
                                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <p class="text-gray-400 text-sm">Upload gambar untuk melihat preview</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Preview with Image (shown when image uploaded) --}}
                            <div id="preview-with-image" class="hidden">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-2xl">🖼️</span>
                                        <div>
                                            <p class="text-sm font-bold text-green-800">Preview Thumbnail</p>
                                            <p class="text-xs text-green-600">Gambar yang akan diupload</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-white bg-green-600 px-3 py-1.5 rounded-full font-semibold shadow-md">✓ Siap Upload</span>
                                </div>
                                <div class="relative bg-gray-50 p-3 rounded-lg shadow-md border border-gray-200">
                                    <img id="new-preview-img" src="" alt="Preview" 
                                        class="w-full max-w-2xl mx-auto h-80 object-contain rounded-lg bg-white">
                                    <button type="button" onclick="window.clearNewPreview()" 
                                            class="absolute top-4 right-4 bg-red-500 hover:bg-red-600 text-white rounded-full p-2.5 shadow-xl transition-all duration-200 hover:scale-110"
                                            title="Hapus dan pilih gambar lain">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="mt-4 bg-white/60 backdrop-blur-sm p-3 rounded-lg border border-green-200">
                                    <div id="file-info" class="mb-2 text-sm"></div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <p class="text-sm text-green-700 font-medium">Gambar siap disimpan saat form disubmit</p>
                                        </div>
                                        <button type="button" onclick="window.clearNewPreview()" 
                                                class="text-sm text-red-600 hover:text-red-800 font-medium hover:underline transition-colors">
                                            🔄 Ganti Gambar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Upload Zone --}}
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition-all duration-200" id="drop-zone">
                            <div class="space-y-1 text-center">
                                {{-- Loading State --}}
                                <div id="upload-loading" class="hidden">
                                    <div class="animate-spin mx-auto h-8 w-8 border-4 border-purple-500 border-t-transparent rounded-full"></div>
                                    <p class="text-sm text-purple-600 mt-2">Memproses gambar...</p>
                                </div>
                                
                                <div id="upload-placeholder">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                            <span>Upload thumbnail</span>
                                            <input id="image" name="image" type="file" class="sr-only" accept="image/jpeg,image/png,image/jpg,image/gif">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF maksimal 2MB</p>
                                </div>
                                <div id="upload-error" class="hidden text-sm text-red-600"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Content Editor --}}
                    <div>
                        <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                            Isi Artikel <span class="text-red-500">*</span>
                        </label>
                        
                        <!-- Textarea untuk Isi Artikel -->
                        <textarea id="body" name="body" rows="15" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="Tulis isi artikel di sini...">{{ old('body') }}</textarea>
                        
                        <!-- Text Counter -->
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-xs text-gray-500">Tulis konten artikel Anda di sini</p>
                            <span id="char-count" class="text-xs text-gray-400">0 karakter</span>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.articles.index') }}" 
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                            Kembali
                        </a>
                        
                        <div class="flex space-x-3">
                            <button type="submit" name="action" value="publish" 
                                class="px-6 py-2 bg-[#785576] hover:bg-[#614DAC] text-white rounded-lg transition-colors duration-200">
                                tambahkan artikel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Image Preview Script - Load immediately --}}
    <script>
    // ============================================================================
    // GLOBAL FUNCTIONS - Defined in window scope for inline event handlers
    // ============================================================================

    // Image preview function
    window.previewNewImage = function(input) {
        console.log('========================================');
        console.log('🔍 previewNewImage called');
        console.log('📁 Input element:', input);
        console.log('📁 Files:', input.files);
        console.log('📁 Files length:', input.files ? input.files.length : 0);
        
        const errorDiv = document.getElementById('upload-error');
        const newImagePreviewContainer = document.getElementById('new-image-preview-container');
        const uploadPlaceholder = document.getElementById('upload-placeholder');
        const newPreviewImg = document.getElementById('new-preview-img');
        const previewEmptyState = document.getElementById('preview-empty-state');
        const previewWithImage = document.getElementById('preview-with-image');
        
        console.log('🔍 Elements found:');
        console.log('  - errorDiv:', !!errorDiv);
        console.log('  - newImagePreviewContainer:', !!newImagePreviewContainer);
        console.log('  - uploadPlaceholder:', !!uploadPlaceholder);
        console.log('  - newPreviewImg:', !!newPreviewImg);
        console.log('  - previewEmptyState:', !!previewEmptyState);
        console.log('  - previewWithImage:', !!previewWithImage);
        
        // Reset error state
        errorDiv.classList.add('hidden');
        errorDiv.textContent = '';
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            console.log('✅ File selected:');
            console.log('  - Name:', file.name);
            console.log('  - Type:', file.type);
            console.log('  - Size:', file.size, 'bytes');
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                console.error('❌ Invalid file type:', file.type);
                window.showError('❌ Format file tidak didukung. Silakan pilih file JPG, PNG, atau GIF.');
                input.value = '';
                return;
            }
            
            // Validate file size (2MB)
            if (file.size > 2097152) {
                console.error('❌ File too large:', file.size);
                window.showError('❌ Ukuran file terlalu besar. Maksimal 2MB.');
                input.value = '';
                return;
            }
            
            console.log('✅ File validation passed');
            
            // Show loading
            const uploadLoading = document.getElementById('upload-loading');
            uploadPlaceholder.classList.add('hidden');
            uploadLoading.classList.remove('hidden');
            console.log('⏳ Loading state shown');
            
            const reader = new FileReader();
            console.log('📖 FileReader created');
            
            reader.onload = function(e) {
                console.log('========================================');
                console.log('✅ FileReader.onload TRIGGERED!');
                console.log('📊 Data URL length:', e.target.result.length);
                console.log('📊 Data URL preview:', e.target.result.substring(0, 100));
                
                // Hide loading
                uploadLoading.classList.add('hidden');
                console.log('✅ Loading hidden');
                
                // Update container style
                console.log('🎨 Updating container styles...');
                newImagePreviewContainer.classList.remove('from-gray-50', 'to-slate-50', 'border-gray-300', 'border-dashed');
                newImagePreviewContainer.classList.add('from-green-50', 'to-emerald-50', 'border-green-400', 'shadow-lg');
                console.log('✅ Container styles updated');
                
                // Set image source - THIS IS THE KEY PART
                console.log('🖼️ Setting image src...');
                console.log('  - Before src:', newPreviewImg.src);
                newPreviewImg.src = e.target.result;
                newPreviewImg.alt = file.name;
                console.log('  - After src length:', newPreviewImg.src.length);
                console.log('  - Image element:', newPreviewImg);
                console.log('  - Parent element:', newPreviewImg.parentElement);
                console.log('  - Parent visible:', !newPreviewImg.parentElement.parentElement.classList.contains('hidden'));
                
                // Confirm image loaded
                newPreviewImg.onload = function() {
                    console.log('========================================');
                    console.log('🎉 IMAGE LOADED AND DISPLAYED ON SCREEN!');
                    console.log('📐 Natural dimensions:', this.naturalWidth, 'x', this.naturalHeight);
                    console.log('📏 Display dimensions:', this.width, 'x', this.height);
                    console.log('🎨 Classes:', this.className);
                    console.log('✅ Image is visible:', this.offsetWidth > 0 && this.offsetHeight > 0);
                };
                
                newPreviewImg.onerror = function(error) {
                    console.error('========================================');
                    console.error('❌ ERROR: Failed to display image!');
                    console.error('Error details:', error);
                };
                
                // File info
                const fileInfoEl = document.getElementById('file-info');
                if (fileInfoEl) {
                    const fileSizeKB = (file.size / 1024).toFixed(2);
                    fileInfoEl.innerHTML = `
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-600 truncate max-w-xs" title="${file.name}">📄 ${file.name}</span>
                            <span class="text-gray-500 ml-2">${fileSizeKB} KB</span>
                        </div>
                    `;
                    console.log('✅ File info updated');
                }
                
                // Show preview - CRITICAL STEP
                console.log('👁️ Toggling visibility...');
                console.log('  - Before: empty state hidden?', previewEmptyState.classList.contains('hidden'));
                console.log('  - Before: preview visible?', !previewWithImage.classList.contains('hidden'));
                
                previewEmptyState.classList.add('hidden');
                previewWithImage.classList.remove('hidden');
                
                console.log('  - After: empty state hidden?', previewEmptyState.classList.contains('hidden'));
                console.log('  - After: preview visible?', !previewWithImage.classList.contains('hidden'));
                console.log('✅ Visibility toggled!');
                
                // Force a reflow
                void newPreviewImg.offsetWidth;
                console.log('✅ Forced reflow');
                
                // Scroll to view
                setTimeout(() => {
                    newImagePreviewContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    console.log('📍 Scrolled to preview');
                    console.log('========================================');
                }, 150);
            };
            
            reader.onerror = function(error) {
                console.error('========================================');
                console.error('❌ FileReader error:', error);
                uploadLoading.classList.add('hidden');
                uploadPlaceholder.classList.remove('hidden');
                window.showError('Terjadi error saat membaca file.');
                input.value = '';
            };
            
            console.log('📖 Starting to read file...');
            reader.readAsDataURL(file);
            console.log('📖 Read initiated');
        } else {
            console.error('❌ No file selected or files array is empty');
        }
    };

    // Clear preview function
    window.clearNewPreview = function() {
        console.log('🗑️ Clearing preview...');
        
        const imageInput = document.getElementById('image');
        const newImagePreviewContainer = document.getElementById('new-image-preview-container');
        const uploadPlaceholder = document.getElementById('upload-placeholder');
        const uploadLoading = document.getElementById('upload-loading');
        const errorDiv = document.getElementById('upload-error');
        const newPreviewImg = document.getElementById('new-preview-img');
        const fileInfoEl = document.getElementById('file-info');
        const previewEmptyState = document.getElementById('preview-empty-state');
        const previewWithImage = document.getElementById('preview-with-image');
        
        imageInput.value = '';
        newPreviewImg.src = '';
        
        newImagePreviewContainer.classList.remove('from-green-50', 'to-emerald-50', 'border-green-400', 'shadow-lg');
        newImagePreviewContainer.classList.add('from-gray-50', 'to-slate-50', 'border-gray-300', 'border-dashed');
        
        previewWithImage.classList.add('hidden');
        previewEmptyState.classList.remove('hidden');
        
        uploadLoading.classList.add('hidden');
        uploadPlaceholder.classList.remove('hidden');
        
        if (fileInfoEl) fileInfoEl.innerHTML = '';
        
        errorDiv.classList.add('hidden');
        errorDiv.textContent = '';
        
        console.log('✅ Preview cleared');
    };

    // Show error function
    window.showError = function(message) {
        const errorDiv = document.getElementById('upload-error');
        const uploadPlaceholder = document.getElementById('upload-placeholder');
        
        errorDiv.textContent = message;
        errorDiv.classList.remove('hidden');
        uploadPlaceholder.classList.remove('hidden');
        
        setTimeout(() => {
            errorDiv.classList.add('hidden');
        }, 5000);
    };

    // ============================================================================
    // DOM READY
    // ============================================================================

    document.addEventListener('DOMContentLoaded', function() {
        console.log('✅ DOM Ready');
        
        // Character counter
        const bodyTextarea = document.getElementById('body');
        const charCountEl = document.getElementById('char-count');
        
        function updateCharCount() {
            const charCount = bodyTextarea.value.trim().length;
            charCountEl.textContent = charCount + ' karakter';
            
            if (charCount === 0) {
                charCountEl.classList.remove('text-green-600');
                charCountEl.classList.add('text-gray-400');
            } else {
                charCountEl.classList.remove('text-gray-400');
                charCountEl.classList.add('text-green-600');
            }
        }
        
        bodyTextarea.addEventListener('input', updateCharCount);
        updateCharCount();
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const content = bodyTextarea.value.trim();
            
            if (content.length === 0) {
                e.preventDefault();
                alert('⚠️ Isi artikel tidak boleh kosong!');
                bodyTextarea.focus();
                return false;
            }
            
            return true;
        });
        
        // File input listener
        const imageInput = document.getElementById('image');
        imageInput.addEventListener('change', function() {
            console.log('📸 File input change event triggered');
            window.previewNewImage(this);
        });
        
        // Drag and drop
        const dropZone = document.getElementById('drop-zone');
        
        if (dropZone) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, function() {
                    dropZone.classList.add('border-purple-400', 'bg-purple-50');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, function() {
                    dropZone.classList.remove('border-purple-400', 'bg-purple-50');
                }, false);
            });

            dropZone.addEventListener('drop', function(e) {
                const files = e.dataTransfer.files;
                
                if (files.length > 0) {
                    imageInput.files = files;
                    window.previewNewImage(imageInput);
                }
            }, false);
        }
        
        console.log('✅ All event listeners registered');
        console.log('✅ previewNewImage available:', typeof window.previewNewImage === 'function');
    });
    </script>
@endsection
