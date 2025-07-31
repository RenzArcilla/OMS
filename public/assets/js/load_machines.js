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

            const tdControlNo = document.createElement('td');
            tdControlNo.textContent = row.control_no;
            tr.appendChild(tdControlNo);

            const tdDesc = document.createElement('td');
            tdDesc.textContent = row.description;
            tr.appendChild(tdDesc);

            const tdModel = document.createElement('td');
            tdModel.textContent = row.model;
            tr.appendChild(tdModel);

            const tdMaker = document.createElement('td');
            tdMaker.textContent = row.maker;
            tr.appendChild(tdMaker);

            const tdSerial = document.createElement('td');
            tdSerial.textContent = row.serial_no || '';
            tr.appendChild(tdSerial);

            const tdInvoice = document.createElement('td');
            tdInvoice.textContent = row.invoice_no || '';
            tr.appendChild(tdInvoice);

            // Actions TD
            const tdActions = document.createElement('td');

            // Edit link
                const editButton = document.createElement('button');
                editButton.textContent = '✏️';
                editButton.setAttribute('type', 'button');
                editButton.setAttribute('class', 'edit-machine-button');

                // Set data attributes
                editButton.dataset.id = row.machine_id;
                editButton.dataset.control = row.control_no;
                editButton.dataset.description = row.description;
                editButton.dataset.model = row.model;
                editButton.dataset.maker = row.maker;
                editButton.dataset.serial = row.serial_no;
                editButton.dataset.invoice = row.invoice_no;

                // Set onclick handler to open machine modal
                editButton.addEventListener('click', function () {
                    openEditModal(editButton);
                });

                tdActions.appendChild(editButton);
            
            // Delete form
                const deleteForm = document.createElement('form');
                deleteForm.method = 'POST';
                deleteForm.action = '/SOMS/app/controllers/delete_machine.php';
                deleteForm.name = 'deleteForm'; 
                deleteForm.style.display = 'inline';
                deleteForm.onsubmit = () => confirm('Are you sure you want to delete this machine?');

                const hiddenId = document.createElement('input');
                hiddenId.type = 'hidden';
                hiddenId.name = 'machine_id';
                hiddenId.value = row.machine_id;
                deleteForm.appendChild(hiddenId);

                const deleteButton = document.createElement('button');
                deleteButton.type = 'submit';
                deleteButton.textContent = '🗑️';
                deleteForm.appendChild(deleteButton);

                tdActions.appendChild(deleteForm);
                tr.appendChild(tdActions);

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
});
