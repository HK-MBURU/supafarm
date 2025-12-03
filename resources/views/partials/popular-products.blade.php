@php
    // Get popular products - adjust this query as needed
    $popularProducts = App\Models\Product::active()->featured()->inStock()->take(6)->get();
@endphp

@if ($popularProducts->count() > 0)
    <section class="popular-products">
        <div class="section-header">
            <h2 class="section-title">Popular Products</h2>

        </div>

        <div class="products-grid">
            @foreach ($popularProducts as $product)
                <div class="product-card">
                    @if ($product->has_discount)
                        <div class="product-badge">-{{ $product->discount_percentage }}%</div>
                    @endif

                    <div class="product-image">
                        @if ($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                        @else
                            <div class="no-image">No Image</div>
                        @endif
                    </div>

                    <div class="product-content">
                        <h3 class="product-name">{{ Str::limit($product->name, 40) }}</h3>

                        <div class="product-price">
                            @if ($product->has_discount)
                                <span class="current-price">{{ $product->formatted_sale_price }}</span>
                                <span class="original-price">{{ $product->formatted_price }}</span>
                            @else
                                <span class="current-price">{{ $product->formatted_price }}</span>
                            @endif
                        </div>

                        <div class="product-actions">
                            <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form" data-product-id="{{ $product->id }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn-cart">
                                    <i class="fas fa-cart-plus"></i> Add
                                </button>
                            </form>

                            <a href="{{ route('products.view', $product->id) }}" class="btn-details">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- View All Products Button -->
        <div class="view-all-container">
            <a href="{{ route('products.index') }}" class="btn-view-all">
                <span>View All Products</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </section>
@endif

<style>
    /* Popular Products Section - Modern Flat Design */
    .popular-products {
        padding: 30px 20px;
        background-color: #ffffff;
        margin: 30px 0;
    }

    .section-header {
        text-align: center;
        margin-bottom: 10px;
    }

    .section-title {
        color: var(--dark-color);
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .section-subtitle {
        color: #666;
        font-size: 1rem;
    }

    /* Products Grid - Tighter spacing */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        max-width: 1200px;
        margin: 0 auto 30px auto;
        /* Added bottom margin for spacing */
    }

    /* Product Card - Flatter design */
    .product-card {
        background: #ffffff;
        border: 1px solid #e8e8e8;
        border-radius: 4px;
        /* Reduced border radius */
        overflow: hidden;
        position: relative;
        transition: none;
        /* Remove any transitions */
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .product-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        background-color: var(--primary-color);
        color: white;
        padding: 3px 8px;
        border-radius: 10px;
        /* Smaller radius */
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 2;
        line-height: 1;
    }

    .product-image {
        height: 140px;
        /* Reduced height */
        background-color: #fafafa;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid #e8e8e8;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-image {
        color: #aaa;
        font-size: 0.85rem;
    }

    .product-content {
        padding: 15px;
        /* Reduced padding */
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .product-name {
        color: var(--dark-color);
        font-size: 0.95rem;
        /* Smaller font */
        font-weight: 600;
        margin-bottom: 8px;
        /* Reduced margin */
        line-height: 1.3;
        flex-grow: 1;
    }

    .product-price {
        margin-bottom: 15px;
        /* Reduced margin */
    }

    .current-price {
        color: var(--primary-color);
        font-size: 1.1rem;
        /* Smaller font */
        font-weight: 700;
        display: block;
    }

    .original-price {
        color: #999;
        font-size: 0.9rem;
        /* Smaller font */
        text-decoration: line-through;
        display: block;
    }

    /* Product Actions - Stacked buttons */
    .product-actions {
        display: flex;
        flex-direction: column;
        gap: 8px;
        /* Smaller gap */
        margin-top: auto;
    }

    .btn-cart,
    .btn-details {
        width: 100%;
        padding: 8px 12px;
        /* Reduced padding */
        border: none;
        border-radius: 3px;
        /* Smaller radius */
        font-size: 0.85rem;
        /* Smaller font */
        font-weight: 500;
        /* Slightly lighter weight */
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        /* Smaller gap */
        text-decoration: none;
        text-align: center;
        line-height: 1.2;
        height: 36px;
        /* Fixed height */
    }

    .btn-cart {
        background-color: var(--primary-color);
        color: white;
        border: 1px solid var(--primary-color);
    }

    .btn-details {
        background-color: white;
        color: var(--accent-color);
        border: 1px solid var(--accent-color);
    }

    .btn-cart:hover {
        background-color: var(--dark-color);
        border-color: var(--dark-color);
    }

    .btn-details:hover {
        background-color: var(--accent-color);
        color: white;
    }

    .btn-cart i,
    .btn-details i {
        font-size: 0.85rem;
        /* Smaller icons */
    }

    /* Ensure forms don't have extra margins */
    .add-to-cart-form {
        margin: 0;
        width: 100%;
        display: block;
    }

    /* View All Products Button Container */
    .view-all-container {
        text-align: center;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
    }

    /* View All Button - Modern Flat Design */
    .btn-view-all {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background-color: transparent;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
        border-radius: 4px;
        padding: 10px 24px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: none;
        /* No animations */
        letter-spacing: 0.3px;
        line-height: 1.2;
        height: 42px;
        /* Fixed height */
        min-width: 160px;
        /* Minimum width for better proportions */
    }

    .btn-view-all span {
        flex-grow: 1;
        text-align: center;
    }

    .btn-view-all i {
        font-size: 0.85rem;
        transition: none;
        /* No animations */
    }

    .btn-view-all:hover {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .btn-view-all:hover i {
        transform: translateX(3px);
    }

    /* Desktop Styles */
    @media (min-width: 768px) {
        .popular-products {
            padding: 40px 20px;
            margin: 40px 0;
        }

        .section-title {
            font-size: 2rem;
        }

        .section-subtitle {
            font-size: 1.1rem;
        }

        .products-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            /* Slightly larger gap on tablet */
            margin-bottom: 40px;
        }

        .product-image {
            height: 160px;
        }

        .product-name {
            font-size: 1rem;
        }

        .current-price {
            font-size: 1.2rem;
        }

        .btn-cart,
        .btn-details {
            padding: 9px 15px;
            font-size: 0.9rem;
            height: 38px;
        }

        .view-all-container {
            margin-top: 30px;
            padding-top: 30px;
        }

        .btn-view-all {
            padding: 11px 28px;
            font-size: 0.95rem;
            height: 44px;
            min-width: 180px;
        }
    }

    @media (min-width: 1024px) {
        .products-grid {
            grid-template-columns: repeat(6, 1fr);
            gap: 15px;
            /* Tighter gap on desktop */
        }

        .product-image {
            height: 120px;
            /* Even smaller on desktop for 6 columns */
        }

        .product-content {
            padding: 12px;
            /* Even more compact */
        }

        .product-name {
            font-size: 0.9rem;
            margin-bottom: 6px;
        }

        .product-price {
            margin-bottom: 12px;
        }

        .current-price {
            font-size: 1rem;
        }

        .original-price {
            font-size: 0.85rem;
        }

        .product-actions {
            gap: 6px;
        }

        .btn-cart,
        .btn-details {
            padding: 7px 12px;
            font-size: 0.8rem;
            height: 34px;
        }

        .btn-cart i,
        .btn-details i {
            font-size: 0.8rem;
        }

        .btn-view-all {
            padding: 10px 32px;
            font-size: 1rem;
            height: 42px;
            min-width: 200px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select all add-to-cart forms
        const addToCartForms = document.querySelectorAll('.add-to-cart-form');

        addToCartForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                const form = this;
                const submitButton = form.querySelector('.btn-cart');
                const originalButtonText = submitButton.innerHTML;
                const productId = form.dataset.productId;

                // Show loading state
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
                submitButton.disabled = true;

                // Get form data
                const formData = new FormData(form);

                // Send AJAX request
                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message with a toast/notification
                            showCartNotification(data.message, 'success');

                            // Update cart count in the header/navigation if it exists
                            updateCartCount(data.cartCount);

                            // Optionally update the button state
                            setTimeout(() => {
                                submitButton.innerHTML =
                                    '<i class="fas fa-check"></i> Added';
                                submitButton.style.backgroundColor =
                                '#2e7d32'; // Green color for success

                                // Revert button after 2 seconds
                                setTimeout(() => {
                                    submitButton.innerHTML =
                                        originalButtonText;
                                    submitButton.style.backgroundColor = '';
                                    submitButton.disabled = false;
                                }, 2000);
                            }, 500);

                        } else {
                            // Show error message
                            showCartNotification(data.message, 'error');

                            // Revert button
                            submitButton.innerHTML = originalButtonText;
                            submitButton.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showCartNotification('Something went wrong. Please try again.',
                            'error');

                        // Revert button
                        submitButton.innerHTML = originalButtonText;
                        submitButton.disabled = false;
                    });
            });
        });

        // Function to show notification
        function showCartNotification(message, type = 'success') {
            // Check if notification container exists, create if not
            let notificationContainer = document.getElementById('cart-notification-container');

            if (!notificationContainer) {
                notificationContainer = document.createElement('div');
                notificationContainer.id = 'cart-notification-container';
                notificationContainer.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                display: flex;
                flex-direction: column;
                gap: 10px;
                max-width: 350px;
            `;
                document.body.appendChild(notificationContainer);
            }

            // Create notification element
            const notification = document.createElement('div');
            notification.className = `cart-notification ${type}`;
            notification.innerHTML = `
            <div style="padding: 12px 16px; border-radius: 4px; background: ${type === 'success' ? '#4caf50' : '#f44336'}; color: white; font-size: 0.9rem; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                <strong>${type === 'success' ? '✓ Success!' : '✗ Error!'}</strong> ${message}
            </div>
        `;

            notificationContainer.appendChild(notification);

            // Auto-remove after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transition = 'opacity 0.3s';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Function to update cart count in the header
        function updateCartCount(count) {
            // Update cart count in desktop header if it exists
            const desktopCartCount = document.querySelector('.desktop-cart .cart-count');
            if (desktopCartCount) {
                desktopCartCount.textContent = count;
            }

            // Update cart count in mobile navigation if it exists
            const mobileCartCount = document.querySelector('.mobile-bottom-nav .cart-count');
            if (mobileCartCount) {
                mobileCartCount.textContent = count;
            }

            // If cart count elements don't exist, create them or update session
            sessionStorage.setItem('cartCount', count);
        }

        // Optional: Load initial cart count on page load
        function loadCartCount() {
            fetch('{{ route('cart.count') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    updateCartCount(data.cartCount);
                })
                .catch(error => console.error('Error loading cart count:', error));
        }

        // Load cart count when page loads
        loadCartCount();
    });
</script>
