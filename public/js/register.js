// register.js - JavaScript khusus untuk halaman register

document.addEventListener('DOMContentLoaded', function() {
    initRegisterForm();
    initPasswordValidation();
    initPhoneNumberFormat();
});

function initRegisterForm() {
    const registerForm = document.querySelector('form[action*="register"]');
    if (!registerForm) return;

    // Real-time validation
    const inputs = registerForm.querySelectorAll('input[required], textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateRegisterField(this);
        });
        
        input.addEventListener('input', function() {
            clearFieldError(this);
            // Validasi password strength saat mengetik password
            if (this.id === 'password') {
                updatePasswordStrength(this.value);
            }
        });
    });

    // Form submission
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateRegisterForm(this)) {
            showLoadingState();
            // Simulate API call
            setTimeout(() => {
                this.submit();
            }, 1500);
        } else {
            showFormError('Harap periksa kembali data yang Anda masukkan.');
        }
    });
}

function validateRegisterForm(form) {
    let isValid = true;
    const fields = [
        'name',
        'email', 
        'password',
        'password_confirmation'
    ];

    fields.forEach(fieldName => {
        const field = form.querySelector(`[name="${fieldName}"]`);
        if (field && !validateRegisterField(field)) {
            isValid = false;
        }
    });

    // Validasi konfirmasi password
    const password = form.querySelector('[name="password"]');
    const passwordConfirmation = form.querySelector('[name="password_confirmation"]');
    
    if (password && passwordConfirmation && password.value !== passwordConfirmation.value) {
        showFieldError(passwordConfirmation, 'Konfirmasi password tidak cocok');
        isValid = false;
    }

    return isValid;
}

function validateRegisterField(field) {
    const value = field.value.trim();
    const fieldName = field.getAttribute('name');
    let isValid = true;
    let errorMessage = '';

    clearFieldError(field);

    // Validasi berdasarkan field name
    switch(fieldName) {
        case 'name':
            if (value.length < 2) {
                isValid = false;
                errorMessage = 'Nama minimal 2 karakter';
            } else if (value.length > 50) {
                isValid = false;
                errorMessage = 'Nama maksimal 50 karakter';
            }
            break;

        case 'email':
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = 'Format email tidak valid';
            }
            break;

        case 'phone':
            if (value && !/^\+?[\d\s-()]{10,}$/.test(value)) {
                isValid = false;
                errorMessage = 'Format nomor telepon tidak valid';
            }
            break;

        case 'password':
            const passwordErrors = validatePassword(value);
            if (passwordErrors.length > 0) {
                isValid = false;
                errorMessage = passwordErrors[0]; // Tampilkan error pertama
            }
            break;

        case 'password_confirmation':
            const passwordField = document.querySelector('[name="password"]');
            if (passwordField && value !== passwordField.value) {
                isValid = false;
                errorMessage = 'Konfirmasi password tidak cocok';
            }
            break;
    }

    // Validasi required field
    if (field.hasAttribute('required') && value === '') {
        isValid = false;
        errorMessage = 'Field ini wajib diisi';
    }

    if (!isValid) {
        showFieldError(field, errorMessage);
    } else {
        showFieldSuccess(field);
    }

    return isValid;
}

function validatePassword(password) {
    const errors = [];
    
    if (password.length < 8) {
        errors.push('Password minimal 8 karakter');
    }
    if (!/(?=.*[a-z])/.test(password)) {
        errors.push('Password harus mengandung huruf kecil');
    }
    if (!/(?=.*[A-Z])/.test(password)) {
        errors.push('Password harus mengandung huruf besar');
    }
    if (!/(?=.*\d)/.test(password)) {
        errors.push('Password harus mengandung angka');
    }
    if (!/(?=.*[@$!%*?&])/.test(password)) {
        errors.push('Password harus mengandung karakter khusus');
    }
    
    return errors;
}

function initPasswordValidation() {
    const passwordInput = document.getElementById('password');
    if (!passwordInput) return;

    // Create password strength indicator
    const strengthIndicator = document.createElement('div');
    strengthIndicator.className = 'password-strength';
    strengthIndicator.innerHTML = '<div class="strength-bar"></div>';
    passwordInput.parentNode.appendChild(strengthIndicator);

    // Create password requirements list
    const requirementsList = document.createElement('div');
    requirementsList.className = 'password-requirements';
    requirementsList.innerHTML = `
        <div class="requirement unmet" data-requirement="length">
            <i class="fas fa-circle"></i> Minimal 8 karakter
        </div>
        <div class="requirement unmet" data-requirement="lowercase">
            <i class="fas fa-circle"></i> Huruf kecil (a-z)
        </div>
        <div class="requirement unmet" data-requirement="uppercase">
            <i class="fas fa-circle"></i> Huruf besar (A-Z)
        </div>
        <div class="requirement unmet" data-requirement="number">
            <i class="fas fa-circle"></i> Angka (0-9)
        </div>
        <div class="requirement unmet" data-requirement="special">
            <i class="fas fa-circle"></i> Karakter khusus (@$!%*?&)
        </div>
    `;
    passwordInput.parentNode.appendChild(requirementsList);

    passwordInput.addEventListener('input', function() {
        updatePasswordStrength(this.value);
        updatePasswordRequirements(this.value);
    });
}

function updatePasswordStrength(password) {
    const strengthBar = document.querySelector('.strength-bar');
    if (!strengthBar) return;

    let strength = 0;
    
    // Kriteria strength
    if (password.length >= 8) strength += 20;
    if (/(?=.*[a-z])/.test(password)) strength += 20;
    if (/(?=.*[A-Z])/.test(password)) strength += 20;
    if (/(?=.*\d)/.test(password)) strength += 20;
    if (/(?=.*[@$!%*?&])/.test(password)) strength += 20;

    // Update visual strength bar
    strengthBar.style.width = strength + '%';
    strengthBar.className = 'strength-bar';
    
    if (strength <= 40) {
        strengthBar.classList.add('strength-weak');
    } else if (strength <= 80) {
        strengthBar.classList.add('strength-medium');
    } else {
        strengthBar.classList.add('strength-strong');
    }
}

function updatePasswordRequirements(password) {
    const requirements = {
        length: password.length >= 8,
        lowercase: /(?=.*[a-z])/.test(password),
        uppercase: /(?=.*[A-Z])/.test(password),
        number: /(?=.*\d)/.test(password),
        special: /(?=.*[@$!%*?&])/.test(password)
    };

    Object.keys(requirements).forEach(requirement => {
        const element = document.querySelector(`[data-requirement="${requirement}"]`);
        if (element) {
            const icon = element.querySelector('i');
            if (requirements[requirement]) {
                element.classList.remove('unmet');
                element.classList.add('met');
                icon.className = 'fas fa-check-circle';
            } else {
                element.classList.remove('met');
                element.classList.add('unmet');
                icon.className = 'fas fa-circle';
            }
        }
    });
}

function initPhoneNumberFormat() {
    const phoneInput = document.getElementById('phone');
    if (!phoneInput) return;

    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        // Format nomor Indonesia
        if (value.startsWith('0')) {
            value = '+62' + value.substring(1);
        } else if (value.startsWith('62')) {
            value = '+' + value;
        } else if (value && !value.startsWith('+')) {
            value = '+62' + value;
        }
        
        // Batasi panjang
        if (value.length > 15) {
            value = value.substring(0, 15);
        }
        
        e.target.value = value;
    });
}

// Utility functions (sama dengan di auth.js)
function showFieldError(field, message) {
    clearFieldError(field);
    
    field.classList.add('error');
    field.style.borderColor = '#dc2626';
    
    const errorElement = document.createElement('span');
    errorElement.className = 'error-message';
    errorElement.textContent = message;
    
    field.parentNode.appendChild(errorElement);
    field.errorElement = errorElement;
}

function showFieldSuccess(field) {
    field.style.borderColor = '#10b981';
}

function clearFieldError(field) {
    field.classList.remove('error');
    field.style.borderColor = '';
    
    if (field.errorElement) {
        field.errorElement.remove();
        field.errorElement = null;
    }
}

function showFormError(message) {
    const existingError = document.querySelector('.form-global-error');
    if (existingError) {
        existingError.remove();
    }

    const errorElement = document.createElement('div');
    errorElement.className = 'form-global-error flash-message flash-error';
    errorElement.innerHTML = `
        <i class="fas fa-exclamation-circle mr-2"></i>
        ${message}
        <button type="button" class="ml-auto text-red-500 hover:text-red-700 focus:outline-none" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;

    const form = document.querySelector('form');
    form.parentNode.insertBefore(errorElement, form);
}

function showLoadingState() {
    const submitButton = document.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.disabled = true;
        submitButton.classList.add('btn-loading');
        submitButton.innerHTML = 'Mendaftarkan...';
    }
}

// Export untuk penggunaan modular
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        validateRegisterForm,
        validatePassword,
        updatePasswordStrength
    };
}