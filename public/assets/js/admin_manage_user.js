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

// Initialize all functionality when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeFormatOptions();
    initializeDateRange();
    initializeCheckbox();
});