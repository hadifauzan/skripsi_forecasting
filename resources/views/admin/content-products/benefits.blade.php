@extends('layouts.admin.app')

@section('title', 'Kelola Benefits')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
<div class="p-6">
    <!-- Include shared navigation component -->
    @include('admin.content-products.navigation')

    <!-- Success Message -->
    <div id="success-message" class="hidden mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
    </div>

    <!-- CARD GABUNGAN: DESKRIPSI & BENEFIT PRODUK -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <!-- Header Card -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900">DESKRIPSI & BENEFIT PRODUK</h2>
            <div class="flex items-center space-x-3">
                <!-- Filter Kategori Produk -->
                <select id="product-filter" 
                        onchange="handleProductFilterChange(this.value)"
                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                    <option value="gentle-baby">Gentle Baby</option>
                    <option value="mamina">Mamina</option>
                    <option value="nyam">Nyam! MPASI</option>
                    <option value="healo">Healo</option>
                </select>
                
                <!-- Tombol Tambah Benefit -->
                <button onclick="createBenefit()" 
                        class="inline-flex items-center px-4 py-2 bg-[#785576] text-white rounded-lg hover:bg-[#694966] transition-colors shadow-sm hover:shadow-md">
                    <i class="fas fa-plus mr-2"></i>Tambah Benefit
                </button>
            </div>
        </div>

        <!-- Section Deskripsi Produk -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Deskripsi Produk</h3>
            <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg border">
                <div class="flex-1">
                    <div id="product-description-display" class="text-gray-700 leading-relaxed">
                        <!-- Deskripsi produk akan dimuat di sini -->
                    </div>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button onclick="editProductDescription()" 
                           class="group relative text-white p-1.5 rounded-md text-xs transition-all duration-200 hover:scale-110"
                           title="Edit Deskripsi"
                           style="background-color: #785576;"
                           onmouseover="this.style.backgroundColor='#6a4a68';"
                           onmouseout="this.style.backgroundColor='#785576';">
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Section Benefits Produk -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Benefits Produk</h3>

            <!-- Success Message -->
            <div id="success-message" 
                class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            </div>

            <!-- Container Benefit -->
            <div id="benefits-container" class="space-y-4">
                <!-- Benefits akan dimuat di sini sebagai cards -->
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="text-center py-12 hidden">
                <i class="fas fa-check-circle text-gray-300 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada benefit</h3>
                <p class="text-gray-600 mb-4">Klik tombol "Tambah Benefit" untuk mulai menambahkan benefits</p>
            </div>
        </div>
    </div>

    <!-- Modal Form untuk Product Description -->
    <div id="description-modal" class="fixed inset-0 bg-black/30 backdrop-clear-sm z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Deskripsi Produk</h3>
                </div>
                
                <!-- Modal Body -->
                <form id="description-form" onsubmit="saveProductDescription(event)" class="p-6">
                    <!-- Description -->
                    <div class="mb-4">
                        <label for="product-description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Produk *
                        </label>
                        <textarea id="product-description" name="description" rows="6"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Masukkan deskripsi lengkap tentang produk..." required></textarea>
                        <p class="text-xs text-gray-500 mt-1">Jelaskan tentang produk, kegunaan, dan keunggulannya</p>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" onclick="closeDescriptionModal()" 
                            class="px-4 py-2 border bg-[#c94757] rounded-md text-white hover:bg-[#b93e4f] transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-[#785576] text-white rounded-md hover:bg-[#694966] transition-colors">
                            Simpan Deskripsi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Form untuk Benefits -->
    <div id="benefit-modal" class="fixed inset-0 bg-black/30 backdrop-clear-sm z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="p-6 border-b">
                    <h3 id="modal-title" class="text-lg font-semibold text-gray-900">Tambah Benefit</h3>
                </div>
                
                <!-- Modal Body -->
                <form id="benefit-form" onsubmit="saveBenefit(event)" class="p-6">
                    <input type="hidden" id="benefit-id" value="">
                    
                    <!-- Benefit Text -->
                    <div class="mb-4">
                        <label for="benefit-text" class="block text-sm font-medium text-gray-700 mb-2">
                            Teks Benefit *
                        </label>
                        <input type="text" id="benefit-text" name="text" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="Contoh: MINYAK PIJAT BAYI BALITA" required>
                        <p class="text-xs text-gray-500 mt-1">Masukkan satu kalimat benefit yang akan ditampilkan</p>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" onclick="closeBenefitModal()" 
                            class="px-4 py-2 border bg-[#c94757] rounded-md text-white hover:bg-[#b93e4f] transition-colors">
                            Batal
                        </button>
                        <button type="submit" id="benefit-submit-btn"
                                class="px-4 py-2 bg-[#785576] text-white rounded-md hover:bg-[#694966] transition-colors">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Initialize data from database - diupdate untuk support kategori berdasarkan title
let currentProduct = 'gentle-baby'; // Default product category

// Function untuk mengambil deskripsi berdasarkan kategori
function getProductDescriptionByCategory(productDescriptions, category) {
    if (!productDescriptions || productDescriptions.length === 0) return "";
    
    const titleMap = {
        'gentle-baby': 'Product Description - Gentle Baby',
        'mamina': 'Product Description - Mamina', 
        'nyam': 'Product Description - Nyam MPASI',
        'healo': 'Product Description - Healo'
    };
    
    const targetTitle = titleMap[category];
    const description = productDescriptions.find(desc => desc.title === targetTitle);
    return description ? description.body : "";
}

// Function untuk mengambil benefits berdasarkan kategori
function getBenefitsByCategory(benefits, category) {
    if (!benefits || benefits.length === 0) return [];
    
    const titleMap = {
        'gentle-baby': 'Benefit Item - Gentle Baby',
        'mamina': 'Benefit Item - Mamina',
        'nyam': 'Benefit Item - Nyam',
        'healo': 'Benefit Item - Healo'
    };
    
    const targetTitlePrefix = titleMap[category];
    // Filter benefits yang title-nya dimulai dengan target prefix
    return benefits.filter(benefit => benefit.title.startsWith(targetTitlePrefix)).map(benefit => ({
        id: benefit.content_id,
        text: benefit.body
    }));
}

let productDescriptions = {
    'gentle-baby': getProductDescriptionByCategory(@json($productDescription), 'gentle-baby'),
    'mamina': getProductDescriptionByCategory(@json($productDescription), 'mamina'),
    'nyam': getProductDescriptionByCategory(@json($productDescription), 'nyam'),
    'healo': getProductDescriptionByCategory(@json($productDescription), 'healo')
};

let productBenefits = {
    'gentle-baby': getBenefitsByCategory(@json($benefits), 'gentle-baby'),
    'mamina': getBenefitsByCategory(@json($benefits), 'mamina'),
    'nyam': getBenefitsByCategory(@json($benefits), 'nyam'),
    'healo': getBenefitsByCategory(@json($benefits), 'healo')
};

let editingBenefitId = null;

// Handle product filter change
function handleProductFilterChange(selectedProduct) {
    currentProduct = selectedProduct;
    
    // Update display
    renderProductDescription();
    renderBenefits();
}

// Initialize page
function init() {
    // Set default product filter
    document.getElementById('product-filter').value = currentProduct;
    
    // Debug logging
    console.log('Product Descriptions:', productDescriptions);
    console.log('Product Benefits:', productBenefits);
    console.log('Current Product:', currentProduct);
    console.log('Product Description Data from Server:', @json($productDescription));
    console.log('Benefits Data from Server:', @json($benefits));
    
    renderProductDescription();
    renderBenefits();
}

// Product Description Functions
function renderProductDescription() {
    const display = document.getElementById('product-description-display');
    if (display) {
        const description = productDescriptions[currentProduct] || '';
        display.innerHTML = description || '<em class="text-gray-500">Belum ada deskripsi produk. Klik "Edit Deskripsi" untuk menambahkan.</em>';
    }
}

function editProductDescription() {
    const description = productDescriptions[currentProduct] || '';
    document.getElementById('product-description').value = description;
    document.getElementById('description-modal').classList.remove('hidden');
}

function closeDescriptionModal() {
    document.getElementById('description-modal').classList.add('hidden');
}

function saveProductDescription(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const description = formData.get('description');
    
    // Add product category to form data
    formData.append('product_category', currentProduct);
    
    // Send AJAX request to save
    fetch('{{ route("admin.content-products.product-description.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            productDescriptions[currentProduct] = description;
            renderProductDescription();
            closeDescriptionModal();
            showSuccessMessage(data.message);
        } else {
            alert('Error: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan');
    });
}

// Benefits Modal Management
function openBenefitModal(title = 'Tambah Benefit') {
    document.getElementById('modal-title').textContent = title;
    document.getElementById('benefit-modal').classList.remove('hidden');
}

function closeBenefitModal() {
    document.getElementById('benefit-modal').classList.add('hidden');
    editingBenefitId = null;
    resetBenefitForm();
}

// Render benefits
function renderBenefits() {
    const container = document.getElementById('benefits-container');
    const emptyState = document.getElementById('empty-state');
    
    if (!container) return;

    const currentBenefits = productBenefits[currentProduct] || [];

    if (currentBenefits.length === 0) {
        container.innerHTML = '';
        emptyState.classList.remove('hidden');
        return;
    }

    emptyState.classList.add('hidden');

    const html = currentBenefits.map(benefit => `
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border">
            <div class="flex items-center">
                <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                    <i class="fas fa-check text-blue-600 text-sm"></i>
                </div>
                <span class="text-gray-800">${benefit.text}</span>
            </div>
            <div class="flex space-x-1">
                <button onclick="editBenefit(${benefit.id})" 
                       class="group relative text-white p-1.5 rounded-md text-xs transition-all duration-200 hover:scale-110"
                       title="Edit Benefit"
                       style="background-color: #785576;"
                       onmouseover="this.style.backgroundColor='#6a4a68';"
                       onmouseout="this.style.backgroundColor='#785576';">
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </button>
                <button onclick="deleteBenefit(${benefit.id})" 
                       class="group relative text-white p-1.5 rounded-md text-xs transition-all duration-200 hover:scale-110"
                       title="Hapus Benefit"
                       style="background-color: #c94757;"
                       onmouseover="this.style.backgroundColor='#b93e4f';"
                       onmouseout="this.style.backgroundColor='#c94757';">
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
                </button>
            </div>
        </div>
    `).join('');

    container.innerHTML = html;
}

// Benefits CRUD operations
function createBenefit() {
    editingBenefitId = null;
    resetBenefitForm();
    openBenefitModal('Tambah Benefit');
}

function editBenefit(id) {
    const currentBenefits = productBenefits[currentProduct] || [];
    const benefit = currentBenefits.find(b => b.id === id);
    if (!benefit) return;

    editingBenefitId = id;
    
    // Fill form
    document.getElementById('benefit-id').value = benefit.id;
    document.getElementById('benefit-text').value = benefit.text;
    
    openBenefitModal('Edit Benefit');
    
    // Update submit button
    document.getElementById('benefit-submit-btn').textContent = 'Update';
}

function deleteBenefit(id) {
    // Find the benefit to get its text for display
    const currentBenefits = productBenefits[currentProduct] || [];
    const benefit = currentBenefits.find(b => b.id === id);
    const benefitText = benefit ? benefit.text : 'benefit ini';
    
    Swal.fire({
        title: 'Hapus Benefit?',
        text: `"${benefitText}" akan dihapus secara permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#c94757',
        cancelButtonColor: '#785576',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            popup: 'swal-purple-theme',
            confirmButton: 'swal-confirm-btn',
            cancelButton: 'swal-cancel-btn'
        },
        didOpen: () => {
            // Add custom CSS for purple theme
            const style = document.createElement('style');
            style.textContent = `
                .swal-purple-theme {
                    border-radius: 12px !important;
                }
                .swal-confirm-btn {
                    background-color: #c94757 !important;
                    border: none !important;
                    border-radius: 8px !important;
                    font-weight: 600 !important;
                    padding: 10px 20px !important;
                    transition: all 0.3s ease !important;
                }
                .swal-confirm-btn:hover {
                    background-color: #b93e4f !important;
                    transform: translateY(-1px) !important;
                    box-shadow: 0 4px 12px rgba(201, 71, 87, 0.3) !important;
                }
                .swal-cancel-btn {
                    background-color: #785576 !important;
                    border: none !important;
                    border-radius: 8px !important;
                    font-weight: 600 !important;
                    padding: 10px 20px !important;
                    transition: all 0.3s ease !important;
                }
                .swal-cancel-btn:hover {
                    background-color: #694966 !important;
                    transform: translateY(-1px) !important;
                    box-shadow: 0 4px 12px rgba(120, 85, 118, 0.3) !important;
                }
                .swal2-icon.swal2-warning {
                    border-color: #785576 !important;
                    color: #785576 !important;
                }
            `;
            document.head.appendChild(style);
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Send AJAX request to delete
            fetch(`{{ route("admin.content-products.benefits.delete", ":id") }}`.replace(':id', id), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove from current category
                    if (productBenefits[currentProduct]) {
                        productBenefits[currentProduct] = productBenefits[currentProduct].filter(b => b.id !== id);
                    }
                    renderBenefits();
                    showSuccessMessage(data.message);
                } else {
                    alert('Error: ' + (data.message || 'Terjadi kesalahan'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus');
            });
        }
    });
}

function saveBenefit(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = {
        text: formData.get('text'),
        product_category: currentProduct
    };

    const url = editingBenefitId 
        ? `{{ route("admin.content-products.benefits.update", ":id") }}`.replace(':id', editingBenefitId)
        : '{{ route("admin.content-products.benefits.store") }}';
    
    const method = editingBenefitId ? 'PUT' : 'POST';

    // Send AJAX request
    fetch(url, {
        method: method,
        body: JSON.stringify(data),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(responseData => {
        if (responseData.success) {
            if (editingBenefitId) {
                // Update existing
                if (productBenefits[currentProduct]) {
                    const index = productBenefits[currentProduct].findIndex(b => b.id === editingBenefitId);
                    if (index !== -1) {
                        productBenefits[currentProduct][index] = { ...productBenefits[currentProduct][index], text: data.text };
                    }
                }
            } else {
                // Add new benefit from response
                if (!productBenefits[currentProduct]) {
                    productBenefits[currentProduct] = [];
                }
                productBenefits[currentProduct].push(responseData.benefit);
            }

            renderBenefits();
            closeBenefitModal();
            showSuccessMessage(responseData.message);
        } else {
            alert('Error: ' + (responseData.message || 'Terjadi kesalahan'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan');
    });
}

// Helper functions
function resetBenefitForm() {
    document.getElementById('benefit-form').reset();
    document.getElementById('benefit-id').value = '';
    document.getElementById('benefit-submit-btn').textContent = 'Simpan';
}

function showSuccessMessage(message) {
    const successDiv = document.getElementById('success-message');
    successDiv.textContent = message;
    successDiv.classList.remove('hidden');
    
    // Hide after 3 seconds
    setTimeout(() => {
        successDiv.classList.add('hidden');
    }, 3000);
}

// Close modals when clicking outside
document.getElementById('benefit-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBenefitModal();
    }
});

document.getElementById('description-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDescriptionModal();
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', init);
</script>
@endsection
