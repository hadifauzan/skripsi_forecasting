// Chart.js configuration for sales data
function initSalesChart() {
    const ctx = document.getElementById('salesChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Oktober', 'November', 'Desember'],
            datasets: [{
                label: 'Penjualan (Juta Rupiah)',
                data: [7, 9, 11],
                backgroundColor: 'rgba(30, 64, 175, 0.8)',
                borderColor: 'rgba(30, 64, 175, 1)',
                borderWidth: 2,
                borderRadius: 8,
                barPercentage: 0.7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        color: '#1E40AF'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y + ' Juta';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value + ' Jt';
                        },
                        font: {
                            size: 12
                        },
                        stepSize: 2
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Testimonial Carousel
let currentSlide = 0;
let slides, dots;

function initTestimonialCarousel() {
    slides = document.querySelectorAll('.testimonial-slide');
    dots = document.querySelectorAll('.dot');
    
    // Auto-slide every 5 seconds
    setInterval(() => {
        currentSlide++;
        showTestimonial(currentSlide);
    }, 5000);
}

function showTestimonial(n) {
    if (n >= slides.length) { currentSlide = 0; }
    if (n < 0) { currentSlide = slides.length - 1; }
    
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));
    
    slides[currentSlide].classList.add('active');
    dots[currentSlide].classList.add('active');
}

function changeTestimonial(n) {
    currentSlide += n;
    showTestimonial(currentSlide);
}

function currentTestimonial(n) {
    currentSlide = n;
    showTestimonial(currentSlide);
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    initTestimonialCarousel();
});
