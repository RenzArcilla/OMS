/**
 * Edit Record Modal functionality
 * Handles opening, closing, and data pre-population of the edit record modal
 */

// Configuration
const SHIFT_MAPPING = {
    'FIRST': '1st',
    'SECOND': '2nd',
    'NIGHT': 'NIGHT'
};

const REQUIRED_FIELDS = [
    'edit_record_id',
    'edit_date_inspected', 
    'edit_shift',
    'edit_app1',
    'edit_app1_output',
    'edit_machine',
    'edit_machine_output'
];

// Initialize modal functionality when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeModalEventListeners();
});

// Initialize all event listeners for modal functionality
function initializeModalEventListeners() {
    // App2 conditional validation - make output required when app2 is filled
    const app2Input = document.getElementById('edit_app2');
    const app2OutputInput = document.getElementById('edit_app2_output');
    
    if (app2Input && app2OutputInput) {
        app2Input.addEventListener('input', function() {
            if (this.value.trim()) {
                app2OutputInput.setAttribute('required', 'required');
            } else {
                app2OutputInput.removeAttribute('required');
                app2OutputInput.value = '';
            }
        });
    }
    
    // Close modal when clicking outside
    const modal = document.getElementById('editRecordModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeRecordModal();
            }
        });
    }
    
    // Handle Escape key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isModalOpen()) {
            closeRecordModal();
        }
    });
}

// Check if modal is currently open
function isModalOpen() {
    const modal = document.getElementById('editRecordModal');
    return modal && modal.style.display === 'block';
}

// Main function to open edit modal with error handling and validation
function openRecordEditModalSafe(button) {
    try {
        // Validate button element
        if (!button?.dataset) {
            throw new Error('Invalid button element or missing dataset');
        }
        
        const data = button.dataset;
        
        // Validate required data
        validateRequiredData(data);
        
        // Populate form fields
        populateFormFields(data);
        
        // Show modal
        showModal();
        
    } catch (error) {
        console.error('Error opening edit modal:', error);
        alert('Error: ' + error.message);
    }
}

// Validate that required data attributes are present
function validateRequiredData(data) {
    const requiredDataFields = ['id', 'dateInspected', 'shift', 'hp1No', 'app1Output', 'controlNo', 'machineOutput'];
    const missingFields = requiredDataFields.filter(field => !data[field]);
    
    if (missingFields.length > 0) {
        throw new Error(`Missing required record data: ${missingFields.join(', ')}`);
    }
}

// Populate all form fields with data from the record
function populateFormFields(data) {
    const fieldMappings = {
        // Hidden fields for tracking previous values
        'edit_record_id': data.id,
        'edit_prev_date_inspected': data.dateInspected,
        'edit_prev_shift': SHIFT_MAPPING[data.shift] || data.shift || '',
        'edit_prev_app1': data.hp1No,
        'edit_prev_app1_output': data.app1Output,
        'edit_prev_app2': data.hp2No || '',
        'edit_prev_app2_output': data.app2Output || '',
        'edit_prev_machine': data.controlNo,
        'edit_prev_machine_output': data.machineOutput,
        
        // Form fields
        'edit_date_inspected': data.dateInspected,
        'edit_shift': SHIFT_MAPPING[data.shift] || data.shift || '',
        'edit_app1': data.hp1No,
        'edit_app1_output': data.app1Output,
        'edit_app2': data.hp2No || '',
        'edit_app2_output': data.app2Output || '',
        'edit_machine': data.controlNo,
        'edit_machine_output': data.machineOutput
    };
    
    // Set values for all fields
    Object.entries(fieldMappings).forEach(([fieldId, value]) => {
        const element = document.getElementById(fieldId);
        if (element) {
            element.value = value;
        } else {
            console.warn(`Form field not found: ${fieldId}`);
        }
    });
}

// Show the edit modal
function showModal() {
    const modal = document.getElementById('editRecordModal');
    if (modal) {
        modal.style.display = 'block';
    } else {
        throw new Error('Edit modal not found in DOM');
    }
}

// Close the edit modal and reset form
function closeRecordModal() {
    const modal = document.getElementById('editRecordModal');
    if (modal) {
        modal.style.display = 'none';
        resetForm();
    }
}

// Reset the edit form
function resetForm() {
    const form = document.getElementById('editRecordForm');
    if (form) {
        form.reset();
        
        // Remove conditional required attribute from app2_output
        const app2OutputInput = document.getElementById('edit_app2_output');
        if (app2OutputInput) {
            app2OutputInput.removeAttribute('required');
        }
    }
}

/**
 * Basic form validation before submission
 * Only checks HTML5 required fields - all business logic is server-side
 * @returns {boolean} - True if basic HTML5 validation passes
 */
function validateEditForm() {
    const form = document.getElementById('editRecordForm');
    if (!form) {
        alert('Form not found');
        return false;
    }
    
    // Let HTML5 validation handle required fields
    if (!form.checkValidity()) {
        // HTML5 will show validation messages
        return false;
    }
    
    // All validations (including no-changes check) are handled server-side
    return true;
}

// Legacy function for backward compatibility
function openRecordEditModal(button) {
    console.warn('openRecordEditModal is deprecated. Use openRecordEditModalSafe instead.');
    openRecordEditModalSafe(button);
}