@extends('layouts.app')

@section('title', 'Tentang Kami - Gentle Living')

@section('content')
    @php
        use Illuminate\Support\Facades\Storage;
    @endphp
    {{-- Hero Section / Banner Tentang Kami --}}
    <section id="hero" class="relative min-h-screen bg-gray-100">
        <!-- Carousel Background Images -->
        <div class="absolute inset-0 w-full h-full">
            <div id="bannerCarousel" class="h-full overflow-hidden relative">
                @if ($bannerImages && $bannerImages->count() > 0)
                    @foreach ($bannerImages as $index => $banner)
                        @if ($banner && $banner->image)
                            <div
                                class="carousel-slide {{ $index === 0 ? 'active' : '' }} absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
                                <img src="{{ Storage::url($banner->image) }}" alt="Banner About Us {{ $index + 1 }}"
                                    class="w-full h-full object-cover object-center">
                            </div>
                        @endif
                    @endforeach
                @else
                    <img src="{{ asset('images/banner/profil1.jpg') }}" alt="Banner Tentang Kami"
                        class="w-full h-full object-cover object-center">
                @endif
            </div>
        </div>

        <!-- Enhanced Dark Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/50 to-black/70 z-10"></div>

        <!-- Text Overlay -->
        <div class="absolute inset-0 flex items-center justify-center z-20">
            <div class="text-center text-white px-3 sm:px-4 max-w-6xl mx-auto">

                <h1 class="font-fredoka text-2xl sm:text-4xl text-white/85 mb-2 sm:mb-4 relative inline-block">
                    {{ $heroContent->title ?? 'Tentang Kami' }}
                </h1>

                <!-- Enhanced Description -->
                <p
                    class="font-nunito text-sm sm:text-lg md:text-xl lg:text-2xl text-white/90 max-w-xs sm:max-w-3xl mx-auto leading-relaxed mb-8 sm:mb-12 px-2 sm:px-4">
                    {!! $heroContent->body ??
                        'Mengenal lebih dekat perjalanan <span class="text-blue-300 font-semibold">Gentle Living</span> dalam menghadirkan nutrisi terbaik dan produk berkualitas untuk keluarga Indonesia' !!}
                </p>

                <!-- Enhanced Scroll Down Arrow -->
                <div class="flex flex-col items-center">
                    <a href="#tentang-kami"
                        class="group relative animate-bounce cursor-pointer p-2 sm:p-4 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 hover:bg-white/20 transition-all duration-300 scroll-smooth">
                        <!-- Animated Arrow Container -->
                        <div class="relative overflow-hidden">
                            <!-- Multiple Arrow Effects -->
                            <svg class="w-5 h-5 sm:w-8 sm:h-8 text-white transform transition-all duration-500 group-hover:translate-y-1 group-hover:scale-110"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>

                            <!-- Second Arrow for Depth -->
                            <svg class="absolute top-0 left-0 w-5 h-5 sm:w-8 sm:h-8 text-blue-300/60 transform transition-all duration-500 group-hover:translate-y-2"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </div>

                        <!-- Pulse Effect -->
                        <div class="absolute inset-0 rounded-full bg-white/20 animate-ping"></div>
                    </a>

                    <!-- Enhanced Guide Text -->
                    <div class="mt-2 sm:mt-4 flex flex-col items-center">
                        <p class="text-white/70 text-xs sm:text-sm font-nunito tracking-wide">Scroll untuk melanjutkan</p>
                        <div
                            class="w-6 sm:w-8 h-px bg-gradient-to-r from-transparent via-white/40 to-transparent mt-1 sm:mt-2">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Tentang Kami Section -->
    <section id="tentang-kami" class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-8 sm:mb-12">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl text-[#614DAC] mb-4 font-fredoka">
                    {{ $aboutContent->title ?? 'Tentang Kami' }}
                </h2>
            </div>

            <!-- Grid Content Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                <!-- Text Content -->
                <div class="order-2 lg:order-1">
                    <div class="prose prose-lg max-w-none text-[#4D4C4C] font-nunito leading-relaxed">
                        @if ($aboutContent && $aboutContent->body)
                            <div class="text-base sm:text-lg">{!! $aboutContent->body !!}</div>
                        @else
                            <p class="text-base sm:text-lg mb-6">
                                Gentle Living adalah perusahaan yang berkomitmen menyediakan produk MPASI (Makanan
                                Pendamping
                                ASI) berkualitas tinggi untuk mendukung tumbuh kembang optimal si kecil. Kami memahami
                                betapa
                                pentingnya nutrisi yang tepat pada masa emas pertumbuhan anak.
                            </p>

                            <p class="text-base sm:text-lg mb-6">
                                Dengan menggunakan bahan-bahan alami pilihan dan proses produksi yang higienis, setiap
                                produk
                                MPASI kami diformulasikan khusus untuk memenuhi kebutuhan nutrisi bayi dan balita sesuai
                                dengan
                                tahapan perkembangannya.
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Image Content -->
                <div class="order-1 lg:order-2">
                    <div class="relative">
                        <div class="overflow-hidden shadow-lg">
                            <img src="{{ $aboutContent && $aboutContent->image ? Storage::url($aboutContent->image) : asset('images/about-gentle-living.jpg') }}"
                                alt="{{ $aboutContent->title ?? 'Tentang Gentle Living' }}"
                                class="w-full h-64 sm:h-80 lg:h-96 object-cover transition-transform duration-300 hover:scale-105">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Perjalanan Kami Section -->
    <section id = "perjalanan-kami" class="py-12 sm:py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-8 sm:mb-12">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl text-[#614DAC] mb-4 font-fredoka">
                    {{ $journeyContent->title ?? 'Perjalanan Kami' }}
                </h2>
            </div>

            <!-- Grid Content Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                <!-- Image Content -->
                <div class="order-1">
                    <div class="relative">
                        <div class="overflow-hidden shadow-lg">
                            <img src="{{ $journeyContent && $journeyContent->image ? Storage::url($journeyContent->image) : asset('images/about-gentle-living.jpg') }}"
                                alt="{{ $journeyContent->title ?? 'Perjalanan Kami' }}"
                                class="w-full h-64 sm:h-80 lg:h-96 object-cover transition-transform duration-300 hover:scale-105">
                        </div>
                    </div>
                </div>
                <!-- Text Content -->
                <div class="order-2">
                    <div class="prose prose-lg max-w-none text-[#4D4C4C] font-nunito leading-relaxed">
                        @if ($journeyContent && $journeyContent->body)
                            <div class="text-base sm:text-lg">{!! $journeyContent->body !!}</div>
                        @else
                            <p class="text-base sm:text-lg mb-6">
                                Berawal dari pengalaman sebagai orangtua yang kesulitan menemukan MPASI berkualitas tinggi
                                untuk si kecil, kami memulai perjalanan Gentle Living dengan misi menyediakan produk MPASI
                                yang sehat, bergizi, dan aman untuk bayi.
                            </p>

                            <p class="text-base sm:text-lg mb-6">
                                Kami berkomitmen untuk menggunakan bahan-bahan terbaik dan proses produksi yang higienis
                                serta memenuhi standar keamanan pangan. Setiap produk MPASI kami diformulasikan khusus
                                untuk mendukung tumbuh kembang optimal bayi.
                            </p>

                            <p class="text-base sm:text-lg mb-6">
                                Dengan dukungan tim ahli gizi dan pengalaman bertahun-tahun, Gentle Living kini telah
                                dipercaya oleh ribuan ibu di Indonesia untuk memberikan nutrisi terbaik bagi buah hati
                                mereka.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Visi Misi Section -->
    <section class="py-12 sm:py-16 bg-white">
        <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8">
            <!-- Content -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-12 text-[#4D4C4C] font-nunito leading-relaxed">

                <!-- Visi -->
                <div>
                    @php
                        $visionContent = \App\Models\MasterContent::where('type_of_page', 'about_us')
                            ->where('section', 'vision')
                            ->first();
                        $visionData = $visionContent ? json_decode($visionContent->body, true) : [];
                    @endphp

                    <h3
                        class="text-2xl sm:text-3xl lg:text-4xl text-[#614DAC] text-center font-fredoka font-semibold mb-8 sm:mb-12">
                        {{ $visionContent->title ?? 'Visi' }}
                    </h3>

                    <ul class="list-disc pl-6 space-y-3 text-sm sm:text-base">
                        @if (!empty($visionData) && is_array($visionData))
                            @foreach ($visionData as $vision)
                                @if (!empty(trim($vision)))
                                    <li>{{ $vision }}</li>
                                @endif
                            @endforeach
                        @else
                            <li>Menjadi perusahaan terdepan dalam menyediakan produk nutrisi berkualitas tinggi</li>
                            <li>Menghadirkan inovasi terbaik untuk kesehatan keluarga Indonesia</li>
                            <li>Membangun kepercayaan konsumen melalui kualitas produk yang konsisten</li>
                            <li>Menciptakan dampak positif bagi masyarakat dan lingkungan</li>
                        @endif
                    </ul>
                </div>

                <!-- Misi -->
                <div>
                    @php
                        $missionContent = \App\Models\MasterContent::where('type_of_page', 'about_us')
                            ->where('section', 'mission')
                            ->first();
                        $missionData = $missionContent ? json_decode($missionContent->body, true) : [];
                    @endphp

                    <h3
                        class="text-2xl sm:text-3xl lg:text-4xl text-[#614DAC] text-center font-fredoka font-semibold mb-8 sm:mb-12">
                        {{ $missionContent->title ?? 'Misi' }}
                    </h3>

                    <ul class="list-disc pl-6 space-y-3 text-sm sm:text-base">
                        @if (!empty($missionData) && is_array($missionData))
                            @foreach ($missionData as $mission)
                                @if (!empty(trim($mission)))
                                    <li>{{ $mission }}</li>
                                @endif
                            @endforeach
                        @else
                            <li>Mengembangkan produk nutrisi dengan standar internasional yang aman dan berkualitas</li>
                            <li>Memberikan edukasi kepada masyarakat tentang pentingnya nutrisi yang tepat</li>
                            <li>Membangun kemitraan strategis untuk memperluas jangkauan distribusi</li>
                            <li>Melakukan riset berkelanjutan untuk inovasi produk yang lebih baik</li>
                        @endif
                    </ul>
                </div>

            </div>
        </div>
    </section>


    <!-- Keluarga Gentle Living Section -->
    <section class="py-12 sm:py-16 bg-white">
        <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl text-[#614DAC] mb-4 sm:mb-8 font-fredoka">
                    {{ $familyHeader->title ?? 'Keluarga Gentle Living' }}
                </h2>
                <p class="text-sm sm:text-lg text-[#4D4C4C] max-w-3xl mx-auto font-nunito px-2 sm:px-0">
                    {{ $familyHeader->body ?? 'Bergabunglah dengan ribuan keluarga Indonesia yang telah mempercayakan nutrisi terbaik untuk si kecil kepada Gentle Living' }}
                </p>
            </div>

            {{-- Grid Images --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
                @if ($familyContent && $familyContent->count() > 0)
                    @foreach ($familyContent as $family)
                        <div class="aspect-square bg-gray-300 rounded-lg overflow-hidden">
                            <img src="{{ $family->image ? Storage::url($family->image) : asset('images/family/family' . $loop->iteration . '.jpg') }}"
                                alt="{{ $family->title ?? 'Keluarga Gentle Living ' . $loop->iteration }}"
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                        </div>
                    @endforeach
                @else
                    <!-- Row 1 -->
                    <div class="aspect-square bg-gray-300 rounded-lg overflow-hidden">
                        <img src="{{ asset('images/family/family1.jpg') }}" alt="Keluarga Gentle Living 1"
                            class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                    </div>
                    <div class="aspect-square bg-gray-300 rounded-lg overflow-hidden">
                        <img src="{{ asset('images/family/family2.jpg') }}" alt="Keluarga Gentle Living 2"
                            class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                    </div>
                    <div class="aspect-square bg-gray-300 rounded-lg overflow-hidden">
                        <img src="{{ asset('images/family/family3.jpg') }}" alt="Keluarga Gentle Living 3"
                            class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                    </div>
                    <div class="aspect-square bg-gray-300 rounded-lg overflow-hidden">
                        <img src="{{ asset('images/family/family4.jpg') }}" alt="Keluarga Gentle Living 4"
                            class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                    </div>
                    <div class="aspect-square bg-gray-300 rounded-lg overflow-hidden">
                        <img src="{{ asset('images/family/family5.jpg') }}" alt="Keluarga Gentle Living 5"
                            class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                    </div>

                    <!-- Row 2 -->
                    <div class="aspect-square bg-gray-300 rounded-lg overflow-hidden">
                        <img src="{{ asset('images/family/family6.jpg') }}" alt="Keluarga Gentle Living 6"
                            class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                    </div>
                    <div class="aspect-square bg-gray-300 rounded-lg overflow-hidden">
                        <img src="{{ asset('images/family/family7.jpg') }}" alt="Keluarga Gentle Living 7"
                            class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                    </div>
                    <div class="aspect-square bg-gray-300 rounded-lg overflow-hidden">
                        <img src="{{ asset('images/family/family8.jpg') }}" alt="Keluarga Gentle Living 8"
                            class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                    </div>
                    <div class="aspect-square bg-gray-300 rounded-lg overflow-hidden">
                        <img src="{{ asset('images/family/family9.jpg') }}" alt="Keluarga Gentle Living 9"
                            class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                    </div>
                    <div class="aspect-square bg-gray-300 rounded-lg overflow-hidden">
                        <img src="{{ asset('images/family/family10.jpg') }}" alt="Keluarga Gentle Living 10"
                            class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                    </div>
                @endif
            </div>

        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-12 sm:py-16 bg-white">
        <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 sm:gap-12">
                @if ($statisticsContent && $statisticsContent->count() > 0)
                    @foreach ($statisticsContent as $stat)
                        <div class="text-center">
                            <div class="mb-4">
                                <div class="text-4xl sm:text-5xl lg:text-6xl font-bold text-[#614DAC] mb-2 font-fredoka">
                                    {{ $stat->title }}
                                </div>
                                <h3 class="text-lg sm:text-xl font-semibold text-[#4D4C4C] font-nunito">
                                    {{ strip_tags($stat->content) }}
                                </h3>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Default Statistics -->
                    <div class="text-center">
                        <div class="mb-4">
                            <div class="text-4xl sm:text-5xl lg:text-6xl font-bold text-[#614DAC] mb-2 font-fredoka">
                                {{ number_format($customerCount, 0, ',', '.') }}+
                            </div>
                            <h3 class="text-lg sm:text-xl font-semibold text-[#4D4C4C] font-nunito">
                                Ibu Percaya
                            </h3>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="mb-4">
                            <div class="text-4xl sm:text-5xl lg:text-6xl font-bold text-[#614DAC] mb-2 font-fredoka">
                                {{ number_format($productVariantsCount, 0, ',', '.') }}+
                            </div>
                            <h3 class="text-lg sm:text-xl font-semibold text-[#4D4C4C] font-nunito">
                                Varian Produk
                            </h3>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="mb-4">
                            <div class="text-4xl sm:text-5xl lg:text-6xl font-bold text-[#614DAC] mb-2 font-fredoka">
                                {{ number_format($productsSoldCount, 0, ',', '.') }}+
                            </div>
                            <h3 class="text-lg sm:text-xl font-semibold text-[#4D4C4C] font-nunito">
                                Produk Terjual
                            </h3>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Carousel JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.carousel-slide');
            let currentSlide = 0;

            if (slides.length > 1) {
                setInterval(function() {
                    // Hide current slide
                    slides[currentSlide].classList.remove('opacity-100');
                    slides[currentSlide].classList.add('opacity-0');

                    // Move to next slide
                    currentSlide = (currentSlide + 1) % slides.length;

                    // Show next slide
                    slides[currentSlide].classList.remove('opacity-0');
                    slides[currentSlide].classList.add('opacity-100');
                }, 4000); // Change slide every 4 seconds
            }
        });
    </script>
@endsection
