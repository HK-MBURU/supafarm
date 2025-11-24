<!-- Modern Flat Footer -->
<footer class="modern-footer" itemscope itemtype="https://schema.org/LocalBusiness">
    <div class="footer-main">
        <div class="container">
            <div class="row g-4">
                <!-- Company Info -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-section">
                        <h3 class="footer-title">Supa Farm Supplies</h3>
                        <p class="footer-desc">Kenya's premier supplier of fresh farm eggs, pure honey, and premium coffee. Quality from farm to table.</p>
                        <div class="trust-badges">
                            <span class="badge-item">🥚 Fresh Daily</span>
                            <span class="badge-item">🍯 100% Pure</span>
                            <span class="badge-item">☕ Premium Quality</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6">
                    <div class="footer-section">
                        <h4 class="footer-heading">Quick Links</h4>
                        <ul class="footer-links">
                            <li><a href="/">Home</a></li>
                            <li><a href="/products">Products</a></li>
                            <li><a href="/about">About Us</a></li>
                            <li><a href="/contact">Contact</a></li>
                            <li><a href="/cart">Cart</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Products -->
                <div class="col-lg-2 col-md-6">
                    <div class="footer-section">
                        <h4 class="footer-heading">Our Products</h4>
                        <ul class="footer-links">
                            @foreach (App\Models\Category::where('is_active', true)->take(5)->get() as $footerCategory)
                                <li><a href="{{ route('products.page', $footerCategory->slug) }}">{{ $footerCategory->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-section">
                        <h4 class="footer-heading">Contact Us</h4>
                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                                    <span itemprop="streetAddress">Commercial Street</span>,
                                    <span itemprop="addressLocality">Thika</span>
                                </span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <a href="tel:+254726619243" itemprop="telephone">+254 726 619243</a>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:info@supafarmsupplies.com">info@supafarmsupplies.com</a>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-clock"></i>
                                <span>Mon - Sun: 8AM - 6PM</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-content">
                <p class="copyright">&copy; {{ date('Y') }} Supa Farm Supplies. All rights reserved.</p>
                <div class="social-links">
                    <a href="https://facebook.com/supafarmsupplies" aria-label="Facebook" target="_blank" rel="noopener">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://instagram.com/supafarmsupplies" aria-label="Instagram" target="_blank" rel="noopener">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://twitter.com/supafarmsupplies" aria-label="Twitter" target="_blank" rel="noopener">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://wa.me/254726619243" aria-label="WhatsApp" target="_blank" rel="noopener">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
