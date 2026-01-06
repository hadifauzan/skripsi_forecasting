<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="description" content="Peluang usaha MPASI terbaik dengan modal 500ribu. Jadi agen NYAM sekarang dan raih penghasilan dari rumah!">
    <title>Agen NYAM - Peluang Usaha MPASI</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo1.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-start">
                <div class="col-lg-6 hero-content">
                    <h1>CUMA MODAL 500RIBU, SUDAH<br>BISA PUNYA USAHA SENDIRI<br>YANG LARIS SETIAP HARI!</h1>
                    <p>Sekarang ibu bisa jualan dari rumah, tanpa stok barang, tanpa keluar rumah, tanpa keluar rugi.</p>
                </div>
                <div class="col-lg-6 hero-images">
                    <div class="hero-products">
                        <img src="{{ asset('images/produk.png') }}" alt="Produk NYAM 1" class="img-fluid" loading="eager">
                    </div>
                    <div class="hero-person">
                        <img src="{{ asset('images/ibu.png') }}" alt="Ibu Agen NYAM" class="img-fluid" loading="eager">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Problem Section -->
    <section class="problem-section">
        <div class="container">
            <h2 class="text-center">Ibu Pasti Pernah Ngerasain Ini...</h2>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row g-3 problem-grid-custom">
                        <div class="col-md-4">
                            <div class="problem-item">
                                <p>Pengen nambah penghasilan, tapi bingung jual apa</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="problem-item">
                                <p>Takut modal besar & barang nggak laku</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="problem-item">
                                <p>Pengen usaha sendiri, tapi nggak ada yang bimbing</p>
                            </div>
                        </div>
                        <div class="col-md-4 offset-md-2">
                            <div class="problem-item">
                                <p>Saingan banyak, untung cuma receh</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="problem-item">
                                <p>Waktu habis urus anak & rumah tangga</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <p class="highlight-text text-center">Tenang Bu.. NYAM adalah jalan paling gampang & paling<br>cuan saat ini.</p>
        </div>
    </section>

    <!-- Fact Section -->
    <section class="fact-section">
        <div class="container">
            <h2 class="text-center">FAKTA: Penjualan Produk MPASI banyak<br>peminatnya tiap bulan!</h2>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="fact-box">
                        <p>Market MPASI sedang BOOMING karena ibu-ibu sekarang rela keluar budget<br>lebih demi makanan bayi yang aman & bebas pengawet.</p>
                        <p><strong>Ini waktu TERBAIK buat Ibu masuk!</strong></p>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="https://wa.link/ne6pud" target="_blank" class="cta-button">DAFTAR AGEN NYAM SEKARANG</a>
            </div>
        </div>
    </section>

    <!-- Why Section -->
    <section class="why-section">
        <div class="container">
            <h2 class="text-center">KENAPA JUALAN MPASI NYAM PALING<br>GAMPANG & MENJANJIKAN?</h2>

            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="table-responsive">
                        <table class="why-table table">
                            <tbody>
                                <tr>
                                    <td>Repeat order 70-80%</td>
                                </tr>
                                <tr>
                                    <td>Modal mulai 500ribu saja</td>
                                </tr>
                                <tr>
                                    <td>Kerja 100% dari rumah sambil momong anak</td>
                                </tr>
                                <tr>
                                    <td>Materi promosi lengkap (foto, video)</td>
                                </tr>
                                <tr>
                                    <td>Ada grup support + bimbingan Nyam pusat</td>
                                </tr>
                                <tr>
                                    <td>Slot agen TERBATAS per kecamatan/kota</td>
                                </tr>
                                <tr>
                                    <td>Reward EMAS untuk agen aktif</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Best Seller Section -->
    <section class="bestseller-section">
        <div class="container">
            <h2 class="text-center">10 PRODUK BEST SELLER NYAM!</h2>

            @if(isset($bestSellers) && $bestSellers->count() > 0)
            <!-- Top 5 Products -->
            <div class="row g-3 bestseller-grid-custom justify-content-center mb-3">
                @foreach($bestSellers->take(5) as $index => $item)
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="bestseller-item">
                            <div class="bestseller-rank">#{{ $index + 1 }}</div>
                            <div class="bestseller-image">
                                <img src="{{ $item->image_url ?? asset('images/nyam.png') }}" 
                                     alt="{{ $item->masterItem->name_item ?? 'Produk NYAM' }}"
                                     class="img-fluid"
                                     loading="lazy"
                                     onerror="this.src='{{ asset('images/nyam.png') }}'">
                            </div>
                            <div class="bestseller-info">
                                <h3>{{ $item->masterItem->name_item ?? 'Produk NYAM' }}</h3>
                                <p class="bestseller-price">Rp {{ number_format($item->sell_price ?? $item->masterItem->costprice_item ?? 0, 0, ',', '.') }}</p>
                                <p class="bestseller-sold">Terjual: {{ $item->total_sold }} pcs</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Bottom 5 Products -->
            <div class="row g-3 bestseller-grid-custom justify-content-center">
                @foreach($bestSellers->skip(5)->take(5) as $index => $item)
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="bestseller-item">
                            <div class="bestseller-rank">#{{ $index + 6 }}</div>
                            <div class="bestseller-image">
                                <img src="{{ $item->image_url ?? asset('images/nyam.png') }}" 
                                     alt="{{ $item->masterItem->name_item ?? 'Produk NYAM' }}"
                                     class="img-fluid"
                                     loading="lazy"
                                     onerror="this.src='{{ asset('images/nyam.png') }}'">
                            </div>
                            <div class="bestseller-info">
                                <h3>{{ $item->masterItem->name_item ?? 'Produk NYAM' }}</h3>
                                <p class="bestseller-price">Rp {{ number_format($item->sell_price ?? $item->masterItem->costprice_item ?? 0, 0, ',', '.') }}</p>
                                <p class="bestseller-sold">Terjual: {{ $item->total_sold }} pcs</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @else
            <!-- Static Best Seller Products -->
            <div class="row g-3 bestseller-grid-custom justify-content-center mb-3">
                @php
                    $staticProducts = [
                        ['name' => 'Full Meal Bolognese', 'price' => 35000, 'sold' => 1250, 'image' => 'full-meal-bolognese.jpg'],
                        ['name' => 'Chicken Pudding', 'price' => 28000, 'sold' => 980, 'image' => 'chicken-pudding.jpg'],
                        ['name' => 'Beef Pudding', 'price' => 30000, 'sold' => 875, 'image' => 'beef-pudding.jpg'],
                        ['name' => 'Full Meal Hati Ayam Bumbu Kuning', 'price' => 33000, 'sold' => 820, 'image' => 'full-meal-hati-ayam-bumbu-kuning.jpg'],
                        ['name' => 'Abon Hati Ayam', 'price' => 32000, 'sold' => 750, 'image' => 'abon-hati-ayam.jpg'],
                    ];
                @endphp
                @foreach($staticProducts as $index => $product)
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="bestseller-item">
                            <div class="bestseller-rank">#{{ $index + 1 }}</div>
                            <div class="bestseller-image">
                                <img src="{{ asset('images/nyam/' . $product['image']) }}" 
                                     alt="{{ $product['name'] }}"
                                     class="img-fluid"
                                     loading="lazy"
                                     onerror="this.src='{{ asset('images/nyam.png') }}'">
                            </div>
                            <div class="bestseller-info">
                                <h3>{{ $product['name'] }}</h3>
                                <p class="bestseller-price">Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
                                <p class="bestseller-sold">Terjual: {{ $product['sold'] }} pcs</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row g-3 bestseller-grid-custom justify-content-center">
                @php
                    $staticProducts2 = [
                        ['name' => 'Full Meal Dori Bumbu Kuning', 'price' => 34000, 'sold' => 695, 'image' => 'full-meal-dori-bumbu-kuning.jpg'],
                        ['name' => 'Ciki Bone Broth', 'price' => 25000, 'sold' => 620, 'image' => 'ciki-bone-broth.jpg'],
                        ['name' => 'Full Meal Nasi Uduk Ayam Telor', 'price' => 35000, 'sold' => 580, 'image' => 'full-meal-nasi-uduk-ayam-telor.jpg'],
                        ['name' => 'Hati Ayam Lengkuas', 'price' => 31000, 'sold' => 540, 'image' => 'hati-ayam-lengkuas.jpg'],
                        ['name' => 'Full Meal Opor Otak', 'price' => 33000, 'sold' => 495, 'image' => 'full-meal-opor-otak.jpg'],
                    ];
                @endphp
                @foreach($staticProducts2 as $index => $product)
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="bestseller-item">
                            <div class="bestseller-rank">#{{ $index + 6 }}</div>
                            <div class="bestseller-image">
                                <img src="{{ asset('images/nyam/' . $product['image']) }}" 
                                     alt="{{ $product['name'] }}"
                                     class="img-fluid"
                                     loading="lazy"
                                     onerror="this.src='{{ asset('images/nyam.png') }}'">
                            </div>
                            <div class="bestseller-info">
                                <h3>{{ $product['name'] }}</h3>
                                <p class="bestseller-price">Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
                                <p class="bestseller-sold">Terjual: {{ $product['sold'] }} pcs</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="testimonial-section">
        <div class="container">
            <h2 class="text-center">TESTIMONI IBU-IBU YANG SUDAH<br>MENJADI AGEN NYAM</h2>

            <div class="testimonial-carousel">
                <button class="carousel-btn prev" onclick="changeTestimonial(-1)">❮</button>
                <div class="testimonial-container">
                    @php
                        // Daftar gambar testimoni dari folder images/testimoni
                        $testimonialImages = [
                            'testi1.png',
                            'testi2.png',
                            'testi3.png',
                            'testi4.png',
                        ];
                    @endphp
                    
                    @foreach($testimonialImages as $index => $image)
                        <div class="testimonial-slide {{ $index === 0 ? 'active' : '' }}">
                            <div class="testimonial-content">
                                <img src="{{ asset('images/testimoni/' . $image) }}" 
                                     alt="Testimoni {{ $index + 1 }}"
                                     class="img-fluid testimonial-image"
                                     loading="lazy"
                                     onerror="this.style.display='none'">
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-btn next" onclick="changeTestimonial(1)">❯</button>
            </div>
            
            <div class="carousel-dots text-center">
                @foreach($testimonialImages as $index => $image)
                    <span class="dot {{ $index === 0 ? 'active' : '' }}" onclick="currentTestimonial({{ $index }})"></span>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Info Section -->
    <section class="info-section">
        <div class="container">
            <h2 class="text-center">INFO PENTING - HARAP BACA DULU<br>SEBELUM CHAT ADMIN</h2>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <ul class="info-list">
                        <li>Pengiriman Paket Paket / NCS / ESBooks (sameday/nextday)</li>
                        <li>Masa Simpan Produk 6 bulan sejak diproduksi</li>
                        <li>Modal Pertama Rp500.000</li>
                        <li>Kalau dalam 3 bulan setelah pembelian pertama tidak ada restock lagi, keagenan akan kami tinjau ulang (supaya slot tetap untuk ibu-ibu yang serius)</li>
                        <li>Semua foto, video, caption, testimoni, story template → sudah lengkap di Google Drive resmi. Bisa langsung download begitu ibu masuk grup agen </li>
                        <li>Harga Jual bebas ibu naikkan sesuai pasar kota masing-masing</li>
                    </ul>
                </div>
            </div>

            <div class="text-center">
                <a href="https://wa.link/ne6pud" target="_blank" class="cta-button">SAYA MAU JADI AGEN NYAM</a>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/nyam-landing.js') }}"></script>
    <script>
        // Initialize chart with static data
        initSalesChart();
    </script>
</body>
</html>
