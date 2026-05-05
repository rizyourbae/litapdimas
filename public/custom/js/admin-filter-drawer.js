/**
 * Admin Filter Drawer Logic
 * Handles both the compact search bar and the slide-out filter drawer.
 */
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.querySelector('[data-admin-filter-form]');
    if (!filterForm) return;

    const baseUrl = filterForm.getAttribute('data-filter-base-url');
    const submitBtns = document.querySelectorAll('[data-filter-submit], [data-filter-submit-main]');
    const mainSearchInput = document.getElementById('mainSearchInput');

    /**
     * Collects all filter parameters and redirects
     */
    const applyFilters = () => {
        const params = new URLSearchParams();
        
        // Collect from all data-filter-param elements (select, input, radio)
        const filterElements = document.querySelectorAll('[data-filter-param]');
        filterElements.forEach(el => {
            let value = '';
            
            if (el.type === 'radio' || el.type === 'checkbox') {
                if (el.checked) value = el.value;
                else return; // Skip if not checked
            } else {
                value = el.value;
            }

            if (value !== '') {
                params.set(el.getAttribute('data-filter-param'), value);
            }
        });

        // Sync main search input to the hidden search field if it exists
        if (mainSearchInput) {
            const searchValue = mainSearchInput.value.trim();
            if (searchValue !== '') {
                params.set('search', searchValue);
            } else {
                params.delete('search');
            }
        }

        const finalUrl = baseUrl + (params.toString() ? '?' + params.toString() : '');
        window.location.href = finalUrl;
    };

    // Attach click events to submit buttons
    submitBtns.forEach(btn => {
        btn.addEventListener('click', applyFilters);
    });

    // Handle Enter key on main search
    if (mainSearchInput) {
        mainSearchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyFilters();
            }
        });
    }
});
