function openRecordEditModal(button) {
    console.log('Raw dataset:', button.dataset);
    
    // CamelCase property names for data attributes
    document.getElementById('edit_record_id').value = button.dataset.id;
    document.getElementById('edit_prev_app1').value = button.dataset.hp1No;  
    document.getElementById('edit_prev_app1_output').value = button.dataset.app2Output; 
    document.getElementById('edit_prev_app2').value = button.dataset.hp2No;  
    document.getElementById('edit_prev_app2_output').value = button.dataset.app2Output; 
    document.getElementById('edit_date_inspected').value = button.dataset.dateInspected;  
    document.getElementById('edit_shift').value = button.dataset.shift;
    document.getElementById('edit_app1').value = button.dataset.hp1No;
    document.getElementById('edit_app1_output').value = button.dataset.app1Output;  
    document.getElementById('edit_app2').value = button.dataset.hp2No;
    document.getElementById('edit_app2_output').value = button.dataset.app2Output; 
    document.getElementById('edit_machine').value = button.dataset.controlNo; 
    document.getElementById('edit_machine_output').value = button.dataset.machineOutput;  
    
    // Debug: Log what its actually setting
    console.log('Setting prev_app1 to:', button.dataset.hp1No);
    console.log('Setting prev_app2 to:', button.dataset.hp2No);
    console.log('Final prev_app1 value:', document.getElementById('edit_prev_app1').value);
    console.log('Final prev_app2 value:', document.getElementById('edit_prev_app2').value);
    
    document.getElementById('editRecordModal').style.display = 'block';
}

function closeRecordModal() {
    document.getElementById('editRecordModal').style.display = 'none';
}