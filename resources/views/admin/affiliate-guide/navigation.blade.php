<!-- Affiliate Guide Navigation Component -->
<div class="mb-6 mt-4">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold text-gray-900" style="font-family: 'Nunito', sans-serif;">
            Kelola Panduan Affiliator
        </h1>
        <a href="{{ route('admin.affiliate-guide.create', ['section' => request('section', 'produk')]) }}" 
           class="inline-flex items-center px-4 py-2 bg-[#785576] text-white text-sm font-medium rounded-lg hover:bg-[#634460] transition-colors duration-200 shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Panduan
        </a>
    </div>
    
    <!-- Horizontal Menu Navigation -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2 mb-6">
        <div class="flex space-x-1 overflow-x-auto">
            <!-- Produk -->
            <a href="{{ route('admin.affiliate-guide.index', ['section' => 'produk']) }}" 
               class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ !request('section') || request('section') == 'produk' ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-purple-600 hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                Produk
            </a>

            <!-- Pengajuan -->
            <a href="{{ route('admin.affiliate-guide.index', ['section' => 'pengajuan']) }}" 
               class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ request('section') == 'pengajuan' ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-purple-600 hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Pengajuan
            </a>

            <!-- Pengiriman -->
            <a href="{{ route('admin.affiliate-guide.index', ['section' => 'pengiriman']) }}" 
               class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ request('section') == 'pengiriman' ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-purple-600 hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                Pengiriman
            </a>

            <!-- Video Review -->
            <a href="{{ route('admin.affiliate-guide.index', ['section' => 'video']) }}" 
               class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ request('section') == 'video' ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-purple-600 hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                Video Review
            </a>
        </div>
    </div>
</div>
