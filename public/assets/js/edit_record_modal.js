function openRecordEditModal(button) {
    // Get all data attributes from the button
    const data = button.dataset;
    
    // Debug: Log all data attributes to console
    console.log('Button data attributes:', data);
    
    // Set hidden fields for tracking previous values
    document.getElementById('edit_record_id').value = data.id || '';
    document.getElementById('edit_prev_app1').value = data.hp1No || '';
    document.getElementById('edit_prev_app1_output').value = data.app1Output || '';
    document.getElementById('edit_prev_app2').value = data.hp2No || '';
    document.getElementById('edit_prev_app2_output').value = data.app2Output || '';
    document.getElementById('edit_prev_machine').value = data.controlNo || '';
    document.getElementById('edit_prev_machine_output').value = data.machineOutput || '';
    
    // Set form fields with current values
    document.getElementById('edit_date_inspected').value = data.dateInspected || '';
    document.getElementById('edit_shift').value = data.shift || '';
    document.getElementById('edit_app1').value = data.hp1No || '';
    document.getElementById('edit_app1_output').value = data.app1Output || '';
    document.getElementById('edit_app2').value = data.hp2No || '';
    document.getElementById('edit_app2_output').value = data.app2Output || '';
    document.getElementById('edit_machine').value = data.controlNo || '';
    document.getElementById('edit_machine_output').value = data.machineOutput || '';
    
    // Show the modal
    document.getElementById('editRecordModal').style.display = 'block';
}

function closeRecordModal() {
    document.getElementById('editRecordModal').style.display = 'none';
}

// Alternative function with better error handling and validation
function openRecordEditModalSafe(button) {
    try {
        // Validate button element
        if (!button || !button.dataset) {
            console.error('Invalid button element or missing dataset');
            alert('Error: Invalid record data');
            return;
        }
        
        const data = button.dataset;
        
        // Required fields validation
        const requiredFields = ['id', 'dateInspected', 'shift', 'hp1No', 'app1Output', 'controlNo', 'machineOutput'];
        const missingFields = requiredFields.filter(field => !data[field]);
        
        if (missingFields.length > 0) {
            console.error('Missing required data attributes:', missingFields);
            alert('Error: Missing required record data - ' + missingFields.join(', '));
            return;
        }
        
        // Set all form fields
        const fieldMappings = {
            'edit_record_id': data.id,
            'edit_prev_app1': data.hp1No,
            'edit_prev_app1_output': data.app1Output,
            'edit_prev_app2': data.hp2No || '',
            'edit_prev_app2_output': data.app2Output || '',
            'edit_prev_machine': data.controlNo,
            'edit_prev_machine_output': data.machineOutput,
            'edit_date_inspected': data.dateInspected,
            'edit_shift': data.shift,
            'edit_app1': data.hp1No,
            'edit_app1_output': data.app1Output,
            'edit_app2': data.hp2No || '',
            'edit_app2_output': data.app2Output || '',
            'edit_machine': data.controlNo,
            'edit_machine_output': data.machineOutput
        };
        
        // Apply values to form fields
        Object.entries(fieldMappings).forEach(([fieldId, value]) => {
            const element = document.getElementById(fieldId);
            if (element) {
                element.value = value;
            } else {
                console.warn(`Form field not found: ${fieldId}`);
            }
        });
        
        // Show modal
        const modal = document.getElementById('editRecordModal');
        if (modal) {
            modal.style.display = 'block';
        } else {
            console.error('Edit modal not found');
            alert('Error: Edit modal not available');
        }
        
    } catch (error) {
        console.error('Error opening edit modal:', error);
        alert('Error: Unable to open edit form');
    }
}

// Function to validate form before submission
function validateEditForm() {
    const requiredFields = [
        'edit_record_id',
        'edit_date_inspected', 
        'edit_shift',
        'edit_app1',
        'edit_app1_output',
        'edit_machine',
        'edit_machine_output'
    ];
    
    const emptyFields = requiredFields.filter(fieldId => {
        const element = document.getElementById(fieldId);
        return !element || !element.value.trim();
    });
    
    if (emptyFields.length > 0) {
        alert('Please fill in all required fields: ' + emptyFields.join(', '));
        return false;
    }
    
    // Validate app2_output if app2 is provided
    const app2 = document.getElementById('edit_app2').value.trim();
    const app2Output = document.getElementById('edit_app2_output').value.trim();
    
    if (app2 && !app2Output) {
        alert('Please provide output value for Applicator 2');
        return false;
    }
    
    // Check for duplicate applicators
    const app1 = document.getElementById('edit_app1').value.trim();
    if (app1 && app2 && app1 === app2) {
        alert('Error! Duplicate applicator entry: ' + app1);
        return false;
    }
    
    return true;
}

// Enhanced close function
function closeRecordModal() {
    const modal = document.getElementById('editRecordModal');
    if (modal) {
        modal.style.display = 'none';
        
        // Optional: Reset form
        const form = document.getElementById('editRecordForm');
        if (form) {
            form.reset();
        }
    }
}