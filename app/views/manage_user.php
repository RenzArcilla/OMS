<?php
// Require admin privileges
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEPC - Admin Manage User</title>
    <link rel="icon" href="/OMS/public/assets/images/favicon.ico">
    <link rel="stylesheet" href="../../public/assets/css/base/base.css">
    <link rel="stylesheet" href="../../public/assets/css/base/header.css">
    <link rel="stylesheet" href="../../public/assets/css/admin_manage_user.css">
    <link rel="stylesheet" href="../../public/assets/css/components/table.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/modal.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/header.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/tables.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/buttons.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/sidebar.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/layout/grid.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/export_modal.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/search_filter.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/info.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/pagination.css">
</head>
<body>
    <?php 
        // Include necessary files
        include_once $_SERVER['DOCUMENT_ROOT'] . '/OMS/app/includes/sidebar.php';
        include_once $_SERVER['DOCUMENT_ROOT'] . '/OMS/app/includes/header.php';

        // Require admin priveledge to view this page 
        require_once __DIR__ . '/../includes/auth.php';
        //requireAdmin(); 
    ?>
    <div class="container">
        <div class="main-content">
            <div id="dashboard-tab" class="tab-content">
                <!-- Header -->
                <div class="page-header">
                    <h1 class="page-title">Manage Users</h1>
                    <div class="header-actions">    
                        <button type="button" class="btn btn-secondary" onclick="exportData()">
                            Export Report
                        </button>
                        <button type="button" class="btn btn-primary" onclick="refreshPage(this)">
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
                    
                    <div class="search-filter" style="display: flex; gap: 10px; align-items: center;">
                        <input 
                            type="text" 
                            class="search-input" 
                            id="userSearchInput"
                            placeholder="Search here..." 
                            autocomplete="off"
                        >
                        <select 
                            id="roleFilter" 
                            class="filter-select" 
                            onchange="applyUserFilters()"
                            required
                        >
                            <option value="all">All Roles</option>
                            <option value="DEFAULT">Default</option>
                            <option value="TOOLKEEPER">Toolkeeper</option>
                            <option value="ADMIN">Admin</option>
                        </select>
                        <button 
                            type="button" 
                            class="tab-btn" 
                            id="refreshUserTableBtn"
                            onclick="refreshData()"
                        >Refresh</button>
                    </div>
                    <!-- Users Table -->
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
                                    <tr><td colspan="3">Loading users...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="pagination-container" id="usersPaginationContainer"></div>
                </div>
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
        <!-- Export Modal -->
    <div id="exportModal" class="modal-overlay">
        <div class="form-container">
            <button class="modal-close-btn" onclick="closeExportModal()">×</button>
            
            <div class="form-header">
                <h1 class="form-title">Export Data</h1>
                <p style="font-size: 14px; color: #6B7280;">Choose your export format and options</p>
            </div>

            <!-- Export Format Section -->
            <div class="form-section">
                <div class="info-section">
                    <div style="display: flex; align-items: flex-start; gap: 8px;">
                        <span class="info-icon">ℹ️</span>
                        <div>
                            <strong>Export Information</strong>
                            <p>The report will include all current users. The data will be exported in Excel format.</p>
                        </div>
                    </div>
                </div>

            <!-- Date Range Section -->
                <div class="section-header">
                    <div class="section-icon">📅</div>
                    <div class="section-info">
                        <div class="section-title">Date Range</div>
                        <div class="section-description">Select the time period for your export</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <select id="dateRange" class="form-select">
                        <option value="all">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">Last 7 Days</option>
                        <option value="month">Last 30 Days</option>
                        <option value="quarter">Last 3 Months</option>
                        <option value="year">Last Year</option>
                        <option value="custom">Custom Date Range</option>
                    </select>
                </div>

                <div id="customDates" class="date-inputs hidden">
                    <div class="form-group">
                        <label class="form-label" style="font-size: 12px; color: #6B7280;">Start Date</label>
                        <input type="date" id="startDate" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-size: 12px; color: #6B7280;">End Date</label>
                        <input type="date" id="endDate" class="form-input">
                    </div>
                </div>
            </div>

            <!-- Additional Options Section -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">⚙️</div>
                    <div class="section-info">
                        <div class="section-title">Additional Options</div>
                        <div class="section-description">Configure export settings</div>
                    </div>
                </div>
                
                <div class="checkbox-group">
                    <label class="checkbox-item">
                        <input type="checkbox" id="includeHeaders" class="checkbox-input" checked>
                        <span class="checkbox-label">Include column headers</span>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="button-group">
                <button type="button" class="cancel-btn" onclick="closeExportModal()">Cancel</button>
                <button type="button" class="export-btn" onclick="handleExport()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7,10 12,15 17,10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Export Data
                </button>
            </div>
        </div>
    </div>
    <script src="../../public/assets/js/admin_manage_user.js" defer></script>
    <script src="../../public/assets/js/sidebar.js"></script>
</body>
</html>