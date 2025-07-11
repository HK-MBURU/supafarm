@extends('layouts.app')

@section('content')
<div class="success-container">
    <div class="success-content">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1 class="success-title">Order Placed Successfully!</h1>
        <p class="success-subtitle">Thank you for your order. We'll send you a confirmation email shortly.</p>
        
        <div class="order-summary-card">
            <div class="order-header">
                <h3>Order Summary</h3>
                <div class="order-details">
                    <div class="order-detail">
                        <span class="label">Order Number:</span>
                        <span class="value">#{{ $order->order_number }}</span>
                    </div>
                    <div class="order-detail">
                        <span class="label">Order Date:</span>
                        <span class="value">{{ $order->created_at->format('F j, Y g:i A') }}</span>
                    </div>
                    <div class="order-detail">
                        <span class="label">Payment Method:</span>
                        <span class="value">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</span>
                    </div>
                    <div class="order-detail">
                        <span class="label">Status:</span>
                        <span class="badge badge-{{ $order->status_badge_class }}">{{ ucfirst($order->status) }}</span>
                    </div>
                </div>
            </div>
            
            <div class="order-items">
                <h4>Items Ordered</h4>
                @foreach($orderItems as $item)
                <div class="order-item">
                    <div class="item-image">
                        @if($item->product && $item->product->image_url)
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                        @else
                        <div class="no-image"><i class="fas fa-image"></i></div>
                        @endif
                    </div>
                    <div class="item-details">
                        <h5>{{ $item->product->name ?? $item->product_name ?? 'Product' }}</h5>
                        <p class="item-quantity">Quantity: {{ $item->quantity }}</p>
                        <p class="item-price">KSh {{ number_format($item->price, 2) }} each</p>
                    </div>
                    <div class="item-total">
                        KSh {{ number_format($item->total ?? ($item->quantity * $item->price), 2) }}
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="order-totals">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>KSh {{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Shipping:</span>
                    <span>
                        @if($order->shipping_cost > 0)
                            KSh {{ number_format($order->shipping_cost, 2) }}
                        @else
                            <span class="free-shipping">FREE</span>
                        @endif
                    </span>
                </div>
                <div class="total-row">
                    <span>Tax (16% VAT):</span>
                    <span>KSh {{ number_format($order->tax_amount, 2) }}</span>
                </div>
                <div class="total-row total-final">
                    <span>Total Paid:</span>
                    <span>KSh {{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
        
        <div class="delivery-info">
            <div class="info-section">
                <h4><i class="fas fa-truck"></i> Delivery Information</h4>
                
                @if($order->shipping_address)
                <div class="address-card">
                    <h5>Delivery Address:</h5>
                    @if(is_array($order->shipping_address))
                        @if(isset($order->shipping_address['full_address']))
                            <p><i class="fas fa-map-marker-alt"></i> {{ $order->shipping_address['full_address'] }}</p>
                        @else
                            @if(isset($order->shipping_address['address']))
                                <p>{{ $order->shipping_address['address'] }}</p>
                            @endif
                            @if(isset($order->shipping_address['city']) && isset($order->shipping_address['state']))
                                <p>{{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }}</p>
                            @endif
                            @if(isset($order->shipping_address['zipcode']))
                                <p>{{ $order->shipping_address['zipcode'] }}</p>
                            @endif
                            @if(isset($order->shipping_address['country']))
                                <p>{{ $order->shipping_address['country'] }}</p>
                            @endif
                        @endif
                        
                        @if(isset($order->shipping_address['name']))
                            <p><strong>Recipient:</strong> {{ $order->shipping_address['name'] }}</p>
                        @endif
                        @if(isset($order->shipping_address['phone']))
                            <p><strong>Phone:</strong> {{ $order->shipping_address['phone'] }}</p>
                        @endif
                    @else
                        <p><i class="fas fa-map-marker-alt"></i> {{ $order->shipping_address }}</p>
                    @endif
                    
                    @if($order->delivery_latitude && $order->delivery_longitude)
                        <div class="coordinates-info">
                            <small class="text-muted">
                                <i class="fas fa-crosshairs"></i>
                                GPS: {{ number_format($order->delivery_latitude, 6) }}, {{ number_format($order->delivery_longitude, 6) }}
                            </small>
                        </div>
                    @endif
                </div>
                @endif
                
                @if($order->delivery_instructions)
                <div class="delivery-instructions">
                    <h5><i class="fas fa-sticky-note"></i> Delivery Instructions:</h5>
                    <p>{{ $order->delivery_instructions }}</p>
                </div>
                @endif
                
                @if($order->estimated_delivery_at)
                <div class="delivery-estimate">
                    <h5><i class="fas fa-clock"></i> Estimated Delivery:</h5>
                    <p>{{ $order->estimated_delivery_at->format('F j, Y g:i A') }}</p>
                    <small class="delivery-time">{{ $order->estimated_delivery_time }}</small>
                </div>
                @endif
                
                @if($order->delivery_status && $order->delivery_status !== 'pending')
                <div class="delivery-status">
                    <h5><i class="fas fa-shipping-fast"></i> Delivery Status:</h5>
                    <span class="badge badge-{{ $order->delivery_status_badge_class }}">
                        {{ $order->delivery_status_text }}
                    </span>
                    @if($order->delivery_person_name)
                        <p class="delivery-person">
                            <strong>Delivery Person:</strong> {{ $order->delivery_person_name }}
                            @if($order->delivery_person_phone)
                                <br><strong>Phone:</strong> {{ $order->delivery_person_phone }}
                            @endif
                        </p>
                    @endif
                </div>
                @endif
                
                @if($order->payment_method === 'cash_on_delivery')
                <div class="cod-notice">
                    <i class="fas fa-info-circle"></i>
                    <span>You have selected Cash on Delivery. Please have the exact amount (KSh {{ number_format($order->total_amount, 2) }}) ready when your order arrives.</span>
                </div>
                @endif
                
                @if($order->payment_method === 'bank_transfer')
                <div class="bank-details">
                    <h5><i class="fas fa-university"></i> Bank Transfer Details</h5>
                    <div class="bank-info">
                        <p><strong>Bank:</strong> Your Bank Name</p>
                        <p><strong>Account Name:</strong> Your Store Name</p>
                        <p><strong>Account Number:</strong> 1234567890</p>
                        <p><strong>Reference:</strong> {{ $order->order_number }}</p>
                        <p class="bank-note">Please use the order number as your reference when making the transfer.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <div class="next-steps">
            <h4><i class="fas fa-clipboard-list"></i> What's Next?</h4>
            <div class="steps-grid">
                <div class="step">
                    <div class="step-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h5>Order Confirmation</h5>
                    <p>You'll receive an email confirmation with your order details within a few minutes.</p>
                </div>
                
                <div class="step">
                    <div class="step-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <h5>Order Processing</h5>
                    <p>We'll prepare your order for delivery within 1-2 business hours.</p>
                </div>
                
                <div class="step">
                    <div class="step-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h5>Delivery Updates</h5>
                    <p>You'll receive real-time updates as your order makes its way to you.</p>
                </div>
                
                <div class="step">
                    <div class="step-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h5>Delivery</h5>
                    <p>Your order will be delivered to your specified location.</p>
                </div>
            </div>
        </div>
        
        @if($order->progress_percentage)
        <div class="order-progress">
            <h4><i class="fas fa-chart-line"></i> Order Progress</h4>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $order->progress_percentage }}%"></div>
            </div>
            <p class="progress-text">{{ $order->progress_percentage }}% Complete</p>
        </div>
        @endif
        
        <div class="action-buttons">
            <a href="/" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i>
                Continue Shopping
            </a>
            
            <button onclick="window.print()" class="btn btn-secondary">
                <i class="fas fa-print"></i>
                Print Order
            </button>
            
            @auth
            <a href="/orders" class="btn btn-outline">
                <i class="fas fa-list"></i>
                View All Orders
            </a>
            @endauth
            
            @if($order->canBeCancelled())
            <button onclick="cancelOrder('{{ $order->id }}')" class="btn btn-danger">
                <i class="fas fa-times"></i>
                Cancel Order
            </button>
            @endif
        </div>
        
        <div class="contact-support">
            <h5><i class="fas fa-headset"></i> Need Help?</h5>
            <p>If you have any questions about your order, feel free to contact our support team.</p>
            <div class="contact-methods">
                <a href="mailto:support@yourstore.com" class="contact-method">
                    <i class="fas fa-envelope"></i>
                    support@yourstore.com
                </a>
                <a href="tel:+254712345678" class="contact-method">
                    <i class="fas fa-phone"></i>
                    +254 712 345 678
                </a>
                <a href="/support" class="contact-method">
                    <i class="fas fa-comment"></i>
                    Live Chat
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.success-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px 15px;
}

.success-content {
    text-align: center;
}

.success-icon {
    margin-bottom: 20px;
}

.success-icon i {
    font-size: 4rem;
    color: #28a745;
    animation: successPulse 2s ease-in-out;
}

@keyframes successPulse {
    0% {
        transform: scale(0.8);
        opacity: 0.5;
    }
    50% {
        transform: scale(1.1);
        opacity: 1;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.success-title {
    font-size: 2rem;
    color: var(--dark-color);
    margin-bottom: 10px;
}

.success-subtitle {
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 30px;
}

.order-summary-card {
    background-color: white;
    border-radius: 8px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: left;
}

.order-header h3 {
    color: var(--primary-color);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.order-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.order-detail {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.order-detail .label {
    font-weight: 500;
    color: #666;
}

.order-detail .value {
    font-weight: bold;
    color: var(--dark-color);
}

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: bold;
    text-transform: uppercase;
}

.badge-warning { background-color: #fff3cd; color: #856404; }
.badge-info { background-color: #d1ecf1; color: #0c5460; }
.badge-success { background-color: #d4edda; color: #155724; }
.badge-primary { background-color: #d6e9ff; color: #004085; }
.badge-secondary { background-color: #e2e3e5; color: #383d41; }
.badge-danger { background-color: #f8d7da; color: #721c24; }

.order-items h4 {
    color: var(--dark-color);
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

.order-item {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
}

.order-item:last-child {
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

.item-details h5 {
    margin: 0 0 5px 0;
    color: var(--dark-color);
    font-size: 1rem;
}

.item-quantity,
.item-price {
    margin: 0;
    font-size: 0.9rem;
    color: #666;
}

.item-total {
    font-weight: bold;
    color: var(--primary-color);
    font-size: 1.1rem;
}

.order-totals {
    border-top: 1px solid #eee;
    padding-top: 15px;
    margin-top: 20px;
}

.total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 0.95rem;
}

.total-row.total-final {
    font-size: 1.2rem;
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

.delivery-info {
    margin-bottom: 30px;
}

.info-section {
    background-color: white;
    border-radius: 8px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: left;
}

.info-section h4 {
    color: var(--primary-color);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.address-card,
.delivery-instructions,
.delivery-estimate,
.delivery-status {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 15px;
}

.address-card h5,
.delivery-instructions h5,
.delivery-estimate h5,
.delivery-status h5 {
    color: var(--dark-color);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.address-card p,
.delivery-instructions p,
.delivery-estimate p {
    margin: 5px 0;
    color: #666;
}

.coordinates-info {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #e9ecef;
}

.delivery-time {
    color: var(--primary-color);
    font-weight: 500;
}

.delivery-person {
    margin-top: 10px;
    padding: 10px;
    background-color: #e3f2fd;
    border-radius: 4px;
}

.cod-notice {
    background-color: #fff3cd;
    color: #856404;
    padding: 12px;
    border-radius: 6px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-top: 15px;
}

.bank-details {
    background-color: #e3f2fd;
    padding: 15px;
    border-radius: 6px;
    margin-top: 15px;
}

.bank-details h5 {
    color: var(--primary-color);
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.bank-info p {
    margin: 8px 0;
    color: var(--dark-color);
}

.bank-note {
    font-style: italic;
    color: #666 !important;
    margin-top: 10px !important;
}

.next-steps {
    background-color: white;
    border-radius: 8px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: left;
}

.next-steps h4 {
    color: var(--primary-color);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.steps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.step {
    text-align: center;
    padding: 15px;
}

.step-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-size: 1.2rem;
}

.step h5 {
    color: var(--dark-color);
    margin-bottom: 8px;
    font-size: 1rem;
}

.step p {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.4;
}

.order-progress {
    background-color: white;
    border-radius: 8px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.order-progress h4 {
    color: var(--primary-color);
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.progress-bar {
    width: 100%;
    height: 20px;
    background-color: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 10px;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--primary-color), #28a745);
    transition: width 0.3s ease;
}

.progress-text {
    text-align: center;
    color: var(--dark-color);
    font-weight: 500;
    margin: 0;
}

.action-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 24px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 0.95rem;
}

.btn-primary {
    background-color: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background-color: var(--dark-color);
    transform: translateY(-1px);
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #545b62;
    transform: translateY(-1px);
}

.btn-outline {
    background-color: transparent;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
}

.btn-outline:hover {
    background-color: var(--primary-color);
    color: white;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.btn-danger:hover {
    background-color: #c82333;
    transform: translateY(-1px);
}

.contact-support {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
}

.contact-support h5 {
    color: var(--dark-color);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.contact-support p {
    color: #666;
    margin-bottom: 15px;
}

.contact-methods {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.contact-method {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--primary-color);
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.contact-method:hover {
    background-color: rgba(0, 123, 255, 0.1);
}

/* Print Styles */
@media print {
    .action-buttons,
    .contact-support {
        display: none;
    }
    
    .success-container {
        max-width: none;
        padding: 0;
    }
    
    .order-summary-card,
    .delivery-info .info-section,
    .next-steps {
        box-shadow: none;
        border: 1px solid #ddd;
        break-inside: avoid;
    }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .order-details {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .order-detail {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    .steps-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }
    
    .contact-methods {
        flex-direction: column;
        gap: 10px;
    }
}

@media (max-width: 480px) {
    .success-title {
        font-size: 1.5rem;
    }
    
    .success-subtitle {
        font-size: 1rem;
    }
    
    .order-summary-card,
    .info-section,
    .next-steps {
        padding: 20px;
    }
    
    .order-item {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }
    
    .item-image {
        margin-right: 0;
        margin-bottom: 10px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add animation to success elements
    const successElements = document.querySelectorAll('.order-summary-card, .delivery-info, .next-steps');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });
    
    successElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(element);
    });
    
    // Update cart count to 0 if cart helper is available
    if (window.cartHelper) {
        window.cartHelper.updateCartCount(0);
    }
    
    console.log('Order success page initialized');
});

// Cancel order function
function cancelOrder(orderId) {
    if (confirm('Are you sure you want to cancel this order? This action cannot be undone.')) {
        fetch(`/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Order cancelled successfully.');
                location.reload();
            } else {
                alert('Failed to cancel order: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while cancelling the order.');
        });
    }
}
</script>
@endpush