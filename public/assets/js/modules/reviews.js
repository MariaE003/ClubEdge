/**
 * ClubEdge - Reviews Module
 * Star rating and review functionality
 */

'use strict';

const Reviews = {
    /**
     * Initialize reviews
     */
    init() {
        this.initStarRating();
        this.initReviewForm();
    },

    /**
     * Interactive star rating
     */
    initStarRating() {
        document.querySelectorAll('.star-rating').forEach(container => {
            const stars = container.querySelectorAll('.star');
            const input = container.querySelector('input[type="hidden"]');
            let currentRating = parseInt(input?.value || 0);

            stars.forEach((star, index) => {
                // Hover effect
                star.addEventListener('mouseenter', () => {
                    this.highlightStars(stars, index + 1);
                });

                // Click to set rating
                star.addEventListener('click', () => {
                    currentRating = index + 1;
                    if (input) input.value = currentRating;
                    this.setStars(stars, currentRating);
                });
            });

            // Reset on mouse leave
            container.addEventListener('mouseleave', () => {
                this.setStars(stars, currentRating);
            });
        });
    },

    /**
     * Highlight stars on hover
     */
    highlightStars(stars, count) {
        stars.forEach((star, index) => {
            star.classList.toggle('filled', index < count);
        });
    },

    /**
     * Set permanent star rating
     */
    setStars(stars, rating) {
        stars.forEach((star, index) => {
            star.classList.toggle('filled', index < rating);
        });
    },

    /**
     * Review form submission
     */
    initReviewForm() {
        const form = document.getElementById('review-form');
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');

            // Validate rating
            const rating = formData.get('rating');
            if (!rating || rating === '0') {
                this.showNotification('Veuillez donner une note.', 'warning');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<div class="spinner spinner-sm"></div> Envoi...';

            try {
                await this.simulateApiCall();

                this.showNotification('Votre avis a été enregistré !', 'success');
                form.reset();

                // Reset stars
                const stars = form.querySelectorAll('.star');
                this.setStars(stars, 0);

                // Optionally reload reviews
                this.loadReviews();
            } catch (error) {
                this.showNotification('Une erreur est survenue.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Publier mon avis';
            }
        });
    },

    /**
     * Load reviews (placeholder)
     */
    loadReviews() {
        // Would fetch reviews from API
        console.log('Loading reviews...');
    },

    /**
     * Create star rating HTML
     */
    createStarRatingHTML(rating, readonly = false) {
        const stars = [];
        for (let i = 1; i <= 5; i++) {
            const filled = i <= rating ? 'filled' : '';
            stars.push(`
        <svg class="star ${filled}" viewBox="0 0 24 24" fill="${i <= rating ? 'currentColor' : 'none'}" stroke="currentColor" stroke-width="1.5">
          <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
        </svg>
      `);
        }

        return `<div class="star-rating ${readonly ? 'readonly' : ''}">${stars.join('')}</div>`;
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
document.addEventListener('DOMContentLoaded', () => Reviews.init());

// Export
window.ClubEdge = window.ClubEdge || {};
window.ClubEdge.Reviews = Reviews;
