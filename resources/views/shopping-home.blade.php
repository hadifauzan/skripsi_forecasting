@extends('layouts.ecommerce')

@section('title', 'Beranda - Gentle Living E-Commerce')

@section('content')
<div class="min-h-screen bg-gray-50">
    
    <!-- Simple Banner Carousel Section -->
    <div class="bg-white">
        <div class="max-w-[1502px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Banner Carousel -->
            <div class="relative w-full max-w-[1502px] h-[751px] bg-gray-200 rounded-lg overflow-hidden mx-auto" id="bannerCarousel" style="aspect-ratio: 1502/751;">
                <!-- Banner Images -->
                <div class="carousel-item active absolute inset-0">
                    <img src="{{ asset('images/banner/banner-1.png') }}" 
                         alt="Banner 1" 
                         class="w-full h-full object-cover">
                </div>
                <div class="carousel-item absolute inset-0 opacity-0">
                    <img src="{{ asset('images/banner/banner-2.png') }}" 
                         alt="Banner 2" 
                         class="w-full h-full object-cover">
                </div>
                <div class="carousel-item absolute inset-0 opacity-0">
                    <img src="{{ asset('images/banner/banner-3.png') }}" 
                         alt="Banner 3" 
                         class="w-full h-full object-cover">
                </div>
                
                <!-- Navigation Arrows -->
                <button class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-2 shadow-lg transition-all duration-200" onclick="previousSlide()">
                    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-2 shadow-lg transition-all duration-200" onclick="nextSlide()">
                    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                
                <!-- Carousel Navigation Dots -->
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                    <button class="w-3 h-3 bg-blue-600 rounded-full carousel-dot active" onclick="goToSlide(0)"></button>
                    <button class="w-3 h-3 bg-gray-300 rounded-full carousel-dot" onclick="goToSlide(1)"></button>
                    <button class="w-3 h-3 bg-gray-300 rounded-full carousel-dot" onclick="goToSlide(2)"></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-12">
        
        <!-- Kategori Produk Section -->
        <section>
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-4 font-nunito">Kategori Produk</h2>
                <p class="text-gray-600 font-nunito">Temani setiap momen penting ibu dan anak dengan produk yang tepat.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    // Category images mapping
                    $categoryImages = [
                        'gentle-baby' => 'gentleBaby.png',
                        'mamina' => 'mamina.png', 
                        'nyam' => 'products/nyam.png',
                        'healo' => 'products/healo.png',
                    ];
                @endphp
                
                @foreach($categories as $category)
                    <a href="{{ route('shopping.products', ['category' => $category->slug]) }}" class="group">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300">
                            <div class="h-48 bg-gray-200 flex items-center justify-center p-6">
                                @php
                                    $categoryImage = $categoryImages[$category->slug] ?? 'no-image.png';
                                @endphp
                                
                                <div class="text-center">
                                    <img src="{{ asset('images/' . $categoryImage) }}" 
                                         alt="{{ $category->name }}" 
                                         class="w-20 h-20 mx-auto mb-3 object-contain"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    
                                    <!-- Fallback SVG (hidden by default) -->
                                    <div style="display: none;" class="text-gray-500">
                                        <svg class="w-16 h-16 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    
                                    <h3 class="text-lg font-semibold font-nunito group-hover:text-blue-600 transition-colors duration-200">{{ $category->name }}</h3>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- Produk Terlaris Section -->
        <section>
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-4 font-nunito">Produk Terlaris</h2>
                <p class="text-gray-600 font-nunito">Bukan hanya banyak diminati, tapi benar-benar dirasakan manfaatnya oleh para ibu setiap harinya.</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                    <!-- Product Image -->
                    <div class="relative bg-gray-100 h-48 flex items-center justify-center">
                        @if(isset($product->total_sold) && $product->total_sold > 0)
                        <div class="absolute top-2 right-2 bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold font-nunito">
                            Terjual {{ $product->total_sold }}
                        </div>
                        @endif
                        <img src="{{ $product->image }}" 
                             alt="{{ $product->name_item }}" 
                             class="w-full h-full object-cover rounded-lg"
                             onerror="this.onerror=null; this.src='{{ asset('storage/gentle-baby/placeholder.jpg') }}'">
                    </div>
                    
                    <!-- Product Info -->
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-800 mb-2 font-nunito line-clamp-2">{{ $product->name_item }}</h3>
                        <p class="text-xl font-bold text-blue-600 mb-3 font-nunito">{{ $product->formatted_price }}</p>
                        
                        <div class="flex space-x-2">
                            <a href="{{ route('product.detail', $product->item_id) }}" class="flex-1 bg-blue-600 text-white text-center py-2 px-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-nunito text-sm">
                                Lihat Produk
                            </a>
                            <button class="bg-gray-100 hover:bg-gray-200 p-2 rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0L17 18m0 0v3a1 1 0 01-1 1H8a1 1 0 01-1-1v-3m10 0a1 1 0 01-1 1H8a1 1 0 01-1-1"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <!-- Cerita para Ibu Section -->
        <section>
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-4 font-nunito">Cerita para Ibu</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimoni 1 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <blockquote class="text-gray-600 font-nunito leading-relaxed mb-4">
                                "Hari ini Fathiyah masuk angin, muntah, dan mual. Trus inget punya Tummy Calmer. Langsung dioles-oles ke perut Alhamdulillah langsung terkentut-kentut dan lega katanya. Makasih Gentle Baby!"
                            </blockquote>
                            <footer class="text-blue-600 font-semibold font-nunito">Mom Firda Amalia</footer>
                        </div>
                    </div>
                </div>

                <!-- Testimoni 2 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <blockquote class="text-gray-600 font-nunito leading-relaxed mb-4">
                                "Hari ini Fathiyah masuk angin, muntah, dan mual. Trus inget punya Tummy Calmer. Langsung dioles-oles ke perut Alhamdulillah langsung terkentut-kentut dan lega katanya. Makasih Gentle Baby!"
                            </blockquote>
                            <footer class="text-blue-600 font-semibold font-nunito">Mom Firda Amalia</footer>
                        </div>
                    </div>
                </div>

                <!-- Testimoni 3 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <blockquote class="text-gray-600 font-nunito leading-relaxed mb-4">
                                "Hari ini Fathiyah masuk angin, muntah, dan mual. Trus inget punya Tummy Calmer. Langsung dioles-oles ke perut Alhamdulillah langsung terkentut-kentut dan lega katanya. Makasih Gentle Baby!"
                            </blockquote>
                            <footer class="text-blue-600 font-semibold font-nunito">Mom Firda Amalia</footer>
                        </div>
                    </div>
                </div>
            </div>
        </section>

      
    </div>
</div>

<script>
// Banner Carousel Variables
let bannerCurrentSlide = 0;
const bannerTotalSlides = 3;

// Banner Carousel Functions
function nextSlide() {
    bannerCurrentSlide = (bannerCurrentSlide + 1) % bannerTotalSlides;
    updateBannerCarousel();
}

function previousSlide() {
    bannerCurrentSlide = (bannerCurrentSlide - 1 + bannerTotalSlides) % bannerTotalSlides;
    updateBannerCarousel();
}

function goToSlide(slideIndex) {
    bannerCurrentSlide = slideIndex;
    updateBannerCarousel();
}

function updateBannerCarousel() {
    const slides = document.querySelectorAll('#bannerCarousel .carousel-item');
    const dots = document.querySelectorAll('#bannerCarousel .carousel-dot');
    
    slides.forEach((slide, index) => {
        if (index === bannerCurrentSlide) {
            slide.classList.add('active');
            slide.style.opacity = '1';
            slide.style.transition = 'opacity 0.8s ease-in-out';
        } else {
            slide.classList.remove('active');
            slide.style.opacity = '0';
            slide.style.transition = 'opacity 0.8s ease-in-out';
        }
    });
    
    dots.forEach((dot, index) => {
        if (index === bannerCurrentSlide) {
            dot.classList.add('active');
            dot.classList.remove('bg-gray-300');
            dot.classList.add('bg-blue-600');
        } else {
            dot.classList.remove('active');
            dot.classList.remove('bg-blue-600');
            dot.classList.add('bg-gray-300');
        }
    });
}

// Auto-rotate banner carousel
setInterval(nextSlide, 5000);

// Initialize banner carousel
document.addEventListener('DOMContentLoaded', function() {
    updateBannerCarousel();
});

// Touch/Swipe support for mobile
let startX = 0;
let endX = 0;

document.getElementById('bannerCarousel').addEventListener('touchstart', function(e) {
    startX = e.touches[0].clientX;
});

document.getElementById('bannerCarousel').addEventListener('touchend', function(e) {
    endX = e.changedTouches[0].clientX;
    handleBannerSwipe();
});

function handleBannerSwipe() {
    const swipeThreshold = 50;
    const diff = startX - endX;
    
    if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0) {
            nextSlide(); // Swipe left - next slide
        } else {
            previousSlide(); // Swipe right - previous slide
        }
    }
}
</script>

<style>
/* Custom carousel styles for specific dimensions */
@media (max-width: 1502px) {
    #bannerCarousel {
        width: 100%;
        height: calc(100vw * 751 / 1502); /* Maintain aspect ratio */
        max-height: 751px;
    }
}

@media (max-width: 768px) {
    #bannerCarousel {
        height: calc(100vw * 751 / 1502);
        min-height: 250px;
        max-height: 400px;
    }
}

@media (max-width: 480px) {
    #bannerCarousel {
        height: calc(100vw * 751 / 1502);
        min-height: 200px;
        max-height: 300px;
    }
}

/* Ensure images maintain aspect ratio */
#bannerCarousel img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}
</style>

@endsection
