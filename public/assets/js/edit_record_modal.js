/**
 * Edit Record Modal functionality
 * Handles opening, closing, and validation of the edit record modal
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

/**
 * Initialize all event listeners for modal functionality
 */
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

/**
 * Check if modal is currently open
 * @returns {boolean}
 */
function isModalOpen() {
    const modal = document.getElementById('editRecordModal');
    return modal && modal.style.display === 'block';
}

/**
 * Main function to open edit modal with error handling and validation
 * @param {HTMLElement} button - The edit button element with data attributes
 */
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

/**
 * Validate that required data attributes are present
 * @param {DOMStringMap} data - Dataset from button element
 */
function validateRequiredData(data) {
    const requiredDataFields = ['id', 'dateInspected', 'shift', 'hp1No', 'app1Output', 'controlNo', 'machineOutput'];
    const missingFields = requiredDataFields.filter(field => !data[field]);
    
    if (missingFields.length > 0) {
        throw new Error(`Missing required record data: ${missingFields.join(', ')}`);
    }
}

/**
 * Populate all form fields with data from the record
 * @param {DOMStringMap} data - Dataset from button element
 */
function populateFormFields(data) {
    const fieldMappings = {
        // Hidden fields for tracking previous values
        'edit_record_id': data.id,
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

/**
 * Show the edit modal
 */
function showModal() {
    const modal = document.getElementById('editRecordModal');
    if (modal) {
        modal.style.display = 'block';
    } else {
        throw new Error('Edit modal not found in DOM');
    }
}

/**
 * Close the edit modal and reset form
 */
function closeRecordModal() {
    const modal = document.getElementById('editRecordModal');
    if (modal) {
        modal.style.display = 'none';
        resetForm();
    }
}

/**
 * Reset the edit form
 */
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
 * Validate form before submission
 * @returns {boolean} - True if form is valid, false otherwise
 */
function validateEditForm() {
    try {
        // Check required fields
        validateRequiredFields();
        
        // Validate app2 conditional logic
        validateApp2Logic();
        
        // Check for duplicate applicators
        validateNoDuplicateApplicators();
        
        return true;
        
    } catch (error) {
        alert(error.message);
        return false;
    }
}

/**
 * Validate that all required fields are filled
 */
function validateRequiredFields() {
    const emptyFields = REQUIRED_FIELDS.filter(fieldId => {
        const element = document.getElementById(fieldId);
        return !element || !element.value.trim();
    });
    
    if (emptyFields.length > 0) {
        throw new Error(`Please fill in all required fields: ${emptyFields.join(', ')}`);
    }
}

/**
 * Validate App2 conditional logic - if app2 is provided, output must be provided
 */
function validateApp2Logic() {
    const app2 = document.getElementById('edit_app2').value.trim();
    const app2Output = document.getElementById('edit_app2_output').value.trim();
    
    if (app2 && !app2Output) {
        throw new Error('Please provide output value for Applicator 2');
    }
}

/**
 * Validate that applicators are not duplicated
 */
function validateNoDuplicateApplicators() {
    const app1 = document.getElementById('edit_app1').value.trim();
    const app2 = document.getElementById('edit_app2').value.trim();
    
    if (app1 && app2 && app1 === app2) {
        throw new Error(`Error! Duplicate applicator entry: ${app1}`);
    }
}

// Legacy function for backward compatibility (if still referenced somewhere)
function openRecordEditModal(button) {
    console.warn('openRecordEditModal is deprecated. Use openRecordEditModalSafe instead.');
    openRecordEditModalSafe(button);
}