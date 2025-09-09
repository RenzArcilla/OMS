document.addEventListener('DOMContentLoaded', function() {
    // Universal modal opener - works with any button that has data-modal attribute
    document.addEventListener('click', function(event) {
        // Helper function to find the closest element with a specific class
        function findClosestWithClass(element, className) {
            let current = element;
            while (current && current !== document) {
                if (current.classList && current.classList.contains(className)) {
                    return current;
                }
                current = current.parentElement;
            }
            return null;
        }

        // Get the actual button element (either the target or its closest parent with button classes)
        const button = findClosestWithClass(event.target, 'add-parts') || 
                      findClosestWithClass(event.target, 'export-output-data') ||
                      findClosestWithClass(event.target, 'export-reset-data') ||
                      findClosestWithClass(event.target, 'edit-maximum-output') ||
                      findClosestWithClass(event.target, 'add-parts-machine') ||
                      findClosestWithClass(event.target, 'export-output-data-machine') ||
                      findClosestWithClass(event.target, 'export-reset-data-machine') ||
                      findClosestWithClass(event.target, 'edit-maximum-output-machine') ||
                      findClosestWithClass(event.target, 'restore-machine-btn') ||
                      findClosestWithClass(event.target, 'export-btn') ||
                      findClosestWithClass(event.target, 'add-record-btn');

        if (!button) return;

// Dashboard Applicator
        if (button.classList.contains('add-parts')) {
            const modal = document.getElementById('addCustomPartModalDashboardApplicator');
            if (modal) {
                modal.style.display = 'block';
            }
        }
        if (button.classList.contains('export-output-data')) {
            const modal = document.getElementById('exportModal');
            if (modal) {
                modal.style.display = 'block';
            }
        }
        if (button.classList.contains('export-reset-data')) {
            const modal = document.getElementById('exportModalRecentlyReset');
            if (modal) {
                modal.style.display = 'block';
            }
        }
        if (button.classList.contains('edit-maximum-output')) {
            const modal = document.getElementById('editMaxOutputModal');
            if (modal) {
                modal.style.display = 'block';
            }
        }

// Dashboard Machine 
        if (button.classList.contains('add-parts-machine')) {
            const modal = document.getElementById('addCustomPartModalDashboardMachine');
            if (modal) {
                modal.style.display = 'block';
            }
        }
        if (button.classList.contains('export-output-data-machine')) {
            const modal = document.getElementById('exportMachineModal');
            if (modal) {
                modal.style.display = 'block';
            }
        }
        if (button.classList.contains('export-reset-data-machine')) {
            const modal = document.getElementById('exportModalRecentlyReset');
            if (modal) {
                modal.style.display = 'block';
            }
        }
        if (button.classList.contains('edit-maximum-output-machine')) {
            const modal = document.getElementById('editMaxOutputModal');
            if (modal) {
                modal.style.display = 'block';
            }
        }
        if (button.classList.contains('restore-machine-btn')) {
            const modal = document.getElementById('disabled-machines-section');
            if (modal) {
                modal.style.display = 'block';
            }
        }
        
        // Handle restore machine button
        if (button.classList.contains('restore-machine-btn')) {
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

        

// Record Output
        if (button.classList.contains('export-btn')) {
            const modal = document.getElementById('exportModal');
            if (modal) {
                modal.style.display = 'block';
            }
        }
        if (button.classList.contains('add-record-btn')) {
            const modal = document.getElementById('addRecordModal');
            if (modal) {
                modal.style.display = 'block';
            }
        }
    });
});
