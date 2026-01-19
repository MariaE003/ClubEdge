/**
 * ClubEdge - Events Module
 */

'use strict';

const Events = {
    /**
     * Initialize events functionality
     */
    init() {
        this.initEventRegistration();
        this.initEventFilters();
        this.initEventGallery();
    },

    /**
     * Event registration
     */
    initEventRegistration() {
        document.querySelectorAll('[data-action="register-event"]').forEach(btn => {
            btn.addEventListener('click', async () => {
                const eventId = btn.dataset.eventId;

                btn.disabled = true;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<div class="spinner spinner-sm"></div> Inscription...';

                try {
                    await this.simulateApiCall();

                    btn.innerHTML = 'Inscrit';
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline');

                    this.showNotification('Vous êtes inscrit à cet événement !', 'success');
                } catch (error) {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    this.showNotification('Une erreur est survenue.', 'error');
                }
            });
        });
    },

    /**
     * Event filters
     */
    initEventFilters() {
        const filterBtns = document.querySelectorAll('.event-filter-btn');
        const eventCards = document.querySelectorAll('.event-card');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Update active state
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const filter = btn.dataset.filter;

                eventCards.forEach(card => {
                    if (filter === 'all') {
                        card.style.display = '';
                        return;
                    }

                    const status = card.dataset.status;
                    card.style.display = status === filter ? '' : 'none';
                });
            });
        });
    },

    /**
     * Event image gallery
     */
    initEventGallery() {
        const galleryItems = document.querySelectorAll('.event-gallery-item');

        galleryItems.forEach((item, index) => {
            item.addEventListener('click', () => {
                this.openLightbox(index);
            });
        });
    },

    /**
     * Open lightbox
     */
    openLightbox(startIndex = 0) {
        const images = Array.from(document.querySelectorAll('.event-gallery-item img'))
            .map(img => img.src);

        if (images.length === 0) return;

        // Create lightbox
        const lightbox = document.createElement('div');
        lightbox.className = 'lightbox';
        lightbox.innerHTML = `
      <div class="lightbox-backdrop"></div>
      <div class="lightbox-content">
        <button class="lightbox-close btn btn-icon btn-ghost">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
        <button class="lightbox-prev btn btn-icon btn-ghost">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <polyline points="15 18 9 12 15 6"/>
          </svg>
        </button>
        <img class="lightbox-image" src="${images[startIndex]}" alt="">
        <button class="lightbox-next btn btn-icon btn-ghost">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <polyline points="9 18 15 12 9 6"/>
          </svg>
        </button>
      </div>
    `;

        document.body.appendChild(lightbox);
        document.body.style.overflow = 'hidden';

        let currentIndex = startIndex;

        // Navigation
        lightbox.querySelector('.lightbox-prev').addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            lightbox.querySelector('.lightbox-image').src = images[currentIndex];
        });

        lightbox.querySelector('.lightbox-next').addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % images.length;
            lightbox.querySelector('.lightbox-image').src = images[currentIndex];
        });

        // Close
        const closeLightbox = () => {
            lightbox.remove();
            document.body.style.overflow = '';
        };

        lightbox.querySelector('.lightbox-close').addEventListener('click', closeLightbox);
        lightbox.querySelector('.lightbox-backdrop').addEventListener('click', closeLightbox);

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') lightbox.querySelector('.lightbox-prev').click();
            if (e.key === 'ArrowRight') lightbox.querySelector('.lightbox-next').click();
        }, { once: true });
    },

    /**
     * Show notification
     */
    showNotification(message, type = 'info') {
        console.log(`[${type}] ${message}`);
    },

    /**
     * Simulate API call
     */
    simulateApiCall(delay = 1000) {
        return new Promise(resolve => setTimeout(resolve, delay));
    }
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => Events.init());

// Export
window.ClubEdge = window.ClubEdge || {};
window.ClubEdge.Events = Events;
