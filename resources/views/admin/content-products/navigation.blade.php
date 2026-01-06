<!-- Content Navigation Component -->
<div class="mb-6 mt-4">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold text-gray-900" style="font-family: 'Nunito', sans-serif;">
            Kelola Konten Produk
        </h1>
    </div>
    
    <!-- Horizontal Menu Navigation -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2 mb-6">
        <div class="flex space-x-1 overflow-x-auto">
            <!-- Carousel Produk -->
            <a href="{{ route('admin.content-products.carousel-produk') }}" 
               class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ Request::routeIs('admin.content-products.carousel-produk*') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-purple-600 hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Banner Produk
            </a>

            <!-- Benefits -->
            <a href="{{ route('admin.content-products.benefits') }}" 
               class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ Request::routeIs('admin.content-products.benefits*') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-purple-600 hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Benefits
            </a>

            <!-- Carousel Varian -->
            <a href="{{ route('admin.content-products.carousel-varian') }}" 
               class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ Request::routeIs('admin.content-products.carousel-varian*') || Request::routeIs('admin.products*') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-purple-600 hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                Varian Produk
            </a>

            <!-- Reviews -->
            <a href="{{ route('admin.content-products.reviews') }}" 
               class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ Request::routeIs('admin.content-products.reviews*') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-purple-600 hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.96 8.96 0 01-4.906-1.435l-3.181 1.295a1 1 0 01-1.272-1.272l1.295-3.181A8.96 8.96 0 013 12a8 8 0 018-8 8 8 0 018 8z"></path>
                </svg>
                Review
            </a>
        </div>
    </div>
</div>