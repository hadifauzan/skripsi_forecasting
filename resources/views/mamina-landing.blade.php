<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mamina Affiliate</title>
    
    <!-- Favicon -->
    {{--  <link rel="icon" type="image/png" href="{{ asset('storage/GentleLiving/logo-tab.png') }}">  --}}
    
    <!-- Local Fonts -->
    <style>
        @font-face {
            font-family: 'Poppins';
            src: url('{{ asset('assets/fonts/poppins/poppins-regular.woff2') }}') format('woff2');
            font-weight: 400;
            font-display: swap;
        }
    </style>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/mamina-landing.css') }}">
</head>
<body>
    <!-- Hero Section -->
        <section class="hero-section">
            <!-- Background Image -->
            <img src="{{ asset('images/Mamina/banner.jpg') }}" alt="Mamina Products" class="hero-background">
            
            <!-- White Overlay -->
            <div class="hero-overlay"></div>
            
            <!-- Content -->
            <div class="hero-content">
                <h1>Cuan dari Rumah dengan Jadi AffiliateMamina</h1>
                <p class="subtitle">
                    Gabung program affiliate yang mudah, gratis, dan bisa langsung menghasilkan. Siap konten yang ibu dan Creator butuh!
                </p>
                 <a href="https://wa.link/5pzwur" target="_blank" class="cta-button-purple">Gabung Affiliate Hari Ini</a>
            </div>
        </section>

        <!-- Why Join Section -->
        <section class="why-join-section">
            <h2 class="section-title">Kenapa Banyak Creator & Ibu-Ibu<br>Join Affiliate Mamina?</h2>
            <p class="section-subtitle">Karena Mereka Ingin Punya Penghasilan, Tapi...</p>

            <div class="features-container">
                <!-- Left Side - Question Mark Icon -->
                <div class="question-side">
                    <div class="question-icon">?</div>
                </div>

                <!-- Right Side - Checklist Items -->
                <div class="checklist-side">
                    <div class="checklist-item">
                        <span class="check-icon">✓</span>
                        <p class="checklist-text">Bingung mau mulai dari mana</p>
                    </div>

                    <div class="checklist-item">
                        <span class="check-icon">✓</span>
                        <p class="checklist-text">Takut rugi kalau harus modal</p>
                    </div>

                    <div class="checklist-item">
                        <span class="check-icon">✓</span>
                        <p class="checklist-text">Followers sedikit jadi kurang percaya diri</p>
                    </div>

                    <div class="checklist-item">
                        <span class="check-icon">✓</span>
                        <p class="checklist-text">Sudah posting konten tapi belum menghasilkan</p>
                    </div>

                    <div class="checklist-item">
                        <span class="check-icon">✓</span>
                        <p class="checklist-text">Tidak punya waktu untuk packing & kirim barang</p>
                    </div>

                    <div class="checklist-item">
                        <span class="check-icon">✓</span>
                        <p class="checklist-text">Mau usaha tapi ingin yang simpel dan realistis</p>
                    </div>
                </div>
            </div>

            <p class="bottom-text">
                Mamina membuat semuanya jadi lebih mudah untuk kamu mulai menghasilkan uang dari rumah tanpa ribet!
            </p>
        </section>

        <!-- Benefits Section -->
        <section class="benefits-section">
            <div class="benefits-container">
                <h2 class="benefits-title">Peluang Penghasilan yang Benar-Benar<br>Bisa kamu Mulai dalam 5 Menit</h2>

                <div class="benefits-grid">
                    <div class="benefits-row">
                        <div class="benefit-card">
                            <p class="benefit-text">Tidak perlu modal</p>
                        </div>

                        <div class="benefit-card">
                            <p class="benefit-text">Tidak perlu stok barang</p>
                        </div>

                        <div class="benefit-card">
                            <p class="benefit-text">Tidak perlu kirim paket</p>
                        </div>
                    </div>

                    <div class="benefits-row">
                        <div class="benefit-card">
                            <p class="benefit-text">Materi promosi lengkap tersedia</p>
                        </div>

                        <div class="benefit-card">
                            <p class="benefit-text">Cocok untuk ibu, mahasiswa, creator pemula, bahkan akun kecil</p>
                        </div>
                    </div>
                </div>

                <!-- Summary Section - Full Width -->
                <div class="benefits-summary">
                    <p class="summary-text">Cukup posting, cukup share link dan peluang penghasilan terbuka untuk kamu.</p>
                    <a href="https://wa.link/5pzwur" target="_blank" class="cta-button-purple">Daftar Affiliate Mamina Sekarang</a>
                </div>
            </div>
        </section>

        <!-- Why Easy Section -->
        <section class="why-easy-section">
            <h2 class="why-easy-title">Kenapa Produk Mamina Mudah Dipromosikan?</h2>
            
            <div class="why-easy-content">
                <h3 class="chart-title">Grafik Penjualan Per Bulan</h3>
                <canvas id="salesChart"></canvas>
            </div>

            <div class="testimonial-section">
                <h3 class="testimonial-title">Testimoni Customer</h3>
                <div class="testimonial-box">
                    <div class="testimonial-carousel-wrapper">
                        <button class="carousel-nav prev" aria-label="Previous testimonial">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        
                        <div class="testimonial-carousel">
                            <div class="testimonial-track">
                                <div class="testimonial-card">
                                    <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                                    <p class="testimonial-text">"Mudah banget! 2 minggu pertama sudah dapat komisi 800rb. Cocok untuk ibu rumah tangga seperti saya!"</p>
                                    <p class="testimonial-author">- Siti Nurhaliza, Jakarta</p>
                                </div>

                                <div class="testimonial-card">
                                    <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                                    <p class="testimonial-text">"Followers cuma 2000-an, tapi bulan pertama udah closing 15 produk. Produknya gampang banget dijual!"</p>
                                    <p class="testimonial-author">- Dina Mariana, Bandung</p>
                                </div>

                                <div class="testimonial-card">
                                    <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                                    <p class="testimonial-text">"Nggak perlu stok barang, nggak ribet packing. Cukup share link, uang masuk rekening. Simple!"</p>
                                    <p class="testimonial-author">- Rina Safitri, Surabaya</p>
                                </div>

                                <div class="testimonial-card">
                                    <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                                    <p class="testimonial-text">"Pas banget buat mahasiswa! Fleksibel, bisa dikerjain kapan aja. Bulan lalu dapat 1,2 juta!"</p>
                                    <p class="testimonial-author">- Ayu Wulandari, Yogyakarta</p>
                                </div>

                                <div class="testimonial-card">
                                    <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                                    <p class="testimonial-text">"Konsisten posting 3-4 kali seminggu, sekarang penghasilan sudah 2 jutaan per bulan!"</p>
                                    <p class="testimonial-author">- Fitri Handayani, Semarang</p>
                                </div>

                                <div class="testimonial-card">
                                    <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                                    <p class="testimonial-text">"Produk berkualitas, customer sering repeat order. Komisi 15-20% sangat kompetitif!"</p>
                                    <p class="testimonial-author">- Maya Sari, Medan</p>
                                </div>

                                <div class="testimonial-card">
                                    <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                                    <p class="testimonial-text">"Hobi bikin konten sekarang menghasilkan uang! Materi promosinya lengkap banget."</p>
                                    <p class="testimonial-author">- Linda Permata, Malang</p>
                                </div>

                                <div class="testimonial-card">
                                    <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                                    <p class="testimonial-text">"Join 3 bulan lalu, sekarang jadi passive income. Konten lama masih terus menghasilkan!"</p>
                                    <p class="testimonial-author">- Dewi Kartika, Bali</p>
                                </div>

                                <div class="testimonial-card">
                                    <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                                    <p class="testimonial-text">"Nggak perlu modal! Bisa kerja sambil ngurus rumah dan anak. Perfect untuk ibu rumah tangga."</p>
                                    <p class="testimonial-author">- Ratih Puspita, Tangerang</p>
                                </div>

                                <div class="testimonial-card">
                                    <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                                    <p class="testimonial-text">"Tim Mamina sangat membantu! Selalu ada panduan dan support. Cocok untuk pemula seperti saya."</p>
                                    <p class="testimonial-author">- Sarah Amelia, Bogor</p>
                                </div>
                            </div>
                        </div>
                        
                        <button class="carousel-nav next" aria-label="Next testimonial">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="carousel-dots"></div>
                </div>
            </div>
        </section>

        <!-- Advantages Section -->
        <section class="advantages-section">
            <div class="advantages-container">
                <h2 class="advantages-title">Keuntungan Bergabung Menjadi Affiliate Mamina</h2>

                <div class="advantages-grid">
                    <div class="advantage-card">
                        <p class="advantage-text">Komisi 15% - 20% dari setiap produk yang terjual link kamu</p>
                    </div>

                    <div class="advantage-card">
                        <p class="advantage-text">Konten kamu berpotensi direpost oleh Mamina</p>
                    </div>

                    <div class="advantage-card">
                        <p class="advantage-text">Peluang tambahan penghasilan setiap bulan</p>
                    </div>

                    <div class="advantage-card">
                        <p class="advantage-text">Bisa dijalankan dari rumah</p>
                    </div>

                    <div class="advantage-card">
                        <p class="advantage-text">Tidak ada target penjualan</p>
                    </div>

                    <div class="advantage-card">
                        <p class="advantage-text">Akses materi promosi</p>
                    </div>
                </div>

                <!-- Summary Section - Full Width -->
                <div class="advantages-summary">
                    <p class="advantages-footer">*Hasil bisa berbeda-beda tergantung konsistensi promosi</p>
                </div>
            </div>
        </section>

        <!-- Final CTA Section -->
        <section class="final-cta-section">
            <h2 class="final-cta-title">Mereka Sudah Bergabung. Sekarang Giliran Kamu</h2>

            <div class="final-cta-content">
                <div class="kol-gallery">
                    <div class="kol-item">
                        <video controls>
                            <source src="{{ asset('storage/Mamina/mamina-kol1.mp4') }}" type="video/mp4">
                            Browser tidak mendukung video.
                        </video>
                    </div>
                    <div class="kol-item">
                        <video controls>
                            <source src="{{ asset('storage/Mamina/mamina-kol2.mp4') }}" type="video/mp4">
                            Browser tidak mendukung video.
                        </video>
                    </div>
                    <div class="kol-item">
                        <video controls>
                            <source src="{{ asset('storage/Mamina/mamina-kol3.mp4') }}" type="video/mp4">
                            Browser tidak mendukung video.
                        </video>
                    </div>
                </div>
            </div>

            <div class="final-cta-button">
                <p class="summary-text">Bergabung sekarang dan nikmati semua keuntungan menjadi bagian dari keluarga Mamina.</p>
                <a href="https://wa.link/5pzwur" target="_blank" class="cta-button-purple">Daftar Affiliate Mamina Sekarang</a>
            </div>
        </section>

        <!-- How to Join Section -->
        <section class="how-to-join-section">
            <div class="how-to-join-container">
                <h2 class="how-to-join-title">Cara Gabung Program Affiliate Mamina</h2>

                <div class="steps-box">
                    <div class="step-item">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <p class="step-text">Daftar / Login ke Akun Mamina</p>
                            <p class="step-subtext">Belum punya akun? Klik untuk daftar dan mulai perjalanan kamu.</p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <p class="step-text">Masuk ke Menu "Program Affiliate Mamina"</p>
                            <p class="step-subtext">Buka Akun Saya → pilih menu Program Affiliate Mamina.</p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <p class="step-text">Lengkapi Informasi yang Dibutuhkan</p>
                            <p class="step-subtext">Isi data dengan benar agar proses verifikasi lebih cepat.</p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <p class="step-text">Tunggu Approval dari Admin Mamina</p>
                            <p class="step-subtext">Admin akan menghubungi kamu saat pengajuan disetujui.</p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">5</div>
                        <div class="step-content">
                            <p class="step-text">Bagikan Link Affiliate Pribadi kamu</p>
                            <p class="step-subtext">Share di TikTok, Instagram, WA, atau link bio.</p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">6</div>
                        <div class="step-content">
                            <p class="step-text">Nikmati Penghasilan Tambahan Setiap Bulan</p>
                            <p class="step-subtext">Konsisten posting = peluang cuan jutaan rupiah tanpa modal.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final Call to Action Section -->
        <section class="final-call-section">
            <h2 class="final-call-title">Mulai Menghasilkan dari Konten yang Kamu Suka, Tanpa Modal, Tanpa Ribet. Hanya dengan Sharing Link</h2>

            <p class="final-call-subtitle">Program Affiliate Mamina adalah kesempatan paling mudah dan siegan untuk membangun penghasilan dari rumah.</p>

            <div class="final-call-button">
                <a href="https://wa.link/5pzwur" target="_blank" class="cta-button-purple">Daftar Affiliate Mamina Sekarang</a>
            </div>
        </section>

    <!-- Chart.js -->
    <script src="{{ asset('js/chart.js') }}"></script>
    
    <!-- Pass data to JavaScript -->
    <script>
        window.maminaData = {
            chartData: @json($chartData)
        };
    </script>
    
    <!-- Custom JavaScript -->
    <script src="{{ asset('js/mamina-landing.js') }}"></script>
</body>
</html>