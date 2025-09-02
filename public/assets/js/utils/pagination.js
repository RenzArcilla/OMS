// Pagination Functions
function changeItemsPerPage(itemsPerPage) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('items_per_page', itemsPerPage);
    currentUrl.searchParams.set('page', '1'); // Reset to first page
    window.location.href = currentUrl.toString();
}

// Preserve search parameters when navigating pagination
function updatePaginationLinks() {
    const currentUrl = new URL(window.location);
    const searchParams = currentUrl.searchParams;
    
    // Get current search and filter parameters
    const searchHp = searchParams.get('search_hp');
    const filterBy = searchParams.get('filter_by');
    const itemsPerPage = searchParams.get('items_per_page');
    
    // Update all pagination links to preserve these parameters
    const paginationLinks = document.querySelectorAll('.pagination-btn[href]');
    paginationLinks.forEach(link => {
        const linkUrl = new URL(link.href);
        if (searchHp) linkUrl.searchParams.set('search_hp', searchHp);
        if (filterBy) linkUrl.searchParams.set('filter_by', filterBy);
        if (itemsPerPage) linkUrl.searchParams.set('items_per_page', itemsPerPage);
        link.href = linkUrl.toString();
    });
}

// Initialize pagination when page loads
document.addEventListener('DOMContentLoaded', function() {
    updatePaginationLinks();
});