document.addEventListener('DOMContentLoaded', function() {
    // Set up CSRF token for all AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Setup for all cart forms
    const cartForms = document.querySelectorAll('.cart-form');
    
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
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Create notification container if it doesn't exist
                let notificationContainer = document.getElementById('notification-container');
                if (!notificationContainer) {
                    notificationContainer = document.createElement('div');
                    notificationContainer.id = 'notification-container';
                    notificationContainer.className = 'notification-container';
                    document.querySelector('.product-details-container').prepend(notificationContainer);
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
                
                // Update cart count
                const cartCountElements = document.querySelectorAll('.cart-count');
                cartCountElements.forEach(element => {
                    element.textContent = data.cart_count || parseInt(element.textContent || '0') + 1;
                });
                
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
                
                // Create notification container if it doesn't exist
                let notificationContainer = document.getElementById('notification-container');
                if (!notificationContainer) {
                    notificationContainer = document.createElement('div');
                    notificationContainer.id = 'notification-container';
                    notificationContainer.className = 'notification-container';
                    document.querySelector('.product-details-container').prepend(notificationContainer);
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