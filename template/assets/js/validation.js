/**
 * ClubEdge - Form Validation
 * Client-side validation utilities
 */

'use strict';

/**
 * Validation Rules
 */
const validators = {
    required: (value) => {
        return value.trim().length > 0;
    },

    email: (value) => {
        const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return pattern.test(value);
    },

    minLength: (value, min) => {
        return value.length >= min;
    },

    maxLength: (value, max) => {
        return value.length <= max;
    },

    match: (value, compareValue) => {
        return value === compareValue;
    },

    pattern: (value, regex) => {
        return regex.test(value);
    }
};

/**
 * Error Messages
 */
const errorMessages = {
    required: 'Ce champ est requis',
    email: 'Veuillez entrer une adresse email valide',
    minLength: (min) => `Minimum ${min} caractères requis`,
    maxLength: (max) => `Maximum ${max} caractères autorisés`,
    match: 'Les champs ne correspondent pas',
    pattern: 'Format invalide'
};

/**
 * Validate a single field
 */
function validateField(input) {
    const value = input.value;
    const rules = input.dataset;
    let isValid = true;
    let message = '';

    // Required
    if (input.hasAttribute('required') && !validators.required(value)) {
        isValid = false;
        message = errorMessages.required;
    }

    // Email
    if (input.type === 'email' && value && !validators.email(value)) {
        isValid = false;
        message = errorMessages.email;
    }

    // Min length
    if (rules.minlength && !validators.minLength(value, parseInt(rules.minlength))) {
        isValid = false;
        message = errorMessages.minLength(rules.minlength);
    }

    // Max length
    if (rules.maxlength && !validators.maxLength(value, parseInt(rules.maxlength))) {
        isValid = false;
        message = errorMessages.maxLength(rules.maxlength);
    }

    // Match (for password confirmation)
    if (rules.match) {
        const matchField = document.getElementById(rules.match);
        if (matchField && !validators.match(value, matchField.value)) {
            isValid = false;
            message = errorMessages.match;
        }
    }

    return { isValid, message };
}

/**
 * Show field error
 */
function showFieldError(input, message) {
    const formGroup = input.closest('.form-group');
    if (!formGroup) return;

    input.classList.add('form-input-error');

    // Remove existing error
    const existingError = formGroup.querySelector('.form-error');
    if (existingError) existingError.remove();

    // Add error message
    const errorEl = document.createElement('span');
    errorEl.className = 'form-error';
    errorEl.textContent = message;
    formGroup.appendChild(errorEl);
}

/**
 * Clear field error
 */
function clearFieldError(input) {
    const formGroup = input.closest('.form-group');
    if (!formGroup) return;

    input.classList.remove('form-input-error');

    const error = formGroup.querySelector('.form-error');
    if (error) error.remove();
}

/**
 * Validate form
 */
function validateForm(form) {
    const inputs = form.querySelectorAll('input, textarea, select');
    let isFormValid = true;

    inputs.forEach(input => {
        const { isValid, message } = validateField(input);

        if (!isValid) {
            isFormValid = false;
            showFieldError(input, message);
        } else {
            clearFieldError(input);
        }
    });

    return isFormValid;
}

/**
 * Password Strength Checker
 */
function checkPasswordStrength(password) {
    let strength = 0;

    // Length check
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;

    // Contains lowercase
    if (/[a-z]/.test(password)) strength++;

    // Contains uppercase
    if (/[A-Z]/.test(password)) strength++;

    // Contains number
    if (/[0-9]/.test(password)) strength++;

    // Contains special char
    if (/[^a-zA-Z0-9]/.test(password)) strength++;

    // Normalize to 0-4 scale
    return Math.min(Math.floor(strength / 1.5), 4);
}

function updatePasswordStrengthUI(password) {
    const strengthBar = document.querySelector('.password-strength-bar');
    const strengthText = document.querySelector('.password-strength-text');

    if (!strengthBar || !strengthText) return;

    const strength = checkPasswordStrength(password);
    const segments = strengthBar.querySelectorAll('.password-strength-segment');

    const strengthLabels = ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'];
    const strengthClasses = ['', '', 'medium', 'strong', 'strong'];

    segments.forEach((segment, index) => {
        segment.classList.remove('active', 'medium', 'strong');

        if (index < strength) {
            segment.classList.add('active');
            if (strengthClasses[strength]) {
                segment.classList.add(strengthClasses[strength]);
            }
        }
    });

    strengthText.textContent = password.length > 0 ? strengthLabels[strength] : 'Entrez un mot de passe';
}

/**
 * Initialize form validation
 */
function initFormValidation() {
    // Real-time validation on blur
    document.querySelectorAll('form input, form textarea').forEach(input => {
        input.addEventListener('blur', () => {
            const { isValid, message } = validateField(input);

            if (!isValid) {
                showFieldError(input, message);
            } else {
                clearFieldError(input);
            }
        });

        // Clear error on input
        input.addEventListener('input', () => {
            clearFieldError(input);
        });
    });

    // Password strength
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', (e) => {
            updatePasswordStrengthUI(e.target.value);
        });
    }

    // Form submission
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!validateForm(form)) {
                e.preventDefault();

                // Focus first error
                const firstError = form.querySelector('.form-input-error');
                firstError?.focus();
            }
        });
    });
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', initFormValidation);

// Export
window.ClubEdge = window.ClubEdge || {};
window.ClubEdge.validateForm = validateForm;
window.ClubEdge.validateField = validateField;
window.ClubEdge.checkPasswordStrength = checkPasswordStrength;
