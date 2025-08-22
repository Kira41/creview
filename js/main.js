// Casino Review Site JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the application
    initializeApp();
});

function initializeApp() {
    // Initialize mobile navigation
    initMobileNav();
    
    // Initialize filters and search
    initFilters();
    
    // Initialize casino cards animations
    initAnimations();
    
    // Initialize load more functionality
    initLoadMore();
    
    // Initialize smooth scrolling
    initSmoothScrolling();
    
    // Initialize PHP integration points
    initPHPIntegration();
}

// Mobile Navigation
function initMobileNav() {
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            
            // Animate hamburger menu
            const bars = navToggle.querySelectorAll('.bar');
            bars.forEach((bar, index) => {
                bar.style.transform = navMenu.classList.contains('active') 
                    ? `rotate(${index === 0 ? 45 : index === 2 ? -45 : 0}deg) translate(${index === 1 ? '100px' : '0'}, ${index === 0 ? '6px' : index === 2 ? '-6px' : '0'})`
                    : 'none';
                bar.style.opacity = navMenu.classList.contains('active') && index === 1 ? '0' : '1';
            });
        });
        
        // Close menu when clicking on links
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
            });
        });
    }
}

// Filters and Search Functionality
function initFilters() {
    const sortSelect = document.getElementById('sort-select');
    const ratingFilter = document.getElementById('rating-filter');
    const typeFilter = document.getElementById('type-filter');
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.querySelector('.search-btn');
    
    // Add event listeners
    if (sortSelect) sortSelect.addEventListener('change', applyFilters);
    if (ratingFilter) ratingFilter.addEventListener('change', applyFilters);
    if (typeFilter) typeFilter.addEventListener('change', applyFilters);
    if (searchInput) {
        searchInput.addEventListener('input', debounce(applyFilters, 300));
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyFilters();
            }
        });
    }
    if (searchBtn) searchBtn.addEventListener('click', applyFilters);
}

function applyFilters() {
    const sortValue = document.getElementById('sort-select')?.value || 'rating';
    const ratingValue = parseFloat(document.getElementById('rating-filter')?.value || 0);
    const typeValue = document.getElementById('type-filter')?.value || 'all';
    const searchValue = document.getElementById('search-input')?.value.toLowerCase() || '';
    
    const casinoCards = document.querySelectorAll('.casino-card');
    let visibleCards = [];
    
    casinoCards.forEach(card => {
        const rating = parseFloat(card.dataset.rating || 0);
        const type = card.dataset.type || '';
        const name = card.dataset.name?.toLowerCase() || '';
        
        // Apply filters
        const matchesRating = rating >= ratingValue;
        const matchesType = typeValue === 'all' || type === typeValue;
        const matchesSearch = searchValue === '' || name.includes(searchValue);
        
        if (matchesRating && matchesType && matchesSearch) {
            card.style.display = 'block';
            card.classList.add('fade-in');
            visibleCards.push({
                element: card,
                rating: rating,
                name: name
            });
        } else {
            card.style.display = 'none';
            card.classList.remove('fade-in');
        }
    });
    
    // Sort visible cards
    sortCards(visibleCards, sortValue);
    
    // Update results count
    updateResultsCount(visibleCards.length);
    
    // Trigger PHP filter update if available
    if (window.phpFilterUpdate) {
        window.phpFilterUpdate({
            sort: sortValue,
            rating: ratingValue,
            type: typeValue,
            search: searchValue
        });
    }
}

function sortCards(cards, sortBy) {
    const container = document.getElementById('casinos-container');
    if (!container) return;
    
    cards.sort((a, b) => {
        switch (sortBy) {
            case 'rating':
                return b.rating - a.rating;
            case 'name':
                return a.name.localeCompare(b.name);
            case 'newest':
                // This would typically use a date field
                return Math.random() - 0.5; // Random for demo
            case 'bonus':
                // This would typically use bonus value
                return Math.random() - 0.5; // Random for demo
            default:
                return 0;
        }
    });
    
    // Reorder DOM elements
    cards.forEach(card => {
        container.appendChild(card.element);
    });
}

function updateResultsCount(count) {
    let countElement = document.getElementById('results-count');
    if (!countElement) {
        countElement = document.createElement('div');
        countElement.id = 'results-count';
        countElement.className = 'results-count';
        const gridHeader = document.querySelector('.grid-header');
        if (gridHeader) {
            gridHeader.appendChild(countElement);
        }
    }
    
    countElement.textContent = `Showing ${count} casino${count !== 1 ? 's' : ''}`;
}

// Animations
function initAnimations() {
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, observerOptions);
    
    // Observe casino cards and process items
    const elementsToAnimate = document.querySelectorAll('.casino-card, .process-item');
    elementsToAnimate.forEach(el => observer.observe(el));
    
    // Counter animation for hero stats
    animateCounters();
}

function animateCounters() {
    const counters = document.querySelectorAll('.stat-number');
    
    counters.forEach(counter => {
        const target = parseInt(counter.textContent.replace(/\D/g, ''));
        const suffix = counter.textContent.replace(/\d/g, '');
        let current = 0;
        const increment = target / 50;
        
        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = Math.ceil(current) + suffix;
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target + suffix;
            }
        };
        
        // Start animation when element is visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    updateCounter();
                    observer.unobserve(entry.target);
                }
            });
        });
        
        observer.observe(counter);
    });
}

// Load More Functionality
function initLoadMore() {
    const loadMoreBtn = document.getElementById('load-more-btn');
    
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            loadMoreCasinos();
        });
    }
}

function loadMoreCasinos() {
    const loadMoreBtn = document.getElementById('load-more-btn');
    const container = document.getElementById('casinos-container');
    
    if (!container) return;
    
    // Show loading state
    loadMoreBtn.innerHTML = '<div class="spinner"></div> Loading...';
    loadMoreBtn.disabled = true;
    
    // Simulate API call or PHP request
    setTimeout(() => {
        // This would typically be replaced with actual PHP/AJAX call
        const newCasinos = generateSampleCasinos(3);
        
        newCasinos.forEach(casino => {
            container.appendChild(casino);
        });
        
        // Reset button
        loadMoreBtn.innerHTML = '<i class="fas fa-plus"></i> Load More Casinos';
        loadMoreBtn.disabled = false;
        
        // Trigger PHP load more if available
        if (window.phpLoadMore) {
            window.phpLoadMore();
        }
    }, 1000);
}

function generateSampleCasinos(count) {
    const sampleData = [
        {
            name: 'Lucky Spin',
            rating: 4.5,
            type: 'online',
            bonus: '100% Match Bonus up to $500',
            features: ['Live dealer games', 'Fast withdrawals', 'Mobile optimized']
        },
        {
            name: 'Golden Palace',
            rating: 4.3,
            type: 'sweepstakes',
            bonus: '50,000 Gold Coins + 25 Sweep Coins',
            features: ['Daily bonuses', '500+ games', '24/7 support']
        },
        {
            name: 'Royal Casino',
            rating: 4.7,
            type: 'crypto',
            bonus: '200% Crypto Bonus + 50 Free Spins',
            features: ['Crypto payments', 'Provably fair', 'Instant deposits']
        }
    ];
    
    const casinos = [];
    
    for (let i = 0; i < count; i++) {
        const data = sampleData[i % sampleData.length];
        const card = createCasinoCard(data);
        casinos.push(card);
    }
    
    return casinos;
}

function createCasinoCard(data) {
    const card = document.createElement('div');
    card.className = 'casino-card fade-in';
    card.dataset.rating = data.rating;
    card.dataset.type = data.type;
    card.dataset.name = data.name;
    
    const ratingClass = data.rating >= 4.5 ? 'excellent' : data.rating >= 4.0 ? 'good' : 'fair';
    const ratingLabel = data.rating >= 4.5 ? 'Excellent' : data.rating >= 4.0 ? 'Good' : 'Fair';
    
    card.innerHTML = `
        <div class="casino-header">
            <div class="casino-logo">
                <img src="https://via.placeholder.com/120x60/4CAF50/white?text=${encodeURIComponent(data.name)}" alt="${data.name}">
            </div>
            <div class="casino-rating">
                <span class="rating-badge ${ratingClass}">${data.rating}</span>
                <span class="rating-label">${ratingLabel}</span>
            </div>
        </div>
        
        <div class="casino-content">
            <h3 class="casino-name">${data.name}</h3>
            <div class="casino-bonus">
                <span class="bonus-text">${data.bonus}</span>
            </div>
            
            <ul class="casino-features">
                ${data.features.map(feature => `<li><i class="fas fa-check"></i> ${feature}</li>`).join('')}
            </ul>
            
            <div class="casino-actions">
                <a href="casino-detail.html?name=${encodeURIComponent(data.name)}" class="btn btn-primary">Read Review</a>
                <a href="#" class="btn btn-secondary">Visit Site</a>
            </div>
        </div>
    `;
    
    return card;
}

// Smooth Scrolling
function initSmoothScrolling() {
    const links = document.querySelectorAll('a[href^="#"]');

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// PHP Integration Points
function initPHPIntegration() {
    // Set up global functions for PHP to call
    window.casinoReviewApp = {
        updateCasinoData: updateCasinoData,
        addNewCasino: addNewCasino,
        removeCasino: removeCasino,
        updateFilters: applyFilters,
        loadMoreCasinos: loadMoreCasinos
    };
    
    // Check if PHP data is available
    if (window.phpCasinoData) {
        loadPHPCasinoData(window.phpCasinoData);
    }
    
    // Set up AJAX endpoints for PHP communication
    setupAjaxEndpoints();
}

function updateCasinoData(casinoId, newData) {
    const card = document.querySelector(`[data-casino-id="${casinoId}"]`);
    if (card) {
        // Update card with new data
        if (newData.rating) {
            card.dataset.rating = newData.rating;
            const ratingBadge = card.querySelector('.rating-badge');
            if (ratingBadge) ratingBadge.textContent = newData.rating;
        }
        
        if (newData.name) {
            card.dataset.name = newData.name;
            const nameElement = card.querySelector('.casino-name');
            if (nameElement) nameElement.textContent = newData.name;
        }
        
        // Trigger re-filter
        applyFilters();
    }
}

function addNewCasino(casinoData) {
    const container = document.getElementById('casinos-container');
    if (container) {
        const newCard = createCasinoCard(casinoData);
        container.appendChild(newCard);
        applyFilters();
    }
}

function removeCasino(casinoId) {
    const card = document.querySelector(`[data-casino-id="${casinoId}"]`);
    if (card) {
        card.remove();
        applyFilters();
    }
}

function loadPHPCasinoData(data) {
    const container = document.getElementById('casinos-container');
    if (container && data.casinos) {
        // Clear existing sample data
        container.innerHTML = '';
        
        // Add PHP-provided casino data
        data.casinos.forEach(casino => {
            const card = createCasinoCard(casino);
            container.appendChild(card);
        });
        
        // Update total count
        if (data.totalCount) {
            const totalElement = document.getElementById('total-casinos');
            if (totalElement) {
                totalElement.textContent = data.totalCount + '+';
            }
        }
    }
}

function setupAjaxEndpoints() {
    // Set up fetch wrapper for PHP communication
    window.phpAjax = {
        get: function(endpoint, params = {}) {
            const url = new URL(endpoint, window.location.origin);
            Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));
            
            return fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => response.json());
        },
        
        post: function(endpoint, data = {}) {
            return fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            }).then(response => response.json());
        }
    };
}

// Utility Functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Error Handling
window.addEventListener('error', function(e) {
    console.error('Casino Review App Error:', e.error);
    
    // Show user-friendly error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #dc3545;
        color: white;
        padding: 1rem;
        border-radius: 8px;
        z-index: 10000;
        max-width: 300px;
    `;
    errorDiv.textContent = 'An error occurred. Please refresh the page.';
    
    document.body.appendChild(errorDiv);
    
    setTimeout(() => {
        errorDiv.remove();
    }, 5000);
});

// Performance Monitoring
if ('performance' in window) {
    window.addEventListener('load', function() {
        setTimeout(() => {
            const perfData = performance.getEntriesByType('navigation')[0];
            console.log('Page Load Time:', perfData.loadEventEnd - perfData.loadEventStart, 'ms');
        }, 0);
    });
}

