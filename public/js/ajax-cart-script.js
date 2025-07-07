// Add to your @push('scripts') section
// AJAX Cart Addition
const cartForms = document.querySelectorAll('.cart-form');

cartForms.forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const productId = this.querySelector('input[name="product_id"]').value;
        const submitBtn = this.querySelector('.btn-add-to-cart');
        
        // Disable button during request
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
        
        fetch('/cart/add', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            // Re-enable button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-cart-plus"></i> Add to Cart';
            
            if (data.success) {
                // Show success message
                const alertContainer = document.createElement('div');
                alertContainer.className = 'alert alert-success';
                alertContainer.innerHTML = `<i class="fas fa-check-circle"></i> ${data.message}`;
                document.querySelector('.category-container').prepend(alertContainer);
                
                // Update cart count
                document.querySelectorAll('.cart-count').forEach(count => {
                    count.textContent = data.cartCount;
                });
                
                // Auto-dismiss alert
                setTimeout(() => {
                    alertContainer.classList.add('fadeOut');
                    setTimeout(() => {
                        alertContainer.remove();
                    }, 500);
                }, 5000);
            } else {
                // Show error message
                const alertContainer = document.createElement('div');
                alertContainer.className = 'alert alert-error';
                alertContainer.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${data.message}`;
                document.querySelector('.category-container').prepend(alertContainer);
                
                // Auto-dismiss alert
                setTimeout(() => {
                    alertContainer.classList.add('fadeOut');
                    setTimeout(() => {
                        alertContainer.remove();
                    }, 500);
                }, 5000);
            }
        })
        .catch(error => {
            // Re-enable button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-cart-plus"></i> Add to Cart';
            
            // Show error message
            console.error('Error:', error);
            const alertContainer = document.createElement('div');
            alertContainer.className = 'alert alert-error';
            alertContainer.innerHTML = '<i class="fas fa-exclamation-circle"></i> Something went wrong. Please try again.';
            document.querySelector('.category-container').prepend(alertContainer);
        });
    });
});