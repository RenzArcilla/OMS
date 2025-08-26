// Open the add user modal
function openAddUserModal() {
    document.getElementById("addUserModal").style.display = "block";
}

// Close the add user modal
function closeAddUserModal() {
    document.getElementById('addUserModal').style.display = 'none';
}

// Open the view user modal
function openViewUserModal(button) {
    document.getElementById('view_username').value = button.dataset.username;
    document.getElementById('view_first_name').value = button.dataset.firstname;
    document.getElementById('view_last_name').value = button.dataset.lastname;
    document.getElementById('view_role').value = button.dataset.role;

    document.getElementById("viewUserModal").style.display = "block";
}

function closeViewUserModal() {
    document.getElementById('viewUserModal').style.display = 'none';
}

// Open the edit user modal
function openEditUserModal() {
    document.getElementById("editUserModal").style.display = "block";
}

function closeEditUserModal() {
    document.getElementById('editUserModal').style.display = 'none';
}

// Refresh the page
function refreshData() {
    // Add loading state
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '⏳ Refreshing...';
    btn.disabled = true;
    
    // Add a small delay to show the loading state
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}
