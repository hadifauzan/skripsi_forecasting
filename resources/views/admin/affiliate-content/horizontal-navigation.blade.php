<div class="mb-6">
    <div class="flex items-center justify-between py-5">
        <h2 class="font-nunito font-bold text-2xl text-[#614DAC]">
            Kelola Konten Affiliate
        </h2>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2 mb-6 font-nunito">
        <div class="flex space-x-1 overflow-x-auto">
            <!-- Banner -->
            <a href="{{ route('admin.affiliate-content.banner') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ Request::routeIs('admin.affiliate-content.banner') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                Banner
            </a>

            <!-- Alasan Bergabung -->
            <a href="{{ route('admin.affiliate-content.reasons') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ Request::routeIs('admin.affiliate-content.reasons') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <svg class="w-5 h-5 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Alasan Bergabung
            </a>

            <!-- Keuntungan Bergabung -->
            <a href="{{ route('admin.affiliate-content.benefits') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ Request::routeIs('admin.affiliate-content.benefits') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <svg class="w-5 h-5 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.598 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Keuntungan Bergabung
            </a>

            <!-- Target Kerjasama -->
            <a href="{{ route('admin.affiliate-content.perfect-for') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ Request::routeIs('admin.affiliate-content.perfect-for') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <svg class="w-5 h-5 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Target Kerjasama
            </a>

            <!-- Video Affiliate -->
            <a href="{{ route('admin.affiliate-content.videos') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ Request::routeIs('admin.affiliate-content.videos*') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <svg class="w-5 h-5 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                Video Affiliate
            </a>

            <!-- Cara Bergabung -->
            <a href="{{ route('admin.affiliate-content.steps') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ Request::routeIs('admin.affiliate-content.steps') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <svg class="w-5 h-5 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                Cara Bergabung
            </a>
        </div>
    </div>
</div>
