// Modal Logic for Editing Machines
function openEditModal(button) {
    // Get values from button
    document.getElementById('edit_machine_id').value = button.dataset.id;
    document.getElementById('edit_control_no').value = button.dataset.control;
    document.getElementById('edit_description').value = button.dataset.description;
    document.getElementById('edit_model').value = button.dataset.model;
    document.getElementById('edit_maker').value = button.dataset.maker;
    document.getElementById('edit_serial_no').value = button.dataset.serial;
    document.getElementById('edit_invoice_no').value = button.dataset.invoice;

    // Show modal
    document.getElementById('editModal').style.display = 'block';
}

function closeMachineModal() {
    document.getElementById('editModal').style.display = 'none';
}