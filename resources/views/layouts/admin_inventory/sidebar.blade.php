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
                        {{ request()->routeIs('admin.inventory.*') && !request()->routeIs('admin.inventory.dashboard') ? 'bg-teal-500 text-white shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                        <x-heroicon-s-archive-box class="nav-icon w-5 h-5 flex-shrink-0" />
                        <span class="sidebar-text font-medium ml-3 hidden whitespace-nowrap">Inventory Management</span>
                        <x-heroicon-s-chevron-down class="sidebar-text w-4 h-4 chevron-transition hidden ml-auto flex-shrink-0"
                            id="inventory-menu-chevron" />
                    </button>
                    <ul id="inventory-menu-submenu" class="sidebar-text ml-8 mt-2 space-y-2 submenu-transition max-h-0">
                        <li>
                            <a href="{{ Route::has('admin.inventory.index') ? route('admin.inventory.index') : route('admin.inventory.dashboard') }}"
                                class="group flex items-center px-4 py-2 rounded-lg transition-all duration-200
                                {{ request()->routeIs('admin.inventory.index') ? 'bg-teal-600 text-white font-medium shadow-sm' : 'text-gray-300 hover:bg-teal-500 hover:text-white' }}">
                                <x-heroicon-s-list-bullet class="w-4 h-4 mr-3 flex-shrink-0" />
                                <span style="font-family: 'Nunito', sans-serif;">Daftar Inventaris</span>
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
            @endif
        </ul>
    </nav>
</div>