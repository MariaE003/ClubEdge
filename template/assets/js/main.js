/**
 * ClubEdge - Main JavaScript
 * Core functionality and initialization
 */

'use strict';

// DOM Ready
document.addEventListener('DOMContentLoaded', () => {
    initSidebar();
    initDropdowns();
    initModals();
    initAlerts();
    initTabs();
    initTooltips();
});

/**
 * Sidebar Toggle (Mobile)
 */
function initSidebar() {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobile-overlay');

    if (!menuToggle || !sidebar) return;

    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        overlay?.classList.toggle('active');
        document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
    });

    overlay?.addEventListener('click', () => {
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    });

    // Close on escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && sidebar.classList.contains('open')) {
            sidebar.classList.remove('open');
            overlay?.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
}

/**
 * Dropdown Menus
 */
function initDropdowns() {
    const dropdowns = document.querySelectorAll('.dropdown');

    dropdowns.forEach(dropdown => {
        const trigger = dropdown.querySelector('button, .dropdown-trigger');
        const menu = dropdown.querySelector('.dropdown-menu');

        if (!trigger || !menu) return;

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();

            // Close other dropdowns
            dropdowns.forEach(d => {
                if (d !== dropdown) d.classList.remove('active');
            });

            dropdown.classList.toggle('active');
        });
    });

    // Close on outside click
    document.addEventListener('click', () => {
        dropdowns.forEach(d => d.classList.remove('active'));
    });

    // Close on escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            dropdowns.forEach(d => d.classList.remove('active'));
        }
    });
}

/**
 * Modal System
 */
function initModals() {
    const modalTriggers = document.querySelectorAll('[data-modal]');
    const modals = document.querySelectorAll('.modal');
    const backdrops = document.querySelectorAll('.modal-backdrop');

    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', () => {
            const modalId = trigger.dataset.modal;
            const modal = document.getElementById(modalId);
            const backdrop = document.querySelector(`[data-modal-backdrop="${modalId}"]`);

            openModal(modal, backdrop);
        });
    });

    // Close buttons
    document.querySelectorAll('[data-modal-close]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('.modal');
            const modalId = modal?.id;
            const backdrop = document.querySelector(`[data-modal-backdrop="${modalId}"]`);

            closeModal(modal, backdrop);
        });
    });

    // Close on backdrop click
    backdrops.forEach(backdrop => {
        backdrop.addEventListener('click', () => {
            const modalId = backdrop.dataset.modalBackdrop;
            const modal = document.getElementById(modalId);

            closeModal(modal, backdrop);
        });
    });

    // Close on escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            modals.forEach(modal => {
                if (modal.classList.contains('active')) {
                    const modalId = modal.id;
                    const backdrop = document.querySelector(`[data-modal-backdrop="${modalId}"]`);
                    closeModal(modal, backdrop);
                }
            });
        }
    });
}

function openModal(modal, backdrop) {
    if (!modal) return;

    modal.classList.add('active');
    backdrop?.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal(modal, backdrop) {
    if (!modal) return;

    modal.classList.remove('active');
    backdrop?.classList.remove('active');
    document.body.style.overflow = '';
}

// Global modal functions
window.openModal = openModal;
window.closeModal = closeModal;

/**
 * Alert Dismissal
 */
function initAlerts() {
    document.querySelectorAll('.alert-close').forEach(btn => {
        btn.addEventListener('click', () => {
            const alert = btn.closest('.alert');

            alert.style.opacity = '0';
            alert.style.transform = 'translateX(100%)';

            setTimeout(() => {
                alert.remove();
            }, 300);
        });
    });
}

/**
 * Tabs
 */
function initTabs() {
    const tabGroups = document.querySelectorAll('.tabs');

    tabGroups.forEach(group => {
        const tabs = group.querySelectorAll('.tab');
        const panels = group.parentElement?.querySelectorAll('.tab-panel');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active from all tabs
                tabs.forEach(t => t.classList.remove('active'));
                panels?.forEach(p => p.classList.remove('active'));

                // Add active to clicked tab
                tab.classList.add('active');

                // Show corresponding panel
                const panelId = tab.dataset.tab;
                const panel = document.getElementById(panelId);
                panel?.classList.add('active');
            });
        });
    });
}

/**
 * Tooltips
 */
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');

    tooltips.forEach(el => {
        el.classList.add('tooltip');
    });
}

/**
 * Toggle Switch
 */
function initToggles() {
    document.querySelectorAll('.toggle').forEach(toggle => {
        toggle.addEventListener('click', () => {
            toggle.classList.toggle('active');

            const input = toggle.querySelector('input[type="checkbox"]');
            if (input) {
                input.checked = toggle.classList.contains('active');
            }
        });
    });
}

/**
 * Utility: Debounce
 */
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

/**
 * Utility: Format Date
 */
function formatDate(date, locale = 'fr-FR') {
    return new Intl.DateTimeFormat(locale, {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    }).format(new Date(date));
}

/**
 * Utility: Format Relative Time
 */
function formatRelativeTime(date) {
    const now = new Date();
    const diff = now - new Date(date);
    const seconds = Math.floor(diff / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);

    if (days > 0) return `Il y a ${days} jour${days > 1 ? 's' : ''}`;
    if (hours > 0) return `Il y a ${hours} heure${hours > 1 ? 's' : ''}`;
    if (minutes > 0) return `Il y a ${minutes} minute${minutes > 1 ? 's' : ''}`;
    return 'À l\'instant';
}

// Export utilities
window.ClubEdge = {
    debounce,
    formatDate,
    formatRelativeTime,
    openModal,
    closeModal
};
