// Initialize event listeners when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function () {
    // Close modal when clicking outside
    const modalOverlay = document.getElementById('modalOverlay');
    if (modalOverlay) {
        modalOverlay.addEventListener('click', function(e) {
            if (e.target === modalOverlay) {
                closeModal();
            }
        });
    }

    // Optional: Close modal on overlay click
    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('deleteRecordModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeDeleteRecordModal();
            });
        }
    });

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
        
        // Also try to initialize after a short delay in case elements aren't ready
        setTimeout(() => {
            initializeDateRange();
        }, 500);
    });
});

// Refresh functionality
function refreshPage(btn) {
    const originalText = btn.innerHTML;
    btn.innerHTML = '⏳ Refreshing...';
    btn.disabled = true;
    window.location.href = '/OMS/app/views/record_output.php';
}

// Modal functions
function openModal() {
    document.getElementById('modalOverlay').classList.add('active');
}

function closeModal() {
    document.getElementById('modalOverlay').classList.remove('active');
}

function openDeleteRecordModal(recordId) {
    const modal = document.getElementById('deleteRecordModal');
    const recordIdInput = document.getElementById('delete_record_id');
    
    if (!modal) {
        console.error('Delete modal not found!');
        return;
    }
    
    if (!recordIdInput) {
        console.error('Delete record ID input not found!');
        return;
    }
    
    recordIdInput.value = recordId;
    
    // Use the same approach as the working edit modal
    modal.style.display = 'block';
    
    // Debug: Check if modal is actually visible
    setTimeout(() => {
        const computedStyle = window.getComputedStyle(modal);
    }, 100);
}
function closeDeleteRecordModal() {
    const modal = document.getElementById('deleteRecordModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.remove('active');
    }
}

// State variables
let selectedFormat = 'csv';
let selectedDateRange = 'today';
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
function initializeDateRange() {
    const dateRangeSelect = document.getElementById('dateRange');
    const customDatesDiv = document.getElementById('customDates');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');

    dateRangeSelect.addEventListener('change', function() {
        selectedDateRange = this.value;
        if (this.value === 'custom') {
            customDatesDiv.classList.remove('hidden');  // Shows the date inputs
        } else {
            customDatesDiv.classList.add('hidden');     // Hides the date inputs
        }
    });

    // Handle date input changes
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

// Re-initialize date range when export modal is opened
function openExportModal() {
    const modal = document.getElementById('exportModal');
    if (modal) {
        modal.style.display = 'block';
        // Re-initialize date range functionality when modal opens
        setTimeout(() => {
            initializeDateRange();
        }, 100);
    } else {
        console.error('Export modal not found!');
    }
}


// Close export modal
function closeExportModal() {
    const modal = document.getElementById('exportModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Funtion to download the file upload format
function downloadFileUploadFormat() {
    const link = document.createElement('a');
    link.href = '/OMS/app/dl/oms_file_upload_format.xlsx';
    link.download = 'oms_file_upload_format.xlsx';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

// Simple function to toggle custom dates - called by onclick
function toggleCustomDates(selectElement) {
    const customDatesDiv = document.getElementById('customDates');
    
    if (customDatesDiv) {
        if (selectElement.value === 'custom') {
            // Show custom dates
            customDatesDiv.classList.remove('hidden');
            customDatesDiv.style.display = 'grid';
            customDatesDiv.style.visibility = 'visible';
        } else {
            // Hide custom dates
            customDatesDiv.classList.add('hidden');
            customDatesDiv.style.display = 'none';
            customDatesDiv.style.visibility = 'hidden';
        }
    }
}
