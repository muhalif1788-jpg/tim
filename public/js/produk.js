// Simple interactivity for product page
document.addEventListener('DOMContentLoaded', function() {
    // Product button click handlers
    const productButtons = document.querySelectorAll('.product-btn');
    
    productButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productName = this.closest('.product-card').querySelector('.product-name').textContent;
            alert(`Detail produk ${productName} akan ditampilkan di sini!`);
            
            // Add click effect
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });

    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add loading animation to product images
    const productImages = document.querySelectorAll('.product-image img');
    productImages.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
        
        // Set initial opacity for fade-in effect
        img.style.opacity = '0';
        img.style.transition = 'opacity 0.5s ease';
    });
});