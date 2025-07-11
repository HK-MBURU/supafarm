/**
 * Global Cart Helper Functions
 * Include this file in your main layout for cart functionality across the site
 */

class CartHelper {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.init();
    }

    init() {
        if (!this.csrfToken) {
            console.error('CSRF token not found. Make sure to include it in your layout.');
            return;
        }
        
        // Initialize event listeners for add to cart buttons
        this.initAddToCartButtons();
        
        // Initialize cart count display
        this.initCartCountDisplay();
        
        console.log('Cart Helper initialized');
    }

    // Initialize add to cart buttons throughout the site
    initAddToCartButtons() {
        document.addEventListener('click', (e) => {
            if (e.target.closest('.add-to-cart-btn')) {
                e.preventDefault();
                const button = e.target.closest('.add-to-cart-btn');
                this.handleAddToCart(button);
            }
        });
    }

    // Handle add to cart functionality
    handleAddToCart(button) {
        const productId = button.getAttribute('data-product-id');
        const quantity = button.getAttribute('data-quantity') || 1;
        const productName = button.getAttribute('data-product-name') || 'Product';

        if (!productId) {
            this.showNotification('Product ID not found', 'error');
            return;
        }

        // Add loading state to button
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
        button.disabled = true;

        this.makeAjaxRequest('/cart/add', 'POST', {
            product_id: productId,
            quantity: parseInt(quantity)
        })
        .then(data => {
            if (data.success) {
                this.showNotification(`${productName} added to cart!`, 'success');
                this.updateCartCount(data.cartCount);
                
                // Optional: Show mini cart or update cart icon
                this.animateCartIcon();
            } else {
                this.showNotification(data.message || 'Failed to add product to cart', 'error');
            }
        })
        .catch(error => {
            console.error('Add to cart error:', error);
            this.showNotification('Failed to add product to cart', 'error');
        })
        .finally(() => {
            // Restore button state
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }

    // Initialize cart count display elements
    initCartCountDisplay() {
        // Update cart count on page load
        this.fetchCartCount();
    }

    // Fetch current cart count from server
    fetchCartCount() {
        this.makeAjaxRequest('/cart/count', 'GET')
        .then(data => {
            this.updateCartCount(data.cartCount);
        })
        .catch(error => {
            console.error('Failed to fetch cart count:', error);
        });
    }

    // Update cart count in all relevant elements
    updateCartCount(count) {
        const cartCountElements = document.querySelectorAll('.cart-count, .cart-badge, [data-cart-count]');
        cartCountElements.forEach(element => {
            element.textContent = count;
            
            // Hide/show based on count
            if (count > 0) {
                element.style.display = '';
                element.classList.remove('hidden');
            } else {
                element.style.display = 'none';
                element.classList.add('hidden');
            }
        });

        // Update cart icons
        const cartIcons = document.querySelectorAll('.cart-icon');
        cartIcons.forEach(icon => {
            if (count > 0) {
                icon.classList.add('has-items');
            } else {
                icon.classList.remove('has-items');
            }
        });
    }

    // Animate cart icon when item is added
    animateCartIcon() {
        const cartIcons = document.querySelectorAll('.cart-icon, .cart-button');
        cartIcons.forEach(icon => {
            icon.classList.add('cart-bounce');
            setTimeout(() => {
                icon.classList.remove('cart-bounce');
            }, 600);
        });
    }

    // Show notification/toast message
    showNotification(message, type = 'success') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.cart-notification');
        existingNotifications.forEach(notification => notification.remove());

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `cart-notification cart-notification-${type}`;
        notification.innerHTML = `
            <div class="cart-notification-content">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                <span>${message}</span>
                <button class="cart-notification-close">&times;</button>
            </div>
        `;

        // Add styles if not already present
        if (!document.querySelector('#cart-notification-styles')) {
            const styles = document.createElement('style');
            styles.id = 'cart-notification-styles';
            styles.textContent = `
                .cart-notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: white;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    z-index: 10000;
                    max-width: 400px;
                    animation: slideInRight 0.3s ease-out;
                }
                
                .cart-notification-success {
                    border-left: 4px solid #4caf50;
                }
                
                .cart-notification-error {
                    border-left: 4px solid #f44336;
                }
                
                .cart-notification-content {
                    padding: 16px;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }
                
                .cart-notification-success i {
                    color: #4caf50;
                }
                
                .cart-notification-error i {
                    color: #f44336;
                }
                
                .cart-notification-close {
                    background: none;
                    border: none;
                    font-size: 18px;
                    cursor: pointer;
                    color: #999;
                    margin-left: auto;
                }
                
                .cart-notification-close:hover {
                    color: #666;
                }
                
                @keyframes slideInRight {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                
                @keyframes slideOutRight {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                }
                
                .cart-bounce {
                    animation: cartBounce 0.6s ease-in-out;
                }
                
                @keyframes cartBounce {
                    0%, 100% { transform: scale(1); }
                    50% { transform: scale(1.2); }
                }
            `;
            document.head.appendChild(styles);
        }

        // Add to page
        document.body.appendChild(notification);

        // Add close functionality
        const closeBtn = notification.querySelector('.cart-notification-close');
        closeBtn.addEventListener('click', () => {
            this.hideNotification(notification);
        });

        // Auto-hide after 5 seconds
        setTimeout(() => {
            this.hideNotification(notification);
        }, 5000);
    }

    // Hide notification with animation
    hideNotification(notification) {
        notification.style.animation = 'slideOutRight 0.3s ease-in';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }

    // Helper method for AJAX requests
    makeAjaxRequest(url, method, data = null) {
        const options = {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                'Accept': 'application/json',
            }
        };

        if (data && method !== 'GET') {
            options.body = JSON.stringify(data);
        }

        return fetch(url, options)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            });
    }

    // Quick add to cart method for external use
    addToCart(productId, quantity = 1, productName = 'Product') {
        return this.makeAjaxRequest('/cart/add', 'POST', {
            product_id: productId,
            quantity: quantity
        })
        .then(data => {
            if (data.success) {
                this.showNotification(`${productName} added to cart!`, 'success');
                this.updateCartCount(data.cartCount);
                this.animateCartIcon();
                return data;
            } else {
                this.showNotification(data.message || 'Failed to add product to cart', 'error');
                throw new Error(data.message);
            }
        });
    }
}

// Initialize cart helper when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.cartHelper = new CartHelper();
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CartHelper;
}