document.addEventListener('DOMContentLoaded', function() {
    // Universal modal closer - works with any button that has modal-close-btn or cancel-btn class
    document.addEventListener('click', function(event) {
        // Check if the clicked element has the class we want
        if (
            event.target.classList.contains('modal-close-btn') ||
            event.target.classList.contains('cancel-btn')
        ) {
            // Find the closest modal overlay and close it
            const modal = event.target.closest('.modal-overlay');
            if (modal) {
                modal.style.display = 'none';
            }
            
            // Also close any modals that might be open (fallback)
            const allModals = document.querySelectorAll('.modal-overlay');
            allModals.forEach(modal => modal.style.display = 'none');
        }
    });
    
    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            event.target.style.display = 'none';
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const openModals = document.querySelectorAll('.modal-overlay[style*="block"]');
            openModals.forEach(modal => modal.style.display = 'none');
        }
    });

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('cancel-btn')) {
            const modal = event.target.closest('.modal-overlay');
            if (modal) {
                modal.style.display = 'none';
            }
        }
    });
});

// Universal modal close functions for dashboard modals
window.closeResetModal = function() {
    const applicatorModal = document.getElementById('resetModalDashboardApplicator');
    const machineModal = document.getElementById('resetModalDashboardMachine');
    
    if (applicatorModal) {
        applicatorModal.style.display = 'none';
        const form = applicatorModal.querySelector('form');
        if (form) form.reset();
    }
    
    if (machineModal) {
        machineModal.style.display = 'none';
        const form = machineModal.querySelector('form');
        if (form) form.reset();
    }
};

window.closeUndoModal = function() {
    const applicatorModal = document.getElementById('undoModalDashboardApplicator');
    const machineModal = document.getElementById('undoModalDashboardMachine');
    
    if (applicatorModal) {
        applicatorModal.style.display = 'none';
        const form = applicatorModal.querySelector('form');
        if (form) form.reset();
        const dropdown = applicatorModal.querySelector('#editStatus');
        if (dropdown) dropdown.innerHTML = '<option value="">Select a part first</option>';
    }
    
    if (machineModal) {
        machineModal.style.display = 'none';
        const form = machineModal.querySelector('form');
        if (form) form.reset();
        const dropdown = machineModal.querySelector('#editStatus');
        if (dropdown) dropdown.innerHTML = '<option value="">Select a part first</option>';
    }
};

window.closeRestoreApplicatorModal = function() {
    const modal = document.getElementById('restoreApplicatorModalDashboardApplicator');
    if (modal) {
        modal.style.display = 'none';
        const form = modal.querySelector('form');
        if (form) form.reset();
        const checkbox = modal.querySelector('#confirmRestore');
        const submitBtn = modal.querySelector('#restoreBtn');
        if (checkbox) checkbox.checked = false;
        if (submitBtn) submitBtn.disabled = true;
    }
};

// Also handle machine restore modal (same modal ID, different context)
window.closeRestoreMachineModal = function() {
    const modal = document.getElementById('restoreApplicatorModalDashboardApplicator');
    if (modal) {
        modal.style.display = 'none';
        const form = modal.querySelector('form');
        if (form) form.reset();
        const checkbox = modal.querySelector('#confirmRestore');
        const submitBtn = modal.querySelector('#restoreBtn');
        if (checkbox) checkbox.checked = false;
        if (submitBtn) submitBtn.disabled = true;
    }
};

// Handle add custom part modal close
window.closeAddCustomPartModal = function() {
    const modal = document.getElementById('addCustomPartModalDashboardMachine');
    if (modal) {
        modal.style.display = 'none';
        const form = modal.querySelector('form');
        if (form) form.reset();
    }
};

