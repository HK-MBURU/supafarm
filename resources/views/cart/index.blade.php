@extends('layouts.app')

@section('content')
<div class="cart-container">
    <div class="breadcrumbs">
        <a href="/">Home</a> &gt; <span>Shopping Cart</span>
    </div>



    <!-- Success/Error Messages -->
    <div id="cart-messages" class="cart-messages"></div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
    @endif

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay" style="display: none;">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Updating cart...</p>
        </div>
    </div>

    <div id="cart-content">
        @if($cartItems->count() > 0)


            <div class="cart-items" id="cart-items-container">
                <div class="cart-header">
                    <div class="product-col">Product</div>
                    <div class="price-col">Price</div>
                    <div class="quantity-col">Quantity</div>
                    <div class="total-col">Total</div>
                    <div class="action-col">Action</div>
                </div>

                @foreach($cartItems as $item)
                <div class="cart-item" data-item-id="{{ $item->id }}">
                    <div class="product-col">
                        <div class="product-info">
                            @if($item->product->image_url)
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="product-thumbnail">
                            @else
                            <div class="no-image">No Image</div>
                            @endif
                            <div class="product-details">
                                <h3>{{ $item->product->name }}</h3>
                                <p class="category">{{ $item->product->category->name ?? 'Uncategorized' }}</p>

                                @if(($item->product->stock ?? 0) <= 5 && ($item->product->stock ?? 0) > 0)
                                <p class="stock-warning">Only {{ $item->product->stock }} left</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="price-col">
                        <span class="price">KSh {{ number_format($item->price, 2) }}</span>
                    </div>

                    <div class="quantity-col">
                        <div class="quantity-form">
                            <div class="quantity-selector">
                                <button type="button" class="qty-btn minus" data-item-id="{{ $item->id }}">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number"
                                       class="qty-input"
                                       value="{{ $item->quantity }}"
                                       min="1"
                                       max="{{ $item->product->stock ?? 10 }}"
                                       data-item-id="{{ $item->id }}"
                                       data-original-value="{{ $item->quantity }}">
                                <button type="button" class="qty-btn plus" data-item-id="{{ $item->id }}">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Fallback form for non-JS users -->
                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="fallback-form" style="display: none;">
                            @csrf
                            @method('PUT')
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock ?? 10 }}">
                            <button type="submit" class="update-btn">Update</button>
                        </form>
                    </div>

                    <div class="total-col">
                        <span class="item-total" data-item-id="{{ $item->id }}">KSh {{ number_format($item->total_price, 2) }}</span>
                    </div>

                    <div class="action-col">
                        <button type="button" class="remove-btn" data-item-id="{{ $item->id }}" data-product-name="{{ $item->product->name }}">
                            <i class="fas fa-trash"></i>
                        </button>

                        <!-- Fallback form for non-JS users -->
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="fallback-form" style="display: none;" onsubmit="return confirm('Are you sure you want to remove this item?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="remove-btn"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="cart-actions">
                <div class="continue-shopping">
                    <a href="/" class="btn-secondary"><i class="fas fa-arrow-left"></i> Continue Shopping</a>
                </div>

                <div class="cart-totals">
                    <div class="totals-row subtotal">
                        <span>Subtotal:</span>
                        <span>KSh <span id="cart-subtotal">{{ number_format($total, 2) }}</span></span>
                    </div>

                    <div class="totals-row shipping">
                        <span>Shipping:</span>
                        <span>Calculated at checkout</span>
                    </div>

                    <div class="totals-row total">
                        <span>Total:</span>
                        <span>KSh <span id="cart-final-total">{{ number_format($total, 2) }}</span></span>
                    </div>

                    <div class="checkout-actions">
                        <button type="button" id="clear-cart-btn" class="clear-cart-btn">Clear Cart</button>

                        <!-- Fallback form for non-JS users -->
                        <form action="{{ route('cart.clear') }}" method="POST" class="fallback-form" style="display: none;" onsubmit="return confirm('Are you sure you want to clear your cart?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="clear-cart-btn">Clear Cart</button>
                        </form>

                        <a href="{{ route('cart.checkout') }}" class="checkout-btn">
                            <i class="fas fa-lock"></i> Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-cart" id="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h2>Your cart is empty</h2>
                <p>Looks like you haven't added any products to your cart yet.</p>
                <a href="/" class="btn-primary">Start Shopping</a>
            </div>
        @endif
    </div>

  
</div>
@endsection

@push('styles')
<style>
.cart-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
    position: relative;
}

.breadcrumbs {
    margin-bottom: 20px;
    color: #666;
}

.breadcrumbs a {
    color: var(--primary-color);
    text-decoration: none;
}

.breadcrumbs a:hover {
    text-decoration: underline;
}

.cart-title {
    font-size: 1.8rem;
    color: var(--primary-color);
    margin-bottom: 20px;
    text-align: center;
}

.cart-messages {
    margin-bottom: 20px;
}

.alert {
    padding: 10px 15px;
    margin-bottom: 20px;
    border-radius: 4px;
    transition: opacity 0.3s ease;
}

.alert-success {
    background-color: #e8f5e9;
    color: #2e7d32;
    border-left: 4px solid #2e7d32;
}

.alert-error {
    background-color: #ffebee;
    color: #c62828;
    border-left: 4px solid #c62828;
}

.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    border-radius: 8px;
}

.loading-spinner {
    text-align: center;
    padding: 20px;
}

.loading-spinner i {
    font-size: 2rem;
    color: var(--primary-color);
    margin-bottom: 10px;
}

.loading-spinner p {
    color: var(--dark-color);
    margin: 0;
}

.cart-summary {
    margin-bottom: 20px;
    padding: 10px;
    background-color: #f9f9f9;
    border-radius: 4px;
    text-align: center;
    transition: all 0.3s ease;
}

.cart-items {
    margin-bottom: 30px;
}

.cart-header {
    display: none;
}

.cart-item {
    background-color: white;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    display: grid;
    grid-template-columns: 1fr;
    gap: 10px;
    transition: all 0.3s ease;
}

.cart-item.updating {
    opacity: 0.7;
    pointer-events: none;
}

.cart-item.removing {
    opacity: 0;
    transform: translateX(-100%);
    margin-bottom: 0;
    padding: 0;
    height: 0;
    overflow: hidden;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.product-thumbnail {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
}

.no-image {
    width: 80px;
    height: 80px;
    background-color: #f0f0f0;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
    font-size: 0.8rem;
}

.product-details h3 {
    font-size: 1rem;
    margin: 0 0 5px 0;
    color: var(--dark-color);
}

.category {
    font-size: 0.8rem;
    color: #666;
    margin: 0 0 5px 0;
}

.stock-warning {
    font-size: 0.8rem;
    color: #f57c00;
    margin: 0;
}

.price-col, .quantity-col, .total-col, .action-col {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.price-col::before,
.quantity-col::before,
.total-col::before {
    content: attr(data-label);
    font-weight: bold;
    width: 80px;
    min-width: 80px;
}

.price {
    font-weight: bold;
    color: var(--primary-color);
}

.quantity-form {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.quantity-selector {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 4px;
    overflow: hidden;
}

.qty-btn {
    background-color: #f5f5f5;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
}

.qty-btn:hover:not(:disabled) {
    background-color: #eee;
}

.qty-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.qty-btn.loading {
    color: transparent;
}

.qty-btn.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 12px;
    height: 12px;
    border: 2px solid #ccc;
    border-top: 2px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}

.qty-input {
    width: 50px;
    border: none;
    text-align: center;
    padding: 8px 5px;
    font-size: 14px;
    transition: background-color 0.2s;
}

.qty-input:focus {
    outline: none;
    background-color: #f0f8ff;
}

.item-total {
    font-weight: bold;
    color: var(--dark-color);
    font-size: 1.1rem;
    transition: color 0.3s ease;
}

.item-total.updating {
    color: var(--primary-color);
}

.remove-btn {
    background-color: transparent;
    border: none;
    color: #e53935;
    cursor: pointer;
    font-size: 1.2rem;
    padding: 8px;
    border-radius: 4px;
    transition: all 0.2s;
    position: relative;
}

.remove-btn:hover:not(:disabled) {
    color: #c62828;
    background-color: #ffebee;
}

.remove-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.remove-btn.loading {
    color: transparent;
}

.remove-btn.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 12px;
    height: 12px;
    border: 2px solid #ccc;
    border-top: 2px solid #e53935;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

.cart-actions {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.continue-shopping {
    margin-bottom: 15px;
}

.btn-secondary {
    display: inline-block;
    padding: 10px 15px;
    background-color: #f0f0f0;
    border: 1px solid #ddd;
    border-radius: 4px;
    color: var(--dark-color);
    text-decoration: none;
    transition: background-color 0.2s;
}

.btn-secondary:hover {
    background-color: #e0e0e0;
}

.cart-totals {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.totals-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
    transition: all 0.3s ease;
}

.totals-row.total {
    font-weight: bold;
    font-size: 1.2rem;
    color: var(--dark-color);
    border-bottom: none;
    padding-top: 15px;
}

.checkout-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 20px;
}

.clear-cart-btn {
    padding: 10px;
    background-color: transparent;
    border: 1px solid #e53935;
    color: #e53935;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
}

.clear-cart-btn:hover:not(:disabled) {
    background-color: #ffebee;
}

.clear-cart-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.clear-cart-btn.loading {
    color: transparent;
}

.clear-cart-btn.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 12px;
    height: 12px;
    border: 2px solid #ccc;
    border-top: 2px solid #e53935;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

.checkout-btn {
    display: block;
    padding: 15px;
    background-color: var(--primary-color);
    color: white;
    text-align: center;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
    font-size: 1.1rem;
    transition: background-color 0.2s;
}

.checkout-btn:hover {
    background-color: var(--dark-color);
}

.checkout-btn i {
    margin-right: 8px;
}

.empty-cart {
    text-align: center;
    padding: 50px 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: all 0.5s ease;
}

.empty-cart i {
    font-size: 4rem;
    color: #ccc;
    margin-bottom: 20px;
}

.empty-cart h2 {
    font-size: 1.5rem;
    color: var(--dark-color);
    margin-bottom: 10px;
}

.empty-cart p {
    color: #666;
    margin-bottom: 20px;
}

.btn-primary {
    display: inline-block;
    padding: 12px 24px;
    background-color: var(--primary-color);
    color: white;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.2s;
}

.btn-primary:hover {
    background-color: var(--dark-color);
}

.cart-meta {
    margin-top: 30px;
    text-align: center;
    font-size: 0.8rem;
    color: #999;
    padding: 10px;
    background-color: #f9f9f9;
    border-radius: 4px;
}

.fallback-form {
    display: none !important;
}

/* Desktop Styles */
@media (min-width: 768px) {
    .cart-header {
        display: grid;
        grid-template-columns: 3fr 1fr 2fr 1fr 1fr;
        padding: 15px;
        background-color: #f5f5f5;
        border-radius: 8px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .cart-item {
        grid-template-columns: 3fr 1fr 2fr 1fr 1fr;
        padding: 20px;
        align-items: center;
    }

    .price-col::before,
    .quantity-col::before,
    .total-col::before {
        content: none;
    }

    .price-col, .quantity-col, .total-col, .action-col {
        justify-content: center;
    }

    .product-col {
        justify-self: start;
    }

    .cart-actions {
        flex-direction: row;
        justify-content: space-between;
        align-items: flex-start;
    }

    .cart-totals {
        width: 350px;
    }

    .checkout-actions {
        flex-direction: row;
        gap: 15px;
    }

    .clear-cart-btn {
        flex: 1;
    }

    .checkout-btn {
        flex: 2;
    }
}

/* Mobile responsive adjustments */
@media (max-width: 767px) {
    .price-col::before {
        content: "Price: ";
    }

    .quantity-col::before {
        content: "Qty: ";
    }

    .total-col::before {
        content: "Total: ";
    }

    .cart-item {
        padding: 15px;
    }

    .product-info {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }

    .product-details {
        text-align: left;
    }

    .quantity-form {
        width: 100%;
        justify-content: space-between;
    }

    .quantity-selector {
        flex: 1;
        max-width: 120px;
    }

    .checkout-actions {
        gap: 15px;
    }
}

/* JavaScript disabled fallback */
.no-js .fallback-form {
    display: block !important;
}

.no-js .quantity-selector {
    display: none;
}

.no-js .remove-btn:not(.fallback-form .remove-btn) {
    display: none;
}

.no-js #clear-cart-btn {
    display: none;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // CSRF token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!csrfToken) {
        console.error('CSRF token not found. Make sure to include it in your layout.');
        return;
    }

    // Utility function to show messages
    function showMessage(message, type = 'success') {
        const messagesContainer = document.getElementById('cart-messages');
        const messageElement = document.createElement('div');
        messageElement.className = `alert alert-${type}`;
        messageElement.textContent = message;
        messagesContainer.appendChild(messageElement);

        // Auto-hide after 5 seconds
        setTimeout(() => {
            messageElement.style.opacity = '0';
            setTimeout(() => {
                messageElement.remove();
            }, 300);
        }, 5000);
    }

    // Utility function to update cart count in header/navbar
    function updateCartCount(count) {
        const cartCountElements = document.querySelectorAll('.cart-count, .cart-badge, [data-cart-count]');
        cartCountElements.forEach(element => {
            element.textContent = count;
        });
    }

    // Utility function to update totals
    function updateTotals(cartTotal) {
        document.getElementById('cart-total-display').textContent = cartTotal;
        document.getElementById('cart-subtotal').textContent = cartTotal;
        document.getElementById('cart-final-total').textContent = cartTotal;
    }

    // Utility function to show/hide empty cart state
    function toggleEmptyCart(isEmpty) {
        const cartContent = document.getElementById('cart-content');
        const cartItemsContainer = document.getElementById('cart-items-container');

        if (isEmpty) {
            cartContent.innerHTML = `
                <div class="empty-cart" id="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h2>Your cart is empty</h2>
                    <p>Looks like you haven't added any products to your cart yet.</p>
                    <a href="/" class="btn-primary">Start Shopping</a>
                </div>
            `;
        }
    }

    // AJAX request helper
    function makeAjaxRequest(url, method, data, onSuccess, onError) {
        const loadingOverlay = document.getElementById('loading-overlay');
        loadingOverlay.style.display = 'flex';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: data ? JSON.stringify(data) : null,
        })
        .then(response => response.json())
        .then(data => {
            loadingOverlay.style.display = 'none';
            if (data.success) {
                onSuccess(data);
            } else {
                onError(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            loadingOverlay.style.display = 'none';
            console.error('Error:', error);
            onError('Network error occurred');
        });
    }

    // Update quantity function
    function updateQuantity(itemId, newQuantity) {
        const cartItem = document.querySelector(`[data-item-id="${itemId}"]`);
        const qtyInput = cartItem.querySelector('.qty-input');
        const itemTotal = cartItem.querySelector('.item-total');
        const minusBtn = cartItem.querySelector('.qty-btn.minus');
        const plusBtn = cartItem.querySelector('.qty-btn.plus');

        // Disable buttons and add loading state
        minusBtn.disabled = true;
        plusBtn.disabled = true;
        minusBtn.classList.add('loading');
        plusBtn.classList.add('loading');
        cartItem.classList.add('updating');
        itemTotal.classList.add('updating');

        makeAjaxRequest(
            `/cart/update-quantity/${itemId}`,
            'PUT',
            { quantity: newQuantity },
            function(data) {
                // Update item total
                itemTotal.textContent = `KSh ${data.item_total}`;

                // Update cart totals
                updateTotals(data.cart_total);

                // Update cart count
                updateCartCount(data.cart_count);

                // Update quantity input
                qtyInput.value = data.quantity;
                qtyInput.setAttribute('data-original-value', data.quantity);

                // Show success message
                showMessage(data.message, 'success');

                // Re-enable buttons and remove loading state
                minusBtn.disabled = false;
                plusBtn.disabled = false;
                minusBtn.classList.remove('loading');
                plusBtn.classList.remove('loading');
                cartItem.classList.remove('updating');
                itemTotal.classList.remove('updating');
            },
            function(errorMessage) {
                // Revert quantity to original value
                const originalValue = qtyInput.getAttribute('data-original-value');
                qtyInput.value = originalValue;

                // Show error message
                showMessage(errorMessage, 'error');

                // Re-enable buttons and remove loading state
                minusBtn.disabled = false;
                plusBtn.disabled = false;
                minusBtn.classList.remove('loading');
                plusBtn.classList.remove('loading');
                cartItem.classList.remove('updating');
                itemTotal.classList.remove('updating');
            }
        );
    }

    // Quantity button event listeners
    document.addEventListener('click', function(e) {
        if (e.target.closest('.qty-btn.minus')) {
            const button = e.target.closest('.qty-btn.minus');
            const itemId = button.getAttribute('data-item-id');
            const input = button.nextElementSibling;
            const currentValue = parseInt(input.value);
            const minValue = parseInt(input.min);

            if (currentValue > minValue && !button.disabled) {
                updateQuantity(itemId, currentValue - 1);
            }
        }

        if (e.target.closest('.qty-btn.plus')) {
            const button = e.target.closest('.qty-btn.plus');
            const itemId = button.getAttribute('data-item-id');
            const input = button.previousElementSibling;
            const currentValue = parseInt(input.value);
            const maxValue = parseInt(input.max);

            if (currentValue < maxValue && !button.disabled) {
                updateQuantity(itemId, currentValue + 1);
            }
        }
    });

    // Quantity input change event
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('qty-input')) {
            const input = e.target;
            const itemId = input.getAttribute('data-item-id');
            const newValue = parseInt(input.value);
            const minValue = parseInt(input.min);
            const maxValue = parseInt(input.max);
            const originalValue = parseInt(input.getAttribute('data-original-value'));

            if (newValue !== originalValue && newValue >= minValue && newValue <= maxValue) {
                updateQuantity(itemId, newValue);
            } else if (newValue < minValue || newValue > maxValue) {
                input.value = originalValue;
                showMessage(`Quantity must be between ${minValue} and ${maxValue}`, 'error');
            }
        }
    });

    // Remove item function
    function removeItem(itemId, productName) {
        const cartItem = document.querySelector(`[data-item-id="${itemId}"]`);
        const removeBtn = cartItem.querySelector('.remove-btn');

        // Add loading state
        removeBtn.disabled = true;
        removeBtn.classList.add('loading');
        cartItem.classList.add('updating');

        makeAjaxRequest(
            `/cart/remove-item/${itemId}`,
            'DELETE',
            null,
            function(data) {
                // Show success message
                showMessage(data.message, 'success');

                // Update cart count
                updateCartCount(data.cart_count);

                // Animate item removal
                cartItem.classList.add('removing');

                setTimeout(() => {
                    cartItem.remove();

                    // Update totals
                    updateTotals(data.cart_total);

                    // Check if cart is empty
                    if (data.cart_empty) {
                        toggleEmptyCart(true);
                    } else {
                        // Update item count in summary
                        const remainingItems = document.querySelectorAll('.cart-item').length - 1;
                        document.getElementById('cart-item-count').textContent = remainingItems;
                    }
                }, 300);
            },
            function(errorMessage) {
                // Remove loading state
                removeBtn.disabled = false;
                removeBtn.classList.remove('loading');
                cartItem.classList.remove('updating');

                // Show error message
                showMessage(errorMessage, 'error');
            }
        );
    }

    // Remove item event listener
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-btn') && !e.target.closest('.fallback-form')) {
            const button = e.target.closest('.remove-btn');
            const itemId = button.getAttribute('data-item-id');
            const productName = button.getAttribute('data-product-name');

            if (confirm(`Are you sure you want to remove "${productName}" from your cart?`)) {
                removeItem(itemId, productName);
            }
        }
    });

    // Clear cart function
    function clearCart() {
        const clearBtn = document.getElementById('clear-cart-btn');

        // Add loading state
        clearBtn.disabled = true;
        clearBtn.classList.add('loading');

        makeAjaxRequest(
            '/cart/clear-cart',
            'DELETE',
            null,
            function(data) {
                // Show success message
                showMessage(data.message, 'success');

                // Update cart count
                updateCartCount(0);

                // Show empty cart state
                toggleEmptyCart(true);
            },
            function(errorMessage) {
                // Remove loading state
                clearBtn.disabled = false;
                clearBtn.classList.remove('loading');

                // Show error message
                showMessage(errorMessage, 'error');
            }
        );
    }

    // Clear cart event listener
    document.addEventListener('click', function(e) {
        if (e.target.id === 'clear-cart-btn') {
            if (confirm('Are you sure you want to clear your entire cart?')) {
                clearCart();
            }
        }
    });

    // Auto-hide existing success/error messages
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });

    console.log('Dynamic cart functionality initialized');
});
</script>
@endpush
