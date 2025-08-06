@extends('layouts.app')

@section('content')
<div class="checkout-container">
    <div class="breadcrumbs">
        <a href="/">Home</a> &gt;
        <a href="{{ route('cart.index') }}">Cart</a> &gt;
        <span>Checkout</span>
    </div>

    <h1 class="checkout-title">Checkout</h1>

    <!-- Progress Steps -->
    <div class="checkout-progress">
        <div class="progress-step active">
            <div class="step-number">1</div>
            <div class="step-label">Order Details</div>
        </div>
        <div class="progress-step">
            <div class="step-number">2</div>
            <div class="step-label">Payment</div>
        </div>
        <div class="progress-step">
            <div class="step-number">3</div>
            <div class="step-label">Confirmation</div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="checkout-loading" class="checkout-loading" style="display: none;">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Processing your order...</p>
        </div>
    </div>

    <!-- Error Messages -->
    <div id="checkout-messages" class="checkout-messages"></div>

    <div class="checkout-content">
        <!-- Order Summary - Mobile First -->
        <div class="checkout-summary mobile-summary">
            <div class="summary-card">
                <div class="summary-header">
                    <h3><i class="fas fa-shopping-cart"></i> Order Summary</h3>
                    <button type="button" class="toggle-summary" onclick="toggleSummary()">
                        <span class="summary-total">KSh {{ number_format($total, 2) }}</span>
                        <i class="fas fa-chevron-down toggle-icon"></i>
                    </button>
                </div>

                <div class="summary-details" id="summaryDetails">
                    <div class="summary-items">
                        @foreach($cartItems as $item)
                        <div class="summary-item">
                            <div class="item-image">
                                @if($item->product->image_url)
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                                @else
                                <div class="no-image"><i class="fas fa-image"></i></div>
                                @endif
                            </div>
                            <div class="item-details">
                                <h4>{{ $item->product->name }}</h4>
                                <p class="item-quantity">Qty: {{ $item->quantity }}</p>
                            </div>
                            <div class="item-price">
                                KSh {{ number_format($item->quantity * $item->price, 2) }}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="summary-totals">
                        <div class="total-row">
                            <span>Subtotal:</span>
                            <span>KSh {{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="total-row">
                            <span>Shipping:</span>
                            <span id="shipping-cost">
                                @if($shipping > 0)
                                KSh {{ number_format($shipping, 2) }}
                                @else
                                <span class="free-shipping">FREE</span>
                                @endif
                            </span>
                        </div>
                        <div class="total-row">
                            <span>Tax (16% VAT):</span>
                            <span>KSh {{ number_format($tax, 2) }}</span>
                        </div>
                        <div class="total-row total-final">
                            <span>Total:</span>
                            <span>KSh {{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    @if($shipping == 0)
                    <div class="free-shipping-notice">
                        <i class="fas fa-truck"></i>
                        <span>Congratulations! You qualify for FREE shipping</span>
                    </div>
                    @endif

                    <div class="security-notice">
                        <i class="fas fa-shield-alt"></i>
                        <span>Your payment information is secure and encrypted</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="checkout-form-section">
            <form id="checkout-form">
                @csrf

                <!-- Customer Information -->
                <div class="form-section">
                    <h3><i class="fas fa-user"></i> Customer Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name *</label>
                            <input type="text" id="first_name" name="customer[first_name]"
                                value="{{ $customer['first_name'] }}" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name *</label>
                            <input type="text" id="last_name" name="customer[last_name]"
                                value="{{ $customer['last_name'] }}" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="customer[email]"
                                value="{{ $customer['email'] }}" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="customer[phone]"
                                value="{{ $customer['phone'] }}" required>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="form-section">
                    <h3><i class="fas fa-shipping-fast"></i> Delivery Location</h3>
                    
                    <div class="form-group">
                        <label for="location_search">Search Your Location *</label>
                        <div class="location-picker">
                            <div class="location-input-wrapper">
                                <input type="text"
                                    id="location_search"
                                    placeholder="Type your address, landmark, or area..."
                                    autocomplete="off"
                                    required>
                                <button type="button" id="current_location_btn" class="current-location-btn">
                                    <i class="fas fa-crosshairs"></i>
                                    <span class="btn-text">Current Location</span>
                                </button>
                            </div>
                            <div id="location_suggestions" class="location-suggestions"></div>
                            <div id="selected_location" class="selected-location" style="display: none;">
                                <i class="fas fa-check-circle"></i>
                                <span class="location-text"></span>
                                <button type="button" class="change-location-btn">
                                    <i class="fas fa-edit"></i>
                                    Change
                                </button>
                            </div>
                        </div>
                        <small>We'll use this location to calculate delivery charges and time</small>
                    </div>

                    <!-- Hidden fields to store the selected location -->
                    <input type="hidden" id="selected_lat" name="shipping[latitude]">
                    <input type="hidden" id="selected_lng" name="shipping[longitude]">
                    <input type="hidden" id="selected_address" name="shipping[full_address]">
                </div>

                <!-- Payment Method -->
                <div class="form-section">
                    <h3><i class="fas fa-credit-card"></i> Payment Method</h3>

                    <div class="payment-methods">
                        <label class="payment-method">
                            <input type="radio" name="payment[method]" value="mpesa" checked>
                            <div class="payment-option">
                                <div class="payment-icon">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <div class="payment-details">
                                    <h4>M-Pesa</h4>
                                    <p>Pay using M-Pesa mobile money</p>
                                </div>
                            </div>
                        </label>

                        <label class="payment-method">
                            <input type="radio" name="payment[method]" value="cash_on_delivery">
                            <div class="payment-option">
                                <div class="payment-icon">
                                    <i class="fas fa-money-bill"></i>
                                </div>
                                <div class="payment-details">
                                    <h4>Cash on Delivery</h4>
                                    <p>Pay when you receive your order</p>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- M-Pesa Details -->
                    <div id="mpesa-details" class="payment-details-section">
                        <div class="form-group">
                            <label for="mpesa_phone">M-Pesa Phone Number *</label>
                            <input type="tel" id="mpesa_phone" name="payment[mpesa_phone]"
                                placeholder="254712345678" value="{{ $customer['phone'] }}">
                            <small>Enter your M-Pesa registered phone number</small>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="form-section">
                    <h3><i class="fas fa-sticky-note"></i> Order Notes (Optional)</h3>
                    <div class="form-group">
                        <textarea id="notes" name="notes" rows="4"
                            placeholder="Any special instructions for your order..."></textarea>
                    </div>
                </div>

            </form>
        </div>

        <!-- Desktop Order Summary -->
        <div class="checkout-summary desktop-summary">
            <div class="summary-card">
                <h3><i class="fas fa-shopping-cart"></i> Order Summary</h3>

                <div class="summary-items">
                    @foreach($cartItems as $item)
                    <div class="summary-item">
                        <div class="item-image">
                            @if($item->product->image_url)
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                            @else
                            <div class="no-image"><i class="fas fa-image"></i></div>
                            @endif
                        </div>
                        <div class="item-details">
                            <h4>{{ $item->product->name }}</h4>
                            <p class="item-quantity">Qty: {{ $item->quantity }}</p>
                        </div>
                        <div class="item-price">
                            KSh {{ number_format($item->quantity * $item->price, 2) }}
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="summary-totals">
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span>KSh {{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="total-row">
                        <span>Shipping:</span>
                        <span id="shipping-cost-desktop">
                            @if($shipping > 0)
                            KSh {{ number_format($shipping, 2) }}
                            @else
                            <span class="free-shipping">FREE</span>
                            @endif
                        </span>
                    </div>
                    <div class="total-row">
                        <span>Tax (16% VAT):</span>
                        <span>KSh {{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="total-row total-final">
                        <span>Total:</span>
                        <span>KSh {{ number_format($total, 2) }}</span>
                    </div>
                </div>

                @if($shipping == 0)
                <div class="free-shipping-notice">
                    <i class="fas fa-truck"></i>
                    <span>Congratulations! You qualify for FREE shipping</span>
                </div>
                @endif

                <button type="button" id="place-order-btn-desktop" class="place-order-btn">
                    <i class="fas fa-lock"></i>
                    Place Order - KSh {{ number_format($total, 2) }}
                </button>

                <div class="security-notice">
                    <i class="fas fa-shield-alt"></i>
                    <span>Your payment information is secure and encrypted</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Sticky Footer -->
    <div class="mobile-checkout-footer">
        <div class="footer-total">
            <span class="total-label">Total:</span>
            <span class="total-amount">KSh {{ number_format($total, 2) }}</span>
        </div>
        <button type="button" id="place-order-btn-mobile" class="place-order-btn-mobile">
            <i class="fas fa-lock"></i>
            Place Order
        </button>
    </div>
</div>
@endsection

@push('styles')
<style>
    .checkout-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
        position: relative;
        padding-bottom: 100px; /* Space for mobile footer */
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

    .checkout-title {
        font-size: 1.8rem;
        color: var(--primary-color);
        margin-bottom: 30px;
        text-align: center;
    }

    .checkout-progress {
        display: flex;
        justify-content: center;
        margin-bottom: 40px;
        padding: 0 20px;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        flex: 1;
        max-width: 200px;
    }

    .progress-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 15px;
        left: 60%;
        right: -40%;
        height: 2px;
        background-color: #ddd;
        z-index: -1;
    }

    .progress-step.active:not(:last-child)::after {
        background-color: var(--primary-color);
    }

    .step-number {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #ddd;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .progress-step.active .step-number {
        background-color: var(--primary-color);
    }

    .step-label {
        font-size: 0.9rem;
        color: #666;
        text-align: center;
    }

    .progress-step.active .step-label {
        color: var(--primary-color);
        font-weight: bold;
    }

    .checkout-loading {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
    }

    .loading-spinner {
        text-align: center;
        padding: 20px;
    }

    .loading-spinner i {
        font-size: 2rem;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .loading-spinner p {
        color: var(--dark-color);
        margin: 0;
        font-size: 1.1rem;
    }

    .checkout-messages {
        margin-bottom: 20px;
    }

    .checkout-content {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 40px;
        margin-top: 20px;
    }

    .checkout-form-section {
        background-color: white;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .form-section {
        margin-bottom: 30px;
        padding-bottom: 25px;
        border-bottom: 1px solid #eee;
    }

    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .form-section h3 {
        color: var(--dark-color);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-section h3 i {
        color: var(--primary-color);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: var(--dark-color);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
    }

    .form-group small {
        display: block;
        margin-top: 5px;
        color: #666;
        font-size: 0.85rem;
    }

    /* Location Picker Styles */
    .location-picker {
        position: relative;
    }

    .location-input-wrapper {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }

    #location_search {
        flex: 1;
        border-color: #ddd;
    }

    #location_search:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
    }

    .current-location-btn {
        padding: 12px 16px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .current-location-btn:hover:not(:disabled) {
        background-color: #218838;
        transform: translateY(-1px);
    }

    .current-location-btn:disabled {
        background-color: #6c757d;
        cursor: not-allowed;
        transform: none;
    }

    .location-suggestions {
        border: 1px solid #ddd;
        border-top: none;
        border-radius: 0 0 4px 4px;
        max-height: 200px;
        overflow-y: auto;
        background: white;
        display: none;
        position: absolute;
        left: 0;
        right: 0;
        z-index: 1000;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .suggestion-item {
        padding: 12px 15px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: background-color 0.2s ease;
    }

    .suggestion-item:hover {
        background-color: #f8f9fa;
    }

    .suggestion-item:last-child {
        border-bottom: none;
    }

    .suggestion-icon {
        color: var(--primary-color);
        width: 16px;
        flex-shrink: 0;
    }

    .suggestion-text {
        flex: 1;
    }

    .suggestion-main {
        font-weight: 500;
        color: var(--dark-color);
        margin-bottom: 2px;
    }

    .suggestion-secondary {
        font-size: 0.85rem;
        color: #666;
    }

    .selected-location {
        background-color: #e8f5e9;
        color: #2e7d32;
        padding: 12px 15px;
        border-radius: 4px;
        margin-top: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .selected-location i {
        color: #28a745;
    }

    .location-text {
        flex: 1;
        font-weight: 500;
    }

    .change-location-btn {
        background: none;
        border: none;
        color: var(--primary-color);
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: background-color 0.2s ease;
    }

    .change-location-btn:hover {
        background-color: rgba(0, 123, 255, 0.1);
    }

    /* Payment Method Styles */
    .payment-methods {
        display: grid;
        gap: 15px;
        margin-bottom: 20px;
    }

    .payment-method {
        cursor: pointer;
        margin-bottom: 0 !important;
    }

    .payment-method input[type="radio"] {
        display: none;
    }

    .payment-option {
        display: flex;
        align-items: center;
        padding: 15px;
        border: 2px solid #eee;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .payment-method input[type="radio"]:checked+.payment-option {
        border-color: var(--primary-color);
        background-color: rgba(0, 123, 255, 0.05);
    }

    .payment-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        border-radius: 8px;
        margin-right: 15px;
    }

    .payment-icon i {
        font-size: 1.5rem;
        color: var(--primary-color);
    }

    .payment-details h4 {
        margin: 0 0 5px 0;
        color: var(--dark-color);
        font-size: 1rem;
    }

    .payment-details p {
        margin: 0;
        color: #666;
        font-size: 0.9rem;
    }

    .payment-details-section {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-top: 15px;
    }

    /* Mobile Summary */
    .mobile-summary {
        display: none;
    }

    .desktop-summary {
        display: block;
    }

    .summary-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }

    .toggle-summary {
        background: none;
        border: none;
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--primary-color);
        font-weight: bold;
        cursor: pointer;
    }

    .summary-total {
        font-size: 1.1rem;
    }

    .toggle-icon {
        transition: transform 0.3s ease;
    }

    .toggle-icon.rotated {
        transform: rotate(180deg);
    }

    .summary-details {
        margin-top: 15px;
    }

    /* Order Summary Styles */
    .checkout-summary {
        position: sticky;
        top: 20px;
        height: fit-content;
    }

    .summary-card {
        background-color: white;
        border-radius: 8px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .summary-card h3 {
        color: var(--dark-color);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .summary-card h3 i {
        color: var(--primary-color);
    }

    .summary-items {
        margin-bottom: 20px;
    }

    .summary-item {
        display: flex;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #eee;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .item-image {
        width: 60px;
        height: 60px;
        margin-right: 15px;
        border-radius: 4px;
        overflow: hidden;
    }

    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-image {
        width: 100%;
        height: 100%;
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
    }

    .item-details {
        flex: 1;
    }

    .item-details h4 {
        margin: 0 0 5px 0;
        font-size: 0.9rem;
        color: var(--dark-color);
    }

    .item-quantity {
        margin: 0;
        font-size: 0.8rem;
        color: #666;
    }

    .item-price {
        font-weight: bold;
        color: var(--primary-color);
    }

    .summary-totals {
        border-top: 1px solid #eee;
        padding-top: 15px;
        margin-bottom: 20px;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 0.95rem;
    }

    .total-row.total-final {
        font-size: 1.1rem;
        font-weight: bold;
        color: var(--dark-color);
        border-top: 1px solid #eee;
        padding-top: 10px;
        margin-top: 15px;
    }

    .free-shipping {
        color: #28a745;
        font-weight: bold;
    }

    .free-shipping-notice {
        background-color: #e8f5e9;
        color: #2e7d32;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
    }

    .place-order-btn {
        width: 100%;
        padding: 15px;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .place-order-btn:hover:not(:disabled) {
        background-color: var(--dark-color);
        transform: translateY(-1px);
    }

    .place-order-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .place-order-btn.loading {
        position: relative;
        color: transparent;
    }

    .place-order-btn.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    /* Mobile Sticky Footer */
    .mobile-checkout-footer {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        border-top: 1px solid #eee;
        padding: 15px 20px;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .footer-total {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .total-label {
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 2px;
    }

    .total-amount {
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--dark-color);
    }

    .place-order-btn-mobile {
        padding: 12px 24px;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }

    .place-order-btn-mobile:hover:not(:disabled) {
        background-color: var(--dark-color);
        transform: translateY(-1px);
    }

    .place-order-btn-mobile:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .place-order-btn-mobile.loading {
        position: relative;
        color: transparent;
    }

    .place-order-btn-mobile.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    .security-notice {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: #666;
        font-size: 0.85rem;
        text-align: center;
    }

    .security-notice i {
        color: #28a745;
    }

    .alert {
        padding: 12px 15px;
        margin-bottom: 20px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        gap: 10px;
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

    @keyframes spin {
        0% {
            transform: translate(-50%, -50%) rotate(0deg);
        }

        100% {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .checkout-container {
            padding: 0 10px 120px 10px; /* More space for mobile footer */
        }

        .checkout-content {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .mobile-summary {
            display: block;
            order: -1;
            position: relative;
            top: auto;
            margin-bottom: 20px;
        }

        .desktop-summary {
            display: none;
        }

        .mobile-checkout-footer {
            display: flex;
        }

        .checkout-form-section {
            padding: 20px;
            margin-bottom: 0;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .location-input-wrapper {
            flex-direction: column;
            gap: 10px;
        }

        .current-location-btn {
            justify-content: center;
            padding: 14px 16px;
        }

        .btn-text {
            display: inline;
        }

        .checkout-progress {
            padding: 0 10px;
            margin-bottom: 20px;
        }

        .progress-step {
            font-size: 0.8rem;
        }

        .step-number {
            width: 25px;
            height: 25px;
            font-size: 0.8rem;
        }

        .checkout-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .summary-details {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .summary-details.expanded {
            max-height: 500px;
        }

        .summary-card {
            padding: 20px;
        }

        .summary-header {
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .payment-option {
            padding: 12px;
        }

        .payment-icon {
            width: 40px;
            height: 40px;
            margin-right: 12px;
        }

        .payment-icon i {
            font-size: 1.2rem;
        }

        .payment-details h4 {
            font-size: 0.9rem;
        }

        .payment-details p {
            font-size: 0.8rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 14px 12px;
            font-size: 16px; /* Prevents zoom on iOS */
        }

        .suggestion-item {
            padding: 15px 12px;
        }

        .selected-location {
            padding: 15px 12px;
        }

        .item-image {
            width: 50px;
            height: 50px;
            margin-right: 12px;
        }

        .item-details h4 {
            font-size: 0.85rem;
        }

        .item-quantity {
            font-size: 0.75rem;
        }

        .item-price {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 480px) {
        .checkout-container {
            padding: 0 5px 120px 5px;
        }

        .breadcrumbs {
            font-size: 0.85rem;
            margin-bottom: 15px;
        }

        .checkout-title {
            font-size: 1.3rem;
        }

        .form-section h3 {
            font-size: 1.1rem;
        }

        .payment-option {
            padding: 10px;
            flex-direction: column;
            text-align: center;
            gap: 8px;
        }

        .payment-icon {
            margin-right: 0;
            margin-bottom: 8px;
            width: 35px;
            height: 35px;
        }

        .current-location-btn .btn-text {
            display: none;
        }

        .footer-total {
            align-items: center;
        }

        .total-label {
            font-size: 0.8rem;
        }

        .total-amount {
            font-size: 1.1rem;
        }

        .place-order-btn-mobile {
            padding: 10px 16px;
            font-size: 0.9rem;
        }

        .checkout-loading {
            padding: 15px;
        }

        .loading-spinner p {
            font-size: 1rem;
        }
    }

    /* Tablet Responsive */
    @media (min-width: 769px) and (max-width: 1024px) {
        .checkout-content {
            grid-template-columns: 1fr 350px;
            gap: 30px;
        }

        .checkout-form-section {
            padding: 25px;
        }

        .summary-card {
            padding: 20px;
        }
    }

    /* Large Screen Responsive */
    @media (min-width: 1200px) {
        .checkout-content {
            grid-template-columns: 1fr 450px;
        }

        .checkout-form-section {
            padding: 40px;
        }
    }

    /* Landscape Mobile */
    @media (max-width: 768px) and (orientation: landscape) {
        .checkout-progress {
            margin-bottom: 15px;
        }

        .checkout-title {
            margin-bottom: 15px;
        }

        .mobile-checkout-footer {
            padding: 12px 20px;
        }
    }

    /* Touch Target Improvements */
    @media (max-width: 768px) {
        .toggle-summary,
        .change-location-btn,
        .current-location-btn,
        .place-order-btn-mobile {
            min-height: 44px;
            min-width: 44px;
        }

        .payment-method {
            margin-bottom: 10px;
        }

        .suggestion-item {
            min-height: 50px;
        }
    }

    /* Loading State Improvements */
    .place-order-btn.loading,
    .place-order-btn-mobile.loading {
        pointer-events: none;
    }

    /* Focus Improvements */
    @media (max-width: 768px) {
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
        }
    }
</style>
@endpush

@push('scripts')
<!-- Load Google Maps API -->
<script async defer 
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCMJQ89pMJEjMoU97bcZUjC1jFlnDFgtGA&libraries=places&callback=initMap">
</script>

<script>
let autocompleteService;
let geocoder;

function initMap() {
    // Initialize Google Maps services
    autocompleteService = new google.maps.places.AutocompleteService();
    geocoder = new google.maps.Geocoder();
    
    const searchInput = document.getElementById('location_search');
    const suggestionsDiv = document.getElementById('location_suggestions');
    const currentLocationBtn = document.getElementById('current_location_btn');
    const selectedLocationDiv = document.getElementById('selected_location');
    
    let searchTimeout;
    
    // Search input handler
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 3) {
            hideSuggestions();
            return;
        }
        
        searchTimeout = setTimeout(() => {
            searchPlaces(query);
        }, 300);
    });
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.location-picker')) {
            hideSuggestions();
        }
    });
    
    // Current location button
    currentLocationBtn.addEventListener('click', getCurrentLocation);
    
    // Change location button
    document.addEventListener('click', function(e) {
        if (e.target.closest('.change-location-btn')) {
            clearSelectedLocation();
        }
    });
}

function searchPlaces(query) {
    const request = {
        input: query,
        types: ['establishment', 'geocode'],
        componentRestrictions: { country: 'ke' }
    };
    
    autocompleteService.getPlacePredictions(request, (predictions, status) => {
        if (status === google.maps.places.PlacesServiceStatus.OK && predictions) {
            showSuggestions(predictions);
        } else {
            hideSuggestions();
        }
    });
}

function showSuggestions(predictions) {
    const suggestionsDiv = document.getElementById('location_suggestions');
    suggestionsDiv.innerHTML = '';
    
    predictions.forEach(prediction => {
        const item = document.createElement('div');
        item.className = 'suggestion-item';
        item.innerHTML = `
            <i class="fas fa-map-marker-alt suggestion-icon"></i>
            <div class="suggestion-text">
                <div class="suggestion-main">${prediction.structured_formatting.main_text}</div>
                <div class="suggestion-secondary">${prediction.structured_formatting.secondary_text || ''}</div>
            </div>
        `;
        
        item.addEventListener('click', () => selectPlace(prediction.place_id, prediction.description));
        suggestionsDiv.appendChild(item);
    });
    
    suggestionsDiv.style.display = 'block';
}

function hideSuggestions() {
    document.getElementById('location_suggestions').style.display = 'none';
}

function selectPlace(placeId, description) {
    const request = { placeId: placeId };
    
    const service = new google.maps.places.PlacesService(document.createElement('div'));
    service.getDetails(request, (place, status) => {
        if (status === google.maps.places.PlacesServiceStatus.OK) {
            setSelectedLocation(
                place.geometry.location.lat(),
                place.geometry.location.lng(),
                place.formatted_address || description
            );
        }
    });
    
    hideSuggestions();
}

function getCurrentLocation() {
    const btn = document.getElementById('current_location_btn');
    
    if (!navigator.geolocation) {
        alert('Geolocation is not supported by this browser.');
        return;
    }
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span class="btn-text">Getting...</span>';
    
    navigator.geolocation.getCurrentPosition(
        (position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            geocoder.geocode({ location: { lat, lng } }, (results, status) => {
                if (status === 'OK' && results[0]) {
                    setSelectedLocation(lat, lng, results[0].formatted_address);
                } else {
                    setSelectedLocation(lat, lng, `${lat}, ${lng}`);
                }
                
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-crosshairs"></i> <span class="btn-text">Current Location</span>';
            });
        },
        (error) => {
            alert('Unable to get your location. Please search manually.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-crosshairs"></i> <span class="btn-text">Current Location</span>';
        }
    );
}

function setSelectedLocation(lat, lng, address) {
    document.getElementById('selected_lat').value = lat;
    document.getElementById('selected_lng').value = lng;
    document.getElementById('selected_address').value = address;
    document.getElementById('location_search').value = address;
    
    showSelectedLocation(address);
    hideSuggestions();
}

function showSelectedLocation(address) {
    const selectedDiv = document.getElementById('selected_location');
    const locationText = selectedDiv.querySelector('.location-text');
    
    locationText.textContent = address;
    selectedDiv.style.display = 'flex';
    
    // Hide the input and current location button
    document.querySelector('.location-input-wrapper').style.display = 'none';
}

function clearSelectedLocation() {
    document.getElementById('selected_lat').value = '';
    document.getElementById('selected_lng').value = '';
    document.getElementById('selected_address').value = '';
    document.getElementById('location_search').value = '';
    
    document.getElementById('selected_location').style.display = 'none';
    document.querySelector('.location-input-wrapper').style.display = 'flex';
    
    // Focus back on search input
    document.getElementById('location_search').focus();
}

// Toggle mobile summary
function toggleSummary() {
    const summaryDetails = document.getElementById('summaryDetails');
    const toggleIcon = document.querySelector('.toggle-icon');
    
    summaryDetails.classList.toggle('expanded');
    toggleIcon.classList.toggle('rotated');
}

document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }

    // Payment method selection
    const paymentMethods = document.querySelectorAll('input[name="payment[method]"]');
    const mpesaDetails = document.getElementById('mpesa-details');

    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.value === 'mpesa') {
                mpesaDetails.style.display = 'block';
                document.getElementById('mpesa_phone').setAttribute('required', 'required');
            } else {
                mpesaDetails.style.display = 'none';
                document.getElementById('mpesa_phone').removeAttribute('required');
            }
        });
    });

    // Phone number formatting
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.startsWith('0')) {
                value = '254' + value.substring(1);
            } else if (!value.startsWith('254')) {
                value = '254' + value;
            }
            this.value = value;
        });
    });

    // Form submission
    const placeOrderBtnDesktop = document.getElementById('place-order-btn-desktop');
    const placeOrderBtnMobile = document.getElementById('place-order-btn-mobile');
    const checkoutForm = document.getElementById('checkout-form');
    const loadingOverlay = document.getElementById('checkout-loading');
    const messagesContainer = document.getElementById('checkout-messages');

    function showMessage(message, type = 'success') {
        const messageElement = document.createElement('div');
        messageElement.className = `alert alert-${type}`;
        messageElement.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        messagesContainer.appendChild(messageElement);

        messageElement.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });

        setTimeout(() => {
            messageElement.style.opacity = '0';
            setTimeout(() => messageElement.remove(), 300);
        }, 8000);
    }

    function clearMessages() {
        messagesContainer.innerHTML = '';
    }

    function handleOrderSubmission() {
        clearMessages();

        // Check if location is selected
        if (!document.getElementById('selected_address').value) {
            showMessage('Please select your delivery location', 'error');
            return;
        }

        // Basic form validation
        const form = checkoutForm;
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Show loading state
        if (placeOrderBtnDesktop) {
            placeOrderBtnDesktop.classList.add('loading');
            placeOrderBtnDesktop.disabled = true;
        }
        if (placeOrderBtnMobile) {
            placeOrderBtnMobile.classList.add('loading');
            placeOrderBtnMobile.disabled = true;
        }
        loadingOverlay.style.display = 'flex';

        // Collect form data
        const formData = new FormData(form);
        const data = {};

        // Convert FormData to nested object
        for (let [key, value] of formData.entries()) {
            const keys = key.match(/[^[\]]+/g);
            let current = data;

            for (let i = 0; i < keys.length - 1; i++) {
                if (!current[keys[i]]) {
                    current[keys[i]] = {};
                }
                current = current[keys[i]];
            }

            current[keys[keys.length - 1]] = value;
        }

        // Add CSRF token
        data._token = csrfToken;

        // Submit order
        fetch('/checkout/process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');

                    // Update cart count if cart helper is available
                    if (window.cartHelper) {
                        window.cartHelper.updateCartCount(0);
                    }

                    // Redirect to success page
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 2000);
                } else {
                    showMessage(data.message || 'Order processing failed. Please try again.', 'error');

                    // Show validation errors if any
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            data.errors[field].forEach(error => {
                                showMessage(error, 'error');
                            });
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Checkout error:', error);
                showMessage('An unexpected error occurred. Please try again.', 'error');
            })
            .finally(() => {
                // Remove loading state
                if (placeOrderBtnDesktop) {
                    placeOrderBtnDesktop.classList.remove('loading');
                    placeOrderBtnDesktop.disabled = false;
                }
                if (placeOrderBtnMobile) {
                    placeOrderBtnMobile.classList.remove('loading');
                    placeOrderBtnMobile.disabled = false;
                }
                loadingOverlay.style.display = 'none';
            });
    }

    // Attach event listeners to both buttons 
    if (placeOrderBtnDesktop) {
        placeOrderBtnDesktop.addEventListener('click', handleOrderSubmission);
    }
    if (placeOrderBtnMobile) {
        placeOrderBtnMobile.addEventListener('click', handleOrderSubmission);
    }

    console.log('Checkout functionality initialized');
});
</script>
@endpush