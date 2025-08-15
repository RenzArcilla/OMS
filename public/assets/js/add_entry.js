// Switch between machines and applicators tabs
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
        document.getElementById('machine-table').classList.add('active');
    } else if (tab === 'applicators') {
        document.getElementById('applicators-table').classList.add('active');
    }
}

// Open Add Machine Modal
function openMachineModal() {
    document.getElementById('addMachineModal').style.display = 'block';
}

// Open Add Applicator Modal
function openApplicatorModal() {
    document.getElementById('addApplicatorModal').style.display = 'block';
}

// Close Add Applicator Modal
function closeAddApplicatorModal() {
    document.getElementById('addApplicatorModal').style.display = 'none';
}

// Close Add Machine Modal
function closeAddMachineModal() {
    document.getElementById('addMachineModal').style.display = 'none';
}

// Close modal when clicking outside of it
document.addEventListener('click', function(event) {
    const modals = document.querySelectorAll('.modal-overlay');
    modals.forEach(modal => {
        if (event.target === modal) {
            closeAddApplicatorModal();
            closeAddMachineModal();
            closeApplicatorModal();
            closeMachineModal();
        }
    });
});

// Close modal when pressing Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAddApplicatorModal();
        closeAddMachineModal();
        closeApplicatorModal();
        closeMachineModal();
    }
});

/* If the add machine/applicator is not working, use this function
function saveMachine() {
    // Get form element
    const form = document.getElementById('addMachineForm');
    
    // Validate required fields
    const requiredFields = [
        'machine_ctrl_no',
        'description',
        'model',
        'machine_maker'
    ];
    
    let isValid = true;
    let firstInvalidField = null;
    
    // Check each required field
    requiredFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (!field.value.trim()) {
            field.style.borderColor = '#ff4444';
            if (!firstInvalidField) firstInvalidField = field;
            isValid = false;
        } else {
            field.style.borderColor = '';
        }
    });
    
    // If validation fails, focus on first invalid field and return
    if (!isValid) {
        firstInvalidField.focus();
        alert('Please fill in all required fields.');
        return;
    }
    
    // Validate description selection
    const description = document.getElementById('description').value;
    if (!['AUTOMATIC', 'SEMI-AUTOMATIC'].includes(description)) {
        alert('Please select a valid description (AUTOMATIC or SEMI-AUTOMATIC).');
        return;
    }
    
    // If all validations pass, you can submit the form or perform further actions
    // For now, just close the modal
    closeAddMachineModal();
}

function saveApplicator() {
    // Get form element
    const form = document.getElementById('applicatorForm');
    
    // Validate required fields
    const requiredFields = [
        'add_applicator_ctrl_no',
        'add_applicator_terminal_no', 
        'add_applicator_description',
        'add_applicator_wire_type',
        'add_terminal_maker',
        'add_applicator_maker'
    ];
    
    let isValid = true;
    let firstInvalidField = null;
    
    // Check each required field
    requiredFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (!field.value.trim()) {
            field.style.borderColor = '#ff4444';
            if (!firstInvalidField) firstInvalidField = field;
            isValid = false;
        } else {
            field.style.borderColor = '';
        }
    });
    
    // If validation fails, focus on first invalid field and return
    if (!isValid) {
        firstInvalidField.focus();
        alert('Please fill in all required fields.');
        return;
    }
    
    // Validate description selection
    const description = document.getElementById('add_applicator_description').value;
    if (!['SIDE', 'END'].includes(description)) {
        alert('Please select a valid description (SIDE or END).');
        return;
    }
    
    // Validate wire type selection
    const wireType = document.getElementById('add_applicator_wire_type').value;
    if (!['BIG', 'SMALL'].includes(wireType)) {
        alert('Please select a valid wire type (BIG or SMALL).');
        return;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('applicatorActionBtn');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Adding...';
    submitBtn.disabled = true;
    
    // Submit the form
    form.submit();
}

// Function to clear the applicator form
function clearApplicatorForm() {
    const form = document.getElementById('applicatorForm');
    form.reset();
    
    // Clear any custom styling
    const fields = form.querySelectorAll('input, select');
    fields.forEach(field => {
        field.style.borderColor = '';
    });
}

// Function to reset form when modal is opened
function openApplicatorModal() {
    document.getElementById('addApplicatorModal').style.display = 'block';
    clearApplicatorForm(); // Clear form when opening modal
}