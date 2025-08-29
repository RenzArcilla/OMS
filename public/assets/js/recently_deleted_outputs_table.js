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
        // Create a form dynamically
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "../controllers/restore_record.php";

        // Add hidden input for record_id
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "record_id";
        input.value = recordId;

        form.appendChild(input);
        document.body.appendChild(form);

        form.submit();
    }
}


// Filter the table based on search input
async function filterTable(searchValue) {
    const response = await fetch(`../controllers/search_disabled_records.php?q=${encodeURIComponent(searchValue)}`);
    const data = await response.json();

    const tbody = document.getElementById("deletedRecordsMetricsBody");
    tbody.innerHTML = "";

    data.forEach(row => {
        tbody.innerHTML += `
            <tr>
                <td><button class="tab-btn" data-record-id="${row.record_id}">Restore</button></td>
                <td>${row.record_id}</td>
                <td>${row.date_inspected}</td>
                <td>${row.date_encoded.split(" ")[0]}</td>
                <td>${row.last_updated.split(" ")[0]}</td>
                <td>${row.shift}</td>
                <td>${row.hp1_no}</td>
                <td>${row.app1_output}</td>
                <td>${row.hp2_no}</td>
                <td>${row.app2_output}</td>
                <td>${row.control_no}</td>
                <td>${row.machine_output}</td>
            </tr>
        `;
    });
}
