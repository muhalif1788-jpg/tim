// Cart functionality
class CartManager {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // Quantity input change events
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', (e) => {
                const cartId = e.target.id.replace('quantity-input-', '');
                this.updateQuantity(cartId);
            });
        });

        // Quantity button events
        document.querySelectorAll('.quantity-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const cartId = e.target.closest('.quantity-form').id.replace('quantity-form-', '');
                const action = e.target.closest('button').querySelector('i').getAttribute('data-feather');
                
                if (action === 'minus') {
                    this.decreaseQuantity(cartId);
                } else if (action === 'plus') {
                    this.increaseQuantity(cartId);
                }
            });
        });
    }

    // Update quantity via form submission
    updateQuantity(cartId) {
        const form = document.getElementById(`quantity-form-${cartId}`);
        const quantityInput = document.getElementById(`quantity-input-${cartId}`);
        const currentValue = parseInt(quantityInput.value);
        const maxValue = parseInt(quantityInput.max);

        if (currentValue < 1) {
            quantityInput.value = 1;
            return;
        }

        if (currentValue > maxValue) {
            quantityInput.value = maxValue;
            Swal.fire({
                icon: 'warning',
                title: 'Stok Terbatas',
                text: `Stok maksimum yang tersedia adalah ${maxValue} pcs`,
                timer: 2000
            });
        }

        form.submit();
    }

    // Decrease quantity
    decreaseQuantity(cartId) {
        const quantityInput = document.getElementById(`quantity-input-${cartId}`);
        const currentValue = parseInt(quantityInput.value);
        
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
            this.updateQuantity(cartId);
        } else {
            this.confirmRemoveItem(cartId);
        }
    }

    // Increase quantity
    increaseQuantity(cartId) {
        const quantityInput = document.getElementById(`quantity-input-${cartId}`);
        const currentValue = parseInt(quantityInput.value);
        const maxValue = parseInt(quantityInput.max);
        
        if (currentValue < maxValue) {
            quantityInput.value = currentValue + 1;
            this.updateQuantity(cartId);
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Terbatas',
                text: `Stok maksimum yang tersedia adalah ${maxValue} pcs`,
                timer: 2000
            });
        }
    }

    // Confirm remove item
    confirmRemoveItem(cartId) {
        Swal.fire({
            title: 'Hapus Produk?',
            text: 'Apakah Anda yakin ingin menghapus produk ini dari keranjang?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`remove-form-${cartId}`).submit();
            }
        });
    }

    // Confirm clear cart
    confirmClearCart() {
        Swal.fire({
            title: 'Kosongkan Keranjang?',
            text: 'Apakah Anda yakin ingin mengosongkan seluruh keranjang?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Kosongkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('clear-form').submit();
            }
        });
    }

    // Validate cart before checkout
    validateCart() {
        const unavailableItems = document.querySelectorAll('.cart-item.unavailable');
        const outOfStockItems = document.querySelectorAll('.stock-out');
        
        if (unavailableItems.length > 0 || outOfStockItems.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Produk Tidak Valid',
                html: 'Terdapat produk yang tidak tersedia atau stok habis di keranjang Anda. Silakan hapus produk tersebut terlebih dahulu.',
                confirmButtonText: 'OK'
            });
            return false;
        }

        const totalItems = parseInt(document.getElementById('cart-count')?.textContent || 0);
        if (totalItems === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Keranjang Kosong',
                text: 'Keranjang Anda masih kosong. Silakan tambahkan produk terlebih dahulu.',
                confirmButtonText: 'OK'
            });
            return false;
        }

        return true;
    }

    // AJAX update quantity (optional - jika ingin tanpa reload page)
    async updateQuantityAjax(cartId, quantity) {
        try {
            const response = await fetch(`/cart/${cartId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: quantity })
            });

            const data = await response.json();

            if (data.success) {
                return data;
            } else {
                throw new Error(data.message || 'Gagal mengupdate quantity');
            }
        } catch (error) {
            console.error('Error updating quantity:', error);
            throw error;
        }
    }

    // Update cart count badge
    updateCartCount(count) {
        const badge = document.getElementById('cart-count');
        if (badge) {
            badge.textContent = count;
            if (count > 0) {
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }
    }

    // Refresh page with smooth transition
    refreshPage() {
        document.body.style.opacity = '0.5';
        setTimeout(() => {
            window.location.reload();
        }, 300);
    }
}

// Initialize cart manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const cartManager = new CartManager();
    
    // Global functions for onclick attributes
    window.confirmRemoveItem = (cartId) => cartManager.confirmRemoveItem(cartId);
    window.confirmClearCart = () => cartManager.confirmClearCart();
    window.decreaseQuantity = (cartId) => cartManager.decreaseQuantity(cartId);
    window.increaseQuantity = (cartId) => cartManager.increaseQuantity(cartId);
    window.updateQuantity = (cartId) => cartManager.updateQuantity(cartId);
    window.validateCart = () => cartManager.validateCart();
});

// Feather icons replacement on dynamic content
document.addEventListener('feathericons-update', function() {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});