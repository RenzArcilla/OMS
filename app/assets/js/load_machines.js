// Initial offset and limit for pagination
let offset = 0;
const limit = 10;

// Flag to prevent multiple simultaneous fetches
let loading = false;


function loadMachines() {
    /*
    Loads a batch of machine rows from the server via AJAX
    and appends them to the machine table body.
    */

    // Prevent multiple fetches if one is already in progress
    if (loading) return;    
    loading = true;

    // Fetch machine data using offset and limit
    fetch(`../ajax/get_machines.php?offset=${offset}&limit=${limit}`)
        .then(response => response.json()) // Parse response as JSON
        .then(data => {
            const tbody = document.getElementById('machine-body');

            // Loop through returned machine rows and create <tr> elements
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
                tbody.appendChild(tr); // Append new row to table
            });

            // Increase offset for the next fetch
            offset += data.length;
            loading = false;

            // If fewer results returned than limit, stop further loading
            if (data.length < limit) {
                document.getElementById('machine-container').removeEventListener('scroll', scrollHandler);
            }
        })
        .catch(error => {
            // Handle any errors and allow retrying
            console.error("Error loading machines:", error);
            loading = false;
        });
}

/**
 * Handles scroll event:
 * Loads more machines if the user scrolls near the bottom of the container.
 */
function scrollHandler() {
    const container = document.getElementById('machine-container');

    // Check if user has scrolled near the bottom (with 5px threshold)
    if (container.scrollTop + container.clientHeight >= container.scrollHeight - 5) {
        loadMachines();
    }
}

// Set up event listeners when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('machine-container').addEventListener('scroll', scrollHandler); // Scroll trigger
    loadMachines(); // Load initial batch of machines
});
