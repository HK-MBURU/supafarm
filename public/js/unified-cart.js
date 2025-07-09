document.addEventListener('DOMContentLoaded', function() {
    // Set up CSRF token for all AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Setup for all cart forms
    const cartForms = document.querySelectorAll('.cart-form');
    
    // Function to update all cart counts on the page
    function updateAllCartCounts(count) {
        // Update desktop cart count
        const desktopCartCounts = document.querySelectorAll('.cart-count');
        desktopCartCounts.forEach(element => {
            element.textContent = count;
        });
        
        // Update mobile cart count
        const mobileCartCounts = document.querySelectorAll('.mobile-cart-count');
        mobileCartCounts.forEach(element => {
            element.textContent = count;
        });
    }
    
    // Initial load - get cart count from server
    fetch('/cart/count', {
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.cartCount) {
            updateAllCartCounts(data.cartCount);
        }
    })
    .catch(error => {
        console.error('Error fetching cart count:', error);
    });
    
    // Handle cart form submissions
    cartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state on button
            const submitBtn = this.querySelector('.btn-add-to-cart');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
            submitBtn.disabled = true;
            
            // Create form data
            const formData = new FormData(this);
            
            // Send AJAX request
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                
                // Check content type before parsing as JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // If not JSON, throw an error with the text content
                    return response.text().then(text => {
                        throw new Error('Server returned non-JSON response: ' + text.substring(0, 100) + '...');
                    });
                }
            })
            .then(data => {
                // Find an appropriate container for notifications
                let container = null;
                
                // Try different container options in order of preference
                if (document.querySelector('.product-details-container')) {
                    container = document.querySelector('.product-details-container');
                } else if (document.querySelector('.category-container')) {
                    container = document.querySelector('.category-container');
                } else if (document.querySelector('main')) {
                    container = document.querySelector('main');
                } else {
                    // If no suitable container, create one at the top of the body
                    container = document.body;
                    const newContainer = document.createElement('div');
                    newContainer.className = 'notification-wrapper';
                    document.body.prepend(newContainer);
                    container = newContainer;
                }
                
                // Create notification container if it doesn't exist
                let notificationContainer = document.getElementById('notification-container');
                if (!notificationContainer) {
                    notificationContainer = document.createElement('div');
                    notificationContainer.id = 'notification-container';
                    notificationContainer.className = 'notification-container';
                    container.prepend(notificationContainer);
                }
                
                // Create success notification
                const notification = document.createElement('div');
                notification.className = 'notification success';
                notification.innerHTML = `
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <p>${data.message || 'Product added to cart!'}</p>
                        <p class="product-name">${data.product_name || 'Item'}</p>
                    </div>
                    <button type="button" class="close-notification"><i class="fas fa-times"></i></button>
                `;
                
                notificationContainer.prepend(notification);
                
                // Update all cart counts
                const cartCount = data.cartCount || data.cart_count || 0;
                updateAllCartCounts(cartCount);
                
                // Remove notification after delay
                setTimeout(() => {
                    notification.classList.add('fadeOut');
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                }, 5000);
                
                // Add close button functionality
                notification.querySelector('.close-notification').addEventListener('click', function() {
                    notification.classList.add('fadeOut');
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                });
                
                // Reset button
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Find an appropriate container for notifications
                let container = null;
                
                // Try different container options in order of preference
                if (document.querySelector('.product-details-container')) {
                    container = document.querySelector('.product-details-container');
                } else if (document.querySelector('.category-container')) {
                    container = document.querySelector('.category-container');
                } else if (document.querySelector('main')) {
                    container = document.querySelector('main');
                } else {
                    // If no suitable container, create one at the top of the body
                    container = document.body;
                    const newContainer = document.createElement('div');
                    newContainer.className = 'notification-wrapper';
                    document.body.prepend(newContainer);
                    container = newContainer;
                }
                
                // Create notification container if it doesn't exist
                let notificationContainer = document.getElementById('notification-container');
                if (!notificationContainer) {
                    notificationContainer = document.createElement('div');
                    notificationContainer.id = 'notification-container';
                    notificationContainer.className = 'notification-container';
                    container.prepend(notificationContainer);
                }
                
                // Create error notification
                const notification = document.createElement('div');
                notification.className = 'notification error';
                notification.innerHTML = `
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <p>Failed to add product to cart.</p>
                        <p class="details">Please try again later.</p>
                    </div>
                    <button type="button" class="close-notification"><i class="fas fa-times"></i></button>
                `;
                
                notificationContainer.prepend(notification);
                
                // Remove notification after delay
                setTimeout(() => {
                    notification.classList.add('fadeOut');
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                }, 5000);
                
                // Add close button functionality
                notification.querySelector('.close-notification').addEventListener('click', function() {
                    notification.classList.add('fadeOut');
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                });
                
                // Reset button
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            });
        });
    });
});