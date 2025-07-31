function openApplicatorEditModal(button) {
    document.getElementById('edit_applicator_id').value = button.dataset.id;
    document.getElementById('edit_applicator_control').value = button.dataset.control;
    document.getElementById('edit_terminal_no').value = button.dataset.terminal;
    document.getElementById('edit_applicator_description').value = button.dataset.description;
    document.getElementById('edit_wire_type').value = button.dataset.wire;
    document.getElementById('edit_terminal_maker').value = button.dataset.terminalMaker;
    document.getElementById('edit_applicator_maker').value = button.dataset.applicatorMaker;
    document.getElementById('edit_applicator_serial_no').value = button.dataset.serial;
    document.getElementById('edit_applicator_invoice_no').value = button.dataset.invoice;

    document.getElementById('editApplicatorModal').style.display = 'block';
}

function closeApplicatorModal() {
    document.getElementById('editApplicatorModal').style.display = 'none';
}