/**
 * ClubEdge - Utility Functions
 */

'use strict';

/**
 * Debounce function
 */
function debounce(func, wait = 300) {
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

/**
 * Throttle function
 */
function throttle(func, limit = 300) {
    let inThrottle;
    return function (...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

/**
 * Format date
 */
function formatDate(date, options = {}) {
    const defaultOptions = {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    };

    return new Intl.DateTimeFormat('fr-FR', { ...defaultOptions, ...options })
        .format(new Date(date));
}

/**
 * Format relative time
 */
function formatRelativeTime(date) {
    const now = new Date();
    const diff = now - new Date(date);
    const seconds = Math.floor(diff / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);
    const weeks = Math.floor(days / 7);
    const months = Math.floor(days / 30);

    if (months > 0) return `Il y a ${months} mois`;
    if (weeks > 0) return `Il y a ${weeks} semaine${weeks > 1 ? 's' : ''}`;
    if (days > 0) return `Il y a ${days} jour${days > 1 ? 's' : ''}`;
    if (hours > 0) return `Il y a ${hours} heure${hours > 1 ? 's' : ''}`;
    if (minutes > 0) return `Il y a ${minutes} minute${minutes > 1 ? 's' : ''}`;
    return 'À l\'instant';
}

/**
 * Escape HTML
 */
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

/**
 * Generate unique ID
 */
function generateId(prefix = 'id') {
    return `${prefix}_${Math.random().toString(36).substr(2, 9)}`;
}

/**
 * Deep clone object
 */
function deepClone(obj) {
    return JSON.parse(JSON.stringify(obj));
}

/**
 * Check if element is in viewport
 */
function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

/**
 * Smooth scroll to element
 */
function scrollToElement(element, offset = 0) {
    const top = element.getBoundingClientRect().top + window.pageYOffset - offset;
    window.scrollTo({
        top,
        behavior: 'smooth'
    });
}

/**
 * Copy to clipboard
 */
async function copyToClipboard(text) {
    try {
        await navigator.clipboard.writeText(text);
        return true;
    } catch (err) {
        console.error('Failed to copy:', err);
        return false;
    }
}

/**
 * Format file size
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';

    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

/**
 * Truncate text
 */
function truncate(text, length = 100, suffix = '...') {
    if (text.length <= length) return text;
    return text.substring(0, length).trim() + suffix;
}

/**
 * Parse query string
 */
function parseQueryString(queryString = window.location.search) {
    const params = new URLSearchParams(queryString);
    const result = {};

    for (const [key, value] of params) {
        result[key] = value;
    }

    return result;
}

/**
 * Build query string
 */
function buildQueryString(params) {
    return new URLSearchParams(params).toString();
}

/**
 * Local storage helpers
 */
const storage = {
    get(key, defaultValue = null) {
        try {
            const item = localStorage.getItem(key);
            return item ? JSON.parse(item) : defaultValue;
        } catch {
            return defaultValue;
        }
    },

    set(key, value) {
        try {
            localStorage.setItem(key, JSON.stringify(value));
            return true;
        } catch {
            return false;
        }
    },

    remove(key) {
        localStorage.removeItem(key);
    },

    clear() {
        localStorage.clear();
    }
};

// Export utilities
window.ClubEdge = window.ClubEdge || {};
Object.assign(window.ClubEdge, {
    debounce,
    throttle,
    formatDate,
    formatRelativeTime,
    escapeHtml,
    generateId,
    deepClone,
    isInViewport,
    scrollToElement,
    copyToClipboard,
    formatFileSize,
    truncate,
    parseQueryString,
    buildQueryString,
    storage
});
