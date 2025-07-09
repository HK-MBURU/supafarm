@extends('layouts.app')

@section('content')
<div class="product-details-container">
    <!-- Breadcrumbs Navigation -->
    <div class="breadcrumbs">
        <a href="/">Home</a> &gt;
        <a href="{{ route('products.page', $product->category->slug) }}">{{ ucfirst($product->category->name) }}</a> &gt;
        <span>{{ $product->name }}</span>
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

    <!-- Product Details Section -->
    <div class="product-details-wrapper">
        <!-- Product Images Gallery -->
        <div class="product-gallery">
            <div class="main-image">
                @if($product->image_url)
                <img id="mainImage" src="{{ $product->image_url }}" alt="{{ $product->name }}">

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

                        <!-- Zoom icon -->
                        <div class="zoom-icon">
                            <i class="fas fa-search-plus"></i>
                        </div>
                        @else
                        <img src="{{ asset('images/no-product-image.jpg') }}" alt="No image available">
                        @endif
            </div>

            <div class="thumbnail-gallery">
                <!-- Main product image thumbnail -->
                @if($product->image_url)
                <div class="thumbnail active" data-image="{{ $product->image_url }}">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                </div>
                @endif

                <!-- Additional product images would be loaded here -->
                @if(isset($product->additional_images) && is_array($product->additional_images))
                @foreach($product->additional_images as $image)
                <div class="thumbnail" data-image="{{ $image }}">
                    <img src="{{ $image }}" alt="{{ $product->name }} - additional view">
                </div>
                @endforeach
                @endif
            </div>
        </div>

        <!-- Product Information -->
        <div class="product-info">
            <h1 class="product-title">{{ $product->name }}</h1>

            <!-- Product Meta Info -->
            <div class="product-meta">

                <span class="category"><i class="fas fa-tag"></i> Category: <a href="{{ route('products.page', $product->category->slug) }}">{{ $product->category->name }}</a></span>
                @if(isset($product->brand) && $product->brand)
                <span class="brand"><i class="fas fa-industry"></i> Brand: {{ $product->brand }}</span>
                @endif
            </div>

            <!-- Product Price -->
            <div class="product-price">
                @if(($product->sale_price ?? 0) > 0 && ($product->sale_price < $product->price))
                    <div class="price-wrapper">
                        <span class="original-price">KSh {{ number_format($product->price, 2) }}</span>
                        <span class="current-price">KSh {{ number_format($product->sale_price, 2) }}</span>
                        @php
                        $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
                        @endphp
                        <span class="discount-badge">{{ $discount }}% OFF</span>
                    </div>
                    @else
                    <div class="price-wrapper">
                        <span class="current-price">KSh {{ number_format($product->price, 2) }}</span>
                    </div>
                    @endif
            </div>

            <!-- Stock Status -->
            <div class="stock-status-wrapper">
                <span class="stock-status {{ ($product->stock ?? 0) > 0 ? 'in-stock' : 'out-of-stock' }}">
                    <i class="fas {{ ($product->stock ?? 0) > 0 ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                    {{ ($product->stock ?? 0) > 0 ? 'In Stock' : 'Out of Stock' }}
                    @if(($product->stock ?? 0) > 0 && ($product->stock ?? 0) <= 5)
                        <span class="stock-count">(Only {{ $product->stock }} left)</span>
                @endif
                </span>
            </div>

            <!-- Short Description -->
            <div class="short-description">
                <p>{{ $product->short_description ?? Str::limit($product->description, 150) }}</p>
            </div>

            <!-- Add to Cart Form -->
            <form action="/cart/add" method="POST" class="cart-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <!-- Quantity Selector -->
                <div class="quantity-control">
                    <label for="quantity">Quantity:</label>
                    <div class="quantity-selector">
                        <button type="button" class="qty-btn minus"><i class="fas fa-minus"></i></button>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock ?? 10 }}" class="qty-input">
                        <button type="button" class="qty-btn plus"><i class="fas fa-plus"></i></button>
                    </div>
                </div>

                <!-- Add to Cart Button -->
                <div class="action-buttons">
                    <button type="submit" class="btn-add-to-cart" {{ ($product->stock ?? 0) <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>

                    <!-- Wishlist Button (Optional) -->
                    <button type="button" class="btn-wishlist">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
            </form>

            <!-- Additional Information -->
            <div class="additional-info">
                <!-- Shipping Info -->
                <div class="info-item">
                    <i class="fas fa-truck"></i>
                    <div>
                        <h4>Fast Delivery</h4>
                        <p>Delivered within 2-5 business days</p>
                    </div>
                </div>

                <!-- Return Policy -->
                <div class="info-item">
                    <i class="fas fa-undo"></i>
                    <div>
                        <h4>Easy Returns</h4>
                        <p>30-day return policy</p>
                    </div>
                </div>

                <!-- Secure Payment -->
                <div class="info-item">
                    <i class="fas fa-lock"></i>
                    <div>
                        <h4>Secure Payment</h4>
                        <p>Safe & encrypted checkout</p>
                    </div>
                </div>
            </div>

            <!-- Social Sharing -->
            <div class="social-sharing">
                <span>Share:</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="share-link facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?text={{ $product->name }}&url={{ url()->current() }}" target="_blank" class="share-link twitter">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://wa.me/?text={{ urlencode($product->name . ' - ' . url()->current()) }}" target="_blank" class="share-link whatsapp">
                    <i class="fab fa-whatsapp"></i>
                </a>
                <a href="mailto:?subject={{ $product->name }}&body=Check out this product: {{ url()->current() }}" class="share-link email">
                    <i class="fas fa-envelope"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Product Tabs -->
    <div class="product-tabs">
        <div class="tab-buttons">
            <button class="tab-btn active" data-tab="description">Description</button>
            <button class="tab-btn" data-tab="specifications">Specifications</button>
            <button class="tab-btn" data-tab="reviews">Reviews</button>
        </div>

        <div class="tab-content">
            <!-- Description Tab -->
            <div class="tab-pane active" id="description">
                <div class="product-description">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>

            <!-- Specifications Tab -->
            <div class="tab-pane" id="specifications">
                <div class="product-specifications">
                    <table class="specs-table">
                        <tbody>
                            @if(isset($product->specifications) && is_array($product->specifications))
                            @foreach($product->specifications as $key => $value)
                            <tr>
                                <th>{{ $key }}</th>
                                <td>{{ $value }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="2">Specifications not available</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Reviews Tab -->
            <div class="tab-pane" id="reviews">
                <div class="product-reviews">
                    @if(isset($product->reviews) && count($product->reviews) > 0)
                    <!-- Reviews would be displayed here -->
                    <p>Reviews are loading...</p>
                    @else
                    <div class="no-reviews">
                        <i class="far fa-comment-alt"></i>
                        <p>No reviews yet. Be the first to review this product!</p>
                        <button class="btn-primary write-review-btn">Write a Review</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="related-products">
        <h2>You May Also Like</h2>
        <div class="product-list">
            <!-- Sample related products (you would loop through actual related products) -->
            @if(isset($relatedProducts) && count($relatedProducts) > 0)
            @foreach($relatedProducts as $relatedProduct)
            <!-- Include related product cards here -->
            <div class="product-card">
                <!-- Related product content -->
            </div>
            @endforeach
            @else
            <!-- Placeholder for sample related products -->
            @for($i = 0; $i < 4; $i++)
                <div class="product-card">
                <div class="product-image">
                    <img src="{{ asset('images/no-product-image.jpg') }}" alt="Related product">
                </div>
                <div class="product-details">
                    <h2>Related Product Example</h2>
                    <div class="price-container">
                        <p class="price">KSh 1,999.00</p>
                    </div>
                    <div class="product-actions">
                        <a href="#" class="btn-view-details">View Details</a>
                    </div>
                </div>
        </div>
        @endfor
        @endif
    </div>
</div>
</div>

<!-- Image Modal for zoomed view -->
<div class="image-modal" id="imageModal">
    <span class="close-image-modal">&times;</span>
    <img class="modal-image-content" id="modalImage">
</div>
@endsection

@push('styles')
<style>
    /* Product Details Page Styles */
    .product-details-container {
        max-width: 100%;
        margin: 0 auto;
    }

    /* Breadcrumbs */
    .breadcrumbs {
        margin: 15px 0 25px;
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

    /* Product Details Wrapper */
    .product-details-wrapper {
        display: grid;
        grid-template-columns: 1fr;
        gap: 30px;
        margin-bottom: 40px;
    }

    @media (min-width: 768px) {
        .product-details-wrapper {
            grid-template-columns: 1fr 1fr;
        }
    }

    /* Product Gallery */
    .product-gallery {
        position: relative;
    }

    .main-image {
        position: relative;
        background-color: white;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 15px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        /* Set a fixed aspect ratio container */
        padding-top: 75%;
        /* 4:3 aspect ratio (3/4 = 0.75 = 75%) */
        height: 0;
        width: 100%;
    }

    .main-image img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        /* Change from 'contain' to 'cover' to fill the container */
        object-fit: cover;
        display: block;
    }

    .zoom-icon {
        position: absolute;
        bottom: 15px;
        right: 15px;
        width: 40px;
        height: 40px;
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.3s;
        z-index: 5;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .zoom-icon:hover {
        background-color: white;
    }

    .thumbnail-gallery {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 10px;
        /* Hide scrollbar but keep functionality */
        scrollbar-width: none;
        /* Firefox */
        -ms-overflow-style: none;
        /* IE and Edge */
    }

    .thumbnail-gallery::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari, Opera */
    }



    .thumbnail {
        flex: 0 0 70px;
        width: 70px;
        height: 70px;
        border-radius: 4px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: border-color 0.3s;
    }

    .thumbnail.active {
        border-color: var(--primary-color);
    }

    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Product Badges */
    .badge {
        position: absolute;
        padding: 5px 10px;
        font-size: 0.8rem;
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
        bottom: 15px;
        left: 10px;
    }

    /* Product Info */
    .product-info {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .product-title {
        font-size: 1.8rem;
        color: var(--dark-color);
        margin: 0;
        line-height: 1.3;
    }

    .product-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        font-size: 0.9rem;
        color: #666;
    }

    .product-meta span {
        display: flex;
        align-items: center;
    }

    .product-meta i {
        margin-right: 5px;
        color: var(--primary-color);
    }

    .product-meta a {
        color: var(--primary-color);
        text-decoration: none;
    }

    .product-meta a:hover {
        text-decoration: underline;
    }

    /* Product Price */
    .product-price {
        margin: 5px 0 15px;
    }

    .price-wrapper {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .original-price {
        text-decoration: line-through;
        color: #999;
        font-size: 1.1rem;
    }

    .current-price {
        font-size: 1.8rem;
        font-weight: bold;
        color: var(--dark-color);
    }

    .discount-badge {
        background-color: #e53935;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: bold;
    }

    /* Stock Status */
    .stock-status-wrapper {
        margin-bottom: 15px;
    }

    .stock-status {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 0.9rem;
        font-weight: bold;
    }

    .stock-status i {
        margin-right: 5px;
    }

    .in-stock {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .out-of-stock {
        background-color: #ffebee;
        color: #c62828;
    }

    .stock-count {
        font-weight: normal;
        margin-left: 5px;
        font-size: 0.85rem;
    }

    /* Short Description */
    .short-description {
        margin: 15px 0;
        line-height: 1.6;
        color: #555;
    }

    /* Add to Cart Form */
    .cart-form {
        margin: 20px 0;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        gap: 15px;
    }

    .quantity-control label {
        font-weight: bold;
        min-width: 80px;
    }

    /* Quantity Selector */
    .quantity-selector {
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 4px;
        overflow: hidden;
        width: 120px;
    }

    .qty-btn {
        background-color: #f5f5f5;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .qty-btn:hover {
        background-color: #eee;
    }

    .qty-input {
        flex-grow: 1;
        border: none;
        text-align: center;
        padding: 10px 0;
        width: 40px;
        font-size: 1rem;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-add-to-cart {
        flex: 1;
        padding: 12px 20px;
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
        font-size: 1rem;
        transition: background-color 0.3s;
    }

    .btn-add-to-cart:hover {
        background-color: var(--dark-color);
    }

    .btn-add-to-cart:disabled {
        background-color: #ddd;
        cursor: not-allowed;
    }

    .btn-wishlist {
        width: 46px;
        height: 46px;
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-wishlist:hover {
        background-color: #ffe0e0;
        border-color: #ffb0b0;
    }

    .btn-wishlist i {
        font-size: 1.2rem;
        color: #666;
    }

    .btn-wishlist:hover i {
        color: #e53935;
    }

    /* Additional Info */
    .additional-info {
        margin: 25px 0;
        display: flex;
        flex-direction: column;
        gap: 15px;
        border-top: 1px solid #eee;
        padding-top: 20px;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .info-item i {
        font-size: 1.5rem;
        color: var(--primary-color);
        min-width: 25px;
        text-align: center;
    }

    .info-item h4 {
        margin: 0 0 5px;
        color: var(--dark-color);
    }

    .info-item p {
        margin: 0;
        font-size: 0.9rem;
        color: #666;
    }

    /* Social Sharing */
    .social-sharing {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 20px;
    }

    .social-sharing span {
        font-weight: bold;
        color: #555;
    }

    .share-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        color: white;
        transition: transform 0.3s, opacity 0.3s;
    }

    .share-link:hover {
        transform: translateY(-3px);
        opacity: 0.9;
    }

    .share-link.facebook {
        background-color: #3b5998;
    }

    .share-link.twitter {
        background-color: #1da1f2;
    }

    .share-link.whatsapp {
        background-color: #25d366;
    }

    .share-link.email {
        background-color: #666;
    }

    /* Product Tabs */
    .product-tabs {
        margin: 40px 0;
        border: 1px solid #eee;
        border-radius: 8px;
        overflow: hidden;
        background-color: white;
    }

    .tab-buttons {
        display: flex;
        border-bottom: 1px solid #eee;
        background-color: #f9f9f9;
        overflow-x: auto;
    }

    .tab-btn {
        padding: 15px 20px;
        background: none;
        border: none;
        cursor: pointer;
        font-weight: bold;
        color: #666;
        transition: all 0.3s;
        white-space: nowrap;
    }

    .tab-btn:hover {
        color: var(--primary-color);
    }

    .tab-btn.active {
        color: var(--primary-color);
        border-bottom: 2px solid var(--primary-color);
        background-color: white;
    }

    .tab-content {
        padding: 20px;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    /* Product Description */
    .product-description {
        line-height: 1.8;
        color: #444;
    }

    /* Product Specifications */
    .specs-table {
        width: 100%;
        border-collapse: collapse;
    }

    .specs-table tr {
        border-bottom: 1px solid #eee;
    }

    .specs-table tr:last-child {
        border-bottom: none;
    }

    .specs-table th,
    .specs-table td {
        padding: 12px 15px;
        text-align: left;
    }

    .specs-table th {
        width: 30%;
        background-color: #f9f9f9;
        font-weight: 600;
    }

    /* No Reviews */
    .no-reviews {
        text-align: center;
        padding: 30px;
        color: #666;
    }

    .no-reviews i {
        font-size: 3rem;
        color: #ddd;
        margin-bottom: 15px;
    }

    .write-review-btn {
        margin-top: 15px;
        padding: 10px 20px;
    }

    /* Related Products */
    .related-products {
        margin: 40px 0;
    }

    .related-products h2 {
        text-align: center;
        margin-bottom: 25px;
        color: var(--dark-color);
        font-size: 1.5rem;
    }

    /* Image Modal for Zoomed View */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        padding-top: 50px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        overflow: auto;
    }

    .modal-image-content {
        margin: auto;
        display: block;
        max-width: 90%;
        max-height: 90vh;
        object-fit: contain;
    }

    .close-image-modal {
        position: absolute;
        top: 15px;
        right: 25px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        cursor: pointer;
    }

    .close-image-modal:hover {
        color: #bbb;
    }

    /* Animation for modal */
    .modal-image-content {
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    @keyframes zoom {
        from {
            transform: scale(0)
        }

        to {
            transform: scale(1)
        }
    }

    /* Responsive adjustments */
    @media (max-width: 767px) {
        .product-title {
            font-size: 1.5rem;
        }

        .current-price {
            font-size: 1.5rem;
        }

        .tab-btn {
            padding: 12px 15px;
            font-size: 0.9rem;
        }

        .specs-table th {
            width: 40%;
        }
    }

    @media (max-width: 480px) {
        .action-buttons {
            flex-direction: column;
        }

        .btn-wishlist {
            width: 100%;
            height: 42px;
        }

        .quantity-control {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .tab-buttons {
            flex-wrap: nowrap;
        }
    }

    /* Notification Styles */
    .notification-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        max-width: 350px;
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .notification {
        display: flex;
        align-items: center;
        padding: 15px;
        border-radius: 8px;
        background-color: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        animation: slideIn 0.3s ease;
        gap: 12px;
    }

    .notification.success {
        border-left: 4px solid #4CAF50;
    }

    .notification.error {
        border-left: 4px solid #F44336;
    }

    .notification i:first-child {
        font-size: 24px;
        min-width: 24px;
    }

    .notification.success i:first-child {
        color: #4CAF50;
    }

    .notification.error i:first-child {
        color: #F44336;
    }

    .notification div {
        flex: 1;
    }

    .notification p {
        margin: 0;
        font-size: 14px;
        line-height: 1.4;
    }

    .notification .product-name,
    .notification .details {
        font-size: 12px;
        color: #666;
        margin-top: 3px;
    }

    .close-notification {
        background: none;
        border: none;
        color: #999;
        cursor: pointer;
        font-size: 16px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 24px;
        width: 24px;
    }

    .close-notification:hover {
        color: #666;
    }

    .notification.fadeOut {
        animation: fadeOut 0.5s ease forwards;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }

        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    @media (max-width: 576px) {
        .notification-container {
            max-width: calc(100% - 40px);
            top: 10px;
            right: 10px;
        }
    }
</style>
@endpush

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Alert messages timeout
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

        // Quantity selector
        const minusBtn = document.querySelector('.qty-btn.minus');
        const plusBtn = document.querySelector('.qty-btn.plus');
        const qtyInput = document.querySelector('.qty-input');

        if (minusBtn && plusBtn && qtyInput) {
            minusBtn.addEventListener('click', function() {
                let value = parseInt(qtyInput.value);
                if (value > parseInt(qtyInput.min)) {
                    qtyInput.value = value - 1;
                }
            });

            plusBtn.addEventListener('click', function() {
                let value = parseInt(qtyInput.value);
                if (value < parseInt(qtyInput.max)) {
                    qtyInput.value = value + 1;
                }
            });
        }

        // Product tabs
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons and panes
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));

                // Add active class to current button and corresponding pane
                this.classList.add('active');
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // Image gallery
        const thumbnails = document.querySelectorAll('.thumbnail');
        const mainImage = document.getElementById('mainImage');

        if (thumbnails.length && mainImage) {
            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', function() {
                    // Update main image
                    mainImage.src = this.getAttribute('data-image');

                    // Update active thumbnail
                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        }

        // Image zoom modal
        const zoomIcon = document.querySelector('.zoom-icon');
        const imageModal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const closeModal = document.querySelector('.close-image-modal');

        if (zoomIcon && imageModal && modalImage && closeModal) {
            zoomIcon.addEventListener('click', function() {
                modalImage.src = mainImage.src;
                imageModal.style.display = 'block';
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            });

            closeModal.addEventListener('click', function() {
                imageModal.style.display = 'none';
                document.body.style.overflow = ''; // Restore scrolling
            });

            // Close modal when clicking outside the image
            imageModal.addEventListener('click', function(e) {
                if (e.target === imageModal) {
                    imageModal.style.display = 'none';
                    document.body.style.overflow = '';
                }
            });
        }

        // Wishlist button toggle
        const wishlistBtn = document.querySelector('.btn-wishlist');
        if (wishlistBtn) {
            wishlistBtn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    // Here you would typically send an AJAX request to add to wishlist
                    alert('Product added to wishlist!');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    // Here you would typically send an AJAX request to remove from wishlist
                    alert('Product removed from wishlist!');
                }
            });
        }

        // Write review button
        const writeReviewBtn = document.querySelector('.write-review-btn');
        if (writeReviewBtn) {
            writeReviewBtn.addEventListener('click', function() {
                alert('Review form would open here');
                // Here you would typically show a review form modal
            });
        }
    });
</script>
@endpush