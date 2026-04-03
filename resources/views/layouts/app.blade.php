<!DOCTYPE html>
<html class="scroll-smooth" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Halaman')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-tab.png') }}">

    {{-- tailwind --}}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --color-brand-50: #f0f9f9;
            --color-brand-100: #d9f2f1;
            --color-brand-200: #b7e6e4;
            --color-brand-300: #87d4d0;
            --color-brand-400: #56bbb6;
            --color-brand-500: #528b89;
            --color-brand-600: #446b6a;
            --color-brand-700: #3a5756;
            --color-brand-800: #324947;
            --color-brand-900: #2d3e3d;

            --color-success-50: #f0fdf4;
            --color-success-500: #22c55e;
            --color-success-600: #16a34a;

            --color-warning-50: #fffbeb;
            --color-warning-500: #f59e0b;
            --color-warning-600: #d97706;

            --color-danger-50: #fef2f2;
            --color-danger-500: #ef4444;
            --color-danger-600: #dc2626;

            --font-fredoka: "Fredoka One", cursive;
            --font-nunito: "Nunito", sans-serif;
            --font-instrument: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;

            --shadow-gentle: 0 1px 3px 0 rgba(0, 0, 0, 0.1),
                0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-gentle-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
                0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
    <!-- Local Fonts -->
    <style>
        @font-face {
            font-family: 'Fredoka One';
            src: url('{{ asset('assets/fonts/fredoka-v17-latin/fredoka-v17-latin-regular.woff2') }}') format('woff2');
            font-weight: 400;
            font-display: swap;
        }
        @font-face {
            font-family: 'Nunito';
            src: url('{{ asset('assets/fonts/nunito-v32-latin/nunito-v32-latin-regular.woff2') }}') format('woff2');
            font-weight: 400;
            font-display: swap;
        }
        @font-face {
            font-family: 'Instrument Sans';
            src: url('{{ asset('assets/fonts/instrument-sans-v4-latin/instrument-sans-v4-latin-regular.woff2') }}') format('woff2');
            font-weight: 400;
            font-display: swap;
        }
    </style>

    <script src="{{ asset('js/carousel.js') }}"></script>
    <script src="{{ asset('js/topbar.js') }}"></script>
</head>

<body>
    {{-- Include Topbar --}}
    @include('layouts.topbar')

    {{-- Main Content tanpa spacer - Banner hero akan mulai dari top --}}
    <main>
        @yield('content')
    </main>

    <!-- Footer Section -->
    @include('layouts.footer')
</body>

</html>

<!-- Mobile Navigation Menu -->
<div class="lg:hidden hidden" id="mobile-menu">
    <div class="pt-4 pb-3 space-y-1 border-t border-gray-200 mt-4">
        <nav class="space-y-1 font-fredoka">
            <a href="{{ route('home') }}"
                class="block px-3 py-2 text-base text-[#444444]/50 hover:text-[#614DAC] transition-colors duration-200">Beranda</a>

            <!-- Mobile Produk Dropdown dengan Tailwind CSS -->
            <div class="relative">
                <button id="mobile-produk-dropdown-btn"
                    class="w-full text-left px-3 py-2 text-base text-[#444444]/50 hover:text-[#614DAC] transition-colors duration-200 flex items-center justify-between focus:outline-none group">
                    <span>Produk</span>
                    <svg class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>

                <!-- Mobile Dropdown Menu dengan Tailwind CSS -->
                <div id="mobile-produk-dropdown-menu"
                    class="hidden pl-6 space-y-1 bg-gray-50 rounded-lg mt-1 mr-3 transition-all duration-300">
                    <a href="{{ route('products') }}?product=gentle-baby"
                        class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-[#614DAC] hover:bg-white rounded-md transition-all duration-200 group">
                        <span>Gentle Baby</span>
                    </a>
                    <a href="{{ route('products') }}?product=mamina-asi-booster"
                        class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-[#614DAC] hover:bg-white rounded-md transition-all duration-200 group">
                        <span>Mamina ASI Booster</span>
                    </a>
                    <a href="{{ route('products') }}?product=nyam"
                        class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-[#614DAC] hover:bg-white rounded-md transition-all duration-200 group">
                        <span>Nyam</span>
                    </a>
                </div>
            </div>

            <a href="#"
                class="block px-3 py-2 text-base text-[#444444]/50 hover:text-[#614DAC] transition-colors duration-200">Belanja</a>
            <a href="{{ route('affiliate') }}"
                class="block px-3 py-2 text-base text-[#444444]/50 hover:text-[#614DAC] transition-colors duration-200">Partner</a>
            <a href="{{ route('about-us') }}"
                class="block px-3 py-2 text-base text-[#444444]/50 hover:text-[#614DAC] transition-colors duration-200">Tentang
                Kami</a>
        </nav>
        <div class="pt-4 border-t border-gray-200">
            <a href="{{ route('login') }}" class="font-nunito
                            class="block mx-3 px-4 py-2
                text-center border border-[#6C63FF] text-[#6C63FF] rounded-full hover:bg-[#6C63FF] hover:text-white
                transition-all duration-300">
                Login
            </a>
        </div>
    </div>
</div>
</div>
</header>
</body>

</html>
