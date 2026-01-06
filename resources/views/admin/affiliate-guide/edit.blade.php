@extends('layouts.admin.app')

@section('title', 'Edit Panduan Affiliator')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('admin.affiliate-guide.index', ['section' => $section ?? $affiliateGuide->section_type ?? 'produk']) }}" 
               class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="page-title text-3xl text-[#6C63FF] flex items-center mb-2">
                    <svg class="w-8 h-8 mr-3 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Panduan Affiliator
                </h1>
                <p class="page-subtitle text-[#6C63FF] text-lg ml-11">Update informasi panduan affiliator</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.affiliate-guide.update', $affiliateGuide) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Judul -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Panduan <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       value="{{ old('title', $affiliateGuide->title) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('title') border-red-500 @enderror"
                       placeholder="Contoh: Pilih Produk yang Tepat"
                       required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Konten -->
            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Konten Panduan <span class="text-red-500">*</span>
                </label>
                <textarea name="content" 
                          id="content" 
                          rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('content') border-red-500 @enderror"
                          placeholder="Masukkan deskripsi utama panduan..."
                          required>{{ old('content', $affiliateGuide->content) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Deskripsi singkat yang akan ditampilkan di awal.</p>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Detail Konten - Simplified -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-sm font-medium text-gray-700">
                        Detail Konten (Opsional)
                    </label>
                </div>

                <!-- Section Type Info -->
                <div id="content-type-info" class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-800 font-medium">
                        <span id="section-info-text"></span>
                    </p>
                </div>
                
                <div id="simple-items-container">
                    <!-- Dynamic content based on section type -->
                </div>
                
                <input type="hidden" name="sub_items" id="sub_items_json">
            </div>

            <!-- Section Type -->
            <div class="mb-6">
                <label for="section_type" class="block text-sm font-medium text-gray-700 mb-2">
                    Section <span class="text-red-500">*</span>
                </label>
                <select name="section_type" 
                        id="section_type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('section_type') border-red-500 @enderror"
                        required>
                    <option value="produk" {{ old('section_type', $affiliateGuide->section_type) == 'produk' ? 'selected' : '' }}>Produk</option>
                    <option value="pengajuan" {{ old('section_type', $affiliateGuide->section_type) == 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                    <option value="pengiriman" {{ old('section_type', $affiliateGuide->section_type) == 'pengiriman' ? 'selected' : '' }}>Pengiriman</option>
                    <option value="video" {{ old('section_type', $affiliateGuide->section_type) == 'video' ? 'selected' : '' }}>Video Review</option>
                </select>
                @error('section_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-6">
                <div class="flex items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           id="is_active" 
                           value="1"
                           {{ old('is_active', $affiliateGuide->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Aktifkan panduan ini
                    </label>
                </div>
                <p class="mt-1 text-sm text-gray-500 ml-6">Panduan yang aktif akan ditampilkan di halaman frontend</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-[#785576] text-white font-medium rounded-lg hover:bg-[#634460] transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Panduan
                </button>
                <a href="{{ route('admin.affiliate-guide.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let existingSubItems = @json($affiliateGuide->sub_items ?? []);

// Load existing data and setup form
document.addEventListener('DOMContentLoaded', function() {
    const sectionSelect = document.getElementById('section_type');
    
    // Setup initial content
    setupContentBySection(sectionSelect.value);
    
    // Change handler
    sectionSelect.addEventListener('change', function() {
        if (confirm('Mengubah section akan mereset detail konten. Lanjutkan?')) {
            existingSubItems = [];
            setupContentBySection(this.value);
        } else {
            this.value = this.getAttribute('data-current');
        }
    });
    
    // Store current value
    sectionSelect.setAttribute('data-current', sectionSelect.value);
});

function setupContentBySection(section) {
    const container = document.getElementById('simple-items-container');
    const infoText = document.getElementById('section-info-text');
    
    let html = '';
    let info = '';
    
    switch(section) {
        case 'produk':
            info = '📦 Daftar produk yang tersedia untuk program affiliator';
            html = `
                <div class="space-y-3">
                    <button type="button" onclick="addProductItem()" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Produk
                    </button>
                    <div id="products-list" class="space-y-3"></div>
                </div>
            `;
            break;
            
        case 'pengajuan':
            info = '📝 Panduan langkah-langkah pengajuan dan tips untuk affiliator';
            html = `
                <div class="space-y-4">
                    <!-- Langkah-langkah -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-sm font-medium text-gray-700">Langkah-langkah Pengajuan</label>
                            <button type="button" onclick="addStepItem()" 
                                    class="inline-flex items-center px-3 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Langkah
                            </button>
                        </div>
                        <div id="steps-list" class="space-y-2"></div>
                    </div>
                    
                    <!-- Tips -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-sm font-medium text-gray-700">Tips & Saran</label>
                            <button type="button" onclick="addTipItem()" 
                                    class="inline-flex items-center px-3 py-2 bg-cyan-600 text-white text-sm rounded-lg hover:bg-cyan-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Tips
                            </button>
                        </div>
                        <div id="tips-list" class="space-y-2"></div>
                    </div>
                    
                    <!-- Catatan Penting -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-sm font-medium text-gray-700">Catatan Penting</label>
                            <button type="button" onclick="addNoteItem()" 
                                    class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Catatan
                            </button>
                        </div>
                        <div id="notes-list" class="space-y-2"></div>
                    </div>
                </div>
            `;
            break;
            
        case 'pengiriman':
            info = '📦 Informasi tentang pengiriman produk gratis';
            html = `
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-sm font-medium text-gray-700">Poin-poin Pengiriman</label>
                            <button type="button" onclick="addCheckItem()" 
                                    class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Poin
                            </button>
                        </div>
                        <div id="checks-list" class="space-y-2"></div>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-sm font-medium text-gray-700">Instruksi Tambahan</label>
                            <button type="button" onclick="addInstructionItem()" 
                                    class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Instruksi
                            </button>
                        </div>
                        <div id="instructions-list" class="space-y-2"></div>
                    </div>
                </div>
            `;
            break;
            
        case 'video':
            info = '🎥 Panduan pembuatan dan upload video review';
            html = `
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-sm font-medium text-gray-700">Syarat Video</label>
                            <button type="button" onclick="addRequirementItem()" 
                                    class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Syarat
                            </button>
                        </div>
                        <div id="requirements-list" class="space-y-2"></div>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-sm font-medium text-gray-700">Langkah Upload</label>
                            <button type="button" onclick="addVideoStepItem()" 
                                    class="inline-flex items-center px-3 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Langkah
                            </button>
                        </div>
                        <div id="video-steps-list" class="space-y-2"></div>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-sm font-medium text-gray-700">Tips Video</label>
                            <button type="button" onclick="addVideoTipItem()" 
                                    class="inline-flex items-center px-3 py-2 bg-cyan-600 text-white text-sm rounded-lg hover:bg-cyan-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Tips
                            </button>
                        </div>
                        <div id="video-tips-list" class="space-y-2"></div>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-sm font-medium text-gray-700">Peringatan</label>
                            <button type="button" onclick="addWarningItem()" 
                                    class="inline-flex items-center px-3 py-2 bg-amber-600 text-white text-sm rounded-lg hover:bg-amber-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Peringatan
                            </button>
                        </div>
                        <div id="warnings-list" class="space-y-2"></div>
                    </div>
                </div>
            `;
            break;
    }
    
    infoText.textContent = info;
    container.innerHTML = html;
    
    // Load existing data
    loadExistingData(section);
}

function loadExistingData(section) {
    if (!existingSubItems || existingSubItems.length === 0) return;
    
    existingSubItems.forEach(item => {
        if (item.type === 'product') {
            addProductItem(item);
        } else if (item.step) {
            addStepItem(item);
        } else if (item.type === 'tip') {
            if (section === 'video') {
                addVideoTipItem(item);
            } else {
                addTipItem(item);
            }
        } else if (item.type === 'note' || item.type === 'info' || item.type === 'instruction') {
            if (section === 'pengiriman') {
                addInstructionItem(item);
            } else {
                addNoteItem(item);
            }
        } else if (item.check) {
            addCheckItem(item);
        } else if (item.requirement) {
            addRequirementItem(item);
        } else if (item.type === 'warning') {
            addWarningItem(item);
        }
    });
}

// Produk Section
function addProductItem(data = null) {
    const container = document.getElementById('products-list');
    const id = 'product-' + Date.now();
    
    const div = document.createElement('div');
    div.id = id;
    div.className = 'flex gap-2 items-start p-3 bg-blue-50 rounded-lg border border-blue-200';
    div.innerHTML = `
        <div class="flex-1 grid grid-cols-3 gap-2">
            <input type="text" class="px-3 py-2 border border-gray-300 rounded-lg text-sm" 
                   placeholder="Nama produk" value="${data?.name || ''}" onchange="collectData()">
            <input type="text" class="px-3 py-2 border border-gray-300 rounded-lg text-sm" 
                   placeholder="Deskripsi" value="${data?.description || ''}" onchange="collectData()">
            <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm" onchange="collectData()">
                <option value="blue" ${data?.color === 'blue' ? 'selected' : ''}>Biru</option>
                <option value="purple" ${data?.color === 'purple' ? 'selected' : ''}>Ungu</option>
            </select>
        </div>
        <button type="button" onclick="removeItem('${id}')" class="text-red-600 hover:text-red-800 p-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    container.appendChild(div);
    collectData();
}

// Pengajuan Section
function addStepItem(data = null) {
    const container = document.getElementById('steps-list');
    const id = 'step-' + Date.now();
    const stepNumber = container.children.length + 1;
    
    const div = document.createElement('div');
    div.id = id;
    div.className = 'flex gap-2 items-start';
    div.innerHTML = `
        <div class="bg-purple-100 text-purple-700 w-8 h-8 rounded-full flex items-center justify-center font-bold flex-shrink-0 mt-1">
            ${data?.step || stepNumber}
        </div>
        <textarea class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" rows="2" 
                  placeholder="Deskripsi langkah..." onchange="collectData()">${data?.text || ''}</textarea>
        <button type="button" onclick="removeItem('${id}')" class="text-red-600 hover:text-red-800 p-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    container.appendChild(div);
    collectData();
}

function addTipItem(data = null) {
    const container = document.getElementById('tips-list');
    const id = 'tip-' + Date.now();
    
    const div = document.createElement('div');
    div.id = id;
    div.className = 'flex gap-2 items-start';
    div.innerHTML = `
        <div class="text-2xl flex-shrink-0">💡</div>
        <textarea class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" rows="2" 
                  placeholder="Tulis tips di sini..." onchange="collectData()">${data?.text || ''}</textarea>
        <button type="button" onclick="removeItem('${id}')" class="text-red-600 hover:text-red-800 p-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    container.appendChild(div);
    collectData();
}

function addNoteItem(data = null) {
    const container = document.getElementById('notes-list');
    const id = 'note-' + Date.now();
    
    const div = document.createElement('div');
    div.id = id;
    div.className = 'p-3 bg-indigo-50 rounded-lg border border-indigo-200';
    div.innerHTML = `
        <div class="flex justify-between items-start mb-2">
            <input type="text" class="flex-1 px-3 py-1 border border-gray-300 rounded-lg text-sm font-medium" 
                   placeholder="Judul (opsional)" value="${data?.title || ''}" onchange="collectData()">
            <button type="button" onclick="removeItem('${id}')" class="text-red-600 hover:text-red-800 p-1 ml-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" rows="2" 
                  placeholder="Isi catatan..." onchange="collectData()">${data?.text || ''}</textarea>
    `;
    container.appendChild(div);
    collectData();
}

// Pengiriman Section
function addCheckItem(data = null) {
    const container = document.getElementById('checks-list');
    const id = 'check-' + Date.now();
    
    const div = document.createElement('div');
    div.id = id;
    div.className = 'flex gap-2 items-start';
    div.innerHTML = `
        <div class="bg-green-100 text-green-700 w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">✓</div>
        <textarea class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" rows="2" 
                  placeholder="Tulis poin di sini..." onchange="collectData()">${data?.text || ''}</textarea>
        <button type="button" onclick="removeItem('${id}')" class="text-red-600 hover:text-red-800 p-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    container.appendChild(div);
    collectData();
}

function addInstructionItem(data = null) {
    const container = document.getElementById('instructions-list');
    const id = 'instruction-' + Date.now();
    
    const div = document.createElement('div');
    div.id = id;
    div.className = 'flex gap-2 items-start';
    div.innerHTML = `
        <div class="text-2xl flex-shrink-0">ℹ️</div>
        <textarea class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" rows="2" 
                  placeholder="Tulis instruksi di sini..." onchange="collectData()">${data?.text || ''}</textarea>
        <button type="button" onclick="removeItem('${id}')" class="text-red-600 hover:text-red-800 p-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    container.appendChild(div);
    collectData();
}

// Video Section
function addRequirementItem(data = null) {
    const container = document.getElementById('requirements-list');
    const id = 'req-' + Date.now();
    
    const div = document.createElement('div');
    div.id = id;
    div.className = 'flex gap-2 items-start p-2 bg-red-50 rounded-lg border border-red-200';
    div.innerHTML = `
        <div class="text-xl flex-shrink-0 mt-1">📌</div>
        <textarea class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" rows="2" 
                  placeholder="Tulis syarat di sini..." onchange="collectData()">${data?.text || ''}</textarea>
        <label class="flex items-center gap-1 text-xs whitespace-nowrap mt-2">
            <input type="checkbox" ${data?.bold ? 'checked' : ''} onchange="collectData()">
            <span>Bold</span>
        </label>
        <button type="button" onclick="removeItem('${id}')" class="text-red-600 hover:text-red-800 p-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    container.appendChild(div);
    collectData();
}

function addVideoStepItem(data = null) {
    const container = document.getElementById('video-steps-list');
    const id = 'vstep-' + Date.now();
    const stepNumber = container.children.length + 1;
    
    const div = document.createElement('div');
    div.id = id;
    div.className = 'flex gap-2 items-start';
    div.innerHTML = `
        <div class="bg-purple-100 text-purple-700 w-8 h-8 rounded-full flex items-center justify-center font-bold flex-shrink-0 mt-1">
            ${data?.step || stepNumber}
        </div>
        <textarea class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" rows="2" 
                  placeholder="Deskripsi langkah..." onchange="collectData()">${data?.text || ''}</textarea>
        <button type="button" onclick="removeItem('${id}')" class="text-red-600 hover:text-red-800 p-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    container.appendChild(div);
    collectData();
}

function addVideoTipItem(data = null) {
    const container = document.getElementById('video-tips-list');
    const id = 'vtip-' + Date.now();
    
    const div = document.createElement('div');
    div.id = id;
    div.className = 'flex gap-2 items-start';
    div.innerHTML = `
        <div class="text-2xl flex-shrink-0">💡</div>
        <textarea class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" rows="2" 
                  placeholder="Tulis tips di sini..." onchange="collectData()">${data?.text || ''}</textarea>
        <button type="button" onclick="removeItem('${id}')" class="text-red-600 hover:text-red-800 p-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    container.appendChild(div);
    collectData();
}

function addWarningItem(data = null) {
    const container = document.getElementById('warnings-list');
    const id = 'warn-' + Date.now();
    
    const div = document.createElement('div');
    div.id = id;
    div.className = 'p-3 bg-amber-50 rounded-lg border border-amber-200';
    div.innerHTML = `
        <div class="flex justify-between items-start mb-2">
            <input type="text" class="flex-1 px-3 py-1 border border-gray-300 rounded-lg text-sm font-medium" 
                   placeholder="Judul peringatan (opsional)" value="${data?.title || ''}" onchange="collectData()">
            <button type="button" onclick="removeItem('${id}')" class="text-red-600 hover:text-red-800 p-1 ml-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" rows="2" 
                  placeholder="Isi peringatan..." onchange="collectData()">${data?.text || ''}</textarea>
    `;
    container.appendChild(div);
    collectData();
}

// Common functions
function removeItem(id) {
    document.getElementById(id).remove();
    collectData();
}

function collectData() {
    const section = document.getElementById('section_type').value;
    const data = [];
    
    // Collect based on section
    if (section === 'produk') {
        document.querySelectorAll('#products-list > div').forEach(item => {
            const inputs = item.querySelectorAll('input, select');
            if (inputs[0].value.trim()) {
                data.push({
                    type: 'product',
                    name: inputs[0].value,
                    description: inputs[1].value,
                    color: inputs[2].value
                });
            }
        });
    } else if (section === 'pengajuan') {
        // Steps
        document.querySelectorAll('#steps-list > div').forEach((item, idx) => {
            const textarea = item.querySelector('textarea');
            if (textarea.value.trim()) {
                data.push({
                    step: (idx + 1).toString(),
                    text: textarea.value,
                    color: 'purple'
                });
            }
        });
        // Tips
        document.querySelectorAll('#tips-list > div').forEach(item => {
            const textarea = item.querySelector('textarea');
            if (textarea.value.trim()) {
                data.push({
                    type: 'tip',
                    text: textarea.value
                });
            }
        });
        // Notes
        document.querySelectorAll('#notes-list > div').forEach(item => {
            const inputs = item.querySelectorAll('input, textarea');
            if (inputs[1].value.trim()) {
                const noteData = {
                    type: 'note',
                    text: inputs[1].value
                };
                if (inputs[0].value.trim()) {
                    noteData.title = inputs[0].value;
                }
                data.push(noteData);
            }
        });
    } else if (section === 'pengiriman') {
        // Checks
        document.querySelectorAll('#checks-list > div').forEach(item => {
            const textarea = item.querySelector('textarea');
            if (textarea.value.trim()) {
                data.push({
                    check: true,
                    text: textarea.value
                });
            }
        });
        // Instructions
        document.querySelectorAll('#instructions-list > div').forEach(item => {
            const textarea = item.querySelector('textarea');
            if (textarea.value.trim()) {
                data.push({
                    type: 'instruction',
                    text: textarea.value
                });
            }
        });
    } else if (section === 'video') {
        // Requirements
        document.querySelectorAll('#requirements-list > div').forEach(item => {
            const textarea = item.querySelector('textarea');
            const checkbox = item.querySelector('input[type="checkbox"]');
            if (textarea.value.trim()) {
                const reqData = {
                    requirement: true,
                    text: textarea.value
                };
                if (checkbox.checked) {
                    reqData.bold = true;
                }
                data.push(reqData);
            }
        });
        // Video Steps
        document.querySelectorAll('#video-steps-list > div').forEach((item, idx) => {
            const textarea = item.querySelector('textarea');
            if (textarea.value.trim()) {
                data.push({
                    step: (idx + 1).toString(),
                    text: textarea.value,
                    color: 'red'
                });
            }
        });
        // Video Tips
        document.querySelectorAll('#video-tips-list > div').forEach(item => {
            const textarea = item.querySelector('textarea');
            if (textarea.value.trim()) {
                data.push({
                    type: 'tip',
                    text: textarea.value
                });
            }
        });
        // Warnings
        document.querySelectorAll('#warnings-list > div').forEach(item => {
            const inputs = item.querySelectorAll('input, textarea');
            if (inputs[1].value.trim()) {
                const warnData = {
                    type: 'warning',
                    text: inputs[1].value
                };
                if (inputs[0].value.trim()) {
                    warnData.title = inputs[0].value;
                    warnData.important = true;
                }
                data.push(warnData);
            }
        });
    }
    
    document.getElementById('sub_items_json').value = JSON.stringify(data);
}

// Update on form submit
document.querySelector('form').addEventListener('submit', function(e) {
    collectData();
});
</script>
@endsection
