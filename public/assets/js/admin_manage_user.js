// Open the add user modal
function openAddUserModal() {
    document.getElementById("addUserModal").style.display = "block";
}

// Close the add user modal
function closeAddUserModal() {
    document.getElementById('addUserModal').style.display = 'none';
}

// Open the view user modal
function openViewUserModal(button) {
    document.getElementById('view_username').value = button.dataset.username || '';
    document.getElementById('view_first_name').value = button.dataset.firstname || '';
    document.getElementById('view_last_name').value = button.dataset.lastname || '';
    document.getElementById('view_role').value = button.dataset.role || '';

    document.getElementById("viewUserModal").style.display = "block";
}

// Close the view user modal
function closeViewUserModal() {
    document.getElementById('view_username').value = '';
    document.getElementById('view_first_name').value = '';
    document.getElementById('view_last_name').value = '';
    document.getElementById('view_role').value = '';
    document.getElementById('viewUserModal').style.display = 'none';
}

// Open the edit user modal
function openEditUserModal(button) {
    document.getElementById('edit_user_id').value = button.dataset.id || '';
    document.getElementById('edit_username').value = button.dataset.username || '';
    document.getElementById('edit_first_name').value = button.dataset.firstname || '';
    document.getElementById('edit_last_name').value = button.dataset.lastname || '';
    document.getElementById('edit_role').value = button.dataset.role || '';

    document.getElementById("editUserModal").style.display = "block";
}

// Close the edit user modal
function closeEditUserModal() {
    document.getElementById('edit_username').value = '';
    document.getElementById('edit_first_name').value = '';
    document.getElementById('edit_last_name').value = '';
    document.getElementById('edit_role').value = '';
    document.getElementById('editUserModal').style.display = 'none';
}

// Refresh the page
function refreshData() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '⏳ Refreshing...';
    btn.disabled = true;
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}


// Open the export modal
// Listen for clicks on the "Export Data" button to open the export modal
document.querySelectorAll('[onclick="exportData()"]').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        // Prevent default if button is inside a form
        e.preventDefault();
        document.getElementById('exportModal').style.display = 'block';
    });
});

// Listen for clicks on the close button or outside the modal to close it
document.addEventListener('DOMContentLoaded', function() {
    // Close button
    var closeBtn = document.querySelector('#exportModal .modal-close-btn');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            document.getElementById('exportModal').style.display = 'none';
        });
    }

    // Click outside modal content
    var exportModal = document.getElementById('exportModal');
    if (exportModal) {
        exportModal.addEventListener('click', function(event) {
            if (event.target === exportModal) {
                exportModal.style.display = 'none';
            }
        });
    }
});

// State variables
let selectedFormat = 'csv';
let selectedDateRange = 'all';
let customStartDate = '';
let customEndDate = '';
let includeHeaders = true;
let isExporting = false;

// Format selection
function initializeFormatOptions() {
    const formatOptions = document.querySelectorAll('.format-option');
    formatOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all options
            formatOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Add selected class to clicked option
            this.classList.add('selected');
            selectedFormat = this.dataset.format;
        });
    });
}

// Date range handling
function initializeDateRange() {
    const dateRangeSelect = document.getElementById('dateRange');
    const customDatesDiv = document.getElementById('customDates');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');

    dateRangeSelect.addEventListener('change', function() {
        selectedDateRange = this.value;
        if (this.value === 'custom') {
            customDatesDiv.classList.remove('hidden');
        } else {
            customDatesDiv.classList.add('hidden');
        }
    });

    startDateInput.addEventListener('change', function() {
        customStartDate = this.value;
    });

    endDateInput.addEventListener('change', function() {
        customEndDate = this.value;
    });
}

// Checkbox handling
function initializeCheckbox() {
    const headersCheckbox = document.getElementById('includeHeaders');
    headersCheckbox.addEventListener('change', function() {
        includeHeaders = this.checked;
    });
}

// Export handling
async function handleExport() {
    if (isExporting) return;

    isExporting = true;
    const exportBtn = document.querySelector('.export-btn');
    
    // Update button to loading state
    exportBtn.disabled = true;
    exportBtn.innerHTML = `
        <div class="loading-spinner"></div>
        Exporting...
    `;

    try {
        // Simulate export process
        await new Promise(resolve => setTimeout(resolve, 2000));
        
        // Here you would normally make an API call or generate the file
        console.log('Export configuration:', {
            format: selectedFormat,
            dateRange: selectedDateRange,
            customStartDate,
            customEndDate,
            includeHeaders
        });

        // Show success message (you could add a toast notification here)
        alert('Export completed successfully!');
        
        closeExportModal();
    } catch (error) {
        console.error('Export failed:', error);
        alert('Export failed. Please try again.');
    } finally {
        // Reset button state
        isExporting = false;
        exportBtn.disabled = false;
        exportBtn.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7,10 12,15 17,10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            Export Data
        `;
    }
}

// Close modal when clicking outside
document.getElementById('exportModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeExportModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeExportModal();
    }
});

// Close the export modal
function closeExportModal() {
    document.getElementById('exportModal').style.display = 'none';
}

// Initialize all functionality when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeFormatOptions();
    initializeDateRange();
    initializeCheckbox();
});

/*
    Utility function to debounce frequent calls to another function.
    It ensures the given function only executes after the user has
    stopped triggering it for the specified delay (in ms).
    
    Parameters:
        func  (Function) - The function to debounce.
        delay (Number)   - The time to wait (in milliseconds).
*/
function debounce(func, delay) {
    let timer;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => func.apply(this, args), delay);
    };
}

/*
    Function to apply search and role filters on the users table.
    It sends an asynchronous request to the controller with the
    current search input and selected role filter, then updates
    the table dynamically with the results.
*/
async function applyUserFilters(page = 1, limit = 10) {
    const search = document.querySelector('.search-input').value.trim();
    const role   = document.getElementById('roleFilter').value;

    // Show loading indicator
    showLoading();

    try {
        const response = await fetch(`../controllers/search_user.php?search=${encodeURIComponent(search)}&role=${encodeURIComponent(role)}&page=${page}&limit=${limit}`);
        
        if (!response.ok) {
            console.error('Network error:', response.status, response.statusText);
            const text = await response.text();
            console.error('Response text:', text);
            return;
        }

        const payload = await response.json();

        if (payload.error) {
            console.error('Server error:', payload.error);
            return;
        }

        updateUsersTable(payload.data || []);
        renderUsersPagination(payload.pagination || { page, limit, total: 0, total_pages: 1 });
    } catch (err) {
        console.error('Search failed:', err);
    }
}

/*
    Function to update the users table body with search results.
    It rebuilds the <tbody> content using the user data returned
    from the backend. If no users are found, a placeholder row is shown.
    
    Parameters:
        users (Array) - List of users returned from the backend.
*/
function updateUsersTable(users) {
    const tbody = document.getElementById('usersTableBody');
    tbody.innerHTML = '';

    if (!users.length) {
        tbody.innerHTML = `<tr><td colspan="3">No users found.</td></tr>`;
        return;
    }

    users.forEach(user => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <div class="actions">
                    <button class="view-btn"
                        onclick="openViewUserModal(this)"
                        data-username="${user.username}"
                        data-firstname="${user.first_name}"
                        data-lastname="${user.last_name}"
                        data-role="${user.user_type}">
                        View
                    </button>
                    <button class="edit-btn"
                        onclick="openEditUserModal(this)"
                        data-id="${user.user_id}"
                        data-username="${user.username}"
                        data-firstname="${user.first_name}"
                        data-lastname="${user.last_name}"
                        data-role="${user.user_type}">
                        Edit
                    </button>
                </div>
            </td>
            <td>
                <div class="user-info">
                    <div class="user-avatar">👤</div>
                    <div class="user-details">
                        <div class="user-name">${user.first_name} ${user.last_name}</div>
                        <div class="user-email">${user.username}</div>
                    </div>
                </div>
            </td>
            <td><span class="role-badge">${user.user_type}</span></td>
        `;
        tbody.appendChild(tr);
    });
}

/*
    Attach event listeners with debounce to reduce frequent queries.
    The search input will wait 500ms after the user stops typing before
    sending a request. The role filter triggers immediately on change.
*/
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners for search and filter
    const searchInput = document.querySelector('.search-input');
    const roleFilter = document.getElementById('roleFilter');
    
    if (searchInput) {
        searchInput.addEventListener('input', debounce(applyUserFilters, 500));
    }
    
    if (roleFilter) {
        roleFilter.addEventListener('change', applyUserFilters);
    }
    
    // Load initial data
    applyUserFilters();
});

// Show loading indicator
function showLoading() {
    const tbody = document.getElementById('usersTableBody');
    tbody.innerHTML = `<tr><td colspan="3">Loading...</td></tr>`;
}

// Render pagination controls for users table
function renderUsersPagination(pagination) {
    const containerId = 'usersPaginationContainer';
    let container = document.getElementById(containerId);
    if (!container) {
        container = document.createElement('div');
        container.id = containerId;
        container.className = 'pagination-container';
        const table = document.getElementById('usersTable');
        table.parentNode.appendChild(container);
    }

    const { page, total_pages, total, limit } = pagination;

    const makeBtn = (label, targetPage, disabled = false, current = false) => {
        const cls = ['pagination-btn'];
        if (disabled) cls.push('disabled');
        if (current) cls.push('pagination-current');
        return `<button class="${cls.join(' ')}" ${disabled ? 'disabled' : ''} data-page="${targetPage}">${label}</button>`;
    };

    const prevDisabled = page <= 1;
    const nextDisabled = page >= total_pages;
    const start = Math.max(1, page - 2);
    const end = Math.min(total_pages, page + 2);

    let numbers = '';
    if (start > 1) {
        numbers += makeBtn('1', 1, false, page === 1);
        if (start > 2) numbers += `<span class="pagination-ellipsis">...</span>`;
    }
    for (let i = start; i <= end; i++) {
        numbers += makeBtn(String(i), i, false, i === page);
    }
    if (end < total_pages) {
        if (end < total_pages - 1) numbers += `<span class="pagination-ellipsis">...</span>`;
        numbers += makeBtn(String(total_pages), total_pages, false, page === total_pages);
    }

    container.innerHTML = `
        <div class="pagination-info">
            <span class="pagination-text">Page ${page} of ${total_pages} • ${total.toLocaleString()} users</span>
        </div>
        <div class="pagination-controls" style="position: relative; right: 300px;">
            ${makeBtn('← Previous', page - 1, prevDisabled)}
            <div class="pagination-numbers">${numbers}</div>
            ${makeBtn('Next →', page + 1, nextDisabled)}
        </div>
    `;

    container.querySelectorAll('button[data-page]')
        .forEach(btn => btn.addEventListener('click', () => {
            const target = parseInt(btn.getAttribute('data-page'), 10);
            applyUserFilters(target, limit);
        }));
}
