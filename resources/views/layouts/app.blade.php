<!DOCTYPE html>
<html lang="en" itemscope itemtype="https://schema.org/Store">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Primary Meta Tags -->
    <title>Supa Farm Supplies - Premium Eggs, Honey & Coffee in Kenya</title>
    <meta name="title" content="Supa Farm Supplies - Premium Eggs, Honey & Coffee in Kenya">
    <meta name="description" content="Kenya's leading supplier of fresh farm eggs, pure honey, and premium coffee. Located in Thika with nationwide & international delivery. Quality guaranteed.">
    <meta name="keywords" content="fresh eggs kenya, pure honey kenya, premium coffee kenya, farm supplies thika, buy eggs online kenya, kenyan honey, arabica coffee kenya">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://supafarmsupplies.com/">
    <meta property="og:title" content="Supa Farm Supplies - Premium Eggs, Honey & Coffee in Kenya">
    <meta property="og:description" content="Kenya's leading supplier of fresh farm eggs, pure honey, and premium coffee. Quality products from Thika to your doorstep.">
    <meta property="og:image" content="https://supafarmsupplies.com/images/og-image.jpg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="Supa Farm Supplies">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://supafarmsupplies.com/">
    <meta property="twitter:title" content="Supa Farm Supplies - Premium Eggs, Honey & Coffee in Kenya">
    <meta property="twitter:description" content="Kenya's leading supplier of fresh farm eggs, pure honey, and premium coffee.">
    <meta property="twitter:image" content="https://supafarmsupplies.com/images/og-image.jpg">

    <!-- Schema.org markup -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Store",
        "name": "Supa Farm Supplies",
        "description": "Kenya's leading supplier of fresh farm eggs, pure honey, and premium coffee",
        "url": "https://supafarmsupplies.com",
        "telephone": "+254726619243",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Kihanya Building, Commercial Street",
            "addressLocality": "Thika",
            "addressCountry": "KE"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": "-1.0391",
            "longitude": "37.0844"
        },
        "openingHours": "Mo-Su 08:00-18:00",
        "priceRange": "$$",
        "areaServed": ["Kenya", "International"],
        "sameAs": [
            "https://facebook.com/supafarmsupplies",
            "https://instagram.com/supafarmsupplies",
            "https://twitter.com/supafarmsupplies"
        ]
    }
    </script>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#bc450d">
    <meta name="msapplication-TileColor" content="#bc450d">
    <meta name="theme-color" content="#bc450d">

    <!-- Canonical URL -->
    <link rel="canonical" href="https://supafarmsupplies.com" />

    <!-- Robots -->
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Lightbox for Gallery -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alert.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">

    <!-- Push styles from child views -->
    @stack('styles')
</head>

<body>
    <header class="main-header">
        <div class="header-container">
            <!-- Mobile Menu Icon (hidden on desktop) -->
            <div class="menu-icon" id="menuToggle">
                <i class="fas fa-bars"></i>
            </div>

            <!-- Logo (visible on both mobile and desktop) -->
            <div class="logo">
                <h1 itemprop="name">Supa Farm Supplies</h1>
            </div>

            <!-- Desktop Search Bar (only visible on desktop) -->
            <div class="desktop-search ">
                <form action="/search" method="GET" role="search">
                    <input type="text" name="query" placeholder="Search for products..." aria-label="Search products">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <!-- Desktop Cart Icon (only visible on desktop) -->
            <div class="desktop-cart">
                <a href="{{ route('cart.index') }}" aria-label="Shopping cart">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">{{ $cartCount ?? 0 }}</span>
                </a>
            </div>

            <!-- Mobile Search Icon (hidden on desktop) -->
            <div class="search-icon">
                <i class="fas fa-search"></i>
            </div>
        </div>

        <!-- Mobile Search Container (hidden on desktop) -->
        <div class="search-container" id="searchContainer">
            <form action="/search" method="GET" role="search">
                <input type="text" name="query" placeholder="Search for products..." aria-label="Search products">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <!-- Navigation (visible on desktop) -->
        <nav class="desktop-nav" aria-label="Main navigation">
            <ul>
                <li><a href="/">Home</a></li>
                @foreach(App\Models\Category::where('is_active', true)->take(4)->get() as $navCategory)
                <li><a href="{{ route('products.page', $navCategory->slug) }}">{{ $navCategory->name }}</a></li>
                @endforeach
                <li><a href="/about">About Us</a></li>
                <li><a href="/contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <!-- Mobile Menu (Slide-in from left) -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="close-menu" id="closeMenu">
            <i class="fas fa-times"></i>
        </div>
        <ul>
            <li><a href="/">Home</a></li>
            @foreach(App\Models\Category::where('is_active', true)->take(4)->get() as $navCategory)
            <li><a href="{{ route('products.page', $navCategory->slug) }}">{{ $navCategory->name }}</a></li>
            @endforeach
            <li><a href="/about">About Us</a></li>
            <li><a href="/contact">Contact</a></li>
        </ul>
    </div>

    <main>
        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav" aria-label="Mobile navigation">
        <a href="/" class="nav-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="/products" class="nav-item">
            <i class="fas fa-store"></i>
            <span>Products</span>
        </a>
        <a href="{{ route('cart.index') }}" class="nav-item">
            <i class="fas fa-shopping-cart"></i>
            <span>Cart (<span class="mobile-cart-count">{{ $cartCount ?? 0 }}</span>)</span>
        </a>
    </nav>

    @include('partials.seo')
    @include('partials.footer')

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Lightbox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/unified-cart.js') }}"></script>
    <script src="{{ asset('js/cart-helper.js') }}"></script>

    <script>
        // Menu toggle
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.add('active');
        });

        document.getElementById('closeMenu').addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.remove('active');
        });

        // Search toggle (mobile only)
        document.querySelector('.search-icon').addEventListener('click', function() {
            const searchContainer = document.getElementById('searchContainer');
            searchContainer.classList.toggle('active');
        });
    </script>

    <!-- Scripts from child views -->
    @stack('scripts')
</body>

</html>
