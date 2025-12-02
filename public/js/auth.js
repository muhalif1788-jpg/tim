// auth.js - JavaScript untuk halaman authentication (login/register)

document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi fungsi
    initPasswordToggle();
    initFormValidation();
    initSocialLogin();
    initRememberMe();
    initFlashMessages();
    initSmoothAnimations();
});

// Fungsi untuk toggle visibility password
function initPasswordToggle() {
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    
    passwordInputs.forEach(input => {
        const container = input.parentElement;
        const toggleButton = document.createElement('button');
        toggleButton.type = 'button';
        toggleButton.className = 'absolute inset-y-0 right-0 pr-3 flex items-center focus:outline-none';
        toggleButton.innerHTML = '<i class="fas fa-eye text-gray-400 hover:text-red-500 transition duration-200"></i>';
        
        // Hapus icon lock default jika ada
        const existingIcon = container.querySelector('.fa-lock');
        if (existingIcon) {
            existingIcon.remove();
        }
        
        container.appendChild(toggleButton);
        
        toggleButton.addEventListener('click', function() {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            if (type === 'text') {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                icon.classList.add('text-red-500');
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
                icon.classList.remove('text-red-500');
            }
        });
    });
}

// Fungsi untuk validasi form real-time
function initFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input[required]');
        
        inputs.forEach(input => {
            // Validasi real-time
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            // Reset validasi saat user mulai mengetik
            input.addEventListener('input', function() {
                clearFieldError(this);
            });
        });
        
        // Validasi sebelum submit
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                showFormError('Harap periksa kembali data yang Anda masukkan.');
            }
        });
    });
}

// Validasi individual field
function validateField(field) {
    const value = field.value.trim();
    const fieldName = field.getAttribute('name');
    let isValid = true;
    let errorMessage = '';
    
    clearFieldError(field);
    
    // Validasi email
    if (fieldName === 'email') {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            isValid = false;
            errorMessage = 'Format email tidak valid';
        }
    }
    
    // Validasi password
    if (fieldName === 'password') {
        if (value.length < 6) {
            isValid = false;
            errorMessage = 'Password minimal 6 karakter';
        }
    }
    
    // Validasi required field
    if (field.hasAttribute('required') && value === '') {
        isValid = false;
        errorMessage = 'Field ini wajib diisi';
    }
    
    if (!isValid) {
        showFieldError(field, errorMessage);
    }
    
    return isValid;
}

// Validasi seluruh form
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });
    
    return isValid;
}

// Menampilkan error pada field
function showFieldError(field, message) {
    // Hapus error sebelumnya
    clearFieldError(field);
    
    // Tambah class error
    field.classList.add('border-red-500');
    field.classList.remove('border-gray-300');
    
    // Buat element error message
    const errorElement = document.createElement('p');
    errorElement.className = 'mt-1 text-sm text-red-600 animate-pulse';
    errorElement.textContent = message;
    
    // Insert setelah field
    field.parentNode.parentNode.appendChild(errorElement);
    
    // Simpan reference untuk nanti dihapus
    field.errorElement = errorElement;
}

// Hapus error dari field
function clearFieldError(field) {
    field.classList.remove('border-red-500');
    field.classList.add('border-gray-300');
    
    if (field.errorElement) {
        field.errorElement.remove();
        field.errorElement = null;
    }
}

// Menampilkan error form global
function showFormError(message) {
    // Hapus error sebelumnya
    const existingError = document.querySelector('.form-global-error');
    if (existingError) {
        existingError.remove();
    }
    
    // Buat element error
    const errorElement = document.createElement('div');
    errorElement.className = 'form-global-error bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center mb-4 animate-bounce';
    errorElement.innerHTML = `
        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
        ${message}
    `;
    
    // Insert di atas form
    const form = document.querySelector('form');
    form.parentNode.insertBefore(errorElement, form);
    
    // Auto remove setelah 5 detik
    setTimeout(() => {
        errorElement.remove();
    }, 5000);
}

// Fungsi untuk social login
function initSocialLogin() {
    const socialButtons = document.querySelectorAll('.social-btn');
    
    socialButtons.forEach(button => {
        button.addEventListener('click', function() {
            const provider = this.textContent.trim().toLowerCase();
            simulateSocialLogin(provider);
        });
    });
}

// Simulasi social login (untuk demo)
function simulateSocialLogin(provider) {
    const button = event.currentTarget;
    const originalText = button.innerHTML;
    
    // Loading state
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
    button.disabled = true;
    
    // Simulasi proses login
    setTimeout(() => {
        showFormError(`Fitur login dengan ${provider} sedang dalam pengembangan.`);
        button.innerHTML = originalText;
        button.disabled = false;
    }, 2000);
}

// Fungsi untuk remember me
function initRememberMe() {
    const rememberCheckbox = document.getElementById('remember');
    
    // Cek jika ada data remember di localStorage
    const savedEmail = localStorage.getItem('rememberedEmail');
    if (savedEmail && rememberCheckbox) {
        const emailField = document.getElementById('email');
        if (emailField) {
            emailField.value = savedEmail;
            rememberCheckbox.checked = true;
        }
    }
    
    // Simpan email saat form submit
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            if (rememberCheckbox && rememberCheckbox.checked) {
                const emailField = document.getElementById('email');
                if (emailField) {
                    localStorage.setItem('rememberedEmail', emailField.value);
                }
            } else {
                localStorage.removeItem('rememberedEmail');
            }
        });
    });
}

// Fungsi untuk auto-hide flash messages
function initFlashMessages() {
    const flashMessages = document.querySelectorAll('.flash-message');
    
    flashMessages.forEach(message => {
        // Auto hide setelah 5 detik
        setTimeout(() => {
            if (message.parentNode) {
                message.style.opacity = '0';
                message.style.transition = 'opacity 0.5s ease';
                setTimeout(() => {
                    if (message.parentNode) {
                        message.remove();
                    }
                }, 500);
            }
        }, 5000);
        
        // Tambah close button
        const closeButton = document.createElement('button');
        closeButton.innerHTML = '<i class="fas fa-times"></i>';
        closeButton.className = 'ml-auto text-gray-500 hover:text-gray-700 focus:outline-none';
        closeButton.addEventListener('click', function() {
            message.style.opacity = '0';
            setTimeout(() => {
                if (message.parentNode) {
                    message.remove();
                }
            }, 500);
        });
        
        message.appendChild(closeButton);
    });
}

// Fungsi untuk smooth animations
function initSmoothAnimations() {
    // Add loading animation to submit buttons
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    
    submitButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (validateForm(this.closest('form'))) {
                // Add loading state
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
                this.disabled = true;
                
                // Re-enable after 3 seconds (fallback)
                setTimeout(() => {
                    this.disabled = false;
                    const originalText = this.getAttribute('data-original-text') || 'Login';
                    this.innerHTML = originalText;
                }, 3000);
            }
        });
        
        // Save original text
        button.setAttribute('data-original-text', button.textContent);
    });
}

// Utility function untuk debounce
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

// Export functions untuk penggunaan di file lain (jika needed)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        validateField,
        validateForm,
        simulateSocialLogin
    };
}