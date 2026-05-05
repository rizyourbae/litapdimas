/**
 * Sidebar Search Logic
 * Functional approach, decoupled from the UI.
 */
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('sidebar-menu-search');
    const menuContainer = document.getElementById('navigation');
    
    if (!searchInput || !menuContainer) return;

    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        filterMenuItems(query);
    });

    /**
     * Filters menu items based on the search query
     */
    function filterMenuItems(query) {
        const items = menuContainer.querySelectorAll('.nav-item');
        
        // If query is empty, show everything and collapse expanded ones
        if (query === '') {
            items.forEach(item => {
                item.classList.remove('d-none', 'search-match', 'search-parent-match');
            });
            return;
        }

        items.forEach(item => {
            const link = item.querySelector('.nav-link');
            const label = link ? link.textContent.toLowerCase() : '';
            const isMatch = label.includes(query);

            if (isMatch) {
                item.classList.add('search-match');
                item.classList.remove('d-none');
                
                // Show all parents
                showParents(item);
                
                // If it's a parent itself, show all children (optional)
                // showChildren(item);
            } else {
                item.classList.remove('search-match');
                item.classList.add('d-none');
            }
        });
    }

    /**
     * Recursively shows parents of a matched item
     */
    function showParents(item) {
        let parent = item.parentElement.closest('.nav-item');
        while (parent) {
            parent.classList.remove('d-none');
            parent.classList.add('search-parent-match');
            
            // Expand the treeview if it's a parent
            if (parent.classList.contains('menu-open') === false) {
                const link = parent.querySelector('.nav-link');
                // AdminLTE/Bootstrap Treeview expand logic (simple version)
                parent.classList.add('menu-open');
                const subMenu = parent.querySelector('.nav-treeview');
                if (subMenu) subMenu.style.display = 'block';
            }
            
            parent = parent.parentElement.closest('.nav-item');
        }
    }
});
