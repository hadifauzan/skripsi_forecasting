<div class="mb-6">
    <div class="flex items-center justify-between py-5">
        <h2 class="font-nunito font-bold text-2xl text-[#614DAC]">
            Sistem Pricing Reseller
        </h2>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2 mb-6 font-nunito">
        <div class="flex space-x-1 overflow-x-auto">
            <!-- Pricing Overview -->
            <a href="{{ route('admin.reseller-pricing.index') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ request()->routeIs('admin.reseller-pricing.index') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                Daftar Harga
            </a>

            <!-- Statistics -->
            <a href="{{ route('admin.reseller-pricing.statistics') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ request()->routeIs('admin.reseller-pricing.statistics') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Statistik & Analisis
            </a>

            <!-- Points System -->
            <a href="{{ route('admin.reseller-pricing.points') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ request()->routeIs('admin.reseller-pricing.points') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                </svg>
                Sistem Poin
            </a>

            <!-- Purchases -->
            <a href="{{ route('admin.reseller-pricing.purchases') }}"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                      {{ request()->routeIs('admin.reseller-pricing.purchases') ? 'bg-[#785576] text-white shadow-md' : 'text-gray-600 hover:text-[#614DAC] hover:bg-purple-50' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                Pembelian Customer
            </a>

        </div>
    </div>
</div>