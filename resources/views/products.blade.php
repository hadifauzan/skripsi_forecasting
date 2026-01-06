@extends('layouts.app')

@section('title', 'Produk - Gentle Living')

@section('content')
    <style>
        /* Custom styles for variant selection */
        .variant-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .variant-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .variant-card.selected {
            border: 2px solid #6C63FF;
            background: linear-gradient(135deg, #f0f0ff 0%, #e6e6ff 100%);
        }
        
        .variant-card.selected .variant-button {
            background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
        }
        
        /* Enhanced Carousel Styles for Smoother Animations */
        .carousel-container {
            will-change: transform;
            backface-visibility: hidden;
        }
        
        .carousel-track {
            will-change: transform;
            backface-visibility: hidden;
            transform: translate3d(0, 0, 0);
        }
        
        .carousel-smooth {
            transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        
        .carousel-instant {
            transition: none !important;
        }
        
        /* Dynamic Varian Carousel Styles */
        .varian-carousel-container {
            width: 100%;
            overflow: hidden;
            will-change: transform;
            backface-visibility: hidden;
        }
        
        .varian-carousel-track {
            display: flex;
            gap: 0.5rem;
            will-change: transform;
            backface-visibility: hidden;
            transform: translate3d(0, 0, 0);
        }
        
        .varian-smooth {
            transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        
        .varian-instant {
            transition: none !important;
        }
        
        .varian-item {
            flex: 0 0 auto;
            width: 100%;
            will-change: transform;
            backface-visibility: hidden;
        }
        
        /* Responsive varian item widths */
        @media (min-width: 640px) {
            .varian-item {
                width: calc(50% - 0.25rem);
            }
        }
        
        @media (min-width: 768px) {
            .varian-item {
                width: calc(33.333% - 0.333rem);
            }
        }
        
        @media (min-width: 1024px) {
            .varian-item {
                width: calc(25% - 0.375rem);
            }
        }
        
        @media (min-width: 1280px) {
            .varian-item {
                width: calc(20% - 0.4rem);
            }
        }
        
        /* Smooth hover animations */
        .carousel-item {
            transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            will-change: transform;
        }
        
        .carousel-item:hover {
            transform: translateY(-4px) scale(1.02);
        }
    </style>

    <!-- Hero Produk Section -->
    <section class="relative bg-white">
        <div class="max-w-7xl mx-auto px-2 sm:px-2 lg:px-2">
            <!-- Product Title Section dengan Tailwind Classes -->
            <div class="text-center pt-20 md:pt-24 lg:pt-28 pb-5 min-h-[5rem] md:min-h-[4rem] lg:min-h-[5rem]">
                <h1 class="text-2xl md:text-3xl lg:text-4xl text-[#614DAC] font-fredoka font-semibold leading-tight mb-4 drop-shadow-sm">
                    {{ $productTitle ?? 'Gentle Baby' }}
                </h1>
            </div>

            <!-- Product Carousel -->
            <div class="relative mb-8 px-2 lg:px-8 max-w-7xl mx-auto mt-4">
                <!-- Carousel Container -->
                <div class="overflow-hidden rounded-xl bg-white p-3 lg:p-4 carousel-container">
                    <div id="productCarousel" class="flex gap-4 carousel-track">
                        @php
                            // Use dynamic data from database
                            $carouselImages = $productImages ?? [];
                        @endphp
                        
                        <!-- Product Cards - dinamis berdasarkan data dari database -->
                        @foreach($carouselImages as $index => $imageData)
                        <div class="flex-none carousel-item transition-transform duration-300
                                    w-full sm:w-[calc(50%-1rem)] lg:w-[calc(25%-1.5rem)]">
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl text-center shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-300">
                                <!-- Square Image Container -->
                                <div class="w-full aspect-square relative overflow-hidden rounded-lg bg-white shadow-md">
                                    <img src="{{ $imageData['image'] }}" 
                                        alt="{{ $imageData['alt'] ?? $imageData['title'] ?? $productTitle . ' Product ' . ($index + 1) }}" 
                                        class="absolute inset-0 w-full h-full object-cover rounded-lg transition-transform duration-300 hover:scale-105"
                                        onerror="
                                            this.onerror=null;
                                            const product = '{{ request('product') }}';
                                            if (product === 'nyam') {
                                                this.src='{{ asset('images/products/nyam.png') }}';
                                            } else if (product === 'mamina' || product === 'mamina-asi-booster') {
                                                this.src='{{ asset('images/products/mamina.png') }}';
                                            } else if (product === 'healo') {
                                                this.src='{{ asset('images/products/healo.jpg') }}';
                                            } else {
                                                this.src='{{ asset('images/products/gentle-baby.png') }}';
                                            }
                                        ">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                        
                <!-- Dots Indicator dengan styling yang lebih menarik -->
                <div class="flex justify-center mt-8 space-x-2">
                    <!-- Dots akan di-generate oleh JavaScript berdasarkan total slides -->
                </div>
            </div>
        </div>
    </section>

    <!-- Product Benefits Section -->
    <section class="py-4 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Product Benefits -->
            <div class="text-center mb-6">
                @if(isset($benefitsData) && !empty($benefitsData))
                    <!-- Dynamic content from database -->
                    @if(isset($benefitsData['description']) && !empty($benefitsData['description']))
                        <p class="text-lg text-gray-700 mb-6 font-nunito">
                            {{ $benefitsData['description'] }}
                        </p>
                    @endif
                    
                    @if(isset($benefitsData['benefits']) && !empty($benefitsData['benefits']))
                        <div class="space-y-3 max-w-2xl mx-auto">
                            @foreach($benefitsData['benefits'] as $benefit)
                                <div class="flex items-center justify-center space-x-3">
                                    <div class="w-6 h-6 bg-[#6C63FF] rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700">{{ $benefit }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </section>

    @if((!request('product') || request('product') == 'gentle-baby') || request('product') == 'nyam' || request('product') == 'mamina' || request('product') == 'mamina-asi-booster' || request('product') == 'healo')
    <!-- Varian Section - For Gentle Baby, Nyam, Mamina, and Healo -->
    <section class="py-6 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl lg:text-4xl text-[#614DAC] mb-8 font-fredoka">
                    Varian
                </h2>
                
                <!-- Varian Carousel -->
                <div class="px-4 sm:px-8 max-w-7xl mx-auto relative">
                    <div class="varian-carousel-container rounded-xl">
                        <div id="varianCarousel" class="varian-carousel-track">
                            <!-- Varian Cards - Dynamic responsive display -->
                            @foreach($variants as $index => $variant)
                            <div class="varian-item px-2 carousel-item">
                                <div class="variant-card bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-2 text-center shadow-lg border border-gray-200 h-full flex flex-col justify-between transition-all duration-300 hover:shadow-xl" 
                                     data-variant-index="{{ $index }}">
                                    <!-- Square Image Container 1:1 ratio -->
                                    <div class="w-full aspect-square relative overflow-hidden rounded-lg bg-white shadow-md mb-3">
                                        <img src="{{ $variant['image'] }}" 
                                            alt="{{ $variant['name'] }}" 
                                            class="w-full h-full object-cover rounded-lg"
                                            onerror="
                                                this.onerror=null;
                                                const product = '{{ request('product') }}';
                                                if (product === 'nyam') {
                                                    this.src='{{ asset('images/products/nyam.png') }}';
                                                } else if (product === 'mamina' || product === 'mamina-asi-booster') {
                                                    this.src='{{ asset('images/products/mamina.png') }}';
                                                } else if (product === 'healo') {
                                                    this.src='{{ asset('images/products/healo.jpg') }}';
                                                } else {
                                                    this.src='{{ asset('images/products/gentle-baby.png') }}';
                                                }
                                            ">
                                    </div>
                                    
                                    <!-- Varian Info -->
                                    <div class="text-center">
                                        <h3 class="font-bold text-gray-800 mb-2 text-sm font-nunito">
                                            {{ $variant['name'] }}
                                        </h3>
                                        <button onclick="selectVariant('{{ addslashes($variant['name']) }}', '{{ $variant['image'] }}', '{{ $variant['link'] ?? '#' }}', {{ $index }})" 
                                            class="variant-button w-full text-white font-nunito font-semibold py-3 px-4 text-sm rounded-lg mt-auto transition-all duration-200"
                                            data-variant-name="{{ $variant['name'] }}"
                                            data-variant-slug="{{ strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($variant['name']))) }}"
                                            data-variant-link="{{ $variant['link'] ?? '' }}"
                                            style="background-color: #785576; hover:background-color:614DAC;">
                                            Lihat Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Navigation Arrows -->
                    <button id="varianPrevBtn" class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white/90 border border-gray-200 rounded-full w-10 h-10 flex items-center justify-center cursor-pointer transition-all duration-300 backdrop-blur-sm hover:bg-white hover:shadow-lg z-10">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    
                    <button id="varianNextBtn" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white/90 border border-gray-200 rounded-full w-10 h-10 flex items-center justify-center cursor-pointer transition-all duration-300 backdrop-blur-sm hover:bg-white hover:shadow-lg z-10">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Carousel Indicators -->
                <div id="varianIndicators" class="flex justify-center space-x-2 mt-6">
                    <!-- Dots will be generated by JavaScript -->
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Variant Detail Modal Popup -->
    <div id="variantModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <!-- Backdrop overlay -->
        <div class="fixed inset-0 backdrop-blur-sm transition-opacity duration-300" onclick="closeVariantDetail()"></div>
        
        <!-- Modal container -->
        <div class="flex min-h-full items-center justify-center p-4 sm:p-6 lg:p-8">
            <!-- Modal content -->
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-auto transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
                <!-- Close button -->
                <button onclick="closeVariantDetail()" class="absolute top-4 right-4 z-10 w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors duration-200">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                
                <!-- Modal body -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 overflow-hidden rounded-2xl">
                    <!-- Image section -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center p-8 lg:p-12">
                        <div class="w-full max-w-sm aspect-square">
                            <img id="modalVariantImage" 
                                 src="" 
                                 alt="Selected Variant" 
                                 class="w-full h-full object-cover rounded-xl shadow-lg">
                        </div>
                    </div>
                    
                    <!-- Content section -->
                    <div class="p-8 lg:p-12 flex flex-col justify-center">
                        <div class="mb-8">
                            <h3 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-4 font-fredoka" id="modalVariantName">
                                <!-- Dynamic name from database -->
                            </h3>
                            <p class="text-gray-600 font-nunito leading-relaxed mb-6" id="modalVariantDescription">
                                <!-- Dynamic description from database -->
                            </p>
                        </div>
                        
                        <!-- Action buttons -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a id="modalVariantBuyLink" 
                               href="#" 
                               class="flex-1 bg-[#785576] text-white text-center py-2.5 px-4 rounded-lg font-semibold font-nunito 
                                    hover:bg-[#694966] transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                                    🛒 Beli Sekarang
                            </a>
                            <button onclick="closeVariantDetail()" 
                                class="flex-1 bg-[#F3F0F4] text-[#785576] text-center py-2.5 px-4 rounded-lg font-semibold font-nunito 
                                    hover:bg-[#E9E3EC] transition-all duration-300 border border-[#D9CCE1]">
                                    Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Produk Lainnya Section -->
    <section class="py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl text-[#614DAC] mb-8 font-fredoka">
                    Produk Lainnya
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto">
                    @if(isset($otherProducts) && !empty($otherProducts))
                        @foreach($otherProducts as $product)
                        <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group border border-[#785576]/20">
                            <div class="flex flex-col h-full">
                                <div class="w-full bg-[#785576]/10 flex items-center justify-center p-4">
                                    <div class="bg-white rounded-xl p-3 shadow-md group-hover:scale-105 transition-transform duration-300">
                                        <img src="{{ $product['image'] }}" 
                                            alt="{{ $product['name'] }}" 
                                            class="w-28 h-28 object-contain mx-auto">
                                    </div>
                                </div>

                                <div class="w-full p-6 flex flex-col justify-between flex-grow">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800 mb-3 font-nunito text-center">{{ $product['name'] }}</h3>
                                        <p class="text-gray-600 text-sm mb-4 leading-relaxed text-center">
                                            {{ $product['description'] }}
                                        </p>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <a href="{{ route('products', ['product' => $product['route_param']]) }}" 
                                        class="bg-[#785576] text-white px-6 py-2 rounded-full text-sm font-medium hover:bg-[#634461] transition-all duration-300 transform hover:scale-105 shadow-md">
                                            Lihat Produk
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        {{-- Fallback to static content if no data --}}
                        {{-- Gentle Baby --}}
                        @if(request('product') != 'gentle-baby' && request('product') != null)
                        <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group border border-[#785576]/20">
                            <div class="flex flex-col h-full">
                                <div class="w-full bg-[#785576]/10 flex items-center justify-center p-4">
                                    <div class="bg-white rounded-xl p-3 shadow-md group-hover:scale-105 transition-transform duration-300">
                                        <img src="{{ asset('images/products/gentle-baby.png') }}" 
                                            alt="Gentle Baby" 
                                            class="w-28 h-28 object-contain mx-auto">
                                    </div>
                                </div>

                                <div class="w-full p-6 flex flex-col justify-between flex-grow">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800 mb-3 font-nunito text-center">Gentle Baby</h3>
                                        <p class="text-gray-600 text-sm mb-4 leading-relaxed text-center">
                                            Minyak Bayi Aromaterapi untuk kesehatan ibu dan bayi.
                                        </p>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <a href="{{ route('products', ['product' => 'gentle-baby']) }}" 
                                        class="bg-[#785576] text-white px-6 py-2 rounded-full text-sm font-medium hover:bg-[#634461] transition-all duration-300 transform hover:scale-105 shadow-md">
                                            Lihat Produk
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Healo --}}
                        @if(request('product') != 'healo' && request('product') != null)
                        <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group border border-[#785576]/20">
                            <div class="flex flex-col h-full">
                                <div class="w-full bg-[#785576]/10 flex items-center justify-center p-4">
                                    <div class="bg-white rounded-xl p-3 shadow-md group-hover:scale-105 transition-transform duration-300">
                                        <img src="{{ asset('images/products/healo.png') }}" 
                                            alt="Healo" 
                                            class="w-28 h-28 object-contain mx-auto">
                                    </div>
                                </div>
                                <div class="w-full p-6 flex flex-col justify-between flex-grow">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800 mb-3 font-nunito text-center">Healo</h3>
                                        <p class="text-gray-600 text-sm mb-4 leading-relaxed text-center">
                                            Roll On Aromaterapi Anak.
                                        </p>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <a href="{{ route('products', ['product' => 'healo']) }}" 
                                        class="bg-[#785576] text-white px-6 py-2 rounded-full text-sm font-medium hover:bg-[#634461] transition-all duration-300 transform hover:scale-105 shadow-md">
                                            Lihat Produk
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Mamina ASI Booster --}}
                        @if(request('product') != 'mamina' && request('product') != 'mamina-asi-booster')
                        <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group border border-[#785576]/20">
                            <div class="flex flex-col h-full">
                                <div class="w-full bg-[#785576]/10 flex items-center justify-center p-4">
                                    <div class="bg-white rounded-xl p-3 shadow-md group-hover:scale-105 transition-transform duration-300">
                                        <img src="{{ asset('images/products/mamina.png') }}" 
                                            alt="Mamina ASI Booster" 
                                            class="w-28 h-28 object-contain mx-auto">
                                    </div>
                                </div>
                                <div class="w-full p-6 flex flex-col justify-between flex-grow">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800 mb-3 font-nunito text-center">Mamina ASI Booster</h3>
                                        <p class="text-gray-600 text-sm mb-4 leading-relaxed text-center">
                                            Pelancar ASI dari bahan Rempah Alami.
                                        </p>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <a href="{{ route('products', ['product' => 'mamina-asi-booster']) }}" 
                                        class="bg-[#785576] text-white px-6 py-2 rounded-full text-sm font-medium hover:bg-[#634461] transition-all duration-300 transform hover:scale-105 shadow-md">
                                            Lihat Produk
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Nyam! --}}
                        @if(request('product') != 'nyam')
                        <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group border border-[#785576]/20">
                            <div class="flex flex-col h-full">
                                <div class="w-full bg-[#785576]/10 flex items-center justify-center p-4">
                                    <div class="bg-white rounded-xl p-3 shadow-md group-hover:scale-105 transition-transform duration-300">
                                        <img src="{{ asset('images/products/nyam.png') }}" 
                                            alt="Nyam! MPASI" 
                                            class="w-28 h-28 object-contain mx-auto">
                                    </div>
                                </div>
                                <div class="w-full p-6 flex flex-col justify-between flex-grow">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800 mb-3 font-nunito text-center">Nyam!</h3>
                                        <p class="text-gray-600 text-sm mb-4 leading-relaxed text-center">
                                            Makanan Pendamping ASI (MPASI) dengan nutrisi lengkap.
                                        </p>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <a href="{{ route('products', ['product' => 'nyam']) }}" 
                                        class="bg-[#785576] text-white px-6 py-2 rounded-full text-sm font-medium hover:bg-[#634461] transition-all duration-300 transform hover:scale-105 shadow-md">
                                            Lihat Produk
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Penilaian Produk Section -->
    <section class="py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl lg:text-4xl text-[#614DAC] mb-8 font-fredoka">
                    Penilaian Produk
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @forelse($featuredReviews as $review)
                    <!-- Review {{ $loop->iteration }} -->
                    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="flex justify-center mb-3">
                            <div class="flex space-x-1">
                                @for($star = 1; $star <= 5; $star++)
                                <svg class="w-5 h-5 {{ $star <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                @endfor
                            </div>
                        </div>
                        <h4 class="font-bold text-gray-800 mb-2">{{ $review->user->name ?? 'Customer' }}</h4>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            {{ $review->comment }}
                        </p>
                    </div>
                    @empty
                    <!-- Default review when no featured reviews available -->
                    <div class="col-span-full text-center py-8">
                        <div class="bg-gray-50 rounded-xl p-8">
                            <i class="fas fa-comments text-gray-300 text-4xl mb-4"></i>
                            <h4 class="font-bold text-gray-700 mb-2">Belum Ada Review untuk Kategori Ini</h4>
                            <p class="text-sm text-gray-500">
                                Review khusus untuk kategori {{ ucfirst(str_replace('-', ' ', $productType ?? 'produk')) }} belum ada yang dipilih untuk ditampilkan.<br>
                                Admin dapat memilih review di panel admin untuk menampilkannya di sini.
                            </p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <script>
        // Variant Selection Functions - Using Dynamic Data from Database with Modal Popup
        function selectVariant(name, image, link, index) {
            console.log('Variant selected:', {name, image, link, index});
            
            // Get variant data from PHP array
            const variants = @json($variants ?? []);
            console.log('All variants data:', variants);
            
            const selectedVariant = variants[index];
            console.log('Selected variant data:', selectedVariant);
            
            if (!selectedVariant) {
                console.error('Variant not found at index:', index);
                return;
            }
            
            // Remove selected class from all variant cards
            document.querySelectorAll('.variant-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selected class to clicked card
            const selectedCard = document.querySelector(`[data-variant-index="${index}"]`);
            if (selectedCard) {
                selectedCard.classList.add('selected');
            }
            
            // Update modal content with dynamic data
            document.getElementById('modalVariantName').textContent = selectedVariant.name || name;
            document.getElementById('modalVariantImage').src = selectedVariant.image || image;
            
            // Create proper shopping URL for the variant
            let buyLink = '#';
            let isExternal = false;
            
            if (selectedVariant.link && selectedVariant.link !== '#' && selectedVariant.link.trim() !== '') {
                // Check if the link is external (contains http/https or starts with www)
                if (selectedVariant.link.startsWith('http') || selectedVariant.link.startsWith('www.') || selectedVariant.link.includes('://')) {
                    buyLink = selectedVariant.link;
                    isExternal = true;
                } else {
                    buyLink = selectedVariant.link;
                }
            } else {
                // Create internal shopping URL with variant parameters
                const productType = '{{ request("product") ?? "gentle-baby" }}';
                
                // Use the new route to find product by variant name
                if (selectedVariant.name) {
                    buyLink = `/shopping/product-by-variant?variant_name=${encodeURIComponent(selectedVariant.name)}&product_type=${encodeURIComponent(productType)}`;
                } else {
                    // Fallback to general shopping page
                    buyLink = `/shopping/products?search=${encodeURIComponent(selectedVariant.name || name)}`;
                }
            }
            
            const buyLinkElement = document.getElementById('modalVariantBuyLink');
            if (!buyLinkElement) {
                console.error('Buy link element not found');
                return;
            }
            
            buyLinkElement.href = buyLink;
            
            // Log the generated URL for debugging
            console.log('Generated buy link:', buyLink, 'Is external:', isExternal);
            console.log('Variant data:', selectedVariant);
            
            // Update button text based on link type
            let buttonText = '🛒 Beli Sekarang';
            if (buyLink === '#') {
                buttonText = '🛍️ Lihat Produk';
            } else if (buyLink.includes('/shopping/products')) {
                buttonText = '🛒 Beli Sekarang';
            } else if (isExternal) {
                buttonText = '🔗 Beli di Toko Online';
            }
            buyLinkElement.textContent = buttonText;
            
            // Add click tracking for analytics
            buyLinkElement.onclick = function(e) {
                // Track variant buy click
                console.log('Variant buy button clicked:', {
                    variant_name: selectedVariant.name || name,
                    product_type: '{{ request("product") ?? "gentle-baby" }}',
                    buy_link: buyLink,
                    is_external: isExternal
                });
                
                // Let the default link behavior continue
                return true;
            };
            
            // Set target and rel attributes for external links
            if (isExternal) {
                buyLinkElement.setAttribute('target', '_blank');
                buyLinkElement.setAttribute('rel', 'noopener noreferrer');
            } else {
                buyLinkElement.removeAttribute('target');
                buyLinkElement.removeAttribute('rel');
            }
            
            // Use dynamic description from database
            let description = selectedVariant.description;
            console.log('Raw description from database:', description);
            
            // Clean and validate description
            if (!description || description.trim() === '' || description === null || description === undefined) {
                description = 'Deskripsi produk sedang dalam proses pembaruan. Silakan hubungi customer service untuk informasi lebih lanjut.';
            } else {
                // Strip HTML tags if present and clean whitespace
                description = description.replace(/<[^>]*>/g, '').trim();
                console.log('Cleaned description:', description);
            }
            
            document.getElementById('modalVariantDescription').textContent = description;
            
            // Show modal with animation
            const modal = document.getElementById('variantModal');
            const modalContent = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden'); // Prevent body scroll
            
            // Trigger animation after a small delay
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
        
        function closeVariantDetail() {
            const modal = document.getElementById('variantModal');
            const modalContent = document.getElementById('modalContent');
            
            // Animate close
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            
            // Remove selected class from all variant cards
            document.querySelectorAll('.variant-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden'); // Restore body scroll
            }, 300);
        }
        
        // Close modal when clicking outside or pressing Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('variantModal');
                if (!modal.classList.contains('hidden')) {
                    closeVariantDetail();
                }
            }
        });
        
        // Fungsi getVariantDescription telah dihapus karena sekarang menggunakan data dinamis dari database

        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.getElementById('productCarousel');
            const dotsContainer = document.querySelector('.flex.justify-center.mt-8.space-x-2');
            
            if (!carousel || !dotsContainer) {
                console.log('Product carousel elements not found');
                return;
            }
            
            let currentSlide = 0;
            let isTransitioning = false;
            
            // Get actual number of images dynamically from PHP
            @php
                $imageCount = 0;
                if(request('product') == 'mamina' || request('product') == 'mamina-asi-booster') {
                    $imageCount = count($productImages); // Mamina images
                } elseif(request('product') == 'nyam') {
                    $imageCount = count($productImages); // Nyam images
                } elseif(request('product') == 'healo') {
                    $imageCount = count($productImages); // Healo images
                } else {
                    $imageCount = count($productImages); // Gentle Baby images
                }
            @endphp
            
            const totalItems = {{ $imageCount }}; // Dynamic count from PHP
            function getItemsPerSlide() {
                if (window.innerWidth < 640) {
                    return 1; // Mobile
                } else if (window.innerWidth < 1024) {
                    return 2; // Tablet
                } else {
                    return 4; // Desktop
                }
            }

            let itemsPerSlide = getItemsPerSlide();

            console.log('Product Carousel Initialized:', {totalItems, itemsPerSlide});
            
            function getTotalSlides() {
                // Menghitung berapa banyak slide yang dibutuhkan untuk menampilkan semua gambar
                return Math.ceil(totalItems / itemsPerSlide);
            }
            
            // Create duplicate slides for infinite loop
            function createInfiniteLoop() {
                const items = carousel.querySelectorAll('.flex-none:not(.clone)');
                if (items.length === 0) return;
                
                // Remove existing clones
                carousel.querySelectorAll('.clone').forEach(clone => clone.remove());
                
                // Only create clones if we have multiple slides
                const totalSlides = getTotalSlides();
                if (totalSlides <= 1) return;
                
                // Clone first few items and append to end
                const itemsToClone = Math.min(itemsPerSlide, items.length);
                for (let i = 0; i < itemsToClone; i++) {
                    const clone = items[i].cloneNode(true);
                    clone.classList.add('clone');
                    carousel.appendChild(clone);
                }
                
                // Clone last few items and prepend to beginning
                for (let i = items.length - itemsToClone; i < items.length; i++) {
                    const clone = items[i].cloneNode(true);
                    clone.classList.add('clone');
                    carousel.insertBefore(clone, carousel.firstChild);
                }
                
                // Set initial position to account for prepended clones
                currentSlide = 1;
                updateCarousel(false);
            }
            
            function createDots() {
                const totalSlides = getTotalSlides();
                dotsContainer.innerHTML = '';

                console.log('Creating dots for', totalSlides, 'slides');
                
                for (let i = 0; i < totalSlides; i++) {
                    const dot = document.createElement('div');
                    dot.className = 'dot w-4 h-4 bg-gray-300 rounded-full cursor-pointer transition-all duration-200 hover:scale-110 hover:bg-gray-400';
                    if (i === 0) {
                        dot.classList.remove('bg-gray-300');
                        dot.classList.add('bg-purple-500');
                    }
                    dot.setAttribute('data-slide', i);
                    dot.addEventListener('click', () => goToSlide(i));
                    dotsContainer.appendChild(dot);
                }
            }
            
            function updateCarousel(useTransition = true) {
                console.log('Updating carousel, currentSlide:', currentSlide);
                
                // Calculate transform based on items per slide
                const items = carousel.querySelectorAll('.flex-none'); // ambil semua item
                if (items.length === 0) return;

                const itemWidth = items[0].getBoundingClientRect().width + 16; 
                // +16px karena ada gap-4 antar item

                const translateX = -(currentSlide * itemsPerSlide * itemWidth);
                
                // Apply smooth or instant transition
                if (useTransition) {
                    carousel.classList.remove('carousel-instant');
                    carousel.classList.add('carousel-smooth');
                } else {
                    carousel.classList.remove('carousel-smooth');
                    carousel.classList.add('carousel-instant');
                    // Re-enable smooth transition after a brief delay
                    requestAnimationFrame(() => {
                        setTimeout(() => {
                            carousel.classList.remove('carousel-instant');
                            carousel.classList.add('carousel-smooth');
                        }, 50);
                    });
                }
                
                // Use transform3d for better GPU acceleration
                carousel.style.transform = `translate3d(${translateX}px, 0, 0)`;
                
                // Update dots (adjust current slide for display)
                const totalSlides = getTotalSlides();
                let dotIndex = currentSlide;
                if (currentSlide > totalSlides) dotIndex = 1;
                if (currentSlide < 1) dotIndex = totalSlides;
                
                const dots = dotsContainer.querySelectorAll('.dot');
                dots.forEach((dot, index) => {
                    const isActive = index === (dotIndex - 1);
                    dot.classList.toggle('bg-purple-500', isActive);
                    dot.classList.toggle('bg-gray-300', !isActive);
                });
                
                console.log('Transform applied:', `translate3d(${translateX}px, 0, 0)`);
            }
            
            function nextSlide() {
                const totalSlides = getTotalSlides();
                console.log('Next slide called, current:', currentSlide, 'total slides:', totalSlides);
                
                if (isTransitioning) return;
                isTransitioning = true;
                
                currentSlide++;
                updateCarousel();
                
                // Check if we've moved past the last real slide
                setTimeout(() => {
                    if (currentSlide > totalSlides) {
                        // Jump back to first real slide without animation
                        currentSlide = 1;
                        updateCarousel(false);
                    }
                    isTransitioning = false;
                }, 600); // Match CSS transition duration (0.6s)
            }
            
            function prevSlide() {
                const totalSlides = getTotalSlides();
                console.log('Prev slide called, current:', currentSlide);
                
                if (isTransitioning) return;
                isTransitioning = true;
                
                currentSlide--;
                updateCarousel();
                
                // Check if we've moved before the first real slide
                setTimeout(() => {
                    if (currentSlide < 1) {
                        // Jump to last real slide without animation
                        currentSlide = totalSlides;
                        updateCarousel(false);
                    }
                    isTransitioning = false;
                }, 600); // Match CSS transition duration (0.6s)
            }
            
            function goToSlide(slideIndex) {
                console.log('Go to slide:', slideIndex);
                if (isTransitioning) return;
                
                const totalSlides = getTotalSlides();
                currentSlide = slideIndex + 1; // +1 because we start from 1 due to cloned slides
                updateCarousel();
            }
            
            // AUTO-SLIDE CAROUSEL - Fixed implementation
            let autoPlayInterval;
            
            function startAutoSlide() {
                console.log('Starting auto-slide...');
                autoPlayInterval = setInterval(() => {
                    console.log('Auto-slide executing...');
                    nextSlide();
                }, 5000); // Increased to 5 seconds for smoother experience
            }
            
            function stopAutoSlide() {
                console.log('Stopping auto-slide...');
                clearInterval(autoPlayInterval);
            }
            
            // Pause auto-slide on hover
            const carouselContainer = carousel.parentElement;
            carouselContainer.addEventListener('mouseenter', () => {
                console.log('Mouse entered, pausing auto-slide');
                stopAutoSlide();
            });
            
            // Resume auto-slide when mouse leaves
            carouselContainer.addEventListener('mouseleave', () => {
                console.log('Mouse left, resuming auto-slide');
                startAutoSlide();
            });
            
            // Handle window resize
            window.addEventListener('resize', () => {
                itemsPerSlide = getItemsPerSlide();
                const totalSlides = getTotalSlides();
                if (currentSlide > totalSlides) {
                    currentSlide = totalSlides;
                }
                createInfiniteLoop(); // Recreate clones for new layout
                createDots(); // regenerate dots sesuai jumlah slide
                updateCarousel(false);
            });
            
            // Touch/Swipe support for mobile
            let startX = 0;
            let currentX = 0;
            let isTouch = false;
            
            carouselContainer.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                isTouch = true;
                stopAutoSlide(); // Pause during touch
            });
            
            carouselContainer.addEventListener('touchmove', (e) => {
                if (!isTouch) return;
                currentX = e.touches[0].clientX;
                e.preventDefault();
            });
            
            carouselContainer.addEventListener('touchend', () => {
                if (!isTouch) return;
                isTouch = false;
                
                const deltaX = startX - currentX;
                if (Math.abs(deltaX) > 50) { // Minimum swipe distance
                    if (deltaX > 0) {
                        nextSlide();
                    } else {
                        prevSlide();
                    }
                }
                
                // Resume auto-slide after touch
                startAutoSlide();
            });
            
            // Initialize carousel
            console.log('Initializing product carousel...');
            createInfiniteLoop();
            createDots();
            updateCarousel(false);
            
            // Start auto-slide after a short delay
            setTimeout(() => {
                startAutoSlide();
                console.log('Auto-slide started');
            }, 1000);
            
            // Test carousel movement (for debugging)
            console.log('Testing manual slide in 2 seconds...');
            setTimeout(() => {
                if (getTotalSlides() > 1) {
                    nextSlide();
                    console.log('Manual test slide executed');
                }
            }, 2000);
            
            // Varian Carousel functionality - Dynamic based on actual variant count
            const varianCarousel = document.getElementById('varianCarousel');
            const varianNextBtn = document.getElementById('varianNextBtn');
            const varianPrevBtn = document.getElementById('varianPrevBtn');
            const varianIndicators = document.getElementById('varianIndicators');
            
            if (varianCarousel && varianNextBtn && varianPrevBtn && varianIndicators) {
                console.log('Varian carousel elements found, initializing...');
                
                // Get dynamic variant count from PHP
                @php
                    $variantCount = isset($variants) ? count($variants) : 0;
                @endphp
                
                const totalVarianItems = {{ $variantCount }};
                let varianCurrentSlide = 0;
                let varianIsTransitioning = false;
                
                console.log('Varian Carousel Initialized:', {totalVarianItems});
                
                function getVarianItemsPerView() {
                    if (window.innerWidth >= 1280) return 5; // Desktop XL: 5 cards
                    if (window.innerWidth >= 1024) return 4; // Desktop LG: 4 cards
                    if (window.innerWidth >= 768) return 3;  // Tablet MD: 3 cards
                    if (window.innerWidth >= 640) return 2;  // Tablet SM: 2 cards
                    return 1; // Mobile: 1 card
                }
                
                function getTotalVarianSlides() {
                    const itemsPerView = getVarianItemsPerView();
                    return Math.max(1, Math.ceil(totalVarianItems / itemsPerView));
                }
                
                // Create duplicate slides for infinite loop (variant carousel)
                function createVarianInfiniteLoop() {
                    const items = varianCarousel.querySelectorAll('.varian-item:not(.clone)');
                    if (items.length === 0) return;
                    
                    // Remove existing clones
                    varianCarousel.querySelectorAll('.clone').forEach(clone => clone.remove());
                    
                    // Only create clones if we have multiple slides
                    const totalSlides = getTotalVarianSlides();
                    if (totalSlides <= 1) return;
                    
                    const itemsPerView = getVarianItemsPerView();
                    
                    // Clone first few items and append to end
                    const itemsToClone = Math.min(itemsPerView, items.length);
                    for (let i = 0; i < itemsToClone; i++) {
                        const clone = items[i].cloneNode(true);
                        clone.classList.add('clone');
                        varianCarousel.appendChild(clone);
                    }
                    
                    // Clone last few items and prepend to beginning
                    for (let i = items.length - itemsToClone; i < items.length; i++) {
                        const clone = items[i].cloneNode(true);
                        clone.classList.add('clone');
                        varianCarousel.insertBefore(clone, varianCarousel.firstChild);
                    }
                    
                    // Set initial position to account for prepended clones
                    varianCurrentSlide = 1;
                    updateVarianCarousel(false);
                }
                
                function updateVarianCarousel(useTransition = true) {
                    console.log('updateVarianCarousel called, currentSlide:', varianCurrentSlide);
                    
                    if (totalVarianItems === 0) return;
                    
                    const itemsPerView = getVarianItemsPerView();
                    const varianItems = varianCarousel.querySelectorAll('.varian-item');
                    
                    if (varianItems.length === 0) return;
                    
                    // Calculate pixel-based transform using actual item width
                    const itemWidth = varianItems[0].getBoundingClientRect().width;
                    const gap = 8; // 0.5rem gap = 8px
                    const slideWidth = (itemWidth + gap) * itemsPerView;
                    const translateX = -(varianCurrentSlide * slideWidth);
                    
                    // Apply smooth or instant transition
                    if (useTransition) {
                        varianCarousel.classList.remove('varian-instant');
                        varianCarousel.classList.add('varian-smooth');
                    } else {
                        varianCarousel.classList.remove('varian-smooth');
                        varianCarousel.classList.add('varian-instant');
                        // Re-enable smooth transition after a brief delay
                        requestAnimationFrame(() => {
                            setTimeout(() => {
                                varianCarousel.classList.remove('varian-instant');
                                varianCarousel.classList.add('varian-smooth');
                            }, 50);
                        });
                    }
                    
                    // Use transform3d for better GPU acceleration
                    varianCarousel.style.transform = `translate3d(${translateX}px, 0, 0)`;
                    
                    // Update indicators (adjust current slide for display)
                    const totalSlides = getTotalVarianSlides();
                    let dotIndex = varianCurrentSlide;
                    if (varianCurrentSlide > totalSlides) dotIndex = 1;
                    if (varianCurrentSlide < 1) dotIndex = totalSlides;
                    
                    const dots = varianIndicators.querySelectorAll('button');
                    dots.forEach((dot, index) => {
                        const isActive = index === (dotIndex - 1);
                        dot.classList.toggle('bg-purple-500', isActive);
                        dot.classList.toggle('bg-gray-300', !isActive);
                    });
                    
                    // Update navigation buttons visibility (always show for infinite loop)
                    if (varianPrevBtn) {
                        if (totalSlides <= 1) {
                            varianPrevBtn.style.display = 'none';
                        } else {
                            varianPrevBtn.style.display = 'flex';
                            varianPrevBtn.style.opacity = '1';
                            varianPrevBtn.style.pointerEvents = 'auto';
                        }
                    }
                    
                    if (varianNextBtn) {
                        if (totalSlides <= 1) {
                            varianNextBtn.style.display = 'none';
                        } else {
                            varianNextBtn.style.display = 'flex';
                            varianNextBtn.style.opacity = '1';
                            varianNextBtn.style.pointerEvents = 'auto';
                        }
                    }
                    
                    console.log('Varian carousel updated:', {
                        currentSlide: varianCurrentSlide,
                        totalSlides: totalSlides,
                        itemsPerView: itemsPerView,
                        translateX: translateX
                    });
                }
                
                function nextVarianSlide() {
                    console.log('Next varian slide called');
                    const totalSlides = getTotalVarianSlides();
                    if (totalSlides <= 1) return;
                    
                    if (varianIsTransitioning) return;
                    varianIsTransitioning = true;
                    
                    varianCurrentSlide++;
                    updateVarianCarousel();
                    
                    // Check if we've moved past the last real slide
                    setTimeout(() => {
                        if (varianCurrentSlide > totalSlides) {
                            // Jump back to first real slide without animation
                            varianCurrentSlide = 1;
                            updateVarianCarousel(false);
                        }
                        varianIsTransitioning = false;
                    }, 600); // Match CSS transition duration (0.6s)
                }
                
                function prevVarianSlide() {
                    console.log('Prev varian slide called');
                    const totalSlides = getTotalVarianSlides();
                    if (totalSlides <= 1) return;
                    
                    if (varianIsTransitioning) return;
                    varianIsTransitioning = true;
                    
                    varianCurrentSlide--;
                    updateVarianCarousel();
                    
                    // Check if we've moved before the first real slide
                    setTimeout(() => {
                        if (varianCurrentSlide < 1) {
                            // Jump to last real slide without animation
                            varianCurrentSlide = totalSlides;
                            updateVarianCarousel(false);
                        }
                        varianIsTransitioning = false;
                    }, 600); // Match CSS transition duration (0.6s)
                }
                
                function goToVarianSlide(slideIndex) {
                    console.log('Go to varian slide:', slideIndex);
                    if (varianIsTransitioning) return;
                    
                    const totalSlides = getTotalVarianSlides();
                    varianCurrentSlide = slideIndex + 1; // +1 because we start from 1 due to cloned slides
                    updateVarianCarousel();
                }
                
                // Create indicators based on actual slides needed
                function createVarianIndicators() {
                    const totalSlides = getTotalVarianSlides();
                    varianIndicators.innerHTML = '';
                    
                    console.log('Creating varian indicators for', totalSlides, 'slides, total items:', totalVarianItems);
                    
                    // Only show indicators if we have more than 1 slide
                    if (totalSlides <= 1) {
                        varianIndicators.style.display = 'none';
                        return;
                    }
                    
                    varianIndicators.style.display = 'flex';
                    
                    for (let i = 0; i < totalSlides; i++) {
                        const dot = document.createElement('button');
                        dot.classList.add('w-3', 'h-3', 'rounded-full', 'transition-colors', 'duration-200');
                        if (i === 0) {
                            dot.classList.add('bg-purple-500');
                        } else {
                            dot.classList.add('bg-gray-300', 'hover:bg-gray-400');
                        }
                        dot.addEventListener('click', () => goToVarianSlide(i));
                        varianIndicators.appendChild(dot);
                    }
                    updateVarianCarousel();
                }
                
                // Event listeners with null checks
                if (varianNextBtn) {
                    varianNextBtn.addEventListener('click', () => {
                        console.log('Varian next button clicked');
                        nextVarianSlide();
                    });
                }
                
                if (varianPrevBtn) {
                    varianPrevBtn.addEventListener('click', () => {
                        console.log('Varian prev button clicked');
                        prevVarianSlide();
                    });
                }
                
                // Auto-slide functionality for varian (only if multiple slides)
                let varianAutoSlideInterval;
                
                function startVarianAutoSlide() {
                    const totalSlides = getTotalVarianSlides();
                    if (totalSlides <= 1) return; // Don't auto-slide if only 1 slide
                    
                    console.log('Starting varian auto-slide...');
                    varianAutoSlideInterval = setInterval(() => {
                        console.log('Varian auto-slide executing...');
                        nextVarianSlide();
                    }, 6000); // Increased to 6 seconds for smoother variant experience
                }
                
                function stopVarianAutoSlide() {
                    console.log('Stopping varian auto-slide...');
                    clearInterval(varianAutoSlideInterval);
                }
                
                // Pause/resume on hover (only if auto-slide is active)
                const varianContainer = varianCarousel.parentElement.parentElement;
                varianContainer.addEventListener('mouseenter', stopVarianAutoSlide);
                varianContainer.addEventListener('mouseleave', startVarianAutoSlide);
                
                // Responsive handling - recalculate on window resize
                window.addEventListener('resize', () => {
                    const oldItemsPerView = getVarianItemsPerView();
                    
                    setTimeout(() => {
                        const newItemsPerView = getVarianItemsPerView();
                        const newTotalSlides = getTotalVarianSlides();
                        
                        // Reset slide if necessary
                        if (varianCurrentSlide > newTotalSlides) {
                            varianCurrentSlide = newTotalSlides;
                        }
                        
                        createVarianInfiniteLoop(); // Recreate clones for new layout
                        createVarianIndicators();
                        updateVarianCarousel(false);
                        console.log('Varian carousel resized:', {
                            oldItemsPerView,
                            newItemsPerView,
                            newTotalSlides,
                            currentSlide: varianCurrentSlide
                        });
                    }, 100); // Small delay to ensure layout is complete
                });
                
                // Touch/swipe support for varian (only if multiple slides)
                let varianStartX = 0;
                let varianCurrentX = 0;
                let varianIsDragging = false;
                
                varianContainer.addEventListener('touchstart', (e) => {
                    if (getTotalVarianSlides() <= 1) return;
                    
                    varianStartX = e.touches[0].clientX;
                    varianIsDragging = true;
                    stopVarianAutoSlide();
                });
                
                varianContainer.addEventListener('touchmove', (e) => {
                    if (!varianIsDragging || getTotalVarianSlides() <= 1) return;
                    varianCurrentX = e.touches[0].clientX;
                    e.preventDefault();
                });
                
                varianContainer.addEventListener('touchend', () => {
                    if (!varianIsDragging || getTotalVarianSlides() <= 1) return;
                    varianIsDragging = false;
                    
                    const diffX = varianStartX - varianCurrentX;
                    const threshold = 50;
                    
                    if (Math.abs(diffX) > threshold) {
                        if (diffX > 0) {
                            nextVarianSlide();
                        } else {
                            prevVarianSlide();
                        }
                    }
                    
                    startVarianAutoSlide();
                });
                
                // Initialize varian carousel
                console.log('Initializing varian carousel...');
                createVarianInfiniteLoop();
                createVarianIndicators();
                updateVarianCarousel(false);
                
                // Start varian auto-slide only if we have multiple slides
                setTimeout(() => {
                    startVarianAutoSlide();
                    console.log('Varian auto-slide initialization completed');
                }, 2000);
            } else {
                console.log('Varian carousel elements not found');
            }
        });
    </script>
@endsection
