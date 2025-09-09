// Refresh functionality
function refreshData() {
    location.reload();
}

// Modal functions
function openModal() {
    document.getElementById('modalOverlay').classList.add('active');
}

function closeModal() {
    document.getElementById('modalOverlay').classList.remove('active');
}

// Close modal when clicking outside
document.getElementById('modalOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});


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
    console.log('🚀 DOM Content Loaded - Starting initialization');
    initializeFormatOptions();
    initializeDateRange();
    initializeCheckbox();
    
    // Also try to initialize after a short delay in case elements aren't ready
    setTimeout(() => {
        console.log('🔄 Delayed initialization attempt');
        initializeDateRange();
    }, 500);
});

// Re-initialize date range when export modal is opened
function openExportModal() {
    console.log('🚀 Opening export modal');
    const modal = document.getElementById('exportModal');
    if (modal) {
        modal.style.display = 'block';
        console.log('✅ Modal opened, re-initializing date range in 100ms');
        // Re-initialize date range functionality when modal opens
        setTimeout(() => {
            console.log('🔄 Re-initializing date range...');
            initializeDateRange();
        }, 100);
    } else {
        console.error('❌ Export modal not found!');
    }
}


// Close export modal
function closeExportModal() {
    const modal = document.getElementById('exportModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

downloadFileUploadFormat = () => {
    const link = document.createElement('a');
    link.href = '/OMS/app//dl/oms_file_upload_format.xlsx';
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

// Manual test function - run this in console to test
window.testCustomDates = function() {
    console.log('🧪 Manual test - forcing custom dates to show');
    const customDatesDiv = document.getElementById('customDates');
    const dateRangeSelect = document.getElementById('dateRange');
    
    if (customDatesDiv) {
        console.log('✅ Custom dates div found');
        console.log('📊 Before - classes:', customDatesDiv.className);
        console.log('📊 Before - computed display:', window.getComputedStyle(customDatesDiv).display);
        
        // Force show
        customDatesDiv.classList.remove('hidden');
        customDatesDiv.style.display = 'grid';
        customDatesDiv.style.visibility = 'visible';
        customDatesDiv.style.opacity = '1';
        
        console.log('📊 After - classes:', customDatesDiv.className);
        console.log('📊 After - style display:', customDatesDiv.style.display);
        console.log('📊 After - computed display:', window.getComputedStyle(customDatesDiv).display);
        console.log('📊 After - computed visibility:', window.getComputedStyle(customDatesDiv).visibility);
        console.log('📊 After - computed opacity:', window.getComputedStyle(customDatesDiv).opacity);
        
        // Also set the dropdown to custom
        if (dateRangeSelect) {
            dateRangeSelect.value = 'custom';
            console.log('📅 Set dropdown to custom');
        }
    } else {
        console.error('❌ Custom dates div not found!');
    }
};