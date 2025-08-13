function openRecordEditModal(button) {

    // Data attributes for edit modal
    document.getElementById('edit_record_id').value = button.dataset.id;
    document.getElementById('edit_prev_app1').value = button.dataset.hp1No;  
    document.getElementById('edit_prev_app1_output').value = button.dataset.app2Output; 
    document.getElementById('edit_prev_app2').value = button.dataset.hp2No;  
    document.getElementById('edit_prev_app2_output').value = button.dataset.app2Output; 
    document.getElementById('edit_prev_machine').value = button.dataset.controlNo; 
    document.getElementById('edit_prev_machine_output').value = button.dataset.machineOutput; 
    document.getElementById('edit_date_inspected').value = button.dataset.dateInspected;  
    document.getElementById('edit_shift').value = button.dataset.shift;
    document.getElementById('edit_app1').value = button.dataset.hp1No;
    document.getElementById('edit_app1_output').value = button.dataset.app1Output;  
    document.getElementById('edit_app2').value = button.dataset.hp2No;
    document.getElementById('edit_app2_output').value = button.dataset.app2Output; 
    document.getElementById('edit_machine').value = button.dataset.controlNo; 
    document.getElementById('edit_machine_output').value = button.dataset.machineOutput;  
    
    document.getElementById('editRecordModal').style.display = 'block';
}

function closeRecordModal() {
    document.getElementById('editRecordModal').style.display = 'none';
}