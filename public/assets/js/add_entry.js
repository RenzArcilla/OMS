// Tab switching functionality
function switchTab(tab) {
    currentTab = tab;
   
    // Update tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
   
    // Show/hide tables
    document.querySelectorAll('.entries-table-card').forEach(card => {
        card.classList.remove('active');
    });
   
    if (tab === 'machines') {
        document.getElementById('machines-table').classList.add('active');
    } else if (tab === 'applicators') {
        document.getElementById('applicators-table').classList.add('active');
    }
}

// Function to open machine modal
function openMachineModal() {
    const modal = document.getElementById('addMachineModal');
    if (modal) {
        modal.style.display = 'block';
        // Focus on first input field
        const firstInput = modal.querySelector('input[type="text"]');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }
    }
}

// Function to open applicator modal
function openApplicatorModal() {
    const modal = document.getElementById('addApplicatorModal');
    if (modal) {
        modal.style.display = 'block';
        // Focus on first input field
        const firstInput = modal.querySelector('input[type="text"]');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }
    }
}

// Enhanced close modal function with form reset
function closeModal() {
    const addMachineModal = document.getElementById('addMachineModal');
    const addApplicatorModal = document.getElementById('addApplicatorModal');

    if (addMachineModal) {
        addMachineModal.style.display = 'none';
    }

    if (addApplicatorModal) {
        addApplicatorModal.style.display = 'none';
    }
}
window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

window.addEventListener('click', function(e) {
    const machineModal = document.getElementById('addMachineModal');
    const applicatorModal = document.getElementById('addApplicatorModal');

    if (e.target === machineModal) {
        machineModal.style.display = 'none';
    }

    if (e.target === applicatorModal) {
        applicatorModal.style.display = 'none';
    }
});


// Function to reset form fields
function resetForm(form) {
    if (!form) return;
    
    // Reset all form fields
    form.reset();
    
    // Clear any custom validation states
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.classList.remove('error', 'success');
        input.style.borderColor = '';
    });
}

// Function to clear error messages
function clearErrorMessages(modal) {
    const errorMessages = modal.querySelectorAll('.error-message, .field-error');
    errorMessages.forEach(error => {
        error.remove();
    });
}

// Close modal when clicking outside of it
document.addEventListener('click', function(event) {
    const modals = document.querySelectorAll('.modal-overlay');
    modals.forEach(modal => {
        if (event.target === modal) {
            closeModal();
        }
    });
});

// Close modal when pressing Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});

// Placeholder functions for save operations
function saveMachine() {
    // Add your machine saving logic here
    console.log('Saving machine...');
    // For now, just close the modal
    closeModal();
}

function saveApplicator() {
    // Add your applicator saving logic here
    console.log('Saving applicator...');
    // For now, just close the modal
    closeModal();
}


// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, modals ready');
});