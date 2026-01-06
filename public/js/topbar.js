/**
 * Topbar Navigation JavaScript
 * Handles mobile menu toggle and dropdown functionality
 */
(function() {
    'use strict';
    
    // Wait for DOM to be ready
    function initTopbar() {
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById("mobile-menu-button");
        const mobileMenu = document.getElementById("mobile-menu");

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Burger button clicked!');
                mobileMenu.classList.toggle("hidden");
            });
        }

    // Desktop dropdown functionality for Produk
    const produkDropdownBtn = document.getElementById("produk-dropdown-btn");
    const produkDropdownMenu = document.getElementById("produk-dropdown-menu");

    if (produkDropdownBtn && produkDropdownMenu) {
        let timeoutId;

        // Show dropdown on hover
        produkDropdownBtn.parentElement.addEventListener(
            "mouseenter",
            function () {
                clearTimeout(timeoutId);
                produkDropdownMenu.classList.remove(
                    "opacity-0",
                    "invisible",
                    "translate-y-2"
                );
                produkDropdownMenu.classList.add(
                    "opacity-100",
                    "visible",
                    "translate-y-0"
                );
            }
        );

        // Hide dropdown on mouse leave with delay
        produkDropdownBtn.parentElement.addEventListener(
            "mouseleave",
            function () {
                timeoutId = setTimeout(() => {
                    produkDropdownMenu.classList.remove(
                        "opacity-100",
                        "visible",
                        "translate-y-0"
                    );
                    produkDropdownMenu.classList.add(
                        "opacity-0",
                        "invisible",
                        "translate-y-2"
                    );
                }, 150);
            }
        );

        // Toggle dropdown on button click (for touch devices)
        produkDropdownBtn.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent event from bubbling
            const isVisible =
                produkDropdownMenu.classList.contains("opacity-100");

            if (isVisible) {
                produkDropdownMenu.classList.remove(
                    "opacity-100",
                    "visible",
                    "translate-y-0"
                );
                produkDropdownMenu.classList.add(
                    "opacity-0",
                    "invisible",
                    "translate-y-2"
                );
            } else {
                produkDropdownMenu.classList.remove(
                    "opacity-0",
                    "invisible",
                    "translate-y-2"
                );
                produkDropdownMenu.classList.add(
                    "opacity-100",
                    "visible",
                    "translate-y-0"
                );
            }
        });
        
        // Close other dropdowns when Produk is opened
        produkDropdownBtn.addEventListener("click", function (e) {
            if (partnerDropdownMenu && partnerDropdownMenu.classList.contains("opacity-100")) {
                partnerDropdownMenu.classList.remove(
                    "opacity-100",
                    "visible",
                    "translate-y-0"
                );
                partnerDropdownMenu.classList.add(
                    "opacity-0",
                    "invisible",
                    "translate-y-2"
                );
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", function (e) {
            if (!produkDropdownBtn.parentElement.contains(e.target)) {
                produkDropdownMenu.classList.remove(
                    "opacity-100",
                    "visible",
                    "translate-y-0"
                );
                produkDropdownMenu.classList.add(
                    "opacity-0",
                    "invisible",
                    "translate-y-2"
                );
            }
        });
    }

    // Desktop dropdown functionality for Partner
    const partnerDropdownBtn = document.getElementById("partner-dropdown-btn");
    const partnerDropdownMenu = document.getElementById("partner-dropdown-menu");

    if (partnerDropdownBtn && partnerDropdownMenu) {
        let partnerTimeoutId;

        // Show dropdown on hover
        partnerDropdownBtn.parentElement.addEventListener(
            "mouseenter",
            function () {
                clearTimeout(partnerTimeoutId);
                partnerDropdownMenu.classList.remove(
                    "opacity-0",
                    "invisible",
                    "translate-y-2"
                );
                partnerDropdownMenu.classList.add(
                    "opacity-100",
                    "visible",
                    "translate-y-0"
                );
            }
        );

        // Hide dropdown on mouse leave with delay
        partnerDropdownBtn.parentElement.addEventListener(
            "mouseleave",
            function () {
                partnerTimeoutId = setTimeout(() => {
                    partnerDropdownMenu.classList.remove(
                        "opacity-100",
                        "visible",
                        "translate-y-0"
                    );
                    partnerDropdownMenu.classList.add(
                        "opacity-0",
                        "invisible",
                        "translate-y-2"
                    );
                }, 150);
            }
        );

        // Toggle dropdown on button click (for touch devices)
        partnerDropdownBtn.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent event from bubbling
            const isVisible =
                partnerDropdownMenu.classList.contains("opacity-100");

            if (isVisible) {
                partnerDropdownMenu.classList.remove(
                    "opacity-100",
                    "visible",
                    "translate-y-0"
                );
                partnerDropdownMenu.classList.add(
                    "opacity-0",
                    "invisible",
                    "translate-y-2"
                );
            } else {
                partnerDropdownMenu.classList.remove(
                    "opacity-0",
                    "invisible",
                    "translate-y-2"
                );
                partnerDropdownMenu.classList.add(
                    "opacity-100",
                    "visible",
                    "translate-y-0"
                );
            }
        });
        
        // Close other dropdowns when Partner is opened
        partnerDropdownBtn.addEventListener("click", function (e) {
            if (produkDropdownMenu && produkDropdownMenu.classList.contains("opacity-100")) {
                produkDropdownMenu.classList.remove(
                    "opacity-100",
                    "visible",
                    "translate-y-0"
                );
                produkDropdownMenu.classList.add(
                    "opacity-0",
                    "invisible",
                    "translate-y-2"
                );
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", function (e) {
            if (!partnerDropdownBtn.parentElement.contains(e.target)) {
                partnerDropdownMenu.classList.remove(
                    "opacity-100",
                    "visible",
                    "translate-y-0"
                );
                partnerDropdownMenu.classList.add(
                    "opacity-0",
                    "invisible",
                    "translate-y-2"
                );
            }
        });
    }

        // Mobile dropdown functionality (vertical style)
        const mobileProdukDropdownBtn = document.getElementById(
            "mobile-produk-dropdown-btn"
        );
        const mobileProdukDropdownMenu = document.getElementById(
            "mobile-produk-dropdown-menu"
        );

        if (mobileProdukDropdownBtn && mobileProdukDropdownMenu) {
            mobileProdukDropdownBtn.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Toggle visibility
                mobileProdukDropdownMenu.classList.toggle("hidden");

                // Rotate arrow with smooth animation
                const arrow = mobileProdukDropdownBtn.querySelector("svg");
                if (arrow) {
                    arrow.classList.toggle("rotate-180");
                }
            });
        }

        // Mobile gentle living submenu toggle
        const mobileGentleBtn = document.getElementById("mobile-gentle-dropdown-btn");
        const mobileGentleMenu = document.getElementById("mobile-gentle-dropdown-menu");

        if (mobileGentleBtn && mobileGentleMenu) {
            mobileGentleBtn.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                
                mobileGentleMenu.classList.toggle("hidden");

                const arrow = this.querySelector("svg");
                if (arrow) {
                    arrow.classList.toggle("rotate-180");
                }
            });
        }

        // Mobile Partner dropdown
        const mobilePartnerDropdownBtn = document.getElementById("mobile-partner-dropdown-btn");
        const mobilePartnerDropdownMenu = document.getElementById("mobile-partner-dropdown-menu");

        if (mobilePartnerDropdownBtn && mobilePartnerDropdownMenu) {
            mobilePartnerDropdownBtn.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Toggle visibility
                mobilePartnerDropdownMenu.classList.toggle("hidden");

                // Rotate arrow
                const arrow = mobilePartnerDropdownBtn.querySelector("svg");
                if (arrow) {
                    arrow.classList.toggle("rotate-180");
                }
            });
        }

        // Close mobile menu when clicking outside
        document.addEventListener("click", function (e) {
            if (mobileMenu && !mobileMenu.classList.contains("hidden")) {
                const header = document.querySelector("header");
                if (header && !header.contains(e.target)) {
                    mobileMenu.classList.add("hidden");
                }
            }
        });

        // Close mobile menu when window is resized to desktop
        window.addEventListener("resize", function () {
            if (
                window.innerWidth >= 1280 &&
                mobileMenu &&
                !mobileMenu.classList.contains("hidden")
            ) {
                mobileMenu.classList.add("hidden");
            }
        });
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTopbar);
    } else {
        initTopbar();
    }
})();
