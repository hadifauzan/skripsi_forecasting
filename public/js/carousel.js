document.addEventListener("DOMContentLoaded", function () {
    // Mobile carousel elements
    const mobileSlides = document.querySelectorAll(
        "#banner-carousel-mobile .banner-slide"
    );

    // Desktop carousel elements
    const desktopSlides = document.querySelectorAll(
        "#banner-carousel .banner-slide"
    );

    // Common elements
    const indicators = document.querySelectorAll("#carousel-indicators button");
    const indicatorLines = document.querySelectorAll(".indicator-line");
    const mobileContentElement = document.getElementById("content");
    const desktopContentElement = document.getElementById("desktop-content");

    let currentSlide = 0;
    let slideInterval;

    // Debug: Log carousel elements and data
    console.log("Mobile slides found:", mobileSlides.length);
    console.log("Desktop slides found:", desktopSlides.length);
    console.log("Indicators found:", indicators.length);
    console.log("Slide contents:", window.slideContents);

    function updateContent(index) {
        // Check if slideContents is available (passed from blade template)
        if (
            typeof window.slideContents === "undefined" ||
            !window.slideContents[index]
        ) {
            console.warn("Slide content not available for index:", index);
            return;
        }

        const slideData = window.slideContents[index];

        // Update mobile content
        if (mobileContentElement) {
            mobileContentElement.innerHTML = `<span class="text-white font-nunito font-bold text-xl sm:text-2xl">${slideData.title}</span><br />
            <span class="text-white font-nunito font-normal text-base sm:text-lg">${slideData.description}</span>`;
        }

        // Update desktop content
        if (desktopContentElement) {
            desktopContentElement.innerHTML = `<span class="text-white font-nunito font-bold text-xl lg:text-2xl xl:text-3xl">${slideData.title}</span><br />
            <span class="text-white font-nunito font-normal text-base lg:text-lg">${slideData.description}</span>`;
        }
    }

    function showSlide(index) {
        // Update mobile slides
        mobileSlides.forEach((slide, i) => {
            slide.style.opacity = i === index ? "1" : "0";
            slide.style.zIndex = i === index ? "1" : "0";
        });

        // Update desktop slides
        desktopSlides.forEach((slide, i) => {
            slide.style.opacity = i === index ? "1" : "0";
            slide.style.zIndex = i === index ? "1" : "0";
        });

        // Update indicators
        indicatorLines.forEach((line, i) => {
            if (i === index) {
                line.classList.add("bg-white/80");
                line.classList.remove("bg-white/40");
            } else {
                line.classList.remove("bg-white/80");
                line.classList.add("bg-white/40");
            }
        });

        // Update content
        updateContent(index);
        currentSlide = index;
    }

    function nextSlide() {
        const totalSlides = window.slideContents
            ? window.slideContents.length
            : Math.max(mobileSlides.length, desktopSlides.length);
        const newIndex = (currentSlide + 1) % totalSlides;
        showSlide(newIndex);
    }

    function goToSlide(index) {
        clearInterval(slideInterval);
        showSlide(index);
        startAutoSlide();
    }

    // Event listeners for indicators
    indicators.forEach((indicator, index) => {
        indicator.addEventListener("click", function () {
            goToSlide(index);
        });
    });

    function startAutoSlide() {
        slideInterval = setInterval(nextSlide, 2000);
    }

    // Touch/swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;

    function handleSwipe() {
        const totalSlides = window.slideContents
            ? window.slideContents.length
            : 3;

        if (touchEndX < touchStartX - 50) {
            // Swipe left - next slide
            goToSlide((currentSlide + 1) % totalSlides);
        }
        if (touchEndX > touchStartX + 50) {
            // Swipe right - previous slide
            goToSlide((currentSlide - 1 + totalSlides) % totalSlides);
        }
    }

    // Touch event listeners for mobile carousel
    const mobileCarousel = document.getElementById("banner-carousel-mobile");
    if (mobileCarousel) {
        mobileCarousel.addEventListener("touchstart", function (e) {
            touchStartX = e.changedTouches[0].screenX;
            clearInterval(slideInterval);
        });

        mobileCarousel.addEventListener("touchend", function (e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
            startAutoSlide();
        });
    }

    // Pause on hover for desktop
    const desktopCarousel = document.getElementById("banner-carousel");
    if (desktopCarousel) {
        desktopCarousel.addEventListener("mouseenter", () => {
            clearInterval(slideInterval);
        });

        desktopCarousel.addEventListener("mouseleave", () => {
            startAutoSlide();
        });
    }

    // Initialize
    updateContent(0);
    startAutoSlide();
});
