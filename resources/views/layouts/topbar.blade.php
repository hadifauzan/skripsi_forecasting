<header class="fixed top-0 left-0 right-0 bg-white shadow-md z-50">
    <div class="max-w-7xl mx-auto py-3 lg:py-4 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('images/top-bar.png') }}" alt="Gentle Living Logo" class="h-10 sm:h-12">
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="xl:hidden">
                <button type="button" id="mobile-menu-button"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-brand transition-all duration-200">
                    <span class="sr-only">Open main menu</span>
                    <!-- Menu icon -->
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden xl:flex xl:items-center xl:space-x-8">
                <nav class="flex space-x-6 xl:space-x-8 font-nunito">
                    <a href="{{ route('home') }}"
                        class="text-sm xl:text-base {{ Route::currentRouteName() == 'home' ? 'text-blue-600 font-bold' : 'text-gray-600' }} hover:text-blue-600 transition-colors duration-200">
                        Beranda
                    </a>

                    <!-- Produk Dropdown -->
                    <div class="relative group">
                        <button id="produk-dropdown-btn"
                            class="text-sm xl:text-base {{ Route::currentRouteName() == 'products' ? 'text-blue-600 font-bold' : 'text-gray-600' }} hover:text-blue-600 transition-colors duration-200 flex items-center space-x-1 focus:outline-none">
                            <span>Produk</span>
                            <svg class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="produk-dropdown-menu"
                            class="absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 z-50">
                            <div class="py-3">
                                <!-- Gentle Living with Submenu -->
                                <div class="relative group/gentle">
                                    <button
                                        class="w-full flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-600 hover:text-white transition-all duration-200 group">
                                        <span class="font-medium">Gentle Living</span>
                                        <div class="ml-auto">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </button>

                                    <!-- Gentle Living Submenu -->
                                    <div
                                        class="absolute left-full top-0 ml-1 w-48 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover/gentle:opacity-100 group-hover/gentle:visible transition-all duration-300 transform translate-x-2 group-hover/gentle:translate-x-0">
                                        <div class="py-2">
                                            <a href="{{ route('products') }}?product=gentle-baby"
                                                class="block px-4 py-2 text-sm {{ request('product') == 'gentle-baby' ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-700 hover:bg-blue-600 hover:text-white' }} transition-all duration-200">
                                                Gentle Baby
                                            </a>
                                            <a href="{{ route('products') }}?product=healo"
                                                class="block px-4 py-2 text-sm {{ request('product') == 'healo' ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-700 hover:bg-blue-600 hover:text-white' }} transition-all duration-200">
                                                Healo
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mamina ASI Booster (no submenu, no arrow) -->
                                <a href="{{ route('products') }}?product=mamina-asi-booster"
                                    class="flex items-center px-4 py-3 text-sm {{ request('product') == 'mamina-asi-booster' ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-700 hover:bg-blue-600 hover:text-white' }} transition-all duration-200">
                                    <span class="font-medium">Mamina ASI Booster</span>
                                </a>

                                <!-- Nyam (no submenu, no arrow) -->
                                <a href="{{ route('products') }}?product=nyam"
                                    class="flex items-center px-4 py-3 text-sm {{ request('product') == 'nyam' ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-700 hover:bg-blue-600 hover:text-white' }} transition-all duration-200">
                                    <span class="font-medium">Nyam</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    @if(!$hasInventoryAccess)
                    <a href="{{ route('shopping') }}" target="_blank"
                        class="text-sm xl:text-base text-gray-600 hover:text-blue-600 transition-colors duration-200">
                        Belanja
                    </a>
                    @endif
                    
                    <a href="{{ route('articles') }}"
                        class="text-sm xl:text-base {{ Route::currentRouteName() == 'articles' ? 'text-blue-600 font-bold' : 'text-gray-600' }} hover:text-blue-600 transition-colors duration-200">
                        Artikel
                    </a>
                    
                    <!-- Partner Dropdown -->
                    <div class="relative group">
                        <button id="partner-dropdown-btn"
                            class="text-sm xl:text-base {{ Route::currentRouteName() == 'affiliate' ? 'text-blue-600 font-bold' : 'text-gray-600' }} hover:text-blue-600 transition-colors duration-200 flex items-center space-x-1 focus:outline-none">
                            <span>Partner</span>
                            <svg class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="partner-dropdown-menu"
                            class="absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 z-50">
                            <div class="py-3">
                                <!-- Reseller (no submenu, no arrow) -->
                                <a href="{{ route('reseller') }}"
                                    class="flex items-center px-4 py-3 text-sm {{ Route::currentRouteName() == 'reseller' ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-700 hover:bg-blue-600 hover:text-white' }} transition-all duration-200">
                                    <span class="font-medium">Reseller</span>
                                </a>

                                <!-- Affiliate (no submenu, no arrow) -->
                                <a href="{{ route('affiliate') }}?type=affiliate"
                                    class="flex items-center px-4 py-3 text-sm {{ request('type') == 'affiliate' ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-700 hover:bg-blue-600 hover:text-white' }} transition-all duration-200">
                                    <span class="font-medium">Affiliate</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('about-us') }}"
                        class="text-sm xl:text-base {{ Route::currentRouteName() == 'about-us' ? 'text-blue-600 font-bold' : 'text-gray-600' }} hover:text-blue-600 transition-colors duration-200">
                        Tentang Kami
                    </a>
                </nav>

                <!-- User Authentication Section -->
                <div class="flex items-center space-x-4">
                    @if(!$currentUser)
                        <a href="{{ route('login') }}"
                            class="h-10 px-6 py-2 text-sm lg:text-base font-nunito font-medium text-blue-600 border border-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition-all duration-300 flex items-center">
                            Login
                        </a>
                    @else
                        <!-- User Menu for Authenticated Users -->
                        <div class="relative" id="user-profile-dropdown">
                            <button type="button" id="user-profile-toggle"
                                class="h-10 flex items-center space-x-2 px-4 py-2 text-gray-700 hover:text-blue-600 transition-colors duration-200 border border-transparent rounded-full">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="font-nunito font-medium text-sm lg:text-base">
                                    {{ $isCustomer ? $currentUser->name_customer : $currentUser->name }}
                                </span>
                                <svg id="user-profile-arrow" class="h-4 w-4 transition-transform duration-200" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="user-profile-menu"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 transition-all duration-200 z-50 hidden">
                                <div class="py-1">
                                    @if($isAdmin)
                                        <a href="{{ route('admin.view-data') }}"
                                            class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                                </path>
                                            </svg>
                                            Dashboard Admin
                                        </a>
                                        <div class="border-t border-gray-100"></div>
                                    @endif
                                    @if($hasInventoryAccess)
                                        <a href="{{ route('admin.inventory.dashboard') }}"
                                            class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                                            <svg class="h-5 w-5 mr-2" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path d="M20 13H4c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2zm0 6H4v-4h16v4zm0-10H4c-1.1 0-2 .9-2 2v4h2v-4h16v4h2v-4c0-1.1-.9-2-2-2zm0-7H4c-1.1 0-2 .9-2 2v4h2V4h16v4h2V4c0-1.1-.9-2-2-2z"></path>
                                            </svg>
                                            Dashboard Inventory
                                        </a>
                                        <div class="border-t border-gray-100"></div>
                                    @endif
                                    @if(!$hasInventoryAccess)
                                    <a target="_blank" href="{{ route('shopping') }}"
                                        class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                        Belanja
                                    </a>
                                    <a href="{{ route('shopping.history') }}"
                                        class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                        Riwayat Pesanan
                                    </a>
                                    @endif
                                    <div class="border-t border-gray-100"></div>
                                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center w-full px-4 py-2 text-left text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-200">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                                </path>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div class="xl:hidden hidden" id="mobile-menu">
            <div class="pt-4 pb-3 space-y-1 border-t border-gray-200 mt-4">
                <nav class="space-y-1 font-nunito">
                    <a href="{{ route('home') }}"
                        class="block px-3 py-2 text-base {{ Route::currentRouteName() == 'home' ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-600' }} hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200">
                        Beranda
                    </a>

                    <!-- Mobile Produk Dropdown -->
                    <div class="relative">
                        <button id="mobile-produk-dropdown-btn"
                            class="w-full text-left px-3 py-2 text-base {{ Route::currentRouteName() == 'products' ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-600' }} hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200 flex items-center justify-between focus:outline-none group">
                            <span>Produk</span>
                            <svg class="w-4 h-4 transition-transform duration-200" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        <!-- Mobile Dropdown Menu -->
                        <div id="mobile-produk-dropdown-menu"
                            class="hidden pl-6 space-y-1 bg-gray-50 rounded-lg mt-1 mr-3 transition-all duration-300 overflow-hidden">

                            <!-- Gentle Living with Mobile Submenu -->
                            <div class="relative">
                                <button id="mobile-gentle-dropdown-btn"
                                    class="w-full text-left flex items-center justify-between px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-white rounded-md transition-all duration-200 focus:outline-none">
                                    <span>Gentle Living</span>
                                    <svg class="w-3 h-3 transition-transform duration-200" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>

                                <!-- Gentle Living Mobile Submenu -->
                                <div id="mobile-gentle-dropdown-menu"
                                    class="hidden pl-4 space-y-1 mt-1 transition-all duration-300">
                                    <a href="{{ route('products') }}?product=gentle-baby"
                                        class="block px-3 py-2 text-xs {{ request('product') == 'gentle-baby' ? 'text-blue-600 font-bold bg-white' : 'text-gray-500' }} hover:text-blue-600 hover:bg-white rounded-md transition-all duration-200">
                                        Gentle Baby
                                    </a>
                                    <a href="{{ route('products') }}?product=healo"
                                        class="block px-3 py-2 text-xs {{ request('product') == 'healo' ? 'text-blue-600 font-bold bg-white' : 'text-gray-500' }} hover:text-blue-600 hover:bg-white rounded-md transition-all duration-200">
                                        Healo
                                    </a>
                                </div>
                            </div>

                            <!-- Mamina ASI Booster (no submenu) -->
                            <a href="{{ route('products') }}?product=mamina-asi-booster"
                                class="flex items-center px-3 py-2 text-sm {{ request('product') == 'mamina-asi-booster' ? 'text-blue-600 font-bold bg-white' : 'text-gray-600' }} hover:text-blue-600 hover:bg-white rounded-md transition-all duration-200">
                                <span>Mamina ASI Booster</span>
                            </a>

                            <!-- Nyam (no submenu) -->
                            <a href="{{ route('products') }}?product=nyam"
                                class="flex items-center px-3 py-2 text-sm {{ request('product') == 'nyam' ? 'text-blue-600 font-bold bg-white' : 'text-gray-600' }} hover:text-blue-600 hover:bg-white rounded-md transition-all duration-200">
                                <span>Nyam</span>
                            </a>
                        </div>
                    </div>

                    @if(!$hasInventoryAccess)
                    <a href="{{ route('shopping') }}"
                        class="block px-3 py-2 text-base text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-all duration-200">
                        Belanja
                    </a>
                    @endif
                    
                    <a href="{{ route('articles') }}"
                        class="block px-3 py-2 text-base {{ Route::currentRouteName() == 'articles' ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-600' }} hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-all duration-200">
                        Artikel
                    </a>
                    
                    <!-- Mobile Partner Dropdown -->
                    <div class="relative">
                        <button id="mobile-partner-dropdown-btn"
                            class="w-full text-left px-3 py-2 text-base {{ Route::currentRouteName() == 'affiliate' || Route::currentRouteName() == 'reseller' ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-600' }} hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200 flex items-center justify-between focus:outline-none group">
                            <span>Partner</span>
                            <svg class="w-4 h-4 transition-transform duration-200" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        <!-- Mobile Partner Dropdown Menu -->
                        <div id="mobile-partner-dropdown-menu"
                            class="hidden pl-6 space-y-1 bg-gray-50 rounded-lg mt-1 mr-3 transition-all duration-300 overflow-hidden">
                            <a href="{{ route('reseller') }}"
                                class="flex items-center px-3 py-2 text-sm {{ Route::currentRouteName() == 'reseller' ? 'text-blue-600 font-bold bg-white' : 'text-gray-600' }} hover:text-blue-600 hover:bg-white rounded-md transition-all duration-200">
                                <span>Reseller</span>
                            </a>
                            <a href="{{ route('affiliate') }}?type=affiliate"
                                class="flex items-center px-3 py-2 text-sm {{ request('type') == 'affiliate' ? 'text-blue-600 font-bold bg-white' : 'text-gray-600' }} hover:text-blue-600 hover:bg-white rounded-md transition-all duration-200">
                                <span>Affiliate</span>
                            </a>
                        </div>
                    </div>
                    
                    <a href="{{ route('about-us') }}"
                        class="block px-3 py-2 text-base {{ Route::currentRouteName() == 'about-us' ? 'text-blue-600 font-bold' : 'text-gray-600' }} hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-all duration-200">
                        Tentang Kami
                    </a>
                </nav>

                <div class="pt-4 border-t border-gray-200">
                    @if($currentUser)
                        <!-- Authenticated User Mobile Menu -->
                        <div class="px-3 mb-4">
                            <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
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
                            <a href="{{ route('admin.view-data') }}"
                                class="block px-3 py-2 mb-2 text-base text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-all duration-200 font-nunito">
                                <svg class="inline h-5 w-5 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                    </path>
                                </svg>
                                Dashboard Admin
                            </a>
                        @endif

                        @if($hasInventoryAccess)
                            <a href="{{ route('admin.inventory.dashboard') }}"
                                class="block px-3 py-2 mb-2 text-base text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-all duration-200 font-nunito">
                                <svg class="inline h-6 w-6 mr-2" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path d="M20 13H4c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2zm0 6H4v-4h16v4zm0-10H4c-1.1 0-2 .9-2 2v4h2v-4h16v4h2v-4c0-1.1-.9-2-2-2zm0-7H4c-1.1 0-2 .9-2 2v4h2V4h16v4h2V4c0-1.1-.9-2-2-2z"></path>
                                </svg>
                                Dashboard Inventory
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="px-3">
                            @csrf
                            <button type="submit"
                                class="w-full text-center px-6 py-3 text-white bg-red-600 border border-red-600 rounded-full hover:bg-red-700 hover:border-red-700 transition-all duration-300 ease-in-out font-nunito font-medium">
                                Logout
                            </button>
                        </form>
                    @else
                        <!-- Guest Mobile Menu -->
                        <a href="{{ route('login') }}"
                            class="block mx-3 px-6 py-3 text-center text-blue-600 border border-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition-all duration-300 ease-in-out font-nunito font-medium">
                            Login
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileDropdown = document.getElementById('user-profile-dropdown');
        const profileToggle = document.getElementById('user-profile-toggle');
        const profileMenu = document.getElementById('user-profile-menu');
        const profileArrow = document.getElementById('user-profile-arrow');

        if (!profileDropdown || !profileToggle || !profileMenu || !profileArrow) {
            return;
        }

        profileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
            profileArrow.style.transform = profileMenu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        });

        document.addEventListener('click', function(e) {
            if (!profileDropdown.contains(e.target)) {
                profileMenu.classList.add('hidden');
                profileArrow.style.transform = 'rotate(0deg)';
            }
        });
    });
</script>
