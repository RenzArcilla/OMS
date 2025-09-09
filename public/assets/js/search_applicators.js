let applicatorSearchTimeout = null;

// Pagination state
let applicatorCurrentPage = 1;
let applicatorTotalPages = 1;
let applicatorTotalCount = 0;
let applicatorLimit = 20;

/*
    Apply search and filters to the applicator table.
    Debounced to reduce the number of AJAX requests on typing.
*/
async function applyApplicatorFilters(searchQuery = '', page = 1, limit = 20) {
    clearTimeout(applicatorSearchTimeout); // cancel any previous scheduled fetch

    applicatorSearchTimeout = setTimeout(async () => {
        const description = document.getElementById('applicatorDescription').value;
        const type = document.getElementById('applicatorWireType').value;

        const tbody = document.getElementById("applicator-body");
        
        // Show loading spinner
        tbody.innerHTML = `
            <tr>
                <td colspan="9" style="text-align:center;">
                    <div class="loading-spinner"></div>
                </td>
            </tr>
        `;

        try {
            // Fetch filtered applicators from controller
            const url = `/OMS/app/controllers/search_applicators.php?q=${encodeURIComponent(searchQuery)}&description=${encodeURIComponent(description)}&type=${encodeURIComponent(type)}&page=${page}&limit=${limit}`;
            console.log('Fetching applicators from:', url);
            
            const response = await fetch(url);
            const data = await response.json();
            
            console.log('Applicators response:', data);

            if (!data.success) {
                console.error("Applicator search failed:", data.error);
                updateApplicatorTable([]); // Show empty table if search fails
                return;
            }

            // Update table with filtered results
            updateApplicatorTable(data.data, data.empty_db);
            
            // Update pagination if available
            if (data.pagination) {
                updateApplicatorPagination(data.pagination);
            }
        } catch (err) {
            console.error("Applicator search failed:", err);
        }
    }, 300); // debounce delay in milliseconds
}

/*
    Dynamically update the applicator table rows.
    Clears previous rows and renders new ones.
*/
function updateApplicatorTable(applicators, emptyDb = false) {
    const tbody = document.getElementById("applicator-body");
    tbody.innerHTML = "";

    if (applicators.length === 0) {
        tbody.innerHTML = emptyDb
            ? `<tr><td colspan="9" style="text-align:center;">No applicators available yet</td></tr>`
            : `<tr><td colspan="9" style="text-align:center;">No results found</td></tr>`;
        return;
    }

    // Loop through each applicator and render table row
    applicators.forEach(row => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>
                <div class="actions">
                    <button class="edit-btn"
                        type="button"
                        onclick="openApplicatorEditModal(this)"
                        data-id="${row.applicator_id}"
                        data-control="${row.hp_no}"
                        data-terminal="${row.terminal_no}"
                        data-description="${row.description}"
                        data-wire="${row.wire}"
                        data-terminal-maker="${row.terminal_maker}"
                        data-applicator-maker="${row.applicator_maker}"
                        data-serial="${row.serial_no}"
                        data-invoice="${row.invoice_no}"
                    >Edit</button>

                    <form action="/OMS/app/controllers/disable_applicator.php" method="POST" style="display:inline;">
                        <input type="hidden" name="applicator_id" value="${row.applicator_id}">
                        <button 
                            type="button"
                            class="delete-btn"
                            onclick="openApplicatorDeleteModal(this)"
                        >Delete</button>
                    </form>
                </div>
            </td>
            <td>${row.hp_no}</td>
            <td>${row.terminal_no}</td>
            <td>${row.description}</td>
            <td>${row.wire}</td>
            <td>${row.terminal_maker}</td>
            <td>${row.applicator_maker}</td>
            <td>${row.serial_no}</td>
            <td>${row.invoice_no}</td>
        `;
        tbody.appendChild(tr);
    });
}

// Update pagination UI
function updateApplicatorPagination(pagination) {
    applicatorCurrentPage = pagination.current_page;
    applicatorTotalPages = pagination.total_pages;
    applicatorTotalCount = pagination.total_count;
    applicatorLimit = pagination.limit;
    
    const paginationContainer = document.getElementById('applicator-pagination');
    const paginationInfo = document.getElementById('applicator-pagination-info');
    const prevBtn = document.getElementById('applicator-prev-btn');
    const nextBtn = document.getElementById('applicator-next-btn');
    const paginationNumbers = document.getElementById('applicator-pagination-numbers');
    
    if (!paginationContainer) return;
    
    // Show pagination if there are multiple pages
    if (applicatorTotalPages > 1) {
        paginationContainer.style.display = 'block';
    } else {
        paginationContainer.style.display = 'none';
        return;
    }
    
    // Update pagination info
    const startItem = pagination.offset + 1;
    const endItem = Math.min(pagination.offset + pagination.limit, pagination.total_count);
    paginationInfo.textContent = `Showing ${startItem} to ${endItem} of ${pagination.total_count} results`;
    
    // Update prev/next buttons
    prevBtn.disabled = applicatorCurrentPage <= 1;
    prevBtn.classList.toggle('disabled', applicatorCurrentPage <= 1);
    
    nextBtn.disabled = applicatorCurrentPage >= applicatorTotalPages;
    nextBtn.classList.toggle('disabled', applicatorCurrentPage >= applicatorTotalPages);
    
    // Generate page numbers
    generateApplicatorPageNumbers(paginationNumbers, applicatorCurrentPage, applicatorTotalPages);
}

// Generate page number buttons
function generateApplicatorPageNumbers(container, currentPage, totalPages) {
    container.innerHTML = '';
    
    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
    
    // Adjust start page if we're near the end
    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    // Add first page and ellipsis if needed
    if (startPage > 1) {
        addApplicatorPageButton(container, 1, currentPage);
        if (startPage > 2) {
            addApplicatorEllipsis(container);
        }
    }
    
    // Add page numbers
    for (let i = startPage; i <= endPage; i++) {
        addApplicatorPageButton(container, i, currentPage);
    }
    
    // Add ellipsis and last page if needed
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            addApplicatorEllipsis(container);
        }
        addApplicatorPageButton(container, totalPages, currentPage);
    }
}

// Add a page button
function addApplicatorPageButton(container, pageNum, currentPage) {
    const button = document.createElement('button');
    button.className = `pagination-btn ${pageNum === currentPage ? 'pagination-current' : ''}`;
    button.textContent = pageNum;
    button.onclick = () => goToApplicatorPage(pageNum);
    container.appendChild(button);
}

// Add ellipsis
function addApplicatorEllipsis(container) {
    const ellipsis = document.createElement('span');
    ellipsis.className = 'pagination-ellipsis';
    ellipsis.textContent = '...';
    container.appendChild(ellipsis);
}

// Go to specific page
function goToApplicatorPage(page) {
    if (page >= 1 && page <= applicatorTotalPages && page !== applicatorCurrentPage) {
        applicatorCurrentPage = page;
        applyApplicatorFilters('', page, applicatorLimit);
    }
}

// Go to previous page
function goToApplicatorPrevPage() {
    if (applicatorCurrentPage > 1) {
        goToApplicatorPage(applicatorCurrentPage - 1);
    }
}

// Go to next page
function goToApplicatorNextPage() {
    if (applicatorCurrentPage < applicatorTotalPages) {
        goToApplicatorPage(applicatorCurrentPage + 1);
    }
}

// Change items per page
function changeApplicatorItemsPerPage() {
    const select = document.getElementById('applicator-items-per-page');
    if (select) {
        applicatorLimit = parseInt(select.value);
        applicatorCurrentPage = 1; // Reset to first page
        applyApplicatorFilters('', 1, applicatorLimit);
    }
}

// Load initial applicator data on page load
document.addEventListener("DOMContentLoaded", () => {
    applyApplicatorFilters(); // Initial fetch without filters
    
    // Add event listeners for pagination
    const prevBtn = document.getElementById('applicator-prev-btn');
    const nextBtn = document.getElementById('applicator-next-btn');
    const itemsPerPageSelect = document.getElementById('applicator-items-per-page');
    
    if (prevBtn) prevBtn.onclick = goToApplicatorPrevPage;
    if (nextBtn) nextBtn.onclick = goToApplicatorNextPage;
    if (itemsPerPageSelect) itemsPerPageSelect.onchange = changeApplicatorItemsPerPage;
});
