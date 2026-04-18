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
                    @if(Auth::user()->hasRole('owner'))
                        <span class="inline-block px-2 py-1 bg-yellow-500 text-white text-xs font-semibold rounded-full">Owner</span>
                    @elseif(Auth::user()->hasRole('admin_inventory'))
                        <span class="inline-block px-2 py-1 bg-orange-500 text-white text-xs font-semibold rounded-full">Inventory Admin</span>
                    @elseif(Auth::user()->hasRole('production_team'))
                        <span class="inline-block px-2 py-1 bg-cyan-500 text-white text-xs font-semibold rounded-full">Production Team</span>
                    @else
                        <span class="inline-block px-2 py-1 bg-gray-500 text-white text-xs font-semibold rounded-full">Admin</span>
                    @endif
                </div>
            </div>
        @endif

        <ul class="space-y-1">
            @php
                $inventoryManagementActive = request()->routeIs('admin.inventory.finished-goods', 'admin.inventory.create');
            @endphp

            <!-- Dashboard -->
            <li class="relative">
                <a href="{{ route('admin.inventory.dashboard') }}"
                    class="nav-item group flex items-center justify-start px-3 py-3 rounded-lg transition-all duration-200
                    {{ request()->routeIs('admin.inventory.dashboard') ? 'bg-teal-500 text-white shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                    <x-heroicon-s-home class="nav-icon w-5 h-5 flex-shrink-0" />
                    <span class="sidebar-text font-medium ml-3 hidden">Dashboard</span>
                </a>
            </li>

            {{-- Inventory Management - Owner, Admin Inventory, Production Team --}}
            @if(Auth::user() && method_exists(Auth::user(), 'hasRole') && (Auth::user()->hasRole('owner') || Auth::user()->hasRole('admin_inventory') || Auth::user()->hasRole('production_team')))
                <li class="relative">
                    <button onclick="toggleSubmenu('inventory-menu')" data-tooltip="Inventory Management"
                        class="nav-item group w-full flex items-center justify-start px-3 py-3 rounded-lg transition-all duration-200
                        {{ $inventoryManagementActive ? 'bg-teal-500 text-white shadow-sm' : 'text-gray-300 hover:bg-transparent hover:text-gray-300' }}">
                        <x-heroicon-s-archive-box class="nav-icon w-5 h-5 flex-shrink-0" />
                        <span class="sidebar-text font-medium ml-3 hidden whitespace-nowrap">Inventory Management</span>
                        <x-heroicon-s-chevron-down class="sidebar-text w-4 h-4 chevron-transition hidden ml-auto flex-shrink-0"
                            id="inventory-menu-chevron" />
                    </button>
                    <ul id="inventory-menu-submenu" class="sidebar-text ml-8 mt-2 space-y-2 submenu-transition max-h-0">
                        <li>
                            <a href="{{ route('admin.inventory.finished-goods') }}"
                                class="group flex items-center px-4 py-2 rounded-lg transition-all duration-200
                                {{ request()->routeIs('admin.inventory.finished-goods') ? 'bg-teal-600 text-white font-medium shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                                <x-heroicon-s-list-bullet class="w-4 h-4 mr-3 flex-shrink-0" />
                                <span style="font-family: 'Nunito', sans-serif;">Data Produk jadi</span>
                            </a>
                        </li>
                        {{-- Tambah Inventaris - Owner dan Admin Inventory saja --}}
                        @if((Auth::user()->hasRole('owner') || Auth::user()->hasRole('admin_inventory')) && Route::has('admin.inventory.create'))
                            <li>
                                <a href="{{ route('admin.inventory.create') }}"
                                    class="group flex items-center px-4 py-2 rounded-lg transition-all duration-200
                                    {{ request()->routeIs('admin.inventory.create') ? 'bg-teal-600 text-white font-medium shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                                    <x-heroicon-s-plus-circle class="w-4 h-4 mr-3 flex-shrink-0" />
                                    <span style="font-family: 'Nunito', sans-serif;">Tambah Inventaris</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                <!-- Buffer Stock Analysis -->
                <li class="relative">
                    <a href="{{ route('admin.inventory.buffer-stock.raw-materials') }}"
                        class="nav-item group flex items-center justify-start px-3 py-3 rounded-lg transition-all duration-200
                        {{ request()->routeIs('admin.inventory.buffer-stock.*') ? 'bg-blue-500 text-white shadow-sm' : 'text-gray-300 hover:bg-blue-500 hover:text-white' }}"
                        data-tooltip="Buffer Stock">
                        <span class="nav-icon text-xl flex-shrink-0">📊</span>
                        <span class="sidebar-text font-medium ml-3 hidden whitespace-nowrap">Buffer Stock</span>
                    </a>
                </li>

                <!-- Demand Forecasting -->
                <li class="relative">
                    <a href="{{ route('admin.inventory.forecasting.demand') }}"
                        class="nav-item group flex items-center justify-start px-3 py-3 rounded-lg transition-all duration-200
                        {{ request()->routeIs('admin.inventory.forecasting.*') ? 'bg-purple-500 text-white shadow-sm' : 'text-gray-300 hover:bg-purple-500 hover:text-white' }}"
                        data-tooltip="Forecasting">
                        <span class="nav-icon text-xl flex-shrink-0">📈</span>
                        <span class="sidebar-text font-medium ml-3 hidden whitespace-nowrap">Forecasting</span>
                    </a>
                </li>

                <!-- Stock Opname -->
                <li class="relative">
                    <a href="{{ route('admin.inventory.stock-opname') }}"
                        class="nav-item group flex items-center justify-start px-3 py-3 rounded-lg transition-all duration-200
                        {{ request()->routeIs('admin.inventory.stock-opname') ? 'bg-orange-500 text-white shadow-sm' : 'text-gray-300 hover:bg-orange-500 hover:text-white' }}"
                        data-tooltip="Stock Opname">
                        <span class="nav-icon text-xl flex-shrink-0">📋</span>
                        <span class="sidebar-text font-medium ml-3 hidden whitespace-nowrap">Stock Opname</span>
                    </a>
                </li>

                <!-- Production Overview -->
                <li class="relative">
                    <a href="{{ route('admin.inventory.production.overview') }}"
                        class="nav-item group flex items-center justify-start px-3 py-3 rounded-lg transition-all duration-200
                        {{ request()->routeIs('admin.inventory.production.*') ? 'bg-green-500 text-white shadow-sm' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}"
                        data-tooltip="Production">
                        <span class="nav-icon text-xl flex-shrink-0">🏭</span>
                        <span class="sidebar-text font-medium ml-3 hidden whitespace-nowrap">Production</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</div>