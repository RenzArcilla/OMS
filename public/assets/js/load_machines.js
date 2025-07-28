// Infinite Scroll Logic for Machine Table

// State variables
let machineOffset = 10;              // Tracks how many rows we've already loaded
const machineLimit = 10;           // How many rows to fetch per scroll
let machineLoading = false;        // Prevents overlapping AJAX calls


/*
Fetches and appends machine rows from the server.
*/
function loadMachines() {
    if (machineLoading) return;
    machineLoading = true;

    fetch('/SOMS/public/ajax/get_machines.php?offset=' + machineOffset + '&limit=' + machineLimit)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('machine-body');

            // Create and append each machine row
            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.machine_id}</td>
                    <td>${row.control_no}</td>
                    <td>${row.description}</td>
                    <td>${row.model}</td>
                    <td>${row.maker}</td>
                    <td>${row.serial_no}</td>
                    <td>${row.invoice_no}</td>
                `;
                tbody.appendChild(tr);
            });

            // Update offset
            machineOffset += data.length;
            machineLoading = false;

            // If fewer than limit were returned, we've reached the end
            if (data.length < machineLimit) {
                document.getElementById('machine-container').removeEventListener('scroll', machineScrollHandler);
            }
        })
        .catch(error => {
            console.error("Error loading machines:", error);
            machineLoading = false;
        });
}


/*
Handles scroll event for the machine container.
Loads more data when near the bottom.
*/
function machineScrollHandler() {
    const container = document.getElementById('machine-container');
    if (container.scrollTop + container.clientHeight >= container.scrollHeight - 5) {
        loadMachines();
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('machine-container').addEventListener('scroll', machineScrollHandler);
    loadMachines(); // Load initial data
});
