// Mamina Landing Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    console.log('Mamina Landing Page Loaded');

    // Smooth scroll untuk CTA button
    const ctaButtons = document.querySelectorAll('.cta-button, .cta-button-purple');
    ctaButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            console.log('CTA Button Clicked:', this.textContent);
        });
    });

    // Animasi fade-in untuk cards saat scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '0';
                entry.target.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    entry.target.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, 100);
                
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe semua cards
    const cards = document.querySelectorAll('.benefit-card, .advantage-card, .step-item');
    cards.forEach(card => {
        observer.observe(card);
    });

    // Testimonial Carousel
    initTestimonialCarousel();

    // Create Sales Chart
    const salesChartCanvas = document.getElementById('salesChart');
    if (salesChartCanvas && window.maminaData && window.maminaData.chartData) {
        const ctx = salesChartCanvas.getContext('2d');
        const chartData = window.maminaData.chartData;
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: chartData.sales,
                    borderColor: '#8B5CF6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointBackgroundColor: '#8B5CF6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                family: 'Poppins',
                                weight: '500'
                            },
                            padding: 20,
                            color: '#333'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            family: 'Poppins',
                            weight: '600'
                        },
                        bodyFont: {
                            size: 13,
                            family: 'Poppins'
                        },
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                            },
                            font: {
                                size: 12,
                                family: 'Poppins'
                            },
                            color: '#666'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 11,
                                family: 'Poppins'
                            },
                            color: '#666'
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Tracking untuk analytics (opsional)
    function trackEvent(eventName, eventData) {
        console.log('Event:', eventName, eventData);
        // Integrasi dengan Google Analytics atau analytics lainnya
        // gtag('event', eventName, eventData);
    }

    // Track CTA clicks
    ctaButtons.forEach((button, index) => {
        button.addEventListener('click', function() {
            trackEvent('cta_click', {
                button_index: index,
                button_text: this.textContent.trim(),
                button_href: this.getAttribute('href')
            });
        });
    });
});

// Testimonial Carousel Function
function initTestimonialCarousel() {
    const track = document.querySelector('.testimonial-track');
    const slides = document.querySelectorAll('.testimonial-card');
    const prevButton = document.querySelector('.carousel-nav.prev');
    const nextButton = document.querySelector('.carousel-nav.next');
    const dotsContainer = document.querySelector('.carousel-dots');
    
    if (!track || !slides.length) return;
    
    let currentIndex = 0;
    const totalSlides = slides.length;
    
    // Create dots
    slides.forEach((_, index) => {
        const dot = document.createElement('div');
        dot.classList.add('dot');
        if (index === 0) dot.classList.add('active');
        dot.addEventListener('click', () => goToSlide(index));
        dotsContainer.appendChild(dot);
    });
    
    const dots = document.querySelectorAll('.dot');
    
    function updateCarousel() {
        const offset = -currentIndex * 100;
        track.style.transform = `translateX(${offset}%)`;
        
        // Update dots
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
    }
    
    function goToSlide(index) {
        currentIndex = index;
        updateCarousel();
    }
    
    function nextSlide() {
        currentIndex = (currentIndex + 1) % totalSlides;
        updateCarousel();
    }
    
    function prevSlide() {
        currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
        updateCarousel();
    }
    
    // Event listeners
    nextButton.addEventListener('click', nextSlide);
    prevButton.addEventListener('click', prevSlide);
    
    // Auto play
    let autoplayInterval = setInterval(nextSlide, 5000);
    
    // Pause on hover
    const carouselWrapper = document.querySelector('.testimonial-carousel-wrapper');
    carouselWrapper.addEventListener('mouseenter', () => {
        clearInterval(autoplayInterval);
    });
    
    carouselWrapper.addEventListener('mouseleave', () => {
        autoplayInterval = setInterval(nextSlide, 5000);
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') prevSlide();
        if (e.key === 'ArrowRight') nextSlide();
    });
}
