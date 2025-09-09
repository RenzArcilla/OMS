// Global variables for pagination state
let currentPageMachines = 1;
let itemsPerPageMachines = 10;
let totalPagesMachines = 1;
let totalMachinesCount = 0;
let currentSearchMachines = '';
let currentDescriptionMachines = 'ALL';

let disabledMachineSearchTimeout = null;

// Initialize pagination on page load
document.addEventListener("DOMContentLoaded", () => {
    // Get URL parameters and set initial state
    const urlParams = new URLSearchParams(window.location.search);
    
    currentPageMachines = parseInt(urlParams.get('page')) || 1;
    itemsPerPageMachines = parseInt(urlParams.get('items_per_page')) || 10;
    currentSearchMachines = urlParams.get('search') || '';
    currentDescriptionMachines = urlParams.get('description') || 'ALL';
    
    // Set initial values in the UI
    const searchInput = document.querySelector('#disabled-machines-section .search-input');
    const descriptionSelect = document.getElementById('disabledMachineDescription');
    
    if (searchInput) searchInput.value = currentSearchMachines;
    if (descriptionSelect) descriptionSelect.value = currentDescriptionMachines;
    
    loadDisabledMachines();
});

// Debounced function to apply filters
async function applyDisabledMachineFilters() {
    clearTimeout(disabledMachineSearchTimeout);
    
    disabledMachineSearchTimeout = setTimeout(async () => {
        const searchInput = document.querySelector('#disabled-machines-section .search-input');
        const descriptionSelect = document.getElementById('disabledMachineDescription');
        
        currentSearchMachines = searchInput ? searchInput.value.trim() : '';
        currentDescriptionMachines = descriptionSelect ? descriptionSelect.value : 'ALL';
        
        // Reset to first page when filters change
        currentPageMachines = 1;
        
        // Update URL
        updateURLMachines();
        
        // Load data with new filters
        await loadDisabledMachines();
    }, 300);
}

// Load disabled machines data
async function loadDisabledMachines() {
    const section = document.getElementById('disabled-machines-section');
    const tbody = section.querySelector('.data-table tbody');
    
    // Show loading spinner
    tbody.innerHTML = `
        <tr>
            <td colspan="5" style="text-align:center;">
                <div class="loading-spinner"></div>
            </td>
        </tr>
    `;
    
    try {
        const params = new URLSearchParams({
            page: currentPageMachines,
            items_per_page: itemsPerPageMachines,
            search: currentSearchMachines,
            description: currentDescriptionMachines
        });
        
        const response = await fetch(`/OMS/app/controllers/get_disabled_machines.php?${params}`);
        const result = await response.json();
        
        if (!result.success) {
            updateDisabledMachinesTable([], result.empty_db || false);
            return;
        }
        
        // Update pagination state
        totalPagesMachines = result.pagination.total_pages;
        totalMachinesCount = result.pagination.total_records;
        
        // Update table and pagination controls
        updateDisabledMachinesTable(result.data, result.empty_db || false);
        updatePaginationControlsMachines(result.pagination);
        
    } catch (error) {
        console.error('Failed to load disabled machines:', error);
        tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;">Failed to load machines</td></tr>';
    }
}

// Update the disabled machines table
function updateDisabledMachinesTable(machines, emptyDb = false) {
    const tbody = document.querySelector('#disabled-machines-section .data-table tbody');
    tbody.innerHTML = '';
    
    if (!Array.isArray(machines) || machines.length === 0) {
        tbody.innerHTML = emptyDb
            ? '<tr><td colspan="5" style="text-align:center;">No machines available yet</td></tr>'
            : '<tr><td colspan="5" style="text-align:center;">No results found</td></tr>';
        return;
    }
    
    machines.forEach(machine => {
        const row = `
            <tr>
                <td>
                    <div class="actions">
                        <button class="restore-btn restore-machine-btn"
                                data-machine-id="${machine.machine_id}">
                            Restore
                        </button>
                    </div>
                </td>
                <td>${machine.control_no}</td>
                <td>${machine.model}</td>
                <td>${machine.maker}</td>
                <td>${machine.last_encoded || ''}</td>
            </tr>
        `;
        tbody.insertAdjacentHTML("beforeend", row);
    });
}

// Update pagination controls
function updatePaginationControlsMachines(pagination) {
    const paginationContainer = document.getElementById('disabled-machines-pagination');
    if (!paginationContainer) return;
    
    // Show/hide pagination based on total pages
    if (pagination.total_pages <= 1) {
        paginationContainer.style.display = 'none';
        return;
    }
    
    paginationContainer.style.display = 'block';
    
    // Update pagination info
    const infoText = document.getElementById('pagination-info-text-machines');
    if (infoText) {
        infoText.textContent = `Showing ${pagination.showing_from} to ${pagination.showing_to} of ${pagination.total_records} machines`;
    }
    
    // Update previous/next buttons
    const prevBtn = document.getElementById('pagination-prev-machines');
    const nextBtn = document.getElementById('pagination-next-machines');
    
    if (prevBtn) {
        prevBtn.disabled = pagination.current_page <= 1;
        prevBtn.onclick = () => goToPageMachines(pagination.current_page - 1);
    }
    
    if (nextBtn) {
        nextBtn.disabled = pagination.current_page >= pagination.total_pages;
        nextBtn.onclick = () => goToPageMachines(pagination.current_page + 1);
    }
    
    // Update page numbers
    updatePageNumbersMachines(pagination.current_page, pagination.total_pages);
    
    // Update items per page selector
    const itemsPerPageSelect = document.getElementById('items-per-page-machines');
    if (itemsPerPageSelect) {
        itemsPerPageSelect.value = pagination.items_per_page;
    }
}

// Update page numbers
function updatePageNumbersMachines(currentPage, totalPages) {
    const numbersContainer = document.getElementById('pagination-numbers-machines');
    if (!numbersContainer) return;
    
    numbersContainer.innerHTML = '';
    
    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
    
    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    // Add ellipsis at the beginning if needed
    if (startPage > 1) {
        numbersContainer.insertAdjacentHTML('beforeend', '<span class="pagination-ellipsis">...</span>');
    }
    
    // Add page numbers
    for (let i = startPage; i <= endPage; i++) {
        const pageLink = document.createElement('a');
        pageLink.href = '#';
        pageLink.textContent = i;
        pageLink.className = i === currentPage ? 'pagination-btn pagination-current' : 'pagination-btn';
        pageLink.onclick = (e) => {
            e.preventDefault();
            goToPageMachines(i);
        };
        numbersContainer.appendChild(pageLink);
    }
    
    // Add ellipsis at the end if needed
    if (endPage < totalPages) {
        numbersContainer.insertAdjacentHTML('beforeend', '<span class="pagination-ellipsis">...</span>');
    }
}

// Navigate to specific page
async function goToPageMachines(page) {
    if (page < 1 || page > totalPagesMachines) return;
    
    currentPageMachines = page;
    updateURLMachines();
    await loadDisabledMachines();
}

// Change items per page
async function changeItemsPerPageMachines(newItemsPerPage) {
    itemsPerPageMachines = parseInt(newItemsPerPage);
    currentPageMachines = 1; // Reset to first page
    updateURLMachines();
    await loadDisabledMachines();
}

// Update URL without page reload
function updateURLMachines() {
    const url = new URL(window.location);
    
    if (currentPageMachines > 1) {
        url.searchParams.set('page', currentPageMachines);
    } else {
        url.searchParams.delete('page');
    }
    
    if (itemsPerPageMachines !== 10) {
        url.searchParams.set('items_per_page', itemsPerPageMachines);
    } else {
        url.searchParams.delete('items_per_page');
    }
    
    if (currentSearchMachines) {
        url.searchParams.set('search', currentSearchMachines);
    } else {
        url.searchParams.delete('search');
    }
    
    if (currentDescriptionMachines !== 'ALL') {
        url.searchParams.set('description', currentDescriptionMachines);
    } else {
        url.searchParams.delete('description');
    }
    
    window.history.pushState({}, '', url);
}
