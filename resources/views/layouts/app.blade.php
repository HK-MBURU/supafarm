<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supa Farm Supplies</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    
    <!-- This line will include any styles pushed from child views -->
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
                <h1>Supa Farm Supplies</h1>
            </div>
            
            <!-- Desktop Search Bar (only visible on desktop) -->
            <div class="desktop-search">
                <form action="/search" method="GET">
                    <input type="text" name="query" placeholder="Search for products...">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
            
            <!-- Desktop Cart Icon (only visible on desktop) -->
            <div class="desktop-cart">
                <a href="/cart">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">0</span>
                </a>
            </div>
            
            <!-- Mobile Search Icon (hidden on desktop) -->
            <div class="search-icon">
                <i class="fas fa-search"></i>
            </div>
        </div>
        
        <!-- Mobile Search Container (hidden on desktop) -->
        <div class="search-container" id="searchContainer">
            <form action="/search" method="GET">
                <input type="text" name="query" placeholder="Search for products...">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        
        <!-- Navigation (visible on desktop) -->
        <nav class="desktop-nav">
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/products/honey">Honey</a></li>
                <li><a href="/products/eggs">Eggs</a></li>
                <li><a href="/products/coffee">Coffee</a></li>
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
            <li><a href="/products/honey">Honey</a></li>
            <li><a href="/products/eggs">Eggs</a></li>
            <li><a href="/products/coffee">Coffee</a></li>
            <li><a href="/about">About Us</a></li>
            <li><a href="/contact">Contact</a></li>
        </ul>
    </div>
    
    <main>
        @yield('content')
    </main>
    
    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav">
        <a href="/" class="nav-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="/products" class="nav-item">
            <i class="fas fa-store"></i>
            <span>Products</span>
        </a>
        <a href="/cart" class="nav-item">
            <i class="fas fa-shopping-cart"></i>
            <span>Cart</span>
        </a>
    </nav>
    
    <footer>
        <div class="footer-content">
            <p>&copy; {{ date('Y') }} Supa Farm Supplies. All rights reserved.</p>
            
        </div>
    </footer>
    
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
    
    <!-- This line will include any scripts pushed from child views -->
    @stack('scripts')
</body>
</html>