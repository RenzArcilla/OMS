// Infinite Scroll Logic for Applicator Table

// State variables
let applicatorOffset = 0;              // Tracks how many rows we've already loaded
const applicatorLimit = 10;           // How many rows to fetch per scroll
let applicatorLoading = false;        // Prevents overlapping AJAX calls


/*
Fetches and appends applicator rows from the server.
*/
function loadApplicators() {
    if (applicatorLoading) return;
    applicatorLoading = true;

    fetch(`../ajax/get_applicators.php?offset=${applicatorOffset}&limit=${applicatorLimit}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('applicator-body');

            // Create and append each applicator row
            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.hp_no}</td>
                    <td>${row.terminal_no}</td>
                    <td>${row.description}</td>
                    <td>${row.wire}</td>
                    <td>${row.terminal_maker}</td>
                    <td>${row.applicator_maker}</td>
                    <td>${row.serial_no}</td>
                    <td>${row.invoice_no}</td>
                `;
                tbody.appendChild(tr);
            });

            // Update offset
            applicatorOffset += data.length;
            applicatorLoading = false;

            // If fewer than limit were returned, we've reached the end
            if (data.length < applicatorLimit) {
                document.getElementById('applicator-container').removeEventListener('scroll', applicatorScrollHandler);
            }
        })
        .catch(error => {
            console.error("Error loading applicators:", error);
            applicatorLoading = false;
        });
}


 /*
Handles scroll event for the applicator container.
Loads more data when near the bottom.
*/
function applicatorScrollHandler() {
    const container = document.getElementById('applicator-container');
    if (container.scrollTop + container.clientHeight >= container.scrollHeight - 5) {
        loadApplicators();
    }
}


// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('applicator-container').addEventListener('scroll', applicatorScrollHandler);
    loadApplicators(); // Load initial data
});
