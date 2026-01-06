@extends('layouts.admin.app')

@section('title', 'Kelola penjualan')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="mb-4 sm:mb-0">
                <h1 class="page-title text-3xl text-[#785576] flex items-center mb-2">
                    <svg class="w-8 h-8 mr-3 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Kelola Produk
                </h1>
                <p class="page-subtitle text-[#785576] text-lg ml-11">Kelola semua produk yang dijual</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.sales.products.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#785576] text-white text-sm font-medium rounded-lg hover:bg-[#5f4360] transition-colors duration-200 shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Produk
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        

        <form method="GET" action="{{ route('admin.sales.products.index') }}" id="filterForm" class="space-y-4">
            <!-- Row 1: Search and Quick Filters -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Produk</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari nama, deskripsi, kode, atau ingredient..."
                               class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}" {{ request('category') == $category->category_id ? 'selected' : '' }}>
                                {{ $category->name_category }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <!-- Row 2: Advanced Filters (collapsible) -->
            <div class="border-t pt-4">
                <button type="button" id="toggleAdvanced" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 mb-4">
                    <svg id="chevronIcon" class="w-4 h-4 mr-2 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                    Filter Lanjutan
                </button>
                
                <div id="advancedFilters" class="hidden grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Min</label>
                        <input type="number" name="price_min" value="{{ request('price_min') }}" 
                               placeholder="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Max</label>
                        <input type="number" name="price_max" value="{{ request('price_max') }}" 
                               placeholder="999999999"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                        <select name="sort_by" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                            <option value="updated_at" {{ request('sort_by') == 'updated_at' ? 'selected' : '' }}>Tanggal Diperbarui</option>
                            <option value="name_item" {{ request('sort_by') == 'name_item' ? 'selected' : '' }}>Nama Produk</option>
                            <option value="costprice_item" {{ request('sort_by') == 'costprice_item' ? 'selected' : '' }}>Harga</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                        <select name="sort_order" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Terlama</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit" 
                        class="inline-flex items-center px-6 py-2 bg-[#785576] text-white rounded-lg hover:bg-[#5f4360] transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Terapkan Filter
                </button>
                <a href="{{ route('admin.sales.products.index') }}" 
                   class="inline-flex items-center px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reset Filter
                </a>
                <div class="flex items-center">
                    <label class="block text-sm font-medium text-gray-700 mr-2">Per halaman:</label>
                    <select name="per_page" onchange="document.getElementById('filterForm').submit();"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Results Info -->
        @if(request()->hasAny(['search', 'category', 'status', 'price_min', 'price_max']))
            <div class="bg-blue-50 border-b border-blue-200 px-6 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-sm text-blue-800">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Menampilkan {{ $products->count() }} dari {{ $products->total() }} produk
                        @if(request('search'))
                            untuk pencarian "<strong>{{ request('search') }}</strong>"
                        @endif
                        @if(request('category'))
                            @php $selectedCategory = $categories->where('category_id', request('category'))->first(); @endphp
                            @if($selectedCategory)
                                dalam kategori "<strong>{{ $selectedCategory->name_category }}</strong>"
                            @endif
                        @endif
                        @if(request('status'))
                            dengan status "<strong>{{ request('status') == 'active' ? 'Aktif' : 'Nonaktif' }}</strong>"
                        @endif
                    </div>
                    <a href="{{ route('admin.sales.products.index') }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat Semua
                    </a>
                </div>
            </div>
        @endif

        @if($products->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div>Harga</div>
                                <div class="text-xs text-gray-400 normal-case font-normal">(Jual & Modal)</div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($product->picture_item)
                                        <img class="h-12 w-12 rounded-lg object-cover mr-4" 
                                             src="{{ $product->image }}" 
                                             alt="{{ $product->name_item }}"
                                             onerror="this.src='{{ asset('storage/gentle-baby/placeholder.jpg') }}'">
                                    @else
                                        <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name_item }}</div>
                                        @if($product->description_item)
                                            <div class="text-sm text-gray-500">{{ Str::limit($product->description_item, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->categories->count() > 0)
                                    @foreach($product->categories->take(2) as $category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1 mb-1">
                                            {{ $category->name_category }}
                                        </span>
                                    @endforeach
                                    @if($product->categories->count() > 2)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            +{{ $product->categories->count() - 2 }}
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Belum dikategorikan
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @php
                                    $regularPricing = $product->itemDetails->where('customer_type_id', 1)->first();
                                    $sellPrice = $regularPricing ? $regularPricing->sell_price : null;
                                    $costPrice = $regularPricing ? $regularPricing->cost_price : null;
                                @endphp
                                @if($sellPrice)
                                    <div class="space-y-1">
                                        <div class="text-sm font-semibold text-green-600">
                                            Rp {{ number_format($sellPrice, 0, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Harga Jual
                                        </div>
                                        @if($costPrice)
                                            <div class="text-xs text-gray-400">
                                                Modal: Rp {{ number_format($costPrice, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </div>
                                @elseif($product->costprice_item)
                                    <div>
                                        <div class="text-sm text-orange-600">
                                            Rp {{ number_format($product->costprice_item, 0, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Harga Pokok
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400">Belum diset</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="text-blue-600 font-medium">
                                    Tersedia
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->status_item === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.sales.products.edit', $product->item_id) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.sales.products.destroy', $product->item_id) }}" 
                                          method="POST" class="inline"
                                          onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada produk</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan produk pertama Anda.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.sales.products.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Produk
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Advanced filter toggle
    const toggleAdvanced = document.getElementById('toggleAdvanced');
    const advancedFilters = document.getElementById('advancedFilters');
    const chevronIcon = document.getElementById('chevronIcon');
    
    // Check if advanced filters have values and show them
    const hasAdvancedValues = ['price_min', 'price_max', 'sort_by', 'sort_order'].some(param => {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param) && urlParams.get(param) !== '';
    });
    
    if (hasAdvancedValues) {
        advancedFilters.classList.remove('hidden');
        chevronIcon.classList.add('rotate-180');
    }
    
    toggleAdvanced.addEventListener('click', function() {
        advancedFilters.classList.toggle('hidden');
        chevronIcon.classList.toggle('rotate-180');
    });
    
    // Auto-submit on filter change (optional)
    const autoSubmitElements = document.querySelectorAll('select[name="category"], select[name="status"]');
    autoSubmitElements.forEach(element => {
        element.addEventListener('change', function() {
            // Optional: Auto-submit when main filters change
            // Uncomment the line below to enable auto-submit
            // document.getElementById('filterForm').submit();
        });
    });
    
    // Search input debounce
    let searchTimeout;
    const searchInput = document.querySelector('input[name="search"]');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                // Optional: Auto-submit after 1 second of no typing
                // Uncomment the line below to enable auto-search
                // document.getElementById('filterForm').submit();
            }, 1000);
        });
    }
    
    // Clear individual filters with double-click
    document.querySelectorAll('select, input[type="text"], input[type="number"]').forEach(element => {
        element.addEventListener('dblclick', function() {
            if (this.type === 'text' || this.type === 'number') {
                this.value = '';
            } else if (this.tagName === 'SELECT') {
                this.selectedIndex = 0;
            }
        });
    });
    
    // Show active filters count
    const activeFilters = document.querySelectorAll('input[type="text"], input[type="number"], select').length;
    let filledFilters = 0;
    
    document.querySelectorAll('input[type="text"], input[type="number"], select').forEach(element => {
        if (element.value && element.value !== '') {
            filledFilters++;
        }
    });
    
    if (filledFilters > 0) {
        const filterButton = document.querySelector('button[type="submit"]:not([name])');
        if (filterButton && filterButton.textContent.includes('Terapkan Filter')) {
            filterButton.innerHTML = `
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Terapkan Filter (${filledFilters})
            `;
        }
    }
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+Enter to submit form
        if (e.ctrlKey && e.key === 'Enter') {
            document.getElementById('filterForm').submit();
        }
        
        // Escape to clear search
        if (e.key === 'Escape' && searchInput === document.activeElement) {
            searchInput.value = '';
        }
    });
});
</script>

@endsection
