// Initialize listeners
document.addEventListener("DOMContentLoaded", () => {
    // Close modal when clicking outside of it
    document.addEventListener('click', function(event) {
        const restoreModal = document.getElementById('restoreApplicatorModalDashboardApplicator');
        if (event.target === restoreModal) {
            closeRestoreApplicatorModal();
        }
    });

    // Listen for clicks on delete buttons in the custom parts table
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('btn-delete')) {
            const partId = event.target.getAttribute('data-part-id');
            const partName = event.target.getAttribute('data-part-name');
            confirmDeleteCustomPart(partId, partName);
        }
    });

    // Listen for clicks on edit buttons in the custom parts table
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('btn-edit')) {
            const partId = event.target.getAttribute('data-part-id');
            const partName = event.target.getAttribute('data-part-name');
            openEditCustomPartModal(partId, partName);
        }
    });

    // Close export modal when clicking outside
    const exportModal = document.getElementById('exportModal');
    if (exportModal) {
        exportModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeExportModal();
            }
        });
    }

    // Close export recently reset modal when clicking outside
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('exportModalRecentlyReset');
        if (modal && e.target === modal) {
            closeExportRecentlyResetModal();
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

        
        // Add event listener for reset form submission
        const resetForm = document.getElementById('resetForm');
        if (resetForm) {
            resetForm.addEventListener('submit', function() {
                // After successful reset, refresh progress bars
                setTimeout(function() {
                    refreshProgressBarsAfterReset();
                }, 1000); // Wait 1 second for the reset to complete
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

    // Listen for changes in the "part" dropdown
    const editWireType = document.getElementById("editWireType");
    if (editWireType) {
        editWireType.addEventListener("change", function() {
            const partName = this.value; // selected part
            const applicatorInput = document.getElementById("undo_applicator_id");
            if (!applicatorInput) return;

            const applicatorId = applicatorInput.value;

            // Do nothing if no part is selected
            if (!partName) return;

            // Send request to server to fetch reset dates
            fetch("/OMS/app/controllers/get_reset_dates_applicator.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({
                    part_name: partName,
                    applicator_id: applicatorId
                })
            })
            .then(res => res.json())
            .then(data => {
                const dropdown = document.getElementById("editStatus");
                if (!dropdown) return;
                dropdown.innerHTML = ""; // clear old options

                if (data.length > 0) {
                    data.forEach(row => {
                        dropdown.appendChild(new Option(row.reset_time, row.reset_time));
                    });
                } else {
                    dropdown.appendChild(new Option("No reset history", ""));
                }
            })
            .catch(console.error);
        });
    }
});

// Refresh the page
function refreshPage() {
    // Add loading state
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '⏳ Refreshing...';
    btn.disabled = true;

    window.location.href = '/OMS/app/views/dashboard_applicator.php';
}

// Open the reset modal
function openResetModal(button) {
    const applicatorId = button.getAttribute("data-id");
    document.getElementById("reset_applicator_id").value = applicatorId;
    document.getElementById("resetModalDashboardApplicator").style.display = "block";
    window.onclick = function(event) {
        if (event.target === document.getElementById('resetModalDashboardApplicator')) {
            document.getElementById('resetModalDashboardApplicator').style.display = 'none';
        }
    }
}

// Close the reset modal
function closeResetModal() {
    document.getElementById('resetModalDashboardApplicator').style.display = 'none';
}

// Refresh progress bars after reset
function refreshProgressBarsAfterReset() {
    // If ProgressBarManager exists, refresh the data
    if (window.progressBarManager) {
        window.progressBarManager.loadProgressData();
    }
}

// Open the undo modal
function openUndoModal(button) {
    const applicatorId = button.getAttribute("data-id");
    document.getElementById("undo_applicator_id").value = applicatorId;
    document.getElementById('undoModalDashboardApplicator').style.display = 'block';
}

// Close the undo modal
function closeUndoModal() {
    const modal = document.getElementById('undoModalDashboardApplicator');

    // Hide modal
    modal.style.display = 'none';

    // Reset the form inside modal
    const form = modal.querySelector('form');
    if (form) form.reset();

    // Clear the dates dropdown
    const dropdown = modal.querySelector('#editStatus');
    if (dropdown) {
        dropdown.innerHTML = '<option value="">Select a part first</option>';
    }
}

// Open the applicator modal
function openApplicatorModal() {
    document.getElementById('applicatorModalDashboardApplicator').style.display = 'block';
    window.onclick = function(event) {
        if (event.target === document.getElementById('applicatorModalDashboardApplicator')) {
            document.getElementById('applicatorModalDashboardApplicator').style.display = 'none';
        }
    }
}

// Close the applicator modal
function closeApplicatorModal() {
    document.getElementById('applicatorModalDashboardApplicator').style.display = 'none';
}

// Open the add custom part modal
function openAddCustomPartModal() {
    document.getElementById('addCustomPartModalDashboardApplicator').style.display = 'block';
    window.onclick = function(event) {
        if (event.target === document.getElementById('addCustomPartModalDashboardApplicator')) {
            document.getElementById('addCustomPartModalDashboardApplicator').style.display = 'none';
        }
    }
}

// Close the add custom part modal
function closeAddCustomPartModal() {
    document.getElementById('addCustomPartModalDashboardApplicator').style.display = 'none';
}

// Open the edit custom part modal
function openEditCustomPartModal(partId, partName) {
    document.getElementById('editCustomPartModalDashboardApplicator').style.display = 'block';
    document.getElementById('edit_part_id').value = partId; // Set the part ID
    document.getElementById('edit_part_name').value = partName; // Set the part name
    window.onclick = function(event) {
        if (event.target === document.getElementById('editCustomPartModalDashboardApplicator')) {
            document.getElementById('editCustomPartModalDashboardApplicator').style.display = 'none';
        }
    }
}

// Close the edit custom part modal
function closeEditCustomPartModal() {
    document.getElementById('editCustomPartModalDashboardApplicator').style.display = 'none';
}


function confirmDeleteCustomPart(partId, partName) {
    const modal = document.getElementById('deleteCustomPartModalDashboardApplicator');
    modal.style.display = 'block';
    
    // Populate the modal with part information
    document.getElementById('delete_part_id').value = partId;
    document.getElementById('delete_part_name').textContent = partName;
    document.getElementById('delete_part_id_display').textContent = '#' + partId;
    
    // Reset the confirmation checkbox and disable delete button
    document.getElementById('confirmDelete').checked = false;
    document.getElementById('deleteBtn').disabled = true;
}

// Close the delete custom part modal
function closeDeleteCustomPartModal() {
    const modal = document.getElementById('deleteCustomPartModalDashboardApplicator');
    modal.style.display = 'none';
    
    // Reset form
    const form = modal.querySelector('form');
    if (form) form.reset();
    
    // Reset confirmation checkbox and disable delete button
    document.getElementById('confirmDelete').checked = false;
    document.getElementById('deleteBtn').disabled = true;
}

// Toggle delete button based on confirmation checkbox
function toggleDeleteButton() {
    const confirmCheckbox = document.getElementById('confirmDelete');
    const deleteBtn = document.getElementById('deleteBtn');
    
    deleteBtn.disabled = !confirmCheckbox.checked;
}

// Close modal when clicking outside of it
document.addEventListener('click', function(event) {
    const deleteModal = document.getElementById('deleteCustomPartModalDashboardApplicator');
    if (event.target === deleteModal) {
        closeDeleteCustomPartModal();
    }
});

// Listen for clicks only on the restore buttons
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('restore-applicator-btn')) {
        const applicatorId = event.target.dataset.applicatorId;
        confirmRestoreApplicator(applicatorId);
    }
});

// Restore confirmation
function confirmRestoreApplicator(applicatorId) {
    const modal = document.getElementById('restoreApplicatorModalDashboardApplicator');
    modal.style.display = 'block';
    
    // Populate the modal with applicator information
    document.getElementById('restore_applicator_id').value = applicatorId;
    document.getElementById('restore_applicator_id_display').textContent = '#' + applicatorId;
    
    // Reset the confirmation checkbox and disable restore button
    document.getElementById('confirmRestore').checked = false;
    document.getElementById('restoreBtn').disabled = true;
}

// Close the restore applicator modal
function closeRestoreApplicatorModal() {
    const modal = document.getElementById('restoreApplicatorModalDashboardApplicator');
    modal.style.display = 'none';
    
    // Reset form
    const form = modal.querySelector('form');
    if (form) form.reset();
    
    // Reset confirmation checkbox and disable restore button
    document.getElementById('confirmRestore').checked = false;
    document.getElementById('restoreBtn').disabled = true;
}

// Toggle restore button based on confirmation checkbox
function toggleRestoreButton() {
    const confirmCheckbox = document.getElementById('confirmRestore');
    const restoreBtn = document.getElementById('restoreBtn');
    
    restoreBtn.disabled = !confirmCheckbox.checked;
}


// Open the parts inventory modal
function openPartsInventoryModal() {
    document.getElementById('partsInventoryModalDashboardApplicator').style.display = 'block';
    window.onclick = function(event) {
        if (event.target === document.getElementById('partsInventoryModalDashboardApplicator')) {
            document.getElementById('partsInventoryModalDashboardApplicator').style.display = 'none';
        }
    }
}

// Close the parts inventory modal
function closePartsInventoryModal() {
    document.getElementById('partsInventoryModalDashboardApplicator').style.display = 'none';
}


// State variables
selectedFormat = 'csv';
selectedDateRange = 'all';
customStartDate = '';
customEndDate = '';
includeHeaders = true;
isExporting = false;

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

// Export Recently Reset Modal functions
function exportRecentlyResetModal() {
    const modal = document.getElementById('exportModalRecentlyReset');
    if (modal) {
        modal.style.display = 'block';
    }
}

function closeExportRecentlyResetModal() {
    const modal = document.getElementById('exportModalRecentlyReset');
    if (modal) {
        modal.style.display = 'none';
    }
}
