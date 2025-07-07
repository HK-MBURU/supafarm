@extends('layouts.app')

@section('content')
<div class="cart-container">
    <div class="breadcrumbs">
        <a href="/">Home</a> &gt; <span>Shopping Cart</span>
    </div>
    
    <h1 class="cart-title">Your Shopping Cart</h1>
    
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
    
    @if($cartItems->count() > 0)
        <div class="cart-summary">
            <p>You have {{ $cartItems->count() }} {{ Str::plural('item', $cartItems->count()) }} in your cart with a total of KSh {{ number_format($total, 2) }}.</p>
        </div>
        
        <div class="cart-items">
            <div class="cart-header">
                <div class="product-col">Product</div>
                <div class="price-col">Price</div>
                <div class="quantity-col">Quantity</div>
                <div class="total-col">Total</div>
                <div class="action-col">Action</div>
            </div>
            
            @foreach($cartItems as $item)
            <div class="cart-item">
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
                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="quantity-form">
                        @csrf
                        <div class="quantity-selector">
                            <button type="button" class="qty-btn minus"><i class="fas fa-minus"></i></button>
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock ?? 10 }}" class="qty-input">
                            <button type="button" class="qty-btn plus"><i class="fas fa-plus"></i></button>
                        </div>
                        <button type="submit" class="update-btn">Update</button>
                    </form>
                </div>
                
                <div class="total-col">
                    <span class="item-total">KSh {{ number_format($item->price * $item->quantity, 2) }}</span>
                </div>
                
                <div class="action-col">
                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this item?');">
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
                    <span>KSh {{ number_format($total, 2) }}</span>
                </div>
                
                <div class="totals-row shipping">
                    <span>Shipping:</span>
                    <span>Calculated at checkout</span>
                </div>
                
                <div class="totals-row total">
                    <span>Total:</span>
                    <span>KSh {{ number_format($total, 2) }}</span>
                </div>
                
                <div class="checkout-actions">
                    <form action="{{ route('cart.clear') }}" method="POST" class="clear-cart-form" onsubmit="return confirm('Are you sure you want to clear your cart?');">
                        @csrf
                        <button type="submit" class="clear-cart-btn">Clear Cart</button>
                    </form>
                    
                    <a href="{{ route('cart.checkout') }}" class="checkout-btn">
                        <i class="fas fa-lock"></i> Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="empty-cart">
            <i class="fas fa-shopping-cart"></i>
            <h2>Your cart is empty</h2>
            <p>Looks like you haven't added any products to your cart yet.</p>
            <a href="/" class="btn-primary">Start Shopping</a>
        </div>
    @endif
    
    <div class="cart-meta">
        <p>Last updated by: {{ $loginUser }} at {{ $timestamp }}</p>
    </div>
</div>
@endsection

@push('styles')
<style>
.cart-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

.cart-title {
    font-size: 1.8rem;
    color: var(--primary-color);
    margin-bottom: 20px;
    text-align: center;
}

.alert {
    padding: 10px 15px;
    margin-bottom: 20px;
    border-radius: 4px;
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

.cart-summary {
    margin-bottom: 20px;
    padding: 10px;
    background-color: #f9f9f9;
    border-radius: 4px;
    text-align: center;
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
}

.qty-btn:hover {
    background-color: #eee;
}

.qty-input {
    width: 40px;
    border: none;
    text-align: center;
    padding: 8px 0;
}

.update-btn {
    padding: 6px 10px;
    background-color: #f0f0f0;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.8rem;
    cursor: pointer;
}

.update-btn:hover {
    background-color: #e0e0e0;
}

.item-total {
    font-weight: bold;
    color: var(--dark-color);
}

.remove-btn {
    background-color: transparent;
    border: none;
    color: #e53935;
    cursor: pointer;
    font-size: 1rem;
}

.remove-btn:hover {
    color: #c62828;
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
}

.totals-row.total {
    font-weight: bold;
    font-size: 1.1rem;
    color: var(--dark-color);
    border-bottom: none;
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
}

.clear-cart-btn:hover {
    background-color: #ffebee;
}

.checkout-btn {
    display: block;
    padding: 12px;
    background-color: var(--primary-color);
    color: white;
    text-align: center;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
}

.checkout-btn:hover {
    background-color: var(--dark-color);
}

.checkout-btn i {
    margin-right: 5px;
}

.empty-cart {
    text-align: center;
    padding: 50px 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
    padding: 10px 20px;
    background-color: var(--primary-color);
    color: white;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
}

.btn-primary:hover {
    background-color: var(--dark-color);
}

.cart-meta {
    margin-top: 30px;
    text-align: center;
    font-size: 0.8rem;
    color: #999;
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
        padding: 15px;
    }
    
    .price-col::before, 
    .quantity-col::before, 
    .total-col::before {
        content: none;
    }
    
    .cart-actions {
        flex-direction: row;
        justify-content: space-between;
    }
    
    .cart-totals {
        width: 350px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
});
</script>
@endpush