<!DOCTYPE html>
<html class="scroll-smooth" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Gentle Living')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-tab.png') }}">

    <!-- Vite CSS & JS (includes Tailwind) -->
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

    <!-- Admin Styles -->
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- JavaScript -->
    <script src="{{ asset('js/carousel.js') }}"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
</head>

<body>
    {{-- Admin Top Bar --}}
    @include('layouts.admin.topbar')

    <!-- Sidebar -->
    @include('layouts.admin.sidebar')

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="fixed top-16 sm:top-20 left-1/2 transform -translate-x-1/2 z-40 w-full max-w-md px-4">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded-r-lg shadow-lg"
                id="successAlert">
                <div class="flex items-center">
                    <x-heroicon-s-check-circle class="w-5 h-5 mr-2" />
                    <span style="font-family: 'Nunito', sans-serif;"
                        class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            </div>
        </div>
        <script>
            setTimeout(function() {
                const alert = document.getElementById('successAlert');
                if (alert) {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translate(-50%, -100%)';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 3000);
        </script>
    @endif

    {{-- Main Content --}}
    <main id="main-content" class="main-content transition-all duration-300 ease-in-out pt-16 lg:ml-16">
        @yield('content')
    </main>

    <!-- Footer Section -->
    <footer id="footer-content" class="main-content transition-all duration-300 ease-in-out lg:ml-16">
        @include('layouts.footer')
    </footer>

    @stack('scripts')
</body>

</html>
