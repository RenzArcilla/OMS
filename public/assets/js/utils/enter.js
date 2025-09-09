document.addEventListener('DOMContentLoaded', function() {
    // Universal modal opener - works with any button that has data-modal attribute
    document.addEventListener('click', function(event) {
        const button = event.target.closest('[data-modal]');
        if (button) {
            const modalId = button.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'block';
            }
        }

        // Handle edit button
        if (event.target.classList.contains('edit-btn')) {
            const editModals = document.querySelectorAll('[id*="editCustomPartModal"]');
            editModals.forEach(modal => modal.style.display = 'block');
        }

        // Handle delete button
        if (event.target.classList.contains('delete-btn')) {
            const deleteModals = document.querySelectorAll('[id*="deleteCustomPartModal"]');
            deleteModals.forEach(modal => modal.style.display = 'block');
        }

        // Handle modal-btn (by text) - but skip specific buttons with onclick handlers
        if (event.target.closest('.modal-btn')) {
            const buttonText = event.target.textContent.trim();

            // Skip buttons that have onclick handlers and specific text
            if (buttonText === 'Add Parts' && event.target.hasAttribute('onclick')) {
                return; // Let the onclick handler work
            }
            if (buttonText === 'Export Applicators') {
                const modal = document.getElementById('exportApplicatorReportModal');
                if (modal) modal.style.display = 'block';
            } else if (buttonText === 'Export Machines') {
                const modal = document.getElementById('exportMachineReportModal');
                if (modal) modal.style.display = 'block';
            } else if (buttonText === 'Edit Maximum Output') {
                const modal = document.getElementById('editMaxOutputModal');
                if (modal) modal.style.display = 'block';
            } else {
                // Default modal handling for other modal-btn clicks
                const modal = document.getElementById('exportModalRecentlyReset');
                if (modal) modal.style.display = 'block';
            } 


        }

        if (event.target.classList.contains('modal-btn')) {
            const modal = document.getElementById('modalOverlay');
            if (modal) modal.style.display = 'block';
        }

        // Handle logout button
        if (event.target.classList.contains('logout-btn')) {
            const modal = document.getElementById('logoutModalOverlay');
            if (modal) modal.style.display = 'block';
        }

        // Handle export button
        if (event.target.classList.contains('export-btn')) {
            const exportModals = document.querySelectorAll('[id*="exportModal"]');
            exportModals.forEach(modal => modal.style.display = 'block');
        }

        // Handle restore button
        if (event.target.classList.contains('restore-output-btn')) {
            const modal = document.getElementById('restoreOutputModal');
            if (modal) modal.style.display = 'block';
        }

        // Handle restore machine button
        if (event.target.classList.contains('restore-machine-btn')) {
            const button = event.target;
            const machineId = button.getAttribute("data-machine-id");
            
            // Open the machine restore modal (using existing modal ID)
            const modal = document.getElementById('restoreApplicatorModalDashboardApplicator');
            if (modal && machineId) {
                // Set the machine ID in the form (using existing field name)
                const machineIdField = document.getElementById('restore_applicator_id');
                if (machineIdField) {
                    machineIdField.value = machineId;
                }
                
                // Update the display
                const displayField = document.getElementById('restore_applicator_id_display');
                if (displayField) {
                    displayField.textContent = '#' + machineId;
                }
                
                // Reset checkbox and button state
                const checkbox = modal.querySelector('#confirmRestore');
                const submitBtn = modal.querySelector('#restoreBtn');
                if (checkbox) checkbox.checked = false;
                if (submitBtn) submitBtn.disabled = true;

                // Also populate hidden machine_id
                const machineIdInput = document.getElementById('machine_id');
                if (machineIdInput) {
                    machineIdInput.value = machineId;
                }
                
                modal.style.display = 'block';
            }
        }
    });
});

// Universal modal open functions for dashboard modals
window.openResetModal = function(button) {
    const applicatorId = button.getAttribute("data-id");
    const machineId = button.getAttribute("data-id");
    
    // Check for applicator reset modal
    const applicatorModal = document.getElementById('resetModalDashboardApplicator');
    if (applicatorModal && applicatorId) {
        document.getElementById("reset_applicator_id").value = applicatorId;
        applicatorModal.style.display = "block";
    }
    
    // Check for machine reset modal
    const machineModal = document.getElementById('resetModalDashboardMachine');
    if (machineModal && machineId) {
        document.getElementById("reset_machine_id").value = machineId;
        machineModal.style.display = "block";
    }
};

window.openUndoModal = function(button) {
    const applicatorId = button.getAttribute("data-id");
    const machineId = button.getAttribute("data-id");
    
    // Check for applicator undo modal
    const applicatorModal = document.getElementById('undoModalDashboardApplicator');
    if (applicatorModal && applicatorId) {
        document.getElementById("undo_applicator_id").value = applicatorId;
        applicatorModal.style.display = "block";
    }
    
    // Check for machine undo modal
    const machineModal = document.getElementById('undoModalDashboardMachine');
    if (machineModal && machineId) {
        document.getElementById("undo_machine_id").value = machineId;
        machineModal.style.display = "block";
    }
};

// Handle add parts modal open
window.openAddPartsModal = function() {
    console.log('openAddPartsModal function called from utils');
    const modal = document.getElementById('addCustomPartModalDashboardMachine');
    if (modal) {
        modal.style.display = 'block';
        console.log('Modal opened successfully from utils');
    } else {
        console.error('Modal element addCustomPartModalDashboardMachine not found');
    }
};