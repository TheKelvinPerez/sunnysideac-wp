/**
 * AJAX Cities Pagination
 */

document.addEventListener('DOMContentLoaded', function() {
    // Load cities page via AJAX
    window.loadCitiesPage = function(page) {
        const citiesGrid = document.querySelector('.cities-archive .grid');
        const paginationContainer = document.querySelector('.cities-pagination');

        if (!citiesGrid) return;

        // Show loading state
        citiesGrid.style.opacity = '0.5';

        // Prepare data for AJAX request
        const formData = new FormData();
        formData.append('action', 'cities_pagination');
        formData.append('page', page);
        formData.append('nonce', citiesPagination.nonce);

        // Make AJAX request
        fetch(citiesPagination.ajaxurl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cities grid with new HTML
                citiesGrid.innerHTML = data.data.html;

                // Update pagination
                if (paginationContainer && data.data.pagination) {
                    paginationContainer.innerHTML = data.data.pagination;
                }

                // Scroll to top of cities section
                const citiesSection = document.querySelector('.cities-archive');
                if (citiesSection) {
                    citiesSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            } else {
                console.error('Error loading cities:', data);
                // Fallback: reload page
                window.location.href = '?paged=' + page;
            }
        })
        .catch(error => {
            console.error('AJAX Error:', error);
            // Fallback: reload page
            window.location.href = '?paged=' + page;
        })
        .finally(() => {
            // Restore opacity
            citiesGrid.style.opacity = '1';
        });
    };

    // Initialize pagination container
    const paginationContainer = document.querySelector('.cities-pagination');
    if (paginationContainer) {
        paginationContainer.classList.add('cities-pagination');
    }
});