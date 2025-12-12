// register-simple.js - GANTI register.js dengan ini
document.addEventListener('DOMContentLoaded', function() {
    // HANYA fungsi yang AMAN:
    initPasswordStrengthIndicator(); // Visual feedback saja
    initPhoneFormatting();           // Formatting saja
    initSubmitLoading();            // Loading state saja
});

// 1. PASSWORD STRENGTH INDICATOR (VISUAL SAJA, TIDAK VALIDASI)
function initPasswordStrengthIndicator() {
    const passwordInput = document.getElementById('password');
    if (!passwordInput) return;
    
    // Buat indicator visual
    const indicator = document.createElement('div');
    indicator.className = 'password-strength';
    indicator.innerHTML = '<div class="strength-bar" style="height: 4px; background: #e5e7eb; border-radius: 2px; margin-top: 5px;"><div class="strength-fill" style="height: 100%; width: 0%; border-radius: 2px; transition: width 0.3s;"></div></div>';
    passwordInput.parentNode.appendChild(indicator);
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        
        if (password.length >= 1) strength += 20;
        if (password.length >= 4) strength += 20;
        if (password.length >= 8) strength += 20;
        if (/[a-z]/.test(password)) strength += 20;
        if (/[A-Z]/.test(password)) strength += 20;
        
        const fill = indicator.querySelector('.strength-fill');
        fill.style.width = Math.min(strength, 100) + '%';
        
        // Warna berdasarkan strength
        if (strength <= 40) {
            fill.style.backgroundColor = '#ef4444'; // red
        } else if (strength <= 80) {
            fill.style.backgroundColor = '#f59e0b'; // yellow
        } else {
            fill.style.backgroundColor = '#10b981'; // green
        }
    });
}

// 2. PHONE FORMATTING (VISUAL SAJA)
function initPhoneFormatting() {
    const phoneInput = document.getElementById('phone');
    if (!phoneInput) return;
    
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        // Format: 0812-3456-7890
        if (value.length > 4) {
            value = value.substring(0, 4) + '-' + value.substring(4);
        }
        if (value.length > 9) {
            value = value.substring(0, 9) + '-' + value.substring(9, 13);
        }
        
        e.target.value = value;
    });
}

// 3. LOADING STATE SAAT SUBMIT
function initSubmitLoading() {
    const form = document.querySelector('form[action*="register"]');
    if (!form) return;
    
    const submitBtn = form.querySelector('button[type="submit"]');
    if (!submitBtn) return;
    
    // Simpan teks asli
    if (!submitBtn.dataset.originalText) {
        submitBtn.dataset.originalText = submitBtn.innerHTML;
    }
    
    form.addEventListener('submit', function() {
        // Tampilkan loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mendaftarkan...';
        
        // Fallback reset setelah 10 detik
        setTimeout(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = submitBtn.dataset.originalText;
        }, 10000);
    });
}