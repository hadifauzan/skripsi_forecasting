<div class="mb-6">
    <div class="flex items-center justify-between py-5">
        <h2 class="font-nunito font-bold text-2xl text-[#614DAC]">
            Kelola Konten Tentang Kami
        </h2>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2 mb-6 font-nunito">
        <div class="flex space-x-1 overflow-x-auto">
            <!-- Banner Management -->
            <a href="{{ route('admin.about-us-content.banner') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ Request::routeIs('admin.about-us-content.banner') || Request::routeIs('admin.about-us-content.edit-banner') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                Banner
            </a>
            <!-- Tentang Kami -->
            <a href="{{ route('admin.about-us-content.tentang-kami') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
          {{ Request::routeIs('admin.about-us-content.tentang-kami') || Request::routeIs('admin.about-us-content.edit-tentang-kami') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <!-- Information Circle Icon -->
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                </svg>
                Tentang Kami
            </a>

            <!-- Perjalanan Kami -->
            <a href="{{ route('admin.about-us-content.journey') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
          {{ Request::routeIs('admin.about-us-content.journey') || Request::routeIs('admin.about-us-content.edit-journey') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <!-- Map Icon -->
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 20l-5.447-2.724A2 2 0 013 15.382V6.618a2 2 0 01.553-1.382L9 2m0 0l6 3m-6-3v18m6-15l6-3m-6 3v18m6-21v18" />
                </svg>
                Perjalanan Kami
            </a>

            <!-- Visi & Misi -->
            <a href="{{ route('admin.about-us-content.vision-mission') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
          {{ Request::routeIs('admin.about-us-content.vision-mission') || Request::routeIs('admin.about-us-content.edit-vision-mission') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <!-- Light Bulb Icon -->
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 3a7 7 0 00-7 7c0 2.21 1.79 4 4 4h1v4h4v-4h1c2.21 0 4-1.79 4-4a7 7 0 00-7-7z" />
                </svg>
                Visi & Misi
            </a>

            <!-- Keluarga Gentle Living -->
            <a href="{{ route('admin.about-us-content.family') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
          {{ Request::routeIs('admin.about-us-content.family') || Request::routeIs('admin.about-us-content.edit-family-header') || Request::routeIs('admin.about-us-content.edit-family-photos') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <!-- Users Icon -->
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m8-4a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Keluarga Gentle Living
            </a>
        </div>
    </div>


</div>
