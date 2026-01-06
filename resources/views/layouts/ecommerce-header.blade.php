<header class="fixed top-0 left-0 right-0 bg-white shadow-md z-50">
    <div class="max-w-7xl mx-auto py-3 lg:py-4 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('images/top-bar.png') }}" alt="Gentle Living Logo" class="h-10 sm:h-12">
                </a>
            </div>

            <!-- Navigation Menu -->
            <nav class="hidden lg:flex lg:items-center lg:space-x-8 font-nunito">
                <a href="{{ route('shopping') }}"
                    class="text-sm xl:text-base {{ Route::currentRouteName() == 'shopping' ? 'text-blue-600 font-bold' : 'text-gray-600' }} hover:text-blue-600 transition-colors duration-200">
                    Beranda
                </a>
                <a href="{{ route('shopping.products') }}"
                    class="text-sm xl:text-base {{ Route::currentRouteName() == 'shopping.products' ? 'text-blue-600 font-bold' : 'text-gray-600' }} hover:text-blue-600 transition-colors duration-200">
                    Produk
                </a>
                @if($currentUser && $currentUser->role_id == 4)
                    <a href="{{ route('affiliate.submissions.list') }}"
                        class="text-sm xl:text-base {{ Route::currentRouteName() == 'affiliate.submissions.list' ? 'text-blue-600 font-bold' : 'text-gray-600' }} hover:text-blue-600 transition-colors duration-200">
                        Pengajuan Saya
                    </a>
                    <a href="{{ route('affiliate.guide') }}"
                        class="text-sm xl:text-base {{ Route::currentRouteName() == 'affiliate.guide' ? 'text-blue-600 font-bold' : 'text-gray-600' }} hover:text-blue-600 transition-colors duration-200">
                        Panduan
                    </a>
                @else
                    <a href="{{ route('shopping.history') }}"
                        class="text-sm xl:text-base {{ Route::currentRouteName() == 'shopping.history' ? 'text-blue-600 font-bold' : 'text-gray-600' }} hover:text-blue-600 transition-colors duration-200">
                        Riwayat
                    </a>
                @endif
            </nav>

            <!-- Search and Actions -->
            <div class="hidden lg:flex lg:items-center lg:space-x-4">
                <!-- Search Bar -->
                <form action="{{ route('shopping.products') }}" method="GET" class="relative" id="search-form-desktop">
                    <input type="text" 
                           name="search"
                           id="search-input-desktop"
                           value="{{ request('search') }}"
                           placeholder="Cari produk..." 
                           class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg class="h-5 w-5 text-gray-400 hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>

                @php
                    // Fallback check jika $currentUser tidak ter-set dari view composer
                    if (!isset($currentUser) || !$currentUser) {
                        if (Auth::guard('customer')->check()) {
                            $currentUser = Auth::guard('customer')->user();
                            $isCustomer = true;
                            $isAdmin = false;
                        } elseif (Auth::guard('web')->check()) {
                            $currentUser = Auth::guard('web')->user();
                            $isCustomer = false;
                            $isAdmin = isset($currentUser->role) && in_array($currentUser->role, ['admin', 'superadmin']);
                        }
                    }
                @endphp

                @if($currentUser)
                    <!-- User Menu for Authenticated Users -->
                    <div class="relative" id="user-dropdown-container">
                        <button class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:text-blue-600 transition-colors duration-200" id="user-dropdown-button">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-nunito font-medium">
                                {{ $isCustomer ? $currentUser->name_customer : $currentUser->name }}
                            </span>
                            <svg class="h-4 w-4 transition-transform duration-200" id="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible transform scale-95 transition-all duration-200 z-50" id="user-dropdown-menu">
                            <div class="py-1">
                                @if($isAdmin)
                                    <a href="{{ route('admin.view-data') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                        </svg>
                                        Dashboard Admin
                                    </a>
                                    <div class="border-t border-gray-100"></div>
                                @endif
                                @if($currentUser->role_id == 4)
                                    <a href="{{ route('affiliate.submissions.list') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        Pengajuan Saya
                                    </a>
                                    <a href="{{ route('affiliate.guide') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        Panduan
                                    </a>
                                @else
                                    <a href="{{ route('shopping.history') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        Riwayat Pesanan
                                    </a>
                                @endif
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-left text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-200">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Login/Register for Guests -->
                    <a href="{{ route('login') }}"
                        class="px-6 py-2 text-sm lg:text-base font-nunito font-medium text-blue-600 border border-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition-all duration-300 ease-in-out transform hover:scale-105">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                        class="px-6 py-2 text-sm lg:text-base font-nunito font-medium text-white bg-blue-600 border border-blue-600 rounded-full hover:bg-blue-700 hover:border-blue-700 transition-all duration-300 ease-in-out transform hover:scale-105">
                        Daftar
                    </a>
                @endif

                <!-- Cart Icon -->
                <a href="{{ route('shopping.cart') }}" class="relative p-2 text-gray-600 hover:text-blue-600 transition-colors duration-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0L17 18m0 0v3a1 1 0 01-1 1H8a1 1 0 01-1-1v-3m10 0a1 1 0 01-1 1H8a1 1 0 01-1-1"></path>
                    </svg>
                    <!-- Cart badge -->
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" id="cart-badge-desktop">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="lg:hidden">
                <button type="button" id="mobile-menu-button"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-brand transition-all duration-200">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div class="lg:hidden hidden" id="mobile-menu">
            <div class="pt-4 pb-3 space-y-1 border-t border-gray-200 mt-4">
                <!-- Mobile Search -->
                <div class="px-3 mb-4">
                    <form action="{{ route('shopping.products') }}" method="GET" class="relative" id="search-form-mobile">
                        <input type="text" 
                               name="search"
                               id="search-input-mobile"
                               value="{{ request('search') }}"
                               placeholder="Cari produk..." 
                               class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400 hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                <nav class="space-y-1 font-nunito">
                    <a href="{{ route('shopping') }}"
                        class="block px-3 py-2 text-base {{ Route::currentRouteName() == 'shopping' ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-600' }} hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-all duration-200">
                        Beranda
                    </a>
                    <a href="{{ route('shopping.products') }}"
                        class="block px-3 py-2 text-base {{ Route::currentRouteName() == 'shopping.products' ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-600' }} hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-all duration-200">
                        Produk
                    </a>
                    @if($currentUser && $currentUser->role_id == 4)
                        <a href="{{ route('affiliate.submissions.list') }}"
                            class="block px-3 py-2 text-base {{ Route::currentRouteName() == 'affiliate.submissions.list' ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-600' }} hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-all duration-200">
                            Pengajuan Saya
                        </a>
                        <a href="{{ route('affiliate.guide') }}"
                            class="block px-3 py-2 text-base {{ Route::currentRouteName() == 'affiliate.guide' ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-600' }} hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-all duration-200">
                            Panduan
                        </a>
                    @else
                        <a href="{{ route('shopping.history') }}"
                            class="block px-3 py-2 text-base {{ Route::currentRouteName() == 'shopping.history' ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-600' }} hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-all duration-200">
                            Riwayat
                        </a>
                    @endif
                </nav>

                <div class="pt-4 border-t border-gray-200">
                    @if($currentUser)
                        <!-- Authenticated User Mobile Menu -->
                        <div class="px-3 mb-4">
                            <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div>
                                    <p class="font-nunito font-medium text-gray-800">
                                        {{ $isCustomer ? $currentUser->name_customer : $currentUser->name }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ $isCustomer ? ($currentUser->email_customer ?: $currentUser->phone_customer) : $currentUser->email }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($isAdmin)
                            <a href="{{ route('admin.view-data') }}" class="block px-3 py-2 text-base text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-all duration-200 font-nunito">
                                <svg class="inline h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                                Dashboard Admin
                            </a>
                        @endif

                        <div class="flex space-x-3 px-3 pt-4">
                            <a href="{{ route('shopping.cart') }}" class="px-4 py-3 bg-gray-100 rounded-full relative flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0L17 18m0 0v3a1 1 0 01-1 1H8a1 1 0 01-1-1v-3m10 0a1 1 0 01-1 1H8a1 1 0 01-1-1"></path>
                                </svg>
                                @if($cartCount > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" id="cart-badge-mobile-auth">{{ $cartCount }}</span>
                                @endif
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full text-center px-6 py-3 text-white bg-red-600 border border-red-600 rounded-full hover:bg-red-700 hover:border-red-700 transition-all duration-300 ease-in-out font-nunito font-medium">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Guest Mobile Menu -->
                        <div class="flex space-x-3 px-3">
                            <a href="{{ route('login') }}"
                                class="flex-1 text-center px-6 py-3 text-blue-600 border border-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition-all duration-300 ease-in-out font-nunito font-medium">
                                Login
                            </a>
                            <a href="{{ route('register') }}"
                                class="flex-1 text-center px-6 py-3 text-white bg-blue-600 border border-blue-600 rounded-full hover:bg-blue-700 hover:border-blue-700 transition-all duration-300 ease-in-out font-nunito font-medium">
                                Daftar
                            </a>
                            <a href="{{ route('shopping.cart') }}" class="px-4 py-3 bg-gray-100 rounded-full relative">
                                <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0L17 18m0 0v3a1 1 0 01-1 1H8a1 1 0 01-1-1v-3m10 0a1 1 0 01-1 1H8a1 1 0 01-1-1"></path>
                                </svg>
                                @if($cartCount > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" id="cart-badge-mobile-guest">{{ $cartCount }}</span>
                                @endif
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>

<script>
// Global function to update cart badge
function updateCartBadge() {
    fetch('{{ route("shopping.cart.count") }}')
        .then(response => response.json())
        .then(data => {
            const count = data.count;
            
            // Update all cart badges
            const badges = ['cart-badge-desktop', 'cart-badge-mobile-auth', 'cart-badge-mobile-guest'];
            
            badges.forEach(badgeId => {
                const badge = document.getElementById(badgeId);
                if (badge) {
                    if (count > 0) {
                        badge.textContent = count;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error updating cart badge:', error);
        });
}

// Search functionality
function initializeSearch() {
    // Handle desktop search form
    const searchFormDesktop = document.getElementById('search-form-desktop');
    const searchInputDesktop = document.getElementById('search-input-desktop');
    
    if (searchFormDesktop && searchInputDesktop) {
        searchFormDesktop.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchQuery = searchInputDesktop.value.trim();
            
            if (searchQuery.length > 0) {
                window.location.href = '{{ route("shopping.products") }}?search=' + encodeURIComponent(searchQuery);
            } else {
                window.location.href = '{{ route("shopping.products") }}';
            }
        });
        
        // Allow search on Enter key
        searchInputDesktop.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchFormDesktop.dispatchEvent(new Event('submit'));
            }
        });
    }
    
    // Handle mobile search form
    const searchFormMobile = document.getElementById('search-form-mobile');
    const searchInputMobile = document.getElementById('search-input-mobile');
    
    if (searchFormMobile && searchInputMobile) {
        searchFormMobile.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchQuery = searchInputMobile.value.trim();
            
            if (searchQuery.length > 0) {
                window.location.href = '{{ route("shopping.products") }}?search=' + encodeURIComponent(searchQuery);
            } else {
                window.location.href = '{{ route("shopping.products") }}';
            }
        });
        
        // Allow search on Enter key
        searchInputMobile.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchFormMobile.dispatchEvent(new Event('submit'));
            }
        });
    }
}

// Update cart badge on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartBadge();
    initializeSearch();
    
    // Mobile menu toggle functionality
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            
            // Toggle hamburger icon animation
            const menuIcon = mobileMenuButton.querySelector('svg');
            if (menuIcon) {
                menuIcon.classList.toggle('rotate-90');
            }
        });
    }
    
    // User dropdown functionality
    const userDropdownContainer = document.getElementById('user-dropdown-container');
    const userDropdownButton = document.getElementById('user-dropdown-button');
    const userDropdownMenu = document.getElementById('user-dropdown-menu');
    const dropdownArrow = document.getElementById('dropdown-arrow');
    
    if (userDropdownContainer && userDropdownButton && userDropdownMenu) {
        let dropdownTimeout;
        
        // Show dropdown on hover
        userDropdownContainer.addEventListener('mouseenter', function() {
            clearTimeout(dropdownTimeout);
            userDropdownMenu.classList.remove('opacity-0', 'invisible', 'scale-95');
            userDropdownMenu.classList.add('opacity-100', 'visible', 'scale-100');
            if (dropdownArrow) {
                dropdownArrow.style.transform = 'rotate(180deg)';
            }
        });
        
        // Hide dropdown on mouse leave with delay
        userDropdownContainer.addEventListener('mouseleave', function() {
            dropdownTimeout = setTimeout(() => {
                userDropdownMenu.classList.remove('opacity-100', 'visible', 'scale-100');
                userDropdownMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                if (dropdownArrow) {
                    dropdownArrow.style.transform = 'rotate(0deg)';
                }
            }, 150);
        });
        
        // Also handle click for mobile/touch devices
        userDropdownButton.addEventListener('click', function(e) {
            e.stopPropagation();
            const isVisible = userDropdownMenu.classList.contains('opacity-100');
            
            if (isVisible) {
                userDropdownMenu.classList.remove('opacity-100', 'visible', 'scale-100');
                userDropdownMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                if (dropdownArrow) {
                    dropdownArrow.style.transform = 'rotate(0deg)';
                }
            } else {
                userDropdownMenu.classList.remove('opacity-0', 'invisible', 'scale-95');
                userDropdownMenu.classList.add('opacity-100', 'visible', 'scale-100');
                if (dropdownArrow) {
                    dropdownArrow.style.transform = 'rotate(180deg)';
                }
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!userDropdownContainer.contains(e.target)) {
                userDropdownMenu.classList.remove('opacity-100', 'visible', 'scale-100');
                userDropdownMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                if (dropdownArrow) {
                    dropdownArrow.style.transform = 'rotate(0deg)';
                }
            }
        });
    }
});
</script>
