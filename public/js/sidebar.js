// public/js/sidebar.js

document.addEventListener("DOMContentLoaded", function () {
    const hamburgerBtn = document.getElementById("hamburger-btn");
    const hamburgerLines = hamburgerBtn
        ? hamburgerBtn.querySelectorAll(".hamburger-line")
        : [];
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.getElementById("main-content");
    const footerContent = document.getElementById("footer-content");
    const sidebarOverlay = document.getElementById("sidebar-overlay");

    let isDesktopExpanded = false;
    let isMobileOpen = false;

    // Initialize sidebar state
    initializeSidebar();

    function initializeSidebar() {
        if (window.innerWidth >= 1024) {
            // Desktop: Start with mini sidebar
            setMiniSidebar();
        } else {
            // Mobile: Start with hidden sidebar
            setMobileHidden();
        }
    }

    // Hamburger button click handler
    if (hamburgerBtn) {
        // Ensure hamburger button is visible
        hamburgerBtn.style.display = "flex";
        hamburgerBtn.style.transition = "all 0.3s cubic-bezier(0.4, 0, 0.2, 1)";

        // Add transition styles to hamburger lines
        hamburgerLines.forEach((line) => {
            line.style.transition = "all 0.3s cubic-bezier(0.4, 0, 0.2, 1)";
        });

        hamburgerBtn.addEventListener("click", function () {
            console.log("Hamburger clicked, window width:", window.innerWidth);
            console.log("Current mobile state:", isMobileOpen);

            // Add click feedback
            hamburgerBtn.style.transform = "scale(0.95)";
            setTimeout(() => {
                hamburgerBtn.style.transform = "scale(1)";
            }, 150);

            if (window.innerWidth >= 1024) {
                // Desktop: Toggle between mini and full sidebar
                isDesktopExpanded = !isDesktopExpanded;

                if (isDesktopExpanded) {
                    setFullSidebar();
                    animateHamburgerToX();
                    hamburgerBtn.classList.add("hamburger-active");
                } else {
                    setMiniSidebar();
                    animateHamburgerToLines();
                    hamburgerBtn.classList.remove("hamburger-active");
                }
            } else {
                // Mobile: Toggle sidebar
                isMobileOpen = !isMobileOpen;
                console.log("Mobile toggle, new state:", isMobileOpen);

                if (isMobileOpen) {
                    console.log("Opening mobile sidebar");
                    setMobileOpen();
                    animateHamburgerToX();
                    hamburgerBtn.classList.add("hamburger-active");
                } else {
                    console.log("Closing mobile sidebar");
                    setMobileHidden();
                    animateHamburgerToLines();
                    hamburgerBtn.classList.remove("hamburger-active");
                }
            }
        });

        // Add hover effects
        hamburgerBtn.addEventListener("mouseenter", function () {
            if (!hamburgerBtn.classList.contains("hamburger-active")) {
                this.style.transform = "scale(1.05)";
            }
        });

        hamburgerBtn.addEventListener("mouseleave", function () {
            if (!hamburgerBtn.classList.contains("hamburger-active")) {
                this.style.transform = "scale(1)";
            }
        });
    }

    // Overlay click to close mobile
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener("click", function () {
            console.log("Overlay clicked");
            isMobileOpen = false;
            setMobileHidden();
            animateHamburgerToLines();
            hamburgerBtn.classList.remove("hamburger-active");
        });
    }

    // Sidebar state functions
    function setMiniSidebar() {
        if (!sidebar || !mainContent) return;

        sidebar.className =
            "sidebar-mini fixed top-20 left-0 h-[calc(100vh-5rem)] w-16 bg-brand-500 shadow-lg transition-all duration-300 ease-in-out z-40 overflow-hidden";
        mainContent.style.marginLeft = "4rem";

        if (footerContent) {
            footerContent.style.marginLeft = "4rem";
            footerContent.style.backgroundColor = "";
            footerContent.style.color = "";
        }

        // Hide text elements
        document.querySelectorAll(".sidebar-text").forEach((el) => {
            el.classList.add("hidden");
        });

        // Center nav items
        document.querySelectorAll(".nav-item").forEach((el) => {
            el.classList.remove("lg:justify-start", "lg:justify-between");
            el.classList.add("justify-center");
        });

        // Close any open submenus
        closeAllSubmenus();
    }

    function setFullSidebar() {
        if (!sidebar || !mainContent) return;

        sidebar.className =
            "sidebar-full fixed top-20 left-0 h-[calc(100vh-5rem)] w-64 bg-brand-500 shadow-lg transition-all duration-300 ease-in-out z-40";
        mainContent.style.marginLeft = "16rem";

        if (footerContent) {
            footerContent.style.marginLeft = "16rem";
            footerContent.style.backgroundColor = "";
            footerContent.style.color = "";
        }

        // Show text elements with delay
        setTimeout(() => {
            document.querySelectorAll(".sidebar-text").forEach((el) => {
                el.classList.remove("hidden");
            });

            // Restore nav items layout
            document.querySelectorAll(".nav-item").forEach((el) => {
                el.classList.remove("justify-center");
                // Don't add justify-between to dropdown buttons, keep them left-aligned
                el.classList.add("lg:justify-start");
            });
        }, 150);
    }

    function setMobileHidden() {
        if (!sidebar || !mainContent) return;

        // Reset sidebar class dan pastikan tersembunyi
        sidebar.className =
            "sidebar-mini fixed top-20 left-0 h-[calc(100vh-5rem)] w-16 bg-brand-500 shadow-lg transform -translate-x-full transition-all duration-300 ease-in-out z-40 overflow-hidden";
        mainContent.style.marginLeft = "0";

        if (footerContent) {
            footerContent.style.marginLeft = "0";
            footerContent.style.backgroundColor = "";
            footerContent.style.color = "";
        }

        if (sidebarOverlay) {
            sidebarOverlay.classList.add("hidden");
        }

        document.body.classList.remove("overflow-hidden");
        closeAllSubmenus();
    }

    function setMobileOpen() {
        if (!sidebar || !mainContent) return;

        sidebar.classList.remove("-translate-x-full");
        sidebar.className =
            "sidebar-full fixed top-20 left-0 h-[calc(100vh-5rem)] w-64 bg-brand-500 shadow-lg transform translate-x-0 transition-all duration-300 ease-in-out z-40";
        mainContent.style.marginLeft = "0";

        if (footerContent) {
            footerContent.style.marginLeft = "0";
        }

        // Show overlay dengan benar
        if (sidebarOverlay) {
            sidebarOverlay.classList.remove("hidden");
            // Force reflow untuk memastikan transition bekerja
            sidebarOverlay.offsetHeight;
        }

        // Show text elements
        document.querySelectorAll(".sidebar-text").forEach((el) => {
            el.classList.remove("hidden");
        });

        // Restore layout for mobile
        document.querySelectorAll(".nav-item").forEach((el) => {
            el.classList.remove("justify-center");
            // Don't add justify-between to dropdown buttons, keep them left-aligned
            el.classList.add("justify-start");
        });

        document.body.classList.add("overflow-hidden");
    }

    // Keep hamburger as hamburger - no X transformation
    function animateHamburgerToX() {
        if (hamburgerLines.length >= 3) {
            // Keep hamburger lines, just add subtle visual feedback with brand color
            hamburgerLines.forEach((line) => {
                line.style.backgroundColor = "#528b89"; // Using brand-500 color directly
            });
        }
    }

    function animateHamburgerToLines() {
        if (hamburgerLines.length >= 3) {
            // Reset to normal hamburger appearance
            hamburgerLines.forEach((line) => {
                line.style.backgroundColor = "";
            });
        }
    }

    // Handle window resize
    window.addEventListener("resize", function () {
        initializeSidebar();
        isDesktopExpanded = false;
        isMobileOpen = false;
        animateHamburgerToLines();
        hamburgerBtn.classList.remove("hamburger-active");
    });

    // ESC key to close
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            if (window.innerWidth >= 1024 && isDesktopExpanded) {
                isDesktopExpanded = false;
                setMiniSidebar();
                animateHamburgerToLines();
                hamburgerBtn.classList.remove("hamburger-active");
            } else if (window.innerWidth < 1024 && isMobileOpen) {
                isMobileOpen = false;
                setMobileHidden();
                animateHamburgerToLines();
                hamburgerBtn.classList.remove("hamburger-active");
            }
        }
    });

    // Outside click handler for mobile
    document.addEventListener("click", function (e) {
        if (
            window.innerWidth < 1024 &&
            isMobileOpen &&
            sidebar &&
            hamburgerBtn
        ) {
            if (
                !sidebar.contains(e.target) &&
                !hamburgerBtn.contains(e.target)
            ) {
                isMobileOpen = false;
                setMobileHidden();
                animateHamburgerToLines();
                hamburgerBtn.classList.remove("hamburger-active");
            }
        }
    });

    // Prevent sidebar clicks from closing it
    if (sidebar) {
        sidebar.addEventListener("click", function (e) {
            e.stopPropagation();
        });
    }

    // Close all submenus function
    function closeAllSubmenus() {
        const allSubmenus = document.querySelectorAll('[id$="-submenu"]');
        const allChevrons = document.querySelectorAll('[id$="-chevron"]');

        allSubmenus.forEach((submenu) => {
            submenu.style.maxHeight = "0px";
        });

        allChevrons.forEach((chevron) => {
            chevron.style.transform = "rotate(0deg)";
        });
    }
});

// Global submenu toggle function (called from HTML onclick)
function toggleSubmenu(submenuId) {
    const submenu = document.getElementById(submenuId + "-submenu");
    const chevron = document.getElementById(submenuId + "-chevron");
    const sidebar = document.getElementById("sidebar");

    // Check if sidebar is in mini mode (width is 4rem/64px)
    const isMiniMode = sidebar && sidebar.classList.contains("sidebar-mini");

    // If in mini mode on desktop, auto-expand sidebar first
    if (isMiniMode && window.innerWidth >= 1024) {
        // Find hamburger button and trigger click to expand
        const hamburgerBtn = document.getElementById("hamburger-btn");
        if (hamburgerBtn) {
            hamburgerBtn.click();
        }

        // Wait for sidebar expansion animation, then show submenu
        setTimeout(() => {
            if (submenu && chevron) {
                submenu.style.maxHeight = submenu.scrollHeight + "px";
                chevron.style.transform = "rotate(180deg)";
            }
        }, 300); // Match the sidebar transition duration

        return; // Exit early since we're handling the submenu opening after expansion
    }

    // Normal submenu toggle behavior for expanded sidebar or mobile
    if (submenu && chevron) {
        if (
            submenu.style.maxHeight === "0px" ||
            submenu.style.maxHeight === ""
        ) {
            submenu.style.maxHeight = submenu.scrollHeight + "px";
            chevron.style.transform = "rotate(180deg)";
        } else {
            submenu.style.maxHeight = "0px";
            chevron.style.transform = "rotate(0deg)";
        }
    }
}

// Keyboard shortcuts
document.addEventListener("keydown", function (e) {
    // Toggle sidebar with Ctrl/Cmd + B
    if ((e.ctrlKey || e.metaKey) && e.key === "b") {
        e.preventDefault();
        const hamburgerBtn = document.getElementById("hamburger-btn");
        if (hamburgerBtn) {
            hamburgerBtn.click();
        }
    }
});
