// Infinite Scroll Logic for Applicator Table

// State variables
let applicatorOffset = 10;              // Tracks how many rows we've already loaded
const applicatorLimit = 10;            // How many rows to fetch per scroll
let applicatorLoading = false;         // Prevents overlapping AJAX calls

/*
    Fetches and appends applicator rows from the server.
    Automatically loads more if the container is not scrollable.
*/
function loadApplicators() {
    if (applicatorLoading) return;
    applicatorLoading = true;

    fetch('/SOMS/public/ajax/get_applicators.php?offset=' + applicatorOffset + '&limit=' + applicatorLimit)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('applicator-body');

            data.forEach(row => {
                const tr = document.createElement('tr');

                const tdId = document.createElement('td');
                tdId.textContent = row.hp_no;
                tr.appendChild(tdId);

                const tdControlNo = document.createElement('td');
                tdControlNo.textContent = row.terminal_no;
                tr.appendChild(tdControlNo);

                const tdDesc = document.createElement('td');
                tdDesc.textContent = row.description;
                tr.appendChild(tdDesc);

                const tdModel = document.createElement('td');
                tdModel.textContent = row.wire;
                tr.appendChild(tdModel);

                const tdTerminalMaker = document.createElement('td');
                tdTerminalMaker.textContent = row.terminal_maker;
                tr.appendChild(tdTerminalMaker); 

                const tdApplicatorMaker = document.createElement('td');
                tdApplicatorMaker.textContent = row.applicator_maker || '';
                tr.appendChild(tdApplicatorMaker); 

                const tdSerial = document.createElement('td');
                tdSerial.textContent = row.serial_no || '';
                tr.appendChild(tdSerial); 

                const tdInvoice = document.createElement('td');
                tdInvoice.textContent = row.invoice_no || '';
                tr.appendChild(tdInvoice);

                // Actions TD
                const tdActions = document.createElement('td');

                // Create actions wrapper div (matching server structure)
                const actionsDiv = document.createElement('div');
                actionsDiv.className = 'actions';
                
                // Edit button
                const editButton = document.createElement('button');
                editButton.textContent = '✏️';
                editButton.setAttribute('type', 'button');
                editButton.setAttribute('class', 'action-btn edit-btn');

                // Set data attributes
                editButton.dataset.id = row.applicator_id;
                editButton.dataset.control = row.hp_no;
                editButton.dataset.terminal = row.terminal_no;
                editButton.dataset.description = row.description;
                editButton.dataset.wire = row.wire;
                editButton.dataset.terminalMaker = row.terminal_maker;
                editButton.dataset.applicatorMaker = row.applicator_maker;
                editButton.dataset.serial = row.serial_no;
                editButton.dataset.invoice = row.invoice_no;

                // Set onclick handler to open applicator modal
                editButton.onclick = () => openApplicatorEditModal(editButton);

                actionsDiv.appendChild(editButton);

                // Delete form
                const deleteForm = document.createElement('form');
                deleteForm.method = 'POST';
                deleteForm.action = '/SOMS/app/controllers/delete_applicator.php';
                deleteForm.style.display = 'inline';
                deleteForm.onsubmit = () => confirm('Are you sure you want to delete this applicator?');

                const hiddenId = document.createElement('input');
                hiddenId.type = 'hidden';
                hiddenId.name = 'applicator_id';
                hiddenId.value = row.applicator_id;
                deleteForm.appendChild(hiddenId);

                const deleteButton = document.createElement('button');
                deleteButton.type = 'submit';
                deleteButton.className = 'action-btn delete-btn';
                deleteButton.textContent = '🗑️';
                deleteForm.appendChild(deleteButton);

                actionsDiv.appendChild(deleteForm);
                tdActions.appendChild(actionsDiv);
                tr.appendChild(tdActions);

                tbody.appendChild(tr);
            });

            applicatorOffset += data.length;
            applicatorLoading = false;

            if (data.length < applicatorLimit) {
                const container = document.getElementById('applicators-table');
                if (container) container.removeEventListener('scroll', applicatorScrollHandler);
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
    const container = document.getElementById('applicators-table');
    if (!container) return;
    if (container.scrollTop + container.clientHeight >= container.scrollHeight - 5) {
        loadApplicators();
    }
}

// Initialize the applicator loading on page load
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('applicators-table');
    if (!container) return;
    container.addEventListener('scroll', applicatorScrollHandler);
    loadApplicators(); // Load initial data
});
