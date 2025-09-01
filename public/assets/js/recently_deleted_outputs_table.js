// Listen for clicks only on the restore buttons
document.addEventListener('click', function(event) {
    const button = event.target.closest('.restore-btn');
    if (button) {
        const recordId = button.dataset.recordId;
        confirmRestoreCustomPart(recordId);
    }
});

// Restore confirmation
function confirmRestoreCustomPart(recordId) {
    if (confirm("Are you sure you want to restore this record?")) {
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "../controllers/restore_record.php";

        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "record_id";
        input.value = recordId;

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

