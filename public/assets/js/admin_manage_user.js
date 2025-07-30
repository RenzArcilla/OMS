const initialUsers = [
    {
        id: 1,
        name: 'Sarah Chen',
        email: 'sarah.chen@company.com',
        role: 'Admin',
        status: 'Active',
        lastLogin: '2025-01-29T10:30:00',
        joinDate: '2024-03-15T09:00:00',
        filesUploaded: 45,
        avatar: '👩‍💼',
        phone: '+1-555-0101',
        department: 'Engineering'
    },
];

let users = [...initialUsers];
let filteredUsers = [...users];
let currentPage = 1;
let usersPerPage = 10;
let currentModalMode = 'view';
let currentUser = null;
let nextId = 7;

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    applyFilters();
});

function setupEventListeners() {
    // Search input with debounce
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            applyFilters();
        }, 300);
    });

    // Filter dropdowns
    document.getElementById('roleFilter').addEventListener('change', applyFilters);
    document.getElementById('statusFilter').addEventListener('change', applyFilters);

    // Modal close on outside click
    document.getElementById('userModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // ESC key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
}

function applyFilters() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const roleFilter = document.getElementById('roleFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;

    filteredUsers = users.filter(user => {
        const matchesSearch = searchTerm === '' || 
            user.name.toLowerCase().includes(searchTerm) || 
            user.email.toLowerCase().includes(searchTerm) ||
            user.department.toLowerCase().includes(searchTerm);
        const matchesRole = roleFilter === 'all' || user.role === roleFilter;
        const matchesStatus = statusFilter === 'all' || user.status === statusFilter;
        
        return matchesSearch && matchesRole && matchesStatus;
    });

    currentPage = 1;
    renderUsers();
}

function renderUsers() {
    const tbody = document.getElementById('usersTableBody');
    const startIndex = (currentPage - 1) * usersPerPage;
    const endIndex = startIndex + usersPerPage;
    const currentUsers = filteredUsers.slice(startIndex, endIndex);

    if (currentUsers.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="no-users">
                    <div class="no-users-icon">👥</div>
                    <div>No users found</div>
                </td>
            </tr>
        `;
    } else {
        tbody.innerHTML = currentUsers.map(user => `
            <tr>
                <td>
                    <div class="user-info">
                        <div class="user-avatar">${user.avatar}</div>
                        <div>
                            <div class="user-name">${escapeHtml(user.name)}</div>
                            <div class="user-email">${escapeHtml(user.email)}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="role-info">
                        <span class="role-icon">${getRoleIcon(user.role)}</span>
                        <span>${escapeHtml(user.role)}</span>
                    </div>
                </td>
                <td><span class="status-badge status-${user.status.toLowerCase()}">${user.status}</span></td>
                <td>${formatDateTime(user.lastLogin)}</td>
                <td>${user.filesUploaded}</td>
                <td>
                    <div class="actions">
                        <button class="action-btn view-btn" onclick="viewUser(${user.id})" title="View Details">👁️</button>
                        <button class="action-btn edit-btn" onclick="editUser(${user.id})" title="Edit User">✏️</button>
                        ${user.status === 'Suspended' ? 
                            `<button class="action-btn activate-btn" onclick="updateUserStatus(${user.id}, 'Active')" title="Activate User">✅</button>` :
                            user.status === 'Active' ? 
                            `<button class="action-btn suspend-btn" onclick="updateUserStatus(${user.id}, 'Suspended')" title="Suspend User">⚠️</button>` : ''
                        }
                        <button class="action-btn delete-btn" onclick="deleteUser(${user.id})" title="Delete User">🗑️</button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    updatePagination();
}

function updatePagination() {
    const totalPages = Math.ceil(filteredUsers.length / usersPerPage);
    const startIndex = filteredUsers.length === 0 ? 0 : (currentPage - 1) * usersPerPage + 1;
    const endIndex = Math.min(currentPage * usersPerPage, filteredUsers.length);

    document.getElementById('paginationInfo').textContent = 
        `Showing ${startIndex} to ${endIndex} of ${filteredUsers.length} users`;

    document.getElementById('prevBtn').disabled = currentPage === 1;
    document.getElementById('nextBtn').disabled = currentPage === totalPages || totalPages === 0;
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        renderUsers();
    }
}

function nextPage() {
    const totalPages = Math.ceil(filteredUsers.length / usersPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderUsers();
    }
}

function openModal(mode, userId = null) {
    currentModalMode = mode;
    const modal = document.getElementById('userModal');
    const modalTitle = document.getElementById('modalTitleText');
    const modalActionBtn = document.getElementById('modalActionBtn');
    
    // Reset form fields
    resetModalForm();
    
    if (mode === 'create') {
        modalTitle.textContent = 'Create New User';
        modalActionBtn.style.display = 'block';
        modalActionBtn.textContent = 'Create User';
        enableFormInputs();
        document.getElementById('modalAvatar').textContent = '👤';
    } else if (userId) {
        currentUser = users.find(u => u.id === userId);
        if (currentUser) {
            populateModal(currentUser);
            
            if (mode === 'view') {
                modalTitle.textContent = 'User Details';
                modalActionBtn.style.display = 'none';
                disableFormInputs();
            } else if (mode === 'edit') {
                modalTitle.textContent = 'Edit User';
                modalActionBtn.style.display = 'block';
                modalActionBtn.textContent = 'Save Changes';
                enableFormInputs();
            }
        }
    }
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modal = document.getElementById('userModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    currentUser = null;
}

function resetModalForm() {
    document.getElementById('userName').value = '';
    document.getElementById('userEmail').value = '';
    document.getElementById('userPhone').value = '';
    document.getElementById('userDepartment').value = '';
    document.getElementById('userRole').value = 'User';
    document.getElementById('userStatus').value = 'Active';
    document.getElementById('userJoinDate').textContent = '-';
    document.getElementById('userLastLogin').textContent = '-';
    document.getElementById('userFilesUploaded').textContent = '0';
    document.getElementById('userDaysActive').textContent = '0';
}

function populateModal(user) {
    document.getElementById('modalAvatar').textContent = user.avatar;
    document.getElementById('userName').value = user.name;
    document.getElementById('userEmail').value = user.email;
    document.getElementById('userPhone').value = user.phone;
    document.getElementById('userDepartment').value = user.department;
    document.getElementById('userRole').value = user.role;
    document.getElementById('userStatus').value = user.status;
    document.getElementById('userJoinDate').textContent = formatDate(user.joinDate);
    document.getElementById('userLastLogin').textContent = formatDateTime(user.lastLogin);
    document.getElementById('userFilesUploaded').textContent = user.filesUploaded;
    
    // Calculate days active
    const joinDate = new Date(user.joinDate);
    const today = new Date();
    const daysActive = Math.floor((today - joinDate) / (1000 * 60 * 60 * 24));
    document.getElementById('userDaysActive').textContent = daysActive;
}

function enableFormInputs() {
    document.getElementById('userName').readOnly = false;
    document.getElementById('userEmail').readOnly = false;
    document.getElementById('userPhone').readOnly = false;
    document.getElementById('userDepartment').readOnly = false;
    document.getElementById('userRole').disabled = false;
    document.getElementById('userStatus').disabled = false;
}

function disableFormInputs() {
    document.getElementById('userName').readOnly = true;
    document.getElementById('userEmail').readOnly = true;
    document.getElementById('userPhone').readOnly = true;
    document.getElementById('userDepartment').readOnly = true;
    document.getElementById('userRole').disabled = true;
    document.getElementById('userStatus').disabled = true;
}

function viewUser(userId) {
    openModal('view', userId);
}

function editUser(userId) {
    openModal('edit', userId);
}

function saveUser() {
    const name = document.getElementById('userName').value.trim();
    const email = document.getElementById('userEmail').value.trim();
    const phone = document.getElementById('userPhone').value.trim();
    const department = document.getElementById('userDepartment').value.trim();
    const role = document.getElementById('userRole').value;
    const status = document.getElementById('userStatus').value;

    // Basic validation
    if (!name || !email || !phone || !department) {
        alert('Please fill in all required fields.');
        return;
    }

    if (!isValidEmail(email)) {
        alert('Please enter a valid email address.');
        return;
    }

    if (currentModalMode === 'create') {
        // Check if email already exists
        if (users.some(u => u.email.toLowerCase() === email.toLowerCase())) {
            alert('A user with this email already exists.');
            return;
        }

        // Create new user
        const newUser = {
            id: nextId++,
            name: name,
            email: email,
            phone: phone,
            department: department,
            role: role,
            status: status,
            joinDate: new Date().toISOString(),
            lastLogin: null,
            filesUploaded: 0,
            avatar: getRandomAvatar()
        };

        users.push(newUser);
        alert('User created successfully!');
    } else if (currentModalMode === 'edit' && currentUser) {
        // Check if email exists for other users
        if (users.some(u => u.id !== currentUser.id && u.email.toLowerCase() === email.toLowerCase())) {
            alert('A user with this email already exists.');
            return;
        }

        // Update existing user
        const userIndex = users.findIndex(u => u.id === currentUser.id);
        if (userIndex !== -1) {
            users[userIndex] = {
                ...users[userIndex],
                name: name,
                email: email,
                phone: phone,
                department: department,
                role: role,
                status: status
            };
        }
        alert('User updated successfully!');
    }

    closeModal();
    applyFilters();
}

function deleteUser(userId) {
    const user = users.find(u => u.id === userId);
    if (user && confirm(`Are you sure you want to delete ${user.name}?\n\nThis action cannot be undone.`)) {
        users = users.filter(u => u.id !== userId);
        alert('User deleted successfully!');
        applyFilters();
    }
}

function updateUserStatus(userId, newStatus) {
    const user = users.find(u => u.id === userId);
    if (user) {
        const action = newStatus === 'Active' ? 'activate' : 'suspend';
        if (confirm(`Are you sure you want to ${action} ${user.name}?`)) {
            const userIndex = users.findIndex(u => u.id === userId);
            if (userIndex !== -1) {
                users[userIndex].status = newStatus;
                alert(`User ${action}d successfully!`);
                applyFilters();
            }
        }
    }
}

function exportUsers() {
    if (filteredUsers.length === 0) {
        alert('No users to export.');
        return;
    }

    // Create CSV content
    const headers = ['Name', 'Email', 'Role', 'Status', 'Department', 'Phone', 'Join Date', 'Last Login', 'Files Uploaded'];
    const csvContent = [
        headers.join(','),
        ...filteredUsers.map(user => [
            `"${user.name}"`,
            `"${user.email}"`,
            `"${user.role}"`,
            `"${user.status}"`,
            `"${user.department}"`,
            `"${user.phone}"`,
            `"${formatDate(user.joinDate)}"`,
            `"${formatDateTime(user.lastLogin) || 'Never'}"`,
            user.filesUploaded
        ].join(','))
    ].join('\n');

    // Create and download file
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.setAttribute('hidden', '');
    a.setAttribute('href', url);
    a.setAttribute('download', `users_export_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
    
    alert(`Exported ${filteredUsers.length} users to CSV file.`);
}

function refreshData() {
    // Reset users to initial state
    users = [...initialUsers];
    nextId = 7;
    
    // Reset filters
    document.getElementById('searchInput').value = '';
    document.getElementById('roleFilter').value = 'all';
    document.getElementById('statusFilter').value = 'all';
    
    // Reapply filters and render
    applyFilters();
    alert('Data refreshed successfully!');
}

// Helper functions
function getRoleIcon(role) {
    switch (role) {
        case 'Admin': return '👑';
        case 'Moderator': return '🛡️';
        default: return '🔰';
    }
}

function formatDate(dateString) {
    if (!dateString) return 'Never';
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function formatDateTime(dateString) {
    if (!dateString) return 'Never';
    return new Date(dateString).toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function getRandomAvatar() {
    const avatars = ['👨‍💼', '👩‍💼', '👨‍💻', '👩‍💻', '👨‍🎨', '👩‍🎨', '👨‍🔬', '👩‍🔬', '👨‍🚀', '👩‍🚀', '👨‍📊', '👩‍📊'];
    return avatars[Math.floor(Math.random() * avatars.length)];
}