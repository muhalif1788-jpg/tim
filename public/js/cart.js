// Cart Manager
class CartManager {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        this.baseUrl = window.location.origin;
        console.log('Cart Manager initialized - Base URL:', this.baseUrl);
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // Quantity buttons
        document.addEventListener('click', (e) => {
            const button = e.target.closest('.quantity-btn');
            if (button) {
                const cartId = button.dataset.cartId;
                const action = button.dataset.action;
                
                console.log(`Button clicked: ${action} for cart ${cartId}`);
                
                if (action === 'increase') {
                    this.increaseQuantity(cartId);
                } else if (action === 'decrease') {
                    this.decreaseQuantity(cartId);
                }
            }
            
            // Remove buttons
            if (e.target.closest('.btn-remove')) {
                const button = e.target.closest('.btn-remove');
                const cartId = button.dataset.cartId;
                if (cartId) {
                    e.preventDefault();
                    this.confirmDeleteItem(cartId);
                }
            }
            
            // Clear cart button
            if (e.target.closest('#clear-cart-btn')) {
                e.preventDefault();
                this.confirmClearCart();
            }
        });
        
        // Checkout button validation
        const checkoutBtn = document.getElementById('checkout-btn');
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', (e) => {
                if (!this.validateCart()) {
                    e.preventDefault();
                }
            });
        }
    }

    // Format number
    formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Get current quantity
    getCurrentQuantity(cartId) {
        const display = document.getElementById(`quantity-display-${cartId}`);
        return display ? parseInt(display.textContent) || 1 : 1;
    }

    // Get max stock
    getProductMaxStock(cartId) {
        const cartItem = document.getElementById(`cart-item-${cartId}`);
        if (!cartItem) return 99;
        
        const maxStock = parseInt(cartItem.dataset.maxStock) || 99;
        return Math.max(1, Math.min(maxStock, 99));
    }

    // Update button states
    updateButtonStates(cartId, quantity, maxStock) {
        const btnMinus = document.querySelector(`[data-cart-id="${cartId}"][data-action="decrease"]`);
        const btnPlus = document.querySelector(`[data-cart-id="${cartId}"][data-action="increase"]`);
        
        if (btnMinus) {
            btnMinus.disabled = quantity <= 1;
            btnMinus.style.opacity = quantity <= 1 ? '0.5' : '1';
        }
        
        if (btnPlus) {
            btnPlus.disabled = quantity >= maxStock;
            btnPlus.style.opacity = quantity >= maxStock ? '0.5' : '1';
        }
    }

    // Increase quantity
    async increaseQuantity(cartId) {
        console.log(`Increasing quantity for cart ${cartId}`);
        const currentQuantity = this.getCurrentQuantity(cartId);
        const maxStock = this.getProductMaxStock(cartId);

        if (currentQuantity >= maxStock) {
            this.showAlert('Stok Terbatas', `Stok maksimum: ${maxStock}`, 'warning');
            return;
        }

        const newQuantity = currentQuantity + 1;
        await this.updateCartQuantity(cartId, newQuantity);
    }

    // Decrease quantity
    async decreaseQuantity(cartId) {
        console.log(`Decreasing quantity for cart ${cartId}`);
        const currentQuantity = this.getCurrentQuantity(cartId);
        
        if (currentQuantity <= 1) {
            this.confirmDeleteItem(cartId);
            return;
        }

        const newQuantity = currentQuantity - 1;
        await this.updateCartQuantity(cartId, newQuantity);
    }

    // Update cart quantity via AJAX
    async updateCartQuantity(cartId, newQuantity) {
        const display = document.getElementById(`quantity-display-${cartId}`);
        if (!display) {
            console.error(`Display element not found for cart ${cartId}`);
            return;
        }
        
        const oldQuantity = parseInt(display.textContent);
        const maxStock = this.getProductMaxStock(cartId);
        
        // Validate before sending
        if (newQuantity > maxStock) {
            this.showAlert('Stok Terbatas', `Stok maksimum: ${maxStock}`, 'warning');
            return;
        }

        if (newQuantity < 1) {
            this.confirmDeleteItem(cartId);
            return;
        }
        
        try {
            // Show loading
            display.classList.add('loading');
            display.textContent = newQuantity;
            
            // Update button states immediately
            this.updateButtonStates(cartId, newQuantity, maxStock);
            
            console.log(`Updating cart ${cartId} to quantity ${newQuantity}`);
            
            const response = await fetch(`${this.baseUrl}/cart/update/${cartId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ quantity: newQuantity })
            });

            console.log(`Response status: ${response.status}`);
            
            if (!response.ok) {
                let errorMessage = `HTTP error: ${response.status}`;
                try {
                    const errorData = await response.json();
                    errorMessage = errorData.message || errorMessage;
                } catch (e) {}
                throw new Error(errorMessage);
            }

            const data = await response.json();
            console.log('Response data:', data);
            
            if (data.success) {
                // Update subtotal for this item
                const priceElement = document.getElementById(`price-${cartId}`);
                if (priceElement) {
                    const price = parseFloat(priceElement.dataset.price);
                    const newSubtotal = price * newQuantity;
                    const subtotalElement = document.getElementById(`subtotal-${cartId}`);
                    if (subtotalElement) {
                        subtotalElement.textContent = `Subtotal: Rp ${this.formatNumber(newSubtotal)}`;
                    }
                }
                
                // Update summary from server data
                this.updateSummaryFromServer(data.summary);
                
                // Update total items
                this.updateTotalItems(data.cart_count);
                
                // Update stock info display
                this.updateStockInfo(cartId, newQuantity, maxStock);
                
                this.showToast('Jumlah berhasil diperbarui', 'success');
                
            } else {
                // Rollback UI
                display.textContent = oldQuantity;
                this.updateButtonStates(cartId, oldQuantity, maxStock);
                this.showAlert('Gagal', data.message || 'Terjadi kesalahan', 'error');
            }
        } catch (error) {
            console.error('Error updating quantity:', error);
            // Rollback UI
            display.textContent = oldQuantity;
            this.updateButtonStates(cartId, oldQuantity, maxStock);
            this.showAlert('Error', error.message || 'Gagal memperbarui jumlah. Silakan coba lagi.', 'error');
        } finally {
            // Remove loading
            display.classList.remove('loading');
        }
    }

    // Update summary from server data
    updateSummaryFromServer(summary) {
        if (!summary) return;
        
        console.log('Updating summary from server:', summary);
        
        // Update subtotal
        const subtotalElement = document.getElementById('subtotal-amount');
        if (subtotalElement && summary.subtotal !== undefined) {
            subtotalElement.textContent = `Rp ${this.formatNumber(summary.subtotal)}`;
        }
        
        // Update shipping
        const shippingElement = document.getElementById('shipping-amount');
        if (shippingElement && summary.shipping !== undefined) {
            shippingElement.innerHTML = summary.shipping === 0 ? 
                '<span class="free-shipping">Gratis</span>' : 
                `Rp ${this.formatNumber(summary.shipping)}`;
        }
        
        // Update discount
        const discountElement = document.getElementById('discount-amount');
        if (discountElement && summary.discount !== undefined) {
            if (summary.discount > 0) {
                discountElement.textContent = `- Rp ${this.formatNumber(summary.discount)}`;
                discountElement.style.display = 'block';
            } else {
                discountElement.style.display = 'none';
            }
        }
        
        // Update grand total
        const grandTotalElement = document.getElementById('grand-total-amount');
        if (grandTotalElement && summary.grand_total !== undefined) {
            grandTotalElement.textContent = `Rp ${this.formatNumber(summary.grand_total)}`;
        }
    }

    // Confirm delete item
    async confirmDeleteItem(cartId) {
        if (typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                title: 'Hapus Produk?',
                text: "Produk ini akan dihapus dari keranjang!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            });
            
            if (result.isConfirmed) {
                await this.deleteItem(cartId);
            }
        } else {
            if (confirm('Hapus produk dari keranjang?')) {
                await this.deleteItem(cartId);
            }
        }
    }

    // Delete item via AJAX
    async deleteItem(cartId) {
        const itemElement = document.getElementById(`cart-item-${cartId}`);
        console.log(`Deleting cart item ${cartId}`);
        
        try {
            // Add removal animation
            if (itemElement) {
                itemElement.classList.add('cart-item-removing');
            }
            
            const response = await fetch(`${this.baseUrl}/cart/${cartId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            console.log(`Delete response status: ${response.status}`);
            const data = await response.json();
            console.log('Delete response data:', data);
            
            if (data.success) {
                // Remove item from DOM after animation
                setTimeout(() => {
                    if (itemElement) {
                        itemElement.remove();
                    }
                    
                    // Update cart count
                    if (data.cart_count !== undefined) {
                        this.updateTotalItems(data.cart_count);
                    }
                    
                    // Check if cart is empty
                    this.checkEmptyCart();
                    
                    this.showToast('Produk dihapus dari keranjang', 'success');
                }, 300);
                
            } else {
                // Remove animation class on error
                if (itemElement) {
                    itemElement.classList.remove('cart-item-removing');
                }
                this.showAlert('Gagal', data.message || 'Gagal menghapus produk', 'error');
            }
        } catch (error) {
            console.error('Error deleting item:', error);
            if (itemElement) {
                itemElement.classList.remove('cart-item-removing');
            }
            this.showAlert('Error', 'Gagal menghapus produk. Silakan coba lagi.', 'error');
        }
    }

    // Confirm clear cart
    async confirmClearCart() {
        console.log('Confirm clear cart called');
        if (typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                title: 'Kosongkan Keranjang?',
                text: "Semua produk akan dihapus dari keranjang!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Kosongkan!',
                cancelButtonText: 'Batal'
            });
            
            if (result.isConfirmed) {
                await this.clearCart();
            }
        } else {
            if (confirm('Kosongkan seluruh keranjang?')) {
                await this.clearCart();
            }
        }
    }

    // Show empty cart state
    showEmptyCart() {
        console.log('Showing empty cart state');
        const cartContentContainer = document.getElementById('cart-content-container');
        
        if (!cartContentContainer) {
            console.error('Cart content container not found');
            return;
        }
        
        const emptyCartHTML = `
            <div class="cart-empty-state">
                <div class="empty-cart">
                    <div class="empty-cart-icon">
                        <i data-feather="shopping-cart"></i>
                    </div>
                    <h2>Keranjang Belanja Anda Kosong</h2>
                    <p class="empty-cart-message">Tambahkan produk favorit Anda untuk mulai berbelanja</p>
                    <a href="/customer/products" class="btn btn-primary">
                        <i data-feather="shopping-bag"></i>
                        Mulai Belanja
                    </a>
                </div>
            </div>
        `;
        
        cartContentContainer.innerHTML = emptyCartHTML;
        
        // Update cart count to 0
        this.updateTotalItems(0);
        
        // Refresh feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        this.showToast('Keranjang berhasil dikosongkan', 'success');
    }

    // Check if cart is empty
    checkEmptyCart() {
        const cartItems = document.querySelectorAll('.cart-item');
        console.log(`Checking empty cart: ${cartItems.length} items remaining`);
        if (cartItems.length === 0) {
            setTimeout(() => {
                this.showEmptyCart();
            }, 500);
        }
    }

    // Update stock info display
    updateStockInfo(cartId, currentQuantity, maxStock) {
        const remainingStock = maxStock - currentQuantity;
        const stockInfo = document.getElementById(`stock-info-${cartId}`);
        
        if (!stockInfo) return;
        
        if (remainingStock < 10 && remainingStock > 0) {
            stockInfo.className = 'stock-warning';
            stockInfo.innerHTML = `Sisa Stok: <span class="stock-count">${remainingStock}</span>`;
        } else if (remainingStock <= 0) {
            stockInfo.className = 'stock-out';
            stockInfo.textContent = 'Stok habis';
        }
    }

    // Update total items count
    updateTotalItems(count) {
        console.log(`Updating total items to: ${count}`);
        const badge = document.getElementById('total-items-badge');
        const summaryCount = document.getElementById('summary-items-count');
        
        if (badge) badge.textContent = count;
        if (summaryCount) summaryCount.textContent = count;
    }

    // Validate cart before checkout
    validateCart() {
        const cartItems = document.querySelectorAll('.cart-item');
        if (cartItems.length === 0) {
            this.showAlert('Keranjang Kosong', 'Tambahkan produk terlebih dahulu!', 'warning');
            return false;
        }
        
        const outOfStockItems = document.querySelectorAll('.stock-out');
        if (outOfStockItems.length > 0) {
            this.showAlert('Stok Habis', 'Ada produk dengan stok habis di keranjang Anda.', 'error');
            return false;
        }
        
        return true;
    }

    // Show alert
    showAlert(title, text, icon = 'info') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title,
                text,
                icon,
                confirmButtonText: 'OK'
            });
        } else {
            alert(`${title}: ${text}`);
        }
    }

    // Show toast
    showToast(message, type = 'success') {
        // Remove existing toasts
        const existingToasts = document.querySelectorAll('.custom-toast');
        existingToasts.forEach(toast => toast.remove());
        
        const toast = document.createElement('div');
        toast.className = 'custom-toast';
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            background: ${type === 'success' ? '#10b981' : '#ef4444'};
            color: white;
            border-radius: 8px;
            z-index: 9999;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideIn 0.3s ease;
        `;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        // Auto remove
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
}

// Initialize Cart Manager
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.cart-page')) {
        window.cartManager = new CartManager();
        console.log('Cart Manager initialized successfully');
    }
    
    // Initialize Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});