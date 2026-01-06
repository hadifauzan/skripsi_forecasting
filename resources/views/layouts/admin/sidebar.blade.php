{{-- sidebar.blade.php --}}
<!-- Mobile Overlay -->
<div id="sidebar-overlay"
    class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden transition-opacity duration-300 ease-in-out">
</div>

<!-- Sidebar -->
<div id="sidebar"
    class="sidebar-mini fixed top-16 left-0 h-[calc(100vh-4rem)] w-16 bg-brand-500 shadow-lg transform lg:transform-none -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out z-30 overflow-hidden">

    <!-- Navigation Menu -->
    <nav class="mt-6 px-2 pb-16 overflow-y-auto h-full">
        {{-- Role Badge --}}
        @if(Auth::user() && method_exists(Auth::user(), 'hasRole'))
            <div class="sidebar-text mb-4 px-3 hidden">
                <div class="text-center py-2 rounded-lg bg-teal-600/20 border border-teal-500/30">
                    <div class="text-xs text-gray-300 mb-1">Role:</div>
                    @if(Auth::user()->hasRole('superadmin'))
                        <span class="inline-block px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">Super
                            Admin</span>
                    @elseif(Auth::user()->hasRole('admin_content'))
                        <span class="inline-block px-2 py-1 bg-blue-500 text-white text-xs font-semibold rounded-full">Content
                            Admin</span>
                    @elseif(Auth::user()->hasRole('admin_partner'))
                        <span class="inline-block px-2 py-1 bg-yellow-500 text-white text-xs font-semibold rounded-full">Partner
                            Admin</span>
                    @elseif(Auth::user()->hasRole('admin_seller'))
                        <span class="inline-block px-2 py-1 bg-purple-500 text-white text-xs font-semibold rounded-full">Seller
                            Admin</span>
                    @else
                        <span
                            class="inline-block px-2 py-1 bg-gray-500 text-white text-xs font-semibold rounded-full">Admin</span>
                    @endif
                </div>
            </div>
        @endif

        <ul class="space-y-1">
            <!-- Dashboard -->
            <li class="relative">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-item group flex items-center justify-start px-3 py-3 rounded-lg transition-all duration-200
                    {{ request()->routeIs('admin.dashboard') ? 'bg-teal-500 text-white shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                    <x-heroicon-s-home class="nav-icon w-5 h-5 flex-shrink-0" />
                    <span class="sidebar-text font-medium ml-3 hidden">Home Page</span>
                </a>
            </li>

            {{-- User Management - Superadmin only --}}
            @if(Auth::user() && method_exists(Auth::user(), 'hasRole') && Auth::user()->hasRole('superadmin'))
                <li class="relative">
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-item group flex items-center justify-start px-3 py-3 rounded-lg transition-all duration-200
                        {{ request()->routeIs('admin.users.*') ? 'bg-teal-500 text-white shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                        <x-heroicon-s-users class="nav-icon w-5 h-5 flex-shrink-0" />
                        <span class="sidebar-text font-medium ml-3 hidden">User Management</span>
                    </a>
                </li>
            @endif

            {{-- Partner Management - Superadmin and Partner Admin --}}
            @if(Auth::user() && method_exists(Auth::user(), 'hasRole') && (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin_partner')))
                    <li class="relative">
                        <button onclick="toggleSubmenu('data-user')" data-tooltip="Partner Management" class="nav-item group w-full flex items-center justify-start px-3 py-3 rounded-lg transition-all duration-200 
                            {{ request()->routeIs('admin.view-data') || request()->routeIs('admin.data-affiliator') || request()->routeIs('admin.data-reseller*')
                ? 'bg-teal-500 text-white shadow-sm'
                : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                            <x-heroicon-s-user-group class="nav-icon w-5 h-5 flex-shrink-0" />
                            <span class="sidebar-text font-medium ml-3 hidden whitespace-nowrap">Partner Management</span>
                            
                            {{-- Notification Bubble untuk Total Pending --}}
                            @if(isset($notificationData) && $notificationData['has_notifications'])
                                <div
                                    class="notification-bubble absolute -top-2 -right-2 lg:-top-1 lg:-right-1 min-w-[20px] h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center animate-pulse">
                                    {{ $notificationData['total_pending'] }}
                                </div>
                            @endif
                            
                            <x-heroicon-s-chevron-down class="sidebar-text w-4 h-4 chevron-transition hidden ml-auto flex-shrink-0"
                                id="data-user-chevron" />
                        </button>

                        <!-- Submenu -->
                        <ul id="data-user-submenu" class="sidebar-text ml-8 mt-2 space-y-2 submenu-transition max-h-0">
                            {{-- Data Affiliator --}}
                            <li>
                                <a href="{{ route('admin.data-affiliator') }}"
                                    class="group flex items-center px-4 py-2 rounded-lg transition-all duration-200 relative
                                    {{ request()->routeIs('admin.data-affiliator') ? 'bg-teal-600 text-white font-medium shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                                    <x-heroicon-s-users class="w-4 h-4 mr-3 flex-shrink-0" />
                                    <span style="font-family: 'Nunito', sans-serif;">Data Affiliator</span>

                                    {{-- Notification Badge untuk Affiliate --}}
                                    @if(isset($notificationData) && $notificationData['pending_affiliates'] > 0)
                                        <div
                                            class="notification-badge ml-auto min-w-[18px] h-[18px] bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center animate-pulse">
                                            {{ $notificationData['pending_affiliates'] }}
                                        </div>
                                    @endif
                                </a>
                            </li>

                            {{-- Data Reseller --}}
                            <li>
                                <a href="{{ route('admin.data-reseller') }}"
                                    class="group flex items-center px-4 py-2 rounded-lg transition-all duration-200 relative
                                    {{ request()->routeIs('admin.data-reseller*') ? 'bg-teal-600 text-white font-medium shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                                    <x-heroicon-s-users class="w-4 h-4 mr-3 flex-shrink-0" />
                                    <span style="font-family: 'Nunito', sans-serif;">Data Reseller</span>

                                    {{-- Notification Badge untuk Reseller --}}
                                    @if(isset($notificationData) && $notificationData['pending_resellers'] > 0)
                                        <div
                                            class="notification-badge ml-auto min-w-[18px] h-[18px] bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center animate-pulse">
                                            {{ $notificationData['pending_resellers'] }}
                                        </div>
                                    @endif
                                </a>
                            </li>
                        </ul>
                    </li>
            @endif

            {{-- Customer Management - Superadmin and Partner Admin --}}
            @if(Auth::user() && method_exists(Auth::user(), 'hasRole') && (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin_partner')))
                <li class="relative">
                    <a href="{{ route('admin.data-customer') }}"
                        class="nav-item group flex items-center justify-start px-3 py-3 rounded-lg transition-all duration-200
                        {{ request()->routeIs('admin.data-customer*') ? 'bg-teal-500 text-white shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                        <x-heroicon-s-user class="nav-icon w-5 h-5 flex-shrink-0" />
                        <span class="sidebar-text font-medium ml-3 hidden">Data Customer</span>
                    </a>
                </li>
            @endif

            {{-- Affiliate Submissions - Superadmin and Partner Admin --}}
            @if(Auth::user() && method_exists(Auth::user(), 'hasRole') && (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin_partner')))
                <li class="relative">
                    <a href="{{ route('admin.affiliate-submissions.index') }}"
                        class="nav-item group flex items-center justify-start px-3 py-3 rounded-lg transition-all duration-200
                        {{ request()->routeIs('admin.affiliate-submissions*') ? 'bg-teal-500 text-white shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                        <x-heroicon-s-clipboard-document-list class="nav-icon w-5 h-5 flex-shrink-0" />
                        <span class="sidebar-text font-medium ml-3 hidden">Pengajuan Affiliate</span>
                    </a>
                </li>
                
                {{-- Affiliate Guide Management --}}
                <li class="relative">
                    <a href="{{ route('admin.affiliate-guide.index') }}"
                        class="nav-item group flex items-center justify-start px-3 py-3 rounded-lg transition-all duration-200
                        {{ request()->routeIs('admin.affiliate-guide*') ? 'bg-teal-500 text-white shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                        <x-heroicon-s-book-open class="nav-icon w-5 h-5 flex-shrink-0" />
                        <span class="sidebar-text font-medium ml-3 hidden">Panduan Affiliator</span>
                    </a>
                </li>
            @endif

            {{-- Content Management - Superadmin and Content Admin --}}
            @if(Auth::user() && method_exists(Auth::user(), 'hasRole') && (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin_content')))
                    <li class="relative">
                        <button onclick="toggleSubmenu('pengaturan')" data-tooltip="Content Management" class="nav-item group w-full flex items-center justify-start px-3 py-3 rounded-lg transition-all duration-200 
                            {{ request()->routeIs('admin.products.*') ||
                request()->routeIs('admin.content-products.*') ||
                request()->routeIs('admin.homepage-content.*') ||
                request()->routeIs('admin.partner-content.*') ||
                request()->routeIs('admin.about-us-content.*') ||
                request()->routeIs('tentang-kami') ||
                request()->routeIs('artikel')
                ? 'bg-teal-500 text-white shadow-sm'
                : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                            <x-heroicon-s-squares-plus class="nav-icon w-5 h-5 flex-shrink-0" />
                            <span class="sidebar-text font-medium ml-3 hidden whitespace-nowrap"
                                style="font-family: 'Nunito', sans-serif;">Content Management</span>
                            <x-heroicon-s-chevron-down class="sidebar-text w-4 h-4 chevron-transition hidden ml-auto flex-shrink-0"
                                id="pengaturan-chevron" />
                        </button>

                        <!-- Submenu -->
                        <ul id="pengaturan-submenu" class="sidebar-text ml-8 mt-2 space-y-2 submenu-transition max-h-0">
                            {{-- Beranda --}}
                            <li>
                                <a href="{{ route('admin.homepage-content.banner') }}"
                                    class="group flex items-center px-4 py-2 rounded-lg transition-all duration-200 
                                    {{ request()->routeIs('admin.homepage-content.*') ? 'bg-teal-600 text-white font-medium shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                                    <x-heroicon-s-home class="w-4 h-4 mr-3 flex-shrink-0" />
                                    <span style="font-family: 'Nunito', sans-serif;">Beranda</span>
                                </a>
                            </li>

                            <!-- Produk -->
                            <li>
                                <a href="{{ route('admin.content-products.index') }}"
                                    class="group flex items-center px-4 py-2 rounded-lg transition-all duration-200 
                                    {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.content-products.*') ? 'bg-teal-600 text-white font-medium shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                                    <x-heroicon-s-cube class="w-4 h-4 mr-3 flex-shrink-0" />
                                    <span style="font-family: 'Nunito', sans-serif;">Produk</span>
                                </a>
                            </li>

                            <!-- Tentang Kami -->
                            <li>
                                <a href="{{ route('admin.about-us-content.banner') }}"
                                    class="group flex items-center px-4 py-2 rounded-lg transition-all duration-200 
                                    {{ request()->routeIs('admin.about-us-content.*') || request()->routeIs('tentang-kami') ? 'bg-teal-600 text-white font-medium shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                                    <x-heroicon-s-information-circle class="w-4 h-4 mr-3 flex-shrink-0" />
                                    <span style="font-family: 'Nunito', sans-serif;">Tentang Kami</span>
                                </a>
                            </li>

                            {{-- Artikel --}}
                            <li>
                                <a href="{{ route('admin.articles.index') }}"
                                    class="group flex items-center px-4 py-2 rounded-lg transition-all duration-200 
                                    {{ request()->routeIs('admin.articles.*') ? 'bg-teal-600 text-white font-medium shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                                    <x-heroicon-s-document-text class="w-4 h-4 mr-3 flex-shrink-0" />
                                    <span style="font-family: 'Nunito', sans-serif;">Artikel</span>
                                </a>
                            </li>

                            <!-- Affiliate Content -->
                            <li>
                                <a href="{{ route('admin.affiliate-content.banner') }}"
                                    class="group flex items-center px-4 py-2 rounded-lg transition-all duration-200 
                                    {{ request()->routeIs('admin.affiliate-content.*') ? 'bg-teal-600 text-white font-medium shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                                    <x-heroicon-s-link class="w-4 h-4 mr-3 flex-shrink-0" />
                                    <span style="font-family: 'Nunito', sans-serif;">Partner - Affiliate</span>
                                </a>
                            </li>

                            <!-- Reseller Content -->
                            <li>
                                <a href="{{ route('admin.reseller-content.banner') }}"
                                    class="group flex items-center px-4 py-2 rounded-lg transition-all duration-200 
                                    {{ request()->routeIs('admin.reseller-content.*') ? 'bg-teal-600 text-white font-medium shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                                    <x-heroicon-s-user-group class="w-4 h-4 mr-3 flex-shrink-0" />
                                    <span style="font-family: 'Nunito', sans-serif;">Partner - Reseller</span>
                                </a>
                            </li>
                        </ul>
                    </li>
            @endif

            {{-- Sales Management - Superadmin and Seller Admin --}}
            @if(Auth::user() && method_exists(Auth::user(), 'hasRole') && (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin_seller')))
                    <li class="relative">
                        <button onclick="toggleSubmenu('kelola-penjualan')" data-tooltip="Sales Management" class="nav-item group w-full flex items-center justify-start px-3 py-3 rounded-lg transition-all duration-200 
                            {{ request()->routeIs('admin.sales.products.*') || request()->routeIs('admin.shipping-status.*') || request()->routeIs('admin.orders.*')
                ? 'bg-teal-500 text-white shadow-sm'
                : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                            <x-heroicon-s-shopping-cart class="nav-icon w-5 h-5 flex-shrink-0" />
                            <span class="sidebar-text font-medium ml-3 hidden whitespace-nowrap">Sales Management</span>
                            <x-heroicon-s-chevron-down class="sidebar-text w-4 h-4 chevron-transition hidden ml-auto flex-shrink-0"
                                id="kelola-penjualan-chevron" />
                        </button>

                        <!-- Submenu -->
                        <ul id="kelola-penjualan-submenu" class="sidebar-text ml-8 mt-2 space-y-2 submenu-transition max-h-0">
                            {{-- Orders --}}
                            <li>
                                <a href="{{ route('admin.orders.index') }}"
                                    class="group flex items-center px-4 py-2 rounded-lg transition-all duration-200 
                                    {{ request()->routeIs('admin.orders.*') ? 'bg-teal-600 text-white font-medium shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                                    <x-heroicon-s-currency-dollar class="w-4 h-4 mr-3 flex-shrink-0" />
                                    <span>Orders</span>
                                </a>
                            </li>
                            {{-- produk --}}
                            <li>
                                <a href="{{ route('admin.sales.products.index') }}"
                                    class="group flex items-center px-4 py-2 rounded-lg transition-all duration-200 
                                    {{ request()->routeIs('admin.sales.products.*') ? 'bg-teal-600 text-white font-medium shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                                    <x-heroicon-s-cube class="w-4 h-4 mr-3 flex-shrink-0" />
                                    <span>Produk</span>
                                </a>
                            </li>
                            {{-- status pengiriman --}}
                            <li>
                                <a href="{{ route('admin.shipping-status.index') }}"
                                    class="group flex items-center px-4 py-2 rounded-lg transition-all duration-200 
                                    {{ request()->routeIs('admin.shipping-status.*') ? 'bg-teal-600 text-white font-medium shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                                    <x-heroicon-s-truck class="w-4 h-4 mr-3 flex-shrink-0" />
                                    <span>Status Pengiriman</span>
                                </a>
                            </li>
                        </ul>
                    </li>
            @endif

            {{-- Rewards/Pricing - Partner Admin only --}}
            @if(Auth::user() && method_exists(Auth::user(), 'hasRole') && (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin_partner')))
                <li class="relative">
                    <button onclick="toggleSubmenu('reward')" data-tooltip="Rewards & Pricing"
                        class="nav-item group w-full flex items-center justify-start px-3 py-3 rounded-lg transition-all duration-200 
                        {{ request()->routeIs('admin.reseller-pricing.*') ? 'bg-teal-500 text-white shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                        <x-heroicon-s-gift class="nav-icon w-5 h-5 flex-shrink-0" />
                        <span class="sidebar-text font-medium ml-3 hidden whitespace-nowrap">Rewards & Pricing</span>
                        <x-heroicon-s-chevron-down class="sidebar-text w-4 h-4 chevron-transition hidden ml-auto flex-shrink-0"
                            id="reward-chevron" />
                    </button>

                    <ul id="reward-submenu" class="sidebar-text ml-8 mt-2 space-y-2 submenu-transition max-h-0">
                        <li>
                            <a href="{{ route('admin.reseller-pricing.index') }}"
                                class="group flex items-center px-4 py-2 rounded-lg transition-all duration-200 
                                {{ request()->routeIs('admin.reseller-pricing.*') ? 'bg-teal-600 text-white font-medium shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                                <x-heroicon-s-users class="w-4 h-4 mr-3 flex-shrink-0" />
                                <span>Reseller Pricing</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            {{-- System Settings - Superadmin only --}}
            @if(Auth::user() && method_exists(Auth::user(), 'hasRole') && Auth::user()->hasRole('superadmin'))
                <li class="relative">
                    <a href="{{ route('admin.settings.system') }}"
                        class="nav-item group flex items-center justify-start px-3 py-3 rounded-lg transition-all duration-200
                        {{ request()->routeIs('admin.settings.*') ? 'bg-teal-500 text-white shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                        <x-heroicon-s-cog-6-tooth class="nav-icon w-5 h-5 flex-shrink-0" />
                        <span class="sidebar-text font-medium ml-3 hidden">System Settings</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</div>