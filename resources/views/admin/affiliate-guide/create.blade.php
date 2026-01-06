@extends('layouts.admin.app')

@section('title', 'Tambah Panduan Affiliator')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('admin.affiliate-guide.index', ['section' => $section ?? 'produk']) }}" 
               class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="page-title text-3xl text-[#6C63FF] flex items-center mb-2">
                    <svg class="w-8 h-8 mr-3 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Panduan Affiliator
                </h1>
                <p class="page-subtitle text-[#6C63FF] text-lg ml-11">Buat langkah panduan baru untuk affiliator</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.affiliate-guide.store') }}" method="POST">
            @csrf

            <!-- Judul -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Panduan <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       value="{{ old('title') }}"
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
                          required>{{ old('content') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Deskripsi singkat yang akan ditampilkan di awal.</p>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sub Items - Visual Editor -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-sm font-medium text-gray-700">
                        Detail Konten (Opsional)
                    </label>
                    <button type="button" onclick="addSubItem()" 
                        class="inline-flex items-center px-4 py-2 bg-[#785576] text-white text-sm rounded-lg hover:bg-[#634460] transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Item Detail
                    </button>
                </div>
                
                <div id="sub-items-container" class="space-y-4">
                    <!-- Sub items will be added here dynamically -->
                </div>
                
                <input type="hidden" name="sub_items" id="sub_items_json">
                
                <p class="mt-2 text-sm text-gray-500">
                    💡 Tambahkan detail konten seperti produk, langkah-langkah, tips, atau informasi penting lainnya.
                </p>
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
                    <option value="produk" {{ old('section_type', 'produk') == 'produk' ? 'selected' : '' }}>Produk</option>
                    <option value="pengajuan" {{ old('section_type') == 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                    <option value="pengiriman" {{ old('section_type') == 'pengiriman' ? 'selected' : '' }}>Pengiriman</option>
                    <option value="video" {{ old('section_type') == 'video' ? 'selected' : '' }}>Video Review</option>
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
                           {{ old('is_active', true) ? 'checked' : '' }}
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
                    Simpan Panduan
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
function showTemplates() {
    document.getElementById('templates-modal').classList.remove('hidden');
}

function hideTemplates() {
    document.getElementById('templates-modal').classList.add('hidden');
}

function useTemplate(type) {
    const templates = {
        'product': `[
  {"type": "product", "name": "Gentle Baby 10ml", "description": "Produk perawatan bayi berkualitas tinggi", "color": "blue"},
  {"type": "product", "name": "Healo 10ml", "description": "Solusi kesehatan keluarga", "color": "purple"}
]`,
        'warning': `[
  {"type": "warning", "text": "Hanya produk dengan ukuran 10ml yang dapat diajukan", "important": true},
  {"type": "warning", "text": "Produk harus memiliki status Tersedia di sistem"}
]`,
        'steps': `[
  {"step": 1, "text": "Login ke akun affiliator Anda"},
  {"step": 2, "text": "Pilih menu Pengajuan Saya"},
  {"step": 3, "text": "Klik tombol Ajukan Produk Baru"}
]`,
</div>

<script>
let subItemCounter = 0;

function addSubItem(existingData = null) {
    subItemCounter++;
    const container = document.getElementById('sub-items-container');
    const itemId = 'sub-item-' + subItemCounter;
    
    const itemDiv = document.createElement('div');
    itemDiv.id = itemId;
    itemDiv.className = 'border border-gray-300 rounded-lg p-4 bg-gray-50';
    
    const itemType = 'product';
    
    itemDiv.innerHTML = `
        <div class="flex items-center justify-between mb-3">
            <label class="block text-sm font-medium text-gray-700">Item Detail #${subItemCounter}</label>
            <button type="button" onclick="removeSubItem('${itemId}')" 
                class="text-red-600 hover:text-red-800 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
        
        <div class="grid gap-3">
            <div>
                <label class="block text-sm text-gray-700 mb-1">Tipe Konten</label>
                <select onchange="updateSubItemFields('${itemId}', this.value)" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm type-select">
                    <option value="product">📦 Produk</option>
                    <option value="warning">⚠️ Peringatan</option>
                    <option value="steps">📋 Langkah-langkah</option>
                    <option value="check">✓ Checklist</option>
                    <option value="tip">💡 Tips</option>
                    <option value="benefit">🎁 Keuntungan</option>
                    <option value="requirement">📌 Persyaratan</option>
                    <option value="note">ℹ️ Catatan</option>
                    <option value="highlight">✨ Highlight</option>
                </select>
            </div>
            
            <div class="fields-container">
                <!-- Dynamic fields will be inserted here -->
            </div>
        </div>
    `;
    
    container.appendChild(itemDiv);
    updateSubItemFields(itemId, itemType, null);
}

function updateSubItemFields(itemId, type, existingData = null) {
    const itemDiv = document.getElementById(itemId);
    const fieldsContainer = itemDiv.querySelector('.fields-container');
    
    let fieldsHTML = '';
    
    switch(type) {
        case 'product':
            fieldsHTML = `
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Nama Produk</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" 
                        placeholder="Contoh: Gentle Baby 10ml" value="" data-field="name">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Deskripsi Produk</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" 
                        placeholder="Contoh: Produk perawatan bayi berkualitas tinggi" value="" data-field="description">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Warna</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" data-field="color">
                        <option value="blue">Biru</option>
                        <option value="purple">Ungu</option>
                        <option value="green">Hijau</option>
                        <option value="red">Merah</option>
                        <option value="yellow">Kuning</option>
                    </select>
                </div>
            `;
            break;
            
        case 'warning':
            fieldsHTML = `
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Teks Peringatan</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" rows="2" 
                        placeholder="Contoh: Hanya produk dengan ukuran 10ml yang dapat diajukan" data-field="text"></textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" class="w-4 h-4 text-purple-600" data-field="important">
                    <label class="ml-2 text-sm text-gray-700">Tandai sebagai penting</label>
                </div>
            `;
            break;
            
        case 'steps':
            fieldsHTML = `
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Nomor Langkah</label>
                    <input type="number" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" 
                        placeholder="1" value="" data-field="step">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Deskripsi Langkah</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" rows="2" 
                        placeholder="Contoh: Login ke akun affiliator Anda" data-field="text"></textarea>
                </div>
            `;
            break;
            
        case 'check':
            fieldsHTML = `
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Teks Checklist</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" rows="2" 
                        placeholder="Contoh: Produk telah dikonfirmasi tersedia" data-field="text"></textarea>
                </div>
            `;
            break;
            
        case 'tip':
            fieldsHTML = `
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Teks Tips</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" rows="2" 
                        placeholder="Contoh: Pastikan koneksi internet stabil saat upload video" data-field="text"></textarea>
                </div>
            `;
            break;
            
        case 'benefit':
            fieldsHTML = `
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Emoji</label>
                    <input type="text" maxlength="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" 
                        placeholder="🎁" value="" data-field="emoji">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Judul Keuntungan</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" 
                        placeholder="Contoh: Produk Gratis" value="" data-field="title">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Deskripsi</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" rows="2" 
                        placeholder="Contoh: Dapatkan produk berkualitas tinggi tanpa biaya" data-field="text"></textarea>
                </div>
            `;
            break;
            
        case 'requirement':
            fieldsHTML = `
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Teks Persyaratan</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" rows="2" 
                        placeholder="Contoh: Durasi video minimal 30 detik" data-field="text"></textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" class="w-4 h-4 text-purple-600" data-field="bold">
                    <label class="ml-2 text-sm text-gray-700">Cetak tebal</label>
                </div>
            `;
            break;
            
        case 'note':
            fieldsHTML = `
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Judul (Opsional)</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" 
                        placeholder="Contoh: Catatan Penting" value="" data-field="title">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Teks Catatan</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" rows="2" 
                        placeholder="Contoh: Pastikan semua data sudah terisi dengan benar" data-field="text"></textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" class="w-4 h-4 text-purple-600" data-field="important">
                    <label class="ml-2 text-sm text-gray-700">Tandai sebagai penting</label>
                </div>
            `;
            break;
            
        case 'highlight':
            fieldsHTML = `
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Judul Highlight</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" 
                        placeholder="Contoh: Komisi Hingga Rp 100.000" value="" data-field="title">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Teks</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" rows="2" 
                        placeholder="Contoh: Untuk setiap video review yang disetujui!" data-field="text"></textarea>
                </div>
            `;
            break;
    }
    
    fieldsContainer.innerHTML = fieldsHTML;
    
    // Add event listeners to update JSON
    fieldsContainer.querySelectorAll('input, textarea, select').forEach(field => {
        field.addEventListener('input', updateSubItemsJSON);
        field.addEventListener('change', updateSubItemsJSON);
    });
    
    updateSubItemsJSON();
}

function removeSubItem(itemId) {
    const item = document.getElementById(itemId);
    if (item && confirm('Hapus item detail ini?')) {
        item.remove();
        updateSubItemsJSON();
    }
}

function updateSubItemsJSON() {
    const container = document.getElementById('sub-items-container');
    const items = container.querySelectorAll('[id^="sub-item-"]');
    const data = [];
    
    items.forEach(item => {
        const typeSelect = item.querySelector('.type-select');
        const type = typeSelect.value;
        const fields = item.querySelectorAll('[data-field]');
        
        let itemData = {};
        
        // Add type for most items
        if (['product', 'warning', 'tip', 'benefit', 'note', 'highlight'].includes(type)) {
            itemData.type = type;
        }
        
        // Add requirement flag
        if (type === 'requirement') {
            itemData.requirement = true;
        }
        
        // Add check flag
        if (type === 'check') {
            itemData.check = true;
        }
        
        // Collect field values
        fields.forEach(field => {
            const fieldName = field.getAttribute('data-field');
            let value;
            
            if (field.type === 'checkbox') {
                value = field.checked;
            } else if (field.type === 'number') {
                value = parseInt(field.value) || '';
            } else {
                value = field.value;
            }
            
            if (value !== '' && value !== false) {
                itemData[fieldName] = value;
            }
        });
        
        // Only add if has content
        if (Object.keys(itemData).length > 1 || (itemData.text && itemData.text.trim() !== '')) {
            data.push(itemData);
        }
    });
    
    document.getElementById('sub_items_json').value = JSON.stringify(data);
}

// Ensure JSON is updated before form submit
document.querySelector('form').addEventListener('submit', function(e) {
    updateSubItemsJSON();
});
</script>
@endsection
