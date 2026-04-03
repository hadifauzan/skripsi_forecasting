<!DOCTYPE html>
<html class="scroll-smooth" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gentle Living E-Commerce')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-tab.png') }}">

    <!-- Tailwind CSS CDN - Latest Version -->
    <script src="{{ asset('js/browser@4.js') }}"></script>
    
    <!-- Tailwind CSS Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'fredoka': ['Fredoka One', 'cursive'],
                        'nunito': ['Nunito', 'sans-serif'],
                        'instrument': ['Instrument Sans', 'sans-serif'],
                    },
                    maxWidth: {
                        '[1502px]': '1502px',
                    },
                    height: {
                        '[751px]': '751px',
                    },
                    width: {
                        '[1502px]': '1502px',
                    },
                    colors: {
                        'brand': {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#6366f1',
                            600: '#4338ca',
                            700: '#3730a3',
                            800: '#312e81',
                            900: '#1e1b4b',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS untuk Footer dan Styling Tambahan -->
    <style>
        /* Font Classes */
        .font-fredoka { font-family: 'Fredoka One', cursive; }
        .font-nunito { font-family: 'Nunito', sans-serif; }
        .font-instrument { font-family: 'Instrument Sans', sans-serif; }

        /* Brand Colors Fallback */
        .bg-brand-500 {
            background-color: #6366f1 !important;
        }

        /* Carousel Styling */
        .carousel-item {
            transition: opacity 0.5s ease-in-out;
        }
        
        .carousel-item.active {
            opacity: 1;
        }
        
        .carousel-dot.active {
            background-color: #2563eb;
        }

        /* Footer Enhancement */
        footer.bg-brand-500 {
            background: linear-gradient(135deg, #528B89 0%, #528B89 100%);
        }

        /* Button Hover Effects */
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        /* Smooth Transitions */
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
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

<body class="bg-gray-50">
    {{-- Include E-commerce Header --}}
    @include('layouts.ecommerce-header')

    <!-- Spacer untuk menggantikan ruang yang diambil oleh fixed header -->
    <div class="h-20"></div>

    {{-- Main Content  --}}
    <main>
        @yield('content')
    </main>

    <!-- Footer Section -->
    @include('layouts.footer')
</body>

</html>
