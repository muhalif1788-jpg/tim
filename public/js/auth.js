// public/js/auth-simple.js - GANTI auth.js dengan ini
document.addEventListener('DOMContentLoaded', function() {
    // 1. Password toggle saja
    initPasswordToggle();
    
    // 2. Loading state saat submit
    initSubmitLoading();
    
    // 3. Auto-hide messages
    initAutoHideMessages();
});

// 1. TOGGLE PASSWORD
function initPasswordToggle() {
    document.querySelectorAll('input[type="password"]').forEach(input => {
        const toggleBtn = document.createElement('button');
        toggleBtn.type = 'button';
        toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
        toggleBtn.style.cssText = `
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%); background: none;
            border: none; cursor: pointer; color: #666; padding: 5px;
        `;
        
        if (input.parentElement.style.position !== 'relative') {
            input.parentElement.style.position = 'relative';
        }
        
        input.parentElement.appendChild(toggleBtn);
        
        toggleBtn.addEventListener('click', function() {
            if (input.type === 'password') {
                input.type = 'text';
                this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                this.style.color = '#dc2626';
            } else {
                input.type = 'password';
                this.innerHTML = '<i class="fas fa-eye"></i>';
                this.style.color = '#666';
            }
        });
    });
}

// 2. LOADING STATE (TANPA VALIDASI)
function initSubmitLoading() {
    document.querySelectorAll('form').forEach(form => {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            if (!submitBtn.dataset.originalText) {
                submitBtn.dataset.originalText = submitBtn.innerHTML;
            }
            
            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            });
        }
    });
}

// 3. AUTO-HIDE MESSAGES
function initAutoHideMessages() {
    setTimeout(() => {
        document.querySelectorAll('.alert, .flash-message').forEach(msg => {
            if (msg.parentNode) {
                msg.style.opacity = '0';
                setTimeout(() => {
                    if (msg.parentNode) msg.remove();
                }, 500);
            }
        });
    }, 5000);
}