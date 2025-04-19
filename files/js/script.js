// Cache DOM elements
const cartIcon = document.getElementById('cartIcon');
const cartDropdown = document.getElementById('cartDropdown');
const profileTrigger = document.getElementById('profileTrigger');
const profileMenu = document.getElementById('profileMenu');
const header = document.querySelector('header');

// Debounce function for performance optimization
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Scroll handler for header shadow
const handleScroll = debounce(() => {
    if (window.scrollY > 0) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
}, 10);

document.addEventListener('DOMContentLoaded', function() {
    // Add scroll event listener
    window.addEventListener('scroll', handleScroll);

    // Toggle dropdowns
    const toggleDropdown = (dropdown, otherDropdown) => {
        dropdown.classList.toggle('show');
        if (otherDropdown.classList.contains('show')) {
            otherDropdown.classList.remove('show');
        }
    };

    // Event listeners for dropdowns
    cartIcon?.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleDropdown(cartDropdown, profileMenu);
    });

    profileTrigger?.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleDropdown(profileMenu, cartDropdown);
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
        if (!cartIcon?.contains(e.target) && !cartDropdown?.contains(e.target)) {
            cartDropdown?.classList.remove('show');
        }
        if (!profileTrigger?.contains(e.target) && !profileMenu?.contains(e.target)) {
            profileMenu?.classList.remove('show');
        }
    });

    // Add event listeners using event delegation
    document.addEventListener('click', (e) => {
        // Add to cart
        if (e.target.closest('.add-to-cart')) {
            const button = e.target.closest('.add-to-cart');
            addToCart(button.dataset.id);
        }
        
        // Add to wishlist
        if (e.target.closest('.add-to-wishlist')) {
            const button = e.target.closest('.add-to-wishlist');
            addToWishlist(button.dataset.id);
        }
        
        // Remove from cart
        if (e.target.closest('.cart-item-remove')) {
            const button = e.target.closest('.cart-item-remove');
            removeFromCart(button.dataset.id);
        }
    });
});

// API request handler
async function makeRequest(url, bookId) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `book_id=${bookId}`
        });
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
        return null;
    }
}

// Add to cart function
async function addToCart(bookId) {
    const data = await makeRequest('files/php/add_to_cart.php', bookId);
    if (!data) return;

    if (data.success) {
        updateCartUI(data);
        showNotification('Book added to cart successfully!', 'success');
    } else {
        showNotification(data.message || 'Failed to add book to cart', 'error');
    }
}

// Add to wishlist function
async function addToWishlist(bookId) {
    const data = await makeRequest('files/php/add_to_wishlist.php', bookId);
    if (!data) return;

    if (data.success) {
        const wishlistButton = document.querySelector(`.add-to-wishlist[data-id="${bookId}"]`);
        wishlistButton?.classList.add('active');
        showNotification('Book added to wishlist successfully!', 'success');
    } else {
        showNotification(data.message || 'Failed to add book to wishlist', 'error');
    }
}

// Remove from cart function
async function removeFromCart(bookId) {
    const data = await makeRequest('files/php/remove_from_cart.php', bookId);
    if (!data) return;

    if (data.success) {
        updateCartUI(data);
        showNotification('Book removed from cart successfully!', 'success');
    } else {
        showNotification(data.message || 'Failed to remove book from cart', 'error');
    }
}

// Update cart UI
function updateCartUI(data) {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) cartCount.textContent = data.cartCount;
    
    if (cartDropdown) {
        if (data.cartHtml) {
            cartDropdown.innerHTML = data.cartHtml;
        } else if (data.cartCount === 0) {
            cartDropdown.innerHTML = `
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Your cart is empty</p>
                </div>
            `;
        }
    }
}

// Show notification
function showNotification(message, type = 'success') {
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    const notification = document.createElement('div');
    notification.className = `notification ${type} fade-in`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
} 