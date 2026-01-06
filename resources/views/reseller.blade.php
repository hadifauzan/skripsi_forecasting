@extends('layouts.app')
@section('title', 'Reseller - Gentle Living')

@section('content')

    @php
        // DEBUG: Check if data is coming from database
        $debugMode = false; // Set to true to see debug info

        if ($debugMode) {
            echo "<!-- DEBUG INFO:\n";
            echo 'Benefits count: ' . (isset($benefits) ? collect($benefits)->count() : '0') . "\n";
            echo 'WhyJoinTitle: ' . (isset($whyJoinTitle) && $whyJoinTitle ? $whyJoinTitle->title : 'Not set') . "\n";
            echo 'Steps count: ' . (isset($steps) ? collect($steps)->count() : '0') . "\n";
            echo '-->';
        }

        // Ensure collections for easier processing in view
        $benefits = collect($benefits ?? []);
        $whatYouGet = collect($whatYouGet ?? []);
        $perfectFor = collect($perfectFor ?? []);
        $steps = collect($steps ?? []);

        // Provide default values for titles if not found
        $whyJoinTitle = $whyJoinTitle ?? (object) ['title' => 'Why Join Us', 'body' => ''];
        $whatYouGetTitle = $whatYouGetTitle ?? (object) ['title' => 'What You Will Get', 'body' => ''];
        $perfectForTitle = $perfectForTitle ?? (object) ['title' => 'Perfect For You', 'body' => ''];
        $testimonialTitle = $testimonialTitle ?? (object) ['title' => 'Testimonial', 'body' => ''];
        $testimonial = $testimonial ?? (object) ['title' => '', 'body' => ''];
        $howToJoinTitle = $howToJoinTitle ?? (object) ['title' => 'How To Join', 'body' => ''];
        $bannerImage = $bannerImage ?? (object) ['title' => 'Join As Reseller', 'body' => '', 'image' => null];

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

    {{-- Hero Banner Section (revisi posisi konten lebih ke bawah) --}}
    <section id="hero" class="relative w-full min-h-screen overflow-hidden bg-gray-900">
        @if ($bannerImage && $bannerImage->image)
            <img src="{{ Storage::url($bannerImage->image) }}" alt="Banner Reseller"
                class="absolute inset-0 w-full h-full object-cover object-center">
        @else
            <div
                class="absolute inset-0 w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-800 to-gray-900">
                <i class="fas fa-image text-gray-400 text-5xl"></i>
            </div>
        @endif

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/40 to-black/60"></div>

        {{-- Content --}}
        <div
            class="absolute inset-0 flex flex-col items-center justify-end text-center px-4 sm:px-6 pb-24 sm:pb-32 lg:pb-40">
            <h1
                class="text-3xl sm:text-4xl lg:text-5xl font-fredoka font-bold text-[#F4A6A6] leading-tight mb-4 drop-shadow-lg max-w-3xl">
                {{ $bannerImage->title ?? 'Judul Banner Reseller' }}
            </h1>
            <p class="text-base sm:text-lg lg:text-xl text-[#D4B5A0] font-nunito max-w-2xl leading-relaxed mb-6">
                {{ $bannerImage->body ?? 'Deskripsi singkat banner reseller bisa ditulis di sini.' }}
            </p>
            <a href="{{ route('reseller.form') }}"
                class="inline-flex items-center px-6 py-3 bg-white text-gray-900 font-nunito font-semibold text-sm rounded-full shadow-lg hover:bg-gray-100 hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                DAFTAR SEKARANG
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </section>

    {{-- Why Join Us Section --}}
    <section class="py-12 sm:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            {{-- Header --}}
            <div class="text-center mb-10 sm:mb-12">
                <h2 class="font-fredoka text-2xl sm:text-3xl text-[#614DAC] mb-3">
                    {{ $whyJoinTitle->title ?? 'Why Join Us' }}
                </h2>
                <p class="font-nunito text-sm sm:text-base text-[#4D4C4C] leading-relaxed max-w-3xl mx-auto">
                    {{ $whyJoinTitle->body }}
                </p>
            </div>

            {{-- Benefits Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                @foreach ($benefits as $benefit)
                    <div
                        class="group bg-white rounded-xl shadow-sm hover:shadow-md p-5 flex items-start gap-4 transition-all duration-300 border border-gray-100">
                        <div
                            class="w-10 h-10 flex items-center justify-center rounded-lg bg-gradient-to-br from-purple-50 to-blue-50 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            @php
                                $selectedIcon = $benefit->image ?? 'star';
                                $iconPath = $iconSvgPaths[$selectedIcon] ?? $iconSvgPaths['star'];
                            @endphp
                            <svg class="w-5 h-5 text-[#614DAC]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $iconPath !!}
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-nunito font-bold text-[#4D4C4C] text-base mb-1">
                                {{ $benefit->title }}
                            </h3>
                            <p class="font-nunito text-[#6B7280] text-sm leading-relaxed">
                                {{ $benefit->body }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- What You Will Get Section --}}
    <section class="py-12 sm:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            {{-- Header --}}
            <div class="text-center mb-10 sm:mb-12">
                <h2 class="font-fredoka text-2xl sm:text-3xl text-[#614DAC] mb-3">
                    {{ $whatYouGetTitle->title ?? 'What you will get' }}
                </h2>
                @if ($whatYouGetTitle && $whatYouGetTitle->body)
                    <p class="font-nunito text-sm sm:text-base text-[#4D4C4C] leading-relaxed max-w-3xl mx-auto">
                        {{ $whatYouGetTitle->body }}
                    </p>
                @endif
            </div>

            {{-- Cards Grid --}}
            @php
                $firstRowItems = $whatYouGet->take(3);
                $secondRowItems = $whatYouGet->skip(3)->take(2);
            @endphp

            <div class="space-y-5">
                @if ($firstRowItems->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
                        @foreach ($firstRowItems as $item)
                            <div
                                class="group bg-gradient-to-br from-white to-blue-50/30 rounded-xl border border-blue-100 hover:border-blue-300 p-5 hover:shadow-md transition-all duration-300">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="bg-gradient-to-br from-blue-100 to-blue-200 p-2.5 rounded-lg flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                        @php
                                            $selectedIcon = $item->image ?? 'dollar';
                                            $iconPath = $iconSvgPaths[$selectedIcon] ?? $iconSvgPaths['dollar'];
                                        @endphp
                                        <svg class="w-5 h-5 text-[#614DAC]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            {!! $iconPath !!}
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-nunito font-bold text-gray-800 text-sm mb-1">
                                            {{ $item->title }}
                                        </h4>
                                        @if ($item->body)
                                            <p class="text-gray-600 text-xs leading-relaxed">
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5 max-w-4xl mx-auto">
                        @foreach ($secondRowItems as $item)
                            <div
                                class="group bg-gradient-to-br from-white to-blue-50/30 rounded-xl border border-blue-100 hover:border-blue-300 p-5 hover:shadow-md transition-all duration-300">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="bg-gradient-to-br from-blue-100 to-blue-200 p-2.5 rounded-lg flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                        @php
                                            $selectedIcon = $item->image ?? 'dollar';
                                            $iconPath = $iconSvgPaths[$selectedIcon] ?? $iconSvgPaths['dollar'];
                                        @endphp
                                        <svg class="w-5 h-5 text-[#614DAC]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            {!! $iconPath !!}
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-nunito font-bold text-gray-800 text-sm mb-1">
                                            {{ $item->title }}
                                        </h4>
                                        @if ($item->body)
                                            <p class="text-gray-600 text-xs leading-relaxed">
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
    </section>

    {{-- Perfect For You Section --}}
    <section class="py-12 sm:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            {{-- Header --}}
            <div class="text-center mb-10 sm:mb-12">
                <h2 class="font-fredoka text-2xl sm:text-3xl text-[#614DAC]">
                    {{ $perfectForTitle->title ?? 'Perfect for you' }}
                </h2>
            </div>

            {{-- Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-5">
                @foreach ($perfectFor as $target)
                    <div
                        class="group bg-white rounded-xl border border-gray-200 hover:border-purple-300 hover:shadow-md transition-all duration-300 p-5 text-center flex flex-col items-center justify-center min-h-[180px]">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-purple-100 to-blue-100 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-[#614DAC]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h4 class="font-fredoka font-bold text-gray-800 text-sm mb-2">
                            {{ $target->title }}
                        </h4>
                        <p class="text-gray-600 text-xs leading-relaxed">
                            {{ $target->body }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Meet The Product Section --}}
    <section class="py-12 sm:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <h2 class="text-2xl sm:text-3xl text-[#6C63FF] mb-10 text-center font-fredoka">
                Produk Terlaris Kami
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                @forelse($topProducts as $product)
                    <!-- Dynamic Product Card -->
                    <div
                        class="group bg-white rounded-xl border-2 border-blue-200 hover:border-blue-400 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col">
                        <!-- Product Image -->
                        <div class="relative bg-gradient-to-b from-blue-50 to-white p-6 flex items-center justify-center"
                            style="height: 200px;">
                            <img src="{{ $product->display_image }}" alt="{{ $product->display_name }}"
                                class="object-contain h-full w-auto transition-transform duration-300 group-hover:scale-110">
                        </div>

                        <!-- Product Info -->
                        <div class="p-5 flex flex-col flex-1 bg-white">
                            <div class="text-center mb-4 flex-1">
                                <h3 class="font-fredoka text-gray-800 text-lg mb-1.5">{{ $product->display_name }}</h3>
                                <p class="text-sm text-gray-600 font-nunito mb-2">{{ $product->display_description }}</p>
                                @if ($product->transaction_sales_details_sum_qty)
                                    <p class="text-xs text-green-600 font-nunito font-semibold mb-4">
                                        {{ number_format($product->transaction_sales_details_sum_qty) }}+
                                        Terjual
                                    </p>
                                @endif
                            </div>
                            <a href="{{ $product->shopping_url }}" target="_blank" rel="noopener noreferrer"
                                class="w-full bg-gradient-to-r from-[#6C63FF] to-[#8B7FFF] text-white font-nunito font-semibold py-3 px-4 text-sm rounded-lg hover:from-[#5B52EE] hover:to-[#7A6EEE] hover:shadow-lg transform hover:scale-[1.02] transition-all duration-300 block text-center">
                                Lihat Produk
                            </a>
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
    </section>

    {{-- Testimonial Section --}}
    <section class="py-12 sm:py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <h2 class="text-2xl sm:text-3xl text-[#614DAC] mb-8 text-center font-fredoka">
                Telah dipercaya {{ number_format($customerCount, 0, ',', '.') }}+ Ibu
            </h2>

            {{-- Dynamic Testimonial from Database --}}
            @if ($bestReview)
                <div class="bg-white rounded-xl p-6 sm:p-8 shadow-lg border border-gray-100">
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-5">
                        {{-- Avatar dengan Rating Stars --}}
                        <div class="flex-shrink-0">
                            <div
                                class="bg-gradient-to-br from-purple-200 to-blue-200 w-14 h-14 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                </svg>
                            </div>
                            {{-- Rating Stars --}}
                            <div class="flex justify-center">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $bestReview->rating)
                                        <svg class="w-3 h-3 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3 text-gray-300 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 text-center md:text-left">
                            <svg class="w-8 h-8 text-purple-200 mb-3 mx-auto md:mx-0" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path d="M6 17h3l2-4V7H5v6h3zm8 0h3l2-4V7h-6v6h3z" />
                            </svg>
                            <p class="text-sm sm:text-base text-gray-700 mb-4 italic leading-relaxed font-nunito">
                                "{{ $bestReview->comment }}"
                            </p>
                            <p class="text-xs text-gray-500 font-nunito mt-0.5">
                                {{ $bestReview->created_at->format('Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            @elseif ($testimonial)
                {{-- Fallback Static Testimonial --}}
                <div class="bg-white rounded-xl p-6 sm:p-8 shadow-lg border border-gray-100">
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-5">
                        {{-- Avatar --}}
                        <div
                            class="bg-gradient-to-br from-purple-200 to-blue-200 w-14 h-14 rounded-full flex-shrink-0 flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 text-center md:text-left">
                            <svg class="w-8 h-8 text-purple-200 mb-3 mx-auto md:mx-0" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path d="M6 17h3l2-4V7H5v6h3zm8 0h3l2-4V7h-6v6h3z" />
                            </svg>
                            <p class="text-sm sm:text-base text-gray-700 mb-4 italic leading-relaxed font-nunito">
                                "{{ $testimonial->body }}"
                            </p>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">{{ $testimonial->title }}</p>
                                <p class="text-xs text-gray-500 font-nunito mt-0.5">Affiliate Partner sejak 2024</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- How to Join Section --}}
    <section class="py-12 sm:py-16 lg:py-20 bg-[#B8E6D9]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Title --}}
            <h2 class="text-2xl sm:text-3xl lg:text-4xl text-[#6C63FF] mb-6 text-center lg:text-left font-fredoka">
                {{ $howToJoinTitle->title ?? 'How to Join' }}
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                {{-- Left Side - Content Box --}}
                <div class="order-2 lg:order-1">
                    <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 lg:p-8 shadow-lg">
                        <h3 class="text-xl lg:text-2xl text-[#6C63FF] mb-3 text-center lg:text-left font-fredoka">
                            Work Easy, Earn More
                        </h3>
                        <p
                            class="text-gray-600 text-sm lg:text-base leading-relaxed mb-6 lg:mb-8 text-center lg:text-left font-nunito">
                            Gabung jadi affiliate partner kami dan mulai hasilkan income tambahan dengan mudah.
                        </p>

                        {{-- CTA Button --}}
                        <div class="text-center lg:text-left">
                            <button
                                class="relative bg-gradient-to-r from-[#FF6B6B] to-[#FF9191] text-white px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-bold shadow-md overflow-hidden transform transition duration-300 ease-in-out hover:shadow-xl hover:scale-105 hover:-translate-y-1 group w-full sm:w-auto">
                                <a class="relative z-10" href="{{ route('reseller.form') }}">DAFTAR SEKARANG</a>
                                {{-- Hover Gradient Overlay --}}
                                <span
                                    class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-[#D94C4C] to-[#FF6B6B] transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left z-0 rounded-xl"></span>
                                {{-- Shine Effect --}}
                                <span
                                    class="absolute top-0 right-0 w-8 h-full bg-white/20 skew-x-[30deg] transform translate-x-32 group-hover:translate-x-0 transition-all duration-1000 z-0"></span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Right Side - Steps --}}
                <div class="space-y-8 order-1 lg:order-2">
                    @foreach ($steps as $index => $step)
                        <div class="flex items-start space-x-4 relative">
                            <div class="relative flex flex-col items-center">
                                {{-- Circle --}}
                                <div
                                    class="bg-[#FF6B6B] w-12 h-12 rounded-full flex items-center justify-center shadow-lg z-10">
                                    <span class="text-white font-bold text-lg">{{ $index + 1 }}</span>
                                </div>
                                {{-- Vertical Line --}}
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
