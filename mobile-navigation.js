/**
 * Simplified Mobile Navigation for MKM Accounting
 */

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing mobile menu...');
    initializeMobileMenu();
    initializeLanguageBar();
});

/**
 * Initialize simplified mobile navigation
 */
function initializeMobileMenu() {
    console.log('Initializing mobile menu...');
    
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileNavOverlay = document.getElementById('mobileNavOverlay');
    const mobileNav = document.getElementById('mobileNav');
    const mobileCloseBtn = document.getElementById('mobileCloseBtn');
    const mobileServicesBtn = document.getElementById('mobileServicesBtn');
    const mobileServicesList = document.getElementById('mobileServicesList');
    
    console.log('Elements found:', {
        mobileMenuBtn: !!mobileMenuBtn,
        mobileNavOverlay: !!mobileNavOverlay,
        mobileCloseBtn: !!mobileCloseBtn,
        mobileServicesBtn: !!mobileServicesBtn,
        mobileServicesList: !!mobileServicesList
    });
    
    // Mobile menu button click
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Hamburger clicked!');
            openMobileMenu();
        });
    } else {
        console.error('Mobile menu button not found!');
    }
    
    // Close button click
    if (mobileCloseBtn) {
        mobileCloseBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Close button clicked!');
            closeMobileMenu();
        });
    }
    
    // Overlay click to close
    if (mobileNavOverlay) {
        mobileNavOverlay.addEventListener('click', function(e) {
            if (e.target === mobileNavOverlay) {
                console.log('Overlay clicked!');
                closeMobileMenu();
            }
        });
    }
    
    // Services dropdown toggle
    if (mobileServicesBtn && mobileServicesList) {
        mobileServicesBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Services button clicked!');
            const dropdownItem = mobileServicesBtn.closest('.mobile-dropdown');
            const isActive = mobileServicesList.classList.contains('active');
            if (isActive) {
                mobileServicesList.classList.remove('active');
                dropdownItem.classList.remove('active');
            } else {
                mobileServicesList.classList.add('active');
                dropdownItem.classList.add('active');
            }
        });
    }
    
    // Close menu when clicking nav links
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-link, .mobile-sub-link');
    console.log('Found mobile nav links:', mobileNavLinks.length);
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', function() {
            console.log('Nav link clicked, closing menu...');
            closeMobileMenu();
        });
    });
    
    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeMobileMenu();
        }
    });
    
    // Close on window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeMobileMenu();
        }
    });
}

/**
 * Open mobile menu
 */
function openMobileMenu() {
    console.log('Opening mobile menu...');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileNavOverlay = document.getElementById('mobileNavOverlay');
    const mobileNav = document.getElementById('mobileNav');

    if (mobileMenuBtn && mobileNavOverlay && mobileNav) {
        mobileMenuBtn.classList.add('active');
        mobileNavOverlay.classList.add('active');
        mobileNav.classList.add('active');
        document.body.style.overflow = 'hidden';
        console.log('Mobile menu opened successfully!');
    } else {
        console.error('Could not open mobile menu - elements not found');
    }
}

/**
 * Close mobile menu
 */
function closeMobileMenu() {
    console.log('Closing mobile menu...');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileNavOverlay = document.getElementById('mobileNavOverlay');
    const mobileNav = document.getElementById('mobileNav');
    const mobileServicesList = document.getElementById('mobileServicesList');
    const mobileServicesBtn = document.getElementById('mobileServicesBtn');

    if (mobileMenuBtn && mobileNavOverlay && mobileNav) {
        mobileMenuBtn.classList.remove('active');
        mobileNavOverlay.classList.remove('active');
        mobileNav.classList.remove('active');
        document.body.style.overflow = '';

        // Close services dropdown
        if (mobileServicesList) {
            mobileServicesList.classList.remove('active');
            const dropdownItem = document.querySelector('.mobile-dropdown');
            if (dropdownItem) {
                dropdownItem.classList.remove('active');
            }
        }
        console.log('Mobile menu closed successfully!');
    }
}

/**
 * Initialize language functionality
 */
function initializeLanguageBar() {
    // Google Translate initialization
    if (typeof google !== 'undefined' && google.translate) {
        new google.translate.TranslateElement(
            { 
                pageLanguage: 'en', 
                includedLanguages: 'en,pl', 
                autoDisplay: false 
            },
            'google_translate_element'
        );
    }
}

/**
 * Set language
 */
function setLanguage(lang) {
    const select = document.querySelector("select.goog-te-combo");
    if (select) {
        select.value = lang;
        select.dispatchEvent(new Event("change"));
    }
}

// Make functions globally available
window.openMobileMenu = openMobileMenu;
window.closeMobileMenu = closeMobileMenu;
window.setLanguage = setLanguage;