<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEPC - Admin Manage User</title>
    <link rel="icon" href="/SOMS/public/assets/images/favicon.ico">
    <link rel="stylesheet" href="../../public/assets/css/base/base.css">
    <link rel="stylesheet" href="../../public/assets/css/base/header.css">
    <link rel="stylesheet" href="../../public/assets/css/admin_manage_user.css">
    <link rel="stylesheet" href="../../public/assets/css/components/table.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/modal.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/header.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/tables.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/buttons.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/sidebar.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/layout/grid.css">
</head>
<body>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/SOMS/app/includes/sidebar.php'; ?>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/SOMS/app/includes/header.php'; ?>
    <div class="container">
        <div class="main-content">
            <div id="dashboard-tab" class="tab-content">
                <!-- Header -->
                <div class="page-header">
                    <h1 class="page-title">Manage Users</h1>
                    <div class="header-actions">    
                        <button type="button" class="btn btn-secondary" onclick="exportUsers()">
                            Export Report
                        </button>
                        <button type="button" class="btn btn-primary" onclick="refreshData()">
                            Refresh Data
                        </button>
                        <button type="button" class="btn btn-primary" onclick="openAddUserModal()">
                            + Add New User
                        </button>
                    </div>
                </div>

                <!-- Users Data Section -->
                <div class="data-section">
                    <div class="section-header expanded" onclick="toggleSection(this)">
                        <div class="section-title">
                            <span class="filter-info">Users Management</span>
                        </div>
                    </div>
                    
                    <div class="search-filter">
                        <div class="search-wrapper">
                            <input type="text" 
                                id="searchInput" 
                                class="search-input" 
                                placeholder="Search users..." 
                                onkeyup="searchUsers(this.value)">
                        </div>
                        
                        <select id="roleFilter" class="filter-select" onchange="filterByRole(this.value)">
                            <option value="all">All Roles</option>
                            <option value="Admin">Admin</option>
                            <option value="Moderator">Moderator</option>
                            <option value="User">User</option>
                        </select>

                    </div>
                    
                    <!-- Users Table -->
                    <?php 
                    require_once '../models/read_user.php'; 
                    $users = getUsers(10, 0);
                    // Debug: Log users array
                    error_log("Users in table: " . print_r($users, true));
                    ?>
                    
                    <div class="section-content expanded">
                        <div class="table-container">
                            <table class="data-table" id="usersTable">
                                <thead>
                                    <tr>
                                        <th>Actions</th>
                                        <th>User</th>
                                        <th>Role</th>
                                    </tr>
                                </thead>
                                <tbody id="usersTableBody">
                                    <?php if (empty($users)): ?>
                                        <tr>
                                            <td colspan="3">No users found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td>
                                                    <div class="actions">
                                                        <button class="view-btn" 
                                                                onclick="openViewUserModal(this)" 
                                                                title="View Details"
                                                                data-username="<?php echo htmlspecialchars($user['username'] ?? ''); ?>"
                                                                data-firstname="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>"
                                                                data-lastname="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>"
                                                                data-role="<?php echo htmlspecialchars($user['user_type'] ?? ''); ?>">
                                                            View
                                                        </button>
                                                        <button class="edit-btn" 
                                                                onclick="openEditUserModal(this)" 
                                                                title="Edit User"
                                                                data-id="<?php echo htmlspecialchars($user['user_id'] ?? ''); ?>"
                                                                data-username="<?php echo htmlspecialchars($user['username'] ?? ''); ?>"
                                                                data-firstname="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>"
                                                                data-lastname="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>"
                                                                data-role="<?php echo htmlspecialchars($user['user_type'] ?? ''); ?>">
                                                            Edit
                                                        </button>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="user-info">
                                                        <div class="user-avatar">👤</div>
                                                        <div class="user-details">
                                                            <div class="user-name"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></div>
                                                            <div class="user-email"><?php echo htmlspecialchars($user['username']); ?></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="role-badge admin"><?php echo htmlspecialchars($user['user_type'] ?? 'Unknown'); ?></span>
                                                </td>
                                                
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <!-- Pagination>
            <div class="pagination">
                <div class="pagination-info" id="paginationInfo">
                    Showing 1 to 1 of 1 users
                </div>
                <div class="pagination-controls">
                    <button id="prevBtn" class="pagination-btn" onclick="previousPage()" disabled>Previous</button>
                    <button id="nextBtn" class="pagination-btn" onclick="nextPage()" disabled>Next</button>
                </div>
            </div -->

    <!-- View User Modal -->
    <div id="viewUserModal" class="modal-overlay">
        <div class="form-container">
            <button class="modal-close-btn" onclick="closeViewUserModal()">×</button>
            
            <div class="form-header">
                <h1 class="form-title">
                    <span class="modal-avatar">👁️</span>
                    User Details
                </h1>
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
                        <label for="view_username" class="form-label">Username</label>
                        <input type="text" id="view_username" name="username" class="form-input" readonly autocomplete="off">
                    </div>
                    
                    <div class="form-group">
                        <label for="view_first_name" class="form-label">First Name</label>
                        <input type="text" id="view_first_name" name="first_name" class="form-input" readonly autocomplete="off">
                    </div>
                    
                    <div class="form-group">
                        <label for="view_last_name" class="form-label">Last Name</label>
                        <input type="text" id="view_last_name" name="last_name" class="form-input" readonly autocomplete="off">
                    </div>
                </div>
            </div>

            <!-- Account Information Section -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">⚙️</div>
                    <div class="section-info">
                        <div class="section-title">Account Information</div>
                        <div class="section-description">Role, credential</div>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="view_role" class="form-label">Role</label>
                        <div class="form-field">
                            <input type="text" id="view_role" name="role" class="form-input" readonly autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Close Button -->
            <div class="button-group">
                <button type="button" class="cancel-btn" onclick="closeViewUserModal()">Close</button>
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

            <form id="addUserForm" method="POST" action="../controllers/sign_up.php">
                <input type="hidden" name="admin_create_user" value="admin_create_user">

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
                            <input type="text" id="addUserName" name="username" class="form-input" placeholder="Enter user name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="addUserFirstName" class="form-label">
                                First Name
                                <span class="required-badge">Required</span>
                            </label>
                            <input type="text" id="addUserFirstName" name="firstname" class="form-input" placeholder="Enter first name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="addUserLastName" class="form-label">
                                Last Name
                                <span class="required-badge">Required</span>
                            </label>
                            <input type="text" id="addUserLastName" name="lastname" class="form-input" placeholder="Enter last name" required>
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
                                <option value="default">Default</option>
                                <option value="toolkeeper">Toolkeeper</option>
                                <option value="admin">Admin</option>
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

    <!-- Edit User Modal -->
    <div id="editUserModal" class="modal-overlay">
        <div class="form-container">
            <button class="modal-close-btn" onclick="closeEditUserModal()">×</button>
                
            <div class="form-header">
                <h1 class="form-title">
                    <span class="modal-avatar">✏️</span>
                    Edit User
                </h1>
                <p class="form-subtitle">Update user information and credentials</p>
            </div>

            <form id="editUserForm" method="POST" action="../controllers/edit_user.php">
                <input type="hidden" id="edit_user_id" name="user_id" value="">

                <!-- Personal Information Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">👤</div>
                        <div class="section-info">
                            <div class="section-title">Personal Information</div>
                            <div class="section-description">Update user information</div>
                        </div>
                    </div>

                    <div class="form-grid-vertical">
                        <div class="form-group">
                            <label for="editUserName" class="form-label">
                                Username
                                <span class="required-badge">Required</span>
                            </label>
                            <input type="text" id="edit_username" name="username" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="editUserFirstName" class="form-label">
                                First Name
                                <span class="required-badge">Required</span>
                            </label>
                            <input type="text" id="edit_first_name" name="first_name" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="editUserLastName" class="form-label">
                                Last Name
                                <span class="required-badge">Required</span>
                            </label>
                            <input type="text" id="edit_last_name" name="last_name" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="editUserPassword" class="form-label">
                                Password
                                <span class="required-badge">Required</span>
                            </label>
                            <input type="password" id="edit_password" name="password" class="form-input">
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
                            <select id="edit_role" name="role" class="form-input">
                                <option value="DEFAULT">Default</option>
                                <option value="TOOLKEEPER">Toolkeeper</option>
                                <option value="ADMIN">Admin</option>
                            </select>
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
    <script src="../../public/assets/js/admin_manage_user.js" defer></script>
    <script src="../../public/assets/js/sidebar.js"></script>
</body>
</html>