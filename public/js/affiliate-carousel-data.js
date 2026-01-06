/**
 * Affiliate Carousel Data Initialization
 * This file is generated dynamically by affiliate.blade.php
 */

// Function to initialize carousel data
function initAffiliateCarouselData(carouselData) {
    // Make carousel content available globally for carousel.js
    window.slideContents = carouselData;
    console.log('Affiliate carousel data initialized:', carouselData);
}
