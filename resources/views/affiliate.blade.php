@extends('layouts.app')

@section('title', 'Partner - Gentle Living')

@section('content')
    @php
        use Illuminate\Support\Facades\Storage;
    @endphp
    <!-- Hero Section / partner -->
    <section id="partner" class="relative min-h-screen bg-gray-100 pt-20">
        <!-- Mobile: Banner Background (visible only on mobile) -->
        <div class="absolute inset-0 w-full h-full lg:hidden" id="banner-carousel-mobile">
            <!-- Slides Container -->
            <div class="h-full overflow-hidden relative z-0">
                @if ($carouselItems->count() >= 3)
                    @foreach ($carouselItems as $index => $item)
                        <img src="{{ $item->image ? Storage::url($item->image) : asset('images/banner/banner_partner' . ($index + 1) . '.png') }}"
                            alt="{{ $item->title }}"
                            class="banner-slide w-full h-full object-cover object-center absolute top-0 left-0 transition-all duration-700 {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}">
                    @endforeach
                @else
                    <img src="{{ asset('images/banner/banner_partner1.png') }}" alt="Banner 1"
                        class="banner-slide w-full h-full object-cover object-center absolute top-0 left-0 transition-all duration-700 opacity-100 z-10">
                    <img src="{{ asset('images/banner/banner_partner2.jpg') }}" alt="Banner 2"
                        class="banner-slide w-full h-full object-cover object-center absolute top-0 left-0 transition-all duration-700 opacity-0 z-0">
                    <img src="{{ asset('images/banner/banner_partner3.jpg') }}" alt="Banner 3"
                        class="banner-slide w-full h-full object-cover object-center absolute top-0 left-0 transition-all duration-700 opacity-0 z-0">
                @endif
            </div>

            <!-- Mobile: Overlay Gradient -->
            <div class="absolute inset-0 bg-gradient-to-b from-[#444444]/100 via-[#444444]/100 to-transparent z-10"></div>

            <!-- Mobile Content -->
            <div class="absolute inset-0 flex items-center justify-center z-20 px-4 sm:px-6 overflow-hidden">
                <div class="text-center max-w-xs sm:max-w-md md:max-w-lg mx-auto">
                    <!-- Main Title - Pink Color like in image -->
                    <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold leading-tight drop-shadow-lg mb-3 sm:mb-4 md:mb-6 font-fredoka break-words"
                        style="color: #F4A6A6;">
                        {{ $heroTitle->title ?? 'Join Our Baby Wellness Affiliate Program' }}
                    </h1>

                    <!-- Description - Beige/Light color like in image -->
                    <p class="text-xs sm:text-sm md:text-base leading-relaxed drop-shadow mb-4 sm:mb-6 md:mb-8 font-nunito break-words"
                        style="color: #D4B5A0;">
                        {{ $heroTitle->body ?? 'Kami sedang membuka program affiliate partnership untuk 3 produk best-seller kami yang fokus pada wellness bunda & bayi.' }}
                    </p>

                    <!-- Dynamic Caption Container - White text like in image -->
                    <div class="mb-3 sm:mb-4 md:mb-6 min-h-[2.5rem] sm:min-h-[3rem]">
                        <div id="content" class="text-sm sm:text-base md:text-lg lg:text-xl font-bold font-fredoka leading-tight text-white break-words">
                            <!-- Content will be updated by carousel.js -->
                        </div>
                    </div>

                    <!-- CTA Button - White background, dark text like in image -->
                    <a href="{{ route('affiliate.form') }}"
                        class="inline-block px-5 sm:px-6 md:px-8 lg:px-10 py-2.5 sm:py-3 md:py-4 bg-white text-gray-800 font-bold rounded-full shadow-lg hover:shadow-2xl hover:bg-gray-100 transform hover:scale-105 hover:-translate-y-1 transition-all duration-300 ease-in-out font-nunito text-xs sm:text-sm md:text-base">
                        DAFTAR SEKARANG
                    </a>

                    <!-- Navigation Indicators -->
                    <div id="carousel-indicators" class="flex justify-center space-x-2 mt-4 sm:mt-6 md:mt-8">
                        <button class="w-5 sm:w-6 md:w-8 h-1 rounded-full bg-white/40 hover:bg-white/80 transition-all duration-200">
                            <div class="indicator-line w-full h-full rounded-full bg-white/80 transition-all duration-300">
                            </div>
                        </button>
                        <button class="w-5 sm:w-6 md:w-8 h-1 rounded-full bg-white/40 hover:bg-white/80 transition-all duration-200">
                            <div class="indicator-line w-full h-full rounded-full bg-white/40 transition-all duration-300">
                            </div>
                        </button>
                        <button class="w-5 sm:w-6 md:w-8 h-1 rounded-full bg-white/40 hover:bg-white/80 transition-all duration-200">
                            <div class="indicator-line w-full h-full rounded-full bg-white/40 transition-all duration-300">
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Layout Container -->
        <div class="relative flex-col lg:flex-row hidden lg:flex lg:p-4" style="height: calc(100vh - 5rem);">
            <!-- Desktop Background Gradient -->
            <div
                class="absolute top-0 left-0 right-0 h-full bg-gradient-to-r from-[#444444]/100 via-[#444444]/100 to-transparent z-10">
            </div>

            <!-- Left Side - Text Content with Dynamic Caption -->
            <div class="relative w-full lg:w-1/2 flex items-center justify-center px-6 lg:px-16 xl:px-24 z-20">
                <div class="text-center lg:text-left py-8 lg:py-0 max-w-xl">
                    <!-- Main Title - Pink Color like in image, with line breaks -->
                    <h1 class="text-3xl lg:text-4xl xl:text-5xl font-bold leading-tight mb-4 lg:mb-6 xl:mb-8 font-fredoka break-words"
                        style="color: #F4A6A6;">
                        {!! $heroTitle ? nl2br(e($heroTitle->title)) : 'Join Our Baby<br>Wellness Affiliate<br>Program' !!}
                    </h1>

                    <!-- Description - Beige/Light color like in image -->
                    <p class="text-sm lg:text-base leading-relaxed font-nunito mb-4 lg:mb-6 xl:mb-8 break-words"
                        style="color: #D4B5A0;">
                        {{ $heroTitle->body ?? 'Kami sedang membuka program affiliate partnership untuk 3 produk best-seller kami yang fokus pada wellness bunda & bayi.' }}
                    </p>

                    <!-- Desktop Dynamic Caption Container - White text like in image -->
                    <div class="mb-4 lg:mb-6 xl:mb-8 min-h-[3rem]">
                        <div id="desktop-content"
                            class="text-lg lg:text-xl xl:text-2xl font-bold font-fredoka leading-tight text-white break-words">
                            <!-- Content will be updated by carousel.js -->
                        </div>
                    </div>

                    <!-- CTA Button - White background, dark text like in image -->
                    <a href="{{ route('affiliate.form') }}"
                        class="inline-block px-6 lg:px-8 xl:px-10 py-2.5 lg:py-3 xl:py-4 bg-white text-gray-800 font-bold rounded-full shadow-lg hover:shadow-2xl hover:bg-gray-100 transform hover:scale-105 hover:-translate-y-1 transition-all duration-300 ease-in-out font-nunito text-sm lg:text-base">
                        DAFTAR SEKARANG
                    </a>
                </div>
            </div>

            <!-- Right Side - Desktop Banner Carousel -->
            <div class="relative w-full lg:w-1/2 h-full" id="banner-carousel">
                <!-- Slides Container -->
                <div class="h-full overflow-hidden relative">
                    @if ($carouselItems->count() >= 3)
                        @foreach ($carouselItems as $index => $item)
                            <img src="{{ $item->image ? Storage::url($item->image) : asset('images/banner/banner_partner' . ($index + 1) . '.png') }}"
                                alt="{{ $item->title }}"
                                class="banner-slide w-full h-full object-cover object-center absolute transition-all duration-700 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
                        @endforeach
                    @else
                        <img src="{{ asset('images/banner/banner_partner1.png') }}" alt="Banner 1"
                            class="banner-slide w-full h-full object-cover object-center absolute top-0 left-0 transition-all duration-700 opacity-100 z-10">
                        <img src="{{ asset('images/banner/banner_partner2.jpg') }}" alt="Banner 2"
                            class="banner-slide w-full h-full object-cover object-center absolute top-0 left-0 transition-all duration-700 opacity-0 z-0">
                        <img src="{{ asset('images/banner/banner_partner3.jpg') }}" alt="Banner 3"
                            class="banner-slide w-full h-full object-cover object-center absolute top-0 left-0 transition-all duration-700 opacity-0 z-0">
                    @endif
                </div>
                <!-- Desktop: Subtle overlay -->
                <div class="absolute inset-0 bg-gradient-to-r from-black/5 to-transparent"></div>
            </div>
        </div>
    </section>

    <!-- Include Carousel Data Initialization Script -->
    <script src="{{ asset('js/affiliate-carousel-data.js') }}"></script>
    
    <!-- Initialize Carousel Data -->
    <script>
        // Initialize carousel data from database
        initAffiliateCarouselData([
            @if ($carouselItems->count() >= 3)
                @foreach ($carouselItems as $item)
                    {
                        title: {!! json_encode($item->title) !!},
                        description: {!! json_encode($item->body) !!}
                    }
                    @if (!$loop->last)
                        ,
                    @endif
                @endforeach
            @else
                {
                    title: "Gentlebaby Massage Oil",
                    description: "bantu atasi bayi rewel dan rileks, juga media bonding dengan ayah bunda"
                }, {
                    title: "Mamina ASI Booster",
                    description: "herbal booster ASI alami, tanpa efek samping dan pemanis perisa"
                }, {
                    title: "Nyam BB Booster",
                    description: "MPASI booster untuk berat badan bayi, diformulasi oleh dokter dan konselor MPASI"
                }
            @endif
        ]);
    </script>

    <!-- Include Carousel JavaScript -->
    <script src="{{ asset('js/carousel.js') }}"></script>

    <!-- Produk Section -->
    <section id="products" class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Why Join Us Header -->
            <div class="text-center mb-8 sm:mb-12 lg:mb-16">
                <h2 class="font-fredoka text-2xl sm:text-3xl lg:text-4xl text-[#614DAC] mb-4 sm:mb-6 lg:mb-8">
                    {{ $whyJoinTitle->title ?? 'Why Join Us' }}
                </h2>
                <p
                    class="font-nunito text-sm sm:text-base lg:text-lg text-[#4D4C4C] leading-relaxed max-w-4xl mx-auto px-2 sm:px-0">
                    {{ $whyJoinTitle->body ?? 'Kami percaya produk ini sangat cocok untuk audience kami yang didominasi moms, new parents, breastfeeding moms, dan pejuang MPASI. Helping Moms - Earning with Purpose' }}
                </p>
            </div>

            <!-- Benefits Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 lg:gap-8 mb-8 sm:mb-12 lg:mb-16">
                @php
                    $iconSvgPaths = [
                        'dollar' =>
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.598 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                        'gift' =>
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>',
                        'star' =>
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>',
                        'chart' =>
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>',
                        'users' =>
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a4 4 0 11-8 0 4 4 0 018 0z"></path>',
                        'support' =>
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>',
                        'trophy' =>
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>',
                        'lightning' =>
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>',
                    ];
                @endphp

                @foreach ($benefits as $index => $benefit)
                    <!-- Benefit {{ $index + 1 }} -->
                    <div
                        class="bg-white rounded-xl shadow-md p-4 sm:p-5 flex items-start gap-3 sm:gap-4 hover:shadow-lg transition-shadow duration-300">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-full bg-[#F8F5F8] shadow-sm flex-shrink-0">
                            @php
                                $selectedIcon = $benefit->image ?? 'star';
                                $iconPath = $iconSvgPaths[$selectedIcon] ?? $iconSvgPaths['star'];
                            @endphp
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-[#444444]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $iconPath !!}
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-nunito font-bold text-[#4D4C4C] text-sm sm:text-base lg:text-lg mb-1">
                                {{ $benefit->title }}
                            </h3>
                            <p class="font-nunito text-[#4D4C4C] text-xs sm:text-sm leading-relaxed break-words">
                                {{ $benefit->body }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- What you will get Section -->
        <div class="py-10 sm:py-16 bg-white mb-8 sm:mb-12 lg:mb-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <!-- Section Header -->
                <div class="mb-8 sm:mb-12">
                    <h2 class="font-fredoka text-2xl sm:text-3xl lg:text-4xl text-[#614DAC] mb-4">
                        {{ $whatYouGetTitle->title ?? 'What you will get' }}
                    </h2>
                    @if ($whatYouGetTitle && $whatYouGetTitle->body)
                        <p
                            class="font-nunito text-sm sm:text-base lg:text-lg text-[#4D4C4C] leading-relaxed max-w-4xl mx-auto">
                            {{ $whatYouGetTitle->body }}
                        </p>
                    @endif
                </div>

                <!-- Cards Grid -->
                <div class="max-w-6xl mx-auto">
                    @php
                        $iconSvgPaths = [
                            'dollar' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.598 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                            'gift' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>',
                            'star' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>',
                            'chart' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>',
                            'users' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a4 4 0 11-8 0 4 4 0 018 0z"></path>',
                            'support' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>',
                            'trophy' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>',
                            'lightning' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>',
                        ];

                        $firstRowItems = $whatYouGet->take(3);
                        $secondRowItems = $whatYouGet->skip(3)->take(2);
                    @endphp

                    @if ($firstRowItems->count() > 0)
                        <!-- Baris pertama - 3 item -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8 mb-6 sm:mb-8">
                            @foreach ($firstRowItems as $index => $item)
                                <div
                                    class="bg-white rounded-lg p-4 sm:p-5 lg:p-6 text-left border border-gray-200 hover:border-blue-400 hover:shadow-md transition-all duration-200 shadow-sm {{ $index == 2 ? 'sm:col-span-2 lg:col-span-1' : '' }}">
                                    <div class="flex items-start">
                                        <div class="bg-blue-100 p-2 sm:p-3 rounded-full mr-3 sm:mr-4 flex-shrink-0">
                                            @php
                                                $selectedIcon = $item->image ?? 'dollar';
                                                $iconPath = $iconSvgPaths[$selectedIcon] ?? $iconSvgPaths['dollar'];
                                            @endphp
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-[#444444]" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                {!! $iconPath !!}
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class=" text-gray-800 text-sm sm:text-base leading-tight">
                                                {{ $item->title }}
                                            </h4>
                                            @if ($item->body)
                                                <p class="text-gray-600 text-xs sm:text-sm leading-tight mt-1">
                                                    {{ $item->body }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if ($secondRowItems->count() > 0)
                        <!-- Baris kedua - 2 item di tengah -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 lg:gap-8 max-w-4xl mx-auto">
                            @foreach ($secondRowItems as $index => $item)
                                <div
                                    class="bg-white rounded-lg p-4 sm:p-5 lg:p-6 text-left border border-gray-200 hover:border-blue-400 hover:shadow-md transition-all duration-200 shadow-sm">
                                    <div class="flex items-start">
                                        <div class="bg-blue-100 p-2 sm:p-3 rounded-full mr-3 sm:mr-4 flex-shrink-0">
                                            @php
                                                $selectedIcon = $item->image ?? 'dollar';
                                                $iconPath = $iconSvgPaths[$selectedIcon] ?? $iconSvgPaths['dollar'];
                                            @endphp
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-[#444444]" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                {!! $iconPath !!}
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-gray-800 text-sm sm:text-base leading-tight">
                                                {{ $item->title }}
                                            </h4>
                                            @if ($item->body)
                                                <p class="text-gray-600 text-xs sm:text-sm leading-tight mt-1">
                                                    {{ $item->body }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Perfect for you Section -->
        <div class="py-10 sm:py-16 bg-white mb-8 sm:mb-12 lg:mb-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <!-- Section Header -->
                <div class="mb-8 sm:mb-12">
                    <h2 class="font-fredoka text-2xl sm:text-3xl lg:text-4xl text-[#614DAC] mb-2">
                        {{ $perfectForTitle->title ?? 'Perfect for you' }}
                    </h2>
                </div>

                <!-- Cards Grid - 5 Columns Layout -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6 lg:gap-8">
                    @foreach ($perfectFor as $index => $target)
                        <!-- Card -->
                        <div
                            class="bg-white rounded-lg border border-gray-200 hover:border-blue-400 hover:shadow-md transition-all duration-200 shadow-sm 
                           flex items-center justify-center text-center h-48 p-6">

                            <!-- Wrapper agar isi melebar -->
                            <div class="w-full">
                                <h4 class="font-bold text-gray-800 text-sm sm:text-base mb-2">
                                    {{ $target->title }}
                                </h4>
                                <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">
                                    {{ $target->body }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


        <!-- Meet The Product Section - Dynamic from Database -->
        <div class="text-center py-10 lg:py-14 px-4 sm:px-6 lg:px-8 ">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl text-[#6C63FF] mb-8 lg:mb-12 font-fredoka">
                Produk Terlaris Kami
            </h2>
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 lg:gap-12">
                    @forelse($topProducts as $product)
                        <!-- Dynamic Product Card -->
                        <div
                            class="bg-gradient-to-b from-white to-blue-50 rounded-xl border-2 border-blue-400 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col">
                            <div class="relative">
                                <div class="p-6 flex flex-col flex-1">
                                    <!-- Product Image -->
                                    <div class="w-full rounded-lg mb-5 overflow-hidden flex items-center justify-center bg-gray-50 p-3"
                                        style="height: 200px;">
                                        <img src="{{ $product->display_image }}" alt="{{ $product->display_name }}"
                                            class="object-contain transition-transform duration-300 hover:scale-110 max-h-full">
                                    </div>
                                    <!-- Product Info -->
                                    <div class="text-center flex-1 flex flex-col justify-between">
                                        <div>
                                            <h3 class="font-fredoka text-gray-800 mb-2 text-xl">
                                                {{ $product->display_name }}</h3>
                                            <p class="text-sm text-gray-600 font-nunito mb-2">
                                                {{ $product->display_description }}</p>
                                            @if ($product->transaction_sales_details_sum_qty)
                                                <p class="text-xs text-green-600 font-nunito font-semibold mb-4">
                                                    {{ number_format($product->transaction_sales_details_sum_qty) }}+
                                                    Terjual
                                                </p>
                                            @endif
                                        </div>
                                        <a href="{{ $product->shopping_url }}" target="_blank" rel="noopener noreferrer"
                                            class="w-full bg-gradient-to-r from-[#6C63FF] to-[#8B7FFF] text-white font-nunito font-semibold py-3 px-4 text-sm rounded-lg mt-auto hover:from-[#5B52EE] hover:to-[#7A6EEE] hover:shadow-lg transform hover:scale-[1.02] transition-all duration-300 block text-center">
                                            Lihat Produk
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Fallback jika tidak ada data produk -->
                        <div class="col-span-full text-center py-8">
                            <p class="text-gray-500 font-nunito">Data produk sedang dimuat...</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Section - TikTok Videos -->
<section class="py-12 sm:py-16 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl text-[#614DAC] font-fredoka mb-3">
                Telah dipercaya {{ number_format($customerCount, 0, ',', '.') }}+ Ibu
            </h2>
            <p class="text-gray-600 text-sm sm:text-base max-w-xl mx-auto font-nunito">
                Lihat testimoni nyata dari para ibu yang sudah merasakan manfaat produk kami
            </p>
        </div>

        {{-- TikTok Videos Grid --}}
        @if($videos->count() > 0)
            @php
                $videoCount = $videos->count();
                // Dynamic grid columns based on video count
                $gridClass = match($videoCount) {
                    1 => 'grid-cols-1 max-w-sm mx-auto',
                    2 => 'grid-cols-1 sm:grid-cols-2 max-w-2xl mx-auto',
                    3 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 max-w-4xl mx-auto',
                    default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4'
                };
            @endphp
            <div class="grid {{ $gridClass }} gap-6">
                @foreach ($videos as $video)
                    <div class="group overflow-hidden transition-all duration-300">

                        {{-- Video Embed --}}
                        <div class="relative aspect-[9/16] w-full bg-gray-100 overflow-hidden rounded-2xl">
                            <blockquote class="tiktok-embed h-full w-full group-hover:scale-105"
                                cite="{{ $video->video_url }}"
                                data-video-id="{{ basename(parse_url($video->video_url, PHP_URL_PATH)) }}"
                                style="max-width: 100% !important; min-width: 100% !important; height: 100% !important; margin: 0 !important;">
                                <section></section>
                            </blockquote>

                            <!-- Bottom Gradient -->
                            <div class="absolute bottom-0 left-0 right-0 h-28 bg-gradient-to-t from-black/70 to-transparent pointer-events-none z-10"></div>

                            <!-- Username Overlay -->
                            <div class="absolute bottom-4 left-0 right-0 px-4 pointer-events-none z-20">
                                <p class="text-white text-sm font-bold drop-shadow-lg font-nunito">
                                    {{ '@' . $video->username }}
                                </p>
                            </div>
                        </div>

                        {{-- CTA Button --}}
                        <a href="{{ $video->video_url }}" target="_blank" rel="noopener noreferrer"
                            class="block text-center mx-4 my-4 bg-[#EE1D52] hover:bg-[#ee5589]
                                   text-white font-bold py-2.5 rounded-full shadow-md transition-all duration-300 hover:scale-105">
                            Tonton Video
                        </a>

                    </div>
                @endforeach
            </div>
        @else
            {{-- Fallback: Default videos jika belum ada data di database --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                @foreach ($tiktokVideos as $video)
                    <div class="group overflow-hidden transition-all duration-300">

                        {{-- Video Embed --}}
                        <div class="relative aspect-[9/16] w-full bg-gray-100 overflow-hidden rounded-2xl">
                            <blockquote class="tiktok-embed h-full w-full group-hover:scale-105"
                                cite="{{ $video['url'] }}"
                                data-video-id="{{ basename(parse_url($video['url'], PHP_URL_PATH)) }}"
                                style="max-width: 100% !important; min-width: 100% !important; height: 100% !important; margin: 0 !important;">
                                <section></section>
                            </blockquote>

                            <!-- Bottom Gradient -->
                            <div class="absolute bottom-0 left-0 right-0 h-28 bg-gradient-to-t from-black/70 to-transparent pointer-events-none z-10"></div>

                            <!-- Username Overlay -->
                            <div class="absolute bottom-4 left-0 right-0 px-4 pointer-events-none z-20">
                                <p class="text-white text-sm font-bold drop-shadow-lg font-nunito">
                                    {{ $video['username'] }}
                                </p>
                            </div>
                        </div>

                        {{-- CTA Button --}}
                        <a href="{{ $video['url'] }}" target="_blank" rel="noopener noreferrer"
                            class="block text-center mx-4 my-4 bg-[#EE1D52] hover:bg-[#ee5589]
                                   text-white font-bold py-2.5 rounded-full shadow-md transition-all duration-300 hover:scale-105">
                            Tonton Video
                        </a>

                    </div>
                @endforeach

            </div>
        @endif

    </div>

    {{-- TikTok Embed Script --}}
    <script async src="https://www.tiktok.com/embed.js"></script>
</section>


    {{-- TikTok Embed Script --}}
    <script async src="https://www.tiktok.com/embed.js"></script>

    <!-- How to Join Section -->
    <section class="py-12 sm:py-16 lg:py-20 bg-[#B8E6D9]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl text-[#6C63FF] mb-6 text-center lg:text-left font-fredoka">
                {{ $howToJoinTitle->title ?? 'How to Join' }}
            </h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                <!-- Left Side - Content -->
                <div class="order-2 lg:order-1">
                    <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 lg:p-8 shadow-lg">
                        <h3 class="text-xl lg:text-2xl text-brand-500 mb-3 text-center lg:text-left font-fredoka">
                            Work Easy, Earn More
                        </h3>
                        <p
                            class="text-gray-600 text-sm lg:text-base leading-relaxed mb-6 lg:mb-8 text-center lg:text-left font-nunito">
                            Kami bisa bantu rekomendasikan bikin konten/script sesuai gaya kamu
                        </p>

                        <!-- CTA Button -->
                        <div class="text-center lg:text-left">
                            <button
                                class="relative bg-gradient-to-r from-[#FF6B6B] to-[#FF9191] text-white px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-bold shadow-md overflow-hidden transform transition duration-300 ease-in-out hover:shadow-xl hover:scale-105 hover:-translate-y-1 group w-full sm:w-auto">
                                <a class="relative z-10" href="{{ route('affiliate.form') }}">DAFTAR SEKARANG</a>
                                <!-- Hover Gradient Overlay -->
                                <span
                                    class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-[#D94C4C] to-[#FF6B6B] transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left z-0 rounded-xl"></span>
                                <!-- Shine Effect -->
                                <span
                                    class="absolute top-0 right-0 w-8 h-full bg-white/20 skew-x-[30deg] transform translate-x-32 group-hover:translate-x-0 transition-all duration-1000 z-0"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Steps -->
                <div class="space-y-8 order-1 lg:order-2">
                    @foreach ($steps as $index => $step)
                        <!-- Step {{ $index + 1 }} -->
                        <div class="flex items-start space-x-4 relative">
                            <div class="relative flex flex-col items-center">
                                <!-- Circle -->
                                <div
                                    class="bg-[#FF6B6B] w-12 h-12 rounded-full flex items-center justify-center shadow-lg z-10">
                                    <span class="text-white font-bold text-lg">{{ $index + 1 }}</span>
                                </div>
                                <!-- Vertical Line -->
                                @if ($index < $steps->count() - 1)
                                    <div
                                        class="w-1 h-16 bg-[#D2F4E4] absolute top-12 left-1/2 transform -translate-x-1/2 z-0">
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 mt-2">
                                <p class="text-[#D94C4C] font-bold text-sm sm:text-base leading-relaxed font-nunito">
                                    {{ $step->title }}
                                </p>
                                @if ($step->body)
                                    <p class="text-[#D94C4C] text-sm leading-relaxed font-nunito mt-1">
                                        {{ $step->body }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
