@extends('layouts.app')

@section('content')
    <div class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Supa Farm Supplies</h1>
            <p>Fresh farm products straight from nature to your table</p>
            <a href="/products" class="btn-primary btn-sm">Shop Now</a>
        </div>
    </div>

    <!-- Load immediately - above the fold -->
    @include('partials.product_categories')

    <!-- Lazy load below the fold sections -->
    <div data-lazy-section="popular-products" class="lazy-section">
        <div class="loading-placeholder">
            <i class="fas fa-spinner fa-spin"></i> Loading products...
        </div>
    </div>

    <div data-lazy-section="latest-news" class="lazy-section">
        <div class="loading-placeholder">
            <i class="fas fa-spinner fa-spin"></i> Loading news...
        </div>
    </div>

    <div data-lazy-section="gallery-scroll" class="lazy-section">
        <div class="loading-placeholder">
            <i class="fas fa-spinner fa-spin"></i> Loading gallery...
        </div>
    </div>

    <div data-lazy-section="about" class="lazy-section">
        <div class="loading-placeholder">
            <i class="fas fa-spinner fa-spin"></i> Loading about...
        </div>
    </div>

    <div data-lazy-section="seo" class="lazy-section">
        <div class="loading-placeholder">
            <i class="fas fa-spinner fa-spin"></i> Loading content...
        </div>
    </div>

    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top" aria-label="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <style>
        /* Loading Placeholder */
        .lazy-section {
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loading-placeholder {
            color: var(--primary-color);
            font-size: 1.1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .loading-placeholder i {
            font-size: 1.5rem;
        }

        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background-color: var(--dark-color);
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        .back-to-top:active {
            transform: translateY(-1px);
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .back-to-top {
                width: 45px;
                height: 45px;
                bottom: 20px;
                right: 20px;
                font-size: 1rem;
            }
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Loading state optimization */
        .lazy-section.loaded {
            min-height: auto;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ==========================================
            // LAZY LOADING SECTIONS
            // ==========================================

            const lazySections = document.querySelectorAll('.lazy-section');
            const sectionRoutes = {
                'popular-products': '{{ route("home.section", "popular-products") }}',
                'latest-news': '{{ route("home.section", "latest-news") }}',
                'gallery-scroll': '{{ route("home.section", "gallery-scroll") }}',
                'about': '{{ route("home.section", "about") }}',
                'seo': '{{ route("home.section", "seo") }}'
            };

            // Intersection Observer for lazy loading
            const observerOptions = {
                root: null,
                rootMargin: '50px', // Start loading 50px before section enters viewport
                threshold: 0.01
            };

            const sectionObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !entry.target.classList.contains('loaded')) {
                        loadSection(entry.target);
                    }
                });
            }, observerOptions);

            // Observe all lazy sections
            lazySections.forEach(section => {
                sectionObserver.observe(section);
            });

            // Function to load section content
            function loadSection(sectionElement) {
                const sectionName = sectionElement.getAttribute('data-lazy-section');
                const route = sectionRoutes[sectionName];

                if (!route) return;

                // Mark as loading
                sectionElement.classList.add('loading');

                fetch(route, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.text();
                })
                .then(html => {
                    sectionElement.innerHTML = html;
                    sectionElement.classList.add('loaded');
                    sectionElement.classList.remove('loading');

                    // Re-initialize any scripts in the loaded section
                    initializeSectionScripts(sectionElement);
                })
                .catch(error => {
                    console.error('Error loading section:', error);
                    sectionElement.innerHTML = `
                        <div class="loading-error">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>Failed to load content. <a href="#" onclick="location.reload()">Reload page</a></p>
                        </div>
                    `;
                });
            }

            // Re-initialize scripts for dynamically loaded sections
            function initializeSectionScripts(section) {
                const scripts = section.querySelectorAll('script');
                scripts.forEach(oldScript => {
                    const newScript = document.createElement('script');
                    Array.from(oldScript.attributes).forEach(attr => {
                        newScript.setAttribute(attr.name, attr.value);
                    });
                    newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                    oldScript.parentNode.replaceChild(newScript, oldScript);
                });
            }

            // ==========================================
            // BACK TO TOP BUTTON
            // ==========================================

            const backToTopButton = document.getElementById('backToTop');

            // Show/hide button based on scroll position
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    backToTopButton.classList.add('visible');
                } else {
                    backToTopButton.classList.remove('visible');
                }
            });

            // Scroll to top on button click
            backToTopButton.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            // ==========================================
            // IMAGE LAZY LOADING OPTIMIZATION
            // ==========================================

            // Native lazy loading for images already in DOM
            const images = document.querySelectorAll('img[loading="lazy"]');

            // Fallback for browsers that don't support native lazy loading
            if ('loading' in HTMLImageElement.prototype) {
                // Browser supports native lazy loading
                images.forEach(img => {
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                    }
                });
            } else {
                // Fallback: use Intersection Observer
                const imageObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            if (img.dataset.src) {
                                img.src = img.dataset.src;
                                img.removeAttribute('data-src');
                            }
                            imageObserver.unobserve(img);
                        }
                    });
                });

                images.forEach(img => imageObserver.observe(img));
            }

            // ==========================================
            // PERFORMANCE OPTIMIZATION
            // ==========================================

            // Preload critical resources
            const preloadLink = document.createElement('link');
            preloadLink.rel = 'preload';
            preloadLink.as = 'image';
            preloadLink.href = '{{ asset("images/hero-bg.jpg") }}'; // Adjust to your hero image
            document.head.appendChild(preloadLink);

            // Defer non-critical CSS
            const deferCSS = () => {
                const links = document.querySelectorAll('link[data-defer="true"]');
                links.forEach(link => {
                    link.rel = 'stylesheet';
                });
            };

            if (document.readyState === 'complete') {
                deferCSS();
            } else {
                window.addEventListener('load', deferCSS);
            }
        });
    </script>
@endsection
