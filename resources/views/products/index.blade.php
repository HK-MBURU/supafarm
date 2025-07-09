@extends('layouts.app')

@section('content')
<div class="category-container">
    <!-- Category Header with Breadcrumbs -->
    <div class="breadcrumbs">
        <a href="/">Home</a> &gt; <span>{{ ucfirst($category->name) }}</span>
    </div>

    @if(session('success'))
    <div class="alert alert-success" id="successAlert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error" id="errorAlert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif
    <!-- Product Grid -->
    <div class="product-list">
        @forelse($products as $product)
        <div class="product-card">
            <div class="product-image">
                @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                @else
                <img src="{{ asset('images/no-product-image.jpg') }}" alt="No image available" loading="lazy">
                @endif

                <!-- Product Badges -->
                @if($product->is_featured ?? false)
                <span class="badge featured">Featured</span>
                @endif

                @if(($product->sale_price ?? 0) > 0 && ($product->sale_price < $product->price))
                    <span class="badge sale">Sale</span>
                    @endif

                    @if(($product->stock ?? 0) <= 5 && ($product->stock ?? 0) > 0)
                        <span class="badge low-stock">Low Stock</span>
                        @endif

                        <!-- Quick View Button -->
                        <button class="quick-view-btn" data-product="{{ $product->id }}">
                            <i class="fas fa-eye"></i>
                        </button>
            </div>

            <div class="product-details">
                <h2>{{ $product->name }}</h2>

                <div class="price-container">
                    @if(($product->sale_price ?? 0) > 0 && ($product->sale_price < $product->price))
                        <p class="price">
                            <span class="original">KSh {{ number_format($product->price, 2) }}</span>
                            <span class="current">KSh {{ number_format($product->sale_price, 2) }}</span>
                        </p>
                        @else
                        <p class="price">KSh {{ number_format($product->price, 2) }}</p>
                        @endif

                        <!-- Stock Status -->
                        <span class="stock-status {{ ($product->stock ?? 0) > 0 ? 'in-stock' : 'out-of-stock' }}">
                            {{ ($product->stock ?? 0) > 0 ? 'In Stock' : 'Out of Stock' }}
                        </span>
                </div>

                <div class="product-description">
                    {{ Str::limit($product->description, 100) }}
                </div>
                <!-- Notification container for AJAX cart responses -->
                <div id="notification-container" class="notification-container"></div>

                <div class="product-actions">
                    <form action="/cart/add" method="POST" class="cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="quantity-selector">
                            <button type="button" class="qty-btn minus"><i class="fas fa-minus"></i></button>
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock ?? 10 }}" class="qty-input">
                            <button type="button" class="qty-btn plus"><i class="fas fa-plus"></i></button>
                        </div>

                        <button type="submit" class="btn-add-to-cart" {{ ($product->stock ?? 0) <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    </form>

                    <a href="{{ route('products.view', $product->id) }}" class="btn-view-details">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="no-products">
            <i class="fas fa-box-open"></i>
            <p>No products found in this category.</p>
            <a href="/" class="btn-primary">Continue Shopping</a>
        </div>
        @endforelse
    </div>

    <!-- Pagination if needed -->
    @if(isset($products) && method_exists($products, 'links') && $products->hasPages())
    <div class="pagination-container">
        {{ $products->links() }}
    </div>
    @endif

    <!-- Related Categories -->
    <div class="related-categories">
        <h3>Related Categories</h3>
        <div class="category-tags">
            <a href="#" class="category-tag">Organic Products</a>
            <a href="#" class="category-tag">Fresh Produce</a>
            <a href="#" class="category-tag">Local Farms</a>
            <a href="#" class="category-tag">Seasonal Items</a>
        </div>
    </div>
</div>

<!-- Quick View Modal (hidden by default) -->
<div class="quick-view-modal" id="quickViewModal">
    <div class="modal-content">
        <button class="close-modal" id="closeModal"><i class="fas fa-times"></i></button>
        <div class="modal-body" id="modalContent">
            <!-- Content will be loaded dynamically -->
            <div class="loader">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading product details...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Category Page Styles */
    .category-container {
        max-width: 100%;
        margin: 0 auto;

    }

    /* Breadcrumbs */
    .breadcrumbs {
        margin: 15px 0;
        font-size: 0.9rem;
        color: #666;
    }

    .breadcrumbs a {
        color: var(--primary-color);
        text-decoration: none;
    }

    .breadcrumbs a:hover {
        text-decoration: underline;
    }

    /* Category Header */
    .category-header {
        margin-bottom: 25px;
        text-align: center;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .category-header h1 {
        color: var(--primary-color);
        margin-bottom: 10px;
        font-size: 1.8rem;
    }

    .category-header p {
        color: #666;
        margin-bottom: 15px;
        font-size: 1rem;
    }

    .meta-info {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 15px;
        font-size: 0.8rem;
        color: #777;
    }

    .meta-info span {
        display: flex;
        align-items: center;
    }

    .meta-info i {
        margin-right: 5px;
        color: var(--primary-color);
    }

    /* Filters and Sorting */
    .product-filters {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .filter-toggle {
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 8px 15px;
        font-size: 0.9rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .filter-toggle:hover {
        background-color: #eee;
    }

    .sort-options select {
        padding: 8px 30px 8px 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 0.9rem;
        background-color: white;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='6' fill='%23666'%3E%3Cpath d='M4 6L0 0h8z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
    }

    /* Filter Panel */
    .filter-panel {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }

    .filter-panel.active {
        max-height: 500px;
    }

    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
    }

    .filter-header h3 {
        margin: 0;
        font-size: 1.1rem;
        color: var(--dark-color);
    }

    .close-filter {
        background: none;
        border: none;
        font-size: 1.2rem;
        color: #666;
        cursor: pointer;
    }

    .filter-options {
        padding: 15px 20px;
    }

    .filter-group {
        margin-bottom: 20px;
    }

    .filter-group h4 {
        margin: 0 0 10px 0;
        font-size: 0.95rem;
        color: #333;
    }

    .price-range {
        margin-top: 10px;
    }

    .price-range input {
        width: 100%;
        margin-bottom: 10px;
    }

    .range-labels {
        display: flex;
        justify-content: space-between;
        font-size: 0.8rem;
        color: #666;
    }

    .checkbox-container {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 0.9rem;
        user-select: none;
    }

    .checkbox-container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 20px;
        width: 20px;
        background-color: #eee;
        border-radius: 4px;
    }

    .checkbox-container:hover input~.checkmark {
        background-color: #ddd;
    }

    .checkbox-container input:checked~.checkmark {
        background-color: var(--primary-color);
    }

    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    .checkbox-container input:checked~.checkmark:after {
        display: block;
    }

    .checkbox-container .checkmark:after {
        left: 7px;
        top: 3px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    .apply-filters {
        width: 100%;
        padding: 10px;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
    }

    .apply-filters:hover {
        background-color: var(--dark-color);
    }

    /* Product Grid */
    .product-list {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    @media (min-width: 576px) {
        .product-list {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 768px) {
        .product-list {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (min-width: 1200px) {
        .product-list {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    /* Product Card */
    .product-card {
        background-color: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {

        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    }

    /* Product Image */
    .product-image {
        position: relative;
        padding-top: 75%;
        /* 4:3 Aspect Ratio */
        overflow: hidden;
    }

    .product-image img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }



    /* Product Badges */
    .badge {
        position: absolute;
        padding: 4px 8px;
        font-size: 0.7rem;
        font-weight: bold;
        border-radius: 4px;
        z-index: 2;
    }

    .badge.featured {
        top: 10px;
        left: 10px;
        background-color: var(--primary-color);
        color: white;
    }

    .badge.sale {
        top: 10px;
        right: 10px;
        background-color: #e53935;
        color: white;
    }

    .badge.low-stock {
        bottom: 10px;
        left: 10px;
        background-color: #ff9800;
        color: white;
    }

    /* Quick View Button */
    .quick-view-btn {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background-color: rgba(255, 255, 255, 0.8);
        border: none;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        transform: translateY(10px);
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .product-card:hover .quick-view-btn {
        opacity: 1;
        transform: translateY(0);
    }

    .quick-view-btn:hover {
        background-color: white;
    }

    /* Product Details */
    .product-details {
        padding: 15px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .product-details h2 {
        font-size: 1rem;
        margin-bottom: 10px;
        color: var(--dark-color);
        line-height: 1.3;
        min-height: 2.6rem;
        /* Consistent height for titles */
    }

    /* Price Container */
    .price-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .price {
        font-weight: bold;
        font-size: 1rem;
        color: var(--dark-color);
        margin: 0;
    }

    .price .original {
        text-decoration: line-through;
        color: #999;
        font-size: 0.9rem;
        margin-right: 5px;
    }

    .price .current {
        color: #e53935;
    }

    .stock-status {
        font-size: 0.8rem;
        padding: 2px 6px;
        border-radius: 3px;
    }

    .in-stock {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .out-of-stock {
        background-color: #ffebee;
        color: #c62828;
    }

    /* Product Description */
    .product-description {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 15px;
        line-height: 1.4;
        flex-grow: 1;
        /* Push buttons to bottom */
    }

    /* Product Actions */
    .product-actions {
        margin-top: auto;
        /* Push to bottom of card */
    }

    .cart-form {
        margin-bottom: 10px;
    }

    /* Quantity Selector */
    .quantity-selector {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        overflow: hidden;
    }

    .qty-btn {
        background-color: #f5f5f5;
        border: none;
        padding: 8px 12px;
        cursor: pointer;
    }

    .qty-btn:hover {
        background-color: #eee;
    }

    .qty-input {
        flex-grow: 1;
        border: none;
        text-align: center;
        padding: 8px 0;
        width: 40px;
    }

    /* Add to Cart Button */
    .btn-add-to-cart {
        width: 100%;
        padding: 10px;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .btn-add-to-cart:hover {
        background-color: var(--dark-color);
    }

    .btn-add-to-cart:disabled {
        background-color: #ddd;
        cursor: not-allowed;
    }

    .btn-add-to-cart i {
        font-size: 1rem;
    }

    /* View Details Link */
    .btn-view-details {
        display: block;
        text-align: center;
        padding: 8px;
        margin-top: 10px;
        background-color: transparent;
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.3s;
    }

    .btn-view-details:hover {
        background-color: var(--primary-color);
        color: white;
    }

    /* Empty State */
    .no-products {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
    }

    .no-products i {
        font-size: 3rem;
        color: #ccc;
        margin-bottom: 15px;
    }

    .no-products p {
        margin-bottom: 20px;
        color: #666;
    }

    /* Pagination */
    .pagination-container {
        margin: 30px 0;
        display: flex;
        justify-content: center;
    }

    /* Related Categories */
    .related-categories {
        margin: 30px 0;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .related-categories h3 {
        font-size: 1.2rem;
        margin-bottom: 15px;
        color: var(--dark-color);
    }

    .category-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .category-tag {
        background-color: #f5f5f5;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.9rem;
        color: var(--dark-color);
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .category-tag:hover {
        background-color: var(--primary-color);
        color: white;
    }

    /* Quick View Modal */
    .quick-view-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        overflow-y: auto;
    }

    .modal-content {
        background-color: white;
        border-radius: 8px;
        margin: 20px auto;
        max-width: 90%;
        position: relative;
        animation: modalFadeIn 0.3s;
    }

    @media (min-width: 768px) {
        .modal-content {
            max-width: 700px;
        }
    }

    .close-modal {
        position: absolute;
        top: 10px;
        right: 10px;
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #666;
        cursor: pointer;
        z-index: 10;
    }

    .modal-body {
        padding: 20px;
        min-height: 200px;
    }

    .loader {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px;
    }

    .loader i {
        font-size: 2rem;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive Adjustments */
    @media (max-width: 767px) {
        .product-card {
            max-width: 100%;
        }

        .category-header h1 {
            font-size: 1.5rem;
        }

        .product-details h2 {
            min-height: auto;
        }
    }

    /* Additional Mobile Optimizations */
    @media (max-width: 480px) {
        .product-filters {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-toggle,
        .sort-options select {
            width: 100%;
        }

        .price-container {
            flex-direction: column;
            align-items: flex-start;
        }

        .stock-status {
            margin-top: 5px;
        }
    }

    /* Touch-specific adjustments */
    @media (hover: none) {
        .quick-view-btn {
            opacity: 1;
            transform: translateY(0);
            background-color: white;
        }

        .product-card:hover {
            transform: none;
        }

        .product-card:hover .product-image img {
            transform: none;
        }

        .product-card:active {
            transform: scale(0.98);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    const successAlert = document.getElementById('successAlert');
    const errorAlert = document.getElementById('errorAlert');

    if (successAlert) {
        setTimeout(function() {
            successAlert.classList.add('fadeOut');
            setTimeout(function() {
                successAlert.style.display = 'none';
            }, 500);
        }, 5000);
    }

    if (errorAlert) {
        setTimeout(function() {
            errorAlert.classList.add('fadeOut');
            setTimeout(function() {
                errorAlert.style.display = 'none';
            }, 500);
        }, 5000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Filter panel toggle
        const filterToggle = document.getElementById('filterToggle');
        const filterPanel = document.getElementById('filterPanel');
        const closeFilter = document.getElementById('closeFilter');

        if (filterToggle && filterPanel && closeFilter) {
            filterToggle.addEventListener('click', function() {
                filterPanel.classList.toggle('active');
            });

            closeFilter.addEventListener('click', function() {
                filterPanel.classList.remove('active');
            });
        }

        // Price range slider
        const priceRange = document.getElementById('priceRange');
        const priceValue = document.getElementById('priceValue');

        if (priceRange && priceValue) {
            priceRange.addEventListener('input', function() {
                priceValue.textContent = 'KSh ' + Number(this.value).toLocaleString();
            });
        }

        // Quantity buttons
        const minusButtons = document.querySelectorAll('.qty-btn.minus');
        const plusButtons = document.querySelectorAll('.qty-btn.plus');

        minusButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.nextElementSibling;
                let value = parseInt(input.value);
                if (value > parseInt(input.min)) {
                    input.value = value - 1;
                }
            });
        });

        plusButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                let value = parseInt(input.value);
                if (value < parseInt(input.max)) {
                    input.value = value + 1;
                }
            });
        });

        // Quick view modal
        const quickViewBtns = document.querySelectorAll('.quick-view-btn');
        const quickViewModal = document.getElementById('quickViewModal');
        const closeModal = document.getElementById('closeModal');
        const modalContent = document.getElementById('modalContent');

        if (quickViewBtns.length && quickViewModal && closeModal) {
            quickViewBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productId = this.getAttribute('data-product');

                    // Here you would typically fetch product details via AJAX
                    // For now, we'll just show the modal with loader
                    quickViewModal.style.display = 'block';
                    document.body.style.overflow = 'hidden'; // Prevent scrolling

                    // Simulate loading data (replace with actual AJAX call)
                    setTimeout(() => {
                        // This is where you'd normally inject AJAX response
                        modalContent.innerHTML = `
                        <div class="quick-view-product">
                            <h2>${this.closest('.product-card').querySelector('h2').textContent}</h2>
                            <p>Product details would be loaded here via AJAX</p>
                        </div>
                    `;
                    }, 1000);
                });
            });

            closeModal.addEventListener('click', function() {
                quickViewModal.style.display = 'none';
                document.body.style.overflow = ''; // Restore scrolling
            });

            // Close modal when clicking outside content
            quickViewModal.addEventListener('click', function(e) {
                if (e.target === quickViewModal) {
                    quickViewModal.style.display = 'none';
                    document.body.style.overflow = ''; // Restore scrolling
                }
            });
        }

        // Handle card touch interactions for mobile
        const productCards = document.querySelectorAll('.product-card');

        if ('ontouchstart' in window) {
            productCards.forEach(card => {
                card.addEventListener('touchstart', function() {
                    this.classList.add('touch-active');
                });

                card.addEventListener('touchend', function() {
                    this.classList.remove('touch-active');
                });
            });
        }
    });
</script>
@endpush