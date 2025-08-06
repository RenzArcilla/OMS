// Infinite Scroll Logic for Records Table

// State variables
let recordOffset = 20;            // Tracks how many rows already loaded
const recordLimit = 20  ;           // How many rows to fetch per scroll
let recordLoading = false;        // Prevents overlapping AJAX calls


/*
Fetches and appends record rows from the server.
*/
function loadRecords() {
    if (recordLoading) return;
    recordLoading = true;

    fetch('/SOMS/public/ajax/get_records.php?offset=' + recordOffset + '&limit=' + recordLimit)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('recordsTableBody');

        // Create and append each record row
        data.forEach(row => {
            const tr = document.createElement('tr');

            const tdRecordId = document.createElement('td');
            tdRecordId.textContent = row.record_id;
            tr.appendChild(tdRecordId);

            const tdDateInspected = document.createElement('td');
            tdDateInspected.textContent = row.date_inspected;
            tr.appendChild(tdDateInspected);

            const tdDateEncoded = document.createElement('td');
            tdDateEncoded.textContent = row.date_encoded;
            tr.appendChild(tdDateEncoded);

            const tdShift = document.createElement('td');
            tdShift.textContent = row.shift;
            tr.appendChild(tdShift);

            const tdSerial = document.createElement('td');
            tdSerial.textContent = row.hp1_no;
            tr.appendChild(tdSerial);

            const tdInvoice = document.createElement('td');
            tdInvoice.textContent = row.app1_output;
            tr.appendChild(tdInvoice);

            const tdHp2No = document.createElement('td');
            tdHp2No.textContent = row.hp2_no;
            tr.appendChild(tdHp2No);

            const tdApp2Output = document.createElement('td');
            tdApp2Output.textContent = row.app2_output;
            tr.appendChild(tdApp2Output);

            const tdControlNo = document.createElement('td');
            tdControlNo.textContent = row.control_no;
            tr.appendChild(tdControlNo);

            const tdMachineOutput = document.createElement('td');
            tdMachineOutput.textContent = row.machine_output;
            tr.appendChild(tdMachineOutput);

            // Actions TD

            // Edit link

            // Delete form
            tbody.appendChild(tr); 
        });

            // Update offset
            recordOffset += data.length;
            recordLoading = false;

            // If fewer than limit were returned, we've reached the end
            if (data.length < recordLimit) {
                document.getElementById('record-container').removeEventListener('scroll', recordScrollHandler);
            }
        })
        .catch(error => {
            console.error("Error loading records:", error);
            recordLoading = false;
        });
}

/*
Handles scroll event for the record container.
Loads more data when near the bottom.
*/
function recordScrollHandler() {
    const container = document.getElementById('record-container');
    if (container.scrollTop + container.clientHeight >= container.scrollHeight - 5) {
        loadRecords();
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('record-container').addEventListener('scroll', recordScrollHandler);
});
