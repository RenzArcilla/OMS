// Listen for clicks only on the restore buttons
document.addEventListener('click', function(event) {
    const button = event.target.closest('.restore-output-btn');
    if (button) {
        const recordId = button.dataset.recordId;
        confirmRestoreOutputRecord(recordId);
    }
});

// Restore confirmation
function confirmRestoreOutputRecord(recordId) {
    const modal = document.getElementById('restoreOutputModal');
    modal.style.display = 'block';
    
    document.getElementById('restore_record_id').value = recordId;
    document.getElementById('restore_record_id_display').textContent = '#' + recordId;
    
    // Reset the confirmation checkbox and disable restore button
    document.getElementById('confirmRestore').checked = false;
    document.getElementById('restoreBtn').disabled = true;
}


