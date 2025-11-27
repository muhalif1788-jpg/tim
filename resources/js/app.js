import './bootstrap';
// Import dependencies
import './bootstrap';
import Alpine from 'alpinejs';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Global utility functions
window.formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
};

// Cart management
window.cartManager = {
    items: [],
    
    addItem(product, quantity = 1) {
        const existingItem = this.items.find(item => item.id === product.id);
        
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            this.items.push({
                ...product,
                quantity: quantity
            });
        }
        
        this.updateCartDisplay();
    },
    
    removeItem(productId) {
        this.items = this.items.filter(item => item.id !== productId);
        this.updateCartDisplay();
    },
    
    updateCartDisplay() {
        // Update cart UI
        const cartCount = this.items.reduce((sum, item) => sum + item.quantity, 0);
        const totalAmount = this.items.reduce((sum, item) => sum + (item.harga * item.quantity), 0);
        
        // Dispatch custom event for other components to listen
        window.dispatchEvent(new CustomEvent('cartUpdated', {
            detail: {
                items: this.items,
                count: cartCount,
                total: totalAmount
            }
        }));
    }
};

// Keyboard shortcuts untuk kasir
document.addEventListener('keydown', (e) => {
    // F2 untuk focus search product
    if (e.key === 'F2') {
        e.preventDefault();
        const searchInput = document.querySelector('#productSearch');
        if (searchInput) searchInput.focus();
    }
    
    // F3 untuk new transaction
    if (e.key === 'F3' && e.ctrlKey) {
        e.preventDefault();
        // Logic untuk transaksi baru
    }
});