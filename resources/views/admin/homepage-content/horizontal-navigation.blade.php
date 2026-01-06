<div class="mb-6">
    <div class="flex items-center justify-between py-5">
        <h2 class="font-nunito font-bold text-2xl text-[#614DAC]">
            Kelola Konten Beranda
        </h2>        
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2 mb-6 font-nunito">
        <div class="flex space-x-1 overflow-x-auto">
            <a href="{{ route('admin.homepage-content.banner') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ Request::routeIs('admin.homepage-content.banner') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                Banner
            </a>

            {{-- <a href="{{ route('admin.homepage-content.best-seller') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ Request::routeIs('admin.homepage-content.best-seller') ? 'bg-purple-500 text-white shadow-md' : 'text-gray-600 hover:text-purple-600 hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                    </path>
                </svg>
                Best Seller            --}}

            {{-- <a href=""
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ Request::routeIs('admin.homepage-content.product-details') ? 'bg-purple-500 text-white shadow-md' : 'text-gray-600 hover:text-purple-600 hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                Detail Product
            </a> --}}

            <a href="{{ route('admin.homepage-content.information') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ Request::routeIs('admin.homepage-content.information') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Informasi Tambahan
            </a>

            {{-- <a href=""
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ Request::routeIs('admin.homepage-content.testimonials') ? 'bg-purple text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 4v-4z">
                    </path>
                </svg>
                Testimoni
            </a> --}}

            <a href="{{ route('admin.homepage-content.faq') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ Request::routeIs('admin.homepage-content.faq') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.228 9.228a4.25 4.25 0 016.002 0M10.228 11.228a2.25 2.25 0 013.002 0m-3.002 0l-3.5-3.5a1 1 0 010-1.414l3.5-3.5a1 1 0 011.414 0l3.5 3.5a1 1 0 010 1.414l-3.5 3.5a1 1 0 01-1.414 0zM12 21a9 9 0 100-18 9 9 0 000 18z">
                    </path>
                </svg>
                FAQ
            </a>
        </div>
    </div>
</div>
