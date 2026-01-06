// Gentle Living Landing Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    console.log('Gentle Living Landing Page Loaded');

    // Initialize Sales Chart with Intersection Observer
    const chartCanvas = document.getElementById('salesChart');
    if (chartCanvas && window.gentleLivingData && window.gentleLivingData.chartData) {
        const chartObserver = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting && !chartCanvas.dataset.initialized) {
                    // Mark as initialized to prevent multiple initializations
                    chartCanvas.dataset.initialized = 'true';
                    
                    // Delay sedikit untuk efek yang lebih smooth
                    setTimeout(() => {
                        initSalesChart(window.gentleLivingData.chartData);
                    }, 200);
                    
                    // Stop observing after initialization
                    chartObserver.unobserve(chartCanvas);
                }
            });
        }, {
            threshold: 0.2, // Trigger saat 20% chart terlihat
            rootMargin: '0px'
        });
        
        chartObserver.observe(chartCanvas);
    }

    // Smooth scroll untuk CTA button
    const ctaButton = document.querySelector('.cta-button');
    if (ctaButton) {
        ctaButton.addEventListener('click', function(e) {
            e.preventDefault();
            // Bisa tambahkan logika redirect atau modal di sini
            console.log('CTA Button Clicked');
            // Contoh: window.location.href = '/register';
        });
    }

    // Animasi fade-in untuk feature cards saat scroll
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

    // Observe semua feature cards
    const featureCards = document.querySelectorAll('.feature-card');
    featureCards.forEach(card => {
        observer.observe(card);
    });

    // Tracking untuk analytics (opsional)
    function trackEvent(eventName, eventData) {
        console.log('Event:', eventName, eventData);
        // Integrasi dengan Google Analytics atau analytics lainnya
        // gtag('event', eventName, eventData);
    }

    // Track CTA clicks
    if (ctaButton) {
        ctaButton.addEventListener('click', function() {
            trackEvent('cta_click', {
                location: 'hero_section',
                button_text: 'Gabung Affiliate Hari Ini'
            });
        });
    }

    // Track feature card interactions
    featureCards.forEach((card, index) => {
        card.addEventListener('mouseenter', function() {
            trackEvent('feature_hover', {
                feature_index: index,
                feature_text: card.querySelector('.feature-title')?.textContent || ''
            });
        });
    });
});

// Initialize Sales Chart Function
function initSalesChart(chartData) {
    const canvas = document.getElementById('salesChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Jumlah Transaksi',
                data: chartData.orders,
                backgroundColor: 'rgba(167, 139, 250, 0.1)',
                borderColor: 'rgba(167, 139, 250, 1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#9333ea',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2.5,
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart',
                onProgress: function(animation) {
                    // Progress callback untuk animasi yang lebih smooth
                },
                onComplete: function() {
                    console.log('Chart animation completed');
                },
                y: {
                    duration: 2000,
                    from: canvas.height,
                    easing: 'easeOutElastic'
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#4a3a4a',
                        font: {
                            size: 14,
                            family: 'Poppins',
                            weight: '600'
                        },
                        padding: 15
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(147, 51, 234, 0.9)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    titleFont: {
                        size: 14,
                        family: 'Poppins',
                        weight: '600'
                    },
                    bodyFont: {
                        size: 13,
                        family: 'Poppins'
                    },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: 12,
                            family: 'Poppins'
                        },
                        padding: 8
                    },
                    grid: {
                        color: 'rgba(169, 122, 170, 0.1)',
                        drawBorder: false
                    }
                },
                x: {
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: 12,
                            family: 'Poppins'
                        },
                        padding: 8
                    },
                    grid: {
                        display: false,
                        drawBorder: false
                    }
                }
            }
        }
    });
}
