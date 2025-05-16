/**
 * 88 HOTEL Food Menu JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    const orderItems = document.getElementById('orderItems');
    const orderSummary = document.getElementById('orderSummary');
    const orderTotal = document.getElementById('orderTotal');
    const emptyOrderMessage = document.getElementById('emptyOrderMessage');
    const placeOrderBtn = document.getElementById('placeOrder');
    
    // Cart Object
    const cart = {
        items: [],
        
        addItem: function(id, name, price) {
            const existingItem = this.items.find(item => item.id === id);
            
            if (existingItem) {
                existingItem.quantity++;
            } else {
                this.items.push({
                    id: id,
                    name: name,
                    price: price,
                    quantity: 1
                });
            }
            
            this.updateCart();
        },
        
        removeItem: function(id) {
            const index = this.items.findIndex(item => item.id === id);
            
            if (index !== -1) {
                this.items.splice(index, 1);
                this.updateCart();
            }
        },
        
        updateQuantity: function(id, newQuantity) {
            const item = this.items.find(item => item.id === id);
            
            if (item) {
                item.quantity = Math.max(1, newQuantity);
                this.updateCart();
            }
        },
        
        getTotal: function() {
            return this.items.reduce((total, item) => total + (item.price * item.quantity), 0);
        },
        
        clearCart: function() {
            this.items = [];
            this.updateCart();
        },
        
        updateCart: function() {
            if (orderItems) {
                // Clear current items
                orderItems.innerHTML = '';
                
                if (this.items.length === 0) {
                    if (emptyOrderMessage) {
                        emptyOrderMessage.style.display = 'block';
                    }
                    if (orderSummary) {
                        orderSummary.style.display = 'none';
                    }
                    return;
                }
                
                if (emptyOrderMessage) {
                    emptyOrderMessage.style.display = 'none';
                }
                if (orderSummary) {
                    orderSummary.style.display = 'block';
                }
                
                // Add each item to the cart display
                this.items.forEach(item => {
                    const itemElement = document.createElement('div');
                    itemElement.className = 'order-item';
                    itemElement.innerHTML = `
                        <div class="order-item-name">${item.name}</div>
                        <div class="order-item-price">$${(item.price * item.quantity).toFixed(2)}</div>
                        <div class="order-item-quantity">
                            <button class="quantity-btn decrease-quantity" data-id="${item.id}">-</button>
                            <span class="quantity-value">${item.quantity}</span>
                            <button class="quantity-btn increase-quantity" data-id="${item.id}">+</button>
                        </div>
                        <button class="remove-item" data-id="${item.id}">×</button>
                    `;
                    
                    orderItems.appendChild(itemElement);
                });
                
                // Update event listeners for quantity buttons
                const decreaseButtons = document.querySelectorAll('.decrease-quantity');
                const increaseButtons = document.querySelectorAll('.increase-quantity');
                const removeButtons = document.querySelectorAll('.remove-item');
                
                decreaseButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        const id = button.getAttribute('data-id');
                        const item = this.items.find(item => item.id === id);
                        if (item && item.quantity > 1) {
                            this.updateQuantity(id, item.quantity - 1);
                        }
                    });
                });
                
                increaseButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        const id = button.getAttribute('data-id');
                        const item = this.items.find(item => item.id === id);
                        if (item) {
                            this.updateQuantity(id, item.quantity + 1);
                        }
                    });
                });
                
                removeButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        const id = button.getAttribute('data-id');
                        this.removeItem(id);
                    });
                });
                
                // Update total
                if (orderTotal) {
                    orderTotal.textContent = `$${this.getTotal().toFixed(2)}`;
                }
            }
        },
        
        saveToLocalStorage: function() {
            localStorage.setItem('foodCart', JSON.stringify(this.items));
        },
        
        loadFromLocalStorage: function() {
            const savedCart = localStorage.getItem('foodCart');
            if (savedCart) {
                this.items = JSON.parse(savedCart);
                this.updateCart();
            }
        }
    };
    
    // Load cart from localStorage on page load
    cart.loadFromLocalStorage();
    
    // Add to cart buttons
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    if (addToCartButtons.length > 0) {
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const price = parseFloat(this.getAttribute('data-price'));
                
                if (id && name && !isNaN(price)) {
                    // Add item to cart
                    cart.addItem(id, name, price);
                    
                    // Save cart to localStorage
                    cart.saveToLocalStorage();
                    
                    // Show added animation
                    this.classList.add('added');
                    setTimeout(() => {
                        this.classList.remove('added');
                    }, 1000);
                    
                    // Scroll to order section if not visible
                    const orderSection = document.getElementById('currentOrder');
                    if (orderSection) {
                        const sectionRect = orderSection.getBoundingClientRect();
                        if (sectionRect.bottom < 0 || sectionRect.top > window.innerHeight) {
                            orderSection.scrollIntoView({ behavior: 'smooth' });
                        }
                    }
                }
            });
        });
    }
    
    // Place order button
    if (placeOrderBtn) {
        placeOrderBtn.addEventListener('click', function() {
            if (cart.items.length === 0) {
                alert('Your order is empty. Please add items to your order.');
                return;
            }
            
            // Prepare order data for submission
            const orderData = {
                items: cart.items,
                total: cart.getTotal()
            };
            
            // Submit order via AJAX
            fetch('place-food-order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(orderData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Clear cart
                    cart.clearCart();
                    cart.saveToLocalStorage();
                    
                    // Show success message
                    alert('Your order has been placed successfully!');
                    
                    // Redirect to order confirmation page
                    window.location.href = 'order-confirmation.php?id=' + data.order_id;
                } else {
                    alert(data.message || 'Failed to place order. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error placing order:', error);
                alert('An error occurred. Please try again later.');
            });
        });
    }
    
    // Category tabs
    const categoryTabs = document.querySelectorAll('.category-tab');
    
    if (categoryTabs.length > 0) {
        categoryTabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                // Don't reload if already on the selected category
                if (this.classList.contains('active')) {
                    e.preventDefault();
                }
            });
        });
    }
});