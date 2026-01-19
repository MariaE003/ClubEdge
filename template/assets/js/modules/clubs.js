/**
 * ClubEdge - Clubs Module
 */

'use strict';

const Clubs = {
    /**
     * Initialize clubs functionality
     */
    init() {
        this.initSearch();
        this.initFilters();
        this.initJoinClub();
        this.initLeaveClub();
    },

    /**
     * Search clubs
     */
    initSearch() {
        const searchInput = document.querySelector('.clubs-search-input');
        if (!searchInput) return;

        const clubCards = document.querySelectorAll('.club-card');

        searchInput.addEventListener('input', ClubEdge.debounce((e) => {
            const query = e.target.value.toLowerCase().trim();

            clubCards.forEach(card => {
                const title = card.querySelector('.club-card-title')?.textContent.toLowerCase() || '';
                const description = card.querySelector('.club-card-description')?.textContent.toLowerCase() || '';

                const matches = title.includes(query) || description.includes(query);
                card.style.display = matches ? '' : 'none';
            });

            // Show empty state if no results
            this.toggleEmptyState(document.querySelectorAll('.club-card[style="display: none;"]').length === clubCards.length);
        }, 300));
    },

    /**
     * Filter clubs
     */
    initFilters() {
        const filterSelect = document.querySelector('.clubs-filters select');
        if (!filterSelect) return;

        filterSelect.addEventListener('change', (e) => {
            const category = e.target.value;
            const clubCards = document.querySelectorAll('.club-card');

            clubCards.forEach(card => {
                if (!category) {
                    card.style.display = '';
                    return;
                }

                const cardCategory = card.dataset.category || '';
                card.style.display = cardCategory === category ? '' : 'none';
            });
        });
    },

    /**
     * Join club
     */
    initJoinClub() {
        document.querySelectorAll('[data-action="join-club"]').forEach(btn => {
            btn.addEventListener('click', async () => {
                const clubId = btn.dataset.clubId;

                btn.disabled = true;
                btn.innerHTML = '<div class="spinner spinner-sm"></div>';

                try {
                    // Simulate API call
                    await this.simulateApiCall();

                    btn.innerHTML = 'Rejoint';
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline');

                    this.showNotification('Vous avez rejoint le club avec succès !', 'success');
                } catch (error) {
                    btn.disabled = false;
                    btn.innerHTML = 'Rejoindre';
                    this.showNotification('Une erreur est survenue.', 'error');
                }
            });
        });
    },

    /**
     * Leave club
     */
    initLeaveClub() {
        document.querySelectorAll('[data-action="leave-club"]').forEach(btn => {
            btn.addEventListener('click', async () => {
                if (!confirm('Êtes-vous sûr de vouloir quitter ce club ?')) return;

                const clubId = btn.dataset.clubId;

                btn.disabled = true;

                try {
                    await this.simulateApiCall();
                    window.location.reload();
                } catch (error) {
                    btn.disabled = false;
                    this.showNotification('Une erreur est survenue.', 'error');
                }
            });
        });
    },

    /**
     * Toggle empty state
     */
    toggleEmptyState(show) {
        let emptyState = document.querySelector('.clubs-empty-state');

        if (show && !emptyState) {
            emptyState = document.createElement('div');
            emptyState.className = 'clubs-empty-state empty-state col-span-full';
            emptyState.innerHTML = `
        <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <circle cx="11" cy="11" r="8"/>
          <line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <h3 class="empty-state-title">Aucun club trouvé</h3>
        <p class="empty-state-description">Essayez de modifier vos critères de recherche.</p>
      `;
            document.querySelector('.grid')?.appendChild(emptyState);
        } else if (!show && emptyState) {
            emptyState.remove();
        }
    },

    /**
     * Show notification
     */
    showNotification(message, type = 'info') {
        // Implementation would use a toast/notification system
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
document.addEventListener('DOMContentLoaded', () => Clubs.init());

// Export
window.ClubEdge = window.ClubEdge || {};
window.ClubEdge.Clubs = Clubs;
