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
    document.getElementById('view_username').value = button.dataset.username || '';
    document.getElementById('view_first_name').value = button.dataset.firstname || '';
    document.getElementById('view_last_name').value = button.dataset.lastname || '';
    document.getElementById('view_role').value = button.dataset.role || '';

    document.getElementById("viewUserModal").style.display = "block";
}

// Close the view user modal
function closeViewUserModal() {
    document.getElementById('view_username').value = '';
    document.getElementById('view_first_name').value = '';
    document.getElementById('view_last_name').value = '';
    document.getElementById('view_role').value = '';
    document.getElementById('viewUserModal').style.display = 'none';
}

// Open the edit user modal
function openEditUserModal(button) {
    document.getElementById('edit_username').value = button.dataset.username || '';
    document.getElementById('edit_first_name').value = button.dataset.firstname || '';
    document.getElementById('edit_last_name').value = button.dataset.lastname || '';
    document.getElementById('edit_role').value = button.dataset.role || '';

    document.getElementById("editUserModal").style.display = "block";
}

// Close the edit user modal
function closeEditUserModal() {
    document.getElementById('edit_username').value = '';
    document.getElementById('edit_first_name').value = '';
    document.getElementById('edit_last_name').value = '';
    document.getElementById('edit_role').value = '';
    document.getElementById('editUserModal').style.display = 'none';
}

// Refresh the page
function refreshData() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '⏳ Refreshing...';
    btn.disabled = true;
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}
