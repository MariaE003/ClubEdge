/**
 * ClubEdge - Authentication Module
 */

'use strict';

const Auth = {
    /**
     * Initialize authentication
     */
    init() {
        this.initPasswordToggle();
        this.initRememberMe();
        this.initFormSubmission();
    },

    /**
     * Toggle password visibility
     */
    initPasswordToggle() {
        document.querySelectorAll('.password-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = btn.previousElementSibling;
                const isPassword = input.type === 'password';

                input.type = isPassword ? 'text' : 'password';
                btn.innerHTML = isPassword
                    ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>'
                    : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
            });
        });
    },

    /**
     * Remember me functionality
     */
    initRememberMe() {
        const checkbox = document.querySelector('input[name="remember"]');
        const emailInput = document.getElementById('email');

        if (!checkbox || !emailInput) return;

        // Load saved email
        const savedEmail = localStorage.getItem('clubedge_remember_email');
        if (savedEmail) {
            emailInput.value = savedEmail;
            checkbox.checked = true;
        }

        // Save on form submit
        checkbox.closest('form')?.addEventListener('submit', () => {
            if (checkbox.checked) {
                localStorage.setItem('clubedge_remember_email', emailInput.value);
            } else {
                localStorage.removeItem('clubedge_remember_email');
            }
        });
    },

    /**
     * Handle form submission
     */
    initFormSubmission() {
        const forms = document.querySelectorAll('.auth-form');

        forms.forEach(form => {
            form.addEventListener('submit', async (e) => {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (!submitBtn) return;

                // Add loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<div class="spinner spinner-sm"></div> Chargement...';

                // Form will be submitted normally
                // In real app, would use fetch() here
            });
        });
    },

    /**
     * Check if user is authenticated
     */
    isAuthenticated() {
        return !!localStorage.getItem('clubedge_token');
    },

    /**
     * Get current user
     */
    getCurrentUser() {
        const user = localStorage.getItem('clubedge_user');
        return user ? JSON.parse(user) : null;
    },

    /**
     * Logout
     */
    logout() {
        localStorage.removeItem('clubedge_token');
        localStorage.removeItem('clubedge_user');
        window.location.href = '/login';
    }
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => Auth.init());

// Export
window.ClubEdge = window.ClubEdge || {};
window.ClubEdge.Auth = Auth;
