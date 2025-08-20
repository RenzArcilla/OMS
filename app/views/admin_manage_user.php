<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEPC - Admin Manage User</title>
    <link rel="stylesheet" href="../../public/assets/css/base/base.css">
    <link rel="stylesheet" href="../../public/assets/css/admin_manage_user.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/modal.css">

</head>
<body>
    <?php include '../includes/side_bar.php'; ?>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <button class="back-btn" onclick="window.history.back()">
                    ←
                </button>
                <div>
                    <h1 class="title">Manage Users</h1>
                    <p class="subtitle">Manage user accounts, permissions, and access controls</p>
                </div>
            </div>
            <button class="add-user-btn" onclick="openAddUserModal()">
                + Add New User
            </button>
        </div>

        <!-- Filters -->
        <div class="filters-card">
            <div class="filters-grid">
                <div class="search-wrapper">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInput" placeholder="Search users..." class="search-input">
                </div>
                
                <select id="roleFilter" class="filter-select">
                    <option value="all">All Roles</option>
                    <option value="Admin">Admin</option>
                    <option value="Moderator">Moderator</option>
                    <option value="User">User</option>
                </select>
                
                <select id="statusFilter" class="filter-select">
                    <option value="all">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Pending">Pending</option>
                    <option value="Suspended">Suspended</option>
                </select>
                
                <button type="button" class="btn-secondary" onclick="exportUsers()">📥 Export</button>
                <button type="button" class="btn-secondary" onclick="refreshData()">🔄 Refresh</button>
            </div>
        </div>

        <!-- Users Table -->
        <div class="users-table-card">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">👤</div>
                                <div class="user-details">
                                    <div class="user-name">John Smith</div>
                                    <div class="user-email">john.smith@hepc.com</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="role-badge admin">Admin</span>
                        </td>
                        <td>
                            <span class="status-badge active">Active</span>
                        </td>
                        <td>2024-12-15 14:30</td>
                        <td>
                            <div class="action-buttons">
                                <button class="action-btn view-btn" onclick="openViewUserModal()" title="View Details">
                                    👁️
                                </button>
                                <button class="action-btn edit-btn" onclick="openEditUserModal()" title="Edit User">
                                    ✏️
                                </button>
                                <button class="action-btn delete-btn" onclick="openModal()" title="Delete User">
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination">
                <div class="pagination-info" id="paginationInfo">
                    Showing 1 to 1 of 1 users
                </div>
                <div class="pagination-controls">
                    <button id="prevBtn" class="pagination-btn" onclick="previousPage()" disabled>Previous</button>
                    <button id="nextBtn" class="pagination-btn" onclick="nextPage()" disabled>Next</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="modal-overlay">
        <div class="form-container">
            <button class="modal-close-btn" onclick="closeAddUserModal()">×</button>
            
            <div class="form-header">
                <h1 class="form-title">
                    <span class="modal-avatar">👤</span>
                    Add New User
                </h1>
                <p class="form-subtitle">Enter user information to create a new account</p>
            </div>

            <form id="addUserForm" onsubmit="addUser(event)">
                <!-- Personal Information Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">👤</div>
                        <div class="section-info">
                            <div class="section-title">Personal Information</div>
                            <div class="section-description">Basic user details and contact information</div>
                        </div>
                    </div>

                    <div class="form-grid-vertical">
                        <div class="form-group">
                            <label for="addUserName" class="form-label">
                                Username
                                <span class="required-badge">Required</span>
                            </label>
                            <input type="text" id="addUserName" name="name" class="form-input" placeholder="Enter user name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="addUserFirstName" class="form-label">
                                First Name
                                <span class="required-badge">Required</span>
                            </label>
                            <input type="text" id="addUserFirstName" name="first_name" class="form-input" placeholder="Enter first name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="addUserLastName" class="form-label">
                                Last Name
                                <span class="required-badge">Required</span>
                            </label>
                            <input type="text" id="addUserLastName" name="last_name" class="form-input" placeholder="Enter last name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="addPassword" class="form-label">
                                Password
                                <span class="required-badge">Required</span>
                            </label>
                            <input type="password" id="addPassword" name="password" class="form-input" placeholder="Enter password" required>
                        </div>
                    </div>
                </div>

                <!-- Account Settings Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">⚙️</div>
                        <div class="section-info">
                            <div class="section-title">Account Settings</div>
                            <div class="section-description">User role and account status configuration</div>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="addUserRole" class="form-label">User Role</label>
                            <select id="addUserRole" name="role" class="form-input">
                                <option value="User">User</option>
                                <option value="Moderator">Moderator</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="addUserStatus" class="form-label">Account Status</label>
                            <select id="addUserStatus" name="status" class="form-input">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Pending">Pending</option>
                                <option value="Suspended">Suspended</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="button-group">
                    <button type="button" class="cancel-btn" onclick="closeAddUserModal()">
                        Cancel
                    </button>
                    <button type="submit" class="submit-btn">
                        ✨ Add User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View User Modal -->
    <div id="viewUserModal" class="modal-overlay">
        <div class="form-container">
            <button class="modal-close-btn" onclick="closeViewUserModal()">×</button>
            
            <div class="form-header">
                <h1 class="form-title">
                    <span class="modal-avatar">👁️</span>
                    User Details
                </h1>
                <p class="form-subtitle">Complete user information and activity summary</p>
            </div>

            <!-- Personal Information Section -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">👤</div>
                    <div class="section-info">
                        <div class="section-title">Personal Information</div>
                        <div class="section-description">User contact details and basic information</div>
                    </div>
                </div>

                <div class="form-grid-vertical">
                    <div class="form-group">
                        <label for="viewUserName" class="form-label">
                            Username
                        </label>
                        <input type="text" id="viewUserName" name="name" class="form-input" placeholder="RenzngOdnum" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="viewUserFirstName" class="form-label">
                            First Name
                        </label>
                        <input type="text" id="viewUserFirstName" name="first_name" class="form-input" placeholder="Renz" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="viewUserLastName" class="form-label">
                            Last Name
                        </label>
                        <input type="text" id="viewUserLastName" name="last_name" class="form-input" placeholder="Ng Mundo" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="viewUserPassword" class="form-label">
                            Password
                        </label>
                        <input type="password" id="viewUserPassword" name="password" class="form-input" placeholder="Hatdog123" readonly>
                    </div>
                </div>
            </div>

            <!-- Account Information Section -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">⚙️</div>
                    <div class="section-info">
                        <div class="section-title">Account Information</div>
                        <div class="section-description">Role, status, and account timeline</div>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <div class="form-field">
                            <span class="field-icon">🎭</span>
                            <span id="viewUserRole">-</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <div class="form-field">
                            <span class="field-icon">🟢</span>
                            <span id="viewUserStatus">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Summary -->
            <div class="activity-section">
                <h3 class="activity-title">📊 Activity Summary</h3>
                <div class="activity-stats">
                    <div class="activity-stat">
                        <div id="viewUserFilesUploaded" class="stat-number">0</div>
                        <div class="stat-label">Files Uploaded</div>
                    </div>
                    <div class="activity-stat">
                        <div id="viewUserDaysActive" class="stat-number">0</div>
                        <div class="stat-label">Days Active</div>
                    </div>
                    <div class="activity-stat">
                        <div class="stat-number">5</div>
                        <div class="stat-label">Login Sessions</div>
                    </div>
                </div>
            </div>

            <!-- Close Button -->
            <div class="button-group">
                <button type="button" class="cancel-btn" onclick="closeViewUserModal()">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="modal-overlay">
        <div class="form-container">
                <button class="modal-close-btn" onclick="closeEditUserModal()">×</button>
                
            <div class="form-header">
                <h1 class="form-title">
                    <span class="modal-avatar">✏️</span>
                    Edit User
                </h1>
                <p class="form-subtitle">Update user information and account settings</p>
            </div>

            <form id="editUserForm" onsubmit="saveUser(event)">
                <!-- Personal Information Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">👤</div>
                        <div class="section-info">
                            <div class="section-title">Personal Information</div>
                            <div class="section-description">Update user contact details and basic information</div>
                        </div>
                    </div>

                    <div class="form-grid-vertical">
                        <div class="form-group">
                            <label for="editUserName" class="form-label">
                                Username
                                <span class="required-badge">Required</span>
                            </label>
                            <input type="text" id="editUserName" name="name" class="form-input" placeholder="RenzngOdnum" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="editUserFirstName" class="form-label">
                                First Name
                                <span class="required-badge">Required</span>
                            </label>
                            <input type="text" id="editUserFirstName" name="first_name" class="form-input" placeholder="Renz" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="editUserLastName" class="form-label">
                                Last Name
                                <span class="required-badge">Required</span>
                            </label>
                            <input type="text" id="editUserLastName" name="last_name" class="form-input" placeholder="Ng Mundo" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="editUserPassword" class="form-label">
                                Password
                                <span class="required-badge">Required</span>
                            </label>
                            <input type="password" id="editUserPassword" name="password" class="form-input" placeholder="Hatdog123" required>
                        </div>
                    </div>
                </div>

                <!-- Account Settings Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">⚙️</div>
                        <div class="section-info">
                            <div class="section-title">Account Settings</div>
                            <div class="section-description">Modify user role and account status</div>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="editUserRole" class="form-label">User Role</label>
                            <select id="editUserRole" name="role" class="form-input">
                                <option value="User">User</option>
                                <option value="Moderator">Moderator</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="editUserStatus" class="form-label">Account Status</label>
                            <select id="editUserStatus" name="status" class="form-input">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Pending">Pending</option>
                                <option value="Suspended">Suspended</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Activity Summary -->
                <div class="activity-section">
                    <h3 class="activity-title">📊 Activity Summary</h3>
                    <div class="activity-stats">
                        <div class="activity-stat">
                            <div id="editUserFilesUploaded" class="stat-number">0</div>
                            <div class="stat-label">Files Uploaded</div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="button-group">
                    <button type="button" class="cancel-btn" onclick="closeEditUserModal()">
                        Cancel
                    </button>
                    <button type="submit" class="submit-btn">
                        💾 Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="../../public/assets/js/admin_manage_user.js"></script>
</body>
</html>