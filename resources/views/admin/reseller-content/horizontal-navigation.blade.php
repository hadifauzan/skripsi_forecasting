<div class="mb-6">
    <div class="flex items-center justify-between py-5">
        <h2 class="font-nunito font-bold text-2xl text-[#614DAC]">
            Kelola Konten Reseller
        </h2>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2 mb-6 font-nunito">
        <div class="flex space-x-1 overflow-x-auto">
            <!-- Banner -->
            <a href="{{ route('admin.reseller-content.banner') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ request()->routeIs('admin.reseller-content.banner*') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                Banner
            </a>

            <!-- Reasons -->
            <a href="{{ route('admin.reseller-content.section', 'reasons') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ (request()->routeIs('admin.reseller-content.section') && request()->route('sectionKey') === 'reasons') || (request()->routeIs('admin.reseller-content.section.edit') && request()->route('sectionKey') === 'reasons') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                Alasan Bergabung
            </a>

            <!-- Benefits -->
            <a href="{{ route('admin.reseller-content.section', 'benefits') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ (request()->routeIs('admin.reseller-content.section') && request()->route('sectionKey') === 'benefits') || (request()->routeIs('admin.reseller-content.section.edit') && request()->route('sectionKey') === 'benefits') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Keuntungan
            </a>

            <!-- Perfect For -->
            <a href="{{ route('admin.reseller-content.section', 'perfect-for') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ (request()->routeIs('admin.reseller-content.section') && request()->route('sectionKey') === 'perfect-for') || (request()->routeIs('admin.reseller-content.section.edit') && request()->route('sectionKey') === 'perfect-for') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                Target Cocok
            </a>

            <!-- Steps -->
            <a href="{{ route('admin.reseller-content.steps') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ request()->routeIs('admin.reseller-content.steps*') || (request()->routeIs('admin.reseller-content.section*') && request()->route('sectionKey') === 'steps') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
                Cara Bergabung
            </a>
        </div>
    </div>
</div>
